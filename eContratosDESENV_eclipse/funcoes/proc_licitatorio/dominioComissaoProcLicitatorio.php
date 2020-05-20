<?php
include_once (caminho_util . "dominio.class.php");
class dominioComissaoProcLicitatorio extends dominio {
	static $CD_CPL_I = 1;
	static $CD_CPL_II = 2;
	static $CD_CPL_III = 3;
	static $CD_CPL_CEL = 4;
	static $DS_CPL_I = "CPL-I";
	static $DS_CPL_II = "CPL-II";
	static $DS_CPL_III = "CPL-III";
	static $DS_CPL_CEL = "CL-PROFISC";
	
	static $NM_PREGOEIRO_CPL_I = "ODACY WELLINGTON DA SILVA";
	static $NM_PREGOEIRO_CPL_II = "MARIA GORETE BRANDT DE CARVALHO";
	static $NM_PREGOEIRO_CPL_III = "PATRICIA DE LUCENA FARIAS";
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao ();
	}
	static function getColecao() {
		$retorno = array (
				self::$CD_CPL_I => self::$DS_CPL_I,
				self::$CD_CPL_II => self::$DS_CPL_II,
				self::$CD_CPL_III => self::$DS_CPL_III,
				self::$CD_CPL_CEL => self::$DS_CPL_CEL
		);
		
		return $retorno;
	}
	static function getColecaoConsulta() {
		return static::getColecao();
	}
	/*static function getCPLPorPregoeiro($nmPregoeiro, $retornarDescricao = false) {
		//echo "nome pregoeiro eh $nmPregoeiro";
		$retorno = null;
		if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "gorete" )) {
			$retorno = static::$CD_CPL_II;
		} else if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "odacy" )) {
			$retorno = static::$CD_CPL_I;
		} else if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "patricia" . CAMPO_SEPARADOR . "patr�cia" )) {
			$retorno = array(static::$CD_CPL_III, static::$CD_CPL_CEL);
		}
		
		if ($retornarDescricao) {
			if(!is_array($retorno)){
				$retorno = static::getDescricaoStatic ( $retorno );
			}else{
				for ($i=0; $i<sizeof($retorno);$i++){
					$retorno[$i] = static::getDescricaoStatic ( $retorno[$i] );					
				}
			}
		}
		return $retorno;
	}*/
	static function getNmPregoeiroPorCPL($proclic) {
		$retorno = null;
		
		//echoo(static::$DS_CPL_I);
		$proclic = str_replace(" ", "", $proclic);

		if ($proclic == static::$CD_CPL_I) {
			$retorno = static::$NM_PREGOEIRO_CPL_I;
		}else if ($proclic == static::$CD_CPL_II) {
			$retorno = static::$NM_PREGOEIRO_CPL_II;
		} else if ($proclic == static::$CD_CPL_III) {
			$retorno = static::$NM_PREGOEIRO_CPL_III;
		} else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_I)) {
			$retorno = static::$NM_PREGOEIRO_CPL_I;
		}else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_II )) {
			$retorno = static::$NM_PREGOEIRO_CPL_II;
		} else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_III ) 
				|| existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_CEL)) {
			$retorno = static::$NM_PREGOEIRO_CPL_III;
		}
	
		return $retorno;
	}
	static function getNumPortariaCPL($cdCPL) {
		$retorno = "Portaria SAD n� ";
		if (static::$CD_CPL_I == $cdCPL) {
			$retorno .= "995, do dia 26/04/2018, publicada no DOE edi��o de 27/04/2018";
		} else if (static::$CD_CPL_II == $cdCPL) {
			$retorno .= "996, do dia 26/04/2018, publicada no DOE edi��o de 27/04/2018";
		} else if (static::$CD_CPL_III == $cdCPL) {
			$retorno .= "997, do dia 26/04/2018, publicada no DOE edi��o de 27/04/2018";
		} else if (static::$CD_CPL_CEL == $cdCPL) {
				$retorno .= "2476, do dia 10/10/2019, publicada no DOE edi��o de 11/10/2019";
		}				
		else
			$retorno = null;

		return $retorno;
	}
	
	static function getNumPortariaTodasCPL($anoPortaria=null) {
		
		//ja que nao tem portarias
		if($anoPortaria != null && $anoPortaria < 2017){
			$anoPortaria = null;
		}
		
		$numPortarias = 0;
		$numPortariaMaximaAExibir = 2;
						
		$ano = 2019;
		$pegarPortaria = $numPortarias < $numPortariaMaximaAExibir || $anoPortaria == null;
		if($pegarPortaria || $anoPortaria == $ano){
			$retorno .= "Ano $ano:<br>";
			$retorno .= "<b>842/$ano</b>(CPL-I), publicada no DOE de 07.05.2019.<br>";
			$retorno .= "<b>843/$ano</b>(CPL-II), publicada no DOE de 07.05.2019<br>";
			$retorno .= "<b>844/$ano</b>(CPL-III), publicada no DOE de 07.05.2019<br>";
			$retorno .= "<b>2476/$ano</b>(".static::$CD_CPL_CEL."), publicada no DOE de 11.10.2019<br>";
			$numPortarias++;
		}
		
		$ano = 2018;
		$pegarPortaria = $numPortarias < $numPortariaMaximaAExibir || $anoPortaria == null;
		if($pegarPortaria || $anoPortaria == $ano){
			$retorno .= "<br>Ano $ano:<br>";
			$retorno .= "<b>995/$ano</b>(CPL-I)<br>";
			$retorno .= "<b>996/$ano</b>(CPL-II)<br>";
			$retorno .= "<b>997/$ano</b>(CPL-III)<br>";
			$numPortarias++;
		}
		
		$ano = 2017;
		$pegarPortaria = $numPortarias < $numPortariaMaximaAExibir || $anoPortaria == null;		
		if($pegarPortaria || $anoPortaria == $ano){
			$retorno .= "<br>Ano $ano:<br>";
			$retorno .= "<b>1173/$ano</b>(CPL-I)<br>";
			$retorno .= "<b>1248/$ano</b>(CPL-II)<br>";
			$retorno .= "<b>1249/$ano</b>(CPL-III)<br>";
			$numPortarias++;
		}
		
		return $retorno;
	}
	
}
?>