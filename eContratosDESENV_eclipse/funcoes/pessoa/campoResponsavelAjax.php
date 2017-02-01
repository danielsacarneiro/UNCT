<?php
include_once("../../config_lib.php");
include_once("biblioteca_htmlPessoa.php");
include_once(caminho_vos . "vogestor.php");
include_once(caminho_vos . "vopessoavinculo.php");

$cdGestor = @$_GET[vogestor::$nmAtrDescricao];

//echo getComboGestorPessoa(null, vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $cdGestor, "");      
echo getComboGestorResponsavel($cdGestor);

?>