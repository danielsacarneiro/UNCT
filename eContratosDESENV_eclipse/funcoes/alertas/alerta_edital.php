<?php
require_once ("alerta_demanda_geral.php");

$assunto = "EDITAL:";

try {
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	
	$filtro = new filtroManterDemanda( false );
	$filtro->isValidarConsulta = false;
	// $filtro->voPrincipal = $voDemanda;
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	$filtro->vodemanda->situacao = array (
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
	);

	$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL);	
	$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
	
	//$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Citação', voPA::$nmAtrDtNotificacao, constantes::$CD_TP_DADO_DATA);	
	exibeAlertaDemandasPorFiltroDemanda($filtro, true, $assunto, $colunasAAcrescentar);

} catch ( Exception $ex ) {
	$msg = $ex->getMessage ();
	echo $msg;
}

