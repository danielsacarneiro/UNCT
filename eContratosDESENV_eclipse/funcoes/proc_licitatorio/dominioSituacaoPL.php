<?php
include_once (caminho_util . "dominio.class.php");
class dominioSituacaoPL extends dominio {
	static $CD_SITUACAO_PL_ABERTO = 1;
	static $CD_SITUACAO_PL_CONCLUIDO = 2;
	static $CD_SITUACAO_PL_FRACASSADO = 3;
	static $CD_SITUACAO_PL_DESERTO = 4;
	
	static $DS_SITUACAO_PL_ABERTO = "Aberto";
	static $DS_SITUACAO_PL_CONCLUIDO = "Concluído";
	static $DS_SITUACAO_PL_FRACASSADO = "Fracassado";
	static $DS_SITUACAO_PL_DESERTO = "Deserto";
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_SITUACAO_PL_ABERTO => self::$DS_SITUACAO_PL_ABERTO,
				self::$CD_SITUACAO_PL_CONCLUIDO => self::$DS_SITUACAO_PL_CONCLUIDO,
				self::$CD_SITUACAO_PL_FRACASSADO => self::$DS_SITUACAO_PL_FRACASSADO,
				self::$CD_SITUACAO_PL_DESERTO => self::$DS_SITUACAO_PL_DESERTO,
		);
		
		return $retorno;
	}	
}
?>