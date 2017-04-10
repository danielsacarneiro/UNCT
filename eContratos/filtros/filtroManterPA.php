<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
include_once(caminho_funcoes ."contrato/dominioEspeciesContrato.php");

class filtroManterPA extends filtroManter{
    
    var $nmFiltro = "filtroManterPenalidade";
    
    var $cdPessoa ;    
    var $cdResponsavel ;
    var $doc;
    var $nome;
    var $cdPA;
    var $anoPA;
    var $situacao;
    var $cdEspecieContrato;
    
    var $nmTabelaPessoaContrato = "TAB_PESSOA_CONTRATO";
    var $nmTabelaPessoaResponsavel = "TAB_PESSOA_RESP";
    
    var $nmColNomePessoaContrato = "NmColPessoaContrato";
    var $nmColNomePessoaResponsavel = "NmColPessoaResponsavel";
    
    // ...............................................................
	// construtor
    
	function __construct() {
        //parent::__construct(true);
		parent::__construct();
        
        $this->cdPessoa = @$_POST[vopessoa::$nmAtrCd];
        $this->cdResponsavel = @$_POST[voPA::$nmAtrCdResponsavel];
        //$this->cdGestor = @$_POST[voPA::$nmAtrCdGestor];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->nome = @$_POST[vopessoa::$nmAtrNome];
        
        $this->cdPA = @$_POST[voPA::$nmAtrCdPA];
        $this->anoPA = @$_POST[voPA::$nmAtrAnoPA];
        $this->situacao = @$_POST[voPA::$nmAtrSituacao];
        $this->cdEspecieContrato = @$_POST[vocontrato::$nmAtrCdEspecieContrato];
        
        //isso tudo pq o filtro pode ser usado por mais de um metodo
        //e precisa saber qual voprincipal considera,
        //pra pegar por ex os atributos de ordenacao da tabela correta
        $this->nmEntidadePrincipal = "voPA";
    }
    	
	function getFiltroConsultaSQL(){
        $voPA= new voPA();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
		
		$nmTabelaPessoaContrato = $this->nmTabelaPessoaContrato;
		$nmTabelaPessoaResponsavel = $this->nmTabelaPessoaResponsavel;
		
		$nmTabelaContrato= $vocontrato->getNmTabelaEntidade(false);
		$isHistorico = $this->isHistorico;
		$nmTabela = $voPA->getNmTabelaEntidade($isHistorico);
		if($this->nmEntidadePrincipal != null){
			$nmTabela = $this->getVOEntidadePrincipal()->getNmTabelaEntidade($isHistorico);
		}
		
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            				
		if($this->cdEspecieContrato != null){
			$filtro = $filtro . $conector
					. $nmTabelaContrato. "." .vocontrato::$nmAtrCdEspecieContrato
					. " = '"
					. $this->cdEspecieContrato . "'";
					//. dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
						
			$conector  = "\n AND ";
		}
		
		if($this->situacao != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPA::$nmAtrSituacao
					. " = "
					. $this->situacao
					;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPA != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voPA::$nmAtrCdPA
			. " = "
					. $this->cdPA;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoPA != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voPA::$nmAtrAnoPA
					. " = "
					. $this->anoPA;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdResponsavel!= null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaResponsavel. "." .vopessoa::$nmAtrCd
					. " = "
					. $this->cdResponsavel;
						
			$conector  = "\n AND ";
		}
		
		if($this->cdPessoa != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrCd
			. " = "
					. $this->cdPessoa;
		
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){
			$filtro = $filtro . $conector
						. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrDoc
						. "='"
						. $this->doc
						. "'";
			
			$conector  = "\n AND ";
		}	

		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						//. $nmTabela. "." .voPA::$nmAtrCdGestor
						. "='"
						. $this->cdGestor
						. "'";
			
			$conector  = "\n AND ";
		}	

		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voPA::$nmAtrCdPA=> "PA",
				vocontrato::getNmTabelaStatic($this->isHistorico).".".voPA::$nmAtrCdContrato => "Contrato"
		);
		return $varAtributos;
	}
	

}

?>