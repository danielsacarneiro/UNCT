<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterUsuario extends filtroManter{
    
    public static $nmFiltro = "filtroManterUsuario";
    
    var $id;
    var $name;
    var $cdSetor;
    var $inHabilitados;
    
    // ...............................................................
	// construtor
	function getFiltroFormulario() {        
        $this->id = @$_POST[voUsuarioInfo::$nmAtrID];
        $this->name = @$_POST[voUsuarioInfo::$nmAtrName];
        $this->cdSetor = @$_POST[voUsuarioSetor::$nmAtrCdSetor];        
	}
    	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){

		$filtro = "";
		$conector  = "";
		
        $nmTabela = vousuario::getNmTabelaStatic($this->isHistorico());
        $nmTabelaUsuSetor = voUsuarioSetor::getNmTabela ();
        //$nmTabelaComplementar = vousuario::getNmTabelaDadosComplementares();
        if($this->nmEntidadePrincipal != null){
        	$nmTabela = $this->nmEntidadePrincipal; 
        }
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->id != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vousuario::$nmAtrID
						. " = "
						. $this->id;
			
			$conector  = "\n AND ";
        
		}		

		if($this->name != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vousuario::$nmAtrName
			. " LIKE '%"
					. $this->name
					. "%' ";
						
					$conector  = "\n AND ";
		
		}
		
		if($this->cdSetor != null){
			$filtro = $filtro . $conector
			. $nmTabelaUsuSetor. "." .voUsuarioSetor::$nmAtrCdSetor
			. " = "
			. $this->cdSetor
			;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->inHabilitados != null){
			$nmTabelaWPUsersMeta = vousuarioMeta::getNmTabela ();
			$queryJoin .= "\n LEFT JOIN " . $nmTabelaWPUsersMeta;
			$queryJoin .= "\n ON ";
			$queryJoin .= $nmTabelaWPUsers . "." . vousuario::$nmAtrID . "=" . $nmTabelaUsuSetor . "." . voUsuarioSetor::$nmAtrID;
				
			$filtro = $filtro . $conector
			. $nmTabelaComplementar. "." .voUsuarioSetor::$nmAtrCdSetor
			. " = "
					. $this->cdSetor
					;
		
					$conector  = "\n AND ";
		
		}
		
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);	

		return $filtro;
	}
	
	/**
	 * verifica se houve filtro que exigisse substituicao do campo substituicao
	 *	que permitiria join com outras tabelas no filtro
	 *	se nao tem, eh porque tem que apagar o campo substituicao
	 * @return boolean
	 */
	function temJoinFiltroASubstituir(){
		return $this->inHabilitados != null;
	}
	
	/*function getAtributoOrdenacaoDefault(){
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrPrioridade . " " . constantes::$CD_ORDEM_CRESCENTE
		. "," . $nmTabelaDemanda . "." . voDemanda::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_CRESCENTE;
		return $retorno;
	}*/
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voUsuarioInfo::$nmAtrID => "ID",
				voUsuarioInfo::$nmAtrName => "Nome"
		);
		return $varAtributos;
	}	

}

?>