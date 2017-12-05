<?php
include_once (caminho_util . "dominio.class.php");
class dominioSituacaoPA extends dominio {
	static $CD_SITUACAO_PA_INSTAURADO = 1;
	static $CD_SITUACAO_PA_EM_ANDAMENTO = 2;
	static $CD_SITUACAO_PA_ARQUIVADO = 3;
	static $CD_SITUACAO_PA_ENCERRADO = 4;
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_SITUACAO_PA_INSTAURADO => "Instaurado",
				self::$CD_SITUACAO_PA_EM_ANDAMENTO => "Em Andamento",
				self::$CD_SITUACAO_PA_ARQUIVADO => "Arquivado",
				self::$CD_SITUACAO_PA_ENCERRADO => "Encerrado" 
		);
		
		return $retorno;
	}
	
	static function getColecaoSituacaoAtivos() {
		$retorno = array (
				self::$CD_SITUACAO_PA_INSTAURADO => "Instaurado",
				self::$CD_SITUACAO_PA_EM_ANDAMENTO => "Em Andamento",
		);
	
		return $retorno;
	}
	
}
?>