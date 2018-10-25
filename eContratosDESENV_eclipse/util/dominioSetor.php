<?php
include_once (caminho_util . "dominio.class.php");
class dominioSetor extends dominio {
	static $CD_SETOR_SAFI = 1;
	static $CD_SETOR_UNCT = 2;
	static $CD_SETOR_ATJA = 3;
	static $CD_SETOR_DILC = 4;
	static $CD_SETOR_PGE = 5;
	static $CD_SETOR_SAD = 6;
	static $CD_SETOR_UNCP = 7;
	static $CD_SETOR_CPL = 8;
	static $CD_SETOR_UNSG = 9;
	static $CD_SETOR_DIFIN = 10;
	static $CD_SETOR_DIENG = 11;
	static $CD_SETOR_SEFAZ = 12;
	static $CD_SETOR_GEBES = 13;
	static $CD_SETOR_GOV = 14;
	static $CD_SETOR_DAFE = 15;
	static $CD_SETOR_UNEO = 16;
	static $CD_SETOR_DISCON = 17;
	
	static $DS_SETOR_SAFI = "SAFI";
	static $DS_SETOR_UNCT = "UNCT";
	static $DS_SETOR_UNCP = "UNCP";
	static $DS_SETOR_ATJA = "ATJA";
	static $DS_SETOR_DILC = "DILC";
	static $DS_SETOR_PGE = "PGE";
	static $DS_SETOR_SAD = "SAD";
	static $DS_SETOR_CPL = "CPL";
	static $DS_SETOR_UNSG = "UNSG";
	static $DS_SETOR_DIFIN = "DIFIN";
	static $DS_SETOR_DIENG = "DIENG";
	static $DS_SETOR_SEFAZ = "SEFAZ";
	static $DS_SETOR_GEBES = "GEBES";
	static $DS_SETOR_GOV = "GOV";
	static $DS_SETOR_DAFE = "DAFE";
	static $DS_SETOR_UNEO = "UNEO";
	static $DS_SETOR_DISCON = "DISCON";
	
	// ...............................................................
	// Construtor
	static function getColecao() {
		$retorno = array (
				self::$CD_SETOR_SAFI => self::$DS_SETOR_SAFI,
				self::$CD_SETOR_DILC => self::$DS_SETOR_DILC,
				self::$CD_SETOR_DIENG => self::$DS_SETOR_DIENG,
				self::$CD_SETOR_DIFIN => self::$DS_SETOR_DIFIN,				
				self::$CD_SETOR_DISCON => self::$DS_SETOR_DISCON,
				self::$CD_SETOR_ATJA => self::$DS_SETOR_ATJA,
				self::$CD_SETOR_CPL => self::$DS_SETOR_CPL,
				self::$CD_SETOR_UNCP => self::$DS_SETOR_UNCP,
				self::$CD_SETOR_UNCT => self::$DS_SETOR_UNCT,
				self::$CD_SETOR_UNSG => self::$DS_SETOR_UNSG,
				self::$CD_SETOR_UNEO => self::$DS_SETOR_UNEO,
				self::$CD_SETOR_GEBES => self::$DS_SETOR_GEBES,
				self::$CD_SETOR_DAFE => self::$DS_SETOR_DAFE,
				self::$CD_SETOR_SAD => self::$DS_SETOR_SAD,
				self::$CD_SETOR_PGE => self::$DS_SETOR_PGE,
				self::$CD_SETOR_GOV => self::$DS_SETOR_GOV,
				//self::$CD_SETOR_GEBES => self::$DS_SETOR_GEBES,
		);
		
		//uksort ( $retorno, 'strnatcmp' );
		// sort($retorno);
		
		return $retorno;
	}
	static function getColecaoProcLicitatorio() {
		$retorno = array (
				self::$CD_SETOR_SEFAZ => self::$DS_SETOR_SEFAZ 
		);
		
		//uksort ( $retorno, 'strnatcmp' );
		
		return $retorno;
	}	
}
?>