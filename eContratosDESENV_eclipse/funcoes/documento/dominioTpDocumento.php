<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_PASTABASE = "H:\ASSESSORIA JURÍDICA\ATJA";
  	static $ENDERECO_PASTA_NOTA_TECNICA = "\Notas Técnicas";  	
  	static $ENDERECO_PASTA_OFICIO = "\Ofícios";
  	
  	static $CD_TP_DOC_OFICIO = 1;  	
  	static $CD_TP_DOC_NOTA_TECNICA = 2;
  	static $CD_TP_DOC_NOTA_IMPUTACAO = 3;

// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_TP_DOC_OFICIO => "Ofício",
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota Técnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputação"
				);
	}
	
}
?>