<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioCaracteristicasDemanda extends dominio {
	static $CD_NAO_VALIDA_DOCS = "01";
	static $DS_NAO_VALIDA_DOCS = "Não.valida.docs.";

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
				static::$CD_NAO_VALIDA_DOCS=> self::$DS_NAO_VALIDA_DOCS,
		);

		return $retorno;
	}

}
