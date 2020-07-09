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
	

	static $DS_MODALIDADE_PREGAO_ELETRONICO = "Pregão Eletrônico";
	static $DS_MODALIDADE_PREGAO_PRESENCIAL = "Pregão Presencial";
	static $DS_MODALIDADE_CONCORRENCIA = "Concorrência";
	static $DS_MODALIDADE_CONVITE = "Convite";
	static $DS_MODALIDADE_TOMADA_PRECOS = "Tomada de Preços";
	static $DS_MODALIDADE_DISPENSA = "Dispensa";
	static $DS_MODALIDADE_INEXIGIBILIDADE = "Inexigibilidade";
	
	//PROFISCO
	static $CD_MODALIDADE_LPI = "LPI";
	static $DS_MODALIDADE_LPI = "Licitação Pública Internacional";
	static $DS_PROFISCO = "PROFISCO";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecaoPROFISCO() {
		$retorno = array (
				self::$CD_MODALIDADE_LPI => SELF::$DS_PROFISCO . "-" . self::$CD_MODALIDADE_LPI,	
		);
	
		return $retorno;
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
		
		$retorno = array_merge_keys($retorno, self::getColecaoPROFISCO());
		
		return $retorno;
	}
		
	static function getColecaoImportacaoPlanilha(){
		return array(
				self::$CD_MODALIDADE_PREGAO_ELETRONICO => "eletrônico*eletronico",
				self::$CD_MODALIDADE_PREGAO_PRESENCIAL => "presencial",
				self::$CD_MODALIDADE_CONCORRENCIA => "concorrenc*concorrência",
				self::$CD_MODALIDADE_CONVITE => "carta*convite",
				self::$CD_MODALIDADE_TOMADA_PRECOS => "tomada*preco*preço",
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