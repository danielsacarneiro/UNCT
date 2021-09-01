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

function getMensagemPorSituacao(&$count = 0, $cdSetor=null, $cdSituacao=null){
	if($cdSituacao==null){
		$cdSituacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER;
	}

	if($cdSetor==null){
		$cdSetor = dominioSetor::$CD_SETOR_UNCT;
	}
	
	$dsSetor = dominioSetor::getDescricao($cdSetor);
	$dsSetor = "($dsSetor)";
	
	$dsSituacao = dominioSituacaoDemanda::getDescricao($cdSituacao);
	$dsSituacao = "'$dsSituacao'";
	
	$assunto = "DEMANDAS $dsSituacao $dsSetor:";
	$assunto = getSequenciaAssunto($assunto, $count);

	try {
		$voDemanda = new voDemanda ();
		$dbprocesso = $voDemanda->dbprocesso;

		$filtro = new filtroManterDemanda( false );
		$filtro->isValidarConsulta = false;
		$filtro->voPrincipal = $voDemanda;
		// $filtro->voPrincipal = $voDemanda;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = $cdSituacao;
		//$filtro->inDesativado = constantes::$CD_NAO;
		//$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_EDITAL);
		//$filtro->vodemanda->prioridade = dominioPrioridadeDemanda::$CD_PRIORI_ALTA;
		$filtro->vodemanda->cdSetorDestino = $cdSetor;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;

		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
		$array =array(
					constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
					constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$pArray = array($assunto, $colecao, $colunasAAcrescentar, false, null, false, true);
		$msg = getCorpoMensagemDemandaContratoArray($pArray);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemAltaPrioridade(&$count = 0, $cdSetor=null, $comInfoCompletaDemanda=false){
	if($cdSetor==null){
		$cdSetor = dominioSetor::$CD_SETOR_ATJA;
	}
	
	$dsSetor = dominioSetor::getDescricao($cdSetor);
	$dsSetor = "($dsSetor)";
	
	$assunto = "ALTA PRIORIDADE $dsSetor:";
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
		$filtro->vodemanda->cdSetorDestino = $cdSetor;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		$filtro->fasePlanilha = array(dominioFaseDemanda::$CD_PUBLICADO . CAMPO_SEPARADOR . "N");
				
		if($comInfoCompletaDemanda){
			$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );			
			$array =array(
					constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
					constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
			);
			$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
						
			$pArray = array($assunto, $colecao, $colunasAAcrescentar, false, null, true);
			$msg = getCorpoMensagemDemandaContratoArray($pArray);
			//$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);
		}else{
			$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Ano.PL', voProcLicitatorio::$nmAtrAno);
			$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Código.PL', voProcLicitatorio::$nmAtrCd, constantes::$TAMANHO_CODIGOS_SAFI);				
			$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, true);
		}
	
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
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_UNCT;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColNuTempoUltimaTram;
		$filtro->inDesativado = constantes::$CD_NAO;
		$filtro->voPrincipal = $voDemanda;
		
		$coluna1 =array(
				constantes::$CD_COLUNA_CHAVE => "Sistema",
				constantes::$CD_COLUNA_VALOR => vodemanda::$nmAtrTipo,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO =>  "dominioTipoDemanda",
		);
		
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $coluna1);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
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
	$assunto = "Contratos não incluídos:";
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

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'TIPO',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioTipoContrato",
				constantes::$CD_COLUNA_VALOR => voDemandaContrato::$nmAtrTipoContrato,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrCdContrato);
		$colunas = incluirColunaColecao($colunas, 'ANO', voDemandaContrato::$nmAtrAnoContrato);

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'ESPECIE',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioEspeciesContrato",
				constantes::$CD_COLUNA_VALOR => voDemandaContrato::$nmAtrCdEspecieContrato,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrSqEspecieContrato);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
				constantes::$CD_COLUNA_VALOR => vousuario::$nmAtrName,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);

		//$msg = getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar, false);
		//$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);
		$msg = getCorpoMensagemPorColecao($assunto, $colecao, $colunas);


	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemContratosNaoRegistradosLivro(&$count = 0){
	$assunto = "Contratos não registrados:";
	$assunto = getSequenciaAssunto($assunto, $count);

	try {

		$db = new dbRegistroLivro();
		$colecao = $db->consultarContratosNaoRegistrados();

		/*$coluna1 =array(
				constantes::$CD_COLUNA_CHAVE => "Sistema",
				constantes::$CD_COLUNA_VALOR => vodemanda::$nmAtrTipo,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO =>  "dominioTipoDemanda",
		);

		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $coluna1);*/
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'TIPO',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioTipoContrato",				
				constantes::$CD_COLUNA_VALOR => voDemandaContrato::$nmAtrTipoContrato,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrCdContrato);
		$colunas = incluirColunaColecao($colunas, 'ANO', voDemandaContrato::$nmAtrAnoContrato);		
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'ESPECIE',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioEspeciesContrato",
				constantes::$CD_COLUNA_VALOR => voDemandaContrato::$nmAtrCdEspecieContrato,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);		
		$colunas = incluirColunaColecao($colunas, 'NUMERO', voDemandaContrato::$nmAtrSqEspecieContrato);
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'DATA INCL.',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DATA,
				//constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioTipoContrato",
				constantes::$CD_COLUNA_VALOR => voDemandaContrato::$nmAtrDhInclusao,
		);
		$colunas = incluirColunaColecaoArray($colunas, $array);
		
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
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro);
		
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Início.Vigência', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vigência', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);		
		
		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}


/** ALERTAS DILC **/

function getMensagemEmailsGestorNaoRespondidos(&$count = 0){
	$assunto = "CONTRATOS COM "
			. getTextoHTMLDestacado(complementarCharAEsquerda(voMensageria::$NUM_MSGS_MAX_NAO_RESPONDIDAS, "0",3). "(OU MAIS) EMAILS  NAO RESPONDIDOS") 
			." PELO GESTOR."
			. getTextoHTMLDestacado("<br>*** Sugere-se gestão junto aos responsáveis.");
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = new filtroManterMensageria(false );
		$vo = new voMensageria();
		$dbprocesso = new dbMensageria();
		
		$filtro->voPrincipal = $vo;
		//$filtro->isValidarConsulta = false;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		
		$filtro->tpVigencia = dominioTpVigencia::$CD_OPCAO_VIGENTES;
		$filtro->inHabilitado = constantes::$CD_SIM;
		$filtro->numMsgsEnviadas = voMensageria::$NUM_MSGS_MAX_NAO_RESPONDIDAS;
		$filtro->inSeraProrrogado = constantes::$CD_SIM;
		
		$nmTabelaMsgMax = filtroManterMensageria::$NM_TAB_MSGREGISTRO_MAX_SQ;
		$nmColunaNumMsgsEnviadas = voMensageriaRegistro::$nmAtrSq;
		$filtro->cdAtrOrdenacao = $nmColunaNumMsgsEnviadas . " DESC";		

		$colecao = $dbprocesso->consultarTelaConsulta($vo, $filtro);
		
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, constantes::$CD_COLUNA_CONTRATO, null);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Gestor', voContratoInfo::$IDREQNmPessoaGestor);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Email', vopessoa::$nmAtrEmail);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Encerramento', voMensageria::$nmAtrDtFim, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Num.Emails', $nmColunaNumMsgsEnviadas);

		$msg = getCorpoMensagemPorColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemContratosAVencerGestor(&$count = 0){
	$assunto = "CONTRATOS A VENCER (".voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER." dias) SEM DEMANDA INICIADA (COMUNICAR AO GESTOR):";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = getFiltroContratosAVencer(constantes::$CD_NAO);

		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro);
		
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, constantes::$CD_COLUNA_CONTRATO, null);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Início.Vigência', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vigência', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		//$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'Prazo(dias)',
				constantes::$CD_COLUNA_VALOR => filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  voMensageria::$NUM_DIAS_AVISO_ENCERRAMENTO_CONTRATO_PADRAO,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MENORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$msg = getCorpoMensagemPorColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemContratosAVencerImprorrogaveisGestor(&$count = 0){
	$assunto = "CONTRATOS A VENCER (". voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER_IMPRORROGAVEIS ." dias)". getTextoHTMLDestacado("IMPRORROGÁVEIS"). " SEM DEMANDA INICIADA (COMUNICAR AO GESTOR):";
	$assunto = getSequenciaAssunto($assunto, $count);
	try {
		$filtro = getFiltroContratosAVencerImprorrog(constantes::$CD_NAO);
				
		$dbprocesso = new dbContratoInfo();
		$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro);

		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, constantes::$CD_COLUNA_CONTRATO, null);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Início.Vigência', filtroConsultarContratoConsolidacao::$NmColDtInicioVigencia, constantes::$CD_TP_DADO_DATA);
		$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Fim.Vigência', filtroConsultarContratoConsolidacao::$NmColDtFimVigencia, constantes::$CD_TP_DADO_DATA);
		//$colunasAAcrescentar = incluirColunaColecao($colunasAAcrescentar, 'Prazo(dias)', filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'Prazo(dias)',
				constantes::$CD_COLUNA_VALOR => filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  3*(voMensageria::$NUM_DIAS_AVISO_ENCERRAMENTO_CONTRATO_PADRAO),
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MENORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		
		$msg = getCorpoMensagemPorColecao($assunto, $colecao, $colunasAAcrescentar);

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
		
		$filtro->inMonitorar = voDemanda::$CD_MONITORAR_POR_DATA;
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
				constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'PRAZO',
				constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  voMensageria::$NUM_DIAS_AVISO_ENCERRAMENTO_CONTRATO_PADRAO,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$pArray = array($assunto, $colecao, $colunasAAcrescentar, false, "isAlertaFormatarCelulaDemandaMonitorada");
		$msg = getCorpoMensagemDemandaContratoArray($pArray);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}

function getMensagemDemandasDeContratosAVencer(&$count = 0){
	$assunto = "URGENTE => DEMANDAS CUJOS CONTRATOS VENCERAM OU VENCERÃO EM BREVE:";
	$assunto = getTextoHTMLDestacado($assunto) ;
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
		/*$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );*/
		$filtro->tipoExcludente = array(
				dominioTipoDemanda::$CD_TIPO_DEMANDA_LICON,
				dominioTipoDemanda::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA
		);
		
		$filtro->vodemanda->cdSetorDestino = array(dominioSetor::$CD_SETOR_DIFIN, dominioSetor::$CD_SETOR_UNCT);
		//traz as demandas que estao com os contratos vencidos ou prestes a vencer
		$filtro->inDemandasContratosAVencer = true;		
		$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;

		//$filtro->cdAtrOrdenacao = voDemanda::$nmAtrCdPessoaRespUNCT . "," . filtroManterDemanda::$NmColNuTempoUltimaTram;		
		$filtro->cdAtrOrdenacao =  filtroManterDemanda::$NmColNuTempoUltimaTram . " DESC ";

		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
				
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);		
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'SETOR',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioSetor",
				constantes::$CD_COLUNA_VALOR => voDemandaTramitacao::$nmAtrCdSetorDestino,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'DT.<BR>REFERENCIA',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NmColDtLimiteContratoAVencer,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$CD_TP_DADO_DATA,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  somarOuSubtrairDiasNaData(getDataHoje(), 10),
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
				constantes::$CD_COLUNA_OPERACAO_VALOR_REFERENCIA =>  "isDataFimMaiorDataInicio",
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
				
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'NO SETOR(dias)',
				constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  constantes::$qts_dias_ALERTA_DEMANDA_CONTRATO_AVENCER,
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
	$assunto = "DEMANDAS A PROVIDENCIAR ANDAMENTO:";
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
		//$filtro->vodemanda->tipo = dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO;
		$filtro->tipoExcludente = array(
				dominioTipoDemanda::$CD_TIPO_DEMANDA_LICON,
				dominioTipoDemanda::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA,
				dominioTipoDemanda::$CD_TIPO_DEMANDA_GESTAO_PESSOAS,
		);
		$filtro->cdSetorAtualExcludente = array(
				//dominioSetor::$CD_SETOR_DILC,
				dominioSetor::$CD_SETOR_SAFI,
				dominioSetor::$CD_SETOR_ATJA,
		);
		$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;
		
		$filtro->inCdResponsavelUNCT = constantes::$CD_SIM;
		//$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_UNCT;
		//busca das demandas que estao a mais de 15 dias sem movimentacao
		//$filtro->dtUltMovimentacaoInicial
		$filtro->nuTempoVidaMinimoUltimaTram = 10;

		$filtro->cdAtrOrdenacao = voDemanda::$nmAtrCdPessoaRespUNCT . "," . filtroManterDemanda::$NmColNuTempoUltimaTram;

		//echo "pegar esse aqui";
		$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'RESPONSÁVEL',
				constantes::$CD_COLUNA_VALOR => filtroManterDemanda::$NM_COL_NOME_RESP_UNCT,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);

		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'MONITOR.',
				constantes::$CD_COLUNA_VALOR => voDemanda::$nmAtrInMonitorar,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  constantes::$CD_SIM,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_IGUAL,
				//constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				//constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioSimNao", 				
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'SETOR',
				constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
				constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioSetor",
				constantes::$CD_COLUNA_VALOR => voDemandaTramitacao::$nmAtrCdSetorDestino,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);
		
				
		$array =array(
				constantes::$CD_COLUNA_CHAVE => 'PRAZO',
				constantes::$CD_COLUNA_VALOR => filtroConsultarDemandaGestao::$NmColNuTempoUltimaTram,
				constantes::$CD_COLUNA_TP_DADO =>  constantes::$TAMANHO_CODIGOS_SAFI,
				constantes::$CD_COLUNA_VL_REFERENCIA =>  voMensageria::$NUM_DIAS_AVISO_ENCERRAMENTO_CONTRATO_PADRAO,
				constantes::$CD_COLUNA_TP_VALIDACAO =>  constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE,
		);
		$colunasAAcrescentar = incluirColunaColecaoArray($colunasAAcrescentar, $array);


		$msg = getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar);

	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
	}

	return $msg;
}