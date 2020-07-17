ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ALTER DATABASE unct CHARACTER SET Latin1 COLLATE latin1_general_ci;
-- ALTER DATABASE `sua_base` CHARSET = Latin1 COLLATE = latin1_swedish_ci;

drop table IF EXISTS contrato;
CREATE TABLE contrato (
    sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
	ct_especie VARCHAR(50),
    ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_cd_situacao CHAR(2),
    ct_objeto LONGTEXT,
    ct_gestor_pessoa VARCHAR(300) ,
    pe_cd_resp INT ,
    ct_gestor VARCHAR(200) ,
    gt_cd INT ,
	ct_processo_lic VARCHAR(300),
    ct_cd_processo_lic INT,
    ct_ano_processo_lic INT,
    ct_cdmod_processo_lic CHAR(2),
    ct_modalidade_lic VARCHAR(300),    
	ct_data_public VARCHAR(300),
    ct_dt_public DATE,
    ct_dt_assinatura DATE,
    ct_dt_vigencia_inicio DATE,
    ct_dt_vigencia_fim DATE,    
	ct_dt_proposta DATE NULL,
    ct_contratada VARCHAR(300),
    pe_cd_contratada INT,
    ct_doc_contratada VARCHAR(30),
    ct_num_empenho VARCHAR(50),    
    ct_tp_autorizacao VARCHAR(15), 
    ct_cd_autorizacao INT, 
    ct_in_licom CHAR(1),
	ct_in_importacao CHAR(1) DEFAULT 'N',
    ct_observacao LONGTEXT,    
    ct_valor_global DECIMAL (14,4),
    ct_valor_mensal DECIMAL (14,4),
    ct_doc_link TEXT NULL,
    ct_doc_minuta TEXT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (sq, ct_exercicio, ct_numero, ct_tipo),
    UNIQUE KEY chave_logica_contrato (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie),
    CONSTRAINT fk_ct_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT,
	CONSTRAINT fk_ct_pessoa_resp FOREIGN KEY ( pe_cd_resp ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT,
	CONSTRAINT fk_ct_pessoa_contratada FOREIGN KEY ( pe_cd_contratada ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT
);
-- ALTER TABLE contrato ADD COLUMN ct_doc_minuta TEXT AFTER ct_doc_link;
-- ALTER TABLE contrato ADD COLUMN gp_cd INT NULL AFTER ct_gestor_pessoa;
        
/*ALTER TABLE contrato ADD UNIQUE KEY chave_logica_contrato (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie); 

ALTER TABLE contrato ADD CONSTRAINT fk_ct_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE contrato ADD CONSTRAINT fk_ct_pessoa_resp FOREIGN KEY ( pe_cd_resp ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE contrato ADD CONSTRAINT fk_ct_pessoa_contratada FOREIGN KEY ( pe_cd_contratada ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;*/
    

-- show create table contrato;

        
-- ALTER TABLE contrato DROP FOREIGN KEY fk_ct_gestor_pessoa;
-- ALTER TABLE contrato DROP FOREIGN KEY fk_ct_gestor;
    
UPDATE contrato SET
ct_contratada = replace(replace(replace(replace(ct_contratada,'“','"'),'”','"'),'–','-'), '?','-'),
ct_objeto = replace(replace(replace(replace(ct_objeto,'“','"'),'”','"'),'–','-'), '?','-'),
ct_gestor = replace(replace(replace(replace(ct_gestor,'“','"'),'”','"'),'–','-'), '?','-'),
ct_processo_lic = replace(replace(replace(replace(ct_processo_lic,'“','"'),'”','"'),'–','-'), '?','-')
;
-- WHERE sq = 1751;-- ct_exercicio = 2016 and ct_numero = 13;


drop table contrato_hist;
CREATE TABLE contrato_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    sq INT NOT NULL,
    ct_exercicio INT,
    ct_numero INT,
    ct_tipo char(1),
	ct_especie VARCHAR(50),
    ct_sq_especie INT, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    ct_cd_especie CHAR(2), -- especie do registro (mater, apostilamento, aditivo)
	ct_cd_situacao CHAR(2),
    ct_objeto LONGTEXT,
    ct_gestor_pessoa VARCHAR(300) ,
    pe_cd_resp INT ,
    ct_gestor VARCHAR(200) ,
    gt_cd INT ,
	ct_processo_lic VARCHAR(300),
    ct_cd_processo_lic INT,
    ct_ano_processo_lic INT,
    ct_cdmod_processo_lic CHAR(2),
    ct_modalidade_lic VARCHAR(300),    
	ct_data_public VARCHAR(300),
    ct_dt_public DATE,
    ct_dt_assinatura DATE,
    ct_dt_vigencia_inicio DATE,
    ct_dt_vigencia_fim DATE,
	ct_dt_proposta DATE NULL,
    ct_contratada VARCHAR(300),
    pe_cd_contratada INT,
    ct_doc_contratada VARCHAR(30),
    ct_num_empenho VARCHAR(50),    
    ct_tp_autorizacao VARCHAR(15), 
    ct_cd_autorizacao INT, 
    ct_in_licom CHAR(1),
	ct_in_importacao CHAR(1),
    ct_observacao LONGTEXT,    
    ct_valor_global DECIMAL (14,4),
    ct_valor_mensal DECIMAL (14,4),
    ct_doc_link TEXT NULL,
    ct_doc_minuta TEXT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);

drop table contrato_info;
CREATE TABLE contrato_info (	
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ctinf_cd_autorizacao INT, 
    ctinf_dt_proposta DATE NULL,
    ctinf_dt_basereajuste DATE NULL,
    ctinf_obs MEDIUMTEXT NULL,
    
    ctinf_in_garantia CHAR(1) NULL,
    ctinf_tp_garantia INT NULL,
    
    ctinf_in_mao_obra CHAR(1) NULL,
    ctinf_cd_classificacao INT,
	ctinf_in_credenciamento CHAR(1) NULL,    
    
    ctinf_cd_pegestor INT, 
    ctinf_in_escopo CHAR(1) NULL,
    ctinf_in_prazoprorrogacao INT NULL,
    ctinf_in_sad_estudotec INT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (ct_exercicio, ct_numero, ct_tipo)
);
ALTER TABLE contrato_info ADD COLUMN ctinf_in_escopo CHAR(1) NULL AFTER ctinf_cd_pegestor;
ALTER TABLE contrato_info ADD COLUMN ctinf_in_prazoprorrogacao INT NULL AFTER ctinf_in_escopo;
ALTER TABLE contrato_info ADD COLUMN ctinf_in_sad_estudotec INT NULL AFTER ctinf_in_prazoprorrogacao;
ALTER TABLE contrato_info ADD COLUMN ctinf_in_credenciamento CHAR(1) NULL AFTER ctinf_cd_classificacao;


/*ALTER TABLE contrato_info ADD CONSTRAINT fk_contrato_info FOREIGN KEY (ct_exercicio, ct_numero, ct_tipo) REFERENCES contrato (ct_exercicio, ct_numero, ct_tipo)
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
ALTER TABLE contrato_info ADD COLUMN ctinf_in_garantia CHAR(1) NULL AFTER ctinf_obs;
ALTER TABLE contrato_info ADD COLUMN ctinf_tp_garantia INT NULL AFTER ctinf_in_garantia;
ALTER TABLE contrato_info ADD COLUMN ctinf_in_mao_obra CHAR(1) NULL AFTER ctinf_tp_garantia;
ALTER TABLE contrato_info ADD COLUMN ctinf_cd_classificacao INT NULL AFTER ctinf_in_mao_obra;
ALTER TABLE contrato_info ADD COLUMN in_desativado CHAR(1) NOT NULL DEFAULT 'N' AFTER cd_usuario_ultalt;

-- ALTER TABLE contrato_info ADD COLUMN in_desativado CHAR(1) NOT NULL AFTER cd_usuario_ultalt;
 -- ALTER TABLE contrato_info DROP COLUMN ctinf_in_pat;
    
ALTER TABLE contrato_info DROP FOREIGN KEY fk_contrato_info;*/

-- ALTER TABLE contrato_info ADD COLUMN ctinf_dt_basereajuste DATE NULL AFTER ctinf_dt_proposta;

drop table contrato_info_hist;
CREATE TABLE contrato_info_hist (
	hist INT NOT NULL AUTO_INCREMENT,	
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ctinf_cd_autorizacao INT, 
    ctinf_dt_proposta DATE NULL,
    ctinf_dt_basereajuste DATE NULL,
    ctinf_obs MEDIUMTEXT NULL,
    
    ctinf_in_garantia CHAR(1) NULL,
    ctinf_tp_garantia INT NULL,    
    
    ctinf_in_mao_obra CHAR(1) NULL,
    ctinf_cd_classificacao INT,
    ctinf_in_credenciamento CHAR(1) NULL,
    
    ctinf_cd_pegestor INT, 
    ctinf_in_escopo CHAR(1) NULL,
    ctinf_in_prazoprorrogacao INT NULL,
    ctinf_in_sad_estudotec INT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL,
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,    
    
	CONSTRAINT pk PRIMARY KEY (hist)
);

ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_escopo CHAR(1) NULL AFTER ctinf_cd_pegestor;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_prazoprorrogacao INT NULL AFTER ctinf_in_escopo;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_sad_estudotec INT NULL AFTER ctinf_in_prazoprorrogacao;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_credenciamento CHAR(1) NULL AFTER ctinf_cd_classificacao;
/*ALTER TABLE contrato_info_hist DROP COLUMN ctinf_email_gestor;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_garantia CHAR(1) NULL AFTER ctinf_obs;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_tp_garantia INT NULL AFTER ctinf_in_garantia;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_in_mao_obra CHAR(1) NULL AFTER ctinf_tp_garantia;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_cd_classificacao INT NULL AFTER ctinf_in_mao_obra;
ALTER TABLE contrato_info_hist ADD COLUMN in_desativado CHAR(1) NULL AFTER cd_usuario_ultalt;
-- ALTER TABLE contrato_info_hist DROP FOREIGN KEY desativacao_demanda; 
-- ALTER TABLE contrato_info_hist DROP COLUMN ctinf_in_prestacao_garantia;
ALTER TABLE contrato_info_hist ADD COLUMN ctinf_dt_basereajuste DATE NULL AFTER ctinf_dt_proposta;*/

drop table contrato_licon;
CREATE TABLE contrato_licon (
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    

    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    
    ctl_situacao CHAR(1) DEFAULT 1 NOT NULL,

    ctl_obs MEDIUMTEXT NULL,
            
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie),
    -- UNIQUE KEY uk_contrato_licon (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie),
	CONSTRAINT fk_demanda_licon FOREIGN KEY (dem_ex, dem_cd) 
		REFERENCES demanda (dem_ex, dem_cd) 
			ON DELETE RESTRICT
			ON UPDATE RESTRICT
            
    -- CONSTRAINT ck_demanda_licon_userinclusao CHECK(cd_usuario_incl > 0)            
); 
ALTER TABLE contrato_licon DROP COLUMN dh_ultima_alt;
ALTER TABLE contrato_licon DROP COLUMN cd_usuario_ultalt;

ALTER TABLE contrato_licon ADD COLUMN dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL AFTER ctl_obs;
ALTER TABLE contrato_licon ADD COLUMN dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL AFTER dh_inclusao;
ALTER TABLE contrato_licon ADD COLUMN cd_usuario_incl INT DEFAULT 1 NOT NULL AFTER dh_ultima_alt;
ALTER TABLE contrato_licon ADD COLUMN cd_usuario_ultalt INT DEFAULT 1 NULL AFTER cd_usuario_incl;
ALTER TABLE contrato_licon ADD COLUMN in_desativado CHAR(1) NOT NULL DEFAULT 'N' AFTER cd_usuario_ultalt;


/*ALTER TABLE contrato_licon ADD UNIQUE KEY uk_contrato_licon (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie) 
ALTER TABLE contrato_licon DROP FOREIGN KEY fk_contrato_licon;*/
-- ALTER TABLE contrato_licon DROP INDEX uk_contrato_licon

drop table contrato_licon_hist;
CREATE TABLE contrato_licon_hist (
	hist INT NOT NULL AUTO_INCREMENT,	
    
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    

    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    
    ctl_situacao CHAR(1) DEFAULT 1 NOT NULL,

    ctl_obs MEDIUMTEXT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL,
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,    
    
	CONSTRAINT pk PRIMARY KEY (hist)
); 

DELIMITER $$
DROP PROCEDURE IF EXISTS recuperarDatasContratoLicon $$
CREATE PROCEDURE recuperarDatasContratoLicon()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE dem_ex INT;
  DECLARE dem_cd INT;
  DECLARE dh TIMESTAMP;

  DECLARE cTabela CURSOR FOR 
	  select contrato_licon.dem_ex,contrato_licon.dem_cd,MAX(demanda_tram.dh_inclusao) from contrato_licon
      inner join demanda_tram
      on demanda_tram.dem_ex = contrato_licon.dem_ex
      and demanda_tram.dem_cd = contrato_licon.dem_cd      
      group by demanda_tram.dem_ex, demanda_tram.dem_cd;
        -- retira os pontos, barras e tracos para evitar duplicacoes
	
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO dem_ex,dem_cd,dh;
		IF NOT done THEN		
		
        UPDATE contrato_licon SET dh_inclusao = dh, dh_ultima_alt = dh
        where contrato_licon.dem_ex = dem_ex and contrato_licon.dem_cd = dem_cd;

		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call recuperarDatasContratoLicon();


drop table contrato_mod;
CREATE TABLE contrato_mod (
	ctmod_sq INT NOT NULL,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)    
    
    ctmod_tipo INT NOT NULL,
    ctmod_dtreferencia DATE NOT NULL,
	ctmod_dtreferenciaFim DATE NOT NULL,
    
    ctmod_vlreferencial DECIMAL (14,4) NOT NULL,
    ctmod_vlreal DECIMAL (14,4) NOT NULL,
	ctmod_vlaocontrato DECIMAL (14,4) NOT NULL,

    ctmod_vlmensalatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalreal DECIMAL (14,4) NOT NULL,

    ctmod_vlmensalanterior DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalanterior DECIMAL (14,4) NOT NULL,
	
    ctmod_vlmensalmodatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalmodatual DECIMAL (14,4) NOT NULL,

	ctmod_nummesesfimperiodo DECIMAL (4,2),
    ctmod_numpercentual DECIMAL (6,4),
    ctmod_obs MEDIUMTEXT NULL,
        
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT NOT NULL,
    
    CONSTRAINT pk PRIMARY KEY (ctmod_sq, ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie)
            
    -- CONSTRAINT ck_demanda_licon_userinclusao CHECK(cd_usuario_incl > 0)            
); 

drop table contrato_mod_hist;
CREATE TABLE contrato_mod_hist (
	hist INT NOT NULL AUTO_INCREMENT,	

	ctmod_sq INT NOT NULL,
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)    
    
    ctmod_tipo INT NOT NULL,
    ctmod_dtreferencia DATE NOT NULL,
	ctmod_dtreferenciaFim DATE NOT NULL,
    
    ctmod_vlreferencial DECIMAL (14,4) NOT NULL,
    ctmod_vlreal DECIMAL (14,4) NOT NULL,
	ctmod_vlaocontrato DECIMAL (14,4) NOT NULL,

    ctmod_vlmensalatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalreal DECIMAL (14,4) NOT NULL,

    ctmod_vlmensalanterior DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalanterior DECIMAL (14,4) NOT NULL,
	
    ctmod_vlmensalmodatual DECIMAL (14,4) NOT NULL,
    ctmod_vlglobalmodatual DECIMAL (14,4) NOT NULL,

	ctmod_nummesesfimperiodo DECIMAL (4,2),
    ctmod_numpercentual DECIMAL (6,4),
    ctmod_obs MEDIUMTEXT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL,
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,    
    
	CONSTRAINT pk PRIMARY KEY (hist)
); 

SELECT count(*) FROM CONTRATO
WHERE ct_tipo = 'C'

DELETE FROM CONTRATO
WHERE ct_tipo = 'C'
