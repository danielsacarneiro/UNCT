<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	
  	static $ENDERECO_PASTABASE = "\\\\sf044836\_dag$\ASSESSORIA JUR�DICA\ATJA";
  	//static $ENDERECO_PASTABASE = "H:\ASSESSORIA JUR�DICA\ATJA";
  	static $ENDERECO_PASTA_NOTA_TECNICA = "\Notas T�cnicas";  	
  	static $ENDERECO_PASTA_OFICIO = "\Of�cios";
  	static $ENDERECO_PASTA_PA = "\PROCESSO ADMINISTRATIVO";
  	static $ENDERECO_PASTA_NOTIFICACAO = "\Notificacao";
  	  	
  	static $CD_TP_DOC_OFICIO = "OF";
  	static $CD_TP_DOC_NOTA_TECNICA = "NT";
  	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
  	static $CD_TP_DOC_NOTIFICACAO = "NO";
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = array(
				self::$CD_TP_DOC_OFICIO => "Of�cio",
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputa��o",
				self::$CD_TP_DOC_NOTIFICACAO => "Notifica��o"
				);
	}
	
}
?>