<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDocumento.php");

class filtroManterDocumento extends filtroManter{
    
    public static $nmFiltro = "filtroManterDocumento";
    
    	var $sq = "";
    	var $cdSetor = "";
    	var $ano = "";
    	var $tp = "";
    	var $link = "";
    // ...............................................................
	// construtor	
	function __construct() {
        parent::__construct(true);
        
        $this->sq = @$_POST[voDocumento::$nmAtrSq];
        $this->cdSetor = @$_POST[voDocumento::$nmAtrCdSetor];
        $this->ano = @$_POST[voDocumento::$nmAtrAno];
        $this->tp= @$_POST[voDocumento::$nmAtrTp];
        $this->link= @$_POST[voDocumento::$nmAtrLink];
        
        $this->nmEntidadePrincipal = (new voDocumento())->getNmClassVO();        
              
		if($this->cdOrdenacao == null){        	
        	$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
        }
	}
    	
	function getFiltroConsultaSQL(){
        $voDocumento= new voDocumento();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico;
        $nmTabela = $voDocumento->getNmTabelaEntidade($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->sq != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .voDocumento::$nmAtrSq
						. " = "
						. $this->sq
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->cdSetor != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDocumento::$nmAtrCdSetor
			. " = "
					. $this->cdSetor
					;
						
					$conector  = "\n AND ";
		
		}
		
		if($this->ano != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDocumento::$nmAtrAno
			. " = "
					. $this->ano
					;
						
					$conector  = "\n AND ";
		
		}
		
		if($this->tp != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDocumento::$nmAtrTp
			. " = '"
			. $this->tp
			. "'"
			;
		
			$conector  = "\n AND ";		
		}
		
		if($this->link != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDocumento::$nmAtrLink
			. " LIKE '%"
			//. substituirCaracterSQLLike($this->link)
			. $this->link
			. "%'"
			;		
			$conector  = "\n AND ";		
		}
		
		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributoOrdenacaoDefault(){
		return voDocumento::getNmTabelaStatic($this->isHistorico) . "." . voDocumento::$nmAtrDhUltAlteracao . " " . constantes::$CD_ORDEM_DECRESCENTE;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voDocumento::$nmAtrSq => "Número",
				voDocumento::$nmAtrDhUltAlteracao => "Data",
				voDocumento::$nmAtrCdSetor=> "Setor",
				voDocumento::$nmAtrAno => "Ano",
				voDocumento::$nmAtrTp => "Tp.Doc"
		);
		return $varAtributos;
	}	

}

?>