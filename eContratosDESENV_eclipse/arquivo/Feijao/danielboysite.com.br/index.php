<?php
include("processo.obj.php");
$dbprocesso = new processo();

//exemplo
$codProcesso = 0001;
$resProcesso = $dbprocesso->limpaResultado();
$resProcesso = $dbprocesso->getDadosProcesso($codProcesso);
//para exibir qualquer campo da tabela vinda do resultado acima basta fazer assim:
$nomeProcesso = $resProcesso[0]["campo da tabela"];


$resListaProcesso = $dbprocesso->limpaResultado();
$resListaProcesso = $dbprocesso->listarProcessoo();

if (is_array($resListaProcesso))
	$totalResultado = sizeof($resListaProcesso);
	
else 
	$totalResultado = 0;

for ($i=0;$i<$totalResultado;$i++) {
	echo $resListaProcesso[$i]["nome do campo"];
}	

$today = getdate();
$mesAtual = $today["mon"];
$ano4Digitos = date("Y");
$ano2Digitos = date("y");

$dbprocesso->finalize();
?>
