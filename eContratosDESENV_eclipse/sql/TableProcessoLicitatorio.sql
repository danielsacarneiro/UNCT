ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table if exists proc_licitatorio;
CREATE TABLE proc_licitatorio (
    pl_ex INT NOT NULL, 
    pl_cd INT NOT NULL, 
    mod_cd INT NOT NULL, -- modalidade/identificacao do certame
    
	dem_ex INT NOT NULL, -- dados da demanda
    dem_cd INT NOT NULL,    
	
    pl_tp INT NOT NULL, -- menor preço...
    pl_dt_sessao DATE NULL,
    pl_dt_publicacao DATE NULL,            
    pl_cd_pregoeiro INT NULL,    
    pl_objeto TEXT,
    pl_observacao TEXT,
    pl_si INT NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (pl_ex, pl_cd)
);
ALTER TABLE proc_licitatorio ADD CONSTRAINT fk_pl_demanda FOREIGN KEY (dem_ex, dem_cd) REFERENCES demanda (dem_ex, dem_cd) 
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

