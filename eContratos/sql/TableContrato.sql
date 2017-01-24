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
    gp_cd INT ,
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
-- ALTER TABLE contrato ADD COLUMN gt_cd INT NULL AFTER ct_gestor;
-- ALTER TABLE contrato ADD COLUMN gp_cd INT NULL AFTER ct_gestor_pessoa;

ALTER TABLE contrato ADD CONSTRAINT fk_ct_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
ALTER TABLE contrato ADD CONSTRAINT fk_ct_gestor_pessoa FOREIGN KEY ( gp_cd ) REFERENCES gestor_pessoa (gp_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
UPDATE contrato SET 
ct_contratada = replace(replace(replace(ct_contratada,'“','"'),'”','"'),'–','-'),
ct_objeto = replace(replace(replace(ct_objeto,'“','"'),'”','"'),'–','-')
-- WHERE ct_exercicio = 2016 and ct_numero = 13;


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
    gp_cd INT ,
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


drop table gestor;
CREATE TABLE gestor (
    gt_cd INT NOT NULL AUTO_INCREMENT, 
    gt_descricao VARCHAR(300),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (gt_cd)
);

-- drop table gestor_seq;
-- CREATE TABLE gestor_seq (
--     gt_cd INT NOT NULL AUTO_INCREMENT,    
--    CONSTRAINT pk_contrato PRIMARY KEY (gt_cd)
-- );
-- ALTER TABLE gestor_seq AUTO_INCREMENT=100;


CREATE SEQUENCE SEQ_VAL
     START WITH 1
     INCREMENT BY 1
     NO MAXVALUE
     NO CYCLE
     CACHE 24;

BEGIN
     FOR laco AS ( SELECT * from contrato)
     DO
             INSERT INTO gestor (gt_sq, gt_cd, gt_ds, cd_usuario_incl) VALUES (laco.sq, laco.cd, laco.ct_gestor, 1);
     END FOR;
END;


INSERT INTO gestor 
	SELECT (SELECT MAX(gt_sq)+1 FROM gestor), (SELECT MAX(gt_sq)+1 FROM gestor), ct_gestor, now(), null,null,null
    FROM contrato
    GROUP BY ct_gestor ;

drop table gestor_pessoa;
CREATE TABLE gestor_pessoa (
	gp_cd INT NOT NULL AUTO_INCREMENT,
	gt_cd INT,
    gp_nome VARCHAR(300),
    gp_doc VARCHAR(30),
    gp_tel VARCHAR(30),
    gp_email VARCHAR(300),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
        
    CONSTRAINT pk PRIMARY KEY (gp_cd)
);
ALTER TABLE gestor_pessoa ADD CONSTRAINT fk_gp_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;


-- ALTER TABLE contrato_import CONVERT TO CHARACTER SET utf8;
-- comentarios
-- ct_tpcontrato indica o tipo da movimentacao (C-SAFI, TC-SAFI)

update contrato_import
set ct_in_licom = 'S'
WHERE ct_in_licom = 'O' is not null;

update contrato_import
set ct_in_licom = 'N'
WHERE ct_in_licom  is null;


