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

function imprimeBotaoDocumento($vodocumento, $descricao){
	
	$vodocumento = $vodocumento->dbprocesso->consultarPorChaveVO($vodocumento, false);
	
	$html = 
	"\n<TR>
	\n <TD class='campoformulario'>
	$descricao: ";	
	
	$html .= getBotaoAbrirDocumentoVO($vodocumento);
	
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

function getBotaoAbrirDocumentoMais($pNmCampolink, $nmFuncaoJavaScript, $isMenuSistema=false){
	
	$paramIsMenu = "false";
	if($isMenuSistema){
		$paramIsMenu = "true";
	}
	
	$retorno = "";
	$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."Cliente('" . $pNmCampolink. "',$paramIsMenu);";
	if(isUsuarioAdmin()){
		$complementoJS = "onClick=javascript:".$nmFuncaoJavaScript."('" . $pNmCampolink. "',$paramIsMenu);";
	}		
	$retorno = getBotaoValidacaoAcesso("bttabrirpasta", "Abrir", "botaofuncaop", false,true,true,true, "$complementoJS accesskey='m'");
	return $retorno;
}

function getBotaoAbrirDocumento($pNmCampolink, $isMenuSistema=false){	
	return getBotaoAbrirDocumentoMais($pNmCampolink, "abrirArquivo", $isMenuSistema);
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