<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTipoContrato extends dominio{
  	
  	static $CD_TIPO_CONTRATO  = "C";
  	static $CD_TIPO_CONVENIO  = "V";
  	static $CD_TIPO_PROFISCO  = "P";
  	static $CD_TIPO_TERMO_COLABORACAO = "T";
  	
  	static $DS_TIPO_CONTRATO  = "C-SAFI";
  	static $DS_TIPO_CONVENIO  = "CV-SAFI";
  	static $DS_TIPO_PROFISCO  = "C-PROFISCO";
  	static $DS_TIPO_TERMO_COLABORACAO = "Termo de colaboraзгo";
  	//ATENCAO, A UNCT NUMERA TERMO DE COLABORACAO COMO CONVENIO

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// FunГ§Гµes ( Propriedades e mГ©todos da classe )

	static function getColecao(){
		return array(
				static::$CD_TIPO_CONTRATO => static::$DS_TIPO_CONTRATO,
				static::$CD_TIPO_CONVENIO => static::$DS_TIPO_CONVENIO,
				static::$CD_TIPO_PROFISCO => static::$DS_TIPO_PROFISCO,
		);
	}
	
	
}
?>