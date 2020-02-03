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
	static $DS_VIGILANCIA = "Vigil�ncia";
	static $DS_APOIO_ADM = "Apoio administrativo";	
	static $DS_NAO_SEAPLICA = "N�o se aplica";
	
	// ...............................................................
	// Construtor
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )

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
	
}
?>