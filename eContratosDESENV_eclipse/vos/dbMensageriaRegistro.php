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
 				  				
  				$isContratoImprorrogavel = dominioTipoMensageria::$CD_CONTRATO_IMPRORROGAVEL == $registroMensageria[voMensageria::$nmAtrTipo];
  				$log = $this->enviarEmailGestor($registroMensageria, true, $isContratoImprorrogavel);
  				
  				//se nao deu erro no envio do email acima, cria o mensageriaregistro
  				if(voMensageria::$ENVIAR_EMAIL_GESTOR_CONTRATO){
  					$vomensageria = new voMensageria();
  					$vomensageria->getDadosBanco($registroMensageria);
  						
  					$vomensagemregistro = new voMensageriaRegistro();
  					$vomensagemregistro->sqMensageria = $vomensageria->sq;
  					$this->incluir ( $vomensagemregistro );
  					// End transaction
  					$this->cDb->commit ();
  				}  				
  				
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
  	
  		//$assunto = "COMUNICAÇÃO:";  	
  		$assunto = "SOLICITAÇÃO:";
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
  		$dsPessoa = $registro[vopessoa::$nmAtrNome];
  		if($dsPessoa != null && $dsPessoa != ""){
  			$codigo = "$codigo - $dsPessoa";
  		}
  		$assunto = "$assunto:$codigo.";
  		
  		if(!$isContratoImprorrogavel){
  			$msg .= voMensageriaRegistro::getMensagemGestor($codigo,$numFrequencia);
  		}else{
  			$msg .= voMensageriaRegistro::getMensagemGestorContratoImprorrogavel($codigo,$numFrequencia);
  		}
  		
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
	  			//inclui a DIFIN
	  			$listaEmailTemp = array_merge($listaEmailTemp, email_sefaz::getListaEmailDIFIN());
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
  	
  	/**
  	 * @deprecated
  	 * @param unknown $codigoContrato
  	 * @param unknown $numFrequencia
  	 * @return string
  	 */
  	static function getMensagemGestor($codigoContrato, $numFrequencia){  		
  		return voMensageriaRegistro::getMensagemGestor($codigoContrato, $numFrequencia);
  	}
  	
  	/**
  	 * @deprecated
  	 * @param unknown $codigoContrato
  	 * @param unknown $numFrequencia
  	 * @return string
  	 */
  	static function getMensagemGestorContratoImprorrogavel($codigoContrato, $numFrequencia){
  		return voMensageriaRegistro::getMensagemGestorContratoImprorrogavel($codigoContrato, $numFrequencia);
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