<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioSituacaoDemanda extends dominio{	
	static $CD_SITUACAO_DEMANDA_ABERTA = 1;
	static $CD_SITUACAO_DEMANDA_FECHADA = 2;
	static $CD_SITUACAO_DEMANDA_EM_ANDAMENTO = 3;
	static $CD_SITUACAO_DEMANDA_SUSPENSA = 4;
	static $CD_SITUACAO_DEMANDA_ARQUIVADA = 5;
	static $CD_SITUACAO_DEMANDA_A_REVISAR = 6;
	static $CD_SITUACAO_DEMANDA_A_FAZER = 99;
	
	
	static $DS_SITUACAO_DEMANDA_ABERTA = "Aberta";
	static $DS_SITUACAO_DEMANDA_FECHADA = "Fechada";
	static $DS_SITUACAO_DEMANDA_EM_ANDAMENTO = "Em andamento";
	static $DS_SITUACAO_DEMANDA_SUSPENSA = "Suspensa";
	static $DS_SITUACAO_DEMANDA_A_FAZER = "A Fazer";
	static $DS_SITUACAO_DEMANDA_ARQUIVADA = "Arquivada";
	static $DS_SITUACAO_DEMANDA_A_REVISAR = "A revisar";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		/*return array(				
				self::$CD_SITUACAO_DEMANDA_ABERTA => self::$DS_SITUACAO_DEMANDA_ABERTA,
				self::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO => self::$DS_SITUACAO_DEMANDA_EM_ANDAMENTO,
				self::$CD_SITUACAO_DEMANDA_FECHADA => self::$DS_SITUACAO_DEMANDA_FECHADA,
				self::$CD_SITUACAO_DEMANDA_SUSPENSA => self::$DS_SITUACAO_DEMANDA_SUSPENSA,
				self::$CD_SITUACAO_DEMANDA_ARQUIVADA => self::$DS_SITUACAO_DEMANDA_ARQUIVADA,
		);*/
		
		$array1= static::getColecaoAFazer();
		$array2= static::getColecaoFechada();
		
		$colecao = putElementoArray2NoArray1ComChaves($array1, $array2);
		
		$array3= array(
				self::$CD_SITUACAO_DEMANDA_SUSPENSA => self::$DS_SITUACAO_DEMANDA_SUSPENSA,
		);
		
		$colecao = putElementoArray2NoArray1ComChaves($colecao, $array3);
		return $colecao;
		
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
				self::$CD_SITUACAO_DEMANDA_A_REVISAR => self::$DS_SITUACAO_DEMANDA_A_REVISAR,
		);
	}
	
	static function getColecaoFechada(){
		return array(
				self::$CD_SITUACAO_DEMANDA_FECHADA => self::$DS_SITUACAO_DEMANDA_FECHADA,
				self::$CD_SITUACAO_DEMANDA_ARQUIVADA => self::$DS_SITUACAO_DEMANDA_ARQUIVADA,
		);
	}
	
	static function getCorColuna($cdSituacao){
		$classColunaSituacao = "tabeladadosdestacadoverde";
		if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_FECHADA){
			$classColunaSituacao = "tabeladadosdestacadoazulclaro";
		} else if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA){
			$classColunaSituacao = "tabeladadosdestacado";
		} else if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_SUSPENSA){
			$classColunaSituacao = "tabeladadosdestacadovermelho";
		} else if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ARQUIVADA){
			$classColunaSituacao = "tabeladadosdestacadomarrom";
		} else if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_REVISAR){
			$classColunaSituacao = "tabeladadosdestacadolaranja";
		}
				
		
		return $classColunaSituacao;		
	}
	
	
}
?>