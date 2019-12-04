<?php
include_once(caminho_util."dominio.class.php");

Class dominioProrrogacaoFiltroConsolidacao extends dominio{
	static $CD_PRORROGAVEL  = 1;
	static $CD_NAOPRORROGAVEL  = 2;
	static $CD_PERMITE_EXCEPCIONAL = 3;
	static $CD_NAOPERMITE_EXCEPCIONAL = 4;

	static $DS_PRORROGAVEL  = "Prorrog�veis";
	static $DS_NAOPRORROGAVEL  = "N�o prorrog�veis";
	static $DS_PERMITE_EXCEPCIONAL = "Permitem excepcional";
	static $DS_NAOPERMITE_EXCEPCIONAL = "J� tiveram excepcional";
	
	// ...............................................................
	// Construtor
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )

	static function getColecao(){
		return array(
				self::$CD_PRORROGAVEL => self::$DS_PRORROGAVEL,
				self::$CD_NAOPRORROGAVEL => self::$DS_NAOPRORROGAVEL,
				self::$CD_PERMITE_EXCEPCIONAL => self::$DS_PERMITE_EXCEPCIONAL,
				self::$CD_NAOPERMITE_EXCEPCIONAL => self::$DS_NAOPERMITE_EXCEPCIONAL,
		);
	}

}
?>