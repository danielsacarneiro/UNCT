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
		echoo ("Mensageria: Seleção NÃO ENVIAR email para os contratos cadastrados.");
	}
}

$enviarEmailAlerta = $ativado && $isEnvioEmail;
if ($enviarEmailAlerta) {	
	//echoo("___________________________");
	$log .= getLogComFlagImpressao("___________________________");
	//consulta os contratos que nao tem alerta para cria-los
	$log .= criarAlertasEmailGestorColecaoContratosImprorrog();
	
	//echoo("___________________________");
	$log .= getLogComFlagImpressao("___________________________");
	$log .= criarAlertasEmailGestorColecaoContratos();
	
	//busca os alertas a enviar
	$filtro = new filtroManterMensageria ( false );
	$filtro->isValidarConsulta = false;
	$filtro->inHabilitado = constantes::$CD_SIM;
	$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
	//pega somente os alertas para os contratos que serao prorrogados
	$filtro->inSeraProrrogado = constantes::$CD_SIM;
	//$filtro->inVerificarFrequencia = constantes::$CD_NAO;
	$filtro->inVerificarFrequencia = voMensageria::$IN_VERIFICAR_FREQUENCIA;
	//echoo("Verificador de Frequência do email: '$filtro->inVerificarFrequencia'.");
	$log .= getLogComFlagImpressao("<br>Verificador de Frequência do email: '$filtro->inVerificarFrequencia'.");
	
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	
	$dbMensageria = new dbMensageria ();
	$colecao = $dbMensageria->consultarTelaConsulta ( new voMensageria (), $filtro );
	
	$dbMensageriaRegistro = new dbMensageriaRegistro ();
	if (! isColecaoVazia ( $colecao )) {
		
		//echoo("<br>___________________________");
		//echoo("<br>Enviando email para os contratos cadastrados.");		
		
		$log .= getLogComFlagImpressao("<br>___________________________");
		$log .= getLogComFlagImpressao("<br>Enviando email para os contratos cadastrados.");
		
		foreach ( $colecao as $registro ) {
			try {
				$log .= $dbMensageriaRegistro->incluirComEnvioEmail ( $registro );
			} catch ( Exception $e ) {
				//echoo ( $e->getMessage () );
				$log .= getLogComFlagImpressao("<br>".$e->getMessage ());
			}
		}
	}else{
		//echoo("<br>Mensageria: não existem alertas para o dia de hoje.");
		$log .= getLogComFlagImpressao("<br>Mensageria: não existem alertas para o dia de hoje.");
	}
	
	//echoo("<br>FIM Mensageria.");
	$log .= getLogComFlagImpressao("<br>FIM Mensageria.");
	
	//envia email com o log
	enviarEmail("Log execução criação de alertas automático", $log, true, email_sefaz::getListaEmailLogAlertasGestor());
	
	echoo($log);	
}

