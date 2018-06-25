<?php
//envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

$enviarEmail = true;

$count = 0;
//envia alertas dos editais

$filtro = new filtroManterMensageria(false );
$filtro->isValidarConsulta = false;
// $filtro->voPrincipal = $voDemanda;
$filtro->setaFiltroConsultaSemLimiteRegistro ();

$dbMensageria = new dbMensageria();
$colecao = $dbMensageria->consultarTelaConsulta(new voMensageria(), $filtro);

foreach ($colecao as $registro){

	enviarEmailGestor($registro, $enviarEmail);
	
}

function enviarEmailGestor($registro, $enviarEmail){
	
	$vomensageria = new voMensageria();
	$vomensageria->getDadosBanco($registro);	
	$vocontratoinfo = $vomensageria->vocontratoinfo;
		
	$emailGestor = $registro[vopessoa::$nmAtrEmail];
	
	$listaEmailTemp = email_sefaz::getListaEmailAvisoGestorContrato();
	$array2 = array($emailGestor);
	$listaEmailTemp = array_merge($listaEmailTemp, $array2);	
	
	try {
		
		$assunto = "AVISO";
		$mensagem = getMensagemGestor($vocontratoinfo, $emailGestor);
		
		enviarEmail($assunto, $mensagem, $enviarEmail, $listaEmailTemp);
		/*echoo($emailGestor);
			echoo($mensagem);*/
		//incluir o registro do envio
	
	} catch ( Exception $ex ) {
		echo $msg = $ex->getMessage ();
	}
	
}

function getMensagemGestor($vocontratoinfo, $emailGestor){
	//$vocontrato = new vocontrato();
	$assunto = "COMUNICAÇÃO:";
	
	$isAlertaValido = $emailGestor != null && $emailGestor != "";
	try {
		$codigo = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
		$msg = "<br><br>Caro Gestor, favor verificar o vencimento do contrato $codigo.";
		
		if(!$isAlertaValido){
			$mensagem = "<br><br>Contrato $codigo SEM E-MAIL VÁLIDO para o Gestor.";
		}
		

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

