select ct_exercicio, ct_numero,ct_contratada from contrato 
where ct_contratada like '% – %'
group by ct_exercicio, ct_numero,ct_contratada ;

select ct_tp_autorizacao from contrato
group by ct_tp_autorizacao

SELECT ASCII('–');
SELECT ASCII('”');
SELECT ASCII('"');


select replace('S&B – LOCAÇÕES DE VEÍCULOS LTDA','–','-') as teste

SELECT CHAR();


select ct_exercicio, ct_numero,ct_objeto from contrato 
where ct_exercicio = 2016 and ct_numero = 13
group by ct_exercicio, ct_numero,ct_objeto ;


BEGIN 
 
   DECLARE SEQUENCIAL INT DEFAULT 1;
FOR LOOP AS

			SELECT ct_gestor from contrato where ct_gestor is not null GROUP BY ct_gestor

      DO
      
      INSERT INTO gestor (gt_cd, gt_ds, cd_usuario_incl) 
      VALUES (SEQUENCIAL, LOOP.ct_gestor, 1);

      SET SEQUENCIAL = SEQUENCIAL + 1;

END FOR;

END ;


BEGIN
DECLARE SEQUENCIAL INT DEFAULT 1;

WHILE SEQUENCIAL < 10 DO
      INSERT INTO gestor (gt_cd, gt_ds, cd_usuario_incl) 
      VALUES (SEQUENCIAL, LOOP.ct_gestor, 1);

SET crs = crs + 1;
END WHILE;
END;

