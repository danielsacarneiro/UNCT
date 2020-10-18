<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioFaseDemanda extends dominio {
	static $CD_VISTO_JURIDICO = "01";
	static $CD_REVISAO_UNCT = "02";
	static $CD_FORNECEDOR_SEM_PENDENCIAS = "03";
	static $CD_PUBLICADO = "04";
	
	static $DS_VISTO_JURIDICO = "Visado ATJA";
	static $DS_REVISAO_UNCT = "Revisado UNCT";
	static $DS_FORNECEDOR_SEM_PENDENCIAS = "Assinado(Fornecedor)";
	static $DS_PUBLICADO = "Publicado";
	
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
				static::$CD_REVISAO_UNCT=> self::$DS_REVISAO_UNCT,
				static::$CD_VISTO_JURIDICO => self::$DS_VISTO_JURIDICO,
				static::$CD_FORNECEDOR_SEM_PENDENCIAS => self::$DS_FORNECEDOR_SEM_PENDENCIAS,
		);
				
		return $retorno;
	}

	/**
	 * usado para fases retiradas da planilha
	 */
	static function getColecaoPlanilha() {
		$retorno = array (
				static::$CD_FORNECEDOR_SEM_PENDENCIAS => self::$DS_FORNECEDOR_SEM_PENDENCIAS,
				static::$CD_PUBLICADO => self::$DS_PUBLICADO,
		);
	
		return $retorno;
	}	
}
