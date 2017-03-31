ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ALTER DATABASE unct CHARACTER SET Latin1 COLLATE latin1_general_ci;
-- ALTER DATABASE `sua_base` CHARSET = Latin1 COLLATE = latin1_swedish_ci;

drop table contrato;
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
    ct_doc_link TEXT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
	ct_dt_proposta DATE NULL,
    
    CONSTRAINT pk PRIMARY KEY (sq, ct_exercicio, ct_numero, ct_tipo)
);
-- ALTER TABLE contrato ADD COLUMN pe_cd INT NOT NULL AFTER ct_contratada;
-- ALTER TABLE contrato ADD COLUMN gp_cd INT NULL AFTER ct_gestor_pessoa;
        
ALTER TABLE contrato ADD UNIQUE KEY chave_logica_contrato (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie); 

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
    ct_sq_especie INT, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)
    ct_cd_especie CHAR(2), -- especie do registro (mater, apostilamento, aditivo)
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
    ct_doc_link TEXT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    ct_dt_proposta DATE NULL,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);


drop table contrato_tram;
CREATE TABLE contrato_tram (
    ct_exercicio INT NOT NULL, -- CHAVE CONTRATO
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,    
    sq_tram BIGINT NOT NULL, -- chave tramitacao
    sq_indice INT NOT NULL, 
        
    CONSTRAINT pk PRIMARY KEY (	ct_exercicio, ct_numero, ct_tipo, sq_tram)
);

ALTER TABLE contrato_tram ADD CONSTRAINT fk_ct_tram_contrato FOREIGN KEY (ct_exercicio, ct_numero, ct_tipo) REFERENCES contrato (ct_exercicio, ct_numero, ct_tipo) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE contrato_tram ADD CONSTRAINT fk_ct_tram_tram FOREIGN KEY (sq_tram) REFERENCES tramitacao (sq_tram) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

