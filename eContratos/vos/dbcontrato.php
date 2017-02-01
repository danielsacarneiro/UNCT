<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once(caminho_funcoes . "/contrato/dominioEspeciesContrato.php");
include_once (caminho_vos. "vocontrato.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_filtros."filtroManterContrato.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class dbcontrato extends dbprocesso{
    
    //transacoes multiplas
    function exemploTransacoesmultriplas(){
        //Start transaction         
        $this->retiraAutoCommit();        
        try{
            $retorno = $this->cDb->atualizar($query1);
            $retorno = $this->cDb->atualizar($query2);
            
            //End transaction           
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }        
    }
    	

	function consultarContratoPorChave($voContrato, $isHistorico){
        $nmTabela = $voContrato->getNmTabelaEntidade($isHistorico);
        
		$query = "SELECT ".$nmTabela;
        $query.= ".*, TAB1." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioInclusao;
        $query.= ", TAB2." .vousuario::$nmAtrName. " AS " . voentidade::$nmAtrNmUsuarioUltAlteracao;
        $query.= " FROM ". $nmTabela;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB1 ON ";
        $query.= "TAB1.".vousuario::$nmAtrID. "=".vocontrato::$nmAtrCdUsuarioInclusao;
        $query.= "\n LEFT JOIN ". vousuario::$nmEntidade;
        $query.= "\n TAB2 ON ";
        $query.= "TAB2.".vousuario::$nmAtrID. "=".vocontrato::$nmAtrCdUsuarioUltAlteracao;
        $query.= " WHERE ";
        $query.= $voContrato->getValoresWhereSQLChave($isHistorico);        
        
		/*$query.= $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $voContrato->cdContrato;
		$query.= " AND " . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $voContrato->anoContrato;
		$query.= " AND ". $nmTabela . "." . vocontrato::$nmAtrSqContrato . "=" . $voContrato->sq;*/
		
		//echo $query;
        return $this->consultarEntidade($query, true);
	}

	function consultarContratoMovimentacoes($voContrato){
        $nmTabela = $voContrato->getNmTabelaEntidade(false);
        $atributos = $voContrato->getAtributosMovimentacoes();
        $atributos = getColecaoEntreSeparador($atributos, ",");
        
        $nmAtributosWhere = array(
                            vocontrato::$nmAtrAnoContrato => $voContrato->anoContrato,
                            vocontrato::$nmAtrCdContrato => $voContrato->cdContrato,
                            vocontrato::$nmAtrTipoContrato => "'$voContrato->tipo'"
                            );
        
		$query = "SELECT ";
        $query.= $atributos;
        $query.= "\n FROM ".$nmTabela;        
        $query.= "\n WHERE ";
        $query.= $voContrato->getValoresWhereSQL($voContrato, $nmAtributosWhere);
        $query.= "\n ORDER BY " . vocontrato::$nmAtrDtAssinaturaContrato;
        
		//echo $query;
        return $this->consultarEntidade($query, false);
	}
    
    function incluirSQL($voContrato){
        $atributosInsert = $voContrato->getTodosAtributos();
        //var_dump ($atributosInsert);
        $arrayAtribRemover = array(
            vocontrato::$nmAtrSqContrato,
            vocontrato::$nmAtrInImportacaoContrato,
            vocontrato::$nmAtrDataPublicacaoContrato,
            vocontrato::$nmAtrDhInclusao,
            vocontrato::$nmAtrDhUltAlteracao
            );        
            
        //var_dump($arrayAtribRemover);

        $atributosInsert = removeColecaoAtributos($atributosInsert, $arrayAtribRemover);
        //var_dump ($atributosInsert);
        
        $atributosInsert = getColecaoEntreSeparador($atributosInsert, ",");
        
        //echo "<br>$atributosInsert";
        
		$query = " INSERT INTO " . $voContrato->getNmTabela() . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .=") ";		
		$query .= " \nVALUES(";
		$query .= $this->getSQLValuesInsert($voContrato);
		$query .=")";
        
        //echo $query;
							
	    return $query;        
    }    

    function getSQLValuesInsert($voContrato){
		$retorno = "";				
		$retorno.= $voContrato-> anoContrato . ",";
		$retorno.= $voContrato-> cdContrato . ",";
		$retorno.= $this-> getVarComoString($voContrato->tipo) . ",";
		$retorno.= $this-> getVarComoString($voContrato->especie) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->sqEspecie) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdEspecie) . ",";
		$retorno.= $this-> getVarComoString($voContrato->situacao) . ",";
        $retorno.= $this-> getVarComoString($voContrato->objeto) . ",";
		$retorno.= $this-> getVarComoString($voContrato->nmGestorPessoa) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdGestorPessoa) . ",";
		$retorno.= $this-> getVarComoString($voContrato->gestor) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdGestor) . ",";
		$retorno.= $this-> getVarComoString($voContrato->procLic) . ",";
		$retorno.= $this-> getVarComoString($voContrato->modalidade) . ",";
        $retorno.= $this-> getDataSQL($voContrato->dtPublicacao) . ",";
		$retorno.= $this-> getDataSQL($voContrato->dtAssinatura) . ",";
		$retorno.= $this-> getDataSQL($voContrato->dtVigenciaInicial) . ",";
		$retorno.= $this-> getDataSQL($voContrato->dtVigenciaFinal) . ",";
		$retorno.= $this-> getVarComoString($voContrato->contratada) . ",";
		$retorno.= $this-> getVarComoString($voContrato->docContratada) . ",";
		$retorno.= $this-> getVarComoString($voContrato->empenho) . ",";
		$retorno.= $this-> getVarComoString($voContrato->tpAutorizacao) . ",";
		$retorno.= $this-> getVarComoNumero($voContrato->cdAutorizacao) . ",";
        $retorno.= $this-> getVarComoString($voContrato->licom) . ",";
		$retorno.= $this-> getVarComoString($voContrato->obs) . ",";		
		$retorno.= $this-> getDecimalSQL($voContrato->vlGlobal) . ",";
		$retorno.= $this-> getDecimalSQL($voContrato->vlMensal) . ",";
        $retorno.= $this-> getDataSQL($voContrato->dtProposta);
        
        $retorno.= $voContrato->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }    

    function getSQLValuesUpdate($voContrato){        
        $retorno = "";
        $sqlConector = "";
        
        if($voContrato->tipo != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrTipoContrato . " = " . $this->getVarComoString($voContrato->tipo);
            $sqlConector = ",";
        }
        
        if($voContrato->especie != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrEspecieContrato . " = " . $this->getVarComoString($voContrato->especie);
            $sqlConector = ",";
        }

        if($voContrato->sqEspecie != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrSqEspecieContrato . " = " . $this->getVarComoNumero($voContrato->sqEspecie);
            $sqlConector = ",";
        }
        
        if($voContrato->cdEspecie != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrCdEspecieContrato . " = " . $this->getVarComoNumero($voContrato->cdEspecie);
            $sqlConector = ",";
        }

        if($voContrato->modalidade != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrModalidadeContrato . " = " . $this->getVarComoString($voContrato->modalidade);
            $sqlConector = ",";
        }
               
        if($voContrato->contratada != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrContratadaContrato . " = " . $this->getVarComoString($voContrato->contratada);
            $sqlConector = ",";
        }

        if($voContrato->docContratada != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDocContratadaContrato . " = " . $this->getVarComoString($voContrato->docContratada);
            $sqlConector = ",";
        }
        
        if($voContrato->gestor != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrGestorContrato . " = " . $this->getVarComoString($voContrato->gestor);
            $sqlConector = ",";
        }

        if($voContrato->cdGestor != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrCdGestorContrato . " = " . $this->getVarComoNumero($voContrato->cdGestor);
            $sqlConector = ",";
        }

        if($voContrato->nmGestorPessoa != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrGestorPessoaContrato . " = " . $this->getVarComoString($voContrato->nmGestorPessoa);
            $sqlConector = ",";
        }
        
        if($voContrato->cdGestorPessoa != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrCdGestorPessoaContrato . " = " . $this->getVarComoNumero($voContrato->cdGestorPessoa);
            $sqlConector = ",";
        }

        if($voContrato->obs != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrObservacaoContrato . " = " . $this->getVarComoString($voContrato->obs);
            $sqlConector = ",";
        }

        if($voContrato->objeto != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrObjetoContrato . " = " . $this->getVarComoString($voContrato->objeto);
            $sqlConector = ",";
        }

        if($voContrato->procLic != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrProcessoLicContrato . " = " . $this->getVarComoString($voContrato->procLic);
            $sqlConector = ",";
        }

        if($voContrato->empenho != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrNumEmpenhoContrato . " = " . $this->getVarComoString($voContrato->empenho);
            $sqlConector = ",";
        }

        if($voContrato->tpAutorizacao != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrTipoAutorizacaoContrato . " = " . $this->getVarComoString($voContrato->tpAutorizacao);
            $sqlConector = ",";
        }

        if($voContrato->cdAutorizacao != null){
        	$retorno.= $sqlConector . vocontrato::$nmAtrCdAutorizacaoContrato . " = " . $this->getVarComoNumero($voContrato->cdAutorizacao);
        	$sqlConector = ",";
        }
        
        if($voContrato->licom != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrInLicomContrato . " = " . $this->getVarComoString($voContrato->licom);
            $sqlConector = ",";
        }
					
        if($voContrato->dtVigenciaInicial != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDtVigenciaInicialContrato . " = " . $this->getDataSQL($voContrato->dtVigenciaInicial);
            $sqlConector = ",";
        }
        if($voContrato->dtVigenciaFinal != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDtVigenciaFinalContrato . " = " . $this->getDataSQL($voContrato->dtVigenciaFinal);
            $sqlConector = ",";
        }
        if($voContrato->dtAssinatura != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDtAssinaturaContrato . " = " . $this->getDataSQL($voContrato->dtAssinatura);
            $sqlConector = ",";
        }
        if($voContrato->dtPublicacao != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDtPublicacaoContrato . " = " . $this->getDataSQL($voContrato->dtPublicacao);
            $sqlConector = ",";
        }
        if($voContrato->vlMensal != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrVlMensalContrato . " = " . $this->getDecimalSQL($voContrato->vlMensal);
            $sqlConector = ",";
        }
        if($voContrato->vlGlobal != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrVlGlobalContrato . " = " . $this->getDecimalSQL($voContrato->vlGlobal);
            $sqlConector = ",";
        }
        if($voContrato->situacao != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrCdSituacaoContrato . " = " . $this->getVarComoString($voContrato->situacao);
            $sqlConector = ",";
        }
        if($voContrato->dtProposta != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrDtProposta . " = " . $this->getDataSQL($voContrato->dtProposta);
            $sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $voContrato->getSQLValuesUpdate();
		        
		return $retorno;                
    }
    
    /**
     *FUNCOES DE IMPORTACAO EXCLUSIVA
     */
    
	function incluirContratoImport($tipo, $linha){
        $voContrato = new vocontrato();
        
        $atributosInsert = $voContrato->getTodosAtributos();        
        $arrayAtribRemover = array(
            vocontrato::$nmAtrDhInclusao,
            vocontrato::$nmAtrDhUltAlteracao,
            vocontrato::$nmAtrCdUsuarioInclusao,
            vocontrato::$nmAtrCdUsuarioUltAlteracao
            );                    
        //var_dump($arrayAtribRemover);
        $atributosInsert = removeColecaoAtributos($atributosInsert, $arrayAtribRemover);        
        $atributosInsert = getColecaoEntreSeparador($atributosInsert, ",");
        
		$query = " INSERT INTO " . $voContrato->getNmTabela() . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .=") ";
		
		$query .= " \nVALUES(";
		$query .= $this->getAtributosInsertImportacaoPlanilha($tipo, $linha);
		$query .=")";
		
		//echo $query;		
		$retorno = $this->cDb->atualizarImportacao($query);					
	    return $retorno;		
	}	
    
	function getAtributosInsertImportacaoPlanilha($tipo, $linha){			
		//$tipo = $linha["A"];	
		$numero = $linha["B"];		
		$ano = $linha["B"];
		
		$especie = $linha["C"];
		$objeto = $linha["D"];
		$gestorPessoa = $linha["E"];
        
        if($tipo == "C"){
            //contrato
            $gestor  = $linha["F"];
            
            $valorGlobal = $linha["H"];
            $valorMensal = $linha["G"];
            $processoLic = $linha["I"];
            $modalidadeLic = $linha["J"];
            $dtAssinatura  = $linha["K"];
            $dataPublic  = $linha["L"];
            $nomeContratada  = $linha["M"];
            $docContratada   = $linha["N"];
            
            $dtVigenciaInicio   = $linha["O"];
            $dtVigenciaFim   = $linha["P"];
            $sqEmpenho   = $linha["R"];
            $tpAutorizacao   = $linha["S"];
            $inLicom   = $linha["T"];	
            $obs = $linha["U"];            
        }else{
            //convenio
            $gestor  = null;            
            $valorGlobal = $linha["F"];
            $valorMensal = null;
            $processoLic = $linha["G"];
            $modalidadeLic = $linha["H"];
            $dtAssinatura  = $linha["I"];
            $dataPublic  = $linha["J"];
            $nomeContratada  = $linha["K"];
            $docContratada   = $linha["L"];
            
            $dtVigenciaInicio   = $linha["M"];
            $dtVigenciaFim   = $linha["N"];
            $sqEmpenho   = $linha["P"];
            $tpAutorizacao   = null;
            $inLicom   = $linha["Q"];	
            $obs = $linha["R"];            
        }

        //recupera o sequencial da especie (aditivo, apostilamento) quando existir        
        $sqEspecie = substr($especie, 0, 3);
        $indiceEspecie = getIndicePosteriorAoUltimoNumeroAPartirDoComeco($sqEspecie);
        $sqEspecie = substr($sqEspecie, 0, $indiceEspecie);
        //recuperar a especie propriamente dita
        $cdEspecie = $this->getCdEspecieContrato($especie);
        
        $situacao = "null";
        $dtProposta = "null";
        $cdGestor = "null";
        $cdGestorPessoa = "null";
        
        $importação = "'S'";
        $dtPublic = $this->getDataPublicacaoImportacao($dataPublic);
        //trata o valor do inlicom
        if($inLicom == "OK")
            $inLicom = "S";
        else
            $inLicom = "N";        
		        
        //CUIDADO COM A ORDEM
        //DEVE ESTAR IGUAL A vocontrato->getAtributosFilho()
		$retorno = "";				
		$retorno.= $this->getAnoLinhaImportacao($ano) . ",";
		$retorno.= $this-> getNumeroLinhaImportacao($numero) . ",";
		$retorno.= $this-> getVarComoString($tipo) . ",";
		$retorno.= $this-> getVarComoString($especie) . ",";
        $retorno.= $this-> getVarComoNumero($sqEspecie) . ",";
        $retorno.= $this-> getVarComoNumero($cdEspecie) . ",";
        $retorno.= $situacao . ",";
		$retorno.= $this-> getVarComoString($objeto) . ",";
		$retorno.= $this-> getVarComoString($gestorPessoa) . ",";
        $retorno.= $this-> getVarComoNumero($cdGestorPessoa) . ",";
		$retorno.= $this-> getVarComoString($gestor) . ",";
        $retorno.= $this-> getVarComoNumero($cdGestor) . ",";
		$retorno.= $this-> getVarComoString($processoLic) . ",";
		$retorno.= $this-> getVarComoString($modalidadeLic) . ",";
		$retorno.= $this-> getVarComoString($dataPublic) . ",";        
        $retorno.= $this-> getVarComoString($dtPublic) . ",";
		$retorno.= $this-> getDataLinhaImportacao($dtAssinatura) . ",";
		$retorno.= $this-> getDataLinhaImportacao($dtVigenciaInicio) . ",";
		$retorno.= $this-> getDataLinhaImportacao($dtVigenciaFim) . ",";
		$retorno.= $this-> getVarComoString($nomeContratada) . ",";
		$retorno.= $this-> getVarComoString($docContratada) . ",";
		$retorno.= $this-> getVarComoString($sqEmpenho) . ",";
		$retorno.= $this-> getVarComoString($tpAutorizacao) . ",";
		$retorno.= $this-> getVarComoNumero($this->getCdAutorizacao($tpAutorizacao)) . ",";		
        $retorno.= $this-> getVarComoString($inLicom) . ",";
        $retorno.= $importação . ",";
		$retorno.= $this-> getVarComoString($obs) . ",";		
		$retorno.= $this-> getDecimalLinhaImportacao($valorGlobal) . ",";
		$retorno.= $this-> getDecimalLinhaImportacao($valorMensal) . ",";
        $retorno.= $dtProposta; 
		
		return $retorno;				
	}
           
	function getCdAutorizacao($tipoAutorizacao){
		include_once(caminho_funcoes."contrato/dominioAutorizacao.php");
	
		$retorno = dominioAutorizacao::$CD_AUTORIZ_NENHUM;
		
		$isPGE = mb_stripos($tipoAutorizacao, "pge") !== false;		
		$isSAD = mb_stripos($tipoAutorizacao, "sad") !== false;		
		$isGOV = mb_stripos($tipoAutorizacao, "gov") !== false;

		
		if($isPGE && $isSAD && $isGOV)
			$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD_PGE_GOV;
		else if($isPGE && $isSAD)
			$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD_PGE;
		else if($isPGE)
			$retorno = dominioAutorizacao::$CD_AUTORIZ_PGE;
		else if($isSAD)
			$retorno = dominioAutorizacao::$CD_AUTORIZ_SAD;
					
		
		return $retorno;
		
	}
    
    function getCdEspecieContrato($param){
                        
        $retorno = null;
        $dominioEspecies = new dominioEspeciesContrato();
        $colecao = $dominioEspecies->getDominioImportacaoPlanilha();
        
        $tamanho = count($colecao);
        //echo $tamanho . "<br>";
        //var_dump($colecao) . "<br>";
        $chaves = array_keys($colecao);
        
        //echo "<br>especie:$param";
        
        for($i=0; $i<$tamanho;$i++){                        
            $chave = $chaves[$i];
            $especie = $colecao[$chave];
            
            $mystring = utf8_encode($especie);
            //$mystring = $especie;
            //echo "<br>$mystring X $param";
            
            //verifica se eh o tipo da especie em questao            
            if(existeStr1NaStr2ComSeparador($param, $mystring)){
                $retorno = $chave;
                break;
            }
        }
        
        if($retorno != null){
            echo "<br>EXISTE<br>";
        }
        else{
            echo "<br>NAO EXISTE<br>";
        }
        
        //$mystring = utf8_decode($param);
        return $retorno;
    }
    
    function getDataPublicacaoImportacao($param){
        //echo "<br> valor a converter: $param";
        
        $retorno = null;
        if($param != null){            
            $ano = 0;            
            $indiceSeparadorAno = getIndiceBarraOuPonto($param);
            
            //echo "<br> tamanho da string" . strlen($param);
            //echo "<br> indice separador" . $indiceSeparadorAno;
            
            $ano =  substr($param,$indiceSeparadorAno + 1,4);
            if($ano < 2000){
                //echo "<br> tem 2 digitos";
                $ano = $ano + 2000;
            }/*else{
                echo "<br> tem 4 digitos";
            }*/
            
            $mes =  substr($param,$indiceSeparadorAno-2,2);
            $dia =  substr($param,$indiceSeparadorAno-5,2);
            
            $res = checkdate($mes,$dia,$ano);
            if ($res == 1){               
               $retorno = $ano . "-" . "$mes" . "-". $dia;
            }             
            /*try{
                $ano =  substr($param,$indiceSeparadorAno,4);
                echo "<br> tem 4 digitos";
            }catch(Exception $e){
                echo "<br> tem 2 digitos";
                $ano =  substr($param,$indiceSeparadorAno,2);
                $ano = $ano + 2000;
            }*/
            
            //echo "<BR> IMPRIMINDO A DATA PUBLICACAO SQL: " . $retorno;
        }
                    
       return $retorno;
    }
    
    function getNumeroLinhaImportacao($param){
        $retorno = "null";
        if($param != null)
            $retorno = substr($param,0,3);	
    
        return $retorno;	
    }

    function getAnoLinhaImportacao($param){
        $retorno = "null";
        if($param != null)
            $retorno = substr($param,4,2) + 2000;
    
        return $retorno;	
    }
    
    function getDataLinhaImportacao($param){
        $retorno = "null";
        
        if($param != null)
            $retorno = "'" . (substr($param,6,4) + 2000) . "-" . substr($param,0,2) . "-" . substr($param,3,2). "'";	
        return $retorno;
    }
    
    function getDecimalLinhaImportacao($param){
        $retorno = "null";
            
        $valor = str_replace(",", "", "$param");
        $valor = str_replace(" ", "", "$valor");
        
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
?>