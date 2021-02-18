<?php
include_once("../../config_lib.php");

$chave = @$_GET ["chave"];
$vodocumento = new voDocumento();
$vodocumento->getChavePrimariaVOExplodeParam($chave);

$array = explode(CAMPO_SEPARADOR, $chave);
$nmarquivo = $array[(sizeof($array))-1];

$vodocumento->link = $nmarquivo;

if(isAtributoValido($nmarquivo)){
echo "Prévia da pasta:" . getTextoHTMLNegrito($vodocumento->getEnderecoTpDocumento());
}else{
	echo "Preencha todos os campos necessários.";
}
//"|chave:" . $vodocumento->toString();
?>