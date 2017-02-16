drop TABLE pessoa_teste;
CREATE TABLE pessoa_teste (
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
select max(pe_cd) from pessoa
where pe_nome LIKE '%BANCO DO BRASIL S.A%'
group by Trim(pe_nome);

select pe_nome, pe_doc from pessoa
where pe_doc is not null
group by Trim(pe_DOC) having count(*) > 1
;

select * from pessoa	
    where pe_DOC LIKE '%00.000.000/0001-91%'
	group by pe_nome, pe_doc;


select * from pessoa_vinculo
where 
pe_cd != 67 and
date(dh_ultima_alt) = CURRENT_DATE();

delete from pessoa_vinculo
where 
pe_cd != 67 and
date(dh_ultima_alt) = CURRENT_DATE();

delete from pessoa
where 
pe_cd != 67 and
date(dh_ultima_alt) = CURRENT_DATE();

where vi_cd = 2;

update pessoa_vinculo
set vi_cd = 1
where vi_cd = 2;

select * from pessoa_teste;
delete from pessoa
;
select count(*) from contrato
	where ct_contratada is not null
	group by ct_contratada, ct_doc_contratada;

INSERT INTO pessoa_teste (pe_nome, pe_doc)  values ('teste', 'teste'); 


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
		where ct_contratada is not null
		group by ct_contratada, ct_doc_contratada;
	-- SELECT cd FROM teste;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  SELECT MAX(pe_cd) INTO cdPessoa FROM pessoa_teste; 
  
  OPEN cTabela;
  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO nome,doc;
		IF NOT done THEN
		/*IF done = 1 THEN
		  LEAVE read_loop;
		END IF;*/
		
        set cdPessoa = cdPessoa +1;
		INSERT INTO pessoa_teste  (pe_cd, pe_nome, pe_doc)  values (cdPessoa, nome, doc); 
        INSERT INTO pessoa_vinculo (vi_cd, pe_cd)  values (1, cdPessoa); 

	  -- END LOOP read_loop;
		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call importarContratada();



/** OUTRO EXEMPLO */
DELIMITER $$
/*
Esta procedure cria, para cada grupo existente, um livrable em branco.
Possibilitando, assim, a visualização de quais grupos possuem Livrables cadastrados, e quais não possuem.
*/
DROP PROCEDURE IF EXISTS inclusao_contratada $$
CREATE PROCEDURE inclusao_contratada ()
BEGIN
  DECLARE cont, maxlivrable, quantPessoas int;
  
	select count(*) into quantPessoas from contrato
	where ct_contratada is not null
	group by ct_contratada, ct_doc_contratada;

	select ct_contratada, ct_doc_contratada from contrato
	where ct_contratada is not null
	group by ct_contratada, ct_doc_contratada;
  
    
    set cont = 1;
    while cont <= quantPessoas do
        
        INSERT INTO pessoa 
		SELECT (SELECT MAX(pe_cd)+1 FROM pessoa), null, ct_contratada, ct_doc_contratada, null, null, null, now(),now(),null,null from contrato
		where ct_contratada is not null
		group by ct_contratada, ct_doc_contratada;

        commit;
        select max(id_livrable) into maxlivrable from livrables;
        update grupo set id_livrable = maxlivrable where id_grupo = cont;
        commit;
        set cont = cont + 1;
    end while;
END $$
DELIMITER ;


delimiter $$
DROP PROCEDURE IF EXISTS simpleproc $$
CREATE PROCEDURE simpleproc (OUT param1 INT)
BEGIN
   declare x int;
   SELECT COUNT(*) INTO param1 FROM pessoa; 
   set x = 10;
   set param1 = param1 + x;
end$$

CALL simpleproc(@a);
SELECT @a;
delimiter;


select ct_contratada, ct_doc_contratada from contrato
where ct_doc_contratada is not null
and ct_contratada like '%Audentia %'
group by replace(replace(replace(ct_doc_contratada, ".", ""), "/", ""), "-","");
