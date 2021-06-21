<?php
include_once("../../config_lib.php");

$vocontrato = new vocontrato();

$chave = @$_GET ["chave"];
$array = explode ( CAMPO_SEPARADOR, $chave );

/*$ano = $array[0];
$setor = $array[1];
$tipo = $array[2];*/

$SEI = $chave;
echo validaSEIExistente($SEI);

?>