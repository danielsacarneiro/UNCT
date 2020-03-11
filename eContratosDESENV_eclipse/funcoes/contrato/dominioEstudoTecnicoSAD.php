<?php
include_once(caminho_util."dominio.class.php");

Class dominioEstudoTecnicoSAD extends dominio{
	
	static $CD_LIMPEZA_PREDIAL = 1;
	static $CD_LIMPEZA_HOSPITALAR = 2;
	static $CD_LIMPEZA_ESCOLAR = 3;
	static $CD_PORTARIA = 4;
	static $CD_VIGILANCIA = 5;
	static $CD_APOIO_ADM = 6;	
	
	static $CD_NAO_SEAPLICA = 99;

	static $DS_LIMPEZA_PREDIAL = "Limpeza predial";
	static $DS_LIMPEZA_HOSPITALAR = "Limpeza hospitalar";	
	static $DS_LIMPEZA_ESCOLAR = "Limpeza escolar";
	static $DS_PORTARIA = "Portaria";
	static $DS_VIGILANCIA = "Vigilância";
	static $DS_APOIO_ADM = "Apoio administrativo";	
	static $DS_NAO_SEAPLICA = "Não se aplica";
	
	static $DS_OBJ_PADRONIZADO = "OBJ-PADRON.";
	
	// ...............................................................
	// Construtor
	// ...............................................................
	// FunÃ§Ãµes ( Propriedades e mÃ©todos da classe )

	static function getColecao(){
		return array(				
				self::$CD_NAO_SEAPLICA  => self::$DS_NAO_SEAPLICA,
				self::$CD_LIMPEZA_PREDIAL => self::$DS_LIMPEZA_PREDIAL,
				self::$CD_LIMPEZA_HOSPITALAR => self::$DS_LIMPEZA_HOSPITALAR,
				self::$CD_LIMPEZA_ESCOLAR => self::$DS_LIMPEZA_ESCOLAR,
				self::$CD_PORTARIA => self::$DS_PORTARIA,
				self::$CD_VIGILANCIA => self::$DS_VIGILANCIA,
				self::$CD_APOIO_ADM => self::$DS_APOIO_ADM,
		);
	}
	
	/**
	 * retorna colecao formatada com indicacao de ser objeto padronizado
	 * @return string[]|unknown[]
	 */
	static function getColecaoFormatada(){
		$retorno = array();
		$chaves = array_keys(static::getColecao());
		for($i=0; $i < sizeof(static::getColecao()); $i++){
			
			$chave = $chaves[$i];			
			if(static::isObjetoPadronizado($chave)){
				$retorno[$chave] = static::$DS_OBJ_PADRONIZADO . "-" . static::getDescricao($chave);
			}else{
				$retorno[$chave] = static::getDescricao($chave);
			}
			
		}
		
		return $retorno;
	}
	
	static function getColecaoChavesObjetoPadronizado(){
		return array(
				self::$CD_LIMPEZA_PREDIAL => self::$DS_LIMPEZA_PREDIAL,
		);
	}
	
	static function isObjetoPadronizado($chave){
		return array_key_exists($chave, static::getColecaoChavesObjetoPadronizado());
	}
	
	
	
}
?>