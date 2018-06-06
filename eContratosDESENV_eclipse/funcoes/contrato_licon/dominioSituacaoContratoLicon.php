<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioSituacaoContratoLicon extends dominio{	
	static $CD_SITUACAO_INCLUIDO = 1;
	static $CD_SITUACAO_EXISTENTE = 2;
	static $CD_SITUACAO_ERRO = 3;
		
	static $DS_SITUACAO_INCLUIDO = 'Inclu�do';
	static $DS_SITUACAO_EXISTENTE = 'J� Existia';
	static $DS_SITUACAO_ERRO = "ERRO";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(				
				self::$CD_SITUACAO_INCLUIDO => self::$DS_SITUACAO_INCLUIDO,
				self::$CD_SITUACAO_EXISTENTE => self::$DS_SITUACAO_EXISTENTE,
				self::$CD_SITUACAO_ERRO => self::$DS_SITUACAO_ERRO,
		);
	}	
}
?>