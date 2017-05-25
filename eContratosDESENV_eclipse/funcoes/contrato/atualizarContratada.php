<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbcontrato.php");

$db = new dbcontrato();
echo $db->atualizarPessoasContrato();

header ( "Location: ../mensagemErro.php?cdTela=1", TRUE, 307 );
?>