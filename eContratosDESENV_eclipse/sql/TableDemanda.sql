ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table demanda;
CREATE TABLE demanda (
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
	dem_tp_contrato VARCHAR(100),
    dem_tp_temreajustemontanteA CHAR(1) NULL, -- so valera para o tipo de demanda de reajuste
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
    dem_dtreferencia DATE,
    dem_cdpessoaresp_atja INT,
    -- dem_prt VARCHAR(25),
    dem_inlegado CHAR(1) NOT NULL DEFAULT 'N',
    	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd)
);

-- ALTER TABLE demanda_contrato DROP COLUMN dem_tp_contrato;
ALTER TABLE demanda drop COLUMN dem_prt;
ALTER TABLE demanda ADD COLUMN dem_inlegado CHAR(1) NOT NULL DEFAULT 'S' AFTER dem_dtreferencia;
ALTER TABLE demanda ADD COLUMN dem_tp_contrato VARCHAR(100) AFTER dem_tipo;
ALTER TABLE demanda ADD COLUMN dem_tp_temreajustemontanteA CHAR(1) AFTER dem_tp_contrato;
ALTER TABLE demanda ADD COLUMN in_desativado CHAR(1) NOT NULL DEFAULT 'N' AFTER cd_usuario_ultalt;
ALTER TABLE demanda ADD COLUMN dem_cdpessoaresp_atja INT AFTER dem_dtreferencia;

select dem_tipo from demanda where dem_tp_contrato is null group by dem_tipo;

UPDATE demanda SET dem_tp_contrato = dem_tipo
where  dem_tipo in (1,5,6,7,8,10);

UPDATE demanda SET dem_tp_contrato = CONCAT('0',dem_tipo)
where  dem_tipo in (1,5,6,7,8);

UPDATE demanda SET dem_tipo = 1
where  dem_tipo in (1,5,6,7,8,10);

-- ALTER TABLE demanda CHANGE dem_cd dem_cd INT AUTO_INCREMENT;
-- ALTER TABLE demanda AUTO_INCREMENT=100;
    
-- update demanda set dem_dtreferencia = DATE(dh_inclusao);

drop table demanda_hist;
CREATE TABLE demanda_hist (
    hist INT NOT NULL AUTO_INCREMENT,
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
	dem_tp_contrato VARCHAR(100),
    dem_tp_temreajustemontanteA CHAR(1) NULL, -- so valera para o tipo de demanda de reajuste
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
    dem_dtreferencia DATE,
    dem_cdpessoaresp_atja INT,
    -- dem_prt VARCHAR(25),
    dem_inlegado CHAR(1) NOT NULL DEFAULT 'N',
	    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    in_desativado CHAR(1),
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,
    
    CONSTRAINT pk PRIMARY KEY (hist),
    CONSTRAINT desativacao_demanda CHECK (in_desativado NOT IN ('S'))
);
ALTER TABLE demanda_hist drop COLUMN dem_prt;
ALTER TABLE demanda_hist ADD COLUMN dem_inlegado CHAR(1) NOT NULL DEFAULT 'S' AFTER dem_dtreferencia;
ALTER TABLE demanda_hist ADD COLUMN dem_tp_contrato VARCHAR(100) AFTER dem_tipo;
ALTER TABLE demanda_hist ADD COLUMN dem_tp_temreajustemontanteA CHAR(1) AFTER dem_tp_contrato;
ALTER TABLE demanda_hist ADD COLUMN in_desativado CHAR(1) NOT NULL AFTER cd_usuario_ultalt;
ALTER TABLE demanda_hist ADD CONSTRAINT desativacao_demanda CHECK (in_desativado NOT IN ('S'))
ALTER TABLE demanda_hist ADD COLUMN dem_cdpessoaresp_atja INT AFTER dem_dtreferencia;

drop table demanda_tram;
CREATE TABLE demanda_tram (
	dtm_sq INT NOT NULL,
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    
    dtm_cd_setor_origem INT NOT NULL,    
    dtm_cd_setor_destino INT NOT NULL,
    dtm_texto MEDIUMTEXT NOT NULL,
    dtm_prt VARCHAR(25),
    dtm_dtreferencia DATE,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dtm_sq, dem_ex, dem_cd)
);
-- update demanda_tram set dtm_dtreferencia = DATE(dh_inclusao);

ALTER TABLE demanda_tram ADD CONSTRAINT fk_demanda_tram FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

-- select length(dtm_prt) from demanda_tram
-- group by length(dtm_prt)
ALTER TABLE demanda_tram ADD UNIQUE KEY chave_logica_prt (dtm_prt);
ALTER TABLE demanda_tram DROP INDEX chave_logica_prt;


UPDATE demanda_tram SET
dtm_prt = replace(replace(dtm_prt,'.',''),'-','')
-- where dem_ex = 2015 and dem_cd = 1 and dtm_sq = 1
;

drop table demanda_contrato;
CREATE TABLE demanda_contrato (	
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_sq_especie INT NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)    
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie)
);
ALTER TABLE demanda_contrato ADD CONSTRAINT fk_demanda_contrato FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
drop table demanda_pl;
CREATE TABLE demanda_pl(	
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,
	pl_mod_cd varchar(4) NOT NULL, -- Modalidade/identificacao do certame
    
    pl_ex INT NOT NULL, 
    pl_cd INT NOT NULL,     

    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, pl_ex, pl_cd, pl_mod_cd)/*,
    CONSTRAINT fk_demanda_pl_pl FOREIGN KEY (pl_ex, pl_cd) REFERENCES proc_licitatorio (pl_ex, pl_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT*/
);
ALTER TABLE demanda_pl ADD CONSTRAINT fk_demanda_pl_pl FOREIGN KEY (pl_ex, pl_cd) REFERENCES proc_licitatorio (pl_ex, pl_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
ALTER TABLE demanda_pl MODIFY pl_mod_cd varchar(4) NOT NULL;    
-- ALTER TABLE demanda_pl ADD COLUMN pl_mod_cd char(2) NOT NULL DEFAULT 'PE' AFTER dem_cd;
-- ALTER TABLE demanda_pl DROP PRIMARY KEY;
-- ALTER TABLE demanda_pl ADD PRIMARY KEY pk (dem_ex, dem_cd, pl_ex, pl_cd, pl_mod_cd);


ALTER TABLE demanda_pl DROP FOREIGN KEY fk_demanda_pl_pl;

    
drop table demanda_doc;
CREATE TABLE demanda_doc (
	dtm_sq INT NOT NULL,
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,
    
	doc_sq INT NOT NULL,
    doc_cd_setor INT NOT NULL, 
    doc_ex INT NOT NULL,
    doc_tp CHAR(2) NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, dtm_sq, doc_ex, doc_cd_setor, doc_tp, doc_sq)
);
ALTER TABLE demanda_doc ADD CONSTRAINT fk_demanda_doc FOREIGN KEY (doc_ex, doc_cd_setor, doc_tp, doc_sq) REFERENCES documento (doc_ex, doc_cd_setor, doc_tp, sq) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

drop table if exists demanda_solic_compra;
CREATE TABLE demanda_solic_compra(	
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,

    solic_ex INT NOT NULL,     	    
    solic_cd INT NOT NULL,
    solic_ug INT NOT NULL,

    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, solic_ex, solic_cd, solic_ug)/*,
    CONSTRAINT fk_demanda_pl_pl FOREIGN KEY (pl_ex, pl_cd) REFERENCES proc_licitatorio (pl_ex, pl_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT*/
);
select * from demanda_solic_compra

/** INCLUSAO DEMANDAS */
DELIMITER $$
DROP PROCEDURE IF EXISTS recuperarDemandas $$
CREATE PROCEDURE recuperarDemandas()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE ano_demanda INT;
  DECLARE cd_demanda INT;
  DECLARE ex_contrato INT;
  DECLARE num_contrato INT;
  DECLARE tipo_contrato CHAR(1);

  DECLARE cTabela CURSOR FOR 
		select DISTINCT dem_ex, dem_cd,ct_exercicio,ct_numero,ct_tipo from demanda_contrato
		inner join contrato
		on contrato.sq = demanda_contrato.ct_sq;
	
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
  
  -- DELETE FROM pessoa_vinculo WHERE vi_cd = 2;
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO ano_demanda,cd_demanda,ex_contrato,num_contrato,tipo_contrato;
		IF NOT done THEN
        	
		INSERT INTO demanda_contrato_novo  (dem_ex, dem_cd,ct_exercicio,ct_numero,ct_tipo)  
        values (ano_demanda, cd_demanda,ex_contrato,num_contrato,tipo_contrato); 

		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call recuperarDemandas();
/** INCLUSAO DEMANDAS */


/** ALTERACAO DA SITUACAO DAS DEMANDAS */
DELIMITER $$
DROP PROCEDURE IF EXISTS manterDemandas $$
CREATE PROCEDURE manterDemandas()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE ano_demanda INT;
  DECLARE cd_demanda INT;

  DECLARE cTabela CURSOR FOR 
	select demanda.dem_ex, demanda.dem_cd from demanda
	inner join demanda_tram
	on demanda.dem_ex = demanda_tram.dem_ex
	and demanda.dem_cd = demanda_tram.dem_cd
	where 
	dem_situacao = 1
	group by demanda.dem_ex, demanda.dem_cd
	having count(*) > 1;
	
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO ano_demanda,cd_demanda;
		IF NOT done THEN
        
        update demanda
		set dem_situacao = 3
		WHERE demanda.dem_ex = ano_demanda
          AND demanda.dem_cd = cd_demanda;
        	
		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call manterDemandas();
/** ALTERACAO DA SITUACAO DAS DEMANDAS */