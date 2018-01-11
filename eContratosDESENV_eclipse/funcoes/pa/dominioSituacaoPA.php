<?php
include_once (caminho_util . "dominio.class.php");
class dominioSituacaoPA extends dominio {
	static $CD_SITUACAO_PA_INSTAURADO = 1;
	static $CD_SITUACAO_PA_ARQUIVADO = 2;
	static $CD_SITUACAO_PA_ENCERRADO = 3;
	
	static $DS_SITUACAO_PA_INSTAURADO = "Instaurado";
	static $DS_SITUACAO_PA_ARQUIVADO = "Arquivado";
	static $DS_SITUACAO_PA_ENCERRADO = "Encerrado";
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_SITUACAO_PA_INSTAURADO => self::$DS_SITUACAO_PA_INSTAURADO,
				self::$CD_SITUACAO_PA_ARQUIVADO => self::$DS_SITUACAO_PA_ARQUIVADO,
				self::$CD_SITUACAO_PA_ENCERRADO => self::$DS_SITUACAO_PA_ENCERRADO
		);
		
		return $retorno;
	}
	
	static function getColecaoSituacaoAtivos() {
		$retorno = array (
				self::$CD_SITUACAO_PA_INSTAURADO => self::$DS_SITUACAO_PA_INSTAURADO,
		);
	
		return $retorno;
	}
	
	static function getColecaoSituacaoTerminados() {
		$retorno = array (
				self::$CD_SITUACAO_PA_ARQUIVADO => self::$DS_SITUACAO_PA_ARQUIVADO,
				self::$CD_SITUACAO_PA_ENCERRADO => self::$DS_SITUACAO_PA_ENCERRADO
		);
	
		return $retorno;
	}
	
}
?>