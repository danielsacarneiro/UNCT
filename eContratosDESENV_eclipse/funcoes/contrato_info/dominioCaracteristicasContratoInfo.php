<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioCaracteristicasContratoInfo extends dominio {
	static $CD_CONTRATO_PADRONIZADO_PGE = "01";
	static $DS_CONTRATO_PADRONIZADO_PGE = "Parecer.Referencial.PGE";

	/*static function getColecaoConsulta() {
		$array1 = static::getColecao();
		$array2 = array (
				constantes::$CD_OPCAO_NENHUM => constantes::$DS_OPCAO_NENHUM,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $array1, $array2);

		return $retorno;
	}*/


	static function getColecao() {
		$retorno = array (
				static::$CD_CONTRATO_PADRONIZADO_PGE=> self::$DS_CONTRATO_PADRONIZADO_PGE,
		);

		return $retorno;
	}

}
