<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTpVigencia extends dominio{	
	static $CD_OPCAO_VIGENTES = 1;
	static $CD_OPCAO_NAO_VIGENTES = 2;
	static $CD_OPCAO_FUTURA = 3;
	
	static $DS_OPCAO_VIGENTES = "Vigentes";
	static $DS_OPCAO_NAO_VIGENTES = "Não vigentes";
	static $DS_OPCAO_FUTURA = "Futura";
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(
				constantes::$CD_OPCAO_TODOS => constantes::$DS_OPCAO_TODOS,
				self::$CD_OPCAO_VIGENTES => self::$DS_OPCAO_VIGENTES ,
				self::$CD_OPCAO_NAO_VIGENTES => self::$DS_OPCAO_NAO_VIGENTES,
				self::$CD_OPCAO_FUTURA => self::$DS_OPCAO_FUTURA,
		);
	}
	
	static function getColecaoComVazio(){
		return array(
				"" => constantes::$DS_OPCAO_SELECIONE,
				self::$CD_OPCAO_VIGENTES => self::$DS_OPCAO_VIGENTES ,
				self::$CD_OPCAO_NAO_VIGENTES => self::$DS_OPCAO_NAO_VIGENTES
		);
	}
	

}
?>