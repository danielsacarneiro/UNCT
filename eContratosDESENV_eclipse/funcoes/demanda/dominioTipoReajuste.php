<?php
include_once (caminho_util . "dominio.class.php");
class dominioTipoReajuste extends dominio {
	static $CD_REAJUSTE_MONTANTE_A= 'A';
	static $CD_REAJUSTE_MONTANTE_B= 'B';
	static $CD_REAJUSTE_AMBOS = 'S';
	static $CD_REAJUSTE_OUTROS = 'N';
	
	static $DS_REAJUSTE_MONTANTE_A= 'Mont. A';
	static $DS_REAJUSTE_MONTANTE_B= 'Mont. B';
	static $DS_REAJUSTE_AMBOS = 'Ambos';
	static $DS_REAJUSTE_OUTROS = 'Outros';
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = $this->getColecao ();
	}

	// ...............................................................
	// Funcoes
	static function getColecao() {
		return array (
				self::$CD_REAJUSTE_AMBOS => self::$DS_REAJUSTE_AMBOS,
				self::$CD_REAJUSTE_MONTANTE_A => self::$DS_REAJUSTE_MONTANTE_A,
				self::$CD_REAJUSTE_MONTANTE_B => self::$DS_REAJUSTE_MONTANTE_B,
				self::$CD_REAJUSTE_OUTROS => self::$DS_REAJUSTE_OUTROS,
		);
	}
}