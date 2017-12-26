<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTipoDemanda extends dominio{	
	static $CD_TIPO_DEMANDA_CONTRATO = 1;	
	static $CD_TIPO_DEMANDA_PROCADM = 2;
	static $CD_TIPO_DEMANDA_EDITAL = 3;
	static $CD_TIPO_DEMANDA_JUDICIAL = 4;
	static $CD_TIPO_DEMANDA_CONTRATO_REAJUSTE = 5;
	static $CD_TIPO_DEMANDA_CONTRATO_MATER = 6;
	static $CD_TIPO_DEMANDA_CONTRATO_MODIFICACAO = 7;
	static $CD_TIPO_DEMANDA_CONTRATO_PRORROGACAO = 8;
	static $CD_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO= 10;
	static $CD_TIPO_DEMANDA_PARECER = 9;
	static $CD_TIPO_DEMANDA_A_CLASSIFICAR = 99;
	
	static $DS_TIPO_DEMANDA_CONTRATO = "Contrato";
	static $DS_TIPO_DEMANDA_PROCADM = "Proc.Admin";
	static $DS_TIPO_DEMANDA_EDITAL = "Edital";
	static $DS_TIPO_DEMANDA_JUDICIAL = "Judicial/PGE";
	static $DS_TIPO_DEMANDA_CONTRATO_REAJUSTE = "Contrato Reajuste";
	static $DS_TIPO_DEMANDA_CONTRATO_MATER = "Contrato Mater";
	static $DS_TIPO_DEMANDA_CONTRATO_MODIFICACAO = "Contrato Modificacao";
	static $DS_TIPO_DEMANDA_CONTRATO_PRORROGACAO = "Contrato Prorrogaηγo";
	static $DS_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO= "Apostilamento";
	static $DS_TIPO_DEMANDA_PARECER = "Parecer";
	
	static $DS_TIPO_DEMANDA_A_CLASSIFICAR = "A classificar";
	// ...............................................................
	// Construtor
	function __construct0 () {
		$this->colecao = self::getColecao();
	}
	
	function __construct1 ($colecao) {
		$this->colecao = $colecao;
	}
	
	static function getColecao(){
		$retorno = static::getColecaoTipoDemandaContratoValidacaoEncaminhar();
		return $retorno;		
	}

	static function getColecaoTipoDemanda(){
		$retorno = 	array(
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
		);	
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaComplementar());
	
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContrato(){
		$retorno = static::getColecaoTipoDemandaContratoSemProcAdmin();
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, array(self::$CD_TIPO_DEMANDA_PROCADM => self::$DS_TIPO_DEMANDA_PROCADM));

		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoValidacaoEncaminhar(){
		$retorno = static::getColecaoTipoDemandaContrato();
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaComplementar());
	
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoSemProcAdmin(){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaContratoValido());
		
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoValido(){
		return array(
				self::$CD_TIPO_DEMANDA_CONTRATO_MATER => self::$DS_TIPO_DEMANDA_CONTRATO_MATER,
				self::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE => self::$DS_TIPO_DEMANDA_CONTRATO_REAJUSTE,
				self::$CD_TIPO_DEMANDA_CONTRATO_PRORROGACAO => self::$DS_TIPO_DEMANDA_CONTRATO_PRORROGACAO,
				self::$CD_TIPO_DEMANDA_CONTRATO_MODIFICACAO => self::$DS_TIPO_DEMANDA_CONTRATO_MODIFICACAO,
				self::$CD_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO=> self::$DS_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO,
		);
	}
	
	static function getColecaoTipoDemandaComplementar(){
		return array(
				self::$CD_TIPO_DEMANDA_EDITAL => self::$DS_TIPO_DEMANDA_EDITAL,
				self::$CD_TIPO_DEMANDA_PARECER => self::$DS_TIPO_DEMANDA_PARECER,
				self::$CD_TIPO_DEMANDA_JUDICIAL => self::$DS_TIPO_DEMANDA_JUDICIAL,
				self::$CD_TIPO_DEMANDA_A_CLASSIFICAR => self::$DS_TIPO_DEMANDA_A_CLASSIFICAR,				
		);
	}
	
	static function getColecaoTipoDemandaSAD(){
		return static::getColecaoTipoDemandaContratoSemProcAdmin();
	}
	
	static function isContratoObrigatorio($cdTipoDemanda){
		$retorno = false;
		
		if($cdTipoDemanda != null && in_array($cdTipoDemanda, self::getColecaoTipoDemandaContrato())){
			$retorno = true;
		}
		
		return $retorno;
	}
}
?>