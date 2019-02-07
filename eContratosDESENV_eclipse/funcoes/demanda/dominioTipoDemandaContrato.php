<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioTipoDemandaContrato extends dominio {
	/*static $CD_TIPO_PRORROGACAO = "PR";
	static $CD_TIPO_REAJUSTE = "RE";
	static $CD_TIPO_MODIFICACAO = "MO";
	static $CD_TIPO_OUTROS = "99";*/
	//O NUMERO 1 ERA O ANTIGO TIPO = CONTRATO
	static $CD_TIPO_DEMANDA_CONTRATO = "01";
	static $CD_TIPO_ACRESCIMO = "03";
	static $CD_TIPO_SUPRESSAO = "04";
	static $CD_TIPO_REAJUSTE = "05";
	static $CD_TIPO_MATER = "06";
	static $CD_TIPO_MODIFICACAO = "07";
	static $CD_TIPO_PRORROGACAO = "08";
	static $CD_TIPO_APOSTILAMENTO = "10";
	static $CD_TIPO_REEQUILIBRIO = "11";
	static $CD_TIPO_OUTROS = "99";
	
	static $DS_TIPO_ACRESCIMO = "Acréscimo";
	static $DS_TIPO_SUPRESSAO = "Supressão";
	static $DS_TIPO_REAJUSTE = "Reajuste";
	static $DS_TIPO_MODIFICACAO = "Modificação";
	static $DS_TIPO_MATER = "Mater";
	static $DS_TIPO_PRORROGACAO = "Prorrogação";
	static $DS_TIPO_APOSTILAMENTO = "Apostilamento";
	static $DS_TIPO_REEQUILIBRIO = "Reequilíbrio";
	static $DS_TIPO_OUTROS = "Outros";

	static function getColecaoConsulta() {
		$array1 = static::getColecao();
		$array2 = array (
				constantes::$CD_OPCAO_NENHUM => constantes::$DS_OPCAO_NENHUM,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $array1, $array2);
		
		return $retorno;
	}
	
	static function getColecaoAntiga() {
		$array1 = static::getColecao();
		$array2 = array (
				static::$CD_TIPO_APOSTILAMENTO => static::$DS_TIPO_APOSTILAMENTO,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $array1, $array2);
	
		return $retorno;
	}
	
	static function getColecao() {
		$retorno = array (
				static::$CD_TIPO_MATER=> self::$DS_TIPO_MATER,
				static::$CD_TIPO_PRORROGACAO => self::$DS_TIPO_PRORROGACAO,
				static::$CD_TIPO_REAJUSTE => self::$DS_TIPO_REAJUSTE,
				static::$CD_TIPO_REEQUILIBRIO => self::$DS_TIPO_REEQUILIBRIO,
				static::$CD_TIPO_ACRESCIMO => self::$DS_TIPO_ACRESCIMO,
				static::$CD_TIPO_SUPRESSAO => self::$DS_TIPO_SUPRESSAO,
				static::$CD_TIPO_MODIFICACAO => self::$DS_TIPO_MODIFICACAO,
				static::$CD_TIPO_OUTROS => self::$DS_TIPO_OUTROS,
		);
				
		return $retorno;
	}
	
	static function getColecaolegado() {
		$array1 = static::getColecao();
		$array2 = array (
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER => dominioEspeciesContrato::$DS_ESPECIE_CONTRATO_MATER,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => dominioEspeciesContrato::$DS_ESPECIE_CONTRATO_APOSTILAMENTO,
		);
		$retorno = array_merge($array1, $array2);
				
		return $retorno;
	}
	
}
