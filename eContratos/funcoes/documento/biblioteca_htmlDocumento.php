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

function getBotaoAbrirDocumento($pNmCampolink){
	$retorno = "";
	$retorno = getBotaoValidacaoAcesso("bttabrirpasta", "Abrir", "botaofuncaop", false,true,true,true, "onClick=javascript:abrirArquivoCliente('" . $pNmCampolink. "'); accesskey='m'");	
	if(isUsuarioAdmin()){
		$retorno .= "&nbsp;" . getBotaoValidacaoAcesso("bttabrirservidor", "Abrir no Servidor", "botaofuncaop", false,true,true,true, "onClick=javascript:abrirArquivo('" . $pNmCampolink. "'); accesskey='m'");
	}
	
	return $retorno;
}

?>