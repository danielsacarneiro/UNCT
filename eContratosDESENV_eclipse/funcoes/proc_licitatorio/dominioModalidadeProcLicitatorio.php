<?php
include_once (caminho_util . "dominio.class.php");
class dominioModalidadeProcLicitatorio extends dominio {
		
	static $CD_MODALIDADE_PREGAO_ELETRONICO = "PE";
	static $CD_MODALIDADE_PREGAO_PRESENCIAL = "PP";
	static $CD_MODALIDADE_CONCORRENCIA = "CO";
	static $CD_MODALIDADE_CONVITE = "CV";
	static $CD_MODALIDADE_TOMADA_PRECOS = "TP";
	

	static $DS_MODALIDADE_PREGAO_ELETRONICO = "Preg�o Eletr�nico";
	static $DS_MODALIDADE_PREGAO_PRESENCIAL = "Preg�o Presencial";
	static $DS_MODALIDADE_CONCORRENCIA = "Concorr�ncia";
	static $DS_MODALIDADE_CONVITE = "Convite";
	static $DS_MODALIDADE_TOMADA_PRECOS = "Tomada de Pre�os";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_MODALIDADE_PREGAO_ELETRONICO => self::$DS_MODALIDADE_PREGAO_ELETRONICO,
				self::$CD_MODALIDADE_PREGAO_PRESENCIAL => self::$DS_MODALIDADE_PREGAO_PRESENCIAL,
				self::$CD_MODALIDADE_CONCORRENCIA => self::$DS_MODALIDADE_CONCORRENCIA,
				self::$CD_MODALIDADE_CONVITE => self::$DS_MODALIDADE_CONVITE,
				self::$CD_MODALIDADE_TOMADA_PRECOS => self::$DS_MODALIDADE_TOMADA_PRECOS,
				
		);
		
		return $retorno;
	}
	
	static function getColecaoConsulta() {
	
		return $retorno;
	}
		
}
?>