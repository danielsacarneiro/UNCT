<?php
include_once (caminho_util . "multiplosConstrutores.php");
class dominio extends multiplosConstrutores {
	var $colecao;
	
	// ...............................................................
	// Construtor
	// herda do pai
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function getDescricao($chave) {
		return self::getDescricaoStatic ( $chave, $this->colecao );
	}
	
	static function getDescricaoStaticTeste($chave) {
		$retorno = $chave;
		$colecao = static::getColecao();
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
				
			// echo "chave selecionada: ". $chave. "<br>";
				
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				// echo "chave: ". $cd . "<br>";
		
				if ($cd == $chave) {
					$retorno = $colecao [$cd];
					break;
				}
			}
		}
		
		return $retorno;		
	}
	
	static function getDescricaoStatic($chave, $colecao) {
		$retorno = $chave;
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			// echo "chave selecionada: ". $chave. "<br>";
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				// echo "chave: ". $cd . "<br>";
				
				if ($cd == $chave) {
					$retorno = $colecao [$cd];
					break;
				}
			}
		}
		
		return $retorno;
	}
	static function removeElementoStatic($chave, $colecao) {
		$retorno = array ();
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			// echo "chave selecionada: ". $chave. "<br>";
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				if ($cd != $chave) {
					// echo "chave$i: ". $cd . "<br>";
					$array2 = array (
							$cd => $colecao [$cd] 
					);
					$retorno = putElementoArrayComChaves ( $retorno, $array2 );
				}
			}
		}
		
		return $retorno;
	}
	static function getColecaoComElementosARemover($chaveARemover, $colecao=null) {
		//usado para o caso de um dominio que tenha a colecao chamar sem o 2 argumento
		if($colecao == null){
			$colecao = static::getColecao ();
		}
		
		// var_dump($colecao);
		$retorno = $colecao;
		if ($chaveARemover != null) {
			
			if (! is_array ( $chaveARemover )) {
				
				$retorno = self::removeElementoStatic ( $chaveARemover, $colecao );
			} else {
				
				foreach ( $chaveARemover as $chave ) {
					
					$retorno = self::removeElementoStatic ( $chave, $retorno );
				}
			}
			// echo "remove";
		}
		return $retorno;
	}
	static function getColecaoApenasComElementos($chaves, $colecao=null) {	
		//usado para o caso de um dominio que tenha a colecao chamar sem o 2 argumento
		if($colecao == null){
			$colecao = static::getColecao ();
		}
		
		// var_dump($colecao);
		$retorno = $colecao;
		if ($chaves != null) {
			
			foreach ( array_keys($colecao) as $chave ) {
				
				if(!in_array($chave, $chaves)){				
					$retorno = self::removeElementoStatic ( $chave, $retorno );
				}
			}
			
			// echo "remove";
		}
		return $retorno;
	}
	function getArrayHTMLChaves($nmVariavelHtml) {
		$retorno = getStrComPuloLinha ( "$nmVariavelHtml = new Array();" );
		$colecao = $this->colecao;
		if ($colecao != null) {
			$totalResultado = count ( $colecao );
			$chaves = array_keys ( $colecao );
			
			for($i = 0; $i < $totalResultado; $i ++) {
				$cd = $chaves [$i];
				$retorno .= getStrComPuloLinha ( $nmVariavelHtml . "[" . $i . "] = '$cd';" );
			}
		}
		
		return $retorno;
	}
}
?>