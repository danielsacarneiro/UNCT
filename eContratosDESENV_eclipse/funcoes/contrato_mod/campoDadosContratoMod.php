<?php
include_once ("../../config_lib.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");

$chave = @$_GET ["chave"];
echo getDadosContratoMod ( $chave);
