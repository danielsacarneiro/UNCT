<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterSolicCompra extends filtroManter{
    
    var $nmFiltro = "filtroManterSolicCompra";
    
    var $ano;
    var $cd;
    var $ug;
    
    var $situacao;
    var $tipo;
    var $objeto;        
    // ...............................................................
	// construtor
    
    function getFiltroFormulario(){
    	
    	$this->cd = @$_POST[voSolicCompra::$nmAtrCd];
    	$this->ano = @$_POST[voSolicCompra::$nmAtrAno];
    	$this->ug = @$_POST[voSolicCompra::$nmAtrUG];
    	
    	$this->situacao = @$_POST[voSolicCompra::$nmAtrSituacao];
    	$this->objeto = @$_POST[voSolicCompra::$nmAtrObjeto];    	
    	$this->tipo = @$_POST[voSolicCompra::$nmAtrTipo];   	
  	
    	//isso tudo pq o filtro pode ser usado por mais de um metodo
    	//e precisa saber qual voprincipal considera,
    	//pra pegar por ex os atributos de ordenacao da tabela correta
    	$this->nmEntidadePrincipal = "voSolicCompra";
    	if($this->cdOrdenacao == null)
    		$this->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
    }
    	
	function getFiltroConsultaSQL(){
        $voSolicCompra= new voSolicCompra();
        $vopessoa= new vopessoa();
        $vocontrato= new vocontrato();
		$filtro = "";
		$conector  = "";
	
		$isHistorico = $this->isHistorico;
		$nmTabela = $voSolicCompra->getNmTabelaEntidade($isHistorico);
		if($this->nmEntidadePrincipal != null){
			$nmTabela = $this->getVOEntidadePrincipal()->getNmTabelaEntidade($isHistorico);
		}
		
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
		
		if($this->situacao != null){		
			$filtro = $filtro . $conector
					. $nmTabela. "." .voSolicCompra::$nmAtrSituacao
					. " = "
					. $this->situacao
					;						
			$conector  = "\n AND ";
		}
		
		if($this->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voSolicCompra::$nmAtrCd 
			. " = "
					. $this->cd;
		
					$conector  = "\n AND ";
		}
		
		if($this->ano != null){
			$filtro = $filtro . $conector
					. $nmTabela. "." .voSolicCompra::$nmAtrAno
					. " = "
					. $this->ano;
						
			$conector  = "\n AND ";
		}
		
		if($this->ug != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voSolicCompra::$nmAtrUG 
			. " = "
					. $this->ug;
		
					$conector  = "\n AND ";
		}
				
		if($this->objeto != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voSolicCompra::$nmAtrObjeto
			. " LIKE " . getVarComoString("%$this->objeto%");					
		
			$conector  = "\n AND ";
		}
		
		if($this->tipo != null){
			$filtro = $filtro . $conector
			. "$nmTabela." .voSolicCompra::$nmAtrTipo
			. " = "
					. getVarComoString($this->tipo)
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
				voSolicCompra::$nmAtrUG => "UG",
				voSolicCompra::$nmAtrAno=> "Ano",
				voSolicCompra::$nmAtrDhUltAlteracao=> "Dt.Alteraчуo",
		);
		
		return $varAtributos;
	}
	

}

?>