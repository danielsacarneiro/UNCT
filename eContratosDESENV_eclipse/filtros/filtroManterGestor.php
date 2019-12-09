<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."vogestor.php");

class filtroManterGestor extends filtroManter{
    
    public static $nmFiltro = "filtroManterGestor";
   
	var $descricao = "";
	
	// ...............................................................
	function getFiltroFormulario() {
		$this->descricao = @$_POST[vogestor::$nmAtrDescricao];
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {
        $voGestor= new vogestor();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico;
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

		//finaliza o filtro
		$filtro = parent::getFiltroSQL ( $filtro, $comAtributoOrdenacao );
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>