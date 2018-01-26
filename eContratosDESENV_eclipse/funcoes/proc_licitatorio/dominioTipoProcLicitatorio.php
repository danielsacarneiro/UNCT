<?php
include_once (caminho_util . "dominio.class.php");
class dominioTipoProcLicitatorio extends dominio {
		
	static $CD_TIPO_MENOR_PRECO = "MP";
	static $CD_TIPO_MELHOR_TECNICA = "MT";
	static $CD_TIPO_TECNICA_PRECO = "TP";
	static $CD_TIPO_MAIOR_LANCE = "ML";
	static $CD_TIPO_MAIOR_DESCONTO = "MD";
	
	static $DS_TIPO_MENOR_PRECO = "Menor Preço";
	static $DS_TIPO_MELHOR_TECNICA = "Melhor Técnica";
	static $DS_TIPO_TECNICA_PRECO = "Técnica e preço";
	static $DS_TIPO_MAIOR_LANCE = "Maior lance ou oferta";
	static $DS_TIPO_MAIOR_DESCONTO = "Maior desconto";
	
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_TIPO_MENOR_PRECO => self::$DS_TIPO_MENOR_PRECO,
				self::$CD_TIPO_MELHOR_TECNICA => self::$DS_TIPO_MELHOR_TECNICA,
				self::$CD_TIPO_TECNICA_PRECO => self::$DS_TIPO_TECNICA_PRECO,
				self::$CD_TIPO_MAIOR_LANCE => self::$DS_TIPO_MAIOR_LANCE,
				self::$CD_TIPO_MAIOR_DESCONTO => self::$DS_TIPO_MAIOR_DESCONTO,
		);
		
		return $retorno;
	}
	
	static function getColecaoConsulta() {
	
		return $retorno;
	}
		
}
?>