<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once(caminho_funcoes . "/contrato/dominioEspeciesContrato.php");
include_once (caminho_vos. "vocontrato.php");
include_once (caminho_vos. "vousuario.php");
include_once (caminho_filtros."filtroManterContrato.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."biblioteca_htmlArquivo.php");
include_once(caminho_util."DocumentoPessoa.php");

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
    
    
    function consultarFiltroManterContrato($voentidade, $filtro){    	
    	$isArquivo = ("S" == $filtro->cdConsultarArquivo);
    	
    	if($isArquivo){
    		return "";
    	}else{
    		$groupby = array(vocontrato::$nmAtrSqContrato);    		
    		$filtro->groupby = $groupby;
    		
    		$retorno = $this->consultarFiltroManter($filtro, true);    		
    		
    		return $retorno;
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
		$retorno.= $voContrato->anoContrato . ",";
		$retorno.= $voContrato->cdContrato . ",";
		$retorno.= $this-> getVarComoString($voContrato->tipo) . ",";
		$retorno.= $this-> getVarComoString($voContrato->especie) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->sqEspecie) . ",";
        $retorno.= $this-> getVarComoString($voContrato->cdEspecie) . ",";
		$retorno.= $this-> getVarComoString($voContrato->situacao) . ",";
        $retorno.= $this-> getVarComoString($voContrato->objeto) . ",";
		$retorno.= $this-> getVarComoString($voContrato->nmGestorPessoa) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdPessoaGestor) . ",";
		$retorno.= $this-> getVarComoString($voContrato->gestor) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdGestor) . ",";
		$retorno.= $this-> getVarComoString($voContrato->procLic) . ",";
		$retorno.= $this-> getVarComoString($voContrato->modalidade) . ",";
		
        $retorno.= $this-> getVarComoString($voContrato->dataPublicacao) . ",";
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
        $retorno.= $this-> getVarComoString($voContrato->importacao) . ",";
		$retorno.= $this-> getVarComoString($voContrato->obs) . ",";		
		$retorno.= $this-> getDecimalSQL($voContrato->vlGlobal) . ",";
		$retorno.= $this-> getDecimalSQL($voContrato->vlMensal) . ",";
        $retorno.= $this-> getDataSQL($voContrato->dtProposta) . ",";
        $retorno.= $this-> getVarComoNumero($voContrato->cdPessoaContratada) . ",";
        $retorno.= $this-> getVarComoString($voContrato->linkDoc);
        
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
            $retorno.= $sqlConector . vocontrato::$nmAtrCdEspecieContrato . " = " . $this->getVarComoString($voContrato->cdEspecie);
            $sqlConector = ",";
        }

        if($voContrato->modalidade != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrModalidadeContrato . " = " . $this->getVarComoString($voContrato->modalidade);
            $sqlConector = ",";
        }
               
        if($voContrato->cdPessoaContratada != null){
        	$retorno.= $sqlConector . vocontrato::$nmAtrCdPessoaContratada . " = " . $this->getVarComoNumero($voContrato->cdPessoaContratada);
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
        
        if($voContrato->cdPessoaGestor != null){
            $retorno.= $sqlConector . vocontrato::$nmAtrCdPessoaGestorContrato. " = " . $this->getVarComoNumero($voContrato->cdPessoaGestor);
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
        if($voContrato->linkDoc != null){
        	$retorno.= $sqlConector . vocontrato::$nmAtrLinkDoc . " = " . $this->getVarComoString($voContrato->linkDoc);
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
        /*$arrayAtribRemover = array(
        	vocontrato::$nmAtrSqContrato,
        	vocontrato::$nmAtrDhInclusao,
            vocontrato::$nmAtrDhUltAlteracao,
        	vocontrato::$nmAtrDataPublicacaoContrato,
        	vocontrato::$nmAtrInImportacaoContrato
            );*/                    

        $arrayAtribRemover = array(
        		vocontrato::$nmAtrSqContrato,
        		vocontrato::$nmAtrDhInclusao,
        		vocontrato::$nmAtrDhUltAlteracao
        );
        
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

		try{
			//tenta incluir
			$retorno = $this->cDb->atualizarImportacao($query);
		}catch(Exception $e){
			echo "<BR> ERRO INCLUSAO. <BR>";
			$msgErro = $e->getMessage();
			echo "<BR>" . $msgErro . "<BR>";
				
			$query = "";
			//se der pau, vai alterar
			//$retorno = $this->cDb->atualizarImportacao($query);				
		}
			
	    return $retorno;		
	}	
    
	
	function getAtributosInsertImportacaoPlanilha($tipo, $linha){
		
		$voContrato = $this->getVOImportacaoPlanilha($tipo, $linha);
		
		$retorno = $this->getSQLValuesInsert($voContrato);		
		
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
    
    //recebe tambem o objeto porque as vezes a informacao esta nele
    //quando a informacao nao estiver na especie, ele tenta no objeto 
    function getCdEspecieContrato($paramEspecie, $objeto){
                        
        $retorno = null;
        $dominioEspecies = new dominioEspeciesContrato();
        $colecao = $dominioEspecies->getDominioImportacaoPlanilha();
        
        $tamanho = count($colecao);
        //echo $tamanho . "<br>";
        //var_dump($colecao) . "<br>";
        $chaves = array_keys($colecao);
        
        //echo "<br>especie:$paramEspecie";
        
        for($i=0; $i<$tamanho;$i++){                        
            $chave = $chaves[$i];
            $especie = $colecao[$chave];
            
            $mystring = utf8_encode($especie);
            //$mystring = $especie;
            //echo "<br>$mystring X $paramEspecie";
            
            //verifica se eh o tipo da especie em questao            
            if(existeStr1NaStr2ComSeparador($paramEspecie, $mystring)){
                $retorno = $chave;
                break;
            }
        }
        
        if($retorno != null){
            echo "<br>EXISTE<br>";
        }
        else{
        	//se nao conseguiu na especie, tenta no objeto
        	if($objeto != null){
        		$retorno = $this->getCdEspecieContrato($objeto, null);
        	}else{
            	echo "<br>NAO EXISTE<br>";
        	}
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
               //$retorno = $ano . "-" . "$mes" . "-". $dia;
               $retorno = $dia  . "/" . "$mes" . "/". $ano;
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
    
    /*function getDataLinhaImportacao($param){
        $retorno = "null";
        
        if($param != null)
            $retorno = "'" . (substr($param,6,4) + 2000) . "-" . substr($param,0,2) . "-" . substr($param,3,2). "'";	
        return $retorno;
    }*/
    
    function getDataLinhaImportacao($param){
    	$retorno = "null";    
    	if($param != null && $param != ""){
    		//$retorno = "'" . substr($param,3,2) . "/" . substr($param,0,2) . "/" . (substr($param,6,4) + 2000). "'";
    		$retorno = substr($param,3,2) . "/" . substr($param,0,2) . "/" . (substr($param,6,4) + 2000);
    	}
    	return $retorno;
    }
    
   function getDecimalLinhaImportacao($param){
        $retorno = "null";
            
        $valor = str_replace(",", "", "$param");
        $valor = str_replace(" ", "", "$valor");
                
        //echo "<br>decimal apos conversao:" . $valor;
        if(isNumero($valor)){        	        	
        	$retorno = getMoedaMascaraImportacao($param);        	 
            // echo "� NښMERO! <BR>";
        }
        //else
            //echo "NÃO ɉ NښMERO! <BR>";
            
        return $retorno;
    }
            
    function atualizarPessoasContrato(){    	  	
    	$query = "SELECT ";
    	$query.= vopessoa::getNmTabela(). "." .vopessoa::$nmAtrDoc;
    	$query.= "," . vopessoa::getNmTabela(). "." .vopessoa::$nmAtrCd;
    	$query.= "\n FROM ". vopessoa::getNmTabela();
    	$query.= "\n WHERE ". vopessoa::$nmAtrDoc . " IS NOT NULL";
    	$query.= "\n GROUP BY ". vopessoa::$nmAtrDoc;
   		$colecaoDocs = $this->consultarEntidade($query, false);
   		$tam = count($colecaoDocs);
   		   		
   		$arrayDocs = array();
   		for($i=0;$i<$tam;$i++){
   			
   			$doc = $colecaoDocs[$i][vopessoa::$nmAtrDoc];
   			$cdPessoa = $colecaoDocs[$i][vopessoa::$nmAtrCd];
   			
   			$doc = new documentoPessoa($doc);   			
   			$arrayDocs[$doc->getNumDoc()] = $cdPessoa;
   		}
   		
   		//var_dump($arrayDocs);   		
   	
   		$query = "SELECT ";
   		$query.= vocontrato::getNmTabela(). "." .vocontrato::$nmAtrSqContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrAnoContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrCdContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrTipoContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrCdEspecieContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrSqEspecieContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrDocContratadaContrato;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrCdPessoaContratada;
   		$query.= "," . vocontrato::getNmTabela(). "." .vocontrato::$nmAtrDhUltAlteracao;
   		$query.= "\n FROM ". vocontrato::getNmTabela();
   		$query.= "\n WHERE ". vocontrato::$nmAtrDocContratadaContrato . " IS NOT NULL";
   		$query.= "\n AND ". vocontrato::$nmAtrCdPessoaContratada . " IS NULL";
   		$query.= "\n ORDER BY ". vocontrato::$nmAtrSqContrato;
   		//$query.= " AND " .vocontrato::$nmAtrSqContrato . " = " . "1";
   		
   		//echo $query;
   		
   		$colecaoContratos = $this->consultarEntidade($query, false);
   		
   		$tam = count($colecaoContratos);
   		
   		$qtdRegistros = 0;
   		
   		for($i=0;$i<$tam;$i++){
   			$voContrato = new voContrato();
   			$voContrato->getDadosBanco($colecaoContratos[$i]);   			   			
   			$docContrato = new documentoPessoa($voContrato->docContratada);
   			$doc = $docContrato->getNumDoc();
   			
   			echo "<br> Documento: ". $doc;
   			echo "<br> Cd.Pessoa: " . $arrayDocs[$doc]; 
   			
   			/*$key = array_search($doc, $arrayDocs);
   			$key = in_array($doc, $arrayDocs);   			
   			
   			echo $key;*/
   			
   			if($voContrato->cdPessoaContratada == 0){   			   				
   				$voContrato->cdPessoaContratada = $arrayDocs[$doc];
   				$voContrato->cdUsuarioUltAlteracao = 1;
   				$this->alterarPorCima($voContrato);
   				
   				$qtdRegistros++;
   			}
   			   			
   			ECHO "<br> Contrato: ". $voContrato->toString();   			
   			   			
   		}
   		
   		echo "<br>quantidade registros alterados:" . $qtdRegistros;
   		 
    }
    
    function atualizarNomesCaracteresEspeciais(){    	
    	  //a ideia aqui eh colocar a atualizacao do objeto e nome contratada
    	  //pra retirar os caracterees especiais
    	;//$retorno = $this->cDb->atualizar($query);
    
    }
        
    function getVOImportacaoPlanilha($tipo, $linha){
    		
    	$numero = $linha["B"];
    	$ano = $linha["B"];
    	$especie = $linha["C"];
    	$dtAlteracao = $linha["D"];
    
    	$objeto = $linha["E"];
    	$gestorPessoa = $linha["F"];
    	$linkDoc = $linha[vocontrato::$nmAtrLinkDoc];
    
    	if($tipo == "C"){
    		//contrato
    		$gestor  = $linha["G"];
    
    		$valorGlobal = $linha["I"];
    		$valorMensal = $linha["H"];
    		$processoLic = $linha["J"];
    		$modalidadeLic = $linha["K"];
    		$dtAssinatura  = $linha["L"];
    		$dataPublic  = $linha["M"];
    		$nomeContratada  = $linha["N"];
    		$docContratada   = $linha["O"];
    
    		$dtVigenciaInicio   = $linha["P"];
    		$dtVigenciaFim   = $linha["Q"];
    		$sqEmpenho   = $linha["S"];
    		$tpAutorizacao   = $linha["T"];
    		$inLicom   = $linha["U"];
    		$obs = $linha["V"];
    
    	}else {
    		//convenio
    		$gestor  = null;
    		$valorGlobal = $linha["G"];
    		$valorMensal = null;
    		$processoLic = $linha["H"];
    		$modalidadeLic = $linha["I"];
    		$dtAssinatura  = $linha["J"];
    		$dataPublic  = $linha["K"];
    		$nomeContratada  = $linha["L"];
    		$docContratada   = $linha["M"];
    
    		$dtVigenciaInicio   = $linha["N"];
    		$dtVigenciaFim   = $linha["O"];
    
    		if($tipo == "V")
    			$sqEmpenho   = $linha["Q"];
    		else
    			$sqEmpenho   = $linha["P"];
    
    		$tpAutorizacao   = null;
    		$inLicom   = $linha["R"];
    		$obs = $linha["S"];
    	}
    
    	//recupera o sequencial da especie (aditivo, apostilamento) quando existir
    	$sqEspecie = substr($especie, 0, 3);
    	$indiceEspecie = getIndicePosteriorAoUltimoNumeroAPartirDoComeco($sqEspecie);
    	$sqEspecie = substr($sqEspecie, 0, $indiceEspecie);
    	//recuperar a especie propriamente dita
    	$cdEspecie = $this->getCdEspecieContrato($especie, $objeto);
    
    	$situacao = "null";
    	$dtProposta = "null";
    	$cdGestor = "null";
    	$cdPessoaGestor = "null";
    	$cdPessoaContratada= "null";
    
    	$importacao = "S";
    	$dtPublic = $this->getDataPublicacaoImportacao($dataPublic);
    	//trata o valor do inlicom
    	if($inLicom == "OK")
    		$inLicom = "S";
    	else
    		$inLicom = "N";
    
    	$retorno = new vocontrato();
    	$retorno->cdContrato = $numero;
    	$retorno->anoContrato = $ano;
    	$retorno->tipo = $tipo;
    	$retorno->especie = $especie;
    	$retorno->linkDoc = getDocLinkMascaraImportacao($linkDoc);
    
    	if($sqEspecie != null){
	    	$retorno->sqEspecie = $sqEspecie;
    	}
    	
    	$retorno->cdEspecie = $cdEspecie;
    	$retorno->objeto = $objeto;
    	$retorno->nmGestorPessoa = $gestorPessoa;
    	$retorno->gestor = $gestor;
    	$retorno->vlGlobal = $valorGlobal;
    	$retorno->vlMensal = $valorMensal;
    	$retorno->procLic = $processoLic;
    	$retorno->modalidade = $modalidadeLic;
    	$retorno->dtAssinatura = $dtAssinatura;
    	$retorno->dtPublicacao = $dtPublic;
    	$retorno->dataPublicacao = $dataPublic;
    	$retorno->contratada = $nomeContratada;
    	
    	$documento = new documentoPessoa($docContratada);
    	$retorno->docContratada = $documento->getNumDoc();
    	
    	$retorno->dtVigenciaInicial = $dtVigenciaInicio;
    	$retorno->dtVigenciaFinal = $dtVigenciaFim;
    	$retorno->empenho = $sqEmpenho;
    	$retorno->tpAutorizacao = $tpAutorizacao;
    	$retorno->licom = $inLicom;
    	$retorno->obs = $obs;
    	$retorno->importacao = $importacao;

     	//corrige os tipos de dados
    	$retorno->anoContrato = $this->getAnoLinhaImportacao($retorno->anoContrato);
    	$retorno->cdContrato = $this-> getNumeroLinhaImportacao($retorno->cdContrato);
    	$retorno->cdAutorizacao = $this->getCdAutorizacao($retorno->tpAutorizacao);
    	//echo "<br> VALOR GLOBAL: " . $retorno->vlGlobal;
    	//echo "<br> VALOR vlMensal: " . $retorno->vlMensal;
     	$retorno->vlGlobal = $this->getDecimalLinhaImportacao($retorno->vlGlobal);
    	$retorno->vlMensal = $this->getDecimalLinhaImportacao($retorno->vlMensal);
        	 
    	$retorno->dtAssinatura = $this->getDataLinhaImportacao($retorno->dtAssinatura);
    	$retorno->dtVigenciaInicial = $this->getDataLinhaImportacao($retorno->dtVigenciaInicial);
    	$retorno->dtVigenciaFinal = $this->getDataLinhaImportacao($retorno->dtVigenciaFinal);
    	$retorno->cdUsuarioInclusao = "null";
    	$retorno->cdUsuarioUltAlteracao = "null";
    	
    	/*echo "<br>data assinatura: " . $retorno->dtAssinatura; 
    	echo "<br>data dtVigenciaInicial: " . $retorno->dtVigenciaInicial;
    	echo "<br>data dtVigenciaFinal: " . $retorno->dtVigenciaFinal;*/
    
    	return $retorno;
    }

}
?>