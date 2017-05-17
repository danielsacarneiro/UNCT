<?php
include_once(caminho_util."dominioSetor.php");

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

function getBotaoAbrirDocumentoMais($pLink, $javaScript){
	$retorno = "";
	$retorno = getBotaoValidacaoAcesso("bttabrirpasta", "Abrir", "botaofuncaop", false,true,true,true, "onClick=javascript:".$javaScript."Cliente('" . $pLink. "'); accesskey='m'");
	if(isUsuarioAdmin()){
		$retorno .= "&nbsp;" . getBotaoValidacaoAcesso("bttabrirservidor", "Abrir no Servidor", "botaofuncaop", false,true,true,true, "onClick=javascript:".$javaScript."('" . $pLink. "'); accesskey='m'");
	}

	return $retorno;
}

function getBotaoAbrirDocumento($pNmCampolink){	
	return getBotaoAbrirDocumentoMais($pNmCampolink, "abrirArquivo");
}

?>