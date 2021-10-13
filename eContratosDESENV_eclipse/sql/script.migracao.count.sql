-- Gerar Select count
-- PASSO 1
SELECT CONCAT(
    'SELECT "', 
    table_name, 
    '" AS table_name, COUNT(*) AS exact_row_count FROM `', 
    table_schema,
    '`.`',
    table_name, 
    '` UNION '
) 
FROM INFORMATION_SCHEMA.TABLES 
WHERE table_schema = 'UNCT';

-- PASSO 2
-- executar a query gerada como resultado do select acima, retirando o ultimo UNION concatenado
SELECT "contrato" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato` UNION 
SELECT "contrato_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_hist` UNION 
SELECT "contrato_info" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_info` UNION 
SELECT "contrato_info_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_info_hist` UNION 
SELECT "contrato_licon" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_licon` UNION 
SELECT "contrato_licon_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_licon_hist` UNION 
SELECT "contrato_mod" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`contrato_mod` UNION 
SELECT "demanda" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda` UNION 
SELECT "demanda_contrato" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_contrato` UNION 
SELECT "demanda_doc" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_doc` UNION 
SELECT "demanda_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_hist` UNION 
SELECT "demanda_pl" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_pl` UNION 
SELECT "demanda_sei" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_sei` UNION 
SELECT "demanda_solic_compra" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_solic_compra` UNION 
SELECT "demanda_tram" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`demanda_tram` UNION 
SELECT "documento" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`documento` UNION 
SELECT "gestor" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`gestor` UNION 
SELECT "mensageria" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`mensageria` UNION 
SELECT "mensageria_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`mensageria_hist` UNION 
SELECT "msg_registro" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`msg_registro` UNION 
SELECT "pa" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pa` UNION 
SELECT "pa_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pa_hist` UNION 
SELECT "pa_penalidade" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pa_penalidade` UNION 
SELECT "pa_penalidade_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pa_penalidade_hist` UNION 
SELECT "pessoa" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pessoa` UNION 
SELECT "pessoa_gestor" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pessoa_gestor` UNION 
SELECT "pessoa_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pessoa_hist` UNION 
SELECT "pessoa_vinculo" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`pessoa_vinculo` UNION 
SELECT "proc_licitatorio" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`proc_licitatorio` UNION 
SELECT "proc_licitatorio_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`proc_licitatorio_hist` UNION 
SELECT "registro_livro" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`registro_livro` UNION 
SELECT "registro_livro_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`registro_livro_hist` UNION 
SELECT "solic_compra" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`solic_compra` UNION 
SELECT "solic_compra_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`solic_compra_hist` UNION 
SELECT "usuario_info" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`usuario_info` UNION 
SELECT "usuario_info_hist" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`usuario_info_hist` UNION 
SELECT "usuario_setor" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`usuario_setor` UNION 
SELECT "wp_commentmeta" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_commentmeta` UNION 
SELECT "wp_comments" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_comments` UNION 
SELECT "wp_links" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_links` UNION 
SELECT "wp_options" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_options` UNION 
SELECT "wp_postmeta" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_postmeta` UNION 
SELECT "wp_posts" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_posts` UNION 
SELECT "wp_term_relationships" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_term_relationships` UNION 
SELECT "wp_term_taxonomy" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_term_taxonomy` UNION 
SELECT "wp_termmeta" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_termmeta` UNION 
SELECT "wp_terms" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_terms` UNION 
SELECT "wp_usermeta" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_usermeta` UNION 
SELECT "wp_users" AS table_name, COUNT(*) AS exact_row_count FROM `unct`.`wp_users` 




-- ANTERIOR - POR ENQUANTO NAO USAR!
-- Quantidade de Registros em cada Tabela de uma determinada Base: 
-- O Count com as Tabelas da Base UNCT

SELECT TABLE_NAME, SUM(TABLE_ROWS) 
     FROM INFORMATION_SCHEMA.TABLES 
     WHERE TABLE_SCHEMA = 'UNCT'
     GROUP BY TABLE_NAME;

-- Já o Select abaixo, irá informar a Quantidade de Tabelas existentes em Cada Base
-- UNCT:

SELECT count(*) AS TOTALNUMBEROFTABLES
   FROM INFORMATION_SCHEMA.TABLES
   WHERE TABLE_SCHEMA = 'UNCT';

