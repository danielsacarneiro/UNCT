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

function getMensagemAltaPrioridade(&$count = 0){
	$assunto = "ALTA PRIORIDADE:";	
	$assunto = getSequenciaAssunto($assunto, $count);
	
	try {
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;
	
		$filtro = new filtroManterDemanda( false );
		$filtro->isValidarConsulta = false;
		$filtro->voPrincipal = $voDemanda;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		//$filtro->inDesativado = constantes::$CD_NAO;
	
		//$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL);
		$filtro->vodemanda->prioridade = dominioPrioridadeDemanda::$CD_PRIORI_ALTA;
		$filtro->tipoExcludente = dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM;
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Ano.PL', voProcLicitatorio::$nmAtrAno);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Código.PL', voProcLicitatorio::$nmAtrCd, constantes::$TAMANHO_CODIGOS_SAFI);
		
		$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, true);
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}
	
	return $msg;
}

function getMensagemPAAPAbertoNaoEncaminhado(&$count = 0){
	$assunto = "PAAP´S PENDENTES DE ABERTURA:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroConsultarDemandaPAAP( false );
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO,
		);

		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM);
		//$filtro->voPA->situacao = array_keys(dominioSituacaoPA::getColecaoSituacaoAtivos());
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->inComPAAPInstaurado = constantes::$CD_NAO;
		$filtro->InVerificarPrazo = constantes::$CD_NAO;
		$filtro->inDesativado = constantes::$CD_NAO;

		//art. 25, inciso II, do Decreto n42.191/2015
		//$filtro->qtdDiasPrazo = 10;

		$dbprocesso = new dbPA();
		$colecao = $dbprocesso->consultarDemandaPAAP($filtro );
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclusão', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Última.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dias.Abertos', filtroManterDemanda::$NmColQtdDiasDataDtReferencia, null, 20, constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE);

		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemPAAPAExecutar(&$count = 0){
	$assunto = "PAAPs Com Multa A Executar:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroConsultarDemandaPAAP( false );
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				//dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);

		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM);
		$filtro->voPA->situacao = array(dominioSituacaoPA::$CD_SITUACAO_PA_EM_COBRANCA);
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;

		//art. 25, inciso II, do Decreto n42.191/2015
		//$filtro->qtdDiasPrazo = 10;

		$dbprocesso = new dbPA();
		$colecao = $dbprocesso->consultarDemandaPAAP($filtro );
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Ano PAAP', voPA::$nmAtrAnoPA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Nº PAAP', voPA::$nmAtrCdPA, constantes::$TAMANHO_CODIGOS_SAFI);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'RESPONSÁVEL', filtroConsultarDemandaPAAP::$NmColRESP_PAAP);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclusão', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Última.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);

		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar, true);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemFimPrazoPAAP(&$count = 0){
	$assunto = "PAAPs EM ANDAMENTO:";	
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroConsultarDemandaPAAP( false );
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				//dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
	
		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_PROCADM);
		$filtro->voPA->situacao = array_keys(dominioSituacaoPA::getColecaoSituacaoAtivosFaseInstrucao());
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		//ignora do alerta os processos com prioridade BAIXA
		$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;
	
		//art. 25, inciso II, do Decreto n42.191/2015
		//$filtro->qtdDiasPrazo = 10;
	
		$dbprocesso = new dbPA();
		$colecao = $dbprocesso->consultarDemandaPAAP($filtro );	
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Ano PAAP', voPA::$nmAtrAnoPA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Nº PAAP', voPA::$nmAtrCdPA, constantes::$TAMANHO_CODIGOS_SAFI);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'RESPONSÁVEL', filtroConsultarDemandaPAAP::$NmColRESP_PAAP);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclusão', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Última.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar, true);
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandaContratoPropostaVencida(&$count = 0){
	$assunto = "REAJUSTES:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = getFiltroManterDemandaDataBaseReajusteVencida();
		$colecao = consultarFiltroManterDemandaTelaConsulta($filtro);
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, null);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandaSAD(&$count = 0){
	$assunto = "DEMANDAS SAD:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterDemanda ( false );
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;
		$filtro->voPrincipal = $voDemanda;
		
		$filtro->isValidarConsulta = false;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );
		$filtro->tipoExcludente = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO_MATER);
		
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;
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

function getMensagemSistemasExternos(&$count = 0){
	$assunto = "Sistemas Externos:";
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

		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA, dominioTipoDemanda::$CD_TIPO_DEMANDA_LICON);
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		
		$coluna1 =array(
				constantes::$CD_COLUNA_CHAVE => "Sistema",
				constantes::$CD_COLUNA_VALOR => vodemanda::$nmAtrTipo,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO =>  "dominioTipoDemanda",
		);
		
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $coluna1);

		$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, false);

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