<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voDocumento.php");
inicio();

$vo = new voDocumento();
$vo->getDadosFormulario();

putObjetoSessao("vo", $vo);

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>