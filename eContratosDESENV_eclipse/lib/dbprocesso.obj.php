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
	
	static $nmTabelaUsuarioInclusao = "TAB_USU_INCLUSAO";
	static $nmTabelaUsuarioUltAlteracao = "TAB_USU_ULT_ALTERACAO";
	static $nmTabelaUsuarioOperacao = "TAB_USU_OPERACAO";

	// ...............................................................
	// construtor	
	function __construct() {
		$this->cConfig = new config();
		$this->cDb = new db();
		$this->cDb->abrirConexao($this->cConfig->db, $this->cConfig->login, $this->cConfig->senha,$this->cConfig->odbc,$this->cConfig->driver,$this->cConfig->servidor);
	}
    
    function incluirHistorico($voEntidade) {
    	//par ao historico funcionar, a tabela de historico deve estar adequada a estrutura da query abaixo
    	/*
    	 * deve possuir sempre:
    	 * 1 - o hist como seguencial
    	 * 2 - o dhoperacao (CURRENT TIMESTAMP) que diz a hora em q o registro foi historiado
    	 * 3 - o usuario da operacao id_user logado que fez a operacao
    	 */
        $tabelaHistorico = $voEntidade->getNmTabelaEntidade(true);
        
        $novoSeq = " SELECT MAX(" . voentidade::$nmAtrSqHist . ")+1 FROM " . $tabelaHistorico;
        
        $query = "INSERT INTO " . $tabelaHistorico;
        $query.= " SELECT ($novoSeq),";
        $query.= $voEntidade->getNmTabela() . ".*,";
        $query.= " CURRENT_TIMESTAMP, ";
        $query.= id_user;
        $query.= " FROM " . $voEntidade->getNmTabela();
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
	
	//depreciado
	/*function getQueryNmUsuario($vo, $queryJoin, $isHistorico){
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade($isHistorico);
		return $this->getQueryNmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico);		
	}*/
	
	//acrescenta os dados dos usuarios guardados na tabela
	function getQueryNmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);
		//$temUsuInclusao = false;		
		$temUsuInclusao = existeItemNoArray(voentidade::$nmAtrCdUsuarioInclusao, $vo->getTodosAtributos());
		$temUsuUltAlteracao = existeItemNoArray(voentidade::$nmAtrCdUsuarioUltAlteracao, $vo->getTodosAtributos());
		$temUsuHistorico = $vo->temTabHistorico && $isHistorico;
		
		/*if($temUsuHistorico){
			echo "tem usu ";
		}else{
			echo "NAO tem usu ";
		}*/
		
		if($temUsuInclusao){
			$query.= ", ".self::$nmTabelaUsuarioInclusao."." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
		}
		if($temUsuUltAlteracao){
			$query.= ", ".self::$nmTabelaUsuarioUltAlteracao."." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
		}
		if($temUsuHistorico){
			$query.= ", ".self::$nmTabelaUsuarioOperacao."." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioOperacao;
		}
		
		$query.= " FROM ". $nmTabela;
		
		$query.= $queryJoin;
		
		if($temUsuInclusao){
			$query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
			$query.= "\n ".self::$nmTabelaUsuarioInclusao." ON ";
			$query.= self::$nmTabelaUsuarioInclusao.".".vousuario::$nmAtrID. "=".$nmTabelaACompararCdUsuario.".".voentidade::$nmAtrCdUsuarioInclusao;
		}
		
		if($temUsuUltAlteracao){
			$query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
			$query.= "\n ".self::$nmTabelaUsuarioUltAlteracao." ON ";
			$query.= self::$nmTabelaUsuarioUltAlteracao.".".vousuario::$nmAtrID. "=".$nmTabelaACompararCdUsuario.".".voentidade::$nmAtrCdUsuarioUltAlteracao;
		}
		
		if($temUsuHistorico){
			$query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
			$query.= "\n ".self::$nmTabelaUsuarioOperacao." ON ";
			$query.= self::$nmTabelaUsuarioOperacao.".".vousuario::$nmAtrID. "=".$nmTabelaACompararCdUsuario.".".voentidade::$nmAtrCdUsuarioOperacao;
		}
				
		return $query;		
	}
	    
	function consultarPorChave($vo, $isHistorico){
		$nmTabela = $vo->getNmTabelaEntidade($isHistorico);		
		$arrayColunasRetornadas = array($nmTabela . ".*");
		
		return $this->consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, "", $isHistorico);		
	}
	
	function consultarPorChaveMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $isHistorico){
		
		$queryWhere = " WHERE ";
		$queryWhere.= $vo->getValoresWhereSQLChave($isHistorico);						
		return $this->consultarMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico);
	}
	
	function consultarMontandoQuery($vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico){
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade($isHistorico);
		return $this->consultarMontandoQueryUsuario($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, true);		
	}
	
	function consultarMontandoQueryTelaConsulta($vo, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico){
		$nmTabelaACompararCdUsuario = $vo->getNmTabelaEntidade($isHistorico);
		return $this->consultarMontandoQueryUsuario($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, false);		
	}
	
	function consultarMontandoQueryUsuario($vo, $nmTabelaACompararCdUsuario, $arrayColunasRetornadas, $queryJoin, $queryWhere, $isHistorico, $isConsultaPorChave){			
		$atributos = getSQLStringFormatadaColecaoIN($arrayColunasRetornadas, false);
		$query = "SELECT ". $atributos;
		$query.= $this->getQueryNmUsuarioTabelaAComparar($vo, $nmTabelaACompararCdUsuario, $queryJoin, $isHistorico);
	
		$query.= $queryWhere;
	
		//echo $query;
		$retorno = $this->consultarEntidade($query, $isConsultaPorChave);
		if($retorno != "" && $isConsultaPorChave){
			$retorno = $retorno[0];
		}
	
		return $retorno;
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
	
	function consultarComPaginacaoQuery($voentidade, $filtro, $querySelect, $queryFrom){
		return $this->consultarFiltro($filtro, $querySelect, $queryFrom, true);
	}
    
	function consultarTelaConsulta($filtro, $querySelect, $queryFrom){
		return $this->consultarFiltro($filtro, $querySelect, $queryFrom, true);
	}
	
	function consultarFiltro($filtro, $querySelect, $queryFrom, $validaConsulta){
	
		$retorno = "";
		$isHistorico = ("S" == $filtro->cdHistorico);
	
		//flag que diz se pode consultar ou nao
		$consultar = @$_GET["consultar"];
	
		if($consultar == "S" || !$validaConsulta){
			
			//removeObjetoSessao($filtro->nmFiltro);
			
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
			
			//putObjetoSessao($filtro->nmFiltro, $filtro);
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
    
	function incluirQueryVO($voEntidade){		
		$arrayAtribRemover =  $voEntidade->varAtributosARemover;		
		return $this->incluirQuery($voEntidade, $arrayAtribRemover);		
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
		//echo $query; 
        $registro = $this->consultarEntidade($query, false);
        
        $retorno = $registro[0][$nmColuna];
        
        if($retorno == null)
        	$retorno = 1;
        
        return $retorno;        
	}
	
	function getProximoSequencialChaveComposta($nmColunaSq, $voEntidade){
		$arrayAtribRemover = array($nmColunaSq);
		return $this->getProximoSequencialChaveCompostaLogica($nmColunaSq, $voEntidade, $arrayAtribRemover);		
	}
	
	function getProximoSequencialChaveCompostaLogica($nmColunaSq, $voEntidade, $arrayAtribRemover){
		$isHistorico = $voEntidade->sqHist != null;		

		$arrayColunasChaveSemSq = removeColecaoAtributos($voEntidade->getAtributosChavePrimaria(), $arrayAtribRemover);						
		$query = " SELECT MAX(" . $nmColunaSq . ")+1 AS ". $nmColunaSq ." FROM " . $voEntidade->getNmTabela() . " ";
		$query.= " WHERE ";
		$query.= $voEntidade->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		
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
    
    /**
     *FUNCOES MANIPULACAO
     */
    /*function getVarComoString($param){
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
    
    function getVarComoData($param){
    	return $this->getDataSQL($param);
    }
        
    function getDataSQL($param){
        $retorno = "null";
        //echo "<br> parametro conversao data sql:".$param;
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
    }*/
        
        /**
         *FUNCOES MANIPULACAO
         *pega na bibliotecaSQL
         /*
         *@ deprecated
         */
        function getVarComoString($param){
        	return getVarComoString($param);
        }
        
        /*
         *@ deprecated
         */
        function getVarComoNumero($param){
        	return getVarComoNumero($param);
        }
        
        /*
         *@ deprecated
         */
        function getVarComoData($param){
        	return getVarComoData($param);
        }
        /*
         *@ deprecated
         */        
        function getDataSQL($param){
       		return getVarComoDataSQL($param);
        }
        
        /*
         *@ deprecated
         */        
        function getDecimalSQL($param){        
        	return getDecimalSQL($param);
        }
}	