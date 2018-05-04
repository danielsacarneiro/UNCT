<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPA extends filtroManter{
    
    var $nmFiltro = "filtroManterPA";
    
    var $sqHistPA;
    var $cdPessoa ;    
    var $cdResponsavel ;
    var $doc;
    var $nome;
    var $cdPA;
    var $anoPA;
    var $situacao;

    var $cdContrato;
    var $tipoContrato;
    var $anoContrato;

    var $cdDemanda;
    var $anoDemanda;
    var $tpDocumento;
    
    var $nmTabelaPessoaContrato = "TAB_PESSOA_CONTRATO";
    var $nmTabelaPessoaResponsavel = "TAB_PESSOA_RESP";
    
    var $nmColNomePessoaContrato = "NmColPessoaContrato";
    var $nmColNomePessoaResponsavel = "NmColPessoaResponsavel";
    //var $nmColDtPublicacao = "nmColDtPublicacao";
    
    // ...............................................................
	// construtor
    
    function getFiltroFormulario(){
    	$this->cdPessoa = @$_POST[vopessoa::$nmAtrCd];
    	$this->cdResponsavel = @$_POST[voPA::$nmAtrCdResponsavel];
    	//$this->cdGestor = @$_POST[voPA::$nmAtrCdGestor];
    	$this->doc = @$_POST[vopessoa::$nmAtrDoc];
    	$this->nome = @$_POST[vopessoa::$nmAtrNome];
    	
    	$this->cdPA = @$_POST[voPA::$nmAtrCdPA];
    	$this->anoPA = @$_POST[voPA::$nmAtrAnoPA];
    	$this->situacao = @$_POST[voPA::$nmAtrSituacao];
    	
    	$this->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
    	$this->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
    	$this->tipoContrato = @$_POST[vocontrato::$nmAtrTipoContrato];
    	
    	$this->cdDemanda = @$_POST[voPA::$nmAtrCdDemanda];
    	$this->anoDemanda = @$_POST[voPA::$nmAtrAnoDemanda];
    	$this->tpDocumento = @$_POST[voDocumento::$nmAtrTp];
    	
    	//isso tudo pq o filtro pode ser usado por mais de um metodo
    	//e precisa saber qual voprincipal considera,
    	//pra pegar por ex os atributos de ordenacao da tabela correta
    	$this->nmEntidadePrincipal = "voPA";
    }
    	
	function getFiltroConsultaSQL(){
        $voPA= new voPA();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
		
		$nmTabelaPessoaContrato = $this->nmTabelaPessoaContrato;
		$nmTabelaPessoaResponsavel = $this->nmTabelaPessoaResponsavel;
		
		$nmTabelaContrato= $vocontrato->getNmTabelaEntidade(false);
		$nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic(false);
		$nmTabelaTramitacaoDoc = voDemandaTramDoc::getNmTabelaStatic(false);
		
		$isHistorico = $this->isHistorico;
		$nmTabela = $voPA->getNmTabelaEntidade($isHistorico);
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
			. $nmTabela. "." .voPA::$nmAtrCdDemanda
			. " = "
					. $this->cdDemanda;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoDemanda != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voPA::$nmAtrAnoDemanda
			. " = "
					. $this->anoDemanda;
		
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
				. " AND $nmTabela." .voPA::$nmAtrSituacao
				. " = "
				. dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO
				
				;				
			}else{
			
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPA::$nmAtrSituacao
					. " = "
					. $this->situacao
					;
			}
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPA != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voPA::$nmAtrCdPA
			. " = "
					. $this->cdPA;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoPA != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPA::$nmAtrAnoPA
					. " = "
					. $this->anoPA;
						
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
			//. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrNome
			. getSQLCOALESCE(array($nmTabelaPessoaContrato . "." . vopessoa::$nmAtrNome, $nmTabelaDemanda . "." . voDemanda::$nmAtrTexto))
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

		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						//. $nmTabela. "." .voPA::$nmAtrCdGestor
						. "='"
						. $this->cdGestor
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
		
		if($this->isHistorico() && $this->sqHistPA != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voPA::$nmAtrSqHist
			. " = "
					. $this->sqHistPA
					;
					$conector  = "\n AND ";
		}		
		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$nmTabelaPA = voPA::getNmTabelaStatic($this->isHistorico());
		$varAtributos = array(
				voPA::$nmAtrCdPA=> "PA",
				vocontrato::getNmTabelaStatic($this->isHistorico).".".vocontrato::$nmAtrCdContrato => "Contrato",
				$nmTabelaPA . "." . voPA::$nmAtrDhUltAlteracao=> "Dt.Alteração",
		);
		return $varAtributos;
	}
	

}

?>