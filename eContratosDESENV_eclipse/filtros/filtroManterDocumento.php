<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_vos ."voDocumento.php");

class filtroManterDocumento extends filtroManter{
    
    public $nmFiltro = "filtroManterDocumento";
    
    	var $sq = "";
    	var $cdSetor = "";
    	var $ano = "";
    	var $tp = "";
    	var $link = "";
    	var $vocontrato;
    // ...............................................................
	// construtor	
	//function __construct() {
	function getFiltroFormulario(){
        //parent::__construct(true);        
        $this->sq = @$_POST[voDocumento::$nmAtrSq];
        $this->cdSetor = @$_POST[voDocumento::$nmAtrCdSetor];
        $this->ano = @$_POST[voDocumento::$nmAtrAno];
        if(!isset($_POST[voDocumento::$nmAtrAno])){
        	$this->ano = getAnoHoje();
        }
        
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
        $nmTabelaDemandaContrato = voDemandaContrato::getNmTabelaStatic(false);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		//$this->vocontrato = new vocontrato();
		if($this->vocontrato->anoContrato != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemandaContrato." .voDemandaContrato::$nmAtrAnoContrato
			. " = "
					. $this->vocontrato->anoContrato
					;
						
					$conector  = "\n AND ";
		
		}

		if($this->vocontrato->cdContrato != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemandaContrato." .voDemandaContrato::$nmAtrCdContrato
			. " = "
					. $this->vocontrato->cdContrato
					;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->vocontrato->tipo != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemandaContrato." .voDemandaContrato::$nmAtrTipoContrato
			. " = "
					. getVarComoString($this->vocontrato->tipo)
					;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->vocontrato->cdEspecie != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemandaContrato." .voDemandaContrato::$nmAtrCdEspecieContrato
			. " = "
					. getVarComoString($this->vocontrato->cdEspecie)
					;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->vocontrato->sqEspecie != null){
			$filtro = $filtro . $conector
			. "$nmTabelaDemandaContrato." .voDemandaContrato::$nmAtrSqEspecieContrato
			. " = "
					. $this->vocontrato->sqEspecie
					;
		
					$conector  = "\n AND ";
		
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
		$filtro = parent::getFiltroSQL($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributoOrdenacaoAnteriorDefault(){
		$nmTabela = voDocumento::getNmTabelaStatic($this->isHistorico);
		$retorno = $nmTabela . "." . voDocumento::$nmAtrAno . " " . $this->cdOrdenacao;
		return $retorno;
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