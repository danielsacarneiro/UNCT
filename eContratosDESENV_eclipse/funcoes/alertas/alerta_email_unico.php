<?php
//envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");

//envia alertas dos editais
$mensagem = getMensagemEdital();
//demandas que seguem para a SAD
$mensagem .= getMensagemDemandaSAD();
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
$mensagem .= getMensagemFimPrazoPAAP();
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste
$mensagem .= getMensagemDemandaContratoPropostaVencida();

echo $mensagem;

$assunto = "Relatório diário";
enviarEmail($assunto, $mensagem);
