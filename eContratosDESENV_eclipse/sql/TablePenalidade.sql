ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table pad;
CREATE TABLE pad (
    pad_cd INT NOT NULL, -- processo administrativo cd 
    pad_ex INT NOT NULL, -- processo administrativo ano
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT,
    ct_tipo char(1),
    
    pad_proc_licitatorio VARCHAR(300),
    pad_observacao LONGTEXT,
    pad_dt_abertura DATE NOT NULL,
    pad_si INT NOT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (pad_ex, pad_cd)
);

drop table pad_hist;
CREATE TABLE pad_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    pad_cd INT NOT NULL, -- processo administrativo cd 
    pad_ex INT NOT NULL, -- processo administrativo ano
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT,
    ct_tipo char(1),
    
    pad_proc_licitatorio VARCHAR(300),
    pad_observacao LONGTEXT,
    pad_dt_abertura DATE NOT NULL,
    pad_si INT NOT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    CONSTRAINT pk PRIMARY KEY (hist)
);    

drop table pad_tramitacao;
CREATE TABLE pad_tramitacao (
	sq INT NOT NULL,
    pad_cd INT NOT NULL, -- processo administrativo cd 
    pad_ex INT NOT NULL, -- processo administrativo ano
    padtr_observacao MEDIUMTEXT,
    padtr_link_arquivo TEXT,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
    CONSTRAINT pk PRIMARY KEY (	sq, pad_cd, pad_ex)
);

ALTER TABLE pad_tramitacao ADD CONSTRAINT fk_pad_tramitacao FOREIGN KEY (pad_cd, pad_ex) REFERENCES pad (pad_cd, pad_ex)
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
show create table pad_tramitacao;

