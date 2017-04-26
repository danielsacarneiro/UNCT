<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_DRIVE = "\\\\sf044836\_dag$";
  	static $ENDERECO_PASTABASE = "ASSESSORIA JUR�DICA\ATJA";
  	static $ENDERECO_PASTABASE_UNCT = "UNCT";
  	
  	static $ENDERECO_PASTA_DOCUMENTOS = "\Documentos";
  	//static $ENDERECO_PASTABASE = "H:\ASSESSORIA JUR�DICA\ATJA";  	
  	static $ENDERECO_PASTA_NOTA_TECNICA = "\Notas T�cnicas";  	
  	static $ENDERECO_PASTA_OUTROS = "\Outros";
  	static $ENDERECO_PASTA_OFICIO = "\Of�cios";
  	static $ENDERECO_PASTA_PA = "\PROCESSO ADMINISTRATIVO";
  	static $ENDERECO_PASTA_NOTIFICACAO = "\Notificacao";
  	static $ENDERECO_PASTA_NOTAS_IMPUTACAO = "\Notas de Imputa��o";
  	  	
  	static $CD_TP_DOC_OFICIO = "OF";
  	static $CD_TP_DOC_NOTA_TECNICA = "NT";
  	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
  	static $CD_TP_DOC_NOTIFICACAO = "NO";
  	static $CD_TP_DOC_OUTROS = "OT";
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_TP_DOC_OFICIO => "Of�cio",
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputa��o",
				self::$CD_TP_DOC_NOTIFICACAO => "Notifica��o",
				self::$CD_TP_DOC_OUTROS => "Outros"
				);
	}	
	
	static function getEnderecoPastaBase() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE;
	}
	static function getEnderecoPastaBaseUNCT() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE_UNCT;
	}
	
}
?>