<?php
//include_once ("../../config_lib.php");
require_once (caminho_funcoes . vocontrato::getNmTabela () . "/dominioAutorizacao.php");
require_once (caminho_lib . "phpmailer/config_email.php");

$assunto = "SAD: NÃO HÁ DEMANDAS PARA ANALISAR";
$mensagem = "Nada a exibir";
$voDemanda = new voDemanda ();
try {
	$filtro = new filtroManterDemanda ( false );
	$dbprocesso = $voDemanda->dbprocesso;
	
	$filtro->isValidarConsulta = false;
	// $filtro->voPrincipal = $voDemanda;
	$filtro->setaFiltroConsultaSemLimiteRegistro ();
	$filtro->vodemanda->situacao = array (
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA,
			dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO 
	);
	$filtro->vodemanda->tipo = array_keys ( dominioTipoDemanda::getColecaoTipoDemandaSAD () );
	$filtro->vodemanda->cdSetorDestino = dominioSetor::$CD_SETOR_ATJA;
	$filtro->vocontrato->cdAutorizacao = array (
			dominioAutorizacao::$CD_AUTORIZ_SAD 
	);
	$colecao = $dbprocesso->consultarTelaConsulta ( $voDemanda, $filtro );
	
	$enviarEmail = true;
	
	$mensagem = "PARABÉNS! Não há demandas a serem analisadas.";
	
	$dominioTipoContrato = new dominioTipoContrato();
	
	if (! isColecaoVazia ( $colecao )) {
		$assunto = "SAD: DEMANDAS PENDENTES";
		$mensagem = "DEMANDAS A ANALISAR: \n\n";
		// enviar o email com os registros a serem analisados
		$mensagem .=				
		"<TABLE id='table_tabeladados' class='tabeladados' cellpadding='2' cellspacing='2' BORDER=1>\n
		<TBODY>";
		
		foreach ( $colecao as $registro ) {
			$voAtual = new voDemanda ();
			$voAtual->getDadosBanco ( $registro );
			
			$voDemandaContrato = new voDemandaContrato();
			$voDemandaContrato->getDadosBanco($registro);
			
			$contrato = "";
			$empresa = $registro[vopessoa::$nmAtrNome];
			if($qtContratos > 1){
				$contrato = "VÁRIOS";
			}else{
				$contrato = formatarCodigoAnoComplemento($voDemandaContrato->voContrato->cdContrato,
						$voDemandaContrato->voContrato->anoContrato,
						$dominioTipoContrato->getDescricao($voDemandaContrato->voContrato->tipo));
					
				if($empresa != null){
					$contrato .= ": ".$empresa;
				}
			}				
					
			$mensagem .= 
					"<TR>\n
						<TD class='tabeladadosalinhadodireita'> $voAtual->ano </TD>\n
						<TD class='tabeladadosdestacadonegrito'> " . complementarCharAEsquerda($voAtual->cd, '0', TAMANHO_CODIGOS) . "</TD>\n
						<TD class='tabeladados' > $contrato </TD>\n						
						<TD class='tabeladados'> $voAtual->texto </TD>\n
					</TR>\n";
				
		}
			$mensagem .=
			"</TBODY>\n
			</TABLE>";
			
			//echo $mensagem;
		
	} 
	
	// Exibe uma mensagem de resultado
	echo $assunto . "<br>";
	if ($enviarEmail && email_sefaz::$FLAG_ENVIAR_EMAIL) {
		$mail = new email_sefaz();
		$enviado = $mail->enviarMensagem(email_sefaz::getListaEmailJuridico(), $mensagem, $assunto);
		if ($enviado) {
			echo "Alerta realizado com sucesso";
		} else {
			echo "Não foi possível enviar o e-mail.<br /><br />";
			echo "<b>Informações do erro:</b> <br />" . $mail->mail->ErrorInfo;
		}
	}else {
		echo "Não há alerta para exibir.";
	}
} catch ( Exception $ex ) {
	$msg = $ex->getMessage ();
	echo $msg;
}

echo "<br><br>";
?>