<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTipoDemanda extends dominio{	
	static $CD_TIPO_DEMANDA_CONTRATO = 1;
	static $CD_TIPO_DEMANDA_PROCADM = 2;
	static $CD_TIPO_DEMANDA_A_CLASSIFICAR = 3;
	
	static $DS_TIPO_DEMANDA_CONTRATO = "Contrato";
	static $DS_TIPO_DEMANDA_PROCADM = "Proc.Admin";
	static $DS_TIPO_DEMANDA_A_CLASSIFICAR = "A classificar";
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
	}

	static function getColecao(){
		return array(				
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
				self::$CD_TIPO_DEMANDA_PROCADM => self::$DS_TIPO_DEMANDA_PROCADM,
				self::$CD_TIPO_DEMANDA_A_CLASSIFICAR => self::$DS_TIPO_DEMANDA_A_CLASSIFICAR
		);
	}

}
?>