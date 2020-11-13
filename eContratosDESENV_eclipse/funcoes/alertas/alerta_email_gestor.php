<?php
// envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

$ativado = voMensageria::$ATIVADO;
$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];

//se for nulo, eh pq veio do disparo automatico
$isEnvioEmail = $enviarEmail == null || getAtributoComoBooleano($enviarEmail);
$isEnvioEmail = $isEnvioEmail && voMensageria::$ENVIAR_EMAIL_GESTOR_UNCT;

if(!$ativado){
	echoo ("Mensageria Desativado.");
}else{
	if(!$isEnvioEmail){
		echoo ("Mensageria: Sele��o N�O ENVIAR email para os contratos cadastrados.");
	}
}

$enviarEmailAlerta = $ativado && $isEnvioEmail;
if ($enviarEmailAlerta) {	
	echoo("___________________________");
	$log .= getLogComFlagImpressao("___________________________");
	//consulta os contratos que nao tem alerta para cria-los
	$log .= criarAlertasEmailGestorColecaoContratos();
	echoo($log);
	
	//busca os alertas a enviar
	$filtro = new filtroManterMensageria ( false );
	$filtro->isValidarConsulta = false;
	$filtro->inHabilitado = constantes::$CD_SIM;
	$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
	//pega somente os alertas para os contratos que serao prorrogados
	$filtro->inSeraProrrogado = constantes::$CD_SIM;
	//$filtro->inVerificarFrequencia = constantes::$CD_NAO;
	$filtro->inVerificarFrequencia = voMensageria::$IN_VERIFICAR_FREQUENCIA;
	echoo("Verificador de Frequ�ncia do email: '$filtro->inVerificarFrequencia'.");
	
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	
	$dbMensageria = new dbMensageria ();
	$colecao = $dbMensageria->consultarTelaConsulta ( new voMensageria (), $filtro );
	
	$dbMensageriaRegistro = new dbMensageriaRegistro ();
	if (! isColecaoVazia ( $colecao )) {
		
		echoo("___________________________");
		echoo("Enviando email para os contratos cadastrados.");		
		
		$log .= getLogComFlagImpressao("___________________________");
		$log .= getLogComFlagImpressao("Enviando email para os contratos cadastrados.");
		
		foreach ( $colecao as $registro ) {
			try {
				$log .= $dbMensageriaRegistro->incluirComEnvioEmail ( $registro );
			} catch ( Exception $e ) {
				echoo ( $e->getMessage () );
				$log .= getLogComFlagImpressao($e->getMessage ());
			}
		}
	}else{
		echoo("Mensageria: n�o existem alertas para o dia de hoje.");
		$log .= getLogComFlagImpressao("Mensageria: n�o existem alertas para o dia de hoje.");
	}
	
	echoo("FIM Mensageria.");
	$log .= getLogComFlagImpressao("FIM Mensageria.");
	
	//envia email com o log
	enviarEmail("Log execu��o cria��o de alertas autom�tico", $log, true, email_sefaz::getListaEmailLogAlertasGestor());	
	
}

