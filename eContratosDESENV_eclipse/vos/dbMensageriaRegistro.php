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
  				$vomensageria = new voMensageria();
  				$vomensageria->getDadosBanco($registroMensageria);
  				
  				$vomensagemregistro = new voMensageriaRegistro();
  				$vomensagemregistro->sqMensageria = $vomensageria->sq;
  				$this->incluir ( $vomensagemregistro );
  				$this->enviarEmailGestor($registroMensageria);
  				// End transaction
  				$this->cDb->commit ();
  				
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
  	
  		$assunto = "COMUNICA��O:";  	
  		$emailGestor = $registro[vopessoa::$nmAtrEmail];
  		$isEmailGestorValido = $emailGestor != null && $emailGestor != "";
  		$listaEmailTemp = email_sefaz::getListaEmailAvisoGestorContrato();  		
  		
  		if(isColecaoVazia($listaEmailTemp)){
  			throw new excecaoGenerica("N�o h� respons�veis na UNCT cadastrados para o mensageria.");
  		}  		
  		
  		$codigo = formatarCodigoContrato($vocontratoinfo->cdContrato, $vocontratoinfo->anoContrato, $vocontratoinfo->tipo);
  		$msg .= static::getMensagemGestor($codigo,$numFrequencia);
  		
  		//$msg .= "<br>O contrato vencer� em dias.";
  		if(!$isEmailGestorValido){
  			//se o alerta nao for valido, envia apenas para os responsaveis
  			$msg = "<br><br><u><b>Contrato SEM E-MAIL V�LIDO para o Gestor. Mensageria:</b></u> $vomensageria->toString().";  			  				
  		}else{
  			//se o alerta for valido, acrescenta o e-mail do gestor
  			if(voMensageria::$ENVIAR_EMAIL_GESTOR){
	  			$array2 = array($emailGestor);
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
  		
  		<br><br><br>Esta Unidade de Contratos solicita informa��es referentes � prorroga��o do contrato <b>$codigoContrato</b>, que em breve se encerrar�.
  		<br>Havendo interesse da SEFAZ pela prorroga��o, favor enviar com a maior brevidade poss�vel CI � SAFI.
  		<br><br>Vale salientar que toda prorroga��o dever� ser solicitada com, no m�nimo, <b>60 (sessenta) dias DE ANTECED�NCIA ao vencimento do contrato em quest�o<b>.
  		<br><br><b>Se o contrato n�o comportar mais prorroga��o e persistindo a necessidade da contrata��o, o gestor dever� solicitar novo processo licitat�rio com, no m�nimo 5 (cinco) meses de anteced�ncia, e seguir o modelo de C.I., juntamente com o termo de refer�ncia, sob pena de ficar sem a presta��o do servi�o.
  		
  		<br><br>Informamos ainda que o envio da garantia contratual atualizada da empresa contratada � necess�ria � instru��o da renova��o contratual.</b>  		
  		<br><br>Caso j� tenha enviado o pedido de prorroga��o, favor desconsiderar esta solicita��o informando o n� e data da C.I.
  		
  		<br><br>Aguardamos as provid�ncias cab�veis, com a urg�ncia requerida.
  		<br><br>Este e-mail � reenviado a cada <b>$numFrequencia dias</b>.";  		 		
  		
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