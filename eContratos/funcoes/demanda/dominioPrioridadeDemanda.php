<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioPrioridadeDemanda extends dominio{
	static $CD_PRIORI_ALTA = 3;
	static $CD_PRIORI_MEDIA = 2;
	static $CD_PRIORI_BAIXA = 1;	

	static $DS_PRIORI_ALTA = "Alta";
	static $DS_PRIORI_MEDIA = "Média";
	static $DS_PRIORI_BAIXA = "Baixa";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(
				self::$CD_PRIORI_ALTA => self::$DS_PRIORI_ALTA,
				self::$CD_PRIORI_MEDIA => self::$DS_PRIORI_MEDIA,
				self::$CD_PRIORI_BAIXA => self::$DS_PRIORI_BAIXA				
		);
	}

}
?>