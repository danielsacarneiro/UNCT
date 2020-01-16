<?php
include_once (caminho_util . "dominio.class.php");
class dominioSituacaoSolicCompra extends dominio {
	static $CD_SITUACAO_ABERTA = 1;
	static $CD_SITUACAO_CONCLUIDA = 2;
	
	static $DS_SITUACAO_ABERTA = "Aberta";
	static $DS_SITUACAO_CONCLUIDA = "Concluída";
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_SITUACAO_ABERTA => self::$DS_SITUACAO_ABERTA,
				self::$CD_SITUACAO_CONCLUIDA => self::$DS_SITUACAO_CONCLUIDA,
		);
		
		return $retorno;
	}	
}
?>