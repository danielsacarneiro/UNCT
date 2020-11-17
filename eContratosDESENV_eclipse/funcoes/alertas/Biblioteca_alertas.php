<?php
// include_once ("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");

function incluirColunaColecaoArray($colecao, $array){		
	$colecao[] = $array;
	return $colecao;
}
	/**
	 *
	 * @deprecated
	 *        	
	 * @return string
	 */

function incluirColunaColecao($colecao, $tituloColuna, $valorColuna, $tipoDado = null, $valorReferencia = null, $tpValidacao = null){	
	/*$colecao[] =array( 
			constantes::$CD_COLUNA_CHAVE => $tituloColuna,
			constantes::$CD_COLUNA_VALOR => $valorColuna,
			constantes::$CD_COLUNA_TP_DADO =>  $tipoDado,
			constantes::$CD_COLUNA_VL_REFERENCIA =>  $valorReferencia,
			constantes::$CD_COLUNA_TP_VALIDACAO =>  $tpValidacao,
			
	);*/
	
	$array =array(
	 constantes::$CD_COLUNA_CHAVE => $tituloColuna,
	 constantes::$CD_COLUNA_VALOR => $valorColuna,
	 constantes::$CD_COLUNA_TP_DADO =>  $tipoDado,
	 constantes::$CD_COLUNA_VL_REFERENCIA =>  $valorReferencia,
	 constantes::$CD_COLUNA_TP_VALIDACAO =>  $tpValidacao,	 	
	 );
	
	$colecao = incluirColunaColecaoArray($colecao, $array);
	return $colecao;
}

function getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar = null, $isPrioritario = false){	
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'NÚMERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);
	$colunas = incluirColunaColecao($colunas, 'TÍTULO', voDemanda::$nmAtrTexto);
	
	if($colunasAAcrescentar !=null){
		$colunas = array_merge($colunas, $colunasAAcrescentar);
	}
	
	return getCorpoMensagemPorColecao($assunto, $colecao, $colunas, $isPrioritario);
}

function getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar=null, $semTitulo=false) {

	$colunas = incluirColunaColecao($colunas, 'SEI', voDemanda::$nmAtrProtocolo);
	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'NÚMERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);
	if(!$semTitulo){
		$colunas = incluirColunaColecao($colunas, 'TÍTULO', voDemanda::$nmAtrTexto);
	}
	$colunas = incluirColunaColecao($colunas, constantes::$CD_COLUNA_CONTRATO, null);

	if($colunasAAcrescentar !=null){
		$colunas = array_merge($colunas, $colunasAAcrescentar);
	}

	return getCorpoMensagemPorColecao($assunto, $colecao, $colunas);
}

function getCorpoMensagemPorColecao($titulo, $colecao, $colunasAExibir, $isPrioritario=false) {
	if($colunasAExibir == null){
		throw new excecaoGenerica("Indique pelo menos uma coluna dos dados consultados para exibir.");
	}

	$dominioTipoContrato = new dominioTipoContrato ();
	$mensagem = "Nada a exibir";
	try {

		$mensagem = "PARABÉNS! Não há pendências.<br>";

		if (! isColecaoVazia ( $colecao )) {
			$mensagem = "HÁ PENDÊNCIAS.<BR>";				
			$colspan = count($colunasAExibir)+1;				
			// enviar o email com os registros a serem analisados
			$mensagem .= "\n<TABLE width='100%' id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
				
			$mensagem .= "<TR>\n";
			
			$mensagem .= "<TD class='tabeladadosdestacadonegrito' width='1%'>X</TD>\n";
				
			foreach ($colunasAExibir as $coluna){
				$mensagem .= "<TD class='tabeladadosdestacadonegrito'>". $coluna[constantes::$CD_COLUNA_CHAVE] ."</TD>\n";
			}

			$mensagem .= "</TR>\n";
				
			$contador = 0;
			foreach ( $colecao as $registro ) {

				$mensagem .= "<TR>\n";
				
				$voDemandaChave = new voDemanda();
				$voDemandaChave->getDadosBanco ( $registro );				
				$mensagem .= "<TD class='tabeladadosdestacadonegrito'>".getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voDemandaChave, false)."</TD>\n";

				foreach ($colunasAExibir as $coluna){
					$coluna_valor = $registro[$coluna[constantes::$CD_COLUNA_VALOR]];

					if(constantes::$CD_TP_DADO_DATA == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$coluna_valor = getData($coluna_valor);
					}else if(constantes::$CD_TP_DADO_DOMINIO == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$nmClasseDominio = $coluna[constantes::$CD_COLUNA_NM_CLASSE_DOMINIO];
						$dominio = new $nmClasseDominio();
						$coluna_valor = $dominio->getDescricaoStatic($coluna_valor);
					}
					
					$colunaTipoDado = $coluna[constantes::$CD_COLUNA_TP_DADO];
					if(constantes::$TAMANHO_CODIGOS == $colunaTipoDado || constantes::$TAMANHO_CODIGOS_SAFI == $colunaTipoDado){
						$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
					}

					$colunaVlReferencia = $coluna[constantes::$CD_COLUNA_VL_REFERENCIA];
					$colunaTpValidacao = $coluna[constantes::$CD_COLUNA_TP_VALIDACAO];
					
					if($isPrioritario)
						$classColuna = "tabeladadosdestacadovermelho";
					else					
						$classColuna = "tabeladados";
					
					if($colunaVlReferencia != null){						
						if($colunaTpValidacao == constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE){
							if($coluna_valor > $colunaVlReferencia){
								$classColuna = "tabeladadosdestacadovermelho";
							}
							//$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
						}						
					}
						
					//para o caso de ter dados do contrato
					if(constantes::$CD_COLUNA_CONTRATO == $coluna[constantes::$CD_COLUNA_CHAVE]){

						$voDemandaContrato = new voDemandaContrato ();
						$voDemandaContrato->getDadosBanco ( $registro );

						$contrato = "";
						$empresa = $registro [vopessoa::$nmAtrNome];
						if ($qtContratos > 1) {
							$contrato = "VÁRIOS";
						} else if($voDemandaContrato->voContrato->cdContrato != null || $empresa != null){
							
							$conectorContrato = "";
							if($voDemandaContrato->voContrato->cdContrato != null){
								$contrato = formatarCodigoAnoComplemento ( $voDemandaContrato->voContrato->cdContrato, $voDemandaContrato->voContrato->anoContrato, $dominioTipoContrato->getDescricao ( $voDemandaContrato->voContrato->tipo ) );
								$conectorContrato = ": ";
							}

							if ($empresa != null) {
								$contrato .= $conectorContrato . $empresa;
							}
						}
						$coluna_valor = $contrato;

					}
						
					$mensagem .= "<TD class='$classColuna'>". $coluna_valor ."</TD>\n";
				}
					

				$mensagem .= "</TR>\n";

				$contador++;
			}
				
			$mensagem .= "<TR>\n
			<TD class='totalizadortabeladadosalinhadodireita' colspan=$colspan> Total registros: $contador </TD>\n
			</TR>\n";				
				
			$mensagem .= "</TBODY>\n
			</TABLE>";			
						
		}
	
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
		echo $msg;
	}
	
	if (! isColecaoVazia ( $colecao ) || voMensageria::$IMPRIMIR_MENSAGEM_SE_CONSULTA_VAZIA) {
		$mensagem = "<b>$titulo</b><br>$mensagem";
		$mensagem .= "<br>";
	}else{
		$mensagem = "";
	}
	
	return $mensagem;
}

function getEnderecoImagens(){
	return "C:/xampp/htdocs/wordpress/UNCT/eContratosDESENV_eclipse/imagens/";
}

function getPadraoHTMLMensagem($corpoMensagem, &$mail){
	include_once("config_lib.php");
	include_once(caminho_util."bibliotecaHTML.php");
	include_once(caminho_util."constantes.class.php");
	
	$enderecoImagem = getEnderecoImagens();	
	//$mail = new email_sefaz ($assuntoParam);	
	$mail->addImagem("$enderecoImagem/marca_sefaz.png", email_sefaz::$CD_IMAGEM_SEFAZLOGO);
	
	//inicia os parametros
	//inicio();
	$titulo = "EMAIL INFORMATIVO";
	$cabecalho = setCabecalhoEmail($titulo, $mail);
	$html =
	"<!DOCTYPE html>
	<HTML>
	<HEAD>
	". 
	getStyleEmail()
	.
	"			
	<SCRIPT language='JavaScript' type='text/javascript' src='<?=caminho_js?>tooltip.js'></SCRIPT>\n
	<SCRIPT language='JavaScript' type='text/javascript' src='<?=caminho_js?>biblioteca_funcoes_principal.js'></SCRIPT>\n
	<SCRIPT language='JavaScript' type='text/javascript' src='<?=caminho_js?>biblioteca_funcoes_radiobutton.js'></SCRIPT>\n
	</HEAD>"
	. setTituloPagina($titulo) 
	//.
	//"\n<LINK href='http://sf300451/wordpress/UNCT/eContratosDesenv_eclipse/lib/css/sefaz_pe.css' rel='stylesheet' type='text/css'>\n"
	.
	"<BODY CLASS='paginadados'>
		<FORM name='frm_principal' method='post' action='index.php?consultar=S'>
			<INPUT type='hidden' id='id_contexto_sessao' name='id_contexto_sessao' value=''>
			<INPUT type='hidden' id='evento' name='evento' value=''>
				<TABLE id='table_conteiner' class='conteiner' cellpadding='0' cellspacing='0'>
	    			<TBODY>
	        			" . $cabecalho . "
	        			<TR>
	            			<TD class='conteinerconteudodados'>
	            			 <DIV id='div_conteudodados' class='conteudodados'>
								<TABLE id='table_conteudodados' class='conteudodados' cellpadding='0' cellspacing='0'>"
	        					
	        					. $corpoMensagem
	        					
	        					. "
	            				</TABLE>
	            			</TD>
	        			</TR>
	    			</TBODY>
				</TABLE>
			</FORM>
	</BODY>
	</HTML>";

	return $html;

}

/**
 * 
 * @param unknown $assuntoParam
 * @param unknown $mensagemParam
 * @param string $enviarEmail
 * @param unknown $listaEmail
 * @param unknown $remetente
 */
function enviarEmail($assuntoParam, $mensagemParam, $enviarEmail=true, $listaEmail=null,$remetente = null) {
	
	$log = "";
	
	if($listaEmail == null){
		$listaEmail = email_sefaz::getListaEmailJuridico ();
	}
	
	if($remetente == null){
		$remetente = email_sefaz::$REMETENTE_ATJA;
	}
	
	try {
		if ($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL) {
			$mail = new email_sefaz ($assuntoParam, $remetente);			 
			$mail->criarEmail($listaEmail);
			
			$mensagemParam = getPadraoHTMLMensagem($mensagemParam, $mail);
			
			$enviado = $mail->enviarMensagem ($mensagemParam, $assuntoParam );
			if ($enviado) {
				//echoo ("Email enviado com sucesso");				 
				$log .= getLogComFlagImpressao("<br>Email enviado com sucesso");
			} else {
				/*echo "Não foi possível enviar o e-mail.<br /><br />";
				echo "<b>Informações do erro:</b> <br>" . $mail->mail->ErrorInfo;*/
				
				$log .= getLogComFlagImpressao("<br>Não foi possível enviar o e-mail.<br>");
				$log .= getLogComFlagImpressao("<b>Informações do erro:</b> <br />" . $mail->mail->ErrorInfo);
			}
		} else {
			//echo "RELATÓRIO DIÁRIO: NÃO enviar email.<BR>*******<BR>";
			
			$log .= getLogComFlagImpressao("<br>RELATÓRIO DIÁRIO: NÃO enviar email.<BR>*******<BR>");
		}
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
		//echo $msg;
		$log .= getLogComFlagImpressao($msg);
	}
	
	return $log;
}

function getStyleEmail(){
	$html = "	
<style type='text/css'>
TABLE.conteiner {
		height: 98%;
		width: 100%;
		text-align: left;
		border-width: 0;
		vertical-align: top;
	}

TABLE.conteiner table {
		border-collapse: collapse
}
		
TABLE.conteudodados {
	width: 100%;
	background-color: #ffffff;
	text-align: left;
	vertical-align: top;
}
			
TD.conteinerconteudodados {
	height: 100%;
	vertical-align: top;
}
		
/*  Representa o header de uma tabela de visualização de dados */
TD.tabeladados,TD.tabeladadosalinhadodireita,TD.tabeladadosalinhadocentro,TD.tabeladadosdestacado,TD.tabeladadosdestacadoamarelo, TD.tabeladadosdestacadoverde,TD.tabeladadosdestacadovermelho,TD.tabeladadosdestacadoazulclaro,TD.tabeladadosdestacadonegrito
	{
	color: #222;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 13px;
	/*font-weight: bolder;*/
	border-right: 1px solid #D3D3D5;
	border-bottom: 1px solid #D3D3D5;
	padding: 4px;
	vertical-align: top;
}
		
TD.tabeladados,TD.tabeladadosalinhadodireita,TD.tabeladadosalinhadocentro,TD.tabeladadosdestacado,TD.tabeladadosdestacadoverde,TD.tabeladadosdestacadovermelho,TD.tabeladadosdestacadoazulclaro,TD.tabeladadosdestacadonegrito{
		color: #000;
		border: 1px solid #aaa;
}
		
TD.tabeladadosdestacadovermelho {
	text-align: left;
	background-color: red;
	color: white;
}
		
TD.tabeladadosdestacadonegrito {
	text-align: center;
	font-weight: bolder;
}

/* Totalizador para campos calculados na tabela de resultado da interface de consulta*/
TD.totalizadortabeladadosalinhadodireita {
	background-color: #cccccc;
	color: #000000;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 13px;
	font-weight: bolder;
	text-align: right;
	border-width: 1px;
	border-color: #ffffff;
	border-style: solid;
	padding-left: 3px;
}

TABLE.headertabeladados {
	width: 100%;
	height: 32px;
	background-color: #ffffff;
	text-align: left;
	background-color: black;
	vertical-align: top;
}


TD.conteinerheadertabeladados {
	height: 32px;
	vertical-align: top;
}

/*  Representa o header de uma tabela de visualização de dados
 */
TH.headertabeladados,TH.headertabeladadosalinhadodireita,TH.headertabeladadosalinhadocentro
	{
	background: #006CA9;
	font-weight: bold;
	color: #fff;
	text-shadow: 0 1px 1px #194b7e;
	background-image: -webkit-gradient(linear, left top, left bottom, from(#00599C),
		to(#006CA9) );
	background-image: -webkit-linear-gradient(#00599C, #006CA9);
	background-image: -moz-linear-gradient(#00599C, #006CA9);
	background-image: -ms-linear-gradient(#00599C, #006CA9);
	background-image: -o-linear-gradient(#00599C, #006CA9);
	background-image: linear-gradient(#00599C, #006CA9);
	/*filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00599C', endColorstr='#006CA9',GradientType=0 ); /* IE6-9 */
	font-family: Helvetica, Arial, sans-serif;
	font-size: 14px;
	text-align: left;
	padding: 0.2em 4px;
	border: 1px solid #FFF;
	font-weight: bold;
	border-collapse: collapse;
}

/*  Representa o header de uma tabela de visualização de dados
 */
TH.headertabeladados {
	text-align: left;
}

/*  Representa o header de uma tabela de visualização de dados
 */
TH.headertabeladadosalinhadodireita {
	text-align: right;
}

/*  Representa o header de uma tabela de visualização de dados
 */
TH.headertabeladadosalinhadocentro {
	text-align: center;
}
TH.headertabeladados,TH.headertabeladadosalinhadodireita,TH.headertabeladadosalinhadocentro {
		text-shadow: none;
		color: #000;
		background: #e5e8f0;
		border: solid 1px #777;
}
	</style>
";

	return $html;
}

function getBotaoDetalharAlertas($vo=null){
	if($vo == null){
		$vo=new voDemanda();
	}
	
	$retorno .= getTagHTMLAbreJavaScript();
	$retorno .= getFuncaoJSDetalharEmailPorVO($vo);
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

function imprimeTituloalerta($enviarEmail, $setor=null){
	$titulo = "<br>RELATÓRIO DIÁRIO $setor<BR>";
	echo getTextoHTMLDestacado($titulo, "blue", true);
	if (!($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL)) {
		echoo("<font color='red'><b><u>SEM email</u>.</b></font></br>");
	}	
}

function enviarEmailATJA($enviarEmail){
	$setor = "ATJA";
	imprimeTituloalerta($enviarEmail, $setor);

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
	
	echo $mensagem . getBotaoDetalharAlertas();

	$assunto = "Relatório diário";
	enviarEmail($assunto, $mensagem, $enviarEmail);
}

function enviarEmailUNCT($enviarEmail){
	$setor = "UNCT";
	imprimeTituloalerta($enviarEmail, $setor);
	
	$count = 0;	
	//envia demandas iniciais
	$mensagem .= getMensagemDemandaIniciais($count);
	//sistemas licon portal da transparencia
	$mensagem .= getMensagemSistemasExternos($count);
	//demandas que seguem para a SAD
	//$mensagem .= getMensagemDemandaSAD($count);

	echo $mensagem . getBotaoDetalharAlertas();
	
	$assunto = "Relatório diário";
	enviarEmail($assunto, $mensagem, $enviarEmail, email_sefaz::getListaEmailUNCT());	
	//enviarEmail($assuntoParam, $mensagemParam, $enviarEmail=true, $listaEmail=null,$remetente = null) {
}

function enviarEmailDiretoria($enviarEmail){
	$setor = "DILC";
	imprimeTituloalerta($enviarEmail, $setor);

	$count = 0;
	//envia contratos a vencer TODOS
	$mensagem .= getMensagemContratosAVencer($count);
	//envia contratos a vencer SEM DEMANDA
	$mensagem .= getMensagemContratosAVencerGestor($count);
	//envia contratos a vencer SEM DEMANDA QUE NAO ADMITEM PRORROGACAO
	$mensagem .= getMensagemContratosAVencerImprorrogaveisGestor($count);
	
	echo $mensagem . getBotaoDetalharAlertas(new voContratoInfo());

	$assunto = "Relatório diário";
	enviarEmail($assunto, $mensagem, $enviarEmail, email_sefaz::getListaEmailContratosAVencer());
	//enviarEmail($assuntoParam, $mensagemParam, $enviarEmail=true, $listaEmail=null,$remetente = null) {
}

function getFiltroContratosAVencerImprorrog($inTemDemandaEmTratamento = null){
	$filtro = getFiltroContratosAVencer($inTemDemandaEmTratamento);
	
	$filtro->qtdDiasParaVencimento = voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER_IMPRORROGAVEIS;
	$filtro->inProrrogacao = dominioProrrogacaoFiltroConsolidacao::$CD_NAOPRORROGAVEL;
	
	return $filtro;	
}

function getFiltroContratosAVencer($inTemDemandaEmTratamento = null){
	$filtro = new filtroConsultarContratoConsolidacao ( false );
	$vo = new voContratoInfo();
	$dbprocesso = new dbContratoInfo();

	$filtro->voPrincipal = $vo;
	$filtro->isValidarConsulta = false;
	$filtro->setaFiltroConsultaSemLimiteRegistro ();

	$filtro->tpVigencia = dominioTpVigencia::$CD_OPCAO_VIGENTES;
	$filtro->inProduzindoEfeitos = constantes::$CD_SIM;
	//traz somente os contratos a vencer nos dias abaixo
	$filtro->qtdDiasParaVencimento = voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER;
	//traz somente os contratos indicados como "serao prorrogados"
	$filtro->inSeraProrrogado = constantes::$CD_SIM;
	//$filtro->inSeraProrrogado = array(constantes::$CD_OPCAO_CONSULTA_DIFERENTE, constantes::$CD_NAO);
	if($inTemDemandaEmTratamento != null){
		//o nome dos atributos abaixo estao definidos no filtro da consulta getColecaoCaracteristicas
		$nmFiltroInTemDemanda = getIdComponenteHtmlCheckSimNao(voDemanda::$nmAtrAno, $inTemDemandaEmTratamento);
		$inCaracteristica = array($nmFiltroInTemDemanda);
		$filtro->inCaracteristicas = $inCaracteristica;
	}

	$nmTabelaContratoInfo = voContratoInfo::getNmTabela();
	$filtro->cdAtrOrdenacao = "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrAnoContrato
	. "," . "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrTipoContrato
	. "," . "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrCdContrato;

	return $filtro;
}

function existeAlertaAtivoContrato($vocontratoinfo){
	//$vocontratoinfo = new voContratoInfo();	
	$db = new dbMensageria();
	$filtro = new filtroManterMensageria();
	$filtro->anoContrato = $vocontratoinfo->anoContrato;
	$filtro->cdContrato = $vocontratoinfo->cdContrato;
	$filtro->tipoContrato = $vocontratoinfo->tipo;
	$filtro->inHabilitado = constantes::$CD_SIM;
	$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
	
	$colecao = $db->consultarTelaConsulta(new voMensageria(), $filtro);
	
	return !isColecaoVazia($colecao);
}

/**
 * cria os alertas (se nao existirem) a partir de uma colecao de contratos
 * para os contratos IMPRORROGAVEIS
 */
function criarAlertasEmailGestorColecaoContratosImprorrog(){
	$filtro = getFiltroContratosAVencerImprorrog(constantes::$CD_NAO);
	$log = "<br>CONTRATOS IMPRORROGÁVEIS.";
	return criarAlertasEmailGestorColecaoContratos($filtro, $log, true);
}

/**
 * cria os alertas (se nao existirem) a partir de uma colecao de contratos
 */
function criarAlertasEmailGestorColecaoContratos($filtro=null, $log=null, $isContratosImprorrogaveis = false){

	if($filtro == null){
		$filtro = getFiltroContratosAVencer(constantes::$CD_NAO);
	}
	$log .= "<br>Início de verificação dos contratos a vencer que gerarão alertas.";

	$dbprocesso = new dbContratoInfo();
	$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro, true);
	
	if(!isColecaoVazia($colecao)){
		//$colecao = array($colecao[0]);
		$tam = sizeof($colecao);
		$log .= "<br>Proceder à inclusão de <b>$tam alertas</b>.";
		foreach ($colecao as $registrobanco){
			$voAlerta = new voMensageria();
			$vocontratoinfo = new voContratoInfo();
			$vocontratoinfo->getDadosBanco($registrobanco);
			
			$voAlerta->vocontratoinfo = $vocontratoinfo;
			$voAlerta->dtInicio = getDataHoje();
			$voAlerta->dtFim = getData($registrobanco[filtroConsultarContratoConsolidacao::$NmColDtFimVigencia]);
			$voAlerta->tipo = dominioTipoMensageria::$CD_CONTRATO_PRORROGAVEL;
			if($isContratosImprorrogaveis){
				$voAlerta->tipo = dominioTipoMensageria::$CD_CONTRATO_IMPRORROGAVEL;
			}
			//echoo ($voAlerta->dtFim);
			
			$voAlerta->inHabilitado = constantes::$CD_SIM;
			$voAlerta->numDiasFrequencia = voMensageria::$NUM_DIAS_FREQUENCIA_MAIL_PADRAO;
			$voAlerta->obs = "Alerta incluído automaticamente";
			$voAlerta->cdUsuarioInclusao = $voAlerta->cdUsuarioUltAlteracao = constantes::$CD_USUARIO_BATCH;
			
			$db = new dbMensageria();
			$msgIdContrato = $vocontratoinfo->toString();
			try{
				if(!existeAlertaAtivoContrato($vocontratoinfo)){
					$db->incluir($voAlerta);
					$log .= "<br>Alerta incluído com sucesso:.";
				}else{
					$log .= "<br>Já existe alerta vigente ao contrato:.";
				}			
				
				$log .= " $msgIdContrato.";
				
			}catch(excecaoGenerica $ex){
				$log .= "<br>Erro ao incluir alerta ao contrato:" 
						. "$msgIdContrato |" . $ex->getMessage();
			}
			
		}
	}else{
		$log .= "<br>Não há alertas a serem criados.";
	}	
	
	return $log;
}

//verifica se eh pra imprimir o log
function getLogComFlagImpressao($log, $imprimir=false){
	if($imprimir){
		echoo($log);
	}
	
	return $log;	
}

?>