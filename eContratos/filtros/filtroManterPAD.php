<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_funcoes ."contrato/dominioEspeciesContrato.php");

class filtroManterPAD extends filtroManter{
    
    var $nmFiltro = "filtroManterPenalidade";
    
    // ...............................................................
	// construtor
    
	function __construct() {
        parent::__construct(true);
        
        $this->cdPessoa = @$_POST[vopessoa::$nmAtrCd];
        //$this->cdGestor = @$_POST[voPAD::$nmAtrCdGestor];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->nome = @$_POST[vopessoa::$nmAtrNome];
        
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $voPAD= new voPAD();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";

        $nmTabela = $voPAD->getNmTabelaEntidade($isHistorico);
        $nmTabelaPessoa= $vopessoa->getNmTabelaEntidade(false);
        $nmTabelaContrato= $vocontrato->getNmTabelaEntidade(false);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		$filtro = $filtro . $conector
				. $nmTabelaContrato. "." .vocontrato::$nmAtrCdEspecieContrato
				. " = "
				. dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
					
				$conector  = "\n AND ";
		
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

		if($filtro != "")
			$filtro = " WHERE $filtro";

		if($this->cdAtrOrdenacao  != null){			
			$filtro = $filtro . " ORDER BY " . $nmTabela .".$this->cdAtrOrdenacao $this->cdOrdenacao";
		}
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>