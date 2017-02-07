<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_vos ."vopessoa.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPessoa extends filtroManter{
    
    var $nmFiltro = "filtroManterPessoa";
    
    // ...............................................................
	// construtor
    
	function __construct() {
        parent::__construct(true);
        
        $this->cd = @$_POST[vopessoa::$nmAtrCd];
        //$this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->nome = @$_POST[vopessoa::$nmAtrNome];        
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $voPessoa= new vopessoa();
		$filtro = "";
		$conector  = "";

        $nmTabela = $voPessoa->getNmTabela($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->cd != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrCd
						. " = "
						. $this->cd;
			
			$conector  = "\n AND ";
		}
        
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrDoc
						. "='"
						. $this->doc
						. "'";
			
			$conector  = "\n AND ";
		}	

		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						//. $nmTabela. "." .vopessoa::$nmAtrCdGestor
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