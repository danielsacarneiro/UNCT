<?php
include_once(caminho_lib. "dbprocesso.obj.php");

  Class dbMensageriaRegistro extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = false;
  	
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaMensageria = voMensageria::getNmTabelaStatic ( false );
  		  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrAnoContrato,
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrCdContrato,
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrTipoContrato,
  		);
  		  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaMensageria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMensageria . "." . voMensageria::$nmAtrSq . "=" . $nmTabela . "." . voMensageriaRegistro::$nmAtrSqMensageria;
  	
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaMensageria = voMensageria::getNmTabelaStatic ( false );
  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrAnoContrato,
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrCdContrato,
  				$nmTabelaMensageria . "." . voMensageria::$nmAtrTipoContrato,
  		);
  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaMensageria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMensageria . "." . voMensageria::$nmAtrSq . "=" . $nmTabela . "." . voMensageriaRegistro::$nmAtrSqMensageria;  		
  		
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	} 	 
  	
  	function incluir($vo){
  		if ($vo->sq == null || $vo->sq == "") {
  			$vo->sq = $this->getProximoSequencialChaveComposta ( voMensageriaRegistro::$nmAtrSq, $vo );
  		}
  		
  		$retorno = parent::incluir($vo); 
  		return $retorno;
  	}
  	
  	function incluirComEnvioEmail($registroMensageria) {  		
  			$this->cDb->retiraAutoCommit ();  			
  			try {
 				
  				if(voMensageria::$ENVIAR_EMAIL_GESTOR_CONTRATO){
	  				$vomensageria = new voMensageria();
	  				$vomensageria->getDadosBanco($registroMensageria);
	  				
	  				$vomensagemregistro = new voMensageriaRegistro();
	  				$vomensagemregistro->sqMensageria = $vomensageria->sq;
	  				$this->incluir ( $vomensagemregistro );
	  				// End transaction
	  				$this->cDb->commit ();	  					
  				}
  				
  				$isContratoImprorrogavel = dominioTipoMensageria::$CD_CONTRATO_IMPRORROGAVEL == $registroMensageria[voMensageria::$nmAtrTipo];
  				$log = $this->enviarEmailGestor($registroMensageria, true, $isContratoImprorrogavel);
  				
  			} catch ( Exception $e ) {
  				$this->cDb->rollback ();
  				throw new Exception ( $e->getMessage () );
  			} 
  			
  			return $log;
  	} 
  	
  	function enviarEmailGestor($registro, $enviarEmail=true, $isContratoImprorrogavel=false){  	
  		$vomensageria = new voMensageria();
  		$vomensageria->getDadosBanco($registro);
  		$numFrequencia = $vomensageria->numDiasFrequencia;
  		//$numFrequencia = $registro[voMensageria::$nmAtrNumDiasFrequencia];
  		$vocontratoinfo = $vomensageria->vocontratoinfo; 		
  	
  		$assunto = "COMUNICA��O:";  	
  		$emailGestor = $registro[vopessoa::$nmAtrEmail];
  		$isEmailGestorValido = $emailGestor != null && $emailGestor != "";
  		
  		//para o caso de ser mais de um email cadastrado
  		$arrayEmailGestor = explode(";", $emailGestor);
  		
  		$listaEmailTemp = array();
  		$listaEmailTemp = email_sefaz::getListaEmailAvisoGestorContrato();  		
  		
  		if(isColecaoVazia($listaEmailTemp)){
  			throw new excecaoGenerica("N�o h� respons�veis na UNCT cadastrados para o mensageria.");
  		}  		
  		
  		$codigo = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
  		$dsPessoa = $registro[vopessoa::$nmAtrNome];
  		if($dsPessoa != null && $dsPessoa != ""){
  			$codigo = "$codigo - $dsPessoa"; 
  		}
  		
  		if(!$isContratoImprorrogavel){
  			$msg .= static::getMensagemGestor($codigo,$numFrequencia);
  		}else{
  			$msg .= static::getMensagemGestorContratoImprorrogavel($codigo,$numFrequencia);
  		}
  		
  		//$msg .= "<br>O contrato vencer� em dias.";
  		if(!$isEmailGestorValido){
  			//se o alerta nao for valido, envia apenas para os responsaveis
  			$msg = "<br><br><u><b>Contrato SEM E-MAIL V�LIDO para o Gestor. Mensageria:</b></u> $vomensageria->toString().";  			  				
  		}else{
  			//se o alerta for valido, acrescenta o e-mail do gestor
  			if(voMensageria::$ENVIAR_EMAIL_GESTOR_CONTRATO){
	  			//$array2 = array($emailGestor);
	  			$array2 = $arrayEmailGestor;	  			
	  			$listaEmailTemp = array_merge($listaEmailTemp, $array2);
  			}else{
  				$msg .= "<br><br><u><b>Encaminhamento ao gestor desativado. Entre em contato com o administrador do mensageria</b></u>.";
  			}
  		}
  		
  		$msg .= "<br><br>Atenciosamente, <br><br>UNCT-SAFI";
  	
  		$remetente = email_sefaz::$REMETENTE_PRINCIPAL;
		$log .= enviarEmail($assunto, $msg, $enviarEmail, $listaEmailTemp, $remetente);
		$log .= getLogComFlagImpressao($vomensageria->toString());
		//echoo($vomensageria->toString());
		
		return $log;
  	}
  	
  	static function getMensagemGestor($codigoContrato, $numFrequencia){  		
  		//$retorno = "<br><br>Caro Gestor, favor verificar o vencimento do contrato $codigo.";
  		
  		//$numFrequencia = complementarCharAEsquerda($numFrequencia, "0", 3);
  		$emailPrincipal = email_sefaz::$REMETENTE_PRINCIPAL;
  		$emailCopia = email_sefaz::$REMETENTE_COPIA;
  		
  		$retorno = "<br>Prezado gestor,
		<br><br><br><b>Esta � uma mensagem autom�tica</b>, gerada pelo sistema de automa��o da Unidade de Contratos (UNCT/SAFI), 
		solicitando informa��es referentes � <b>prorroga��o</b> do contrato <b>$codigoContrato</b>, que em breve se encerrar�.
  		<br>Havendo interesse da SEFAZ pela prorroga��o, requere-se provoca��o tempestiva, via SEI, � SAFI, junto com as cota��es de pre�os e a anu�ncia da Contratada.  		

  		<br><br><b>N�o sendo poss�vel nova prorroga��o, e persistindo a necessidade da contrata��o, o gestor dever� solicitar novo processo licitat�rio
  		em tempo h�bil, sob pena de encerramento da presta��o do servi�o.
  		
  		<br><br>Informamos ainda que o envio da garantia contratual atualizada, sendo este o caso, � necess�ria � instru��o da renova��o contratual.</b> 
  		
  		<br><br>A resposta deve ser enviada para o seguinte correio eletr�nico: <b><u>$emailPrincipal</u></b>, com c�pia para <u>$emailCopia</u> .
  		<br><br><b>Sem preju�zo quanto � responsabilidade referente � gest�o contratual pr�pria do setor demandante, 
  		� imprescind�vel a resposta deste email, ainda que inexista interesse na prorroga��o, para fins de controle e registro desta UNCT</b>.
  		
  		<br><br>Caso j� tenha enviado o pedido de prorroga��o, favor desconsiderar esta solicita��o.
  		<br><br>Na aus�ncia de manifesta��o, este e-mail ser� reenviado a cada <b>$numFrequencia dia(s)</b>.";  		 		
  		
  		return $retorno;
  	}
  	
  	static function getMensagemGestorContratoImprorrogavel($codigoContrato, $numFrequencia){
  		//$retorno = "<br><br>Caro Gestor, favor verificar o vencimento do contrato $codigo.";
  	
  		//$numFrequencia = complementarCharAEsquerda($numFrequencia, "0", 3);
  		$emailPrincipal = email_sefaz::$REMETENTE_PRINCIPAL;
  		$emailCopia = email_sefaz::$REMETENTE_COPIA;
  	
  		$retorno = "<br>Prezado gestor,
  		
		<br><br><br><b>Esta � uma mensagem autom�tica</b>, gerada pelo sistema de automa��o da Unidade de Contratos (UNCT/SAFI), 
		comunicando a <b>improrrogabilidade</b> do contrato <b>$codigoContrato</b>, que em breve se encerrar�.
  		<br>Havendo interesse da SEFAZ pela manuten��o do servi�o contratado, requere-se provoca��o tempestiva, via SEI, � SAFI, 
  		pleiteando a abertura de novo processo licitat�rio.
  	  	
  		<br><br>Excepcionalmente, permite-se a an�lise extraordin�ria de uma nova prorroga��o, desde que atendidos os requisitos legais.</b>
  	
  		<br><br>A resposta deve ser enviada para o seguinte correio eletr�nico: <b><u>$emailPrincipal</u></b>, com c�pia para <u>$emailCopia</u> .
  		<br><br><b>Sem preju�zo quanto � responsabilidade referente � gest�o contratual pr�pria do setor demandante,
  		� imprescind�vel a resposta deste email, ainda que inexista interesse na prorroga��o, para fins de controle e registro desta UNCT</b>.
  	
  		<br><br>Caso esta solicita��o j� tenha sido respondida, favor desconsider�-la.
  		<br><br>Na aus�ncia de manifesta��o, este e-mail ser� reenviado a cada <b>$numFrequencia dia(s)</b>.";
  	
  		return $retorno;
  	}
  	 
  	function getSQLValuesInsert($vo){
  		  		
		$retorno = "";
		
		$retorno.= $this-> getVarComoNumero($vo->sq). ",";
		$retorno.= $this-> getVarComoNumero($vo->sqMensageria);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
   
}
?>