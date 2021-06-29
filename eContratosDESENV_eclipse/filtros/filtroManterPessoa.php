<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_vos ."vopessoa.php");
include_once(caminho_vos ."vopessoavinculo.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPessoa extends filtroManter{
    
    var $nmFiltro = "filtroManterPessoa";
    static $ID_REQ_DT_REFERENCIA = "filtroManterPessoa_ID_REQ_DT_REFERENCIA";
    
    // ...............................................................
	// construtor
    var $cd;
    var $cdGestor;
    var $doc="";
    var $nome="";
    var $cdvinculo="";
    var $inAtribuicaoPAAP ="";
    var $inAtribuicaoPregoeiro ="";
    
    var $cdContrato="";
    var $anoContrato="";
    var $tpContrato="";
    var $cdEspecieContrato="";
    var $sqEspecieContrato="";
    var $dtReferenciaContrato ="";
    
    var $cdDemanda ="";
    var $anoDemanda ="";
    var $inDesativadoContrato ="";
	
	function getFiltroFormulario(){		
		$this->cd = @$_POST[vopessoa::$nmAtrCd];
		//$this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
		$this->doc = @$_POST[vopessoa::$nmAtrDoc];
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
		$this->cdvinculo = @$_POST[vopessoavinculo::$nmAtrCd];
		$this->cdGestor = @$_POST[vogestor::$nmAtrCd];
		$this->inAtribuicaoPAAP = @$_POST[vopessoavinculo::$nmAtrInAtribuicaoPAAP];
		$this->inAtribuicaoPregoeiro = @$_POST[vopessoavinculo::$nmAtrInAtribuicaoPregoeiro];
		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
        $voPessoa= new vopessoa();
        $voPessoaVinculo= new vopessoavinculo();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = $voPessoa->getNmTabelaEntidade($isHistorico);
        $isTrazerComHistorico = $filtro->inTrazerComHistorico;
        if($isTrazerComHistorico){
        	$nmTabela = vopessoa::getNmTabelaGeralComHistorico();
        }
        
        $nmTabelaPessoaVinculo = $voPessoaVinculo->getNmTabela();        
        $nmTabelaOrgaoGestor = vogestor::getNmTabela();
        $nmTabelaDemanda = voDemanda::getNmTabelaStatic($isHistorico);
        $nmTabelaContrato = vocontrato::getNmTabela();
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->cd != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrCd
						. " = "
						. $this->cd;
			
			$conector  = "\n AND ";
		}
        
		if($this->cdGestor != null){
			$filtro = $filtro . $conector
			. $nmTabelaOrgaoGestor. "." .vogestor::$nmAtrCd
			. " = "
					. $this->cdGestor;
						
					$conector  = "\n AND ";
		}
		
		if($this->cdvinculo != null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaVinculo. "." .vopessoavinculo::$nmAtrCd
					. " = "
					. $this->cdvinculo;
						
					$conector  = "\n AND ";
		}
		
		if($this->inAtribuicaoPAAP != null){
			$filtro = $filtro . $conector
			. "($nmTabelaPessoaVinculo." .vopessoavinculo::$nmAtrInAtribuicaoPAAP
			. " = "
			. getVarComoString($this->inAtribuicaoPAAP);
			
			if($this->inAtribuicaoPAAP == constantes::$CD_NAO){
				$filtro .= " OR $nmTabelaPessoaVinculo." . vopessoavinculo::$nmAtrInAtribuicaoPAAP . " IS NULL";
			}
			
			$filtro .= ") ";
		
					$conector  = "\n AND ";
		}
		
		if($this->inAtribuicaoPregoeiro != null){
			$filtro = $filtro . $conector
			. "($nmTabelaPessoaVinculo." .vopessoavinculo::$nmAtrInAtribuicaoPregoeiro 
			. " = "
					. getVarComoString($this->inAtribuicaoPregoeiro);
						
					if($this->inAtribuicaoPregoeiro == constantes::$CD_NAO){
						$filtro .= " OR $nmTabelaPessoaVinculo." . vopessoavinculo::$nmAtrInAtribuicaoPregoeiro . " IS NULL";
					}
						
					$filtro .= ") ";
		
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){			
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrDoc
						. "='"
						. documentoPessoa::getNumeroDocSemMascara($this->doc)
						. "'";
			
			$conector  = "\n AND ";
		}	
		
		if($this->inDesativadoContrato != null){
			$filtro = $filtro . $conector
			. "$nmTabelaContrato." .vocontrato::$nmAtrInDesativado
			. "="
					. getVarComoString($this->inDesativadoContrato);
		
					$conector  = "\n AND ";
		}
		
		if($this->cdContrato != null){
			$filtro = $filtro . $conector
					. vocontrato::getNmTabela(). "." .vocontrato::$nmAtrCdContrato
					. "="
					. $this->cdContrato;
						
					$conector  = "\n AND ";
		}
		
		if($this->anoContrato != null){
			$filtro = $filtro . $conector
					. vocontrato::getNmTabela(). "." .vocontrato::$nmAtrAnoContrato
					. "="
					. $this->anoContrato;
		
					$conector  = "\n AND ";
		}
		
		if($this->tpContrato != null){
			$filtro = $filtro . $conector
					. vocontrato::getNmTabela(). "." .vocontrato::$nmAtrTipoContrato
					. "='"
					. $this->tpContrato
					. "'";
		
					$conector  = "\n AND ";
		}
		
		if($this->cdEspecieContrato != null){
			$filtro = $filtro . $conector
					. vocontrato::getNmTabela(). "." .vocontrato::$nmAtrCdEspecieContrato
					. "="
					. getVarComoString($this->cdEspecieContrato)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->sqEspecieContrato != null){
			$filtro = $filtro . $conector
			. vocontrato::getNmTabela(). "." .vocontrato::$nmAtrSqEspecieContrato
			. "="
					. $this->sqEspecieContrato;
		
					$conector  = "\n AND ";
		}
				
		if($this->dtReferenciaContrato != null){
			$filtro = $filtro . $conector
					.  getSQLDataVigenteSimplesPorData(
							vocontrato::getNmTabela(),
							$this->dtReferenciaContrato,
							vocontrato::$nmAtrDtVigenciaInicialContrato,
							vocontrato::$nmAtrDtVigenciaFinalContrato)
			;
		
					$conector  = "\n AND ";
		}
		
		if($this->cdDemanda != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemanda . "." .voDemanda::$nmAtrCd
			. "="
					. $this->cdDemanda;
		
					$conector  = "\n AND ";
		}
		
		if($this->anoDemanda != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemanda . "." .voDemanda::$nmAtrAno
			. "="
					. $this->anoDemanda;
		
					$conector  = "\n AND ";
		}
		

		$this->formataCampoOrdenacao(new vopessoa());
		//finaliza o filtro		
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				vopessoa::$nmAtrNome => "Nome",
				vopessoavinculo::$nmAtrCd=> "Vinculo",				
				vopessoa::$nmAtrDhUltAlteracao=> "Data.Alteração",
				vopessoa::$nmAtrCd => "Código"
		);
		return $varAtributos;
	}
}

?>