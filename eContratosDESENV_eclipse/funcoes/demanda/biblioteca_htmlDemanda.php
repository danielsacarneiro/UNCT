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
				//echo "teste";
				echo getTpDemandaContratoDetalhamento(voDemanda::$nmAtrTpDemandaContrato, "", "DIV_DETALHAR", $voDemanda);
			}			
			echo "<INPUT type='hidden' id='" . voDemanda::$nmAtrTipo . "' name='" . voDemanda::$nmAtrTipo . "' value='$voDemanda->tipo'>";
		}
		
	}
		
	//$voDemanda = new voDemanda();
	if($voDemanda->texto != null){
	?>
         <br>T�tulo: <INPUT type="text" value="<?=getVarComoStringHTML($voDemanda->texto)?>"  class="camporeadonly" size="70" readonly>		
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
	
	$html = "<div id='$nmDivInformacoesComplementares'> <b>Informa��es complementares</b>";	
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
		$filtro->inProduzindoEfeitos = dominioContratoProducaoEfeitos::$CD_VISTO_COM_EFEITOS;
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
			$isContratoNaoSeraProrrogado = $vocontratoinfo->inSeraProrrogado == "N";
			//echo "$prorrogavel $prorrExcepcional";			
			//verifica se o contrato permite prorrogacao
			//$retorno = getAtributoComoBooleano($prorrogavel) || getAtributoComoBooleano($prorrExcepcional); 
			$isContratoPermiteProrrogacao = getAtributoComoBooleano($prorrogavel);
		}		
		
	}

	return array($isContratoPermiteProrrogacao, $isPrazoProrrogacaoServicoContinuo, $isPrazoProrrogacaoServicoInformatica, $isContratoNaoSeraProrrogado);
}

function isSituacaoDemandaFechada($situacao){
	return array_key_exists($situacao, dominioSituacaoDemanda::getColecaoFechada());
}

function getAlertaOrientacao($msgAlerta, &$countATENCAO, $conectorAlerta = null, $link=null, $cor="red"){		
	
	$texto = "ATEN��O$countATENCAO: $msgAlerta";
	
	if($link != null){
		$texto = getTextoLink($texto, $link, null, false, true);
	}else{
		if($cor != constantes::$CD_TEXTO_MARCADO)
			$texto = getTextoHTMLDestacado($texto, $cor, true);
		else
			$texto = getTextoHTMLDestacado($texto, "black", true, constantes::$CD_TEXTO_MARCADO);
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
	//echo "teste";
	
	$countATENCAO = 1;
	$isVODemandaNaoNulo = $voDemanda != null;
	$exibirAlertas = false;
	if($isVODemandaNaoNulo){
		//$exibirInfoProrrog = $voDemanda->situacao != dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA;
		$exibirAlertas = !isSituacaoDemandaFechada($voDemanda->situacao);
	}
	//$exibirAlertas = false;
	//$conectorAlerta = "<BR>";
	
	$html .= dominioTipoDemandaContrato::getHtmlChecksBoxDetalhamento($nmCampoTpDemandaContrato, $pCdOpcaoSelecionadaTpDemandaContrato, 2, true);
	$isReajusteDemanda = $isVODemandaNaoNulo 
		&& dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE, $pCdOpcaoSelecionadaTpDemandaContrato);
	
	if($isReajusteDemanda){
		//eh reajuste
		$html .= "Reajuste: " . dominioTipoReajuste::getHtmlDetalhamento($nmCampoTpDemandaReajuste, $nmCampoTpDemandaReajuste, $pCdOpcaoSelecionadaReajuste, false);
	
		if(isSinalizarDemandaReajustePeriodoNaoTranscorrido($voDemanda)){
			$html .= $conectorAlerta . getTextoHTMLDestacado("ATEN��O$countATENCAO: o per�odo contratual necess�rio para o c�lculo do reajuste(�ndice contratual) ainda n�o transcorreu. Verifique a Data Base de Reajuste do contrato.");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}
	}

	//$voContratoDemanda = new vocontrato();
	$dtInicioContrato = $voContratoDemanda->dtVigenciaInicial;
	$isContratoRetroativo = $dtInicioContrato != null && isDataRetroativa($dtInicioContrato);	
	if($exibirAlertas && $isContratoRetroativo){
		$html .= getAlertaOrientacao("Contrato RETROATIVO: ATEN��O AOS DESPACHOS NO SEI.", $countATENCAO, $conectorAlerta);
		$conectorAlerta = "<BR>";
	}
	
	try{		
		$colecaoLembreteContrato = consultarLembreteContrato($voContratoDemanda);
		$exibirAlertaLembrete = !isColecaoVazia($colecaoLembreteContrato);		
		if($exibirAlertaLembrete){
			$textoLembrete = getTextoLembreteContrato($colecaoLembreteContrato);
			$lembrete = strtoupper(dominioTipoMensageria::$DS_CONTRATO_LEMBRETE);
			$html .= getAlertaOrientacao("$lembrete " . $textoLembrete, $countATENCAO, $conectorAlerta, null, constantes::$CD_TEXTO_MARCADO);
			$html .= getInputHidden(vocontrato::$ID_REQ_InTemLembreteDemanda, vocontrato::$ID_REQ_InTemLembreteDemanda, constantes::$CD_SIM);
			$conectorAlerta = "<BR>";
		}
	}catch (excecaoGenerica $exLembrete){
		$html .= getAlertaOrientacao($exReajuste->getMessage(), $countATENCAO, $conectorAlerta);
		$conectorAlerta = "<BR>";
	}
		
	try{
		//if(!$temReajustePendente){echo "teste";}
		$retornoTemReajustePendente = temReajustePendente($voContratoDemanda);
		$temReajustePendente = $retornoTemReajustePendente[0];
		$exibirAlertaReajustePendente = $temReajustePendente &&  !$isReajusteDemanda;
	
		if($exibirAlertaReajustePendente){
			$html .= getAlertaOrientacao("h� reajustes pendentes.", $countATENCAO, $conectorAlerta);
			$conectorAlerta = "<BR>";
		}
	}catch (excecaoGenerica $exReajuste){
		/*$texto = "ATEN��O$countATENCAO: " . $exDoc->getMessage();
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
			//$html .= getAlertaOrientacao("h� PAAP(s) cadastrado(s) para este fornecedor.", $countATENCAO);
			$texto = "ATEN��O$countATENCAO: h� PAAP(s) cadastrado(s) para este fornecedor.";
			$html .= $conectorAlerta . getTextoLink($texto, "../pa", null, false, true);
		
			$conectorAlerta = "<BR>";
			$countATENCAO++;			
		}
	}catch (excecaoGenerica $exDoc){
		$texto = "ATEN��O$countATENCAO: " . $exDoc->getMessage();		
		$html .= $conectorAlerta . getTextoLink($texto, "../pa", null, false, true);
		$conectorAlerta = "<BR>";
		$countATENCAO++;		
	}
		
	$arrayPermiteProrrogacao = isContratoPermiteProrrogacao($voContratoDemanda);
	$permiteProrrogacao = $arrayPermiteProrrogacao[0];
	$ehServicoContinuo = $arrayPermiteProrrogacao[1];
	$ehServicoInformatica = $arrayPermiteProrrogacao[2];
	$isContratoNaoProrrogado = $arrayPermiteProrrogacao[3];
	
	if(dominioTipoDemandaContrato::existeItemArrayOuStrCampoSeparador(dominioTipoDemandaContrato::$CD_TIPO_PRORROGACAO, $pCdOpcaoSelecionadaTpDemandaContrato)){
		//var_dump($voDemanda->getContrato());
		if($exibirAlertas){
			if(!($ehServicoContinuo || $ehServicoInformatica)){
				$texto = "ATEN��O$countATENCAO: verifique a fundamenta��o legal para a prorroga��o em 'Contratos-Consolida��o'";
				//$html .= $conectorAlerta . getTextoHTMLDestacado($texto);
				$html .= $conectorAlerta . getTextoLink($texto, "../contrato_consolidacao", null, false, true);
				
				$conectorAlerta = "<BR>";
				$countATENCAO++;				
			}
			
			if(!$permiteProrrogacao){
				$texto = "ATEN��O$countATENCAO: verifique se o contrato comporta prorroga��o em 'Contratos-Consolida��o'";
				//$html .= $conectorAlerta . getTextoHTMLDestacado($texto);
				$html .= $conectorAlerta . getTextoLink($texto, "../contrato_consolidacao", null, false, true);
				
				$conectorAlerta = "<BR>";
				$countATENCAO++;				
			}
				
		}	
	}
	
	 if($exibirAlertas && $isContratoNaoProrrogado){
		 $html .= getAlertaOrientacao("Contrato N�O SER� PRORROGADO: verifique a necessidade da demanda.", $countATENCAO, $conectorAlerta);
		 $conectorAlerta = "<BR>";
	 }	
	
	$dtinicioContrato = $voContratoDemanda->dtVigenciaInicial;
	/*if($dtinicioContrato >= normativos::$DATA_PUBLICACAO_RESOLUCAOCPF0012020){
		//var_dump($voDemanda->getContrato());

		if($exibirAlertas){
			$html .= $conectorAlerta . getTextoHTMLDestacado("ATEN��O$countATENCAO: verifique se o contrato n�o est� suspenso pela RESOLU��O CPF 001.2020 ou 002.2020(CORONAVIRUS)");
			$conectorAlerta = "<BR>";
			$countATENCAO++;
		}
	}*/
		
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
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Hist�rico\n";
		
		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%' nowrap>N�mero</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Origem</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' >Despacho</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Anexo</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT/SEI</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usu�rio</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.Refer�ncia</TH> \n";
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
				$respUNCT = $voAtual->nmUsuarioInclusao;				
				
				//$respUNCT = truncarStringHTML($respUNCT, 15, true);
				$arrayParam = array(null, $respUNCT, 15, true, false, "", 2);
				$respUNCT = truncarStringHTMLArray($arrayParam);
					/*$nmDiv = $pArray[0];
					$string = $pArray[1];
					$tamMAximo = $pArray[2];
					$usarReticencia = $pArray[3];
					$comDivExpansivel = $pArray[4];
					$corTextoTruncado = $pArray[5];
					$numMaximoPalavras = $pArray[6];*/			
				
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorOrigem ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $textoTram . "</TD> \n";				
				$html .= getHtmlDocumento($voAtual);				
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
				$html .= "<TD class='tabeladados'>" . $respUNCT . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dtReferencia ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dhInclusao ) . "</TD> \n";
				
				$html .= "</TR> \n";
				
				$sq ++;
				
				$isSetorAtual = $i == 0;
				// o setor origem vai ser o setor destino da ultima tramitacao
				//ATENCAo a ordenacao da consulta. O setor atual/origem serah o da ultima tramitacao em caso de ordenacao crescente. Caso contrario, sera o da primeira tramitacao.				
				if($isSetorAtual){
					//echo "setor atual � $voAtual->cdSetorDestino";
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
		$html .= "<TH class='headertabeladados' width='1%' nowrap>N�mero</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' >Despacho</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT/SEI</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usu�rio</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.In�cio</TH> \n";
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
					//echo "setor atual � $voAtual->cdSetorDestino";
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
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan=$numColunas>Total de registro(s) na p�gina: $numregistros</TD> \n";
		$html .= "</TR>\n";
		
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";

	}

	echo $html;
}

function mostrarGridDemandaContrato($colecaoTramitacao, $isDetalhamento, $comDadosDemanda = true, $comDivExpansao = false) {
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
		
		$htmlDiv .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$htmlDiv .= " <TBODY>  \n";
		$htmlDiv .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$htmlDiv .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$htmlDiv .= "<TH class='headertabeladados' width='1%'>Ano</TH>   \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%'>Dem.</TH> \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%'>Tram.</TH> \n";
		// $htmlDiv .= "<TH class='headertabeladados' width='1%'>Origem</TH> \n";
		// $htmlDiv .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%'>Tipo</TH> \n";		
		if($comDadosDemanda){			
			$htmlDiv .= "<TH class='headertabeladados' width='30%'>T�tulo</TH> \n";
		}
		$htmlDiv .= "<TH class='headertabeladados' width='90%'>Despacho</TH> \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%' >Anexo</TH> \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%' >Usu�rio</TH> \n";
		$htmlDiv .= "<TH class='headertabeladados' width='1%' >Refer�ncia</TH> \n";
		$htmlDiv .= "</TR> \n";

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
				
				$htmlDiv .= "<TR class='dados'> \n";

				if (! $isDetalhamento) {
					$htmlDiv .= "<TD class='tabeladados'> \n";
					$htmlDiv .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$htmlDiv .= "</TD> \n";
				}

				$htmlDiv .= "<TD class='tabeladados' nowrap>" . $voAtual->ano . "</TD> \n";
				$htmlDiv .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->cd, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$htmlDiv .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				//$htmlDiv .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorOrigem ) . "</TD> \n";
				//$htmlDiv .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";				
				$htmlDiv .= "<TD class='tabeladados'>$tipo</TD> \n";
				if($comDadosDemanda){
					$htmlDiv .= "<TD class='tabeladados'>" .  strtolower($voAtual->texto) . "</TD> \n";
				}
				$htmlDiv .= "<TD class='tabeladados' >" . strtolower($voAtual->textoTram) . "</TD> \n";
				
				$htmlDiv .= getHtmlDocumento($voAtual, false);				

				$htmlDiv .= "<TD class='tabeladados'>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$htmlDiv .= "<TD class='tabeladados'>" . getData ( $voAtual->dtReferencia) . "</TD> \n";

				$htmlDiv .= "</TR> \n";

				$sq ++;
			}
		}
		
		$htmlDiv .= "<TR>\n
		<TD class='totalizadortabeladadosalinhadodireita' colspan=$numColunas> Total registros: $i </TD>\n
		</TR>\n";
		

		$htmlDiv .= "</TBODY> \n";
		$htmlDiv .= "</TABLE> \n";
		
		$titulo = "&nbsp;&nbsp;Anexos/Demandas";
		$idDiv = "div_tramitacao";
		
		if($tamanho > 0){
			if(!$comDivExpansao){
				$html .= "<DIV class='campoformulario' id='$idDiv'>$titulo\n";
				$html .= $htmlDiv;
				$html .= "</DIV> \n";
			}else{
				$pArray = array($idDiv, $htmlDiv, $titulo);
				$html .= getDivHtmlExpansivelArray($pArray);
			}
		}else{
			$html .= "Sem tramita��es";
		}
				
		$html .= "</TH>\n";
		$html .= "</TR>\n";
	}

	echo $html;
}
	
function getEmailConvocacaoAssinatura($registro, $isAssinaturaSEI=false){

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
 		throw new excecaoChaveRegistroInexistente("Inclua o termo em quest�o na fun��o de contratos."); 		
 	}
 	
 	$vodemanda = new voDemanda();
 	$vodemanda->getDadosBanco($registro);
 	//$codigoContrato = getCodigoContratoFormatadoEmail($registro);
 	
 	//$codigoContrato = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
 	$codigoContratoCompleto = getTextoHTMLNegrito(getCodigoContratoPublicacao($vocontrato));
 	
 	$dsPessoa = $registro[vopessoa::$nmAtrNome];
 	
 	$a_preencher = "XXXXXXX";
 	$numSEI = $vodemanda->prt;
 	if($numSEI == null){
 		$numSEI = $a_preencher;
 	}
 	$str_confirmar = getTextoEmailAConfirmar();
	
	$pArrayCamposSubstituicao = array($vocontrato, $vocontratoinfo, $dsPessoa, $numSEI, $str_confirmar);
	if($isAssinaturaSEI){
		$retorno = getCorpoEmailAssinaturaSEI($pArrayCamposSubstituicao);
	}else{
		$retorno = getCorpoEmailAssinatura($pArrayCamposSubstituicao);
	}
	
	return $retorno;
}

function getDataLimiteAssinaturaDigital($dtInicioVigencia){
	return somarOuSubtrairDias($dtInicioVigencia, 1, "-", false);
}

function getCorpoEmailAssinatura($pArrayCamposSubstituicao){
	
	$vocontrato = $pArrayCamposSubstituicao[0];
	$vocontratoinfo = $pArrayCamposSubstituicao[1];
	$dsPessoa = $pArrayCamposSubstituicao[2];
	$numSEI = $pArrayCamposSubstituicao[3];
	$str_confirmar = $pArrayCamposSubstituicao[4];
	
	$codigoContratoCompleto = getTextoHTMLNegrito(getCodigoContratoPublicacao($vocontrato));
	
	$dtInicioVigencia = getData($vocontrato->dtVigenciaInicial);
	$isVigenciaAPartirAssinatura = $dtInicioVigencia == null;
	if($dtInicioVigencia == null){
		$dtAssinaturaDigital = $dtInicioVigencia = $str_confirmar;
		$dtInicioVigencia = "<u>SUA ASSINATURA</u>" . $dtInicioVigencia;
	}else{		
		//$dtAssinaturaDigital = somarOuSubtrairDias($dtInicioVigencia, 1, "-", false);
		$dtAssinaturaDigital = getDataAssinaturaLimite($vocontrato);
	}	

	$retorno = "<br>A ".getTextoHTMLNegrito($dsPessoa).",
	<br><br>
	ASSUNTO: ".getTextoHTMLNegrito("ASSINATURA DO $codigoContratoCompleto")."
	<br><br>".getTextoHTMLDestacado("Ref. SEI n� $numSEI", "blue").". $str_confirmar
	
		<br><br><br>Prezado(s) Senhor(es),<br><br><br>
		Em decorr�ncia da Pandemia do Coronav�rus e, cumprindo determina��o do Governo do Estado de Pernambuco, o atendimento presencial,
		na SEFAZ/PE foi substitu�do pelo trabalho remoto. Por esse motivo, estamos encaminhando para assinatura, em formato digital, 01(uma) via do supramencionado contrato,
		cujo objeto � ".getTextoHTMLNegrito($vocontrato->objeto)."$str_confirmar, com vig�ncia a partir de ".getTextoHTMLNegrito($dtInicioVigencia).".
	
	<br><br>Solicitamos que sejam impressas ".getTextoHTMLNegrito("02(duas) vias").", assinadas e rubricadas pelo representante legal, no prazo de at� ".getTextoHTMLNegrito("10(dez) dias")."
	contados a partir do recebimento do presente email, sendo posteriormente devolvidas pelos Correios ou protocoladas na  recep��o do pr�dio desta SEFAZ,
	situada na Av. Cruz Cabug�, N� 1419, T�rreo, Santo Amaro, Recife/PE, CEP.: 50.040-000, no hor�rio de atendimento presencial ".getTextoHTMLNegrito("excepcional: 9h as 15h.");
	
	//echo "garantia: " . $vocontratoinfo->inTemGarantia;
	if($vocontratoinfo->inTemGarantia == "S"){
		$retorno .= "<br><br>Por oportuno, informamos que ser� necess�ria a PRESTA��O, ou REFOR�O, se acr�scimo, ".getTextoHTMLNegrito("DA GARANTIA CONTRATUAL").",
		conforme previs�o edital�cia, devendo esta ser apresentada no prazo de at� ".getTextoHTMLNegrito("10(dez) dias �teis").".". getTextoEmailAConfirmar("CONFIRMAR PRAZO NO EDITAL");
	}
	
	try{
		$isDataRetroativa = isDataRetroativa($dtInicioVigencia);
		if(!$isDataRetroativa){
			$retorno .= "<br><br>Permite-se o uso da assinatura digital, desde que sejam tamb�m encaminhados os meios necess�rios
			� autentica��o do documento digital.$str_confirmar";
	
			if(isDataValidaNaoVazia($dtInicioVigencia)){
				//$retorno .= getTextoHTMLDestacado("<br><br>ATEN��O"). "<b>: sob pena de inadmissibilidade, a assinatura digital deve ocorrer at� ".getTextoHTMLDestacado($dtAssinaturaDigital)."</b>." . getTextoConfirmacaoData();				
				$retorno .= getTextoDataAssinaturaDigital($dtAssinaturaDigital);
			}
		}
	
	}catch (excecaoAtributoInvalido $ex){
		if(!$isVigenciaAPartirAssinatura){
			$retorno .= getTextoHTMLDestacado("<br><br>*****ATEN��O: verifique a data de in�cio de vig�ncia do contrato.*****<br><br>");
		}
	}
	//echo compararDatas($dthtmlassinatura, getDataHoje()) . " $dthtmlassinatura  e " . getDataHoje();
	
	$retorno .= "<br><br>Favor acusar recebimento, devolvendo a via assinada junto com os documentos que comprovem a legitimidade da representa��o legal do procurador assinante.";
	
	$sublinhado = getTextoHTMLDestacado("o n�mero do SEI", "black", true);
	$retorno .= getTextoHTMLNegrito("<br><br>Para um melhor atendimento, ao comparecer a esta unidade para recolhimento da via contratual, favor trazer, em m�os, $sublinhado a que se refere a presente demanda.");
	
	$retorno .= "<br><br>Atenciosamente,";
	
	return $retorno;
}

function getCorpoEmailAssinaturaSEI($pArrayCamposSubstituicao){

	$vocontrato = $pArrayCamposSubstituicao[0];
	$vocontratoinfo = $pArrayCamposSubstituicao[1];
	$dsPessoa = $pArrayCamposSubstituicao[2];
	$numSEI = $pArrayCamposSubstituicao[3];
	$str_confirmar = $pArrayCamposSubstituicao[4];

	$codigoContratoCompleto = getTextoHTMLNegrito(getCodigoContratoPublicacao($vocontrato));
	
	$dtInicioVigenciaBanco = $vocontrato->dtVigenciaInicial;
	$dtInicioVigencia = getData($dtInicioVigenciaBanco);
	//$vocontrato = new vocontrato();
	
	/*if(isDataValidaHtml($vocontrato->dtAssinatura)){
		throw new excecaoGenerica("Contrato j� assinado. N�o permitida nova assinatura pelo SEI.|". $vocontrato->getCodigoContratoFormatado(true));
	}*/
	
	if(isDataValidaNaoVazia($dtInicioVigenciaBanco) && isDataRetroativa($dtInicioVigencia)){
		throw new excecaoGenerica("Contrato retroativo: assinatura n�o permitida no SEI. In�cio de vig�ncia � $dtInicioVigencia.|". $vocontrato->getCodigoContratoFormatado(true));		
	}

	$isVigenciaAPartirAssinatura = $dtInicioVigencia == null;
	if($dtInicioVigencia == null){
		$dtAssinaturaDigital = $dtInicioVigencia = $str_confirmar;
		$dtInicioVigencia = "<u>SUA ASSINATURA</u>" . $dtInicioVigencia;
	}else{
		$dtAssinaturaDigital = $dtInicioVigencia;
		//somente o contrato mater permite que a assinatura seja igual ao inicio da vigencia
		/*if($vocontrato->cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
			$dtAssinaturaDigital = somarOuSubtrairDias($dtInicioVigencia, 1, "-", false);
		}*/		
		$dtAssinaturaDigital = getDataAssinaturaLimite($vocontrato);		
	}
	
	$linkATI = "http://www.portaisgoverno.pe.gov.br/web/site-ati/cadusuarioorgao";
	$linkDeclaracao = "http://www.portaisgoverno.pe.gov.br/c/document_library/get_file?uuid=b4cecfd0-b36b-4b4a-894a-cd8f0facd21e&groupId=20653";
	
	$nomeSEI = "Sistema Eletr�nico de Informa��o-SEI";
	$retorno = "<br>A ".getTextoHTMLNegrito($dsPessoa).",
	<br><br>
	ASSUNTO: ".getTextoHTMLNegrito("ASSINATURA DO $codigoContratoCompleto")."
	<br><br>".getTextoHTMLDestacado("Ref. SEI n� $numSEI", "blue").". $str_confirmar
				
		<br><br><br>A Unidade de Contratos-UNCT/DILC/SEFAZ, vem, por meio deste, convocar para a assinatura do contrato supramencionado, cujo objeto � ".getTextoHTMLNegrito($vocontrato->objeto)
		."$str_confirmar, com vig�ncia a partir de ".getTextoHTMLNegrito($dtInicioVigencia);
		
		$vopessoa = getVOPessoaContratadaContrato($vocontrato);
		$isEmpresaAssinaPeloSEI = isContratadaAssinaPeloSEI($vopessoa);				
		if($isEmpresaAssinaPeloSEI){
			//$vopessoa = new vopessoa();
			$dadosSEI = getTextoHTMLDestacado($vopessoa->emailSEI);
			$retorno .= ", j� dispon�vel no <b>$nomeSEI</b>, conforme cadastro j� realizado anteriormente, cujo email assinante � <b><u>'$dadosSEI'</u></b>.
			<br><br> Havendo qualquer erro ou impedimento, favor
			nos informar, em resposta a este email.";
		}else{
			$retorno .= ", fazendo-se necess�rio o cadastro no <b>$nomeSEI</b>, de acordo com os seguintes passos:";		
			$retorno .= "<b><br><br>1.	Se o fornecedor j� possuir cadastro no sistema, deve apenas responder este email indicando 'nome' e 'email' do 
			respons�vel pela assinatura do termo em quest�o no SEI, <u>ignorando os passos, a seguir, que se referem exclusivamente ao cadastro</u>.</b> 
	
			<b><br><br>2.	Caso contr�rio, o fornecedor deve realizar o cadastro de usu�rio externo ao SEI no site da ATI</b>: 
			<br>	a.Ir em ".getTextoLink($linkATI,$linkATI)." ;
			<br>	b.Selecionar o �rg�o ao qual tem seu processo vinculado (nosso caso: SEFAZ);
			<br>	c.Para se cadastrar, ir em: 'Clique aqui se voc� ainda n�o est� cadastrado';
			
			<b><br><br>3.	Ap�s o cadastro, o fornecedor receber� um e-mail autom�tico solicitando a documenta��o necess�ria, dentre as quais</b>:
			<br>	a.C�pia de comprovante de resid�ncia;
			<br>	b.C�pias de RG e CPF ou de outro documento de identidade no qual conste o CPF;
			<br>	c.Termo de Declara��o de Concord�ncia e Veracidade preenchido e assinado, no link ".getTextoLink($linkDeclaracao,$linkDeclaracao).";"
				. getTextoHTMLDestacado("<br><br>ATEN��O: DESCONSIDERAR as informa��es contidas no e-mail autom�tico de orienta��o enviado pelo SEI, ap�s o envio do formul�rio, a respeito da entrega da documenta��o, cujas regras a serem seguidas est�o detalhadas no item a seguir")
				
				. "<b><br><br>4.	Os documentos necess�rios devem ser enviados como anexos em RESPOSTA A ESTE EMAIL.</b>"
		
				. "<b><br><br>5.	O fornecedor deve aguardar, via email, as instru��es para assinatura do termo.</b>";
		}
		
		if(!isAtributoValido($dtAssinaturaDigital)){
			$msg  = getTextoHTMLDestacado("<br><br>*****ATEN��O: verifique a data de in�cio de vig�ncia do contrato.*****<br><br>");
			throw new excecaoAtributoInvalido($msg);
		}
		
		//$retorno .= getTextoHTMLDestacado("<br><br>ATEN��O"). "<b>: sob pena de inadmissibilidade, a assinatura digital deve ocorrer at� ".getTextoHTMLDestacado($dtAssinaturaDigital)."</b>.$str_confirmar";
		if(!$isVigenciaAPartirAssinatura){
			$retorno .= getTextoDataAssinaturaDigital($dtAssinaturaDigital);
		}
			
		if($vocontratoinfo->inTemGarantia == "S"){
			$retorno .= "<br><br>Por oportuno, informamos que ser� necess�ria a PRESTA��O, ou REFOR�O, se acr�scimo, ".getTextoHTMLNegrito("DA GARANTIA CONTRATUAL").",
				conforme previs�o edital�cia, devendo esta ser apresentada no prazo de at� ".getTextoHTMLNegrito("10(dez) dias �teis").".". getTextoEmailAConfirmar("CONFIRMAR PRAZO NO EDITAL");
		}
		
		$retorno .= "<br><br>Atenciosamente,";

	return $retorno;
}


function isDemandaContratoModificacaoObrigatorio($vodemanda){
	if($vodemanda->tpDemandaContrato == null){
		throw new excecaoAtributoInvalido("tpDemandaContrato nao pode ser nulo.");		
	}	
	return dominioTipoDemandaContrato::existePeloMenosUmaChaveColecaoNoArrayOuStrSeparador(array_keys(dominioTipoDemandaContrato::getColecaoAlteraValorContrato()), $vodemanda->tpDemandaContrato);
}

function validaSEIExistente($SEI){
	$retorno = "";
	if($SEI != null){
		
		$SEI = voDemandaTramitacao::getNumeroPRTSemMascara($SEI);
		$filtro = new filtroManterDemanda(false);
		//$filtro->inDesativado = constantes::$CD_NAO;
		$filtro->setCdHistorico(constantes::$CD_NAO, new voDemanda());
		$filtro->vodemanda->prt = $SEI;
		//$filtro->vodemanda->situacao = dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER;

		$db = new dbDemanda();
		$colecao = $db->consultarTelaConsulta(new voDemanda(), $filtro);
		if(!isColecaoVazia($colecao)){
			$retorno = getTextoHTMLDestacado("Demanda SEI existente. <u>UTILIZE-A SE SE TRATAR DO MESMO 'EVENTO'</u>.", "red", false);
			
			//$retorno .= getTagHTMLAbreJavaScript() . "alert(1);" . getTagHTMLFechaJavaScript(); 
		}

	}
	return $retorno;
}

function isDemandaContratoAssinado($voDemanda){
	return existePeloMenosUmItemNoArrayOuString(dominioFaseDemanda::getColecaoFaseContratoAssinado(), $voDemanda->fase);
}

function getTextoEmailAConfirmar($texto="CONFIRMAR"){
	return getTextoHTMLDestacado("[$texto, REMOVENDO ESSE TRECHO AP�S A CONFIRMA��O]", "red", false, "amarelo");
}

function getTextoDataAssinaturaDigital($dtAssinaturaDigital){
	return getTextoHTMLDestacado("<br><br>ATEN��O"). "<b>: sob pena de inadmissibilidade, a assinatura digital deve ocorrer at� ".getTextoHTMLDestacado($dtAssinaturaDigital)."</b>." . getTextoEmailAConfirmar();
}

function getTextoConfirmacaoData(){
	return getTextoHTMLDestacado("[N�O HAVENDO DATA PRE-ESTABELECIDA, REMOVER TODO O TRECHO QUE MENCIONA A DATA EM QUEST�O]", "red", false, "amarelo");
}

function getVOContratoDemandaPorChave($vo, $levantaExcecao = true){
	$voTemp = clone $vo;
	$vocontratoDemanda = $voTemp->getContrato();

	if($vocontratoDemanda != null){
		try{
			$dbContrato = new dbcontrato();
			//var_dump($vocontratoDemanda);
			$vocontratoDemanda =$dbContrato->consultarPorChaveVO($vocontratoDemanda);
		}catch (excecaoChaveRegistroInexistente $ex){
			if($levantaExcecao){
				throw new excecaoGenerica("Verifique se o termo relacionado foi inclu�do corretamente na fun��o 'contratos'"
						."|Contrato:" . $vocontratoDemanda->getCodigoContratoFormatado(true) . ".");
			}
		}
	}

	return $vocontratoDemanda;
}


?>