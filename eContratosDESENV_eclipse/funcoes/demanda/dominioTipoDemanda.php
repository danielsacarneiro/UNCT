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
	
	static $CD_TIPO_DEMANDA_CONTROLE_AQUISICAO= 11;
	static $CD_TIPO_DEMANDA_LICON = 12;
	static $CD_TIPO_DEMANDA_PORTALTRANSPARENCIA = 13;
	static $CD_TIPO_DEMANDA_FERIAS = 14;
	static $CD_TIPO_DEMANDA_TAC = 15;
	//static $CD_TIPO_DEMANDA_LICON = 12;
	
	static $CD_TIPO_DEMANDA_A_CLASSIFICAR = 99;
	
	static $DS_TIPO_DEMANDA_CONTRATO = "Contrato/Convênio";
	static $DS_TIPO_DEMANDA_PROCADM = "Proc.Admin(PAAP)";
	static $DS_TIPO_DEMANDA_EDITAL = "Edital";
	static $DS_TIPO_DEMANDA_JUDICIAL = "Judicial/PGE";
	static $DS_TIPO_DEMANDA_CONTRATO_REAJUSTE = "Contrato Reajuste";
	static $DS_TIPO_DEMANDA_CONTRATO_MATER = "Contrato Mater";
	static $DS_TIPO_DEMANDA_CONTRATO_MODIFICACAO = "Contrato Modificacao";
	static $DS_TIPO_DEMANDA_CONTRATO_PRORROGACAO = "Contrato Prorrogação";
	static $DS_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO= "Contrato Apostilamento";
	static $DS_TIPO_DEMANDA_PARECER = "Parecer";
	static $DS_TIPO_DEMANDA_CONTROLE_AQUISICAO= "Controle de ATA";	
	static $DS_TIPO_DEMANDA_A_CLASSIFICAR = "A classificar";
	
	static $DS_TIPO_DEMANDA_LICON = "LICON";
	static $DS_TIPO_DEMANDA_PORTALTRANSPARENCIA = "Portal Transparência";
	static $DS_TIPO_DEMANDA_FERIAS = "Gestao de Pessoas";
	static $DS_TIPO_DEMANDA_TAC = "TAC";
	
	// ...............................................................
	// Construtor
	function __construct0 () {
		$this->colecao = self::getColecao();
	}
	
	function __construct1 ($colecao) {
		$this->colecao = $colecao;
	}
	
	static function getColecao($comDetalhamentoContrato = true){
		$retorno = static::getColecaoTipoDemandaContratoValidacaoEncaminhar($comDetalhamentoContrato);
		return $retorno;		
	}

	static function getColecaoTipoDemanda(){
		/*$retorno = 	array(
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
		);*/	
		
		$retorno = static::getColecaoTipoDemandaContrato(false);
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaComplementar());
	
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContrato($comDetalhamentoContrato = true){
		$retorno = static::getColecaoTipoDemandaContratoSemProcAdmin($comDetalhamentoContrato);
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, array(self::$CD_TIPO_DEMANDA_PROCADM => self::$DS_TIPO_DEMANDA_PROCADM));

		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoValidacaoEncaminhar($comDetalhamentoContrato = true){
		$retorno = static::getColecaoTipoDemandaContrato($comDetalhamentoContrato);
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaComplementar());
	
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoSemProcAdmin($comDetalhamentoContrato = true){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
		);
		if($comDetalhamentoContrato){
			$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaContratoValido($comDetalhamentoContrato));
		}
		
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoValido(){
		return array(
				self::$CD_TIPO_DEMANDA_CONTRATO_MATER => self::$DS_TIPO_DEMANDA_CONTRATO_MATER,
				self::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE => self::$DS_TIPO_DEMANDA_CONTRATO_REAJUSTE,
				self::$CD_TIPO_DEMANDA_CONTRATO_PRORROGACAO => self::$DS_TIPO_DEMANDA_CONTRATO_PRORROGACAO,
				self::$CD_TIPO_DEMANDA_CONTRATO_MODIFICACAO => self::$DS_TIPO_DEMANDA_CONTRATO_MODIFICACAO,
				self::$CD_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO=> self::$DS_TIPO_DEMANDA_CONTRATO_APOSTILAMENTO,
				self::$CD_TIPO_DEMANDA_A_CLASSIFICAR => "Outros",				
		);
	}
	
	static function getColecaoTipoDemandaComplementar(){
		return array(
				self::$CD_TIPO_DEMANDA_CONTROLE_AQUISICAO => self::$DS_TIPO_DEMANDA_CONTROLE_AQUISICAO,
				self::$CD_TIPO_DEMANDA_EDITAL => self::$DS_TIPO_DEMANDA_EDITAL,
				self::$CD_TIPO_DEMANDA_PARECER => self::$DS_TIPO_DEMANDA_PARECER,
				self::$CD_TIPO_DEMANDA_JUDICIAL => self::$DS_TIPO_DEMANDA_JUDICIAL,
				self::$CD_TIPO_DEMANDA_TAC => self::$DS_TIPO_DEMANDA_TAC,
				
				self::$CD_TIPO_DEMANDA_LICON => self::$DS_TIPO_DEMANDA_LICON,
				self::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA => self::$DS_TIPO_DEMANDA_PORTALTRANSPARENCIA,
				
				self::$CD_TIPO_DEMANDA_FERIAS => self::$DS_TIPO_DEMANDA_FERIAS,
				self::$CD_TIPO_DEMANDA_A_CLASSIFICAR => self::$DS_TIPO_DEMANDA_A_CLASSIFICAR,				
		);
	}
	
	static function getColecaoTipoDemandaSAD(){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_A_CLASSIFICAR => self::$DS_TIPO_DEMANDA_A_CLASSIFICAR,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoTipoDemandaContratoSemProcAdmin());
		
		return $retorno;		
	}
	
	static function isContratoObrigatorio($cdTipoDemanda){
		$retorno = false;
		
		if($cdTipoDemanda != null && in_array($cdTipoDemanda, self::getColecaoTipoDemandaContrato())){
			$retorno = true;
		}
		
		return $retorno;
	}
	
	static function isTipoDemandaContratoReajuste($cdTipoDemanda){	
		return static::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE == $cdTipoDemanda;
	}
	
	static function getColecaoTipoDemandaContratoReajuste(){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE => self::$DS_TIPO_DEMANDA_CONTRATO_REAJUSTE,
		);		
		return $retorno;
	}
	
	static function getColecaoTipoDemandaContratoGenero(){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_CONTRATO => self::$DS_TIPO_DEMANDA_CONTRATO,
		);
		return $retorno;
	}
	
	static function getColecaoTipoDemandaSistemasExternos(){
		$retorno = array(
				self::$CD_TIPO_DEMANDA_LICON => self::$DS_TIPO_DEMANDA_LICON,
				self::$CD_TIPO_DEMANDA_PORTALTRANSPARENCIA => self::$DS_TIPO_DEMANDA_PORTALTRANSPARENCIA,
		);
		return $retorno;
	}
	
	/*static function getSQLisTipoDemandaContratoReajuste($tipo){
		$retorno = "FALSE";
		if($tipo != null && static::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE)
	
		return $retorno;
	}*/
	
}
?>