<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbcontrato.php");

$db = new dbcontrato();
inicio();

echo $db->atualizarPessoasContrato();
?>