<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_funcoes ."contrato/dominioEspeciesContrato.php");

class filtroManterPenalidade extends filtroManter{
    
    var $nmFiltro = "filtroManterPenalidade";
    
    // ...............................................................
	// construtor
    
	function __construct() {
        parent::__construct(true);
        
        $this->cd = @$_POST[vopessoa::$nmAtrCd];
        //$this->cdGestor = @$_POST[vopenalidade::$nmAtrCdGestor];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->nome = @$_POST[vopessoa::$nmAtrNome];
        
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $vopenalidade= new vopenalidade();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";

        $nmTabela = $vopenalidade->getNmTabela($isHistorico);
        $nmTabelaPessoa= $vopessoa->getNmTabela($isHistorico);
        $nmTabelaContrato= $vocontrato->getNmTabela($isHistorico);
        
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
		
		if($this->cd != null){
			$filtro = $filtro . $conector
						. $nmTabelaPessoa. "." .vopessoa::$nmAtrCd
						. " = "
						. $this->cd;
			
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
						//. $nmTabela. "." .vopenalidade::$nmAtrCdGestor
						. "='"
						. $this->cdGestor
						. "'";
			
			$conector  = "\n AND ";
		}	

		if($filtro != "")
			$filtro = " WHERE $filtro";

		if($this->cdAtrOrdenacao  != null){
			
			$filtro = $filtro . " ORDER BY $this->cdAtrOrdenacao $this->cdOrdenacao";
		}
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>