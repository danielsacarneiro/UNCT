<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterProcLicitatorio extends filtroManter{
    
    var $nmFiltro = "filtroManterProcLicitatorio";
    static  $nmColNomePregoeiro = "NmColNomePregoeiro";
    static  $nmTabelaPregoeiro = "TAB_PESSOA_PREGOEIRO";
    
    var $cdPessoa ;    
    var $cdPregoeiro;
    var $doc;
    var $nome;
    var $cdProc;
    var $anoProc;
    var $situacao;

    var $cdContrato;
    var $tipoContrato;
    var $anoContrato;

    var $cdDemanda;
    var $anoDemanda;
    var $tpDemanda;
    var $tpDocumento;
    var $objeto;
        
    // ...............................................................
	// construtor
    
    function getFiltroFormulario(){
    	$this->cdPessoa = @$_POST[vopessoa::$nmAtrCd];
    	$this->cdPregoeiro = @$_POST[voProcLicitatorio::$nmAtrCdPregoeiro];
    	//$this->cdGestor = @$_POST[voProcLicitatorio::$nmAtrCdGestor];
    	$this->doc = @$_POST[vopessoa::$nmAtrDoc];
    	$this->nome = @$_POST[vopessoa::$nmAtrNome];
    	
    	$this->cdProc = @$_POST[voProcLicitatorio::$nmAtrCd];
    	$this->anoProc = @$_POST[voProcLicitatorio::$nmAtrAno];
    	$this->situacao = @$_POST[voProcLicitatorio::$nmAtrSituacao];
    	$this->objeto = @$_POST[voProcLicitatorio::$nmAtrObjeto];
    	
    	$this->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
    	$this->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
    	$this->tipoContrato = @$_POST[vocontrato::$nmAtrTipoContrato];
    	
    	$this->cdDemanda = @$_POST[voDemandaPL::$nmAtrCdDemanda];
    	$this->anoDemanda = @$_POST[voDemandaPL::$nmAtrAnoDemanda];
    	$this->tpDemanda = @$_POST[voDemanda::$nmAtrTipo];
    	$this->tpDocumento = @$_POST[voDocumento::$nmAtrTp];
    	
    	//isso tudo pq o filtro pode ser usado por mais de um metodo
    	//e precisa saber qual voprincipal considera,
    	//pra pegar por ex os atributos de ordenacao da tabela correta
    	$this->nmEntidadePrincipal = "voProcLicitatorio";
    	$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
    }
    	
	function getFiltroConsultaSQL(){
        $voProcLicitatorio= new voProcLicitatorio();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
		
		$nmTabelaPessoaResponsavel = SELF::$nmTabelaPregoeiro;
		
		$nmTabelaContrato= $vocontrato->getNmTabelaEntidade(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic(false);
		
		$isHistorico = $this->isHistorico;
		$nmTabela = $voProcLicitatorio->getNmTabelaEntidade($isHistorico);
		if($this->nmEntidadePrincipal != null){
			$nmTabela = $this->getVOEntidadePrincipal()->getNmTabelaEntidade($isHistorico);
		}
		
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
		
		if($this->cdDemanda != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrCd
			. " = "
					. $this->cdDemanda;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoDemanda != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrCd
			. " = "
					. $this->anoDemanda;
		
					$conector  = "\n AND ";
		}		
            				
		if($this->tpDemanda != null){
			$filtro = $filtro 
			. $conector
			. "("
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrTipo
			. " IS NULL OR "
			. $nmTabelaDemanda. "." .voDemanda::$nmAtrTipo
			. " = "
			. $this->tpDemanda
			.")";
		
					$conector  = "\n AND ";
		}
		
		if($this->cdEspecieContrato != null){
			$filtro = $filtro . $conector
					. $nmTabelaContrato. "." .vocontrato::$nmAtrCdEspecieContrato
					. " = '"
					. $this->cdEspecieContrato . "'";
					//. dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
						
			$conector  = "\n AND ";
		}
		
		if($this->situacao != null){
			//a situacao EM ANDAMENTO nao existe no PA, ele pega da demanda
			if($this->situacao == dominioSituacaoPA::$CD_SITUACAO_PA_EM_ANDAMENTO){
				$filtro = $filtro . $conector
				. $nmTabelaDemanda. "." .voDemanda::$nmAtrSituacao
				. " = "
				. dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
				;				
			}else if($this->situacao == dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO){
				$filtro = $filtro . $conector
				. "$nmTabelaDemanda." .voDemanda::$nmAtrSituacao
				. " <> "
				. dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO
				. " AND $nmTabela." .voProcLicitatorio::$nmAtrSituacao
				. " = "
				. dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO
				
				;				
			}else{
			
			$filtro = $filtro . $conector
					. $nmTabela. "." .voProcLicitatorio::$nmAtrSituacao
					. " = "
					. $this->situacao
					;
			}
						
			$conector  = "\n AND ";
		}
		
		if($this->cdProc != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voProcLicitatorio::$nmAtrCd 
			. " = "
					. $this->cdProc;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoProc != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voProcLicitatorio::$nmAtrAno
					. " = "
					. $this->anoProc;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPregoeiro != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voProcLicitatorio::$nmAtrCdPregoeiro
			. " = "
					. $this->cdPregoeiro;
		
					$conector  = "\n AND ";
		}
		
		if($this->objeto != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voProcLicitatorio::$nmAtrObjeto
			. " LIKE " . getVarComoString("%$this->objeto%");					
		
			$conector  = "\n AND ";
		}
		
		if($this->cdResponsavel!= null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaResponsavel. "." .vopessoa::$nmAtrCd
					. " = "
					. $this->cdResponsavel;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPessoa != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrCd
			. " = "
					. $this->cdPessoa;
		
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){
			$filtro = $filtro . $conector
						. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrDoc
						. "='"
						. documentoPessoa::getNumeroDocSemMascara($this->doc)
						. "'";
			
			$conector  = "\n AND ";
		}	
		
		if($this->anoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrAnoContrato
			. " = "
					. $this->anoContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->cdContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrCdContrato
			. " = "
					. $this->cdContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->tipoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrTipoContrato
			. " = "
					. getVarComoString($this->tipoContrato)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->tpDocumento != null){			
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacaoDoc
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrAnoDemanda
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrCdDemanda;						
					
						$filtro .= " AND " . $nmTabelaTramitacaoDoc. "." . voDemandaTramDoc::$nmAtrTpDoc. "="
								. getVarComoString($this->tpDocumento);
						
					$filtro .= ")\n";
		
					$conector  = "\n AND ";
		}
		
		/*if($this->isHistorico() && $this->sqHistProcLic != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voProcLicitatorio::$nmAtrSqHist
			. " = "
					. $this->sqHistPA
					;
					$conector  = "\n AND ";
		}*/
		
		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){		
		//$nmTabela = voProcLicitatorio::getNmTabelaStatic($this->isHistorico());		
		$varAtributos = array(
				voProcLicitatorio::$nmAtrCd=> "P.L.",
				voProcLicitatorio::$nmAtrAno=> "Ano",
				voProcLicitatorio::$nmAtrDhUltAlteracao=> "Dt.Alteração",
		);
		
		return $varAtributos;
	}
	

}

?>