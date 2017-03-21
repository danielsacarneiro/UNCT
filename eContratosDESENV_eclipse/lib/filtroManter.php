<?php
include_once(caminho_util."paginacao.php");
include_once(caminho_util."multiplosConstrutores.php");
include_once(caminho_util."dominioTpVigencia.php");

class filtroManter extends multiplosConstrutores{
    // ...............................................................
	// construtor
	static $nmAtrCdConsultarArquivo = "cdConsultarArquivo";
	static $nmAtrCdAtrOrdenacao = "cdAtrOrdenacao";
	static $nmAtrCdOrdenacao = "cdOrdenacao";
	static $nmAtrDtVigencia = "dtVigencia";
	static $nmAtrTpVigencia = "tpVigencia";
	static $nmAtrCdHistorico = "cdHistorico";
	static $nmAtrQtdRegistrosPorPag = "qtdRegistrosPorPag";
	static $nmAtrNumTotalRegistros = "numTotalRegistros";	
	static $nmAtrCdUtilizarSessao = "utilizarSessao";	
	static $nmAtrCdConsultar = "consultar";	
	
	var $cdAtrOrdenacao;
	var $cdOrdenacao;
	var $dtVigencia;
	var $tpVigencia;
	var $cdHistorico;
		
	var $numTotalRegistros;
	var $TemPaginacao;
	var $qtdRegistrosPorPag;
	var $paginacao;
	var $nmEntidadePrincipal;
	var $isHistorico;
	var $cdConsultarArquivo;
				
	function __construct0() {
		//echo "teste0";
		$this->__construct1(true);
	}
	
	function __construct1($pegarFiltrosDaTela) {
		//echo "teste" . $pegarFiltrosDaTela;
		$this->__construct2(true, $pegarFiltrosDaTela);
	}
	
	function __construct2($temPaginacao, $pegarFiltrosDaTela) {
		
		$this->cdConsultarArquivo = constantes::$CD_NAO;
		$this->tpVigencia = constantes::$CD_OPCAO_TODOS;
		
		if($pegarFiltrosDaTela){
			$this->pegarFiltroDaTela();
		}
		
		if($this->numTotalRegistros == null){
			$this->numTotalRegistros = 0;
		}
		if($this->qtdRegistrosPorPag == null){
			$this->qtdRegistrosPorPag = paginacao::$qtdRegistrosPorPag;
		}		
		$this->paginacao = null;        
        $this->TemPaginacao= $temPaginacao;                
        if($temPaginacao){
            $this->paginacao = new paginacao($this->qtdRegistrosPorPag);
        }            

        $this->isHistorico = "S" == $this->cdHistorico;        
        //para o caso de ser necessario setar um filtro default para nao trazer todos os registros
        $this->temValorDefaultSetado = false;        
	}	
    
	function pegarFiltroDaTela(){
		$this->cdAtrOrdenacao = @$_POST[self::$nmAtrCdAtrOrdenacao];
		$this->cdOrdenacao = @$_POST[self::$nmAtrCdOrdenacao];		 
		$this->tpVigencia = @$_POST[self::$nmAtrTpVigencia];
		$this->dtVigencia = @$_POST[self::$nmAtrDtVigencia];
		$this->cdHistorico  = @$_POST[self::$nmAtrCdHistorico];
		$this->qtdRegistrosPorPag = @$_POST[self::$nmAtrQtdRegistrosPorPag];
		$this->numTotalRegistros = @$_POST[self::$nmAtrNumTotalRegistros];		 
		$this->cdConsultarArquivo = @$_POST[self::$nmAtrCdConsultarArquivo];
		
		/*$this->cdAtrOrdenacao = @$_POST["cdAtrOrdenacao"];
		$this->cdOrdenacao = @$_POST["cdOrdenacao"];
		$this->tpVigencia = @$_POST["dtVigencia"];
		$this->dtVigencia = @$_POST["dtVigencia"];
		$this->cdHistorico  = @$_POST["cdHistorico"];
		$this->qtdRegistrosPorPag = @$_POST["qtdRegistrosPorPag"];
		$this->numTotalRegistros = @$_POST["numTotalRegistros"];
		$this->cdConsultarArquivo = @$_POST[self::$nmAtrCdConsultarArquivo];*/
		
	}
	
	function isSetaValorDefault(){
        $retorno = false;
    }
           
    static function verificaFiltroSessao($filtro){
    	//echo $filtro->nmFiltro;
    	 
    	session_start();
    	$utilizarSessao = @$_POST["utilizarSessao"];
    	$isUtilizarSessao = $utilizarSessao != "N";
    
    	$consultar = @$_GET["consultar"];
    	$isConsultar = $consultar == "S";
    
    	$pegarFiltroSessao = $isUtilizarSessao && $isConsultar;
    	//echo "nome filtro". $filtro->nmFiltro;
    	if(existeObjetoSessao($filtro->nmFiltro) && $pegarFiltroSessao){
    		$filtro = getObjetoSessao($filtro->nmFiltro);
    		$paginaAtual = @$_GET['paginaAtual'];
    
    		if($paginaAtual != null)
    			$filtro->paginacao->paginaAtual = $paginaAtual;
    	}
    	else{
    		putObjetoSessao($filtro->nmFiltro, $filtro);
    	}
    
    	return $filtro;
    }
    
    /*static function verificaFiltroSessao($filtro){
    	//echo $filtro->nmFiltro;
    	
        session_start();
        $utilizarSessao = @$_POST["utilizarSessao"];
        $isUtilizarSessao = $utilizarSessao != "N";
        
        $consultar = @$_GET["consultar"];
        $isConsultar = $consultar == "S";
        
        $pegarFiltroSessao = $isUtilizarSessao && $isConsultar;
        //echo "nome filtro". $filtro->nmFiltro;
        if(isset($_SESSION[$filtro->nmFiltro]) && $pegarFiltroSessao){
            $filtro = $_SESSION[$filtro->nmFiltro];
            $paginaAtual = @$_GET['paginaAtual'];
            
            if($paginaAtual != null)
                $filtro->paginacao->paginaAtual = $paginaAtual;
        }
        else{
            $_SESSION[$filtro->nmFiltro] = $filtro;            
        }
        
        return $filtro;
    }*/
    
    function getFiltroConsultaSQL($filtro){
    	//ECHO "TESTE";
    	
    	if($filtro != ""){
    		$filtro = "\n WHERE $filtro";
    	}
    	
    	if($this->cdAtrOrdenacao  != null){
    				
    		$ordem = $this->cdOrdenacao;
    		/*if($ordem == constantes::$CD_ORDEM_CRESCENTE){
    			$ordem = "";
    		}*/
    		
    		//pega do filho, se existir
    		$strOrdemDefault = "";
    		if($this->getAtributoOrdenacaoDefault()){
    			$strOrdemDefault = "," . $this->getAtributoOrdenacaoDefault() . " " . $ordem;
    		}
    		
    		$filtro = $filtro . "\n ORDER BY $this->cdAtrOrdenacao $ordem $strOrdemDefault ";
    		
    		//para setar o atributo de ordenacao de forma mais complexa: quando ha joins na tabela
    		//para tanto o atributo nmEntidadePrincipal precisa ser not null
    		/*$voentidade = $this->getVOEntidadePrincipal();    		
    		if($voentidade != ""){
    			$filtro = $filtro . "\n ORDER BY " . $voentidade->getNmTabelaEntidade($this->isHistorico) . ".$this->cdAtrOrdenacao $ordem";
    		}else{
    			$filtro = $filtro . "\n ORDER BY $this->cdAtrOrdenacao $ordem";
    		}*/
    	}
    	
    	return $filtro; 
    }
    
    function getVOEntidadePrincipal(){
    	$class = $this->nmEntidadePrincipal;
    	$retorno = "";
    	if($class != null)
    		$retorno = new $class();
    	return $retorno ; 
    }
    
    //NAO USAR MAIS
    /*function getAtributosOrdenacao(){
    	$comboOrdenacao = null;
    	if($this->nmEntidadePrincipal != null){
    		$voentidade = $this->getVOEntidadePrincipal();    		
    		$comboOrdenacao = new select($voentidade::getAtributosOrdenacao());
    	}
    	return $comboOrdenacao;
    }*/
    
    function getComboOrdenacao(){
    	$comboOrdenacao = null;
    	try{
    		//$comboOrdenacao = new select(static::getAtributosOrdenacao());
    		$comboOrdenacao = new select($this->getAtributosOrdenacao());
    	
    	//}catch (Throwable $ex){
    	}catch (Error $ex){
    		echo "FiltroManter:Error";
    		$comboOrdenacao = null;
    	}catch (Throwable $ex){
    		echo "FiltroManter:Throwable";
    		$comboOrdenacao = null;
    	}    		

    	return $comboOrdenacao;
    }
    
    function toString(){		
		$retorno.= "qtdRegistrosPorPag=" . $this->qtdRegistrosPorPag . "|";
        $retorno.= "paginaAtual=" . $this->paginacao->paginaAtual . "|";
        $retorno.= "numTotalRegistros=" . $this->numTotalRegistros;
        
		return $retorno;		
	}
	
	function getAtributoOrdenacaoDefault(){
		return "";
	}
	
}

/*class filtroManterGUI extends filtroManter{
	// ...............................................................
	// construtor
	function __construct($temPaginacao) {
		parent::__construct1($temPaginacao, true);
	}
}*/
?>