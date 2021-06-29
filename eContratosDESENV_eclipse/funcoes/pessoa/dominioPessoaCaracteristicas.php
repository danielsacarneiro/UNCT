<?php
include_once(caminho_util."dominio.class.php");

  Class dominioPessoaCaracteristicas extends dominio{
  	
  	static $CD_ASSINA_SEI = "S";
  	
  	static $DS_ASSINA_SEI = "Assina.SEI";
  	
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				static::$CD_ASSINA_SEI => static::$DS_ASSINA_SEI,
		);
	}
			
}
?>