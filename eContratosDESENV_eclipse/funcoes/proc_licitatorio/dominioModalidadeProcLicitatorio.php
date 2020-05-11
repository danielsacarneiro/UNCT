<?php
include_once (caminho_util . "dominio.class.php");
class dominioModalidadeProcLicitatorio extends dominio {
		
	static $CD_MODALIDADE_PREGAO_ELETRONICO = "PE";
	static $CD_MODALIDADE_PREGAO_PRESENCIAL = "PP";
	static $CD_MODALIDADE_CONCORRENCIA = "CC";
	static $CD_MODALIDADE_CONVITE = "CV";
	static $CD_MODALIDADE_TOMADA_PRECOS = "TP";	
	static $CD_MODALIDADE_DISPENSA = "DL";
	static $CD_MODALIDADE_INEXIGIBILIDADE = "IN";
	

	static $DS_MODALIDADE_PREGAO_ELETRONICO = "Preg�o Eletr�nico";
	static $DS_MODALIDADE_PREGAO_PRESENCIAL = "Preg�o Presencial";
	static $DS_MODALIDADE_CONCORRENCIA = "Concorr�ncia";
	static $DS_MODALIDADE_CONVITE = "Convite";
	static $DS_MODALIDADE_TOMADA_PRECOS = "Tomada de Pre�os";
	static $DS_MODALIDADE_DISPENSA = "Dispensa";
	static $DS_MODALIDADE_INEXIGIBILIDADE = "Inexigibilidade";
	
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
				self::$CD_MODALIDADE_DISPENSA => self::$DS_MODALIDADE_DISPENSA,
				self::$CD_MODALIDADE_INEXIGIBILIDADE => self::$DS_MODALIDADE_INEXIGIBILIDADE,
				
		);
		
		return $retorno;
	}
		
	static function getColecaoImportacaoPlanilha(){
		return array(
				self::$CD_MODALIDADE_PREGAO_ELETRONICO => "eletr�nico*eletronico",
				self::$CD_MODALIDADE_PREGAO_PRESENCIAL => "presencial",
				self::$CD_MODALIDADE_CONCORRENCIA => "concorrenc*concorr�ncia",
				self::$CD_MODALIDADE_CONVITE => "carta*convite",
				self::$CD_MODALIDADE_TOMADA_PRECOS => "tomada*preco*pre�o",
				self::$CD_MODALIDADE_DISPENSA => "dispensa",
				self::$CD_MODALIDADE_INEXIGIBILIDADE => "inexig",
		);
	}
	
	/**
	 * a ordem eh importante porque a busca pelo codigo se dara em uma string por extenso
	 * @return string[]
	 */
	static function getColecaoImportacaoPlanilhaPorCodigoSimples(){
		return array(
				self::$CD_MODALIDADE_PREGAO_PRESENCIAL,
				self::$CD_MODALIDADE_CONCORRENCIA,
				self::$CD_MODALIDADE_CONVITE,
				self::$CD_MODALIDADE_TOMADA_PRECOS,
				self::$CD_MODALIDADE_DISPENSA,
				self::$CD_MODALIDADE_INEXIGIBILIDADE,
				self::$CD_MODALIDADE_PREGAO_ELETRONICO,
		);
	}
	
}
?>