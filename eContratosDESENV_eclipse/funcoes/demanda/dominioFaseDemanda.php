<?php
include_once (caminho_util . "dominio.class.php");
include_once (caminho_util . "constantes.class.php");

class dominioFaseDemanda extends dominio {
	static $CD_VISTO_JURIDICO = "01";
	static $CD_REVISAO_UNCT = "02";
	static $CD_ASSINADO_FISICO = "03";
	static $CD_GARANTIA_PRESTADA = "04";
	static $CD_VISTO_SAD = "05";
	static $CD_VISTO_PGE = "06";
	static $CD_ASSINADO_DIGITAL = "07";
	static $CD_ASSINADO_SEI = "08";
		
	static $DS_VISTO_JURIDICO = "Visado.ATJA";
	static $DS_REVISAO_UNCT = "Revisado";
	static $DS_ASSINADO_FISICO = "Assinado.Físico";
	static $DS_GARANTIA_PRESTADA = "Garantia.OK";
	static $DS_VISTO_SAD = "Visto.SAD";
	static $DS_VISTO_PGE = "Visto.PGE";
	static $DS_ASSINADO_DIGITAL = "Assinado.Digital";
	static $DS_ASSINADO_SEI = "Assinado.SEI";
	
	//usados somente pra consultas
	static $CD_PUBLICADO = "PUBLICADO";
	static $CD_ASSINADO = "ASSINADO";
	static $DS_PUBLICADO = "Publicado";
	static $DS_ASSINADO = "Assinado";
	
	static function getColecaoConsulta() {
		$array1 = static::getColecao();
		$array2 = array (
				constantes::$CD_OPCAO_NENHUM => constantes::$DS_OPCAO_NENHUM,
		);
		$retorno = putElementoArray2NoArray1ComChaves ( $array1, $array2);
		
		return $retorno;
	}
	
	
	static function getColecao() {
		$retorno = array (
				static::$CD_REVISAO_UNCT=> self::$DS_REVISAO_UNCT,
				static::$CD_VISTO_JURIDICO => self::$DS_VISTO_JURIDICO,
				static::$CD_ASSINADO_FISICO => self::$DS_ASSINADO_FISICO,
				static::$CD_ASSINADO_DIGITAL => self::$DS_ASSINADO_DIGITAL,
				static::$CD_ASSINADO_SEI => self::$DS_ASSINADO_SEI,
				static::$CD_GARANTIA_PRESTADA => self::$DS_GARANTIA_PRESTADA,
				static::$CD_VISTO_SAD => self::$DS_VISTO_SAD,
				static::$CD_VISTO_PGE => self::$DS_VISTO_PGE,
		);
				
		return $retorno;
	}

	/**
	 * usado para fases retiradas da planilha
	 */
	static function getColecaoPlanilha() {
		$retorno = array (
				static::$CD_ASSINADO => self::$DS_ASSINADO,
				static::$CD_PUBLICADO => self::$DS_PUBLICADO,
		);
	
		return $retorno;
	}
	
	
	static function getColecaoFaseContratoAssinado() {
		$retorno = array (
				static::$CD_ASSINADO_FISICO,
				static::$CD_ASSINADO_DIGITAL,
				static::$CD_ASSINADO_SEI,
		);
	
		return $retorno;
	}
	
	
	/**
	 * metodo que indica as caracteristicas do usuario que possuem permissao para alterar as opcoes indicadas do dominio
	 * geralmente utilizada para a funcao java script isCheckBoxPermiteAlteracao que esta em encaminhar.novo.php
	 * @return unknown[]|string[]
	 */
	static function getColecaoPermissaoCaracteristicasUsuario() {
		$retorno = array (
				static::$CD_REVISAO_UNCT => getArrayComoStringCampoSeparador(array(dominioUsuarioCaracteristicas::$CD_CHEFE)),
		);
	
		return $retorno;
	}
	
}
