<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioSituacaoContratoLicon extends dominio{	
	static $CD_SITUACAO_INCLUIDO = 1;
	static $CD_SITUACAO_EXISTENTE = 2;
	static $CD_SITUACAO_ERRO = 3;
	static $CD_SITUACAO_FORMALIZACAO_PENDENTE = 4;
	static $CD_SITUACAO_INCLUIDO_COM_OBS = 5;
		
	static $DS_SITUACAO_INCLUIDO = 'Inclu�do Sucesso';
	static $DS_SITUACAO_EXISTENTE = 'J� Existia';
	static $DS_SITUACAO_ERRO = "ERRO";
	static $DS_SITUACAO_FORMALIZACAO_PENDENTE = "Formaliza��o Pendente";
	static $DS_SITUACAO_INCLUIDO_COM_OBS = "Inclu�do com Obs.";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(				
				self::$CD_SITUACAO_INCLUIDO => self::$DS_SITUACAO_INCLUIDO,
				self::$CD_SITUACAO_INCLUIDO_COM_OBS => self::$DS_SITUACAO_INCLUIDO_COM_OBS,
				self::$CD_SITUACAO_EXISTENTE => self::$DS_SITUACAO_EXISTENTE,
				self::$CD_SITUACAO_ERRO => self::$DS_SITUACAO_ERRO,
				self::$CD_SITUACAO_FORMALIZACAO_PENDENTE => self::$DS_SITUACAO_FORMALIZACAO_PENDENTE,
		);
	}
	
	static function getColecaoManter(){
		$retorno = static::getColecaoComElementosARemover(array(self::$CD_SITUACAO_INCLUIDO_COM_OBS, self::$CD_SITUACAO_INCLUIDO));
		if(isUsuarioAdmin()){
			$retorno = static::getColecao();			
		}		
		return $retorno;
	}
	
}
?>