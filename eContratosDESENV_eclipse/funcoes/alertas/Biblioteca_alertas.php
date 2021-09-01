<?php
// include_once ("../../config_lib.php");
require_once (caminho_vos . "vocontrato.php");
//require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");
require_once (caminho_vos . "voDemandaTramitacao.php");
require_once (caminho_vos . "voMensageria.php");

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
	$pArray = array($assunto, $colecao, $colunasAAcrescentar, $semTitulo);
	return getCorpoMensagemDemandaContratoArray($pArray);
}

function getCorpoMensagemDemandaContratoArray($pArray) {
	$assunto = $pArray[0];
	$colecao = $pArray[1];
	$colunasAAcrescentar = $pArray[2];
	$semTitulo = $pArray[3];
	$nmFuncaoValidacaoDestaqueRegistro = $pArray[4];
	$isAlertaPrioritario = $pArray[5];
	$isMudarCorPorSituacaoDemanda = $pArray[6];
	
	//$colunas = incluirColunaColecao($colunas, 'SEI', voDemanda::$nmAtrProtocolo);
	
	$array =array(
			constantes::$CD_COLUNA_CHAVE => 'SEI',
			constantes::$CD_COLUNA_TP_VALIDACAO => "formatarSEI", 
			constantes::$CD_COLUNA_VALOR => voDemanda::$nmAtrProtocolo,
	);
	$colunas = incluirColunaColecaoArray($colunas, $array);
	
	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'NÚMERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);
	
	$array =array(
			constantes::$CD_COLUNA_CHAVE => 'TIPO',
			constantes::$CD_COLUNA_TP_DADO => constantes::$CD_TP_DADO_DOMINIO,
			constantes::$CD_COLUNA_NM_CLASSE_DOMINIO => "dominioTipoDemandaContrato",
			constantes::$CD_COLUNA_VALOR => voDemanda::$nmAtrTpDemandaContrato,
	);
	$colunas = incluirColunaColecaoArray($colunas, $array);
	
	if(!$semTitulo){
		$colunas = incluirColunaColecao($colunas, 'TÍTULO', voDemanda::$nmAtrTexto);
	}
	$colunas = incluirColunaColecao($colunas, constantes::$CD_COLUNA_CONTRATO, null);

	if($colunasAAcrescentar !=null){
		$colunas = array_merge($colunas, $colunasAAcrescentar);
	}

	$pArray = array($assunto, $colecao, $colunas, $isAlertaPrioritario, $nmFuncaoValidacaoDestaqueRegistro, $isMudarCorPorSituacaoDemanda);
	return getCorpoMensagemPorColecaoArray($pArray);
}

function getCorpoMensagemPorColecao($titulo, $colecao, $colunasAExibir, $isPrioritario=false) {
	$pArray = array($titulo, $colecao, $colunasAExibir, $isPrioritario);
	return getCorpoMensagemPorColecaoArray($pArray);
}

function isFormatarCelularRegistro($nmFuncaoValidacaoDestaqueRegistro, $registro){
	$validacao = false;
	//ECHO "entrou aqui!";
	if(isAtributoValido($nmFuncaoValidacaoDestaqueRegistro)){
		try{
			$validacao = $nmFuncaoValidacaoDestaqueRegistro($registro);
		}catch(Exception $ex){
			;
		}
	}
	return $validacao;	
}

function getCorpoMensagemPorColecaoArray($pArray) {
	$titulo = $pArray[0];
	$colecao = $pArray[1];
	$colunasAExibir = $pArray[2];
	$isPrioritario = $pArray[3];
	$nmFuncaoValidacaoDestaqueRegistro = $pArray[4];
	$isCorCelulaIgualSituacaoDemanda = $pArray[5];
	
	if($colunasAExibir == null){
		throw new excecaoGenerica("Indique pelo menos uma coluna dos dados consultados para exibir.");
	}

	$dominioTipoContrato = new dominioTipoContrato ();
	$mensagem = "Nada a exibir";
	try {

		$mensagem = "PARABÉNS! Não há pendências.<br>";
		$nmClassCelulaTitulo = 'tabeladadosdestacadonegrito';
		$classColunaGeral = 'tabeladados';

		if (! isColecaoVazia ( $colecao )) {
			$registro = $colecao[0];
			
			if(isFormatarCelularRegistro($nmFuncaoValidacaoDestaqueRegistro, $registro)){
				$classColunaGeral = 'tabeladadosdestacadoamarelo';
				//echo "ENTROU AQUI!";
			}
			
			if($isPrioritario){
				//echo "prioritario VERDADEIRO";
				$classColunaGeral = "tabeladadosdestacadovermelho";
			}
				
			$mensagem = "HÁ PENDÊNCIAS.<BR>";				
			$colspan = count($colunasAExibir)+1;				
			// enviar o email com os registros a serem analisados
			$mensagem .= "\n<TABLE width='100%' id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
				
			$mensagem .= "<TR>\n";
			
			$mensagem .= "<TD class='$nmClassCelulaTitulo' width='1%'>X</TD>\n";
				
			foreach ($colunasAExibir as $coluna){
				$mensagem .= "<TD class='$nmClassCelulaTitulo'>". $coluna[constantes::$CD_COLUNA_CHAVE] ."</TD>\n";
			}

			$mensagem .= "</TR>\n";
				
			$contador = 0;
			foreach ( $colecao as $registro ) {
				$voDemandaChave = new voDemanda();
				$voDemandaChave->getDadosBanco ( $registro );
				
				$voDemandaContratoinfo = new voContratoInfo();
				$voDemandaContratoinfo->getDadosBanco ( $registro );
				
				$voChave = $voDemandaChave;
				if(!isAtributoValido($voDemandaChave->cd)){
					$voChave = $voDemandaContratoinfo;
				}
				
				//zera a cor da coluna para a padrao
				$classColuna = $classColunaGeral;
				//echoo("SITUACAO" . $voDemandaChave->situacao);
				if($isCorCelulaIgualSituacaoDemanda && $voDemandaChave->situacao != null){
					$classColuna = dominioSituacaoDemanda::getCorColuna($voDemandaChave->situacao);
				}
				
				$classColunaPadrao = $classColuna;

				$mensagem .= "<TR>\n";
				
				$mensagem .= "<TD class='$classColuna'>".getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voChave, false)."</TD>\n";

				foreach ($colunasAExibir as $coluna){
					$coluna_valor = $registro[$coluna[constantes::$CD_COLUNA_VALOR]];

					if(constantes::$CD_TP_DADO_DATA == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$coluna_valor = getData($coluna_valor);
					}else if(constantes::$CD_TP_DADO_DOMINIO == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$nmClasseDominio = $coluna[constantes::$CD_COLUNA_NM_CLASSE_DOMINIO];
						$dominio = new $nmClasseDominio();
						$coluna_valor = $dominio->getDescricaoChaveArrayOuComSeparador($coluna_valor);
					}
					
					$colunaTipoDado = $coluna[constantes::$CD_COLUNA_TP_DADO];
					if(constantes::$TAMANHO_CODIGOS == $colunaTipoDado || constantes::$TAMANHO_CODIGOS_SAFI == $colunaTipoDado){
						$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
					}

					$colunaVlReferencia = $coluna[constantes::$CD_COLUNA_VL_REFERENCIA];
					$colunaTpValidacao = $coluna[constantes::$CD_COLUNA_TP_VALIDACAO];
					$colunaOperacao = $coluna[constantes::$CD_COLUNA_OPERACAO_VALOR_REFERENCIA];
					$colunaChave = $coluna[constantes::$CD_COLUNA_CHAVE];
					
					$classColuna = $classColunaPadrao;
					if($colunaVlReferencia != null){
						if(isAtributoValido($colunaOperacao)){
							if($colunaOperacao($coluna_valor,$colunaVlReferencia)){
								//echo "<br>tipo dado: $colunaTipoDado|valor: $coluna_valor e |referencia: $colunaVlReferencia";
								$classColuna = "tabeladadosdestacadovermelho";
							}								
						}
						else if($colunaTpValidacao == constantes::$CD_ALERTA_TP_VALIDACAO_MAIORQUE){
							if($coluna_valor > $colunaVlReferencia){
								//echo "<br>chave: $colunaChave|tipo dado: $colunaTipoDado|valor: $coluna_valor e |referencia: $colunaVlReferencia";
								$classColuna = "tabeladadosdestacadovermelho";
							}
							//$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
						}else if($colunaTpValidacao == constantes::$CD_ALERTA_TP_VALIDACAO_IGUAL){
							if($coluna_valor == $colunaVlReferencia){
								$classColuna = "tabeladadosdestacadovermelho";
							}
							//$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
						}else if($colunaTpValidacao == constantes::$CD_ALERTA_TP_VALIDACAO_MENORQUE){
							if($coluna_valor < $colunaVlReferencia){
								$classColuna = "tabeladadosdestacadovermelho";
							}
								//$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', $colunaTipoDado );
						}						
						
					}else if(isAtributoValido($colunaTpValidacao)){
						//executa qualquer funcao que vier dentro da validacao
						$coluna_valor = $colunaTpValidacao($coluna_valor);						
					}
											
					//para o caso de ter dados do contrato
					if(constantes::$CD_COLUNA_CONTRATO == $colunaChave){

						$voDemandaContrato = new voDemandaContrato ();
						$voDemandaContrato->getDadosBanco ( $registro );

						$contrato = "";
						$empresa = $registro [vopessoa::$nmAtrNome];
						if ($qtContratos > 1) {
							$contrato = "VÁRIOS";
						} else if($voDemandaContrato->voContrato->cdContrato != null || $empresa != null){
							
							$conectorContrato = "";
							$voContratoTemp = $voDemandaContrato->voContrato;
							if($voContratoTemp->cdContrato != null){
								$cdEspeciaAtual = $voContratoTemp->cdEspecie;
								$sqEspeciaAtual = $voContratoTemp->sqEspecie;
								$contrato = formatarCodigoAnoComplemento ( $voContratoTemp->cdContrato, $voContratoTemp->anoContrato, $dominioTipoContrato->getDescricao ( $voContratoTemp->tipo ) );
								if($sqEspeciaAtual != null){
									$contrato .= "|";
									$strtemp = $cdEspeciaAtual==dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?dominioEspeciesContrato::$DS_ESPECIE_CONTRATO_MATER:$sqEspeciaAtual ."o$cdEspeciaAtual";
									$contrato .= $strtemp;
								}
								$contrato = getTextoHTMLNegrito($contrato);
								
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
				$log .= getLogComFlagImpressao("<br>Email enviado com sucesso.");
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
	
	$voDemanda = new voDemanda();
	$vocontratoinfo = new voContratoInfo();
	
	$nmFuncaoJSDemanda = getNmFuncaoDetalharJSPorVO($voDemanda);
	$nmFuncaoJSContrato = getNmFuncaoDetalharJSPorVO($vocontratoinfo);
	
	$retorno .= getTagHTMLAbreJavaScript();
	$retorno .= getFuncaoJSDetalharEmailPorVO($voDemanda);
	$retorno .= getFuncaoJSDetalharEmailPorVO($vocontratoinfo);
	$retorno .= getTagHTMLFechaJavaScript();
	$retorno .= "\n<TABLE width='100%' id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=0>\n
				<TBODY>";
	$retorno .= "<TR>\n";	
	$retorno .= "<TD class='botaofuncao' colspan=$colspan>" 
			//. getBotaoDetalhar ()
			. getBotaoValidacaoAcesso("bttDetalharDemanda", "Det.Demanda", "botaofuncaop", false, false,true,false,"onClick='javascript:$nmFuncaoJSDemanda();' accesskey='d'")
			. "</TD>\n";
	
	$retorno .= "<TD class='botaofuncao' colspan=$colspan>"
			. getBotaoValidacaoAcesso("bttDetalharContrato", "Det.Contrato", "botaofuncaop", false, false,true,false,"onClick='javascript:$nmFuncaoJSContrato();' accesskey='c'")
			. "</TD>\n";
				
			$retorno .= "</TR>\n";

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

function enviarEmailATJA($enviarEmail, $count = 0){
	$setor = "ATJA";
	imprimeTituloalerta($enviarEmail, $setor);

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

function enviarEmailUNCT($enviarEmail, $count = 0){	
	$setor = "UNCT";
	imprimeTituloalerta($enviarEmail, $setor);	
	
	//demandas prioritarias
	$mensagemX = getMensagemAltaPrioridade($count, dominioSetor::$CD_SETOR_UNCT, true);
	$msgAproveitavel .= $mensagemX;
	$mensagem .= $mensagemX;

	//demandas a revisar
	$mensagemX = getMensagemPorSituacao($count, dominioSetor::$CD_SETOR_UNCT, dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_REVISAR);
	$mensagem .= $mensagemX;
	
	//demandas com contratos a vencer
	$mensagemX = getMensagemDemandasDeContratosAVencer($count);
	$msgAproveitavel .= $mensagemX;
	$mensagem .= $mensagemX;	
	
	//envia demandas monitoradas
	$mensagemX = getMensagemDemandasMonitoradas($count);
	$mensagem .= $mensagemX;
	
	//envia demandas iniciais
	$mensagem .= getMensagemDemandaIniciais($count);
	//sistemas licon portal da transparencia
	$mensagem .= getMensagemSistemasExternos($count);
	//$mensagem .= getMensagemContratosNaoIncluidosPlanilha($count);
	
	echo $mensagem . getBotaoDetalharAlertas();
	
	$assunto = "$setor - Relatório diário";
	enviarEmail($assunto, $mensagem, $enviarEmail, email_sefaz::getListaEmailUNCT());	
	//enviarEmail($assuntoParam, $mensagemParam, $enviarEmail=true, $listaEmail=null,$remetente = null) {
	
	return array(2, $msgAproveitavel);
}

function enviarEmailDiretoria($enviarEmail, $count = 0, $msgAproveitavel = null){
	$setor = "DILC";
	imprimeTituloalerta($enviarEmail, $setor);

	//mensagens nao respondidas pelo gestor
	$mensagem .= getMensagemEmailsGestorNaoRespondidos($count);
	//envia contratos a vencer SEM DEMANDA
	$mensagem .= getMensagemContratosAVencerGestor($count);
	//envia contratos a vencer SEM DEMANDA QUE NAO ADMITEM PRORROGACAO
	$mensagem .= getMensagemContratosAVencerImprorrogaveisGestor($count);
	
	echo $mensagem . getBotaoDetalharAlertas(new voContratoInfo());

	$assunto = "$setor - Relatório diário";
	enviarEmail($assunto, $msgAproveitavel . $mensagem, $enviarEmail, email_sefaz::getListaEmailContratosAVencer());
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
	//$filtro->dtVigencia = getDataHoje();
	
	$filtro->inProduzindoEfeitos = constantes::$CD_SIM;
	//traz somente os contratos a vencer nos dias abaixo
	$filtro->qtdDiasParaVencimento = voMensageria::$NUM_DIAS_CONTRATOS_A_VENCER;
	//traz somente os contratos indicados como "serao prorrogados"
	$filtro->inSeraProrrogado = constantes::$CD_SIM;
	//$filtro->inSeraProrrogado = array(constantes::$CD_OPCAO_CONSULTA_DIFERENTE, constantes::$CD_NAO);
	if($inTemDemandaEmTratamento != null){
		//o nome dos atributos abaixo estao definidos no filtro da consulta getColecaoCaracteristicas
		/*$nmFiltroInTemDemanda = getIdComponenteHtmlCheckSimNao(voDemanda::$nmAtrAno, $inTemDemandaEmTratamento);
		$inCaracteristica = array($nmFiltroInTemDemanda);
		$filtro->inCaracteristicas = $inCaracteristica;*/
		$filtro->inTemDemandaProrrogacao = $inTemDemandaEmTratamento;
	}

	$nmTabelaContratoInfo = voContratoInfo::getNmTabela();
	$filtro->cdAtrOrdenacao = 
	filtroConsultarContratoConsolidacao::$NmColQtdDiasParaVencimento
	. ",$nmTabelaContratoInfo." . voContratoInfo::$nmAtrAnoContrato
	. "," . "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrTipoContrato
	. "," . "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrCdContrato;

	return $filtro;
}

/**
 * verifica se ja existe alerta vigente, estando ele habilitado ou nao
 * somente inclui um novo alerta NAO havendo alerta vigente.
 * se estiver vigente, porem desabilitado, significa que o email NAO deve ser enviado, pelo motivo que for, embora esteja vigente
 * dai que nao deve ser incluido novo alerta
 * @param unknown $vocontratoinfo
 * @return boolean
 */
function existeAlertaVigenteGestorContrato($vocontratoinfo){
	//$vocontratoinfo = new voContratoInfo();	
	$db = new dbMensageria();
	$filtro = new filtroManterMensageria(false);
	$filtro->anoContrato = $vocontratoinfo->anoContrato;
	$filtro->cdContrato = $vocontratoinfo->cdContrato;
	$filtro->tipoContrato = $vocontratoinfo->tipo;
	//$filtro->inHabilitado = constantes::$CD_SIM;
	$filtro->inVerificarPeriodoVigente = constantes::$CD_SIM;
	$filtro->cdHistorico = 'N';
	$filtro->tipo = dominioTipoMensageria::getColecaoTipoAlertaGestor();
	
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
	$log .= "<br>Início de verificação dos contratos a vencer que gerarão alertas - (". $filtro->qtdDiasParaVencimento . ") dias para o vencimento.";

	$dbprocesso = new dbContratoInfo();
	$colecao = $dbprocesso->consultarTelaConsultaConsolidacao ($filtro);
	
	if(!isColecaoVazia($colecao)){
		//$colecao = array($colecao[0]);
		$tam = sizeof($colecao);
		$log .= "<br>Proceder à inclusão de <b>$tam alertas</b>.";
		$countAlertasIncluidos = 0;
		$countAlertasExistentes = 0;
		$countAlertasErro = 0;
		
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
			$voAlerta->obs = "Alerta incluído automaticamente.";
			$voAlerta->cdUsuarioInclusao = $voAlerta->cdUsuarioUltAlteracao = constantes::$CD_USUARIO_BATCH;
			
			$db = new dbMensageria();
			$msgIdContrato = $vocontratoinfo->toString();
			try{
				if(!existeAlertaVigenteGestorContrato($vocontratoinfo)){
					//so inclui se nao houver alerta vigente
					$db->incluir($voAlerta);
					$log .= "<br>Alerta incluído com sucesso:";
					$countAlertasIncluidos++;
				}else{
					//havendo alerta vigente, deve seguir a indicacao do alerta ja existente
					$log .= "<br>Já existe alerta vigente ao contrato:";
					$countAlertasExistentes++;
				}			
				
				$log .= " $msgIdContrato.";
				
			}catch(excecaoGenerica $ex){
				$log .= getTextoHTMLDestacado("<br>Erro ao incluir alerta ao contrato:" 
						. "$msgIdContrato |" . $ex->getMessage());
				
				$countAlertasErro++;
			}
			
		}
		
		$log .= getLogComFlagImpressao("<br>Alertas Incluídos: <b>$countAlertasIncluidos</b>.");
		$log .= getLogComFlagImpressao("<br>Alertas Existentes: <b>$countAlertasExistentes</b>.");
		$msgAlertaErro = "Alertas Erro: <b>$countAlertasErro</b>.";
		if($countAlertasErro > 0){
			$msgAlertaErro = getTextoHTMLDestacado("Alertas Erro: $countAlertasErro.");
		}
		$log .= getLogComFlagImpressao("<br>$msgAlertaErro");
		
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

function isAlertaFormatarCelulaDemandaMonitorada($registro){
	return $registro[voDemandaTramitacao::$nmAtrCdSetorDestino] == dominioSetor::$CD_SETOR_UNCT;	
}

function formatarSEI($sei){
	return voDemandaTramitacao::getNumeroPRTComMascara($sei, false);
}
?>