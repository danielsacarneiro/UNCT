<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioContratoProducaoEfeitos extends dominio {
			
	static $CD_VISTO_COM_EFEITOS = "S";
	static $CD_VISTO_ULTTERMO = "N";
	
	static $DS_VISTO_COM_EFEITOS = "Com efeitos";
	static $DS_VISTO_ULTTERMO = "Últ.Termo";	
	
	static function getColecao() {
		$retorno = array (
				static::$CD_VISTO_COM_EFEITOS=> self::$DS_VISTO_COM_EFEITOS,
				static::$CD_VISTO_ULTTERMO => self::$DS_VISTO_ULTTERMO,
		);
				
		return $retorno;
	}
}
