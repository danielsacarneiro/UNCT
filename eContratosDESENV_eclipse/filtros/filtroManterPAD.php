<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_funcoes ."contrato/dominioEspeciesContrato.php");

class filtroManterPAD extends filtroManter{
    
    var $nmFiltro = "filtroManterPenalidade";
    
    var $cdPessoa ;    
    var $doc;
    var $nome;
    var $cdPA;
    var $anoPA;
    var $situacao;
    var $cdEspecieContrato;
    
    var $nmEntidadePrincipal;
    
    // ...............................................................
	// construtor
    
	function __construct() {
        parent::__construct(true);
        
        $this->cdPessoa = @$_POST[vopessoa::$nmAtrCd];
        //$this->cdGestor = @$_POST[voPAD::$nmAtrCdGestor];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->nome = @$_POST[vopessoa::$nmAtrNome];
        
        $this->cdPA = @$_POST[voPAD::$nmAtrCdPA];
        $this->anoPA = @$_POST[voPAD::$nmAtrAnoPA];
        $this->situacao = @$_POST[voPAD::$nmAtrSituacao];
        $this->cdEspecieContrato = @$_POST[vocontrato::$nmAtrCdEspecieContrato];
    }
    	
	function getFiltroConsultaSQL($isHistorico){
        $voPAD= new voPAD();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
		
		$nmTabelaPessoa= $vopessoa->getNmTabelaEntidade(false);
		$nmTabelaContrato= $vocontrato->getNmTabelaEntidade(false);
		
		$nmTabela = $voPAD->getNmTabelaEntidade($isHistorico);
		if($this->nmEntidadePrincipal != null){
			$voentidade = new voentidade();
			$class = $this->nmEntidadePrincipal;
			$voentidade = new $class(); 
			$nmTabela = $voentidade->getNmTabelaEntidade($isHistorico);
		}
		
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            				
		if($this->cdEspecieContrato != null){
			$filtro = $filtro . $conector
					. $nmTabelaContrato. "." .vocontrato::$nmAtrCdEspecieContrato
					. " = "
					. $this->cdEspecieContrato;
					//. dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
						
			$conector  = "\n AND ";
		}
		
		if($this->situacao != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPAD::$nmAtrSituacao
					. " = "
					. $this->situacao
					;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPA != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voPAD::$nmAtrCdPA
			. " = "
					. $this->cdPA;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoPA != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPAD::$nmAtrAnoPA
					. " = "
					. $this->anoPA;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPessoa != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa. "." .vopessoa::$nmAtrCd
			. " = "
					. $this->cdPessoa;
						
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){
			$filtro = $filtro . $conector
						. $nmTabelaPessoa. "." .vopessoa::$nmAtrDoc
						. "='"
						. $this->doc
						. "'";
			
			$conector  = "\n AND ";
		}	

		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						//. $nmTabela. "." .voPAD::$nmAtrCdGestor
						. "='"
						. $this->cdGestor
						. "'";
			
			$conector  = "\n AND ";
		}	

		//finaliza o filtro
		$filtro = parent::getFiltroConsultaSQL($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>