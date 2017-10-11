<?php
// include_once ("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");

function exibeAlertaDemandasPorFiltroDemanda($filtro, $enviarEmail, $assunto) {	
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	exibeAlertaDemandasPorColecao($colecao, $enviarEmail, $assunto);
}

function exibeAlertaDemandasPorColecao($colecao, $enviarEmail, $assuntoParam) {	
	$assunto = $assuntoParam . " NÃO HÁ DEMANDAS PARA ANALISAR";	
	$mensagem = "Nada a exibir";
	try {
		
		$mensagem = "PARABÉNS! Não há demandas para analisar.";		
		$dominioTipoContrato = new dominioTipoContrato ();
		
		if (! isColecaoVazia ( $colecao )) {
			$assunto = "";
			$assunto = $assuntoParam . " DEMANDAS PENDENTES";
			$mensagem = "DEMANDAS A ANALISAR: \n\n";
			
			$colspan= 4;
			// enviar o email com os registros a serem analisados
			$mensagem .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
			
			$contador = 0;
			foreach ( $colecao as $registro ) {
				$voAtual = new voDemanda ();
				$voAtual->getDadosBanco ( $registro );
				
				$voDemandaContrato = new voDemandaContrato ();
				$voDemandaContrato->getDadosBanco ( $registro );
				
				$contrato = "";
				$empresa = $registro [vopessoa::$nmAtrNome];
				if ($qtContratos > 1) {
					$contrato = "VÁRIOS";
				} else {
					$contrato = formatarCodigoAnoComplemento ( $voDemandaContrato->voContrato->cdContrato, $voDemandaContrato->voContrato->anoContrato, $dominioTipoContrato->getDescricao ( $voDemandaContrato->voContrato->tipo ) );
					
					if ($empresa != null) {
						$contrato .= ": " . $empresa;
					}
				}
				
				$mensagem .= "<TR>\n
			<TD class='tabeladadosalinhadodireita'> $voAtual->ano </TD>\n
			<TD class='tabeladadosdestacadonegrito'> " . complementarCharAEsquerda ( $voAtual->cd, '0', TAMANHO_CODIGOS ) . "</TD>\n
			<TD class='tabeladados' > $contrato </TD>\n
			<TD class='tabeladados'> $voAtual->texto </TD>\n
			</TR>\n";
				
				$contador++;
			}
			
			$mensagem .= "<TR>\n
			<TD class='totalizadortabeladadosalinhadodireita' colspan=$colspan> Total registros: $contador </TD>\n
			</TR>\n";
			
			
			$mensagem .= "</TBODY>\n
			</TABLE>";
		}
		
		// Exibe uma mensagem de resultado
		echo $assunto . "<br>";
		echo $mensagem . "<br>";
		if ($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL) {
			$mail = new email_sefaz ();
			$enviado = $mail->enviarMensagem ( email_sefaz::getListaEmailJuridico (), $mensagem, $assunto );
			if ($enviado) {
				echo "Alerta realizado com sucesso";
			} else {
				echo "Não foi possível enviar o e-mail.<br /><br />";
				echo "<b>Informações do erro:</b> <br />" . $mail->mail->ErrorInfo;
			}
		} else {
			echo "Seleção: NÃO enviar email.";
		}
	} catch ( Exception $ex ) {
		$msg = $ex->getMessage ();
		echo $msg;
	}
	
	echo "<br><br>";
}
?>