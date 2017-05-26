<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

inicioComValidacaoUsuario(true);

$vo = new voUsuarioInfo();
$vo->getDadosFormulario();

//echo $vo->id;
//var_dump($vo->colecaoSetor);


putObjetoSessao("vo", $vo);

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>