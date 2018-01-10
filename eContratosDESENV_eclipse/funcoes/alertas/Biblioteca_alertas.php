<?php
// include_once ("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");

function incluirColunaColecao($colecao, $tituloColuna, $valorColuna, $tipoDado = null){	
	$colecao[] =array( constantes::$CD_COLUNA_CHAVE => $tituloColuna,
					constantes::$CD_COLUNA_VALOR => $valorColuna,
					constantes::$CD_COLUNA_TP_DADO =>  $tipoDado);	 
	return $colecao; 
}

function getCorpoMensagemDemandaPorColecao($assunto, $filtro, $colunasAAcrescentar = null){	
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'N�MERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);
	$colunas = incluirColunaColecao($colunas, 'T�TULO', voDemanda::$nmAtrTexto);
	
	if($colunasAAcrescentar !=null){
		$colunas = array_merge($colunas, $colunasAAcrescentar);
	}
	
	return getCorpoMensagemPorColecao($assunto, $colecao, $colunas);
}

function getCorpoMensagemDemandaContratoColecao($assunto, $colecao, $colunasAAcrescentar=null) {

	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'N�MERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);
	$colunas = incluirColunaColecao($colunas, 'T�TULO', voDemanda::$nmAtrTexto);
	$colunas = incluirColunaColecao($colunas, constantes::$CD_COLUNA_CONTRATO, null);

	if($colunasAAcrescentar !=null){
		$colunas = array_merge($colunas, $colunasAAcrescentar);
	}

	return getCorpoMensagemPorColecao($assunto, $colecao, $colunas);
}

function enviarEmail($assuntoParam, $mensagemParam) {	
	try {
		if (email_sefaz::$FLAG_ENVIAR_EMAIL) {
			$mail = new email_sefaz ($assuntoParam);
			$enviado = $mail->enviarMensagem ( email_sefaz::getListaEmailJuridico (), $mensagemParam, $assuntoParam );
			if ($enviado) {
				echo "Email enviado com sucesso";
			} else {
				echo "N�o foi poss�vel enviar o e-mail.<br /><br />";
				echo "<b>Informa��es do erro:</b> <br />" . $mail->mail->ErrorInfo;
			}
		} else {
			echo "Sele��o: N�O enviar email.";
		}
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
		echo $msg;
	}
	
}

function getCorpoMensagemPorColecao($titulo, $colecao, $colunasAExibir) {
	if($colunasAExibir == null){
		throw new excecaoGenerica("Indique pelo menos uma coluna dos dados consultados para exibir.");
	}

	$dominioTipoContrato = new dominioTipoContrato ();
	$mensagem = "Nada a exibir";
	try {

		$mensagem = "PARAB�NS! N�o h� pend�ncias.";

		if (! isColecaoVazia ( $colecao )) {
			$mensagem = "H� PEND�NCIAS.<BR>";				
			$colspan = count($colunasAExibir);				
			// enviar o email com os registros a serem analisados
			$mensagem .= "\n<TABLE id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
				
			$mensagem .= "<TR>\n";
				
			foreach ($colunasAExibir as $coluna){
				$mensagem .= "<TD class='tabeladadosdestacadonegrito'>". $coluna[constantes::$CD_COLUNA_CHAVE] ."</TD>\n";
			}

			$mensagem .= "</TR>\n";
				
			$contador = 0;
			foreach ( $colecao as $registro ) {

				$mensagem .= "<TR>\n";

				foreach ($colunasAExibir as $coluna){
					$coluna_valor = $registro[$coluna[constantes::$CD_COLUNA_VALOR]];
					if(constantes::$CD_TP_DADO_DATA == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$coluna_valor = getData($coluna_valor);
					}
						
					if(constantes::$TAMANHO_CODIGOS == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$coluna_valor = complementarCharAEsquerda ( $coluna_valor, '0', constantes::$TAMANHO_CODIGOS );
					}

					//para o caso de ter dados do contrato
					if(constantes::$CD_COLUNA_CONTRATO == $coluna[constantes::$CD_COLUNA_CHAVE]){

						$voDemandaContrato = new voDemandaContrato ();
						$voDemandaContrato->getDadosBanco ( $registro );

						$contrato = "";
						$empresa = $registro [vopessoa::$nmAtrNome];
						if ($qtContratos > 1) {
							$contrato = "V�RIOS";
						} else {
							$contrato = formatarCodigoAnoComplemento ( $voDemandaContrato->voContrato->cdContrato, $voDemandaContrato->voContrato->anoContrato, $dominioTipoContrato->getDescricao ( $voDemandaContrato->voContrato->tipo ) );

							if ($empresa != null) {
								$contrato .= ": " . $empresa;
							}
						}
						$coluna_valor = $contrato;

					}
						
					$mensagem .= "<TD class='tabeladados'>". $coluna_valor ."</TD>\n";
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
	
	$mensagem = $titulo . "<br>". $mensagem;
	$mensagem .= "<br>";
	
	return $mensagem;
}
?>