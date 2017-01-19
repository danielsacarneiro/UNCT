select * from admfin.gfe_saldo_pagto_unificado
order by
CTBEXERC_DT_ANO, UG_CD,  GFUGESTAO_CD, GFEPGMUNIF_SQ_DOC, TIPODOCCTB_CD;


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

