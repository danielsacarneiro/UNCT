<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioSituacaoDemanda extends dominio{	
	static $CD_SITUACAO_DEMANDA_ABERTA = 1;
	static $CD_SITUACAO_DEMANDA_FECHADA = 2;
	
	static $DS_SITUACAO_DEMANDA_ABERTA = "Aberta";
	static $DS_SITUACAO_DEMANDA_FECHADA = "Fechada";
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(				
				self::$CD_SITUACAO_DEMANDA_ABERTA => self::$DS_SITUACAO_DEMANDA_ABERTA,
				self::$CD_SITUACAO_DEMANDA_FECHADA => self::$DS_SITUACAO_DEMANDA_FECHADA
		);
	}

}
?>