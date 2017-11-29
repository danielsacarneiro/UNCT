<?php
include_once (caminho_util . "dominio.class.php");
/*
 *
 * Lei 8666
 * Art. 87. Pela inexecuчуo total ou parcial do contrato a Administraчуo poderс, garantida a prщvia defesa, aplicar ao
 * contratado as seguintes sanчѕes:
 * I - advertъncia;
 * II - multa, na forma prevista no instrumento convocatѓrio ou no contrato;
 * III - suspensуo temporсria de participaчуo em licitaчуo e impedimento de contratar com a Administraчуo,
 * por prazo nуo superior a 2 (dois) anos;
 * IV - declaraчуo de inidoneidade para licitar ou contratar com a Administraчуo Pњblica enquanto perdurarem
 * os motivos determinantes da puniчуo ou atщ que seja promovida a reabilitaчуo perante a prѓpria autoridade
 * que aplicou a penalidade, que serс concedida sempre que o contratado ressarcir
 * a Administraчуo pelos prejuэzos resultantes e apѓs decorrido o prazo da sanчуo aplicada com base no inciso anterior.
 */
class dominioTipoPenalidade extends dominio {
	static $CD_TP_PENALIDADE_ADVERTENCIA = 1;
	static $CD_TP_PENALIDADE_MULTA = 2;
	static $CD_TP_PENALIDADE_SUSPENSAO = 3;
	static $CD_TP_PENALIDADE_DECLARACAO_INIDONEIDADE = 4;
	static $CD_TP_PENALIDADE_IMPEDIM_LICITAR = 5;
	
	static $DS_TP_PENALIDADE_ADVERTENCIA = "Advertъncia";
	static $DS_TP_PENALIDADE_MULTA = "Multa";
	static $DS_TP_PENALIDADE_SUSPENSAO = "Suspensуo Temporсria";
	static $DS_TP_PENALIDADE_DECLARACAO_INIDONEIDADE = "Declaraчуo de inidoneidade";
	static $DS_TP_PENALIDADE_IMPEDIM_LICITAR = "Impedimento de licitar";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao ();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_TP_PENALIDADE_ADVERTENCIA => self::$DS_TP_PENALIDADE_ADVERTENCIA,
				self::$CD_TP_PENALIDADE_MULTA => self::$DS_TP_PENALIDADE_MULTA,
				self::$CD_TP_PENALIDADE_SUSPENSAO => self::$DS_TP_PENALIDADE_SUSPENSAO,
				self::$CD_TP_PENALIDADE_DECLARACAO_INIDONEIDADE => self::$DS_TP_PENALIDADE_DECLARACAO_INIDONEIDADE,
				self::$CD_TP_PENALIDADE_IMPEDIM_LICITAR => self::$DS_TP_PENALIDADE_IMPEDIM_LICITAR
		);
		
		return $retorno;
	}
		
	static function getColecaoComReferenciaLegal() {		
		$array = self::getColecao();
		$retorno = array();
		foreach (array_keys($array) as $chavePenalidade){
			$texto = static::getDescricaoStatic($chavePenalidade);
			$retorno[$chavePenalidade] = $texto;
		}
		return $retorno;
	}
	
	static function getTextoReferenciaLegal($cd){
		$artigo = "art. 87";
		$lei = "Lei 8.666/93";
		
		if($cd == static::$CD_TP_PENALIDADE_IMPEDIM_LICITAR){
			$artigo = "art. 7";
			$lei = "Lei 10.520/02";
		}
		
		$extenso = " ($artigo, ".constantes::$CD_CAMPO_SUBSTITUICAO." $lei)";
		
		$subs = static::getIncisoReferenciaLegal($cd);		
		$retorno = str_replace(constantes::$CD_CAMPO_SUBSTITUICAO, $subs, $extenso);
		
		return $retorno;
	}
	
	static function getIncisoReferenciaLegal($cd){
		$array = array(
				static::$CD_TP_PENALIDADE_ADVERTENCIA => "I",
				static::$CD_TP_PENALIDADE_MULTA => "II",
				static::$CD_TP_PENALIDADE_SUSPENSAO => "III",
				static::$CD_TP_PENALIDADE_DECLARACAO_INIDONEIDADE => "IV",
		);		
		
		if(array_key_exists($cd, $array)){
			$retorno = $array[$cd] . ",";
		}
		return $retorno;
	}
	
	static function getDescricaoStatic($chave) {
		$retorno = parent::getDescricaoStatic($chave);
		$retorno = $retorno.static::getTextoReferenciaLegal($chave);
		
		return $retorno;
	}
	
}
?>