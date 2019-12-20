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
  				
  				$this->enviarEmailGestor($registroMensageria);
  				
  			} catch ( Exception $e ) {
  				$this->cDb->rollback ();
  				throw new Exception ( $e->getMessage () );
  			}  		
  	} 
  	
  	function enviarEmailGestor($registro, $enviarEmail=true){  	
  		$vomensageria = new voMensageria();
  		$vomensageria->getDadosBanco($registro);
  		$numFrequencia = $vomensageria->numDiasFrequencia;
  		//$numFrequencia = $registro[voMensageria::$nmAtrNumDiasFrequencia];
  		$vocontratoinfo = $vomensageria->vocontratoinfo; 		
  	
  		$assunto = "COMUNICAÇÃO:";  	
  		$emailGestor = $registro[vopessoa::$nmAtrEmail];
  		$isEmailGestorValido = $emailGestor != null && $emailGestor != "";
  		
  		//para o caso de ser mais de um email cadastrado
  		$arrayEmailGestor = explode(";", $emailGestor);
  		
  		$listaEmailTemp = array();
  		$listaEmailTemp = email_sefaz::getListaEmailAvisoGestorContrato();  		
  		
  		if(isColecaoVazia($listaEmailTemp)){
  			throw new excecaoGenerica("Não há responsáveis na UNCT cadastrados para o mensageria.");
  		}  		
  		
  		$codigo = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
  		$msg .= static::getMensagemGestor($codigo,$numFrequencia);
  		
  		//$msg .= "<br>O contrato vencerá em dias.";
  		if(!$isEmailGestorValido){
  			//se o alerta nao for valido, envia apenas para os responsaveis
  			$msg = "<br><br><u><b>Contrato SEM E-MAIL VÁLIDO para o Gestor. Mensageria:</b></u> $vomensageria->toString().";  			  				
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
  	
		enviarEmail($assunto, $msg, $enviarEmail, $listaEmailTemp);
		echoo($vomensageria->toString());
  	}
  	
  	static function getMensagemGestor($codigoContrato, $numFrequencia){  		
  		//$retorno = "<br><br>Caro Gestor, favor verificar o vencimento do contrato $codigo.";
  		
  		$numFrequencia = complementarCharAEsquerda($numFrequencia, "0", 3);
  		
  		$retorno = "<br>Prezado gestor,
  		
  		<br><br><br>A Unidade de Contratos/SAFI solicita informações referentes à <b>prorrogação</b> do contrato <b>$codigoContrato</b>, que em breve se encerrará.
  		<br>Havendo interesse da SEFAZ pela prorrogação, favor enviar com a maior brevidade possível CI, via SEI à SAFI, junto com as cotações de preços e a anuência da Contratada.  		

  		<br><br><b>Se o contrato não comportar mais prorrogação e persistindo a necessidade da contratação, o gestor deverá solicitar novo processo licitatório, juntamente com o termo de referência, sob pena de encerramento da prestação do serviço.
  		
  		<br><br>Informamos ainda que o envio da garantia contratual atualizada, sendo este o caso, é necessária à instrução da renovação contratual.</b>  		
  		<br><br>Caso já tenha enviado o pedido de prorrogação, favor desconsiderar esta solicitação.
  		
  		<br><br>Este e-mail é reenviado a cada <b>$numFrequencia dias</b>.";  		 		
  		
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