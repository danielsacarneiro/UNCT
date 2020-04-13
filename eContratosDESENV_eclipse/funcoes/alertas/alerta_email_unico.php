<?php
//envia alertas dos editais
require_once ("alertas.php");
require_once ("Biblioteca_alertas.php");
require_once (caminho_util . "bibliotecaFuncoesPrincipal.php");
require_once (caminho_funcoes."demanda/biblioteca_htmlDemanda.php");

$enviarEmail = @$_GET [constantes::$ID_REQ_IN_ENVIAR_EMAIL];
if($enviarEmail != null){
	$enviarEmail = getAtributoComoBooleano($enviarEmail);
}else{
	$enviarEmail = true;
}
$enviarEmail = $enviarEmail && voMensageria::$ATIVADO && voMensageria::$ENVIAR_EMAIL_RELATORIO_DIARIO;

if (!($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL)) {
	echoo("<font color='red'><b>RELATÓRIO DIÁRIO: <u>SEM email</u>.</b></font></br>");
}

$count = 0;

//envia alertas dos editais
$mensagem .= getMensagemAltaPrioridade($count);
//demandas que seguem para a SAD
$mensagem .= getMensagemDemandaSAD($count);
//envia alertas dos PAAPs pendentes de abertura
$mensagem .= getMensagemPAAPAbertoNaoEncaminhado($count);
//envia alertas dos PAAPs A Executar
//$mensagem .= getMensagemPAAPAExecutar($count);
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
$mensagem .= getMensagemFimPrazoPAAP($count);
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste
$mensagem .= getMensagemDemandaContratoPropostaVencida($count);
$mensagem .= getMensagemSistemasExternos($count);
//envia alertas dos contratos a vencer. Ainda depende de definicao da diretoria
//$mensagem .= getMensagemContratosAVencer();

echo $mensagem . getBotaoDetalharAlertas();

$assunto = "Relatório diário";
enviarEmail($assunto, $mensagem, $enviarEmail);

function getBotaoDetalharAlertas(){	
	$retorno .= getTagHTMLAbreJavaScript();
	$retorno .= getFuncaoJSDetalharEmailPorVO(new voDemanda());
	$retorno .= getTagHTMLFechaJavaScript();
	$retorno .= "\n<TABLE width='100%' id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=0>\n
				<TBODY>";
	$retorno .= "<TR>\n
	<TD class='botaofuncao' colspan=$colspan>" . getBotaoDetalhar () . "</TD>\n
				</TR>\n";
	$retorno .= "</TBODY>\n
				</TABLE>";
	
	return $retorno;

}
