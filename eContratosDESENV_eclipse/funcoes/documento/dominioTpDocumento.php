<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	static $CD_TP_DOC_OFICIO = 1;
  	static $CD_TP_DOC_NOTA_IMPUTACAO = 2;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_TP_DOC_OFICIO => "Ofнcio",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputaзгo"
				);
	}
	
}
?>