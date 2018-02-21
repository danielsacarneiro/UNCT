<?php
include_once("../../config_lib.php");
include_once(caminho_funcoes . "pa/biblioteca_htmlPA.php");

$chave = @$_GET ["chave"];
$array = explode ( CAMPO_SEPARADOR, $chave );
$idCampo = $array[0];
$data = $array[1];
$prazo = $array[2];

//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo getDataPrazoFinal($data, $prazo, $idCampo);

?>