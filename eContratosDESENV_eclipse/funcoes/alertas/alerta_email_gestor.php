<?php
// envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

$ativado = voMensageria::$ATIVADO;
$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];
//se for nulo, eh pq veio do disparo automatico
$isEnvioEmailGestor = $isEnvioEmailGestor == null || getAtributoComoBooleano($enviarEmail);
$isEnvioEmailGestor = $isEnvioEmailGestor && voMensageria::$ENVIAR_EMAIL_GESTOR;

if(!$ativado){
	echoo ("Mensageria Desativado.");
}else{
	if(!$isEnvioEmailGestor){
		echoo ("Mensageria: Seleção NÃO ENVIAR email para os contratos cadastrados.");
	}
}

$enviarEmailAlerta = $ativado && $isEnvioEmailGestor;
if ($enviarEmailAlerta) {	
	$filtro = new filtroManterMensageria ( false );
	$filtro->isValidarConsulta = false;
	$filtro->inHabilitado = constantes::$CD_SIM;
	$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
	//$filtro->inVerificarFrequencia = constantes::$CD_NAO;
	$filtro->inVerificarFrequencia = voMensageria::$IN_VERIFICAR_FREQUENCIA;
	echoo("___________________________");
	echoo("Verificador de Frequência do email: '$filtro->inVerificarFrequencia'.");
	
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	
	$dbMensageria = new dbMensageria ();
	$colecao = $dbMensageria->consultarTelaConsulta ( new voMensageria (), $filtro );
	
	$dbMensageriaRegistro = new dbMensageriaRegistro ();
	if (! isColecaoVazia ( $colecao )) {
		
		echoo("___________________________");
		echoo("Enviando email para os contratos cadastrados.");		
		
		foreach ( $colecao as $registro ) {
			try {
				$dbMensageriaRegistro->incluirComEnvioEmail ( $registro );
			} catch ( Exception $e ) {
				echoo ( $e->getMessage () );
			}
		}
	}else{
		echoo("Mensageria: não existem contratos cadastrados.");
	}
}

