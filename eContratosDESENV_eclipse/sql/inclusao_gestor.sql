SELECT count(*) as nmCampoCount 
FROM penalidade 
INNER JOIN contrato 
ON contrato.ct_numero=penalidade.ct_numero 
AND contrato.ct_exercicio=penalidade.ct_exercicio 
AND contrato.ct_tipo=penalidade.ct_tipo 
WHERE contrato.ct_especie = 01 ORDER BY ct_numero;

SELECT penalidade.pn_cd,penalidade.pa_cd,penalidade.pa_ex,penalidade.ct_numero,penalidade.ct_exercicio,penalidade.ct_tipo 
FROM penalidade 
INNER JOIN contrato 
ON contrato.ct_numero=penalidade.ct_numero 
AND contrato.ct_exercicio=penalidade.ct_exercicio 
AND contrato.ct_tipo=penalidade.ct_tipo 

WHERE contrato.ct_especie = 01   ORDER BY ct_numero LIMIT 0,40

select * from penalidade

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

