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
        				self::$CD_ESPECIE_CONTRATO_MATER => "Mater",
        				self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => "Apostilamento",
        				self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "Termo Aditivo",
        				self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Termo de Ajuste",
        				self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Termo de Cess�o de Uso",
        				self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Termo de Rerratifica��o",
        				self::$CD_ESPECIE_CONTRATO_COOPERACAO => "Termo de Coopera��o",
        				self::$CD_ESPECIE_CONTRATO_CONVALIDACAO => "Termo de Convalida��o",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Termo de Rescis�o Amig�vel",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Termo de Rescis�o Unilateral",
        				self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Termo de Rescis�o Encerramento",
				);
	}	
    
	function getDominioImportacaoPlanilha() {            
            return self::getColecaoImportacaoPlanilha();
	}

	static function getColecaoImportacaoPlanilha(){
		//cooperacao e convalidacao seram considerados como MATER
		//deixa na ultima posicao as especies que podem se repetir		
		return array(
        		self::$CD_ESPECIE_CONTRATO_RERRATIFICACAO => "Rerratifica��o*Rerratificacao",
        		self::$CD_ESPECIE_CONTRATO_TERMOADITIVO => "T.A",
        		self::$CD_ESPECIE_CONTRATO_TERMOAJUSTE => "Ajuste",
        		self::$CD_ESPECIE_CONTRATO_CESSAO_USO => "Cess�o",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_AMIGAVEL => "Amig�vel*Amigavel",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_UNILATERAL => "Unilateral",
        		self::$CD_ESPECIE_CONTRATO_RESCISAO_ENCERRAMENTO => "Encerramento",      
        		self::$CD_ESPECIE_CONTRATO_MATER => "Mater*Conv�nio*Coopera��o*Convalida��o",
        		self::$CD_ESPECIE_CONTRATO_APOSTILAMENTO => "Apostilamento*Apostuilamento"
				);
	}
	
}

?>