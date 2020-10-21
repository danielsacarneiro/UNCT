<?php

function getDemandaDetalhamento($voDemanda, $exibeTipoDemanda = true, $colspan=null){
	return getDemandaDetalhamentoComLupa($voDemanda, true, $exibeTipoDemanda, $colspan);
	
}
function getDemandaDetalhamentoComLupa($voDemanda, $temLupaDet, $exibeTipoDemanda = true, $colspan=null, $isAlteracaoDemanda = false){
	
	$comComplementos = !$isAlteracaoDemanda;
	
	if($colspan==null){
		$colspan=3;
	}
	?>
	<TR>
	<TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	<TD class="campoformulario" colspan=<?=$colspan?>>
	<?php	
	echo getDetalhamentoHTMLCodigoAno($voDemanda->ano, $voDemanda->cd);
		
	if($exibeTipoDemanda){	
		if($voDemanda->tipo != null){
			$comboTipo = new select(dominioTipoDemanda::getColecao());
			//echo "Tipo: " . $comboTipo->getHtmlCombo("","", $voDemanda->tipo, true, "camporeadonly", false, " disabled ");
			echo "Tipo: " . getInputText("", "", dominioTipoDemanda::getDescricaoStaticTeste($voDemanda->tipo),constantes::$CD_CLASS_CAMPO_READONLY);
			
			if ($voDemanda != null && $temLupaDet) {
				//$voDemanda = new voDemanda();
				echo getLinkPesquisa ( "../demanda/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $voDemanda->getValorChaveHTML() );
			}
				
			if(dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO == $voDemanda->tipo){				
				echo getTpDemandaContratoDetalhamento(voDemanda::$nmAtrTpDemandaContrato, "", "DIV_DETALHAR", $voDemanda);				
			}			
			echo "<INPUT type='hidden' id='" . voDemanda::$nmAtrTipo . "' name='" . voDemanda::$nmAtrTipo . "' value='$voDemanda->tipo'>";
		}
	}
		
	//$voDemanda = new voDemanda();
	if($voDemanda->texto != null){
	?>
         <br>Título: <INPUT type="text" value="<?=getVarComoStringHTML($voDemanda->texto)?>"  class="camporeadonly" size="70" readonly>		
	<?php
	}
	?>		            
		<INPUT type="hidden" id="<?=voDemanda::$nmAtrAno?>" name="<?=voDemanda::$nmAtrAno?>" value="<?=$voDemanda->ano?>">
		<INPUT type="hidden" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>" value="<?=$voDemanda->cd?>">		
		<INPUT type="hidden" id="<?=voDemanda::$nmAtrCdSetor?>" name="<?=voDemanda::$nmAtrCdSetor?>" value="<?=$voDemanda->cdSetor?>">
		<INPUT type="hidden" id="<?=voDemanda::$nmAtrSituacao?>" name="<?=voDemanda::$nmAtrSituacao?>" value="<?=$voDemanda->situacao?>">
	</TR>
	<?php	
	$dbprocesso = $voDemanda->dbprocesso;
	$voPAAP = $dbprocesso->consultarPAAPDemanda($voDemanda, false);
	if($voPAAP != null){
		require_once (caminho_funcoes . voPA::getNmTabela() . "/biblioteca_htmlPA.php");
		getPAAPDetalhamento($voPAAP);
	}
		
	if($comComplementos){
		require_once (caminho_funcoes . voSolicCompra::getNmTabela() . "/biblioteca_htmlSolicCompra.php");
		//var_dump($voDemanda->voSolicCompra);
		getSolicCompraDetalhamento($voDemanda->voSolicCompra);
		
		require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
		getProcLicitatorioDetalhamento($voDemanda->voProcLicitatorio);

		require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
		getColecaoContratoDet($voDemanda->colecaoContrato, true);
		//var_dump($voDemanda->colecaoContrato);
	}
}

function getTpDemandaContrato($nmCampoTpDemandaContrato, $nmCampoTpDemandaReajuste, $nmDivInformacoesComplementares, $pCdOpcaoSelecionadaTpDemandaContrato=null, $pCdOpcaoSelecionadaReajuste=null){
	$comboTpReajuste = new select(dominioTipoReajuste::getColecao());
	
	$html = "<div id='$nmDivInformacoesComplementares'> <b>Informações complementares</b>";	
	$html .= dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, $pCdOpcaoSelecionadaTpDemandaContrato, dominioTipoDemandaContrato::getColecao(), 2, true, "formataFormTpDemandaContrato();");
	$html .= "Reajuste: " . $comboTpReajuste->getHtmlComObrigatorio($nmCampoTpDemandaReajuste,$nmCampoTpDemandaReajuste, $pCdOpcaoSelecionadaReajuste, false,false);
	$html .= "</div>";	
	return $html; 		
}
/**
 * Gera um filtro predeterminado para a consulta de demandas com reajuste prontos para serem calculados (com data base vencida)
 * @param unknown $filtro
 * @return unknown
 */
function getFiltroManterDemandaDataBaseReajusteVencida(){
		$filtro = new filtroManterDemanda ( false );
		//$voDemanda = new voDemanda ();
		//$filtro->voPrincipal = $voDemanda;
		$filtro->isValidarConsulta = false;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->vodemanda->situacao = array (
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
				dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO,
				//dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_SUSPENSA,
		);
		$filtro->inContratoComDtPropostaVencida = constantes::$CD_SIM;
		//$filtro->vocontrato->dtProposta = getDataHoje();
		//$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );
		$filtro->vodemanda->tipo = array(dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO);
		$filtro->vodemanda->tpDemandaContrato = array(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE);
		//$filtro->prioridadeExcludente = dominioPrioridadeDemanda::$CD_PRIORI_BAIXA;
		//$filtro->vocontrato->dtProposta = "11/11/2017";
		$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
		$filtro->cdAtrOrdenacao = filtroManterDemanda::$NmColDtReferenciaSetorAtual;
		
		return $filtro;
}

/**
 * Consulta a demanda a partir de um filtro predeterminado
 * @param unknown $filtro
 * @return unknown
 */
function consultarFiltroManterDemandaTelaConsulta($filtro){
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	return $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
}

/**
 * Verifica se uma demanda eh de reajuste pronto para ser calculado 
 * @param unknown $voDemanda
 * @return string
 */
function isSinalizarDemandaReajustePeriodoNaoTranscorrido ($voDemanda){
	$retorno = false;
	if($voDemanda != null){
		$filtro = getFiltroManterDemandaDataBaseReajusteVencida();
		//$filtro = new filtroManterDemanda();
		$vodemandatemp = new voDemanda();
		$vodemandatemp->ano = $voDemanda->ano;
		$vodemandatemp->cd = $voDemanda->cd;
		$filtro->vodemanda = $vodemandatemp;
		$colecao = consultarFiltroManterDemandaTelaConsulta($filtro);
		
		//$voDemanda = new voDemanda();	
		//echo " setor atual " . $voDemanda->cdSetorAtual;
		$cdSetorAtual = $voDemanda->cdSetorAtual;
		$isColecaoVazia = isColecaoVazia($colecao);
		//var_dump($colecao);
		//echo "colecao eh vazia: " . !$isColecaoVazia;
		
		$retorno = $cdSetorAtual == dominioSetor::$CD_SETOR_ATJA && $isColecaoVazia;
	}
	
	return $retorno;
}

/**
 * verifica se o contrato da demanda permite prorrogacao
 * @param unknown $voDemanda
 * @return boolean
 */
function isContratoPermiteProrrogacao($voContrato){
	$retorno = false;
	//$vocontrato = new vocontrato();
	if($voContrato != null){

		$filtro = new filtroConsultarContratoConsolidacao(false);
		$filtro->isValidarConsulta = false;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		
		$filtro->anoContrato = $voContrato->anoContrato;
		$filtro->cdContrato = $voContrato->cdContrato;
		$filtro->tipoContrato = $voContrato->tipo;
		$db = new dbContratoInfo();				
		$colecao = $db->consultarTelaConsultaConsolidacao($filtro);
		//echo "eh vazia";
		//echo $filtro->tpVigencia;

		if(!isColecaoVazia($colecao)){
			//echo "nao eh vazia";			
			$registro = $colecao[0];
			$prorrogavel = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogavel];
			$prorrExcepcional = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogacaoExcepcional];			
			//echo "$prorrogavel $prorrExcepcional";			
			//verifica se o contrato permite prorrogacao
			//$retorno = getAtributoComoBooleano($prorrogavel) || getAtributoComoBooleano($prorrExcepcional); 
			$retorno = getAtributoComoBooleano($prorrogavel);
		}		
		
	}

	return $retorno;
}

function getTpDemandaContratoDetalhamento($nmCampoTpDemandaContrato, $nmCampoTpDemandaReajuste, $nmDivInformacoesComplementares, $voDemanda = null){

	$pCdOpcaoSelecionadaTpDemandaContrato=$voDemanda->tpDemandaContrato;
	$pCdOpcaoSelecionadaReajuste=$voDemanda->inTpDemandaReajusteComMontanteA;
	$voContratoDemanda = $voDemanda->getContrato();
		
	$html .= dominioTipoDemandaContrato::getHtmlChecksBoxDetalhamento($nmCampoTpDemandaContrato, $pCdOpcaoSelecionadaTpDemandaContrato, 2, true);
	
	$countATENCAO = 1;
	//informa que ha PAAPs abertos para o contrato
	$temPAAPAberto = temPAAPAberto($voContratoDemanda);
	if($temPAAPAberto){
		$texto = "ATENÇÃO$countATENCAO: há PAAP(s) cadastrado(s) para este contrato.";
		$html .= $conectorAlerta . getTextoLink($texto, "../pa", null, false, true);
	
		$conectorAlerta = "<BR>";
		$countATENCAO++;
	}	
	
	if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $pCdOpcaoSelecionadaTpDemandaContrato)){
		//eh reajuste
		$html .= "Reajuste: " . dominioTipoReajuste::getHtmlDetalhamento($nmCampoTpDemandaReajuste, $nmCampoTpDemandaReajuste, $pCdOpcaoSelecionadaReajuste, false);
		
		if(isSinalizarDemandaReajustePeriodoNaoTranscorrido($voDemanda)){
			$html .= getTextoHTMLDestacado("ATENÇÃO$countATENCAO: o período contratual necessário para o cálculo do reajuste(índice contratual) ainda não transcorreu. Verifique a Data Base de Reajuste do contrato.");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}				
	}
	
	if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO, $pCdOpcaoSelecionadaTpDemandaContrato)){
		//var_dump($voDemanda->getContrato());
		$exibirInfoProrrog = $voDemanda->situacao != dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA;		
		if($exibirInfoProrrog && !isContratoPermiteProrrogacao($voContratoDemanda)){
			$texto = "ATENÇÃO$countATENCAO: verifique se o contrato comporta prorrogação em 'Contratos-Consolidação'";
			//$html .= $conectorAlerta . getTextoHTMLDestacado($texto);
			$html .= $conectorAlerta . getTextoLink($texto, "../contrato_consolidacao", null, false, true);
						
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}	
	}
	
	$dtinicioContrato = $voContratoDemanda->dtVigenciaInicial;
	if($dtinicioContrato >= normativos::$DATA_PUBLICACAO_RESOLUCAOCPF0012020){
		//var_dump($voDemanda->getContrato());
		$exibirInfoProrrog = $voDemanda->situacao != dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA;
		if($exibirInfoProrrog){
			$html .= $conectorAlerta . getTextoHTMLDestacado("ATENÇÃO$countATENCAO: verifique se o contrato não está suspenso pela RESOLUÇÃO CPF 001.2020 ou 002.2020(CORONAVIRUS)");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}
	}
		
	return $html;
}

function getHtmlDocumento($voAtual, $comDescricaoPorExtenso = false) {
	$html .= "<TD class='tabeladadosalinhadodireita' nowrap> \n";
	if ($voAtual->voDoc->sq != null) {
		$voDoc = $voAtual->voDoc;
			
		/*
		 * $voDoc->dbprocesso = new dbDocumento();
		 * $registro = $voDoc->dbprocesso->consultarPorChave($voDoc, false);
		 */
			
		$endereco = $voDoc->getEnderecoTpDocumento ();
		$chave = $voDoc->getValorChavePrimaria ();
			
		$html .= $voDoc->formatarCodigo ($comDescricaoPorExtenso) . " \n";
		$html .= "<input type='hidden' name='" . $chave . "' id='" . $chave . "' value='" . $endereco . "'>" . " \n";
		// $html .= getBotaoValidacaoAcesso("bttabrir_arq", "Abrir Anexo", "botaofuncaop", false,true,true,true, "onClick=\"javascript:abrirArquivo('".$chave."');\"");
		$html .= getBotaoAbrirDocumento ( $chave );
	}
	$html .= "</TD>\n";
	
	return $html;	
}

/**
 * monta a grid da tela de detalhamento demanda
 * @param unknown $colecaoTramitacao
 * @param unknown $isDetalhamento
 */

function mostrarGridDemanda($colecaoTramitacao, $isDetalhamento) {
	// var_dump($colecaoTramitacao);
	
	if (is_array ( $colecaoTramitacao )) {
		$tamanho = sizeof ( $colecaoTramitacao );
	} else {
		$tamanho = 0;
	}
	
	$html = "";
	if ($tamanho > 0) {
		
		$numColunas = 9;
				
		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Histórico\n";
		
		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Número</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Origem</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' width='90%'>Texto</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Anexo</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT/SEI</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usuário</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.Referência</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Ult.Mov.</TH> \n";
		$html .= "</TR> \n";
		
		$sq = 1;
		
		$dominioSetor = new dominioSetor ();
		for($i = 0; $i < $tamanho; $i ++) {
			
			$voAtual = new voDemandaTramitacao ();
			$voAtual->getDadosBanco ( $colecaoTramitacao [$i] );
			
			$sq = $voAtual->sq;
			
			if ($voAtual != null) {
				$html .= "<TR class='dados'> \n";
				
				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}
				
				
				//$textoTram = truncarStringHTML($voAtual->textoTram,300, false);
				$textoTram = truncarStringHTMLComDivExpansivel($voAtual->getValorChaveHTML(),$voAtual->textoTram,220, false);
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorOrigem ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $textoTram . "</TD> \n";				
				$html .= getHtmlDocumento($voAtual);				
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dtReferencia ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dhInclusao ) . "</TD> \n";
				
				$html .= "</TR> \n";
				
				$sq ++;
				
				$isSetorAtual = $i == 0;
				// o setor origem vai ser o setor destino da ultima tramitacao
				//ATENCAo a ordenacao da consulta. O setor atual/origem serah o da ultima tramitacao em caso de ordenacao crescente. Caso contrario, sera o da primeira tramitacao.				
				if($isSetorAtual){
					//echo "setor atual é $voAtual->cdSetorDestino";
					$html .= "<INPUT type='hidden' id='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' name='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' value='" . $voAtual->cdSetorDestino . "'> \n";
				}				
			}
		}
				
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
		
	}
	
	echo $html;
}

/**
 * monta a grid da tela de detalhamento demanda gestao
 * @param unknown $colecaoTramitacao
 * @param unknown $isDetalhamento
 */

function mostrarGridDemandaGestao($colecaoTramitacao, $isDetalhamento) {
	// var_dump($colecaoTramitacao);

	if (is_array ( $colecaoTramitacao )) {
		$tamanho = sizeof ( $colecaoTramitacao );
	} else {
		$tamanho = 0;
	}

	$html = "";
	$numregistros = 0;
	if ($tamanho > 0) {

		$numColunas = 8;

		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Prazos\n";

		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Número</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' width='90%'>Texto</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT/SEI</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usuário</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.Início</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.Fim</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Prazo</TH> \n";
		$html .= "</TR> \n";

		$sq = 1;

		$dominioSetor = new dominioSetor ();
		for($i = 0; $i < $tamanho; $i ++) {
				
			$voAtual = new voDemandaTramitacao ();
			$voAtual->getDadosBanco ( $colecaoTramitacao [$i] );
				
			$sq = $voAtual->sq;
			$prazo = $colecaoTramitacao [$i][filtroConsultarDemandaGestao::$NmColNuTempoVida];
			$prazototal += $prazo;
			//exibe apenas as tramitacoes com prazo acima de zero
			//if ($voAtual != null && $prazo > 0) {
			if ($voAtual != null) {
				$numregistros = $numregistros + 1;
				$html .= "<TR class='dados'> \n";

				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}


				//$textoTram = truncarStringHTML($voAtual->textoTram, 300);
				$textoTram = truncarStringHTMLComDivExpansivel($voAtual->getValorChaveHTML(),$voAtual->textoTram,220, false);
				$dataSaida = $colecaoTramitacao [$i][filtroConsultarDemandaGestao::$NmColDtReferenciaSaida];
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $textoTram . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dtReferencia ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $dataSaida ) . "</TD> \n";
				$html .= "<TD class='tabeladadosalinhadodireita' nowrap>" . complementarCharAEsquerda($prazo, "0", constantes::$TAMANHO_CODIGOS_SAFI) . "</TD> \n";

				$html .= "</TR> \n";

				$sq ++;

				$isSetorAtual = $i == 0;
				// o setor origem vai ser o setor destino da ultima tramitacao
				//ATENCAo a ordenacao da consulta. O setor atual/origem serah o da ultima tramitacao em caso de ordenacao crescente. Caso contrario, sera o da primeira tramitacao.
				if($isSetorAtual){
					//echo "setor atual é $voAtual->cdSetorDestino";
					$html .= "<INPUT type='hidden' id='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' name='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' value='" . $voAtual->cdSetorDestino . "'> \n";
				}
			}
		}
		$html .= "<TR> \n";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan=";
		$html .= $numColunas - 1 . ">Prazo Total</TD> \n";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' >". complementarCharAEsquerda($prazototal, "0", constantes::$TAMANHO_CODIGOS_SAFI). " </TD> \n";
		$html .= "</TR>\n";
		$html .= "<TR> \n";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan=$numColunas>Total de registro(s) na página: $numregistros</TD> \n";
		$html .= "</TR>\n";
		
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";

	}

	echo $html;
}

function mostrarGridDemandaContrato($colecaoTramitacao, $isDetalhamento, $comDadosDemanda = true) {
	// var_dump($colecaoTramitacao);	
	if (is_array ( $colecaoTramitacao )) {
		$tamanho = sizeof ( $colecaoTramitacao );
	} else {
		$tamanho = 0;
	}

	$html = "";
	if ($tamanho > 0) {

		$numColunas = 11;
		if($comDadosDemanda){
			$numColunas --;
		}

		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Anexos/Demandas\n";

		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%'>Ano</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Dem.</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Tram.</TH> \n";
		// $html .= "<TH class='headertabeladados' width='1%'>Origem</TH> \n";
		// $html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Tipo</TH> \n";		
		if($comDadosDemanda){			
			$html .= "<TH class='headertabeladados' width='90%'>Título</TH> \n";
		}
		$html .= "<TH class='headertabeladados' width='90%'>Texto</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' >Anexo</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' >Usuário</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' >Referência</TH> \n";
		$html .= "</TR> \n";

		$sq = 1;

		$dominioSetor = new dominioSetor ();
		for($i = 0; $i < $tamanho; $i ++) {
				
			$voAtual = new voDemandaTramitacao ();
			$voAtual->getDadosBanco ( $colecaoTramitacao [$i] );
			$sq = $voAtual->sq;
							
			if ($voAtual != null) {
				$tipo = dominioTipoDemanda::getDescricaoStatic($voAtual->tipo);
				$dsTpDemandaContrato = $voAtual->tpDemandaContrato;
				$dsTpDemandaContrato = dominioTipoDemandaContrato::getDescricaoColecaoChave($dsTpDemandaContrato, false, dominioTipoDemandaContrato::getColecaoAntiga());
				if($voAtual->tpDemandaContrato != null){
					$tipo = $tipo ."<br>". $dsTpDemandaContrato;
				}
				
				$html .= "<TR class='dados'> \n";

				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}

				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->ano . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->cd, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				//$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorOrigem ) . "</TD> \n";
				//$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";				
				$html .= "<TD class='tabeladados'>$tipo</TD> \n";
				if($comDadosDemanda){
					$html .= "<TD class='tabeladados'>" .  strtolower($voAtual->texto) . "</TD> \n";
				}
				$html .= "<TD class='tabeladados' >" . strtolower($voAtual->textoTram) . "</TD> \n";
				
				$html .= getHtmlDocumento($voAtual, false);				

				$html .= "<TD class='tabeladados'>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados'>" . getData ( $voAtual->dtReferencia) . "</TD> \n";

				$html .= "</TR> \n";

				$sq ++;
			}
		}
		
		$html .= "<TR>\n
		<TD class='totalizadortabeladadosalinhadodireita' colspan=$numColunas> Total registros: $i </TD>\n
		</TR>\n";
		

		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
	}

	echo $html;
}

?>