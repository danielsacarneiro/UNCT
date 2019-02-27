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

?>