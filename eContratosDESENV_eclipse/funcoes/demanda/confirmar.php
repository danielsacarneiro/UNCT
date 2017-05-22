<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voDemandaTramitacao.php");
inicioComValidacaoUsuario(true);

$vo = new voDemandaTramitacao();
$vo->getDadosFormulario();

//var_dump($vo->colecaoContrato);

putObjetoSessao("vo", $vo);
//echo $funcao = @$_POST["funcao"];

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>