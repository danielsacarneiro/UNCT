<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTpContratoModificacao extends dominio{	

	static $CD_TIPO_REAJUSTE = 1;
	static $CD_TIPO_ACRESCIMO = 2;
	static $CD_TIPO_SUPRESSAO = 3;
	static $CD_TIPO_PRORROGACAO = 4;
	
	static $DS_TIPO_ACRESCIMO = "Acréscimo";
	static $DS_TIPO_SUPRESSAO = "Supressão";
	static $DS_TIPO_REAJUSTE = "Reajuste";
	static $DS_TIPO_PRORROGACAO = "Prorrogação";
	// ...............................................................

	static function getColecao(){
		return array(				
				self::$CD_TIPO_ACRESCIMO => self::$DS_TIPO_ACRESCIMO,
				self::$CD_TIPO_SUPRESSAO=> self::$DS_TIPO_SUPRESSAO,
				self::$CD_TIPO_REAJUSTE=> self::$DS_TIPO_REAJUSTE,
				self::$CD_TIPO_PRORROGACAO => self::$DS_TIPO_PRORROGACAO,
		);
	}	
}
?>