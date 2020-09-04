<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."voDemandaTramitacao.php");
inicioComValidacaoUsuario(true);

$vo = new voDemandaTramitacao();
$vo->getDadosFormulario();

//echo "modalidade: " . $vo->voProcLicitatorio->cdModalidade;

//var_dump($vo->voSolicCompra);

/*$voDemandaCont = new voDemandaContrato();
$voDemandaCont->getDadosFormulario();
var_dump($voDemandaCont->tpDemandaContrato);*/

//echo $vo->voProcLicitatorio->toString();

/*echo $vo->cdSetor . " cd setor <BR>";
echo $vo->cdSetorAtual . " cd setor atual <BR>";
echo $vo->cdSetorDestino . " cd setor destino <BR>";
echo $vo->cdSetorOrigem . " cd setor origem <BR>";*/

putObjetoSessao("vo", $vo);

/*$cdSetor = $vo->cdSetorOrigem;
$cdSetorDestino = $vo->cdSetorDestino;
echo "$cdSetor e $cdSetorDestino";*/
//echo $funcao = @$_POST["funcao"];

//redirecionar mantendo o post
//o codigo 307 especificado no RFC do protocolo HTTP 1.0 como temporary redirect, mantendo o post

header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
?>