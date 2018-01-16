<?php
/***
 * 
 * @param unknown $assunto
 * @param number $count //tem um & na frente pra indicar que eh passagem por referencia
 * @return string
 */
function getSequenciaAssunto($assunto, &$count = 0){
	$count++;
	$assunto = "$count - $assunto";
	return $assunto;
}

function getMensagemEdital(&$count = 0){
	$assunto = "EDITAL:";	
	$assunto = getSequenciaAssunto($assunto, $count);
	
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
		
		$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, null);
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}
	
	return $msg;
}

function getMensagemFimPrazoPAAP(&$count = 0){
	$assunto = "PAAP:";	
	$assunto = getSequenciaAssunto($assunto, $count);
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
	
		//art. 25, inciso II, do Decreto n42.191/2015
		$filtro->qtdDiasPrazo = 10;
	
		$dbprocesso = new dbPA();
		$colecao = $dbprocesso->consultarDemandaPAAP($filtro );	
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Citação', voPA::$nmAtrDtNotificacao, constantes::$CD_TP_DADO_DATA);		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, null);
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandaContratoPropostaVencida(&$count = 0){
	$assunto = "REAJUSTES:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterDemanda ( false );
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;
		
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		$filtro->inContratoComDtPropostaVencida = constantes::$CD_SIM;
		//$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );
		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE);
		
		$filtro->vocontrato->dtProposta = getDataHoje();
		//$filtro->vocontrato->dtProposta = "11/11/2017";
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );

		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, null);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandaSAD(&$count = 0){
	$assunto = "ENCAMINHAMENTO SAD:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterDemanda ( false );
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;
		
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->vocontrato->cdAutorizacao = array (
				dominioAutorizacao::$CD_AUTORIZ_SAD
		);
		//de acordo com a portaria 1.116/2016 (em se tratando de reajuste, o contrato de locacao de imovel prescinde de autorizacao da SAD)
		$filtro->inRetornarReajusteSeLocacaoImovel = constantes::$CD_NAO;
		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, null);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

/**
 * 
 * @return string
 */
function getMensagemContratosAVencer(&$count = 0){
	$assunto = "CONTRATOS A VENCER:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroConsultarContratoConsolidacao(false);
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		
		$filtro->tpVigencia = dominioTpVigencia::$CD_OPCAO_VIGENTES;
		$filtro->qtdDiasParaVencimento = 120;
		$filtro->cdHistorico = constantes::$CD_NAO;
		$filtro->voPrincipal = new voContratoInfo();
		
		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao($filtro );
		
		$colunasAAcrescentar = null;
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Início.Vigência', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vigência', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);		
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}