<?php
include_once (caminho_util . "dominio.class.php");
class dominioComissaoProcLicitatorio extends dominio {
		
	static $CD_CPL_I = 1;
	static $CD_CPL_II = 2;
	static $CD_CPL_III = 3;

	static $DS_CPL_I = "CPL-I";
	static $DS_CPL_II = "CPL-II";
	static $DS_CPL_III = "CPL-III";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_CPL_I => self::$DS_CPL_I,
				self::$CD_CPL_II => self::$DS_CPL_II,
				self::$CD_CPL_III => self::$DS_CPL_III,				
		);
		
		return $retorno;
	}
	
	static function getColecaoConsulta() {	
		return $retorno;
	}
	
	static function getCPLPorPregoeiro($nmPregoeiro, $retornarDescricao=false) {
		$retorno = null;
		if(existeStr1NaStr2ComSeparador($nmPregoeiro, "gorete")){
			$retorno = static::$CD_CPL_II;
		}else if(existeStr1NaStr2ComSeparador($nmPregoeiro, "odacy")){
			$retorno = static::$CD_CPL_I;
		}else if(existeStr1NaStr2ComSeparador($nmPregoeiro, "patricia".CAMPO_SEPARADOR."patrícia")){
				$retorno = static::$CD_CPL_III;
		}		
		
		if($retornarDescricao){
			$retorno = static::getDescricaoStatic($retorno);
		}
		return $retorno;
	}
	
		
}
?>