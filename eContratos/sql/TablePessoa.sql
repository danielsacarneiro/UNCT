ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

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

INSERT INTO gestor 
	SELECT (SELECT MAX(gt_sq)+1 FROM gestor), (SELECT MAX(gt_sq)+1 FROM gestor), ct_gestor, now(), null,null,null
    FROM contrato
    GROUP BY ct_gestor ;


-- ALTER TABLE pessoa DROP FOREIGN KEY fk_pessoa_usuario;
drop table pessoa_vinculo;
drop table pessoa;
CREATE TABLE pessoa (
	pe_cd INT NOT NULL AUTO_INCREMENT,
	ID BIGINT(20) UNSIGNED,
    pe_nome VARCHAR(150),
    pe_doc VARCHAR(30),
    pe_tel VARCHAR(30),
    pe_email VARCHAR(100),
	pe_endereco VARCHAR(300),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
        
    CONSTRAINT pk PRIMARY KEY (pe_cd)
);
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID)
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

	
drop table pessoa_vinculo;
CREATE TABLE pessoa_vinculo (
	vi_cd INT NOT NULL,
    pe_cd INT NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,

        
    CONSTRAINT pk PRIMARY KEY (vi_cd, pe_cd)
);
ALTER TABLE pessoa_vinculo ADD CONSTRAINT fk_pessoa_vinculo FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
drop table gestor_pessoa;
CREATE TABLE gestor_pessoa (
	pe_cd INT,
	gt_cd INT,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
        
    CONSTRAINT pk PRIMARY KEY (gp_cd)
);
ALTER TABLE gestor_pessoa ADD CONSTRAINT fk_gestor_pessoa FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

