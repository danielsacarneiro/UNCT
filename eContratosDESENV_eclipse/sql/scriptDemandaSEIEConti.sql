drop table IF EXISTS demanda_SEI;
-- Create temporary table demanda_SEI (SEI VARCHAR(25));
Create table demanda_SEI (SEI VARCHAR(25));
insert into demanda_SEI (SEI)
values 
('1500000193000122202032'),
('1500000195000003202060'),
('1500000193000072202093'),
('1500000184000879201900'),
('1500000025000906202001'),
('1500000261000203202081'),
('1500000122000957201992'),
('1500000189000070202063'),
('1500000163000163201942');

call consultarDemandaSEIEconti();

/** PROCEDURE CONSULTA*/
DELIMITER $$
DROP PROCEDURE IF EXISTS consultarDemandaSEIEconti $$
-- CREATE PROCEDURE importarContratada(IN cdPessoa INT)
CREATE PROCEDURE consultarDemandaSEIEconti()
BEGIN

SELECT SEI FROM demanda_SEI
where not exists 
(
select DEMANDA_TRAM.DTM_PRT,DEMANDA_TRAM.dem_ex,DEMANDA_TRAM.dem_CD from DEMANDA_TRAM 
LEFT JOIN DEMANDA 
ON DEMANDA_TRAM.dem_ex = DEMANDA.dem_ex 
AND DEMANDA_TRAM.dem_cd = DEMANDA.dem_cd 

LEFT JOIN ( SELECT MAX(dtm_sq) AS dtm_sq,dem_cd,dem_ex FROM demanda_tram GROUP BY dem_cd,dem_ex) TABELA_MAX 
ON DEMANDA.dem_ex = TABELA_MAX.dem_ex 
AND DEMANDA.dem_cd = TABELA_MAX.dem_cd 
LEFT JOIN DEMANDA_TRAM TABELA_MAX_TRAM
ON TABELA_MAX_TRAM.dem_ex = TABELA_MAX.dem_ex 
AND TABELA_MAX_TRAM.dem_cd = TABELA_MAX.dem_cd 
AND TABELA_MAX_TRAM.dtm_sq = TABELA_MAX.dtm_sq 

WHERE DEMANDA_TRAM.DTM_PRT IS NOT NULL
-- and (demanda.dem_tipo in (1,2, 9)) -- demanda contrato apenas (parecer e PAAP incluso)
AND (TABELA_MAX_TRAM.dtm_cd_setor_destino in (3) OR demanda.dem_tipo = 2) -- destino 3 eh atja, demanda tipo 2 eh PAAP
and demanda.dem_situacao <> 2
and demanda.in_desativado = 'N'

and SEI =  DEMANDA_TRAM.DTM_PRT
GROUP BY DEMANDA_TRAM.DTM_PRT
);
  
END $$
DELIMITER ;
/** PROCEDURE CONSULTA */

