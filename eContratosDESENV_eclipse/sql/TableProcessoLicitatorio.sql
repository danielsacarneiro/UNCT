ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table if exists proc_licitatorio;
CREATE TABLE proc_licitatorio (
    pl_ex INT NOT NULL, 
    pl_cd INT NOT NULL,     
    
	dem_ex INT NOT NULL, -- dados da demanda
    dem_cd INT NOT NULL,    
		
	pl_orgao_responsavel INT NOT NULL, -- orgao responsavel pelo PL (SAD ou SEFAZ)
	pl_comissao_cd INT, -- numero da comissao de licitacao
	pl_mod_cd char(2) NOT NULL, -- Modalidade/identificacao do certame
	pl_mod_num INT NOT NULL, -- NUMERO DA modalidade/identificacao do certame
    pl_tp char(2) NOT NULL, -- menor preço...
	pl_cd_pregoeiro INT NULL,    
	
    pl_dt_abertura DATE NULL,
    pl_dt_publicacao DATE NULL,
    pl_objeto TEXT,
    pl_observacao TEXT,
    pl_si INT NOT NULL,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (pl_ex, pl_cd),

   	CONSTRAINT fk_pl_pregoeiro FOREIGN KEY (pl_cd_pregoeiro) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT

);

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

