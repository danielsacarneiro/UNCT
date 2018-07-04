<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTipoContrato extends dominio{
  	
  	static $CD_TIPO_CONTRATO  = "C";
  	static $CD_TIPO_CONVENIO  = "V";
  	static $CD_TIPO_PROFISCO  = "P";
  	
  	static $DS_TIPO_CONTRATO  = "C-SAFI";
  	static $DS_TIPO_CONVENIO  = "CV-SAFI";
  	static $DS_TIPO_PROFISCO  = "C-PROFISCO";  	 

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				static::$CD_TIPO_CONTRATO => static::$DS_TIPO_CONTRATO,
				static::$CD_TIPO_CONVENIO => static::$DS_TIPO_CONVENIO,
				static::$CD_TIPO_PROFISCO => static::$DS_TIPO_PROFISCO
		);
	}
	
	
}
?>