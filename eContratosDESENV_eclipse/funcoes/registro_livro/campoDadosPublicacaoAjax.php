<?php
include_once ("../../config_lib.php");
include_once (caminho_funcoes . "registro_livro/biblioteca_htmlRegistro.php");

$chave = @$_GET ["chave"];
$voentidade = @$_GET ["voentidade"];
echo getDadosPublicacaoChaves( $chave, $voentidade );

?>