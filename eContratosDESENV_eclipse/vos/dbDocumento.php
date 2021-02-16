<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once 'voDocumento.php';

  Class dbDocumento extends dbprocesso{
  	
  	static $FLAG_PRINTAR_SQL = false;
            
  	function getProximoSqDoc($filtro){
  		//$filtro = new filtroManterDocumento();
  		$voentidade = new voDocumento();
  		$voentidade->cdSetor = $filtro->cdSetor; 
  		$voentidade->ano = $filtro->ano;
  		$voentidade->tp = $filtro->tp;
  		return $this->getProximoSequencialChaveComposta(voDocumento::$nmAtrSq, $voentidade);
  	}
  	 
  	function consultarTelaConsulta($arrayParamConsulta){
  		$filtro = $arrayParamConsulta[0];
  		$voentidade = $filtro->voPrincipal;
  		if($voentidade  == null){
  			$voentidade = new voDocumento();
  		}
  		
  		$isHistorico = ("S" == $filtro->cdHistorico);
  		$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
  		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
  		$nmTabelaDemandaDoc = voDemandaTramDoc::getNmTabelaStatic(false);
  			
  		$arrayColunasRetornadas = array("$nmTabela.*");
  		//$atributosConsulta = "$nmTabela.*";
  		//$querySelect = "SELECT ". $atributosConsulta;
  		//$queryFrom .= "\n FROM ". $nmTabela;
  		$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaDoc;
  		$queryFrom .= "\n ON $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrAnoDoc . "= $nmTabela." . voDocumento::$nmAtrAno;
  		$queryFrom .= "\n AND $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrTpDoc . "= $nmTabela." . voDocumento::$nmAtrTp;
  		$queryFrom .= "\n AND $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrCdSetorDoc . "= $nmTabela." . voDocumento::$nmAtrCdSetor;
  		$queryFrom .= "\n AND $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrSqDoc . "= $nmTabela." . voDocumento::$nmAtrSq;
  		
  		$queryFrom .= "\n LEFT JOIN ". $nmTabelaDemandaContrato;
  		$queryFrom .= "\n ON $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrAnoDemanda . "= $nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrAnoDemanda;
  		$queryFrom .= "\n AND $nmTabelaDemandaDoc.". voDemandaTramDoc::$nmAtrCdDemanda . "= $nmTabelaDemandaContrato." . voDemandaContrato::$nmAtrCdDemanda;
  		
  		$groupby = array("$nmTabela." . voDocumento::$nmAtrAno,
  				"$nmTabela." . voDocumento::$nmAtrTp,
  				"$nmTabela." . voDocumento::$nmAtrCdSetor,
  				"$nmTabela." . voDocumento::$nmAtrSq,
  		);
  		
  		$filtro->groupby = $groupby;
  		
  		//echo $queryFrom;
  		
  		//return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);  
  		return parent::consultarMontandoQueryTelaConsulta ( $voentidade, $filtro, $arrayColunasRetornadas, $queryFrom );
  	}  	
  	
  	function consultarDocumento($voentidade, $filtro){
  		$isHistorico = ("S" == $filtro->cdHistorico);
  		$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
  			
  		$atributosConsulta = "*";
  		$querySelect = "SELECT ". $atributosConsulta;
  		$queryFrom = "\n FROM ". $nmTabela;
  	
  		return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
  	}
  	 
  	function incluir($vo){
  		  	
  		if($vo->sq == null || $vo->sq == ""){
  			$vo->sq = $this->getProximoSequencialChaveComposta(voDocumento::$nmAtrSq, $vo);  			
  		}
  	
  		$query = $this->incluirQueryVO($vo);
  		//echo $query;
  		$retorno = $this->cDb->atualizar($query);
  	
  		return $vo;
  	}   

    function getSQLValuesInsert($voDocumento){
		$retorno = "";        
        $retorno.= $this-> getVarComoNumero($voDocumento->sq) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->cdSetor) . ",";
        $retorno.= $this-> getVarComoNumero($voDocumento->ano). ",";
        $retorno.= $this-> getVarComoString($voDocumento->tp). ",";
        $retorno.= $this-> getVarComoString($voDocumento->link);
        
        $retorno.= $voDocumento->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->link != null){
            $retorno.= $sqlConector . voDocumento::$nmAtrLink. " = " . $this->getVarComoString($vo->link);
            $sqlConector = ",";
        }
                
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
   
}
?>