<?php
include_once(caminho_util. "dominio.class.php");

Class dominioSimNao extends dominio{
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();        
	}

	static function getColecao(){
		return array(
				constantes::$CD_SIM => constantes::$DS_SIM,
				constantes::$CD_NAO => constantes::$DS_NAO
		);
	}
	
	/**
	 * permite o retorno da descricao tanto para numero como para booleano
	 * @param unknown $chave
	 * @return unknown|string
	 */
	static function getDescricao($chave){
		//echo $chave;
		$isnumero = is_numeric($chave);
		if(($isnumero && $chave == 1) || $chave === true){
			$chave = constantes::$CD_SIM;
		} else if(($isnumero && $chave == 0) || $chave === false){
			$chave = constantes::$CD_NAO;
		}
		//echo $chave;
		
		return parent::getDescricao($chave);
	}
	
	// ...............................................................
// Funções( Propriedades e metodos da classe )
	
}
?>