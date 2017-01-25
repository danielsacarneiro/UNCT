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

update contrato_import
set ct_in_licom = 'S'
WHERE ct_in_licom = 'O' is not null;

update contrato_import
set ct_in_licom = 'N'
WHERE ct_in_licom  is null;


