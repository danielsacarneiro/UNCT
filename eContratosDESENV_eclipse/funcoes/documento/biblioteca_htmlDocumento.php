<?php
include_once(caminho_util."dominioSetor.php");

function getSqDocumentoAtual($arrayParam) {
	$ano = $arrayParam[0];
	$setor = $arrayParam[1];
	$tipo = $arrayParam[2];
	$filtro = new filtroManterDocumento();
	$filtro->ano = $ano;
	$filtro->cdSetor = $setor;
	$filtro->tp = $tipo;
	
	$dbdocumento = new dbDocumento();
	$retorno = $dbdocumento->getProximoSqDoc($filtro); 

	return $retorno;
}

/*function imprimeBotaoDocumento($vodocumento, $descricao){
	
	$vodocumento = $vodocumento->dbprocesso->consultarPorChaveVO($vodocumento, false);
	
	$html = 
	"\n<TR>
	\n <TD class='campoformulario'>
	$descricao: " . $vodocumento->formatarCodigo() . " ";	
	
	$html .= getBotaoAbrirDocumentoVO($vodocumento);	
	//$html .= getHtmlDocumento($vodocumento);
	
	$html .= 
	"\n </TD>
	\n</TR>";
	
	return $html; 
}*/

function imprimeLinkDocumento($link, $descricao){
	
	$html =
	"\n<TR>
	\n <TD class='campoformulario'>
	$descricao: ";

	$html .= getLinkPesquisa($link) . " \n";

	$html .=
	"\n </TD>
	\n</TR>";

	return $html;
}


function imprimeBotaoDocumento($vodocumento, $descricao){
	$voDoc = $vodocumento->dbprocesso->consultarPorChaveVO($vodocumento, false);
	$html =
	"\n<TR>
	\n <TD class='campoformulario'>
	$descricao: ";	

	$endereco = $voDoc->getEnderecoTpDocumento ();
	$chave = $voDoc->getValorChavePrimaria ();
		
	$html .= $voDoc->formatarCodigo ($comDescricaoPorExtenso) . " \n";
	$html .= "<input type='hidden' name='" . $chave . "' id='" . $chave . "' value='" . $endereco . "'>" . " \n";
	// $html .= getBotaoValidacaoAcesso("bttabrir_arq", "Abrir Anexo", "botaofuncaop", false,true,true,true, "onClick=\"javascript:abrirArquivo('".$chave."');\"");
	$html .= getBotaoAbrirDocumento ( $chave, true);
	
	$html .=
	"\n </TD>
	\n</TR>";
	
	return $html; 
}


function formatarCodigoDocumento($sq, $cdSetor, $ano, $tpDoc){	
	$dominioSetor = new dominioSetor();	
	$str = "";	
	/*if($sq != null){
		$str = $tpDoc
			. " " . complementarCharAEsquerda($sq, "0",  TAMANHO_CODIGOS_SAFI)
			. "-" . substr($ano, 2, 2)
			. "/" . $dominioSetor->getDescricao($cdSetor);
	}*/
	
	if($sq != null){
		 $str = $dominioSetor->getDescricao($cdSetor)
		 . " " . $tpDoc 
		 . " " . complementarCharAEsquerda($sq, "0",  TAMANHO_CODIGOS_SAFI)
		 . "-" . substr($ano, 2, 2)
		 ;
	 }
	
	return $str;
}

function getBotaoDocumentoArray($pArray){
	$pNmCampolink = $pArray[0];
	$nmFuncaoJavaScript = $pArray[1];	
	$isMenuSistema=$pArray[2];
	$nmBotao =$pArray[3];
	
	if($isMenuSistema == null){
		$isMenuSistema = false;
	}
	
	if($nmBotao == null){
		$nmBotao = "Abrir";
	}

	$paramIsMenu = "false";
	if($isMenuSistema){
		$paramIsMenu = "true";
	}
	
	$retorno = "";
	//$retorno = getBotaoAbrirDocumento ( $pNmCampolink );
	$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."Cliente('" . $pNmCampolink. "',$paramIsMenu);";
	if(isUsuarioAdmin()){
		$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."('" . $pNmCampolink. "',$paramIsMenu);";
	}
	$retorno = getBotaoValidacaoAcesso("bttabrirpasta", $nmBotao, "botaofuncaop", false,true,true,true, "$complementoJS accesskey='m'");
	return $retorno;
}

function getBotaoAbrirDocumentoMais($pNmCampolink, $nmFuncaoJavaScript, $isMenuSistema=false){
	$pArray[0] = $pNmCampolink;
	$pArray[1] = $nmFuncaoJavaScript;
	$pArray[2] = $isMenuSistema;
	
	return getBotaoDocumentoArray($pArray);
}

function getBotaoAbrirDocumento($pNmCampolink, $isMenuSistema=false, $nmBotao = "Abrir"){
	$pArray[0] = $pNmCampolink;
	$pArray[1] = "abrirArquivo";
	$pArray[2] = $isMenuSistema;
	$pArray[3] = $nmBotao;
	
	return getBotaoDocumentoArray($pArray);
}

function getBotaoAbrirDocumentoVO($vodoc, $isMenuSistema = true){
	//$vodoc = new voDocumento();	
	if($vodoc->ano == null){
		throw new excecaoGenerica("Indique a chave do documento para o link.");
	}
	$chave = $vodoc->getValorChaveHTML();
	$endereco = $vodoc->getEnderecoTpDocumento();
	echo getInputHidden($chave, $chave, $endereco);
	
	return getBotaoAbrirDocumento($chave, $isMenuSistema);
}

/**
 * 
 * @param unknown $voContrato vocontrato
 */
function getHTMLDocumentoContratoPorDemandaDoc($voContrato, $tpDoc, $isVersaoResumida=false, $isAlteracaoContrato=false){
	
	$vodemandatram = new voDemandaTramitacao();
	$vodoc = getDocumentoContrato($voContrato, $tpDoc);
	//var_dump($vodocminuta);
	$temDoc = $vodoc != null;
	if($temDoc){
		$vodemandatram->voDoc = $vodoc;
	}
	
	$msgDocNaoExiste = "Documento não inserido na demanda.";	
	
	//$voContrato = new vocontrato();
	//var_dump($voContrato);
	$nmCampo = $voContrato->getValorChaveLogica();
	if(dominioTpDocumento::$CD_TP_DOC_CONTRATO == $tpDoc){
		$enderecoTemp = vocontrato::getEnredeçoDocumento($voContrato->linkDoc);
		$nmCampo .= vocontrato::$nmAtrLinkDoc;
		$descricao = "PDF";
		$cor = "blue";
	}else{
		$enderecoTemp = vocontrato::getEnredeçoDocumento($voContrato->linkMinutaDoc);
		$nmCampo .= vocontrato::$nmAtrLinkMinutaDoc;
		$descricao = "Minuta";
		$cor = "red";
	}

	if(!$isVersaoResumida){
		$retorno .= getTextoHTMLDestacado("  |$descricao: ", $cor, false) . getHtmlDocumentoSemTD($vodemandatram, false, "tabeladadosalinhadoesquerda", $msgDocNaoExiste); 
	}else{ 
		//getTextoHTMLDestacado("$descricao", $cor, false)
		$pArray = array($vodemandatram, false, "tabeladadosalinhadoesquerda", "", false,true, $descricao);
		$retorno .= getHtmlDocumentoArray($pArray);
	}
	
	if(!$temDoc && isAtributoValido($enderecoTemp)){
		$opcaohtml = "readonly";
		$classEndereco = "camporeadonly";
		if($isAlteracaoContrato){
			$opcaohtml = "";
			$classEndereco = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;				
		}
	
		if(!$isVersaoResumida){
			$retorno .= "<br>".getTextoHTMLDestacado("Exibindo antigo link $descricao planilha: ", $cor, false)
			."<textarea id='$nmCampo' name='$nmCampo' rows='2' cols='80' class='$classEndereco' $opcaohtml>"
					. $enderecoTemp . "</textarea>"
						. getBotaoAbrirDocumento($nmCampo)
						;
		}else{
			//echo $enderecoTemp;
			$retorno .= 
			getInputHidden($nmCampo, $nmCampo, $enderecoTemp)
			. getBotaoAbrirDocumento($nmCampo, false, $descricao)
					;						
		}
	}
	
	return array($retorno, $temDoc);
}

/**
 * recupera ambos arquivos minuta e contrato do termo
 * @param unknown $voContrato
 * @return string[]|boolean[]
 */
function getHTMLDocumentosContrato($voContrato, $isVersaoResumida=false, $isAlteracaoContrato=false){
	//$voContrato = new vocontrato();
	//echo $voContrato->linkDoc;
		$array = getHTMLDocumentoContratoPorDemandaDoc($voContrato, dominioTpDocumento::$CD_TP_DOC_MINUTA, $isVersaoResumida);
		$retorno .= $array[0];
		$temDocMinuta = $array[1]; 
		
		$array = getHTMLDocumentoContratoPorDemandaDoc($voContrato, dominioTpDocumento::$CD_TP_DOC_CONTRATO, $isVersaoResumida, $isAlteracaoContrato);
		$retorno .= "<br>".$array[0];
		$temDocPDF = $array[1];
		
		
		$temDocsAExibir = $temDocMinuta || $temDocPDF;
		$temAmbosDocsAExibir = $temDocMinuta && $temDocPDF;
		$array[0] = $temDocsAExibir;
		$array[1] = $retorno;
		$array[2] = $temAmbosDocsAExibir;
	
	return $array;
	
}
function getDocumentoContrato($vocontrato, $tpDoc = null){
	if($tpDoc == null){
		$tpDoc = dominioTpDocumento::$CD_TP_DOC_CONTRATO;
	}
	//$vocontrato = new vocontrato();	
	$filtro = new filtroManterDocumento(false, false);
	$filtro->vocontrato = $vocontrato;
	$filtro->cdSetor = dominioSetor::$CD_SETOR_UNCT;
	$filtro->tp = $tpDoc;
	$filtro->cdAtrOrdenacao = vodocumento::$nmAtrAno . " DESC, " . voDocumento::$nmAtrSq . " DESC";
	
	$dbDoc = new dbDocumento();
	$colecao = $dbDoc->consultarTelaConsulta(array($filtro));
	//$colecao = $dbDoc->consultarDocumento(new voDocumento(), $filtro);	
	
	//var_dump($colecao);
	
	if(!isColecaoVazia($colecao)){
		$retorno = new voDocumento();
		$retorno->getDadosBanco($colecao[0]);
	}
	
	return $retorno;
}

?>