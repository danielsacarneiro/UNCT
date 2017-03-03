<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_vos ."vogestorpessoa.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterGestorPessoa extends filtroManter{
    
    var $nmFiltro = "filtroManterGestorPessoa";
    
    // ...............................................................
	// construtor
    
	function __construct() {
        parent::__construct(true);
        
        $this->cd = @$_POST[vogestorpessoa::$nmAtrCd];
        $this->cdGestor = @$_POST[vogestorpessoa::$nmAtrCdGestor];
        $this->doc = @$_POST[vogestorpessoa::$nmAtrDoc];
        $this->nome = @$_POST[vogestorpessoa::$nmAtrNome];        
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $voGestorPessoa= new vogestorpessoa();
		$filtro = "";
		$conector  = "";

        $nmTabela = $voGestorPessoa->getNmTabela($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->nome != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vogestorpessoa::$nmAtrNome
						. " LIKE '%"
						. utf8_encode($this->nome)
						. "%'";
			
			$conector  = "\n AND ";
		}
        
		if($this->doc != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vogestorpessoa::$nmAtrDoc
						. "='"
						. $this->doc
						. "'";
			
			$conector  = "\n AND ";
		}	

		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vogestorpessoa::$nmAtrCdGestor
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