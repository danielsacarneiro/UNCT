<?php
// include_once ("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");

function exibeAlertaDemandasPorFiltroDemanda($filtro, $enviarEmail, $assunto, $colunasAAcrescentar = null) {	
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	exibeAlertaDemandasPorColecao($colecao, $enviarEmail, $assunto, $colunasAAcrescentar);
}

function exibeAlertaDemandasPorColecao($colecao, $enviarEmail, $assuntoParam, $colunasAAcrescentar = null) {	
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
			if($colunasAAcrescentar != null){
				$colspan = $colspan + count($colunasAAcrescentar);
			}
			// enviar o email com os registros a serem analisados
			$mensagem .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
			
			$mensagem .= "<TR>\n
			<TD class='tabeladadosdestacadonegrito'> ANO </TD>\n
			<TD class='tabeladadosdestacadonegrito'> NÚMERO </TD>\n
			<TD class='tabeladadosdestacadonegrito' > CONTRATO </TD>\n
			<TD class='tabeladadosdestacadonegrito'> TÍTULO </TD>\n";
			if($colunasAAcrescentar != null){				
				foreach ($colunasAAcrescentar as $coluna){
					$mensagem .= "<TD class='tabeladadosdestacadonegrito'>". $coluna[constantes::$CD_COLUNA_CHAVE] ."</TD>\n";
				}				
			}
			
			$mensagem .= "</TR>\n";				
			
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
			<TD class='tabeladados'> $voAtual->texto </TD>\n";
			
			if($colunasAAcrescentar != null){				
				foreach ($colunasAAcrescentar as $coluna){
					$coluna_valor = $registro[$coluna[constantes::$CD_COLUNA_VALOR]];
					if(constantes::$CD_TP_DADO_DATA == $coluna[constantes::$CD_COLUNA_TP_DADO]){
						$coluna_valor = getData($coluna_valor);
					}
					$mensagem .= "<TD class='tabeladados'>". $coluna_valor ."</TD>\n";
				}			
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