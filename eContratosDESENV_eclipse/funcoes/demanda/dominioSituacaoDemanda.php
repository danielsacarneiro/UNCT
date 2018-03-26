<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioSituacaoDemanda extends dominio{	
	static $CD_SITUACAO_DEMANDA_ABERTA = 1;
	static $CD_SITUACAO_DEMANDA_FECHADA = 2;
	static $CD_SITUACAO_DEMANDA_EM_ANDAMENTO = 3;		
	static $CD_SITUACAO_DEMANDA_A_FAZER = 99;
	
	
	static $DS_SITUACAO_DEMANDA_ABERTA = "Aberta";
	static $DS_SITUACAO_DEMANDA_FECHADA = "Fechada";
	static $DS_SITUACAO_DEMANDA_EM_ANDAMENTO = "Em andamento";
	static $DS_SITUACAO_DEMANDA_A_FAZER = "A Fazer";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(				
				self::$CD_SITUACAO_DEMANDA_ABERTA => self::$DS_SITUACAO_DEMANDA_ABERTA,
				self::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO => self::$DS_SITUACAO_DEMANDA_EM_ANDAMENTO,
				self::$CD_SITUACAO_DEMANDA_FECHADA => self::$DS_SITUACAO_DEMANDA_FECHADA
		);
	}

	static function getColecaoHTMLConsulta(){
		$acrescentar= array(
				self::$CD_SITUACAO_DEMANDA_A_FAZER => self::$DS_SITUACAO_DEMANDA_A_FAZER,
		);
		
		$colecao = putElementoArray2NoArray1ComChaves($acrescentar, static::getColecao());
		return $colecao;
	}
	
	static function getColecaoAFazer(){
		return array(
				self::$CD_SITUACAO_DEMANDA_ABERTA => self::$DS_SITUACAO_DEMANDA_ABERTA,
				self::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO => self::$DS_SITUACAO_DEMANDA_EM_ANDAMENTO,
		);
	}
	
	
}
?>