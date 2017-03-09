<?php
include_once(caminho_util."dominioSetor.php");

function formatarCodigoDocumento($sq, $cdSetor, $ano, $tpDoc){
	
	$dominioSetor = new dominioSetor();
	
	$str = "";
	if($sq != null){
		$str = $tpDoc
			. " " . complementarCharAEsquerda($sq, "0",  TAMANHO_CODIGOS_SAFI)
			. "-" . substr($ano, 2, 2)
			. "/" . $dominioSetor->getDescricao($cdSetor);
	}
	
	return $str;
}

?>