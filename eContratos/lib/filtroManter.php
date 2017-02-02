<?php
include_once(caminho_util."paginacao.php");

class filtroManter{
    // ...............................................................
	// construtor
	function __construct($temPaginacao) {        
		$this->cdAtrOrdenacao = @$_POST["cdAtrOrdenacao"];
		$this->cdOrdenacao = @$_POST["cdOrdenacao"];
		$this->dtVigencia = @$_POST["dtVigencia"];
        $this->cdHistorico  = @$_POST["cdHistorico"];        
        $this->qtdRegistrosPorPag = @$_POST["qtdRegistrosPorPag"];
        if($this->qtdRegistrosPorPag == null)
        	$this->qtdRegistrosPorPag = paginacao::$qtdRegistrosPorPag;

        $this->numTotalRegistros = @$_POST["numTotalRegistros"];
        if($this->numTotalRegistros == null)
            $this->numTotalRegistros = 0;

        $this->paginacao = null;        
        $this->TemPaginacao= $temPaginacao;
        
        if($temPaginacao){
            $this->paginacao = new paginacao($this->qtdRegistrosPorPag);
        }
            
        //para o caso de ser necessario setar um filtro default para nao trazer todos os registros
        $this->temValorDefaultSetado = false;
	}
    
    function isSetaValorDefault(){
        $retorno = false;
    }
    
    static function verificaFiltroSessao($filtro){
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
    }
    
	function toString(){		
		$retorno.= "qtdRegistrosPorPag=" . $this->qtdRegistrosPorPag . "|";
        $retorno.= "paginaAtual=" . $this->paginacao->paginaAtual . "|";
        $retorno.= "numTotalRegistros=" . $this->numTotalRegistros;
        
		return $retorno;		
	} 
}

?>