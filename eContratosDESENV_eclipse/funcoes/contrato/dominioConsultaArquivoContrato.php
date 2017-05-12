<?php
include_once(caminho_util."dominio.class.php");

Class dominioConsultaArquivoContrato extends dominio{
	static $CD_CONSULTA_COMUM = "N";
	static $CD_CONSULTA_ARQUIVO_CONTRATO_ASSINADO = "ASS";
	static $CD_CONSULTA_ARQUIVO_CONTRATO_MATER = "CM";
	static $CD_CONSULTA_ARQUIVO_TERMO_ADITIVO = "TA";

	static $DS_CONSULTA_COMUM = "Não";
	static $DS_CONSULTA_ARQUIVO_CONTRATO_ASSINADO = "Contrato Assinado";
	static $DS_CONSULTA_ARQUIVO_CONTRATO_MATER = "Contrato Mater";
	static $DS_CONSULTA_ARQUIVO_TERMO_ADITIVO = "Termo Aditivo";
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
		//ksort($this->colecao);
	}

	static function getColecao(){
		$retorno = array(
				self::$CD_CONSULTA_COMUM => self::$DS_CONSULTA_COMUM,
				self::$CD_CONSULTA_ARQUIVO_CONTRATO_ASSINADO => self::$DS_CONSULTA_ARQUIVO_CONTRATO_ASSINADO,
				self::$CD_CONSULTA_ARQUIVO_CONTRATO_MATER => self::$DS_CONSULTA_ARQUIVO_CONTRATO_MATER,
				self::$CD_CONSULTA_ARQUIVO_TERMO_ADITIVO => self::$DS_CONSULTA_ARQUIVO_TERMO_ADITIVO			
		);

		//uksort($retorno, 'strnatcmp');

		return $retorno;
	}

}
?>