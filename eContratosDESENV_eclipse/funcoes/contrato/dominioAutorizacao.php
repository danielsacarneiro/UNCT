<?php
include_once (caminho_util . "dominio.class.php");
class dominioAutorizacao extends dominio {
	static $CD_AUTORIZ_NENHUM = 1;
	static $CD_AUTORIZ_PGE = 2;
	static $CD_AUTORIZ_SAD = 3;
	static $CD_AUTORIZ_SAD_PGE = 4;
	static $CD_AUTORIZ_SAD_PGE_GOV = 5;
	static $CD_AUTORIZ_GOV = 6;
	
	static $DS_AUTORIZ_PGE = "PGE";
	static $DS_AUTORIZ_SAD = "SAD";
	static $DS_AUTORIZ_SAD_PGE = "PGE e SAD";
	static $DS_AUTORIZ_SAD_PGE_GOV = "PGE, SAD e GOV";
	static $DS_AUTORIZ_GOV = "GOV";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = $this->getColecao ();
	}
	
	// ...............................................................
	// Funcoes
	static function getColecao() {
		return array (
				self::$CD_AUTORIZ_NENHUM => "Nenhum",
				self::$CD_AUTORIZ_PGE => self::$DS_AUTORIZ_PGE,
				self::$CD_AUTORIZ_SAD => self::$DS_AUTORIZ_SAD,
				self::$CD_AUTORIZ_SAD_PGE => self::$DS_AUTORIZ_SAD_PGE,
				self::$CD_AUTORIZ_SAD_PGE_GOV => self::$DS_AUTORIZ_SAD_PGE_GOV 
		);
	}
	static function temAutorizacao($cdAutorizacao, $colecaoAutorizacao) {
		return in_array ( $cdAutorizacao, $colecaoAutorizacao );
	}
	static function checkedTemAutorizacao($cdAutorizacao, $colecaoAutorizacao) {
		$retorno = "";
		if ($colecaoAutorizacao != null && self::temAutorizacao ( $cdAutorizacao, $colecaoAutorizacao )) {
			$retorno = " checked ";
		}
		return $retorno;
	}
	static function getColecaoCdAutorizacaoIntercace($colecaoAutorizacao, $InOR_AND) {
		if($InOR_AND == constantes::$CD_OPCAO_OR)
			return self::getColecaoCdAutorizacaoIntercaceOR ( $colecaoAutorizacao );
		else
			return self::getColecaoCdAutorizacaoIntercaceAND( $colecaoAutorizacao );
	}
	static function getColecaoCdAutorizacaoIntercaceOR($colecaoAutorizacao) {
		$temSAD = in_array ( self::$CD_AUTORIZ_SAD, $colecaoAutorizacao );
		$temPGE = in_array ( self::$CD_AUTORIZ_PGE, $colecaoAutorizacao );
		$temGOV = in_array ( self::$CD_AUTORIZ_GOV, $colecaoAutorizacao );
		$temNenhum = in_array ( self::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao );
		
		$retorno = "";
		
		if ($temSAD || $temPGE || $temGOV) {
			$retorno [] = self::$CD_AUTORIZ_SAD_PGE_GOV;
		}
		
		if ($temSAD || $temPGE) {
			$retorno [] = self::$CD_AUTORIZ_SAD_PGE;
		}
		
		if ($temSAD) {
			$retorno [] = self::$CD_AUTORIZ_SAD;
		}
		
		if ($temPGE) {
			$retorno [] = self::$CD_AUTORIZ_PGE;
		}
		
		if ($temNenhum) {
			$retorno [] = self::$CD_AUTORIZ_NENHUM;
		}
		
		return $retorno;
	}
	static function getColecaoCdAutorizacaoIntercaceAND($colecaoAutorizacao) {
		$temSAD = in_array ( self::$CD_AUTORIZ_SAD, $colecaoAutorizacao );
		$temPGE = in_array ( self::$CD_AUTORIZ_PGE, $colecaoAutorizacao );
		$temGOV = in_array ( self::$CD_AUTORIZ_GOV, $colecaoAutorizacao );
		$temNenhum = in_array ( self::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao );
		
		$retorno = "";
		
		if($temNenhum){
			$retorno =  self::$CD_AUTORIZ_NENHUM;
		}else if ($temSAD && $temPGE && $temGOV) {
			$retorno = self::$CD_AUTORIZ_SAD_PGE_GOV;
		} else if ($temSAD && $temPGE) {
			$retorno = self::$CD_AUTORIZ_SAD_PGE;
		} else if ($temGOV) {
			$retorno = self::$CD_AUTORIZ_GOV;
		} else if ($temSAD) {
			$retorno = self::$CD_AUTORIZ_SAD;
		} else if ($temPGE) {
			$retorno = self::$CD_AUTORIZ_PGE;
		} else {
			$retorno = self::$CD_AUTORIZ_NENHUM;
		}
		
		return $retorno;
	}
}
?>