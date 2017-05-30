ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table demanda;
CREATE TABLE demanda (
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
    dem_dtreferencia DATE,
    dem_prt VARCHAR(25),
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd)
);

ALTER TABLE demanda ADD COLUMN in_desativado CHAR(1) NOT NULL DEFAULT 'N' AFTER cd_usuario_ultalt;


-- ALTER TABLE demanda CHANGE dem_cd dem_cd INT AUTO_INCREMENT;
-- ALTER TABLE demanda AUTO_INCREMENT=100;
    
-- update demanda set dem_dtreferencia = DATE(dh_inclusao);

drop table demanda_hist;
CREATE TABLE demanda_hist (
    hist INT NOT NULL AUTO_INCREMENT,
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
    dem_dtreferencia DATE,
    dem_prt VARCHAR(25),
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);
ALTER TABLE demanda_hist ADD COLUMN in_desativado CHAR(1) NOT NULL AFTER cd_usuario_ultalt;
ALTER TABLE demanda_hist ADD CONSTRAINT desativacao_demanda CHECK (in_desativado NOT IN ('S'))

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
