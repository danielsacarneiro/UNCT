<?php
include_once("../../config_lib.php");
include_once("biblioteca_htmlPessoa.php");
include_once(caminho_vos . "vocontrato.php");

$cdGestor = @$_GET[vocontrato::$nmAtrCdGestorContrato];

echo getComboGestorPessoa(null, vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $cdGestor, "");                    

?>