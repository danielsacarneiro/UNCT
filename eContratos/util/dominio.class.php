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
		return self::getDescricaoStatic($chave, $this->colecao);
		/*$retorno = $chave;
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
        
		return $retorno;*/
	}
	
	static function getDescricaoStatic($chave, $colecao) {
		$retorno = $chave;
		if($colecao != null){
			$totalResultado = count($colecao);
			$chaves = array_keys($colecao);
	
			//echo "chave selecionada: ". $chave. "<br>";
	
			for ($i=0; $i<$totalResultado; $i++) {
				$cd = $chaves[$i];
				//  echo "chave: ". $cd . "<br>";
	
				if($cd == $chave){
					$retorno = $colecao[$cd];
					break;
				}
			}
		}
	
		return $retorno;
	}
	
	
}
?>