ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ALTER DATABASE unct CHARACTER SET Latin1 COLLATE latin1_general_ci;
-- ALTER DATABASE `sua_base` CHARSET = Latin1 COLLATE = latin1_swedish_ci;

drop table contrato_import;

CREATE TABLE contrato_import (
    sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT,
    ct_numero INT,
    ct_tipo char(1),
	ct_especie VARCHAR(50) , -- CHAR(2),
    ct_objeto LONGTEXT,
    ct_gestor_pessoa VARCHAR(300) ,
    ct_gestor VARCHAR(200) ,
	ct_processo_lic VARCHAR(300),
    ct_modalidade_lic VARCHAR(300),
    
	ct_data_public VARCHAR(300),
    ct_dt_assinatura DATE,
    ct_dt_vigencia_inicio DATE,
    ct_dt_vigencia_fim DATE,
    
    ct_contratada VARCHAR(300),
    ct_doc_contratada VARCHAR(30),
    ct_num_empenho VARCHAR(20),
    
    ct_tp_autorizacao VARCHAR(15), -- CHAR(2),
    ct_in_licom CHAR(1),
    ct_observacao LONGTEXT,
    
    ct_valor_global DECIMAL (14,4),
    ct_valor_mensal DECIMAL (14,4),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    
    CONSTRAINT pk_contrato PRIMARY KEY (sq, ct_exercicio, ct_numero, ct_tipo)
);

drop table contrato;
CREATE TABLE contrato (
    sq INT NOT NULL AUTO_INCREMENT,
    ct_exercicio INT,
    ct_numero INT,
    ct_tipo char(1),
	ct_especie VARCHAR(50),
    ct_sq_especie INT,
	ct_cd_especie INT,
	ct_cd_situacao CHAR(2),
    ct_objeto LONGTEXT,
    ct_gestor_pessoa VARCHAR(300) ,
    pe_cd_resp INT ,
    ct_gestor VARCHAR(200) ,
    gt_cd INT ,
	ct_processo_lic VARCHAR(300),
    ct_modalidade_lic VARCHAR(300),    
	ct_data_public VARCHAR(300),
    ct_dt_public DATE,
    ct_dt_assinatura DATE,
    ct_dt_vigencia_inicio DATE,
    ct_dt_vigencia_fim DATE,    
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
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
	ct_dt_proposta DATE NULL,
    
    CONSTRAINT pk PRIMARY KEY (sq, ct_exercicio, ct_numero, ct_tipo)
);
-- ALTER TABLE contrato ADD COLUMN pe_cd INT NOT NULL AFTER ct_contratada;
-- ALTER TABLE contrato ADD COLUMN gp_cd INT NULL AFTER ct_gestor_pessoa;

ALTER TABLE contrato ADD CONSTRAINT fk_ct_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
ALTER TABLE contrato ADD CONSTRAINT fk_ct_pessoa_resp FOREIGN KEY ( pe_cd_resp ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE contrato ADD CONSTRAINT fk_ct_pessoa_contratada FOREIGN KEY ( pe_cd_contratada ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

-- show create table contrato;

        
-- ALTER TABLE contrato DROP FOREIGN KEY fk_ct_gestor_pessoa;
-- ALTER TABLE contrato DROP FOREIGN KEY fk_ct_gestor;
    
UPDATE contrato SET 
ct_contratada = replace(replace(replace(ct_contratada,'“','"'),'”','"'),'–','-'),
ct_objeto = replace(replace(replace(ct_objeto,'“','"'),'”','"'),'–','-')
;-- WHERE sq = 1751;-- ct_exercicio = 2016 and ct_numero = 13;


drop table contrato_hist;
CREATE TABLE contrato_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    sq INT NOT NULL,
    ct_exercicio INT,
    ct_numero INT,
    ct_tipo char(1),
	ct_especie VARCHAR(50),
    ct_sq_especie INT,
    ct_cd_especie INT,
	ct_cd_situacao CHAR(2),
    ct_objeto LONGTEXT,
    ct_gestor_pessoa VARCHAR(300) ,
    pe_cd_resp INT ,
    ct_gestor VARCHAR(200) ,
    gt_cd INT ,
	ct_processo_lic VARCHAR(300),
    ct_modalidade_lic VARCHAR(300),    
	ct_data_public VARCHAR(300),
    ct_dt_public DATE,
    ct_dt_assinatura DATE,
    ct_dt_vigencia_inicio DATE,
    ct_dt_vigencia_fim DATE,    
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
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    ct_dt_proposta DATE NULL,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);

