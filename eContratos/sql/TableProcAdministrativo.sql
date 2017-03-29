ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table pa;
CREATE TABLE pa (
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano    
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT, -- simulacao da utilizacao da chave primaria
    ct_tipo char(1),
    
    pa_cd_responsavel INT NULL,
    pa_proc_licitatorio VARCHAR(300),
    pa_observacao LONGTEXT,
    pa_dt_abertura DATE NOT NULL,
    pa_dt_inicio_prazo DATE NULL,
    pa_si INT NOT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (pa_ex, pa_cd)
);

drop table pa_hist;
CREATE TABLE pa_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano    
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT,
    ct_tipo char(1),
    
    pa_cd_responsavel INT NULL,
    pa_proc_licitatorio VARCHAR(300),
    pa_observacao LONGTEXT,
    pa_dt_abertura DATE NOT NULL,
    pa_dt_inicio_prazo DATE NULL,
    pa_si INT NOT NULL,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    CONSTRAINT pk PRIMARY KEY (hist)
);    

drop table pa_tramitacao;
CREATE TABLE pa_tramitacao (
	sq INT NOT NULL,
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano
    patr_observacao MEDIUMTEXT,

	ofic_sq INT,
    ofic_cd_setor INT, 
    ofic_ex INT,
    ofic_tp_doc CHAR(2),
    
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (	sq, pa_cd, pa_ex)
);

ALTER TABLE pa_tramitacao ADD CONSTRAINT fk_pa_tramitacao FOREIGN KEY (pa_ex, pa_cd) REFERENCES pa (pa_ex, pa_cd)
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
        
show create table pa_tramitacao;

