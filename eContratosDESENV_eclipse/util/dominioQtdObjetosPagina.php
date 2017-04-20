<?php
include_once ("dominio.class.php");
include_once (caminho_util . "bibliotecafuncoesPrincipal.php");
class dominioQtdObjetosPagina extends dominio {
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = static::getColecao ();
		
		if (isUsuarioAdmin ()) {
			$array2 = array (
					constantes::$CD_OPCAO_TODOS => constantes::$DS_OPCAO_TODOS 
			);
			$this->colecao = array_merge_keys ( $this->colecao, $array2 );
		}
	}
	static function getColecao() {
		$fator = 30;
		$opcoes = 6;
		
		$proxvalor = 10;
		$keys = array (
				"5",
				"$proxvalor" 
		);
		$descricao = array (
				"5",
				"$proxvalor" 
		);
		
		$retorno = "";
		
		for($i = count ( $keys ); $i < $opcoes; $i ++) {
			$proxvalor = $proxvalor + $fator;
			$keys [] = "$proxvalor ";
			$descricao [] = "$proxvalor ";
		}
		
		$retorno = array_combine ( $keys, $descricao );
		return $retorno;
	}
}
?>