<?php
include_once(caminho_util."multiplosConstrutores.php");

Class dominio extends multiplosConstrutores{
    var $colecao;
  
// ...............................................................
// Construtor
//herda do pai

// ...............................................................
// Funções ( Propriedades e métodos da classe )
    
	function getDescricao($chave) {
		return self::getDescricaoStatic($chave, $this->colecao);
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
	
	static function removeElementoStatic($chave, $colecao) {
		$retorno = array();		
		if($colecao != null){
			$totalResultado = count($colecao);
			$chaves = array_keys($colecao);
	
			//echo "chave selecionada: ". $chave. "<br>";
	
			for ($i=0; $i<$totalResultado; $i++) {
				$cd = $chaves[$i];	
				if($cd != $chave){
					//echo "chave$i: ". $cd . "<br>";
					$array2 = array ($cd => $colecao[$cd]);						
					$retorno = putElementoArrayComChaves( $retorno, $array2);
				}
			}
		}
	
		return $retorno;
	}
	
	static function getColecaoComElementosARemover($chaveARemover){
		$colecao = static::getColecao();
		//var_dump($colecao);
		$retorno = $colecao; 
		if($chaveARemover != null){			
			$retorno = self::removeElementoStatic($chaveARemover, $colecao);
			//echo "remove";
		}
		return $retorno;
	}
	
	function getArrayHTMLChaves($nmVariavelHtml) {
		$retorno = getStrComPuloLinha("$nmVariavelHtml = new Array();");
		$colecao = $this->colecao;
		if($colecao != null){
			$totalResultado = count($colecao);
			$chaves = array_keys($colecao);	
	
			for ($i=0; $i<$totalResultado; $i++) {
				$cd = $chaves[$i];
				$retorno .= getStrComPuloLinha($nmVariavelHtml."[".$i."] = '$cd';");	
			}
		}
	
		return $retorno;
	}	
	
}
?>