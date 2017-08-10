ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table if exists pa;
CREATE TABLE pa (
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano    

	dem_ex INT NOT NULL, -- dados da demanda
    dem_cd INT NOT NULL,    
        
    pa_cd_responsavel INT NULL,    
    pa_observacao LONGTEXT,
    
    pa_dt_abertura DATE NOT NULL,
    pa_dt_inicio_prazo DATE NULL,
    pa_si INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (pa_ex, pa_cd)
);
ALTER TABLE pa ADD CONSTRAINT fk_pa_demanda FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

drop table if exists pa_hist;
CREATE TABLE pa_hist (
    hist INT NOT NULL AUTO_INCREMENT,
    pa_cd INT NOT NULL, -- processo administrativo cd 
    pa_ex INT NOT NULL, -- processo administrativo ano    
        
	dem_ex INT NOT NULL, -- dados da demanda
    dem_cd INT NOT NULL,    

    pa_cd_responsavel INT NULL,    
    pa_observacao LONGTEXT,
    
    pa_dt_abertura DATE NOT NULL,
    pa_dt_inicio_prazo DATE NULL,
    pa_si INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
	in_desativado CHAR(1) NOT NULL,
    
  	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,

    CONSTRAINT pk PRIMARY KEY (hist)
); 
ALTER TABLE pa_hist ADD CONSTRAINT desativacao_pa CHECK (in_desativado NOT IN ('S'));

show create table pa_hist;

