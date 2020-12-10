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
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'C�digo.PL', voProcLicitatorio::$nmAtrCd, constantes::$TAMANHO_CODIGOS_SAFI);
		
		$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, true);
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}
	
	return $msg;
}

function getMensagemPAAPAbertoNaoEncaminhado(&$count = 0){
	$assunto = "PAAP�S PENDENTES DE ABERTURA:";
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
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclus�o', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.�ltima.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);
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
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'N� PAAP', voPA::$nmAtrCdPA, constantes::$TAMANHO_CODIGOS_SAFI);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'RESPONS�VEL', filtroConsultarDemandaPAAP::$NmColRESP_PAAP);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclus�o', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.�ltima.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);

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
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'N� PAAP', voPA::$nmAtrCdPA, constantes::$TAMANHO_CODIGOS_SAFI);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'RESPONS�VEL', filtroConsultarDemandaPAAP::$NmColRESP_PAAP);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.Inclus�o', voPA::$nmAtrDhInclusao, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Dt.�ltima.Mov', filtroManterDemanda::$NmColDhUltimaMovimentacao, constantes::$CD_TP_DADO_DATA);
		
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
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_UNCT;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColNuTempoUltimaTram;
		$filtro->inDesativado = constantes::$CD_NAO;
		
		$coluna1 =array(
				constantes::$CD_COLUNA_CHAVE => "Sistema",
				constantes::$CD_COLUNA_VALOR => vodemanda::$nmAtrTipo,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO =>  "dominioTipoDemanda",
		);
		
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $coluna1);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONS�VEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);		

		//$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, false);
		$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, false);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemContratosNaoIncluidosPlanilha(&$count = 0){
	$assunto = "Contratos n�o inclu�dos na planilha:";
	$assunto = getSequenciaAssunto($assunto, $count);

	try {

		$db = new dbcontrato();
		$colecao = $db->consultarContratosNaoIncluidosPlanilha();

		/*$coluna1 =array(
				constantes::$CD_COLUNA_CHAVE => "Sistema",
				constantes::$CD_COLUNA_VALOR => vodemanda::$nmAtrTipo,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO =>  "dominioTipoDemanda",
		);

		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $coluna1);*/

		$colunas = incluirColunaColecao($colunas, 'TIPO', voDemandaContrato::$nmAtrTipoContrato);
		$colunas = incluirColunaColecao($colunas, 'ANO', voDemandaContrato::$nmAtrAnoContrato);		
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrCdContrato);
		$colunas = incluirColunaColecao($colunas, 'ESPECIE', voDemandaContrato::$nmAtrCdEspecieContrato);
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrSqEspecieContrato);

		//$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, false);
		//$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);
		$msg = getCorpoMensagemPorColecao($assunto, $colecao, $colunas);
		

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
	$assunto = "CONTRATOS A VENCER (".voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER." dias):";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		//pega todos os contratos a vencer no prazo
		$filtro = getFiltroContratosAVencer();
		
		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro, true);
		
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'In�cio.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);		
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}


/** ALERTAS DILC **/

function getMensagemContratosAVencerGestor(&$count = 0){
	$assunto = "CONTRATOS A VENCER (".voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER." dias) SEM DEMANDA INICIADA (COMUNICAR AO GESTOR):";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = getFiltroContratosAVencer(constantes::$CD_NAO);

		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro, true);

		/*$array =array(
		 constantes::$CD_COLUNA_CHAVE => 'RESPONS�VEL',
		 constantes::$CD_COLUNA_VALOR => voDemanda::$nmAtrCdPessoaRespUNCT,
		 );
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$array =array(
		constantes::$CD_COLUNA_CHAVE => 'PRAZO',
		constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
		constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
		constantes::$CD_COLUNA_VL_REFERENCIA =>  15,
		constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);*/

		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'In�cio.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);

		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemContratosAVencerImprorrogaveisGestor(&$count = 0){
	$assunto = "CONTRATOS A VENCER (". voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER_IMPRORROGAVEIS ." dias)". getTextoHTMLDestacado("IMPRORROG�VEIS"). " SEM DEMANDA INICIADA (COMUNICAR AO GESTOR):";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = getFiltroContratosAVencerImprorrog(constantes::$CD_NAO);
				
		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro, true);

		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'In�cio.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vig�ncia', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandasMonitoradas(&$count = 0){
	$assunto = "DEMANDAS MONITORADAS:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterDemanda ( false );
		$voDemanda = new voDemanda ();
		$dbprocesso = new dbDemanda();
		$filtro->voPrincipal = $voDemanda;
		$filtro->isValidarConsulta = false;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();

		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		
		$filtro->inMonitorar = constantes::$CD_SIM;
		$filtro->numPrazoMonitorar = voDemanda::$NUM_PRAZO_MONITORAMENTO;

		//$filtro->inCdResponsavelUNCT = constantes::$CD_OPCAO_NENHUM;
		//$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColNuTempoUltimaTram;
		$filtro->cdAtrOrdenacao = voDemanda::$nmAtrCdPessoaRespUNCT . "," . filtroManterDemanda::$NmColNuTempoUltimaTram;

		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'SETOR',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioSetor",
				constantes::$CD_COLUNA_VALOR => voDemandaTramitacao::$nmAtrCdSetorDestino,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONS�VEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'PRAZO',
				constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  15,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandaIniciais(&$count = 0){
	$assunto = "DEMANDAS A FAZER:";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterDemanda ( false );
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;
		$filtro->voPrincipal = $voDemanda;
		$filtro->isValidarConsulta = false;	
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
		);
		/*$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );*/
		$filtro->tipoExcludente = array(
				dominioTipoDemanda::$CD_TIPO_DEMANDA_LICON,
				dominioTipoDemanda::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA
		);

		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_UNCT;
		
		//$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;
		/*$filtro->vocontrato->cdAutorizacao = array (
				dominioAutorizacao::$CD_AUTORIZ_SAD
		);*/
		/*$filtro->vodemanda->fase = constantes::$CD_OPCAO_NENHUM;
		$filtro->inOR_AND_Fase = constantes::$CD_OPCAO_AND;*/
		
		//$filtro->inCdResponsavelUNCT = constantes::$CD_OPCAO_NENHUM;
		//$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColNuTempoUltimaTram;
		$filtro->cdAtrOrdenacao = voDemanda::$nmAtrCdPessoaRespUNCT . "," . filtroManterDemanda::$NmColNuTempoUltimaTram;		

		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
				
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONS�VEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);		
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'PRAZO',
				constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  15,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}