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
 * verifica se o contrato da demanda permite prorrogacao e se eh servico continuo
 * @param unknown $voDemanda
 * @return boolean
 */
function isContratoPermiteProrrogacao($voContrato){
	$isContratoPermiteProrrogacao = false;
	$isPrazoProrrogacaoServicoContinuo = false;
	
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
			$vocontratoinfo = new voContratoInfo();
			$vocontratoinfo->getDadosBanco($registro);
			
			$prorrogavel = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogavel];
			$prorrExcepcional = $registro[filtroConsultarContratoConsolidacao::$NmColInProrrogacaoExcepcional];			
			$tpProrrogacao = $vocontratoinfo->inPrazoProrrogacao;
			$isPrazoProrrogacaoServicoContinuo = $tpProrrogacao == dominioProrrogacaoContrato::$CD_ART57_II;
			$isPrazoProrrogacaoServicoInformatica = $tpProrrogacao == dominioProrrogacaoContrato::$CD_ART57_IV;
			//echo "$prorrogavel $prorrExcepcional";			
			//verifica se o contrato permite prorrogacao
			//$retorno = getAtributoComoBooleano($prorrogavel) || getAtributoComoBooleano($prorrExcepcional); 
			$isContratoPermiteProrrogacao = getAtributoComoBooleano($prorrogavel);
		}		
		
	}

	return array($isContratoPermiteProrrogacao, $isPrazoProrrogacaoServicoContinuo, $isPrazoProrrogacaoServicoInformatica);
}

function isSituacaoDemandaFechada($situacao){
	return array_key_exists($situacao, dominioSituacaoDemanda::getColecaoFechada());
}

function getAlertaOrientacao($msgAlerta, &$countATENCAO, $conectorAlerta = null, $link=null){		
	
	$texto = "ATENÇÃO$countATENCAO: $msgAlerta";
	
	if($link != null){
		$texto = getTextoLink($texto, $link, null, false, true);
	}else{
		$texto = getTextoHTMLDestacado($texto, "red", true);
	}
	
	$html .= $conectorAlerta . $texto;
	
	$countATENCAO++;
		
	return $html;	
}


function getTpDemandaContratoDetalhamento($nmCampoTpDemandaContrato, $nmCampoTpDemandaReajuste, $nmDivInformacoesComplementares, $voDemanda = null){

	$pCdOpcaoSelecionadaTpDemandaContrato=$voDemanda->tpDemandaContrato;
	$pCdOpcaoSelecionadaReajuste=$voDemanda->inTpDemandaReajusteComMontanteA;
	//entender porque o contrato perde os valores [BRONCA]
	$voContratoDemanda = $voDemanda->getContrato();
	
	$countATENCAO = 1;
	$isVODemandaNaoNulo = $voDemanda != null;
	//$conectorAlerta = "<BR>";
	
	$html .= dominioTipoDemandaContrato::getHtmlChecksBoxDetalhamento($nmCampoTpDemandaContrato, $pCdOpcaoSelecionadaTpDemandaContrato, 2, true);
	$isReajusteDemanda = $isVODemandaNaoNulo 
		&& dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $pCdOpcaoSelecionadaTpDemandaContrato);
	
	if($isReajusteDemanda){
		//eh reajuste
		$html .= "Reajuste: " . dominioTipoReajuste::getHtmlDetalhamento($nmCampoTpDemandaReajuste, $nmCampoTpDemandaReajuste, $pCdOpcaoSelecionadaReajuste, false);
	
		if(isSinalizarDemandaReajustePeriodoNaoTranscorrido($voDemanda)){
			$html .= $conectorAlerta . getTextoHTMLDestacado("ATENÇÃO$countATENCAO: o período contratual necessário para o cálculo do reajuste(índice contratual) ainda não transcorreu. Verifique a Data Base de Reajuste do contrato.");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}
	}
	
	
	try{
		//if(!$temReajustePendente){echo "teste";}		
		$retornoTemReajustePendente = temReajustePendente($voContratoDemanda);
		$temReajustePendente = $retornoTemReajustePendente[0];
		$exibirAlertaReajustePendente = $temReajustePendente &&  !$isReajusteDemanda;
		
		if($exibirAlertaReajustePendente){
			$html .= getAlertaOrientacao("há reajustes pendentes.", $countATENCAO, $conectorAlerta);
			$conectorAlerta = "<BR>";
		}
	}catch (excecaoGenerica $exReajuste){
		/*$texto = "ATENÇÃO$countATENCAO: " . $exDoc->getMessage();
		$html .= $conectorAlerta . $texto;
		$conectorAlerta = "<BR>";
		$countATENCAO++;*/
		$html .= getAlertaOrientacao($exReajuste->getMessage(), $countATENCAO, $conectorAlerta);
		$conectorAlerta = "<BR>";
	}
	
	//informa que ha PAAPs abertos para o contrato
	try{
		$temPAAPAberto = temPAAPAberto($voContratoDemanda);
		if($temPAAPAberto){
			//$html .= getAlertaOrientacao("há PAAP(s) cadastrado(s) para este fornecedor.", $countATENCAO);
			$texto = "ATENÇÃO$countATENCAO: há PAAP(s) cadastrado(s) para este fornecedor.";
			$html .= $conectorAlerta . getTextoLink($texto, "../pa", null, false, true);
		
			$conectorAlerta = "<BR>";
			$countATENCAO++;			
		}
	}catch (excecaoGenerica $exDoc){
		$texto = "ATENÇÃO$countATENCAO: " . $exDoc->getMessage();		
		$html .= $conectorAlerta . getTextoLink($texto, "../pa", null, false, true);
		$conectorAlerta = "<BR>";
		$countATENCAO++;		
	}
	
	/*$html .= dominioTipoDemandaContrato::getHtmlChecksBoxDetalhamento($nmCampoTpDemandaContrato, $pCdOpcaoSelecionadaTpDemandaContrato, 2, true);
	if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $pCdOpcaoSelecionadaTpDemandaContrato)){
		//eh reajuste
		$html .= "Reajuste: " . dominioTipoReajuste::getHtmlDetalhamento($nmCampoTpDemandaReajuste, $nmCampoTpDemandaReajuste, $pCdOpcaoSelecionadaReajuste, false);		
		
		if(isSinalizarDemandaReajustePeriodoNaoTranscorrido($voDemanda)){
			$html .= getTextoHTMLDestacado("ATENÇÃO$countATENCAO: o período contratual necessário para o cálculo do reajuste(índice contratual) ainda não transcorreu. Verifique a Data Base de Reajuste do contrato.");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}				
	}*/

	if($isVODemandaNaoNulo){
		//$exibirInfoProrrog = $voDemanda->situacao != dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA;
		$exibirInfoProrrog = !isSituacaoDemandaFechada($voDemanda->situacao);		
	}
	
	if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO, $pCdOpcaoSelecionadaTpDemandaContrato)){
		//var_dump($voDemanda->getContrato());		
		$arrayPermiteProrrogacao = isContratoPermiteProrrogacao($voContratoDemanda);
		$permiteProrrogacao = $arrayPermiteProrrogacao[0];
		$ehServicoContinuo = $arrayPermiteProrrogacao[1];
		$ehServicoInformatica = $arrayPermiteProrrogacao[2];
		if($exibirInfoProrrog){
			if(!($ehServicoContinuo || $ehServicoInformatica)){
				$texto = "ATENÇÃO$countATENCAO: verifique a fundamentação legal para a prorrogação em 'Contratos-Consolidação'";
				//$html .= $conectorAlerta . getTextoHTMLDestacado($texto);
				$html .= $conectorAlerta . getTextoLink($texto, "../contrato_consolidacao", null, false, true);
				
				$conectorAlerta = "<BR>";
				$countATENCAO++;				
			}
			
			if(!$permiteProrrogacao){
				$texto = "ATENÇÃO$countATENCAO: verifique se o contrato comporta prorrogação em 'Contratos-Consolidação'";
				//$html .= $conectorAlerta . getTextoHTMLDestacado($texto);
				$html .= $conectorAlerta . getTextoLink($texto, "../contrato_consolidacao", null, false, true);
				
				$conectorAlerta = "<BR>";
				$countATENCAO++;				
			}
				
		}	
	}
	
	$dtinicioContrato = $voContratoDemanda->dtVigenciaInicial;
	if($dtinicioContrato >= normativos::$DATA_PUBLICACAO_RESOLUCAOCPF0012020){
		//var_dump($voDemanda->getContrato());

		if($exibirInfoProrrog){
			$html .= $conectorAlerta . getTextoHTMLDestacado("ATENÇÃO$countATENCAO: verifique se o contrato não está suspenso pela RESOLUÇÃO CPF 001.2020 ou 002.2020(CORONAVIRUS)");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}
	}
		
	return $html;
}

function getHtmlDocumentoSemTD($voAtual, $comDescricaoPorExtenso = false, $nmClassCelula="tabeladadosalinhadodireita", $msgDocNaoExiste=null, $exibirCodigoDocFormatado=true) {
	$pArray = array($voAtual, $comDescricaoPorExtenso, $nmClassCelula, $msgDocNaoExiste, $exibirCodigoDocFormatado);
	return getHtmlDocumentoArray($pArray);
}

function getHtmlDocumentoArray($pArray) {
	$voAtual = $pArray[0];
	$comDescricaoPorExtenso  = $pArray[1];
	$nmClassCelula = $pArray[2];
	$msgDocNaoExiste = $pArray[3];
	$exibirCodigoDocFormatado = $pArray[4];
	$exibirDocComoLink = $pArray[5];
	$textoLink = $pArray[6];
	
	if($exibirDocComoLink == null){
		$exibirDocComoLink = false;
	}
	
	if ($voAtual->voDoc->sq != null) {
		$voDoc = $voAtual->voDoc;
						
		$endereco = $voDoc->getEnderecoTpDocumento ();
		$chave = $voDoc->getValorChavePrimaria ();
		
		if($exibirCodigoDocFormatado){
			$html .= $voDoc->formatarCodigo ($comDescricaoPorExtenso) . " \n";
		}
		$html .= "<input type='hidden' name='" . $chave . "' id='" . $chave . "' value='" . $endereco . "'>" . " \n";

		if(!$exibirDocComoLink){
			$html .= getBotaoAbrirDocumento ( $chave );
		}else{
			$nmFuncaoJavaScript = "abrirArquivo";
			$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."Cliente('" . $chave. "',false);";
			if(isUsuarioAdmin()){
				$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."('" . $chave. "',false);";
			}
			$html .= getTextoLink($textoLink,"#",$complementoJS);
		}
	}else{
		if(isAtributoValido($msgDocNaoExiste)){
			$html .= $msgDocNaoExiste;
		}
	}

	return $html;
}

function getHtmlDocumento($voAtual, $comDescricaoPorExtenso = false, $nmClassCelula="tabeladadosalinhadodireita") {
	$html .= "<TD class='$nmClassCelula' nowrap> \n";
	$html .= getHtmlDocumentoSemTD($voAtual, $comDescricaoPorExtenso, $nmClassCelula);
	/*if ($voAtual->voDoc->sq != null) {
		$voDoc = $voAtual->voDoc;
			
		$endereco = $voDoc->getEnderecoTpDocumento ();
		$chave = $voDoc->getValorChavePrimaria ();
			
		$html .= $voDoc->formatarCodigo ($comDescricaoPorExtenso) . " \n";
		$html .= "<input type='hidden' name='" . $chave . "' id='" . $chave . "' value='" . $endereco . "'>" . " \n";
		// $html .= getBotaoValidacaoAcesso("bttabrir_arq", "Abrir Anexo", "botaofuncaop", false,true,true,true, "onClick=\"javascript:abrirArquivo('".$chave."');\"");
		$html .= getBotaoAbrirDocumento ( $chave );
	}*/
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
				$dataEntrada = $colecaoTramitacao [$i][filtroConsultarDemandaGestao::$NmColDtReferenciaEntrada];
				$dataSaida = $colecaoTramitacao [$i][filtroConsultarDemandaGestao::$NmColDtReferenciaSaida];
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $textoTram . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $dataEntrada ) . "</TD> \n";
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

 function getEmailConvocacaoAssinatura($registro){
 	$vocontratoinfo = new voContratoInfo();
 	$vocontratoinfo->getDadosBanco($registro);
 	
 	$vocontrato = new vocontrato();
 	$vocontrato->getDadosBanco($registro);
 	$dbcontrato = new dbcontrato();
 	
 	if(!isContratoValido($vocontrato))
 		throw new excecaoAtributoInvalido("Demanda sem contrato. Verifique o contrato da demanda.");
 	
 	try{
	 	$colecao = $dbcontrato->consultarContratoPorChave($vocontrato, false);
	 	$vocontrato->getDadosBanco($colecao[0]);
 	}catch (excecaoChaveRegistroInexistente $ex){
 		throw new excecaoChaveRegistroInexistente("Inclua o termo em questão na função de contratos."); 		
 	}
 	
 	$vodemanda = new voDemanda();
 	$vodemanda->getDadosBanco($registro);
 	//$codigoContrato = getCodigoContratoFormatadoEmail($registro);
 	
 	$codigoContrato = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
 	$codigoContratoCompleto = getTextoHTMLNegrito(getCodigoContratoPublicacao($vocontrato));
 	
 	$dsPessoa = $registro[vopessoa::$nmAtrNome];
 	
 	$a_preencher = "XXXXXXX";
 	$numSEI = $vodemanda->prt;
 	if($numSEI == null){
 		$numSEI = $a_preencher;
 	}
 	$dtInicioVigencia = getData($vocontrato->dtVigenciaInicial);
 	if($dtInicioVigencia == null){
 		$dtAssinaturaDigital = $dtInicioVigencia = $a_preencher;
 	}else{
 		$dtAssinaturaDigital = somarOuSubtrairDias($dtInicioVigencia, 1, "-", false);
 	}
 	$str_confirmar = getTextoHTMLDestacado("[CONFIRMAR]");

	$retorno = "<br>À ".getTextoHTMLNegrito($dsPessoa).",
	<br><br>
	ASSUNTO: ".getTextoHTMLNegrito("ASSINATURA DO $codigoContratoCompleto")."
	<br><br>".getTextoHTMLDestacado("Ref. SEI nº $numSEI", "blue").". $str_confirmar
	
	<br><br><br>Prezado(s) Senhor(es),<br><br><br>
	Em decorrência da Pandemia do Coronavírus e, cumprindo determinação do Governo do Estado de Pernambuco, o atendimento presencial, 
	na SEFAZ/PE foi substituído pelo trabalho remoto. Por esse motivo, estamos encaminhando para assinatura, em formato digital, 01(uma) via do supramencionado contrato,
	cujo objeto é ".getTextoHTMLNegrito($vocontrato->objeto)."$str_confirmar, com vigência a partir de ".getTextoHTMLNegrito($dtInicioVigencia).".
	
	<br><br>Solicitamos que sejam impressas ".getTextoHTMLNegrito("02(duas) vias").", assinadas e rubricadas pelo representante legal, no prazo de até ".getTextoHTMLNegrito("10(dez) dias")." 
	contados a partir do recebimento do presente email, sendo posteriormente devolvidas pelos Correios ou protocoladas na  recepção do prédio desta SEFAZ,
	situada na Av. Cruz Cabugá, N° 1419, Térreo, Santo Amaro, Recife/PE, CEP.: 50.040-000, no horário de atendimento presencial ".getTextoHTMLNegrito("excepcional: 9h as 15h.");
	
	//echo "garantia: " . $vocontratoinfo->inTemGarantia;
	if($vocontratoinfo->inTemGarantia == "S"){
		$retorno .= "<br><br>Por oportuno, informamos que será necessária a PRESTAÇÃO, ou REFORÇO, se acréscimo, ".getTextoHTMLNegrito("DA GARANTIA CONTRATUAL").", 
		conforme previsão editalícia, devendo esta ser apresentada no prazo de até ".getTextoHTMLNegrito("10(dez) dias úteis").".". getTextoHTMLDestacado("[CONFIRMAR PRAZO NO EDITAL]");
	}
	
	try{
		$isDataRetroativa = isDataRetroativa($dtInicioVigencia);
		if(!$isDataRetroativa){
			$retorno .= "<br><br>Permite-se o uso da assinatura digital, desde que sejam também encaminhados os meios necessários
			à autenticação do documento digital.$str_confirmar";
		
			if(isDataValidaNaoVazia($dtInicioVigencia)){
				$retorno .= getTextoHTMLDestacado("<br>ATENÇÃO"). ": sob pena de inadmissibilidade, a assinatura digital deve ocorrer até ".getTextoHTMLDestacado($dtAssinaturaDigital).".$str_confirmar";
			}
		}
	
	}catch (excecaoAtributoInvalido $ex){
		$retorno .= getTextoHTMLDestacado("<br><br>*****ATENÇÃO: verifique a data de início de vigência do contrato.*****<br><br>");
	}
	//echo compararDatas($dthtmlassinatura, getDataHoje()) . " $dthtmlassinatura  e " . getDataHoje(); 
	
	$retorno .= "<br><br>Favor acusar recebimento, devolvendo a via assinada junto com os documentos que comprovem a legitimidade da representação legal do procurador assinante.";

	$sublinhado = getTextoHTMLDestacado("o número do SEI", "black", true);
	$retorno .= getTextoHTMLNegrito("<br><br>Para um melhor atendimento, ao comparecer a esta unidade para recolhimento da via contratual, favor trazer, em mãos, $sublinhado a que se refere a presente demanda.");

	return $retorno;
}

function isDemandaContratoModificacaoObrigatorio($vodemanda){
	if($vodemanda->tpDemandaContrato == null){
		throw new excecaoAtributoInvalido("tpDemandaContrato nao pode ser nulo.");		
	}	
	return dominioTipoDemandaContrato::existePeloMenosUmaChaveColecaoNoArrayOuStrSeparador(array_keys(dominioTipoDemandaContrato::getColecaoAlteraValorContrato()), $vodemanda->tpDemandaContrato);
}


?>