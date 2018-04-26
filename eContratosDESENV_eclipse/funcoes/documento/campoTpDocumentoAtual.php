<?php
include_once("../../config_lib.php");
include_once(caminho_funcoes . voDocumento::getNmTabela(). "/biblioteca_htmlDocumento.php");

$chave = @$_GET ["chave"];
$array = explode ( CAMPO_SEPARADOR, $chave );
$setor = $array[0];
/*$ano = $array[0];
$setor = $array[1];
$tipo = $array[2];*/

if($setor == dominioSetor::$CD_SETOR_UNCT){
	$comboTp= new select(dominioTpDocumento::getColecaoUNCT());
}else{
	$comboTp= new select(dominioTpDocumento::getColecaoATJA());
}
echo $comboTp->getHtmlCombo(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, "", true, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, " onChange='criarNomeDocumento(this);' ");
?>