ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;
select * from registro_livro_hist

drop table if exists registro_livro;    
CREATE TABLE registro_livro (
    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)    

    regliv_numlivro INT NOT NULL,	
    regliv_numfolha INT NOT NULL,
    regliv_dtregistro DATE,
	regliv_obs TEXT,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie)
);

drop table if exists registro_livro_hist;
CREATE TABLE registro_livro_hist (
    hist INT NOT NULL AUTO_INCREMENT,

    ct_exercicio INT NOT NULL,
    ct_numero INT NOT NULL,
    ct_tipo char(1) NOT NULL,
    ct_cd_especie CHAR(2) NOT NULL, -- especie do registro (mater, apostilamento, aditivo)
	ct_sq_especie INT DEFAULT 1 NOT NULL, -- indice do documento em questao (primeiro ou segundo apostilamento, por ex)    

    regliv_numlivro INT NOT NULL,	
    regliv_numfolha INT NOT NULL,
    regliv_dtregistro DATE,
	regliv_obs TEXT,
        
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
  	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,

    CONSTRAINT pk PRIMARY KEY (hist),
    CONSTRAINT desativacao_livro_hist CHECK (in_desativado NOT IN ('S'))
); 

