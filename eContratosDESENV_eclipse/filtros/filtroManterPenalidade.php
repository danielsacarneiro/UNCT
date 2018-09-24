<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPenalidade extends filtroManter{
    
    var $nmFiltro = "filtroManterPenalidade";
    
    var $cdPessoa ;
    var $doc;
    var $nome;
    var $cdPA;
    var $anoPA;

    var $cdContrato;
    var $tipoContrato;
    var $anoContrato;
    
    var $nmTabelaPessoaContrato = "TAB_PESSOA_CONTRATO";    
    var $nmColNomePessoaContrato = "NmColPessoaContrato";
    var $tipoPenalidade;
    
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
        
        $this->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
        $this->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
        $this->tipoContrato = @$_POST[vocontrato::$nmAtrTipoContrato];
        $this->tipoPenalidade = @$_POST[voPenalidadePA::$nmAtrTipo];
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
		
		$isHistorico = $this->isHistorico;
		$nmTabela = $voPA->getNmTabelaEntidade($isHistorico);
		$nmTabelaPenalidade = voPenalidadePA::getNmTabelaStatic($isHistorico);
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
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPA::$nmAtrSituacao
					. " = "
					. $this->situacao
					;
						
			$conector  = "\n AND ";
		}
		
		if($this->tipoPenalidade != null){
			$filtro = $filtro . $conector
			. $nmTabelaPenalidade. "." .voPenalidadePA::$nmAtrTipo
			. " = "
					. getVarComoString($this->tipoPenalidade)
					;
		
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
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voPenalidadePA::$nmAtrSq=> "Penalidade",
				voPenalidadePA::getNmTabelaStatic($this->isHistorico).".".voPA::$nmAtrCdPA=> "PA",
				vocontrato::getNmTabelaStatic($this->isHistorico).".".vocontrato::$nmAtrCdContrato => "Contrato"
		);
		return $varAtributos;
	}
	

}

?>