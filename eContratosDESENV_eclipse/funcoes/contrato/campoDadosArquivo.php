<?php
include_once("../../config_lib.php");

$vodocumento = new voDocumento();
$arquivo = $_FILES [vocontrato::$nmAtrLinkDoc];
$chave = $arquivo["name"]; 
//var_dump($arquivo);

$array = explode("\\", $chave);
$nmArquivo = $array[(sizeof($array))-1];

$nmPasta = dominioTpDocumento::getEnderecoPastaBaseUNCT() . "\\" . dominioTpDocumento::getEnderecoPastaTermoDigitalizado() . "\\";

$nmPasta = str_replace(dominioTpDocumento::$ENDERECO_DRIVE, dominioTpDocumento::$UNIDADE_REDE_LOCAL, $nmPasta);
$nmPasta = "uploads/";
echoo($nmPasta);
//$nmPasta = str_replace("\\", "/", $nmPasta);
//echoo($nmPasta);
$enderecoCompletoArquivo = "$nmPasta\\$nmArquivo" ; 

uploadArquivo(vocontrato::$nmAtrLinkDoc, $nmPasta);

//$fileFiltro = new FileFilter($diretorio, $nmArquivo);
?>