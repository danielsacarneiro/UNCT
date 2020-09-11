<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterRegistroLivro extends filtroManter{
    
    var $nmFiltro = "filtroManterRegistroLivro";
    static $ID_REQ_DtRegistroInicial = "ID_REQ_DtRegistroInicial";
    static $ID_REQ_DtRegistroFinal = "ID_REQ_DtRegistroFinal";    
    
    var $voRegistroLivro;
    var $dtRegistroInicial = "";
    var $dtRegistroFinal = "";
    
    // ...............................................................
	// construtor
    function __construct1($pegarFiltrosDaTela) {
    	$this->voRegistroLivro = new voRegistroLivro();
    	$this->voRegistroLivro->voContrato->sqEspecie = null;
    
    	parent::__construct1($pegarFiltrosDaTela);
    }    
    
    function getFiltroFormulario(){
    	
    	$vocontrato = new vocontrato();
    	$vocontrato->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
    	$vocontrato->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
    	$vocontrato->tipo = @$_POST[vocontrato::$nmAtrTipoContrato];
    	$vocontrato->cdEspecie = @$_POST[vocontrato::$nmAtrCdEspecieContrato];
    	$vocontrato->sqEspecie = @$_POST[vocontrato::$nmAtrSqEspecieContrato];
    	$this->voRegistroLivro->voContrato = $vocontrato;
    	 
    	$this->dtRegistroInicial = @$_POST[self::$ID_REQ_DtRegistroInicial];
    	$this->dtRegistroFinal = @$_POST[self::$ID_REQ_DtRegistroFinal];
    	
    	//isso tudo pq o filtro pode ser usado por mais de um metodo
    	//e precisa saber qual voprincipal considera,
    	//pra pegar por ex os atributos de ordenacao da tabela correta
    	$this->nmEntidadePrincipal = voRegistroLivro::class;
    	if($this->cdOrdenacao == null)
    		$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
    }
    	
	function getFiltroConsultaSQL(){
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
	
		$isHistorico = $this->isHistorico;
		$nmTabela = voRegistroLivro::getNmTabelaStatic($isHistorico);
		if($this->nmEntidadePrincipal != null){
			$nmTabela = $this->getVOEntidadePrincipal()->getNmTabelaEntidade($isHistorico);
		}
		
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
		
		if($this->voRegistroLivro->voContrato->anoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaContrato::$nmAtrAnoContrato
			. " = "
					. $this->voRegistroLivro->voContrato->anoContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voRegistroLivro->voContrato->cdContrato != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaContrato::$nmAtrCdContrato
			. " = "
					. $this->voRegistroLivro->voContrato->cdContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voRegistroLivro->voContrato->tipo != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaContrato::$nmAtrTipoContrato
			. " = "
					. getVarComoString($this->voRegistroLivro->voContrato->tipo)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voRegistroLivro->voContrato->cdEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaContrato::$nmAtrCdEspecieContrato
			. " = "
					. getVarComoString($this->voRegistroLivro->voContrato->cdEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voRegistroLivro->voContrato->sqEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaContrato::$nmAtrSqEspecieContrato
			. " = "
					. getVarComoNumero($this->voRegistroLivro->voContrato->sqEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		//finaliza o filtro
		$filtro = parent::getFiltroSQLCompleto($filtro, new voSolicCompra());

		return $filtro;
	}
	
	function getAtributosOrdenacao(){		
		//$nmTabela = voSolicCompra::getNmTabelaStatic($this->isHistorico());		
		$varAtributos = array(
				voRegistroLivro::$nmAtrDtRegistro=> "Dt.Registro",
				vocontrato::$nmAtrAnoContrato=> "Ano.Contrato",
				vocontrato::$nmAtrCdContrato => "Cd.Contrato",
		);
		
		return $varAtributos;
	}
	

}

?>