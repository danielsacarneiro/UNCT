<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbcontrato.php");
inicio();

$vo = new vocontrato();
//recupera dos dados do formulario
$vo->getDadosFormulario();
//$vo->cd = 1;

session_start();
$_SESSION["vo"] = $vo;
//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307); 
?>