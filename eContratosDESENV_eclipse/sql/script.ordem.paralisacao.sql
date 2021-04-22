select 
TAB_OP.ct_exercicio,
TAB_OP.ct_numero,
ct_dt_vigencia_fim, 
qtd,
ADDDATE(ct_dt_vigencia_fim, INTERVAL qtd day)
from contrato
inner join (
select MAX(sq) as sq from contrato
where contrato.ct_exercicio = 2018
and contrato.ct_numero = 23
and contrato.ct_tipo = 'C'
and contrato.ct_cd_especie in ('TA')
) tab_atual
on tab_atual.sq = contrato.sq

inner join

(select ct_dt_assinatura, ct_exercicio, ct_numero, ct_tipo, ct_cd_especie, ct_sq_especie,
SUM(DATEDIFF(ct_dt_vigencia_fim, ct_dt_vigencia_inicio)) as qtd from contrato
where contrato.ct_exercicio = 2018
and contrato.ct_numero = 23
and contrato.ct_tipo = 'C'
and contrato.ct_cd_especie in ('OP')) TAB_OP
ON contrato.ct_exercicio = TAB_OP.ct_exercicio
and contrato.ct_numero = TAB_OP.ct_numero
and contrato.ct_tipo = TAB_OP.ct_tipo
and contrato.ct_dt_assinatura <= TAB_OP.ct_dt_assinatura


