ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table if exists proc_licitatorio;
CREATE TABLE proc_licitatorio (
    pl_ex INT NOT NULL, 
    pl_cd INT NOT NULL,     
    		
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
    pl_valor DECIMAL (14,4),
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (pl_ex, pl_cd, pl_mod_cd),

   	CONSTRAINT fk_pl_pregoeiro FOREIGN KEY (pl_cd_pregoeiro) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT

);
ALTER TABLE proc_licitatorio ADD COLUMN pl_valor DECIMAL (14,4) AFTER pl_si;
-- ALTER TABLE proc_licitatorio DROP PRIMARY KEY;
-- ALTER TABLE proc_licitatorio ADD PRIMARY KEY pk (pl_ex, pl_cd, pl_mod_cd);
-- describe demanda_pl

drop table if exists proc_licitatorio_hist;
CREATE TABLE proc_licitatorio_hist (
    hist INT NOT NULL AUTO_INCREMENT,

    pl_ex INT NOT NULL, 
    pl_cd INT NOT NULL,     
    		
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
    pl_valor DECIMAL (14,4),
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
	in_desativado CHAR(1) NOT NULL,
    
  	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,

    CONSTRAINT pk PRIMARY KEY (hist),
    CONSTRAINT desativacao_pa CHECK (in_desativado NOT IN ('S'))
); 
ALTER TABLE proc_licitatorio_hist ADD COLUMN pl_valor DECIMAL (14,4) AFTER pl_si;

show create table pa_hist;

