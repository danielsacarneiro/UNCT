<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voContratoTramitacao.php");
inicio();

$vo = new voContratoTramitacao();
$vo->getDadosFormulario();

putObjetoSessao("vo", $vo);

//var_dump($vo);

/*echo $vo->toString(). "<BR>";

if($vo->voDoc != null)
	echo $vo->voDoc->toString(). "<BR>";*/

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>