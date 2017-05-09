<?php
include_once ("../../config_lib.php");
include_once (caminho_vos . "vocontrato.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");

function limparCampoDadosContrato() {
	return "";	
}

function imprime(){
	$limpar = @$_GET ["limpar"];
	$limpar = $limpar == "S";
	
	$indice = @$_GET ["indice"];
	
	if($limpar){
		$retorno = limparCampoDadosContrato();
	}else{
		$retorno = getCampoDadosContratoMultiplosPorIndice($indice);
	}
	
	return $retorno; 
}

echo imprime();

?>