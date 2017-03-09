<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTipoContrato extends dominio{

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				constantes::$CD_TIPO_CONTRATO => constantes::$DS_TIPO_CONTRATO,
				constantes::$CD_TIPO_CONVENIO => constantes::$DS_TIPO_CONVENIO,
				constantes::$CD_TIPO_PROFISCO => constantes::$DS_TIPO_PROFISCO
		);
	}
	
	
}
?>