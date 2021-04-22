<?php
include_once(caminho_util. "dominio.class.php");

  Class dominioEspeciesContrato extends dominio{
  	
  	static $CD_ESPECIE_CONTRATO_MATER = "CM";
  	static $CD_ESPECIE_CONTRATO_TERMOADITIVO = "TA";
  	static $CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO = "OP";
  	static $CD_ESPECIE_CONTRATO_APOSTILAMENTO = "AP";
  	static $CD_ESPECIE_CONTRATO_TERMOAJUSTE = "AJ";
  	//static $CD_ESPECIE_CONTRATO_CESSAO_USO = "CS";
  	
  	static $CD_ESPECIE_CONTRATO_RERRATIFICACAO = "RR";
  	static $CD_ESPECIE_CONTRATO_COOPERACAO = "CO";
  	static $CD_ESPECIE_CONTRATO_CONVALIDACAO = "CV";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL = "RA";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL = "RU";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO = "RE";

  	static $DS_ESPECIE_CONTRATO_MATER = "Mater";
  	static $DS_ESPECIE_CONTRATO_TERMOADITIVO = "Termo Aditivo";
  	static $DS_ESPECIE_CONTRATO_ORDEM_PARALISACAO = "Ordem Paralisa��o";
  	static $DS_ESPECIE_CONTRATO_APOSTILAMENTO = "Apostilamento";
  	
  	static $DS_ESPECIE_CONTRATO_TERMOAJUSTE = "Termo Ajuste Contas";
  	static $DS_ESPECIE_CONTRATO_RERRATIFICACAO = "Termo Rerratifica��o";
  	static $DS_ESPECIE_CONTRATO_COOPERACAO = "Termo Coopera��o";
  	static $DS_ESPECIE_CONTRATO_CONVALIDACAO = "Termo Convalida��o";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL = "Termo Rescis�o Amig�vel";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_UNILATERAL = "Termo Rescis�o Unilateral";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO = "Termo Rescis�o Encerramento";  	 
  	 
  	 /*
  	 * ANTES ERA ASSIM:
  	"01" => "Mater",
  	"02" => "Apostilamento",
  	"03" => "Termo Aditivo",
  	"04" => "Termo de Ajuste",
  	"05" => "Termo de Cess�o de Uso",
  	"06" => "Termo de Rerratifica��o",
  	"07" => "Termo de Coopera��o",
  	"08" => "Termo de Convalida��o",
  	"09" => "Termo de Rescis�o Amig�vel",
  	"10" => "Termo de Rescis�o Unilateral",
  	"11" => "Termo de Rescis�o Encerramento",*/
    
// ...............................................................
// Construtor
	function __construct () {
        		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
        				self::$CD_ESPECIE_CONTRATO_MATER => self::$DS_ESPECIE_CONTRATO_MATER,
        				self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => self::$DS_ESPECIE_CONTRATO_APOSTILAMENTO,
        				self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => self::$DS_ESPECIE_CONTRATO_TERMOADITIVO,
						self::$CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO => self::$DS_ESPECIE_CONTRATO_ORDEM_PARALISACAO,
        				self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => self::$DS_ESPECIE_CONTRATO_TERMOAJUSTE,
        				//self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Termo de Cess�o de Uso",
        				self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => self::$DS_ESPECIE_CONTRATO_RERRATIFICACAO,
        				self::$CD_ESPECIE_CONTRATO_COOPERACAO => self::$DS_ESPECIE_CONTRATO_COOPERACAO,
        				self::$CD_ESPECIE_CONTRATO_CONVALIDACAO => self::$DS_ESPECIE_CONTRATO_CONVALIDACAO,
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => self::$DS_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL,
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => self::$DS_ESPECIE_CONTRATO_RESCISAO_UNILATERAL,
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => self::$DS_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO,
				);
	}	
    
	static function getColecaoLicon(){
		return array(
				self::$CD_ESPECIE_CONTRATO_MATER => self::$DS_ESPECIE_CONTRATO_MATER,
				self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => self::$DS_ESPECIE_CONTRATO_TERMOADITIVO,
		);
	}
	
	/**
	 * traz a colecao dos termos permitidos na consulta de contrato consolidacao
	 * @return string[]
	 */
	static function getColecaoFiltroContratoConsolidacao(){
		/*return array(
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RERRATIFICACAO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL,
		
		);*/
		
		return static::getColecaoTermosQuePodemAlterarVigencia();
	}	
	
	/**
	 * colecao de termos que exigem publicacao
	 * @return string[]
	 */
	static function getColecaoTermosPublicacao(){
		return static::getColecaoTermosQuePodemAlterarVigencia();
	}
	
	static function getColecaoImportacaoPlanilha(){
		//cooperacao e convalidacao serao considerados como MATER
		//deixa na ultima posicao as especies que podem se repetir
		// A ORDEM EH IMPORTANTE, pq o item seguinte so sera selecionado se o anterior nao contiver nenhuma palavra em comum
		return array(
        		self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Rerratifica��o*Rerratificacao",
				self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Ajuste*conta",
        		self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "T.A*TA*T.A.*aditivo",        		
        		//self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Cess�o*cessao",
				self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Unilateral",
				self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Encerramento",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Rescis�o*Amig�vel*Amigavel",      
        		//self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Conv�nio*Coopera��o*Convalida��o",
				self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Conv�nio*Coopera��o*Convalida��o*Cess�o*cessao",
        		self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => "Apostilamento*Apostuilamento*Apostilamneto"
				);
	}
	
	static function getColecaoTermosQuePodemAlterarVigencia(){
		return array(
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_TERMOADITIVO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_ORDEM_PARALISACAO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RERRATIFICACAO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL,
	
		);
	}
	
	static function getColecaoTermosNaoNumeradosPublicacao(){
		return array(
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL,
				dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO,
		);
	}
	
}

?>