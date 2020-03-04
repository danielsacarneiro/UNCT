<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTipoContrato extends dominio{
  	
  	static $CD_TIPO_CONTRATO  = "C";
  	static $CD_TIPO_CONVENIO  = "V";
  	static $CD_TIPO_PROFISCO  = "P";
  	static $CD_TIPO_CESSAO_USO = "S";
  	//static $CD_TIPO_TERMOAJUSTE = "A";
  	static $CD_TIPO_TERMO_COLABORACAO = "T";  	 
  	
  	static $DS_TIPO_CONTRATO  = "C-SAFI";
  	static $DS_TIPO_CONVENIO  = "CV-SAFI";
  	static $DS_TIPO_PROFISCO  = "C-PROFISCO";
  	static $DS_TIPO_CESSAO_USO = "CS-SAFI";
  	//static $DS_TIPO_TERMOAJUSTE = "TAC";
  	static $DS_TIPO_TERMO_COLABORACAO = "Termo de colaboraзгo";
  	
  	static $DS_TIPO_INSTRUMENTO_CONTRATO = "Contrato";
  	static $DS_TIPO_INSTRUMENTO_CONVENIO = "Convкnio";
  	 
//ATENCAO, A UNCT NUMERA TERMO DE COLABORACAO COMO CONVENIO
// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = self::getColecao();
	}	
// ...............................................................
// FunГ§Гµes ( Propriedades e mГ©todos da classe )

	static function getColecao(){
		return array(
				static::$CD_TIPO_CONTRATO => static::$DS_TIPO_CONTRATO,
				static::$CD_TIPO_CONVENIO => static::$DS_TIPO_CONVENIO,
				static::$CD_TIPO_CESSAO_USO => static::$DS_TIPO_CESSAO_USO,
				static::$CD_TIPO_PROFISCO => static::$DS_TIPO_PROFISCO,
				//static::$CD_TIPO_TERMOAJUSTE => static::$DS_TIPO_TERMOAJUSTE,
		);
	}
	
	static function getColecaoInstrumentos(){
		return array(
				static::$CD_TIPO_CONTRATO => static::$DS_TIPO_INSTRUMENTO_CONTRATO,
				static::$CD_TIPO_PROFISCO => static::$DS_TIPO_INSTRUMENTO_CONTRATO,
				static::$CD_TIPO_CESSAO_USO => static::$DS_TIPO_INSTRUMENTO_CONTRATO,
				static::$CD_TIPO_CONVENIO => static::$DS_TIPO_INSTRUMENTO_CONVENIO,
		);
	}
	
	/**
	 * essa funcao eh importante para importar os contratos cuja especie foi utilizada indevidamente para caracterizar
	 * o tipo do contrato. O combinado com a UNCT й que a partir de 2020 essa situacao se normaliza, nao devendo haver novos casos
	 * @return string[]
	 */
	static function getColecaoImportacaoPlanilhaExcecao(){
		return array(
				//self::$CD_TIPO_TERMOAJUSTE => "Ajuste*conta",
				self::$CD_TIPO_CESSAO_USO => "Cessгo*cessao",
		);
	}	
	
}
?>