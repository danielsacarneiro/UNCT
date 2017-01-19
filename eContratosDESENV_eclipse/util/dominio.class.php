<?php

Class dominio {
    var $colecao;
  
// ...............................................................
// Construtor
	function __construct () {
	}

// ...............................................................
// Funções ( Propriedades e métodos da classe )
    
	function getDescricao($chave) {
		$retorno = $chave;
        if($this->colecao != null){
            $totalResultado = count($this->colecao);
            $chaves = array_keys($this->colecao);
    
            //echo "chave selecionada: ". $chave. "<br>";
            
            for ($i=0; $i<$totalResultado; $i++) {
                $cd = $chaves[$i];
              //  echo "chave: ". $cd . "<br>";
                
                if($cd == $chave){                
                    $retorno = $this->colecao[$cd];
                    break;
                }			
            }            
        }
        
		return $retorno;
	}    
	
}
?>