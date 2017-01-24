<?php
include_once(caminho_util."dominio.class.php");

  Class dominioAutorizacao extends dominio{
  	
  	static $CD_AUTORIZ_NENHUM = 0;
  	static $CD_AUTORIZ_PGE = 1;
  	static $CD_AUTORIZ_SAD = 2;
  	static $CD_AUTORIZ_SAD_PGE = 3;
  	static $CD_AUTORIZ_SAD_PGE_GOV = 4;
  	 
  	static $DS_AUTORIZ_PGE = "PGE";
  	static $DS_AUTORIZ_SAD = "SAD";
  	static $DS_AUTORIZ_SAD_PGE = "PGE e SAD";
  	static $DS_AUTORIZ_SAD_PGE_GOV = "PGE, SAD e GOV";
  	 
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = array(				   
				self::$CD_AUTORIZ_NENHUM => "Nenhum",
				self::$CD_AUTORIZ_PGE => self::$DS_AUTORIZ_PGE,
				self::$CD_AUTORIZ_SAD => self::$DS_AUTORIZ_SAD,
				self::$CD_AUTORIZ_SAD_PGE => self::$DS_AUTORIZ_SAD_PGE,
				self::$CD_AUTORIZ_SAD_PGE_GOV => self::$DS_AUTORIZ_SAD_PGE_GOV				
				);        
	}

// ...............................................................
// Funcoes
	
}
?>