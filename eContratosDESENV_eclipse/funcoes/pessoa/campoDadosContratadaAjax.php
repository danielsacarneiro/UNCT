<?php
include_once ("../../config_lib.php");
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");

$chave = @$_GET ["chave"];
$voentidade = @$_GET ["voentidade"];
$funcao = @$_GET ["funcao"];
echo getDadosContratada ( $chave, $voentidade,$funcao );

?>