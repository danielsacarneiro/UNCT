<?php
include_once(caminho_util."dominio.class.php");

  Class dominioSetor extends dominio{
  	static $CD_SETOR_SAFI= 1;
  	static $CD_SETOR_UNCT= 2;
  	static $CD_SETOR_ATJA= 3;
  	static $CD_SETOR_DILC= 4;
  	static $CD_SETOR_PGE= 5;
  	static $CD_SETOR_SAD= 6;

  	static $DS_SETOR_SAFI= "SAFI";
  	static $DS_SETOR_UNCT= "UNCT";
  	static $DS_SETOR_ATJA= "ATJA";
  	static $DS_SETOR_DILC= "DILC";
  	static $DS_SETOR_PGE= "_PGE";
  	static $DS_SETOR_SAD= "_SAD";
  	 
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_SETOR_SAFI => "SAFI",
				self::$CD_SETOR_DILC => self::$DS_SETOR_DILC,
				self::$CD_SETOR_UNCT => "UNCT",				
				self::$CD_SETOR_ATJA => "ATJA",				
				self::$CD_SETOR_SAD => self::$DS_SETOR_SAD,
				self::$CD_SETOR_PGE => self::$DS_SETOR_PGE
		);
	}
	
}
?>