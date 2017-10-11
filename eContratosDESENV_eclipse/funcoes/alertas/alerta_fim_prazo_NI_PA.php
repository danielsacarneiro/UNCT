<?php
require_once ("alerta_demanda_geral.php");

$assunto = "PAAP: PRAZOS.";

try {
	$filtro = new filtroConsultarDemandaPAAP( false );
	$filtro->isValidarConsulta = false;
	// $filtro->voPrincipal = $voDemanda;
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	$filtro->vodemanda->situacao = array (
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
	);

	$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM);
	$filtro->voPA->situacao = array_keys(dominioSituacaoPA::getColecaoSituacaoAtivos());	
	$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
	$filtro->qtdDiasPrazo = 15;
	
	$dbprocesso = new dbPA();
	$colecao = $dbprocesso->consultarDemandaPAAP($filtro );
		
	exibeAlertaDemandasPorColecao($colecao, true, $assunto);	

} catch ( Exception $ex ) {
	$msg = $ex->getMessage ();
	echo $msg;
}

