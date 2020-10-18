<?php
//envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
require_once (caminho_funcoes."demanda/biblioteca_htmlDemanda.php");

$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];
if($enviarEmail != null){
	$enviarEmail = getAtributoComoBooleano($enviarEmail);
}else{
	$enviarEmail = true;
}
$enviarEmail = $enviarEmail && voMensageria::$ATIVADO && voMensageria::$ENVIAR_EMAIL_RELATORIO_DIARIO;

enviarEmailATJA($enviarEmail);

enviarEmailUNCT($enviarEmail);
