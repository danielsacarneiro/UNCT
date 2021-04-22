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
  	static $DS_ESPECIE_CONTRATO_ORDEM_PARALISACAO = "Ordem Paralisaчуo";
  	static $DS_ESPECIE_CONTRATO_APOSTILAMENTO = "Apostilamento";
  	
  	static $DS_ESPECIE_CONTRATO_TERMOAJUSTE = "Termo Ajuste Contas";
  	static $DS_ESPECIE_CONTRATO_RERRATIFICACAO = "Termo Rerratificaчуo";
  	static $DS_ESPECIE_CONTRATO_COOPERACAO = "Termo Cooperaчуo";
  	static $DS_ESPECIE_CONTRATO_CONVALIDACAO = "Termo Convalidaчуo";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL = "Termo Rescisуo Amigсvel";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_UNILATERAL = "Termo Rescisуo Unilateral";
  	static $DS_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO = "Termo Rescisуo Encerramento";  	 
  	 
  	 /*
  	 * ANTES ERA ASSIM:
  	"01" => "Mater",
  	"02" => "Apostilamento",
  	"03" => "Termo Aditivo",
  	"04" => "Termo de Ajuste",
  	"05" => "Termo de Cessуo de Uso",
  	"06" => "Termo de Rerratificaчуo",
  	"07" => "Termo de Cooperaчуo",
  	"08" => "Termo de Convalidaчуo",
  	"09" => "Termo de Rescisуo Amigсvel",
  	"10" => "Termo de Rescisуo Unilateral",
  	"11" => "Termo de Rescisуo Encerramento",*/
    
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
        				//self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Termo de Cessуo de Uso",
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
        		self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Rerratificaчуo*Rerratificacao",
				self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Ajuste*conta",
        		self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "T.A*TA*T.A.*aditivo",        		
        		//self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Cessуo*cessao",
				self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Unilateral",
				self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Encerramento",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Rescisуo*Amigсvel*Amigavel",      
        		//self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Convъnio*Cooperaчуo*Convalidaчуo",
				self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Convъnio*Cooperaчуo*Convalidaчуo*Cessуo*cessao",
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