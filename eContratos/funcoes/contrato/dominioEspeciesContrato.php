<?php
include_once(caminho_util. "dominio.class.php");

  Class dominioEspeciesContrato extends dominio{
  	
  	static $CD_ESPECIE_CONTRATO_MATER = "CM";
  	static $CD_ESPECIE_CONTRATO_TERMOADITIVO = "TA";
  	static $CD_ESPECIE_CONTRATO_APOSTILAMENTO = "AP";
  	static $CD_ESPECIE_CONTRATO_TERMOAJUSTE = "AJ";
  	static $CD_ESPECIE_CONTRATO_CESSAO_USO = "CS";
  	
  	static $CD_ESPECIE_CONTRATO_RERRATIFICACAO = "RR";
  	static $CD_ESPECIE_CONTRATO_COOPERACAO = "CO";
  	static $CD_ESPECIE_CONTRATO_CONVALIDACAO = "CV";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL = "RA";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL = "RU";
  	static $CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO = "RE";
  	
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
        				self::$CD_ESPECIE_CONTRATO_MATER => "Mater",
        				self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => "Apostilamento",
        				self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "Termo Aditivo",
        				self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Termo de Ajuste",
        				self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Termo de Cessуo de Uso",
        				self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Termo de Rerratificaчуo",
        				self::$CD_ESPECIE_CONTRATO_COOPERACAO => "Termo de Cooperaчуo",
        				self::$CD_ESPECIE_CONTRATO_CONVALIDACAO => "Termo de Convalidaчуo",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Termo de Rescisуo Amigсvel",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Termo de Rescisуo Unilateral",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Termo de Rescisуo Encerramento",
				);
	}	
    
	function getDominioImportacaoPlanilha() {            
            return self::getColecaoImportacaoPlanilha();
	}

	static function getColecaoImportacaoPlanilha(){
		//cooperacao e convalidacao seram considerados como MATER
		//deixa na ultima posicao as especies que podem se repetir		
		return array(
        		self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Rerratificaчуo*Rerratificacao",
        		self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "T.A",
        		self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Ajuste",
        		self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Cessуo",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Amigсvel*Amigavel",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Unilateral",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Encerramento",      
        		self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Convъnio*Cooperaчуo*Convalidaчуo",
        		self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => "Apostilamento*Apostuilamento"
				);
	}
	
}

?>