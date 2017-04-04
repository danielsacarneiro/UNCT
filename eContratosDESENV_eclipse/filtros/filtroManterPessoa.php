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
    var $doc="";
    var $nome="";
    var $cdvinculo="";
    
    var $cdContrato="";
    var $anoContrato="";
    var $tpContrato="";
    var $cdEspecieContrato="";
    var $sqEspecieContrato="";
    var $dtReferenciaContrato ="";
        	
	function __construct1($pegarFiltrosDaTela) {
		parent::__construct2(true, $pegarFiltrosDaTela);
		
		if($pegarFiltrosDaTela){
			$this->getFiltroFormulario();
			//echo "teste";
		}
		
		//echo "construtor2";
	}
	
	function getFiltroFormulario(){		
		$this->cd = @$_POST[vopessoa::$nmAtrCd];
		//$this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
		$this->doc = @$_POST[vopessoa::$nmAtrDoc];
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
		$this->cdvinculo = @$_POST[vopessoavinculo::$nmAtrCd];
	}
	
	function getFiltroConsultaSQL(){
        $voPessoa= new vopessoa();
        $voPessoaVinculo= new vopessoavinculo();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico;
        $nmTabela = $voPessoa->getNmTabelaEntidade($isHistorico);
        $nmTabelaPessoaVinculo = $voPessoaVinculo->getNmTabela();
        
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
        
		if($this->cdvinculo != null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaVinculo. "." .vopessoavinculo::$nmAtrCd
					. " = "
					. $this->cdvinculo;
						
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
						. $this->doc
						. "'";
			
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
		
		if($this->cdGestor != null){
			$filtro = $filtro . $conector
						//. $nmTabela. "." .vopessoa::$nmAtrCdGestor
						. "='"
						. $this->cdGestor
						. "'";
			
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

		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>