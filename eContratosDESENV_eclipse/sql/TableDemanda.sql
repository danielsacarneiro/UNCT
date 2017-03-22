ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table demanda;
CREATE TABLE demanda (
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd)
);

drop table demanda_hist;
CREATE TABLE demanda_hist (
    hist INT NOT NULL AUTO_INCREMENT,
	dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    dem_tipo INT NOT NULL,
    dem_situacao INT NOT NULL,        
    dem_cd_setor INT NOT NULL,
    dem_texto MEDIUMTEXT NOT NULL,
    dem_prioridade INT DEFAULT 3 NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);
    

drop table demanda_tram;
CREATE TABLE demanda_tram (
	sq INT NOT NULL,
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,    
    
    dtm_cd_setor_origem INT NOT NULL,    
    dtm_cd_setor_destino INT NOT NULL,     
    dtm_texto MEDIUMTEXT,
    dtm_prt TEXT,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (sq, dem_ex, dem_cd)
);

ALTER TABLE demanda_tram ADD CONSTRAINT fk_demanda_tram FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

drop table demanda_contrato;
CREATE TABLE demanda_contrato (	
    dem_ex INT NOT NULL,
    dem_cd INT NOT NULL,
    
    ct_sq INT NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    
    CONSTRAINT pk PRIMARY KEY (dem_ex, dem_cd, ct_sq)
);

ALTER TABLE demanda_contrato ADD CONSTRAINT fk_demanda_contrato FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
