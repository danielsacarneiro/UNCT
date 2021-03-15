ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table usuario_info;
CREATE TABLE usuario_info (
    ID INT NOT NULL,
    user_setor VARCHAR(100),    
    user_in_caracteristicas VARCHAR(100),

    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (ID)
);
ALTER TABLE usuario_info ADD COLUMN user_in_caracteristicas VARCHAR(100) AFTER user_setor;

CREATE TABLE usuario_info_hist (
	hist INT NOT NULL AUTO_INCREMENT,
    
    ID INT NOT NULL, 
    user_setor VARCHAR(100),
    user_in_caracteristicas VARCHAR(100),
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',
    
   	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,    

    CONSTRAINT pk PRIMARY KEY (hist),
    CONSTRAINT desativacao_demanda CHECK (in_desativado NOT IN ('S'))
);
ALTER TABLE usuario_info_hist ADD COLUMN user_in_caracteristicas VARCHAR(100) AFTER user_setor;
ALTER TABLE usuario_info_hist ADD COLUMN dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL AFTER in_desativado;
ALTER TABLE usuario_info_hist ADD COLUMN cd_usuario_operacao INT AFTER dh_operacao;

select * from usuario_info_hist

/** INCLUSAO DOS USUARIOS INFO */
DELIMITER $$
DROP PROCEDURE IF EXISTS incluirUsuarioInfo $$
-- CREATE PROCEDURE importarContratada(IN cdPessoa INT)
CREATE PROCEDURE incluirUsuarioInfo()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE INDICE INTEGER DEFAULT 0;
  DECLARE DATA_IN TIMESTAMP;

  DECLARE cTabela CURSOR FOR 
	  select ID,dh_inclusao from usuario_setor GROUP BY ID;
      
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO INDICE, DATA_IN;
		IF NOT done THEN
        
		INSERT INTO usuario_info  (id, user_setor, cd_usuario_incl, cd_usuario_ultalt, dh_ultima_alt, dh_inclusao)  
			values 
            (INDICE,             
            (SELECT GROUP_CONCAT(case LENGTH(usu_cd_setor) when 1 then CONCAT('0', usu_cd_setor) else usu_cd_setor end SEPARATOR '*') 
			FROM usuario_setor
			where id = INDICE),
            1,
            1,
            DATA_IN,
            DATA_IN
            );         

		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call incluirUsuarioInfo();
/** INCLUSAO DOS USUARIO INFO*/

-- a remover
drop table usuario_setor;
CREATE TABLE usuario_setor (
    ID BIGINT(20) NOT NULL,
    usu_cd_setor INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    CONSTRAINT pk PRIMARY KEY (ID,usu_cd_setor),
    CONSTRAINT fk_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID) ON DELETE RESTRICT ON UPDATE RESTRICT
);

ALTER TABLE usuario_setor CHANGE COLUMN ID ID BIGINT(20);

ALTER TABLE usuario_setor ADD CONSTRAINT fk_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID) ON DELETE RESTRICT ON UPDATE RESTRICT;

select * from usuario_setor
where ID not in (
select ID from wp_users
)

select * from wp_users
where id = 13

select * from usuario_info
where id = 11


update usuario_info
set user_setor = '01*02*03*04*05*06*07*08*09*10*11*13*14*15*16*17*18*99'
where id = 31

insert into usuario_info
(id, user_setor, cd_usuario_incl, cd_usuario_ultalt, in_desativado)
values (33, '01*02*03*04*05*06*07*08*09*10*11*13*14*15*16*17*18*99', 1,1, 'N')


'24', '01**03*04*05*06*07*08*09*10*11*13*14*15*16*17*18*99', '2020-09-02 11:46:17', '2020-09-04 09:45:59', '1', '1', 'N'

SELECT pessoa_GERAL.pe_cd,pessoa_GERAL.pe_nome,pessoa_GERAL.pe_doc,pessoa_GERAL.pe_email,pessoa_GERAL.pe_tel,pessoa_vinculo.vi_cd,pessoa_vinculo.vi_inatribuicaoPAAP,pessoa_vinculo.vi_inatribuicaoPregoeiro 
FROM 
(SELECT * FROM pessoa WHERE pe_cd IN (SELECT pa_cd_responsavel FROM pa GROUP BY pa_cd_responsavel)) pessoa_GERAL 
INNER JOIN pessoa_vinculo ON pessoa_GERAL.pe_cd=pessoa_vinculo.pe_cd 
LEFT JOIN contrato ON pessoa_GERAL.pe_cd=contrato.pe_cd_contratada 
GROUP BY pessoa_GERAL.pe_cd,pessoa_GERAL.pe_nome,pessoa_GERAL.pe_doc,pessoa_GERAL.pe_email,pessoa_GERAL.pe_tel,pessoa_vinculo.vi_cd,pessoa_vinculo.vi_inatribuicaoPAAP,pessoa_vinculo.vi_inatribuicaoPregoeiro
 ORDER BY pe_nome

