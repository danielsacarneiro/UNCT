<?php
include_once (caminho_util . "dominio.class.php");
class dominioSituacaoPA extends dominio {
	static $CD_SITUACAO_PA_INSTAURADO = 1;
	static $CD_SITUACAO_PA_ARQUIVADO = 2;
	static $CD_SITUACAO_PA_ENCERRADO = 3;
	static $CD_SITUACAO_PA_AGUARDANDO_ACAO = 4;
	static $CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA = 5;
	static $CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO = 6;
	static $CD_SITUACAO_PA_EM_COBRANCA = 7;
	
	static $CD_SITUACAO_PA_EM_ANDAMENTO= 99;
	
	static $DS_SITUACAO_PA_INSTAURADO = "Instaurado";
	static $DS_SITUACAO_PA_ARQUIVADO = "Arquivado";
	static $DS_SITUACAO_PA_ENCERRADO = "Encerrado";
	static $DS_SITUACAO_PA_AGUARDANDO_ACAO = "Aguardando ATJA";
	static $DS_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA = "Aguardando prazo";
	static $DS_SITUACAO_PA_AGUARDANDO_PUBLICACAO = "Aguardando publica��o";
	static $DS_SITUACAO_PA_EM_COBRANCA = "Em cobran�a";
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_SITUACAO_PA_INSTAURADO => self::$DS_SITUACAO_PA_INSTAURADO,
				self::$CD_SITUACAO_PA_AGUARDANDO_ACAO => self::$DS_SITUACAO_PA_AGUARDANDO_ACAO,
				self::$CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA => self::$DS_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA,
				self::$CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO => self::$DS_SITUACAO_PA_AGUARDANDO_PUBLICACAO,
				self::$CD_SITUACAO_PA_EM_COBRANCA => self::$DS_SITUACAO_PA_EM_COBRANCA,
				self::$CD_SITUACAO_PA_ARQUIVADO => self::$DS_SITUACAO_PA_ARQUIVADO,
				self::$CD_SITUACAO_PA_ENCERRADO => self::$DS_SITUACAO_PA_ENCERRADO,
				
		);
		
		return $retorno;
	}
	
	static function getColecaoConsulta() {
		include_once (caminho_funcoes . "pa/dominioSituacaoPA.php");
		
		$acrescentar= array(
				self::$CD_SITUACAO_PA_EM_ANDAMENTO => dominioSituacaoDemanda::$DS_SITUACAO_DEMANDA_EM_ANDAMENTO,
				self::$CD_SITUACAO_PA_INSTAURADO => "Ainda n�o movimentado",
				self::$CD_SITUACAO_PA_AGUARDANDO_ACAO => self::$DS_SITUACAO_PA_AGUARDANDO_ACAO,
				self::$CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA => self::$DS_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA,
				self::$CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO => self::$DS_SITUACAO_PA_AGUARDANDO_PUBLICACAO,
				self::$CD_SITUACAO_PA_EM_COBRANCA => self::$DS_SITUACAO_PA_EM_COBRANCA,
		);
		
		$retorno = putElementoArray2NoArray1ComChaves($acrescentar, static::getColecaoSituacaoTerminados());
	
		return $retorno;
	}
	
	static function getColecaoSituacaoAtivos() {
		$colecao = array(
				self::$CD_SITUACAO_PA_INSTAURADO,
				self::$CD_SITUACAO_PA_AGUARDANDO_ACAO,
				self::$CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA,
				self::$CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO,
				self::$CD_SITUACAO_PA_EM_COBRANCA,
		);
			
		return static::getColecaoComDescricao($colecao);
	}
	
	static function getColecaoSituacaoTerminados() {
		
		$colecao = array(
				self::$CD_SITUACAO_PA_ARQUIVADO,
				self::$CD_SITUACAO_PA_ENCERRADO,
		);
		
		return static::getColecaoComDescricao($colecao);
	}
	
	static function getColecaoSituacaoPendentes() {
	
		$colecao = array(
				self::$CD_SITUACAO_PA_AGUARDANDO_ACAO,
				self::$CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO,
				self::$CD_SITUACAO_PA_EM_COBRANCA,
		);
	
		return static::getColecaoComDescricao($colecao);
	}
	
	static function getColecaoSituacaoIndependenteDemanda() {
		$colecao = array(
				self::$CD_SITUACAO_PA_AGUARDANDO_NOTIFICACAO_ENVIADA,
				self::$CD_SITUACAO_PA_AGUARDANDO_PUBLICACAO,
				self::$CD_SITUACAO_PA_EM_COBRANCA,
		);
		
		return static::getColecaoComDescricao($colecao);
	}
	
}
?>