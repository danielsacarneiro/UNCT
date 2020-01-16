<?php
include_once (caminho_util . "dominio.class.php");
class dominioTipoSolicCompra extends dominio {
		
	static $CD_TIPO_AQUISICAO = 1;
	static $CD_TIPO_SERVICO = 2;
	static $CD_TIPO_OBRA = 3;
	
	static $DS_TIPO_AQUISICAO = "Aquisiчуo";
	static $DS_TIPO_SERVICO = "Serviчo";
	static $DS_TIPO_OBRA = "Obra";	
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_TIPO_AQUISICAO => self::$DS_TIPO_AQUISICAO,
				self::$CD_TIPO_SERVICO => self::$DS_TIPO_SERVICO,
				self::$CD_TIPO_OBRA => self::$DS_TIPO_OBRA,
		);
		
		return $retorno;
	}
			
}
?>