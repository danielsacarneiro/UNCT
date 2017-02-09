ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table penalidade;
CREATE TABLE penalidade (
    pn_cd INT NOT NULL, -- codigo da penalidade
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT,
    ct_tipo char(1),
    
    pn_proc_licitatorio VARCHAR(300),
    pn_observacao LONGTEXT,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (pa_ex, pa_cd, pn_cd)
);

drop table penalidade_hist;
CREATE TABLE penalidade_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    pn_cd INT NOT NULL, -- codigo da penalidade
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano
    
    ct_exercicio INT, -- dados contrato
    ct_numero INT,
    ct_tipo char(1),
    
    pn_proc_licitatorio VARCHAR(300),
    pn_observacao LONGTEXT,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (hist)
);    

drop table penalidade_tramitacao;
CREATE TABLE penalidade_tramitacao (
	sq INT NOT NULL,
    pn_cd INT NOT NULL, -- codigo da penalidade
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano
    pnt_observacao LONGTEXT,
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (	sq, pn_cd, pa_cd,pa_ex)
);
