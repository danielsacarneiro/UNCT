<?php

// .................................................................................................................
// Classe select
// cria um combo select html

  Class paginacao {
		var $paginaAtual;
		var $numPaginas;
		static $qtdRegistrosPorPag = 10;
		
// ...............................................................
// Construtor
	function __construct ($qtdRegistrosPorPag) {        
		$this->paginaAtual = 1;     
	}	

    function getPaginaAtual(){
        return $this->paginaAtual;        
    }

	function setNumTotalPaginas($total) {
		$this->numPaginas = $total;
	}		
		
	function getNumTotalPaginas() {
		return $this->numPaginas;
	}		

	function funcaoJavaScript($paginaAtual){
		$form= "document.frm_principal";
		$retorno = "'javascript:$form.paginaAtual.value=$paginaAtual;";
		$retorno = $retorno. "$form.submit();'";
		
		return $retorno;
	}
	
	public function criarLinkPaginacao() {
		for($i = 1; $i < $this->numPaginas + 1; $i++) {
			if($this->paginaAtual != null && $i != $this->paginaAtual){					
				echo "<a rel='nofollow' href=".$this->funcaoJavaScript($i).">$i</a> ";										
			}else
				echo "($i)";
		}
	}
    
	public function criarLinkPaginacaoGET() {
		for($i = 1; $i < $this->numPaginas + 1; $i++) {
			if($this->paginaAtual != null && $i != $this->paginaAtual){					
				echo "<a rel='nofollow' href=index.php?consultar=S&paginaAtual=".$i.">$i</a> ";										
			}else
				echo "($i)";
		}
	}    
	
}
?>