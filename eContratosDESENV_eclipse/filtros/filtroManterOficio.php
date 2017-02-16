<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voOficio.php");

class filtroManterOficio extends filtroManter{
    
    public static $nmFiltro = "filtroManterOficio";
    
    	var $sq = "";
    	var $cdSetor = "";
    	var $ano = "";
    // ...............................................................
	// construtor	
	function __construct() {
        parent::__construct(true);
        
        $this->sq = @$_POST[voOficio::$nmAtrSq];
        $this->cdSetor = @$_POST[voOficio::$nmAtrCdSetor];
        $this->ano = @$_POST[voOficio::$nmAtrAno];
              
		if($this->cdOrdenacao == null){        	
        	$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
        }
	}
    	
	function getFiltroConsultaSQL($isHistorico){
        $voOficio= new voOficio();
		$filtro = "";
		$conector  = "";

        $nmTabela = $voOficio->getNmTabelaEntidade($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->sq != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .voOficio::$nmAtrSq
						. " = "
						. $this->sq
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->cdSetor != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voOficio::$nmAtrCdSetor
			. " = "
					. $this->cdSetor
					;
						
					$conector  = "\n AND ";
		
		}
		
		if($this->ano != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voOficio::$nmAtrAno
			. " = "
					. $this->ano
					;
						
					$conector  = "\n AND ";
		
		}
		
		//finaliza o filtro
		$filtro = parent::getFiltroConsultaSQL($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>