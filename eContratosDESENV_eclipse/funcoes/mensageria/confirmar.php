<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

inicioComValidacaoUsuario(true);

$vo = new voMensageria();
$vo->getDadosFormulario();

//var_dump($vo);

/*$novaObs = @$_POST[voContratoInfo::$nmAtrObs];
$vo->setObsParaIncluirNoBanco($novaObs);*/
//$vo->setCarimboObsParaIncluirNoBanco();

//imprimeHtml($vo->obs);

putObjetoSessao("vo", $vo);

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>