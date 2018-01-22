<?php
//envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];
$enviarEmail = getAtributoComoBooleano($enviarEmail);

$count = 0;
//envia alertas dos editais
$mensagem = getMensagemEdital($count);
//demandas que seguem para a SAD
$mensagem .= getMensagemDemandaSAD($count);
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
$mensagem .= getMensagemFimPrazoPAAP($count);
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste
$mensagem .= getMensagemDemandaContratoPropostaVencida($count);
//envia alertas dos contratos a vencer. Ainda depende de definicao da diretoria
//$mensagem .= getMensagemContratosAVencer();

echo $mensagem;

$assunto = "Relatório diário";
enviarEmail($assunto, $mensagem, $enviarEmail);
