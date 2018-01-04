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

function exibeAlertaDemandasPorFiltroDemanda($filtro, $enviarEmail, $assunto, $colunasAAcrescentar = null) {	
	$voDemanda = new voDemanda ();
	$dbprocesso = $voDemanda->dbprocesso;
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	exibeAlertaDemandasPorColecao($colecao, $enviarEmail, $assunto, $colunasAAcrescentar);
}

function exibeAlertaContrato($colecao, $enviarEmail, $assuntoParam, $colunasAAcrescentar) {

	$colunas = incluirColunaColecao($colunas, constantes::$CD_COLUNA_CONTRATO, null);
	$colunas = array_merge($colunas, $colunasAAcrescentar);

	return exibeAlertaPorColecao($colecao, $enviarEmail, $assuntoParam, $colunas);
}

function exibeAlertaDemandasContrato($colecao, $enviarEmail, $assuntoParam, $colunasAAcrescentar) {
		
	$colunas = incluirColunaColecao($colunas, 'ANO DEMANDA', voDemanda::$nmAtrAno);
	$colunas = incluirColunaColecao($colunas, 'NÚMERO', voDemanda::$nmAtrCd, constantes::$TAMANHO_CODIGOS);	
	$colunas = incluirColunaColecao($colunas, 'TÍTULO', voDemanda::$nmAtrTexto);
	$colunas = incluirColunaColecao($colunas, constantes::$CD_COLUNA_CONTRATO, null);
	
	$colunas = array_merge($colunas, $colunasAAcrescentar);
		
	return exibeAlertaPorColecao($colecao, $enviarEmail, $assuntoParam, $colunas);
}

//recebe colecao de registros e imprime as colunas indicadas em $colunasAExibir
function exibeAlertaPorColecao($colecao, $enviarEmail, $assuntoParam, $colunasAExibir) {
	$dominioTipoContrato = new dominioTipoContrato ();
	$assunto = $assuntoParam . " NÃO HÁ PENDÊNCIAS";
	$mensagem = "Nada a exibir";
	try {
		
		$mensagem = "PARABÉNS! Não há pendências.";		
		
		if (! isColecaoVazia ( $colecao )) {
			$assunto = "";
			$assunto = $assuntoParam . " HÁ PENDÊNCIAS";
			$mensagem = "";
			
			$colspan = count($colunasAExibir);
			
			// enviar o email com os registros a serem analisados
			$mensagem .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
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
							$contrato = "VÁRIOS";
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
		
		// Exibe uma mensagem de resultado
		echo $assunto . "<br>";
		echo $mensagem . "<br>";
		if ($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL) {
			$mail = new email_sefaz ($assunto);
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