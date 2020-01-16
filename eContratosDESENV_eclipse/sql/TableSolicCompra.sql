ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table if exists solic_compra;    
CREATE TABLE solic_compra (
    solic_cd INT NOT NULL,
    solic_ex INT NOT NULL,     
    solic_ug INT NOT NULL,
    		
    solic_tp INT NOT NULL,	
    solic_objeto TEXT,
    solic_si INT NOT NULL,
    solic_valor DECIMAL (14,4),
	solic_observacao TEXT,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (solic_ex, solic_cd, solic_ug)
);

drop table if exists solic_compra_hist;
CREATE TABLE solic_compra_hist (
    hist INT NOT NULL AUTO_INCREMENT,

    solic_cd INT NOT NULL,
    solic_ex INT NOT NULL,     
    solic_ug INT NOT NULL,
    		
    solic_tp INT NOT NULL,	
    solic_objeto TEXT,
    solic_si INT NOT NULL,
    solic_valor DECIMAL (14,4),
	solic_observacao TEXT,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
	in_desativado CHAR(1) NOT NULL,
    
  	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,

    CONSTRAINT pk PRIMARY KEY (hist),
    CONSTRAINT desativacao_solic_compra CHECK (in_desativado NOT IN ('S'))
); 

