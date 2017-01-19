-- INSERT INTO contrato_import (ct_exercicio,ct_numero,ct_tipo,ct_especie,ct_objeto,ct_gestor_pessoa,ct_gestor,ct_processo_lic,ct_modalidade_lic,ct_data_public,ct_dt_assinatura,ct_dt_vigencia_inicio,ct_dt_vigencia_fim,ct_contratada,ct_doc_contratada,ct_num_empenho,ct_tp_autorizacao,ct_in_licom,ct_observacao,ct_valor_global,ct_valor_mensal) VALUES (2004,
007,
'C-SAFI',
'Contrato Mater',
'teste objeto',
'gestor pessoa',
'DILOG',
'061/03',
'Parecer de dispensa de Licitação Nº 004/03',
'Ext. C-SAFI nº 003/04 DOE 05.05.04',
'2004-02-02',
'2004-12-31',
'2004-12-31',
'SANTO AMARO EMPREENDIMENTOS LTDA',
'11.547.213/0001-54',
'2004NE00160',
'SAD / PGE',
'',
'wfeew',
56119.52,
264000.00); 


SELECT contrato.*, 
TAB1.user_nicename AS nm_usuario_incl, 
TAB2.user_nicename AS nm_usuario_ultalt FROM 
contrato LEFT JOIN wp_users TAB1 ON TAB1.ID=cd_usuario_incl 
LEFT JOIN wp_users TAB2 ON TAB2.ID=cd_usuario_ultalt 
WHERE contrato.ct_numero=1 AND contrato.ct_exercicio=2099 AND contrato.sq=2442

SELECT ct_cd_especie, ct_especie,ct_sq_especie FROM CONTRATO
where ct_cd_especie is not null

SELECT count(*) FROM CONTRATO
where ct_tipo = 'C'
numero = 3 and ct_exercicio = 2099

sqcd_especie
where ct_cd_especie is not null

SELECT substr(ct_especie,1,3), ct_sq_especie FROM CONTRATO
where ct_tipo = 'C'
and ct_sq_especie is not null
group by substr(ct_especie,1,3),ct_sq_especie


SELECT ct_especie, ct_sq_especie FROM CONTRATO
where ct_tipo = 'V'
group by ct_especie,ct_sq_especie




INSERT INTO contrato (ct_exercicio,ct_numero,ct_tipo,ct_especie,ct_sq_especie,ct_objeto,ct_gestor_pessoa,ct_gestor,ct_processo_lic,ct_modalidade_lic,ct_data_public,ct_dt_assinatura,ct_dt_vigencia_inicio,ct_dt_vigencia_fim,ct_contratada,ct_doc_contratada,ct_num_empenho,ct_tp_autorizacao,ct_in_licom,ct_observacao,ct_valor_global,ct_valor_mensal) VALUES(2016,1,'C','01',2,'testenado inclusão ççççççççç','daniel sá carneiro','daniel sá carneiro','aqe','3e23e','20/05/2016','RROR-A -DA','2016-01-31','2016-12-01','esgotos e tubos','45.125.545/5112-21','qwe','NAO','S','TESTEADASD',5.15,0.55)

select * from  wp_users;
select * from  wp_usermeta;
select user_id, meta_value  from  wp_usermeta
where meta_key in ('nickname', 'wp_user_level') ;

SELECT contrato.*, TAB1.user_nicename 
FROM contrato LEFT JOIN wp_users TAB1
ON TAB1.id=contrato.cd_usuario_incl  

LEFT JOIN wp_users ON wp_users.ID=cd_usuario_ultalt AS TAB2 
WHERE contrato.ct_numero=1 AND contrato.ct_exercicio=2016 AND contrato.sq=97

select * from contrato
where date(dh_inclusao) = CURDATE()

group by ct_especie;


WHERE contrato_import.ct_dt_vigencia_inicio < '2000-01-01' ORDER BY ct_exercicio 

WHERE contrato_import.ct_tipo='V' ORDER BY ct_exercicio 

group by ct_especie;

select ct_data_public from contrato
where 
where ct_tipo = 'C';


delete from contrato_import
where ct_tipo = 'V';



select count(*) from contrato_import
WHERE ct_in_licom  is not null;