<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_vos. "vogestor.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbgestor extends dbprocesso{
    
	function consultarPorChave($vo, $isHistorico){
        $nmTabela = $vo->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
        $query.= ".*, TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vogestor::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vogestor::$nmAtrCdUsuarioUltAlteracao;
        $query.= " WHERE ";
        $query.= $vo->getValoresWhereSQLChave($isHistorico);        
        		
		//echo $query;
        return $this->consultarEntidade($query, true);
	}    
    
	function consultarSelect(){        
        $vo = new vogestor();            
        $nmTabela = $vo->getNmTabelaEntidade(false);        
		$query = "SELECT * FROM ".$nmTabela;        
        		
		//echo $query;
        return $this->consultarEntidade($query, false);
	}    

    function incluirSQL($voGestor){
        $arrayAtribRemover = array(
            vogestor::$nmAtrDhInclusao,
            vogestor::$nmAtrDhUltAlteracao
            );
        
        if($voGestor->cd == null || $voGestor->cd == ""){
        	$voGestor->cd = $this->getProximoSequencial(vogestor::$nmAtrCd, $voGestor);        
        }
        
        //$voGestor->cd = $this->getProximoSequencial(vogestor::$nmAtrCd, $voGestor);        
        
        return $this->incluirQuery($voGestor, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($voGestor){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($voGestor->cd) . ",";
        $retorno.= $this-> getVarComoString($voGestor->descricao);
        
        $retorno.= $voGestor->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->descricao != null){
            $retorno.= $sqlConector . vogestor::$nmAtrDescricao . " = " . $this->getVarComoString($vo->descricao);
            $sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
    
    /**
     *FUNCOES DE IMPORTACAO EXCLUSIVA
     */
    
	function importar(){        
        $query = "SELECT ct_gestor from contrato where ct_gestor is not null GROUP BY ct_gestor ";
        $retorno = $this->consultarEntidade($query, false);        
            if($retorno != null){
                $tamanho = count($retorno);
                //echo "<br> qtd registros: " . $tamanho;
                               
                for ($i=0; $i<=$tamanho; $i++) {
                    $vo = new vogestor();
                    
                    $vo->descricao = $retorno[$i]["ct_gestor"];
                    //$vo->cd = $i."";
                    
                    //echo $vo->toString() . "<br>";
                    echo $this->incluir($vo) . "<br>";
                    
                }

            } 
        /*$vo = new vogestor();
        $vo->descricao = "TESTE";
                    
        //echo $vo->toString() . "<br>";
        echo $this->incluir($vo) . "<br>";*/
	}	
}
?>