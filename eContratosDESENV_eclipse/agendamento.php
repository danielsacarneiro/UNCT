<?php  
include_once("config_lib.php");

echo "EXECUCAO DE AGENDADOR DE TAREFAS WINDOWS.<br><br>";

//alerta 1
require (caminho_funcoes. "alertas/alerta_encaminhamento_SAD.php");

//alerta 2
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste 
require (caminho_funcoes. "alertas/alerta_demanda_proposta_vencida.php");

?>