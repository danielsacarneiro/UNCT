ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table gestor;
CREATE TABLE gestor (
    gt_cd INT NOT NULL AUTO_INCREMENT, 
    gt_descricao VARCHAR(300),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (gt_cd)
);

INSERT INTO gestor 
	SELECT (SELECT MAX(gt_sq)+1 FROM gestor), (SELECT MAX(gt_sq)+1 FROM gestor), ct_gestor, now(), null,null,null
    FROM contrato
    GROUP BY ct_gestor ;


-- ALTER TABLE pessoa DROP FOREIGN KEY fk_pessoa_usuario;
drop table pessoa;
CREATE TABLE pessoa (
	pe_cd INT NOT NULL AUTO_INCREMENT,
	ID BIGINT(20) UNSIGNED,
    pe_nome VARCHAR(150),
    pe_doc VARCHAR(30),
    pe_tel VARCHAR(30),
    pe_email VARCHAR(100),
	pe_endereco VARCHAR(300),
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
        
    CONSTRAINT pk PRIMARY KEY (pe_cd)
);
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_usuario FOREIGN KEY (ID) REFERENCES wp_users (ID)
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
/** INCLUSAO DAS CONTRATADAS */
DELIMITER $$
DROP PROCEDURE IF EXISTS importarContratada $$
CREATE PROCEDURE importarContratada()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE nome VARCHAR(150);
  DECLARE doc VARCHAR(30);
  DECLARE cdPessoa INT;  

  DECLARE cTabela CURSOR FOR 
	  select ct_contratada, ct_doc_contratada from contrato
		where ct_doc_contratada is not null		
        and pe_cd_contratada is null -- pega apenas as contratadas dos contratos que ainda nao tem relacao
        group by replace(replace(replace(ct_doc_contratada, ".", ""), "/", ""), "-","");
        -- retira os pontos, barras e tracos para evitar duplicacoes
	
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  SELECT MAX(pe_cd) INTO cdPessoa FROM pessoa; 
  
  -- DELETE FROM pessoa_vinculo WHERE vi_cd = 2;
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO nome,doc;
		IF NOT done THEN
		
        set cdPessoa = cdPessoa +1;
		INSERT INTO pessoa  (pe_cd, pe_nome, pe_doc)  values (cdPessoa, nome, doc); 
        INSERT INTO pessoa_vinculo (vi_cd, pe_cd)  values (2, cdPessoa); 

		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call importarContratada();
/** INCLUSAO DAS CONTRATADAS */

UPDATE pessoa SET 
pe_nome = replace(replace(replace(pe_nome,'“','"'),'”','"'),'–','-')
WHERE pe_cd = 305;
	
drop table pessoa_vinculo;
CREATE TABLE pessoa_vinculo (
	vi_cd INT NOT NULL,
    pe_cd INT NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,

        
    CONSTRAINT pk PRIMARY KEY (vi_cd, pe_cd)
);
ALTER TABLE pessoa_vinculo ADD CONSTRAINT fk_pessoa_vinculo FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
    
drop table pessoa_gestor;
CREATE TABLE pessoa_gestor (
	pe_cd INT,
	gt_cd INT,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,
        
    CONSTRAINT pk PRIMARY KEY (pe_cd, gt_cd)
);
ALTER TABLE pessoa_gestor ADD CONSTRAINT fk_pessoa_gestor FOREIGN KEY ( gt_cd ) REFERENCES gestor (gt_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
ALTER TABLE pessoa_gestor ADD CONSTRAINT fk_pessoa_gestor2 FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

