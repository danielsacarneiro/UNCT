<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_PASTABASE = "H:\ASSESSORIA JUR�DICA\ATJA";
  	static $ENDERECO_PASTA_NOTA_TECNICA = "\Notas T�cnicas";  	
  	static $ENDERECO_PASTA_OFICIO = "\Of�cios";
  	
  	static $CD_TP_DOC_OFICIO = 1;  	
  	static $CD_TP_DOC_NOTA_TECNICA = 2;
  	static $CD_TP_DOC_NOTA_IMPUTACAO = 3;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_TP_DOC_OFICIO => "Of�cio",
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputa��o"
				);
	}
	
}
?>