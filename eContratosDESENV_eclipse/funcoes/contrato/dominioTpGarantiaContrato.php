<?php
include_once(caminho_util. "dominio.class.php");

Class dominioTpGarantiaContrato extends dominio{
	 
	static $CD_TIPO_GARANTIA_NAO_PRESTADA = 99;
	static $CD_TIPO_GARANTIA_CAUCAO_DINHEIRO = 1;
	static $CD_TIPO_GARANTIA_SEGURO_GARANTIA = 2;
	static $CD_TIPO_GARANTIA_FIANCA_BANCARIA = 3;
	 	 
	static $DS_TIPO_GARANTIA_NAO_PRESTADA = "Nуo prestada";
	static $DS_TIPO_GARANTIA_CAUCAO_DINHEIRO = "Cauчуo em dinheiro";
	static $DS_TIPO_GARANTIA_SEGURO_GARANTIA = "Seguro Garantia";
	static $DS_TIPO_GARANTIA_FIANCA_BANCARIA = "Fianчa Bancсria";
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(
				self::$CD_TIPO_GARANTIA_CAUCAO_DINHEIRO => self::$DS_TIPO_GARANTIA_CAUCAO_DINHEIRO,
				self::$CD_TIPO_GARANTIA_SEGURO_GARANTIA => self::$DS_TIPO_GARANTIA_SEGURO_GARANTIA,
				self::$CD_TIPO_GARANTIA_FIANCA_BANCARIA => self::$DS_TIPO_GARANTIA_FIANCA_BANCARIA,
				self::$CD_TIPO_GARANTIA_NAO_PRESTADA => self::$DS_TIPO_GARANTIA_NAO_PRESTADA
		);
	}

}

?>