<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbcontrato.php");

$db = new dbcontrato();
inicio();

$isRemoverCaracter = getAtributoComoBooleano(@$_GET[dbcontrato::$ID_REQ_REMOVER_CARACTER_ESPECIAL]);
if($isRemoverCaracter){
	echo $db->removerCaracterEspecial();
}else{	
	/*echoo("Incluindo novas contratadas.");
	$db->atualizarEntidade("call importarContratada();");*/
		
	echo $db->atualizarPessoasContrato();	
}