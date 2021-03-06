<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."vogestor.php");

class filtroManterGestor extends filtroManter{
    
    public static $nmFiltro = "filtroManterGestor";
    
    // ...............................................................
	// construtor
	function __construct() {
        parent::__construct(true);
        
        $this->descricao = @$_POST[vogestor::$nmAtrDescricao];
        
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $voGestor= new vogestor();
		$filtro = "";
		$conector  = "";

        $nmTabela = $voGestor->getNmTabelaEntidade($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->descricao != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vogestor::$nmAtrDescricao
						. " LIKE '%"
						. utf8_encode($this->descricao)
						. "%'";
			
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