<?php
include_once (caminho_util . "dominio.class.php");
class dominioComissaoProcLicitatorio extends dominio {
	static $CD_CPL_I = 1;
	static $CD_CPL_II = 2;
	static $CD_CPL_III = 3;
	static $CD_CPL_CEL = 4;
	static $CD_CPL_CEL_II = 5;
	static $DS_CPL_I = "CPL-I";
	static $DS_CPL_II = "CPL-II";
	static $DS_CPL_III = "CPL-III";
	static $DS_CPL_CEL = "CLI-PROFISC";
	static $DS_CPL_CEL_II = "CLII-PROFISC";
	
	static $NM_PREGOEIRO_CPL_I = "ODACY WELLINGTON DA SILVA";
	static $NM_PREGOEIRO_CPL_II = "MARIA GORETE BRANDT DE CARVALHO";
	static $NM_PREGOEIRO_CPL_III = "PATRICIA DE LUCENA FARIAS";
	static $NM_PREGOEIRO_CEL_II = "ANA CAROLINA FURTADO";

	static $CD_PESSOA_ODACY  = 474;
	static $CD_PESSOA_GORETE = 473;
	static $CD_PESSOA_PATRICIA = 451;
	static $CD_PESSOA_CAROL = 570;
	
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
				self::$CD_CPL_CEL => self::$DS_CPL_CEL,
				self::$CD_CPL_CEL_II => self::$DS_CPL_CEL_II,
		);
		
		return $retorno;
	}
	static function getColecaoConsulta() {
		return static::getColecao();
	}
	static function getColecaoCdPregoeiroPorCPL() {
		$retorno = array (
				self::$CD_CPL_I => self::$CD_PESSOA_ODACY,
				self::$CD_CPL_II => self::$CD_PESSOA_GORETE,
				self::$CD_CPL_III => self::$CD_PESSOA_PATRICIA,
				self::$CD_CPL_CEL => self::$CD_PESSOA_PATRICIA,
				self::$CD_CPL_CEL_II => self::$CD_PESSOA_CAROL,
		);
	
		return $retorno;
	}
	/*static function getCPLPorPregoeiro($nmPregoeiro, $retornarDescricao = false) {
		//echo "nome pregoeiro eh $nmPregoeiro";
		$retorno = null;
		if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "gorete" )) {
			$retorno = static::$CD_CPL_II;
		} else if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "odacy" )) {
			$retorno = static::$CD_CPL_I;
		} else if (existeStr1NaStr2ComSeparador ( $nmPregoeiro, "patricia" . CAMPO_SEPARADOR . "patrícia" )) {
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
		}else if ($proclic == static::$CD_CPL_CEL_II) {
				$retorno = static::$NM_PREGOEIRO_CEL_II;
		} else if ($proclic == static::$CD_CPL_III
				|| $proclic == static::$CD_CPL_CEL) {
			$retorno = static::$NM_PREGOEIRO_CPL_III;
		} else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_I)) {
			$retorno = static::$NM_PREGOEIRO_CPL_I;
		}else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_II )) {
				$retorno = static::$NM_PREGOEIRO_CPL_II;
		}else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_CEL_II)) {
			$retorno = static::$NM_PREGOEIRO_CEL_II;
		} else if (existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_III ) 
				|| existeStr1NaStr2ComSeparador ( $proclic, static::$DS_CPL_CEL)) {
			$retorno = static::$NM_PREGOEIRO_CPL_III;
		}
	
		return $retorno;
	}
	
	static function getNumPortariaTodasCPL($anoPortaria=null) {
		
		//ja que nao tem portarias
		if($anoPortaria != null && $anoPortaria < 2017){
			$anoPortaria = null;
		}
		
		$numPortarias = 0;
		$numPortariaMaximaAExibir = 2;		
		
		$ano = 2020;
		$pegarPortaria = $numPortarias < $numPortariaMaximaAExibir || $anoPortaria == null;
		if($pegarPortaria || $anoPortaria == $ano){
			$retorno .= "<br>Ano $ano:<br>";
			$retorno .= "<b>1978/$ano</b>(".static::getDescricao(static::$CD_CPL_I) ."), publicada no DOE de XX.XX.$ano.<br>";
			$retorno .= "<b>1341/$ano</b>(".static::getDescricao(static::$CD_CPL_I) ."), publicada no DOE de 30.07.$ano.<br>";
			$retorno .= "<b>1065/$ano</b>(".static::getDescricao(static::$CD_CPL_I) ."), publicada no DOE de 27.05.$ano.<br>";
			$retorno .= "<b>1066/$ano</b>(".static::getDescricao(static::$CD_CPL_II) ."), publicada no DOE de 27.05.$ano<br>";
			$retorno .= "<b>XXX/$ano</b>(".static::getDescricao(static::$CD_CPL_III) ."), publicada no DOE de 27.05.$ano<br>";
			$retorno .= "<b>1067/$ano</b>(".static::getDescricao(static::$CD_CPL_CEL) ."), publicada no DOE de 27.05.$ano<br>";
			$numPortarias++;
		}
		
		$ano = 2019;
		$pegarPortaria = $numPortarias < $numPortariaMaximaAExibir || $anoPortaria == null;
		if($pegarPortaria || $anoPortaria == $ano){
			$retorno .= "<br>Ano $ano:<br>";
			$retorno .= "<b>842/$ano</b>(CPL-I), publicada no DOE de 07.05.2019.<br>";
			$retorno .= "<b>843/$ano</b>(CPL-II), publicada no DOE de 07.05.2019<br>";
			$retorno .= "<b>844/$ano</b>(CPL-III), publicada no DOE de 07.05.2019<br>";
			$retorno .= "<b>2476/$ano</b>(".static::getDescricao(static::$CD_CPL_CEL) ."), publicada no DOE de 11.10.2019<br>";
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
		
	/**
	 * traz colecao de comissoes com as possibilidades de serem encontradadas dentro do numero extenso do proc licitatorio
	 * @return string
	 */
	static function getColecaoComissaoNormalizacaoProcLicitatorio(){
		//deixa na ultima posicao as especies que podem se repetir
		// A ORDEM EH IMPORTANTE, pq o item seguinte so sera selecionado se o anterior nao contiver nenhuma palavra em comum
		$colecao = static::getColecao();
		foreach ($colecao as $cd => $ds){
			$retorno[$cd] = static::getOpcoesComissaoFormatado($ds);			
		}
		
		return $retorno;
	}
	
	static function getOpcoesComissaoFormatado($ds){
		//simula as varias possibilidades de ingresarem o proc licitatorio por extenso, baseado nos valores da planilha
		return "$ds*". str_replace("-", "", $ds) 
		. "*" . str_replace("-", " ", $ds)
		. "*" . str_replace("-", ".", $ds)
		;
		
	}
	
	
}
?>