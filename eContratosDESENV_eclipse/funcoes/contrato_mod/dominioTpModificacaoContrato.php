<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTpContratoModificacao extends dominio{	
	static $CD_TIPO_ACRESCIMO = 1;
	static $CD_TIPO_SUPRESSAO = 2;
	static $CD_TIPO_REAJUSTE = 3;
		
	static $DS_TIPO_ACRESCIMO = "Acréscimo";
	static $DS_TIPO_SUPRESSAO = "Supressão";
	static $DS_TIPO_REAJUSTE = "Reajuste";
	// ...............................................................

	static function getColecao(){
		return array(				
				self::$CD_TIPO_ACRESCIMO => self::$DS_TIPO_ACRESCIMO,
				self::$CD_TIPO_SUPRESSAO=> self::$DS_TIPO_SUPRESSAO,
				self::$CD_TIPO_REAJUSTE=> self::$DS_TIPO_REAJUSTE,
		);
	}	
}
?>