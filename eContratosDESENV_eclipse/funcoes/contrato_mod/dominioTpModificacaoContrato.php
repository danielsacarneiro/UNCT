<?php
include_once(caminho_util."dominio.class.php");
include_once(caminho_util."constantes.class.php");

Class dominioTpContratoModificacao extends dominio{	

	static $CD_TIPO_REAJUSTE = 1;
	static $CD_TIPO_ACRESCIMO = 2;
	static $CD_TIPO_SUPRESSAO = 3;
	static $CD_TIPO_PRORROGACAO = 4;
	static $CD_TIPO_REPACTUACAO = 5;
	
	static $DS_TIPO_ACRESCIMO = "Acrщscimo";
	static $DS_TIPO_SUPRESSAO = "Supressуo";
	static $DS_TIPO_REAJUSTE = "Reajuste";
	static $DS_TIPO_PRORROGACAO = "Prorrogaчуo";
	static $DS_TIPO_REPACTUACAO = "Revisуo";
	// ...............................................................

	static function getColecao(){
		return array(				
				self::$CD_TIPO_ACRESCIMO => self::$DS_TIPO_ACRESCIMO,
				self::$CD_TIPO_SUPRESSAO=> self::$DS_TIPO_SUPRESSAO,
				self::$CD_TIPO_REAJUSTE=> self::$DS_TIPO_REAJUSTE,
				self::$CD_TIPO_PRORROGACAO => self::$DS_TIPO_PRORROGACAO,
				self::$CD_TIPO_REPACTUACAO => self::$DS_TIPO_REPACTUACAO,
		);
	}
		
	static function getColecaoQueNaoAlteramValorContrato(){
		return array(
				self::$CD_TIPO_PRORROGACAO => self::$DS_TIPO_PRORROGACAO,
		);
	}
	
	/**
	 * tabela de cores html https://celke.com.br/artigo/tabela-de-cores-html-nome-hexadecimal-rgb
	 * @param unknown $tipo
	 * @return string
	 */
	static function getCorTpModificacao($tipo) {
		$cor = "";
		if ($tipo == static::$CD_TIPO_REAJUSTE) {
			$cor = "green";
		} else if ($tipo == static::$CD_TIPO_ACRESCIMO) {
			$cor = "blue";
		} else if ($tipo == static::$CD_TIPO_SUPRESSAO) {
			$cor = "red";
		} else if ($tipo == static::$CD_TIPO_PRORROGACAO) {
			$cor = "Chocolate";
		} else if ($tipo == static::$CD_TIPO_REPACTUACAO) {
			$cor = "Olive";
		}
		
		return $cor;
	}
}
?>