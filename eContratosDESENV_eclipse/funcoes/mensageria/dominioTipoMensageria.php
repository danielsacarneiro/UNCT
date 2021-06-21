<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioTipoMensageria extends dominio {
	static $CD_CONTRATO_IMPRORROGAVEL = "I";
	static $CD_CONTRATO_PRORROGAVEL = "P";
	static $CD_CONTRATO_LEMBRETE = "L";
		
	static $DS_CONTRATO_IMPRORROGAVEL = "Improrrogável";
	static $DS_CONTRATO_PRORROGAVEL = "Prorrogável";
	static $DS_CONTRATO_LEMBRETE = "Lembrete";
		
	static function getColecaoConsulta() {
		$array1 = static::getColecao();
		$array2 = array (
				constantes::$CD_OPCAO_NENHUM => constantes::$DS_OPCAO_NENHUM,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $array1, $array2);
		
		return $retorno;
	}
	
	
	static function getColecao() {
		$retorno = array (
				static::$CD_CONTRATO_LEMBRETE => self::$DS_CONTRATO_LEMBRETE,
				static::$CD_CONTRATO_IMPRORROGAVEL=> self::$DS_CONTRATO_IMPRORROGAVEL,
				static::$CD_CONTRATO_PRORROGAVEL => self::$DS_CONTRATO_PRORROGAVEL,
		);
				
		return $retorno;
	}
	
	static function getColecaoTipoAlertaGestor() {
		$retorno = array (
				static::$CD_CONTRATO_IMPRORROGAVEL,
				static::$CD_CONTRATO_PRORROGAVEL,
		);
	
		return $retorno;
	}
	
}
