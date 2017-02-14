<?php
include_once "config.obj.php";
include_once "db.obj.php";
include_once(caminho_util."paginacao.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");

class dbprocesso{
	
	var $cDb;
    var $cConfig;
	static $nmCampoCount  = "nmCampoCount";

	// ...............................................................
	// construtor	
	function __construct() {
		$this->cConfig = new config();
		$this->cDb = new db();
		$this->cDb->abrirConexao($this->cConfig->db, $this->cConfig->login, $this->cConfig->senha,$this->cConfig->odbc,$this->cConfig->driver,$this->cConfig->servidor);
	}
    
    function incluirHistorico($voEntidade) {
        $tabelaHistorico = $voEntidade->getNmTabelaEntidade(true);
        
        $novoSeq = " SELECT MAX(" . voentidade::$nmAtrSqHist . ")+1 FROM " . $tabelaHistorico;
        
        $query = "INSERT INTO " . $tabelaHistorico;
        $query.= " SELECT ($novoSeq)," . $voEntidade->getNmTabela() . ".* FROM " . $voEntidade->getNmTabela();
        $query.= " WHERE ";
        $query.= $voEntidade->getValoresWhereSQLChave(false);
        
        //echo $query;
        
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;
	}
    
	function validaAlteracao($voEntidade){
		$query = "SELECT ".voentidade::$nmAtrDhUltAlteracao." FROM ".$voEntidade->getNmTabela();
        $query.= " WHERE ";
        $query.= $voEntidade->getValoresWhereSQLChave(false);
		       
		//echo $query;		
        $registro = $this->consultarEntidade($query, true);                
        $dhValidacao = getDataHoraSQLComoString($registro[0][voentidade::$nmAtrDhUltAlteracao]);
        
        /*echo "<br>data banco: " . $dhValidacao;
        echo "<br>data registro: " . $voEntidade->dhUltAlteracao;*/
        
        if($dhValidacao != $voEntidade->dhUltAlteracao){
            throw new Exception("Registro desatualizado.");
        }        
               
        return $registro[0];
	}
    
	function consultarEntidade($query, $isPorChavePrimaria){
		//echo $query;		
		$retorno = $this->cDb->consultar($query);		

        if($isPorChavePrimaria){
            $tamanho = sizeof($retorno);
            
            if($tamanho > 1)
                throw new Exception("Existe mais de um registro.");
        }
		
	    return $retorno;
	}
    
	function atualizarEntidade($query){
		//echo $query;
		$retorno = $this->cDb->atualizar($query);
		
		return $retorno;
	}
	
	function getEntidadePorChavePrimariaComValoresDiversosEmColunas($recordset, $colecaoAtr){
		$retorno = null;
		if(!isColecaoVazia($recordset) && !isColecaoVazia($colecaoAtr)){
			
			$tamanho = count($colecaoAtr);
			$retorno = $recordset[0];
			
			for($i=0; $i<$tamanho;$i++){
				$nmColuna = $colecaoAtr[$i];
				$retorno[$nmColuna]= getColunaEmLinha($recordset, $nmColuna, CAMPO_SEPARADOR);
			}				
		}
		
		return $retorno;
	}
	
	function consultarComPaginacao($voentidade, $filtro){
        $isHistorico = ("S" == $filtro->cdHistorico);       
        $nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);

        $querySelect = "SELECT * ";
        $queryFrom = " FROM " . $nmTabela;
        
        return $this->consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom);
	}
    
	/*function consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom){
                
        $retorno = "";
        $isHistorico = ("S" == $filtro->cdHistorico);       

		//flag que diz se pode consultar ou nao
		$consultar = @$_GET["consultar"];
		
        if($consultar == "S"){
			$filtroSQL = $filtro->getFiltroConsultaSQL($isHistorico);
			
			//verifica se tem paginacao
			$limite = "";
			if($filtro->TemPaginacao){	
				//ECHO "TEM PAGINACAO";
	            $pagina = $filtro->paginacao->getPaginaAtual();                                    
				//guarda o numero total de registros para nao ter que executar a consulta TODOS novamente
	            $queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount . $queryFrom . $filtroSQL;
	            $numTotalRegistros = $filtro->numTotalRegistros = $this->getNumTotalRegistrosQuery($queryCount);		
	
	            $qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
				//calcula o número de páginas arredondando o resultado para cima
				$numPaginas = ceil($numTotalRegistros/$qtdRegistrosPorPag);
				$filtro->paginacao->setNumTotalPaginas($numPaginas);            
				
				$inicio = ($qtdRegistrosPorPag*$pagina)-$qtdRegistrosPorPag;
				$limite = " LIMIT $inicio,$qtdRegistrosPorPag";
			}
			
            //aqui eh onde faz realmente a consulta a retornar
            $query = $querySelect . $queryFrom. " $filtroSQL ";			
            $query = $query. " $limite";
			
			//echo $filtroSQL;			
			//echo "$queryCount<br>";
			//echo "$query<br>";
			
            //removeObjetoSessao($voentidade->getNmTabela());
			
			$retorno = $this->cDb->consultar($query);
		}
        
        //echo $filtro->toString();
		
	    return $retorno;        
	}*/
	
	function consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom){
		return $this->consultarFiltro($filtro, $querySelect, $queryFrom, true);
	}
    
	function consultarFiltro($filtro, $querySelect, $queryFrom, $validaConsulta){
	
		$retorno = "";
		$isHistorico = ("S" == $filtro->cdHistorico);
	
		//flag que diz se pode consultar ou nao
		$consultar = @$_GET["consultar"];
	
		if($consultar == "S" || !$validaConsulta){
			$filtroSQL = $filtro->getFiltroConsultaSQL($isHistorico);
				
			//verifica se tem paginacao
			$limite = "";
			if($filtro->TemPaginacao){
				//ECHO "TEM PAGINACAO";
				$pagina = $filtro->paginacao->getPaginaAtual();
				//guarda o numero total de registros para nao ter que executar a consulta TODOS novamente
				$queryCount = "SELECT count(*) as " . dbprocesso::$nmCampoCount . $queryFrom . $filtroSQL;
				$numTotalRegistros = $filtro->numTotalRegistros = $this->getNumTotalRegistrosQuery($queryCount);
	
				$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
				//calcula o número de páginas arredondando o resultado para cima
				$numPaginas = ceil($numTotalRegistros/$qtdRegistrosPorPag);
				$filtro->paginacao->setNumTotalPaginas($numPaginas);
	
				$inicio = ($qtdRegistrosPorPag*$pagina)-$qtdRegistrosPorPag;
				$limite = " LIMIT $inicio,$qtdRegistrosPorPag";
			}
				
			//aqui eh onde faz realmente a consulta a retornar
			$query = $querySelect . $queryFrom. " $filtroSQL ";
			$query = $query. " $limite";
				
			//echo $filtroSQL;
			//echo "$queryCount<br>";
			//echo "$query<br>";
				
			//removeObjetoSessao($voentidade->getNmTabela());
				
			$retorno = $this->cDb->consultar($query);
		}
	
		//echo $filtro->toString();
	
		return $retorno;
	}
	
	function getNumTotalRegistrosQuery($query){
        $queryCount = $query;
        //echo $queryCount;
        $retorno = $this->cDb->consultar($queryCount);
        $numTotalRegistros = $retorno[0][dbprocesso::$nmCampoCount];        
        return $numTotalRegistros;
    }

	function incluir($voEntidade){
        //ta na classe filha
        $query = $this->incluirSQL($voEntidade);
		//echo $query;		
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;		
	}
    
    function incluirQuery($voEntidade, $arrayAtribRemover){
        $atributosInsert = $voEntidade->getTodosAtributos();
        //var_dump ($atributosInsert);        
        //echo "<br>";
        //var_dump($arrayAtribRemover);

        $atributosInsert = removeColecaoAtributos($atributosInsert, $arrayAtribRemover);
        //var_dump ($atributosInsert);
        //echo "<br>";
        
        $atributosInsert = getColecaoEntreSeparador($atributosInsert, ",");
        
        //echo "<br>$atributosInsert";
        
		$query = " INSERT INTO " . $voEntidade->getNmTabela() . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .=") ";		
		$query .= " \nVALUES(";
        //o metodo abaixo eh implementado para cada classe filha
		$query .= $this->getSQLValuesInsert($voEntidade);
		$query .=")";
        
        //echo $query;
							
	    return $query;        
    }
    
	function excluir($voEntidade){
        //echo $voEntidade->sqHist;
        $isHistorico = $voEntidade->sqHist != null;
        if($isHistorico)
            $retorno = $this->excluirEmDefinitivo($voEntidade, true);
        else{
            
            if($voEntidade->temTabHistorico)        
                $retorno = $this->excluirHistoriando($voEntidade);
            else
                $retorno = $this->excluirEmDefinitivo($voEntidade, false);
        }

	    return $retorno;		
	}	    

	function excluirEmDefinitivo($voEntidade, $isHistorico){
		
		if($isHistorico && !temPermissaoParamHistorico(true))
			throw new Exception("Usuário não tem permissão para exclusão de histórico.");
		
        $query = $this->excluirSQL($voEntidade, $isHistorico);
		//echo $query;		
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;		
	}	    

	function excluirHistoriando($voEntidade){        
        //Start transaction         
        $this->cDb->retiraAutoCommit();        
        try{
            $this->validaAlteracao($voEntidade);
            $this->incluirHistorico($voEntidade);
            
            $this->excluirEmDefinitivo($voEntidade, false);              
            //End transaction           
            $this->cDb->commit();
        }catch(Exception $e){
            $this->cDb->rollback();
            throw new Exception($e->getMessage());
        }        
                
	  return $retorno;		
	}
    
    function excluirSQL($voEntidade, $isHistorico){
        $nmTabela = $voEntidade->getNmTabelaEntidade($isHistorico);
        
		$query = " DELETE FROM " . $nmTabela . " \n";
        $query .= "\n WHERE ";
        //chave primaria
        $query.= $voEntidade->getValoresWhereSQLChave($isHistorico);
        
        //echo $query;
	    return $query;        
    }    
	                
    function alterarSQL($voEntidade){        
		$query = " UPDATE " . $voEntidade->getNmTabela() . " \n";
		$query .= " SET ";
        $query .= $this->getSQLValuesUpdate($voEntidade);
        $query .= "\n WHERE ";
        //chave primaria
        //$query.= vogestorpessoa::$nmAtrSqContrato . " = " . $voContrato->sq;
        
        $query.= $voEntidade->getValoresWhereSQLChave(false);

	    return $query;        
    }
    
    
	function alterar($voEntidade){
        $temTabHistorico = $voEntidade->temTabHistorico;
        if(!$temTabHistorico)
            $retorno = $this->alterarPorCima($voEntidade);
        else
            $retorno = $this->alterarHistoriando($voEntidade);
	    
        return $retorno;		
	}	    
    
	function alterarPorCima($voEntidade){
        $query = $this->alterarSQL($voEntidade);
		//echo $query;		
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;		
	}
    
	function alterarHistoriando($voEntidade){        
        //Start transaction         
        $this->cDb->retiraAutoCommit();        
        try{
            $this->validaAlteracao($voEntidade);
            $this->incluirHistorico($voEntidade);
            
            //altera o registro sendo este o mais vigente
            $this->alterarPorCima($voEntidade);              
            //End transaction           
            $this->cDb->commit();
        }catch(Exception $e){
            $this->cDb->rollback();
            throw new Exception($e->getMessage());
        }        
                
	  return $retorno;		
	}	    

	function getProximoSequencial($nmColuna, $voEntidade){
		$query = " SELECT MAX(" . $nmColuna . ")+1 AS ". $nmColuna ." FROM " . $voEntidade->getNmTabela() . " ";
        $registro = $this->consultarEntidade($query, false);
        
        $retorno = $registro[0][$nmColuna];
        return $retorno;        
	}
	
	function getProximoSequencialChaveComposta($nmColunaSq, $voEntidade){
		
		$arrayAtribRemover = array($nmColunaSq);		
		$arrayColunasChaveSemSq = removeColecaoAtributos($voEntidade->getAtributosChavePrimaria(), $arrayAtribRemover);
						
		$query = " SELECT MAX(" . $nmColunaSq . ")+1 AS ". $nmColunaSq ." FROM " . $voEntidade->getNmTabela() . " ";
		$query .= "\n GROUP BY ". getSQLStringFormatadaColecaoIN($arrayColunasChaveSemSq, false);
		
		//echo $query;
		$registro = $this->consultarEntidade($query, false);
		
		if($registro != "")
			$retorno = $registro[0][$nmColunaSq];
		else 
			$retorno = 1;
		
		//echo $retorno;
		
		return $retorno;
	}
	
	//---------------------------------	
	function limpaResultado(){
		$this->cDb->limpaResultado();	   
	}
	
	Function finalizar() {
		$this->cDb->fecharConexao();
	}

    /* 	Function finalize() {
		$this->cDb->fecharConexao();
	}

	Function fechar() {
		$this->cDb->close();
	}*/
	
	/*Function getTexto($textoBanco) {
		return utf8_decode($textoBanco);
	}*/
    
    /**
     *FUNCOES MANIPULACAO
     */
    function getVarComoString($param){
        //return "'" . utf8_encode($param) . "'";
        $retorno = "null";
        if($param != null)
            $retorno =  "'" . trim($param) . "'";
            
        return $retorno;	
    }
    
    function getVarComoNumero($param){
        $retorno = "null";        
        $isNum = isNumero($param);         
        if($isNum){
            $retorno =  trim($param);
           // echo "EH NUMERO";
        }        
        return $retorno;	
    }
    
    function getDataSQL($param){
        $retorno = "null";
        //echo $param;
        if($param != null)
            $retorno = "'" . (substr($param,6,4)) . "-" . substr($param,3,2) . "-" . substr($param,0,2) . "'";	
        return $retorno;
    }
    
    function getDecimalSQL($param){
        $retorno = "null";
        $valor = str_replace(" ", "", "$param");
        $valor = str_replace(".", "", "$valor");
        $valor = str_replace(",", ".", "$valor");
        
        //echo $valor;
        if(isNumero($valor)){
            $retorno = $valor;
            //echo "É NÚMERO! <BR>";
        }
        //else
            //echo "NÃO É NÚMERO! <BR>";
            
        return $retorno;
    }	
}	