<?php
include_once(caminho_lib. "dbprocesso.obj.php");
//include_once(caminho_util."bibliotecaDataHora.php");
include_once 'dbContratoModificacao.php';

  Class dbContratoModificacao extends dbprocesso{
  	static $FLAG_PRINTAR_SQL = false;
  	
  	function consultarPorChaveTela($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  		
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  		 
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaInicialContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtVigenciaFinalContrato,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),
  		);
  		  		
  		$queryJoin .= "\n left JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrTipoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrCdEspecieContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoLicon::$nmAtrSqEspecieContrato;
  		 
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  	
  		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico );
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico);
  		$nmTabelaContrato = vocontrato::getNmTabelaStatic ( false );
  		$nmTabelaPessoaContrato = vopessoa::getNmTabelaStatic ( false );
  		$nmTabContratoMATER = "TAB_CONTRATO_MATER";
  	
  		$colecaoAtributoCoalesceNmPessoa = array(
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrContratadaContrato,
  		);
  	
  		$arrayColunasRetornadas = array (
  				$nmTabela . ".*",
  				$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrDoc,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtPublicacaoContrato,
  				$nmTabelaContrato . "." . vocontrato::$nmAtrDtAssinaturaContrato,
  				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlMensalContrato . " AS " . filtroManterContratoModificacao::$NmColVlMensalMater,
  				$nmTabContratoMATER . "." . vocontrato::$nmAtrVlGlobalContrato . " AS " . filtroManterContratoModificacao::$NmColVlGlobalMater,
  				//$nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome,
  				getSQLCOALESCE($colecaoAtributoCoalesceNmPessoa,vopessoa::$nmAtrNome),  				
  		);
  	  	  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaContrato . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrAnoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdContrato. "=" . $nmTabela . "." . voContratoModificacao::$nmAtrCdContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrTipoContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrCdEspecieContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrCdEspecieContrato;
  		$queryJoin .= " AND " . $nmTabelaContrato . "." . vocontrato::$nmAtrSqEspecieContrato . "=" . $nmTabela . "." . voContratoModificacao::$nmAtrSqEspecieContrato;
  	
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaPessoaContrato;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoaContrato . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaContrato . "." . vocontrato::$nmAtrCdPessoaContratada;
  		
  		//SERVE PARA PEGAR O VALOR INICIAL DO CONTRATO
  		$nmTabContratoInterna = vocontrato::getNmTabelaStatic ( false );
  		$groupbyinterno = vocontrato::$nmAtrAnoContrato
  			. "," . vocontrato::$nmAtrCdContrato
  			. "," . vocontrato::$nmAtrTipoContrato
  			. "," . vocontrato::$nmAtrCdEspecieContrato
  			. "," . vocontrato::$nmAtrSqEspecieContrato
	  		. "," . vocontrato::$nmAtrVlMensalContrato 
	  		. "," . vocontrato::$nmAtrVlGlobalContrato;
  			  		
  		$queryJoin .= "\n LEFT JOIN ";
  		$queryJoin .= "\n\n (SELECT $groupbyinterno ";
  		$queryJoin .= " FROM " . $nmTabContratoInterna;
  		$queryJoin .= " WHERE " ;
  		$queryJoin .= vocontrato::$nmAtrCdEspecieContrato . "=" . getVarComoString(dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER);
  		$queryJoin .= "\n) " . $nmTabContratoMATER;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrAnoContrato;
  		$queryJoin .= "\n AND ";
  		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrCdContrato;
  		$queryJoin .= "\n AND ";
  		$queryJoin .= $nmTabela . "." . vocontrato::$nmAtrTipoContrato . "=" . $nmTabContratoMATER . "." . voContratoModificacao::$nmAtrTipoContrato;
  		  		
  		//echo "aqui";
  	  	
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	}  	 
  	   	 
  	function validarInclusao($vo){  		
  		$registro = $this->getRegistroDataTermoComDataPosterior($vo);
  		$termo = new voContratoModificacao();
  		$termo->getDadosBanco($registro);  		
  		$data = $termo->dtModificacao;
  		
  		//verifica se o contrato ja foi adicionado a planilha 
  		$vocontratoTemp = new vocontrato();
  		$vocontratoTemp = $vo->vocontrato;
  		$dbcontrato = new dbcontrato();
  		try{
  			$vocontratoTemp = $dbcontrato->consultarPorChaveVO($vocontratoTemp, false);
  		}catch(excecaoChaveRegistroInexistente $ex){
  			throw new excecaoChaveRegistroInexistente("Contrato selecionado: " . $vocontratoTemp->getCodigoContratoFormatado(true) . " nуo existe na planilha.", $ex);
  		}
  		
  		$isOrdemIncorreta = $data != null;  		
  		if($isOrdemIncorreta){  			
  			$dtProducaoEfeito = $vo->dtModificacao;
  			$detContrato = $termo->vocontrato->getCodigoContratoFormatado(true);
  			throw new excecaoGenerica("A data de produчуo de efeitos do termo em questуo ($dtProducaoEfeito) щ anterior a do termo jс existente ($detContrato): " . getData($data) . ". Verifique a ordem das modificaчѕes contratuais.");
  		}
  	}
  	  	
  	 function getRegistroDataTermoComDataPosterior($vo){
  	 	$voContratoFiltro = clone $vo->vocontrato;
  	 	$voContratoFiltro->cdEspecie = null;
  	 	$voContratoFiltro->sqEspecie = null;
  	 	$dtProducaoEfeito = $vo->dtModificacao;
  	 	if($dtProducaoEfeito == null){
  	 		throw new excecaoGenerica("A data do termo em questуo nуo pode ser nula. Verifique o campo na tela de inclusуo.");
  	 	}  	 		
  	 	$retorno = null;
  	 	$filtro = new filtroManterContratoModificacao(false);
  	 	$filtro->setaFiltroConsultaSemLimiteRegistro();
  	 	$filtro->vocontrato = $voContratoFiltro;
  	 	$filtro->dtProducaoEfeitoTermoPosterior = $dtProducaoEfeito;
  	 	$recordSet = $this->consultarTelaConsulta($vo, $filtro);
  	 	
  	 	if(!isColecaoVazia($recordSet)){
  	 		$retorno = $recordSet[0];
  	 	}
  	 	
  		return $retorno;
  	}
  	 
  	 function incluir($vo){
  	 	$this->validarInclusao($vo);  	 	
  		return parent::incluir($vo);
  	}
  	
  	function alterar($vo){
  		throw new excecaoGenerica("Operaчуo nуo permitida.");
  		//$this->validarInclusao($vo);  			
  		//return parent::alterar($vo);
  	}
  	 
  	function getSQLValuesInsert($vo){  		
  		if ($vo->sq == null || $vo->sq == "") {
  			$vo->sq = $this->getProximoSequencialChaveComposta ( voContratoModificacao::$nmAtrSq, $vo );
  		}
  		
		$retorno = "";		
		$retorno.= $this-> getVarComoNumero($vo->sq). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->anoContrato). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->cdContrato). ",";
		$retorno.= $this-> getVarComoString($vo->vocontrato->tipo). ",";
		$retorno.= $this-> getVarComoString($vo->vocontrato->cdEspecie). ",";
		$retorno.= $this-> getVarComoNumero($vo->vocontrato->sqEspecie). ",";
		
        $retorno.= $this-> getVarComoNumero($vo->tpModificacao). ",";
        $retorno.= $this-> getVarComoData($vo->dtModificacao). ",";
        $retorno.= $this-> getVarComoData($vo->dtModificacaoFim). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoReferencial). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoReal). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlModificacaoAoContrato). ",";
        
        $retorno.= $this-> getVarComoDecimal($vo->vlMensalAtual). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlGlobalAtual). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlGlobalReal). ",";

        $retorno.= $this-> getVarComoDecimal($vo->vlMensalAnterior). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlGlobalAnterior). ",";
        
        $retorno.= $this-> getVarComoDecimal($vo->vlMensalModAtual). ",";
        $retorno.= $this-> getVarComoDecimal($vo->vlGlobalModAtual). ",";
        
        $retorno.= $this-> getVarComoDecimal($vo->numMesesParaOFimdoPeriodo). ",";
        $retorno.= $this-> getVarComoDecimal($vo->numPercentual). ",";
        $retorno.= $this-> getVarComoString($vo->obs);                
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->situacao != null){
            $retorno.= $sqlConector . voContratoLicon::$nmAtrSituacao . " = " . $this->getVarComoNumero($vo->situacao);
            $sqlConector = ",";
        }
                
        if($vo->obs != null){
        	$retorno.= $sqlConector . voContratoLicon::$nmAtrObs . " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>