<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voPA.php");
inicio();

$vo = new voPA();
$vo->getDadosFormulario();

if(existeObjetoSessao(voPA::$nmAtrColecaoTramitacao)){
	$vo->colecaoTramitacao = getObjetoSessao(voPA::$nmAtrColecaoTramitacao);
	//echo "tem objeto sessao";
	
	//var_dump($vo->colecaoTramitacao);
}


putObjetoSessao("vo", $vo);
removeObjetoSessao(voPA::$nmAtrColecaoTramitacao);

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>