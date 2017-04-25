<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_DRIVE = "\\\\sf044836\_dag$";
  	static $ENDERECO_PASTABASE = "ASSESSORIA JURÍDICA\ATJA";
  	static $ENDERECO_PASTABASE_UNCT = "UNCT\Documentos";
  	//static $ENDERECO_PASTABASE = "H:\ASSESSORIA JURÍDICA\ATJA";
  	static $ENDERECO_PASTA_NOTA_TECNICA = "\Notas Técnicas";  	
  	static $ENDERECO_PASTA_OFICIO = "\Ofícios";
  	static $ENDERECO_PASTA_PA = "\PROCESSO ADMINISTRATIVO";
  	static $ENDERECO_PASTA_NOTIFICACAO = "\Notificacao";
  	static $ENDERECO_PASTA_NOTAS_IMPUTACAO = "\Notas de Imputação";
  	  	
  	static $CD_TP_DOC_OFICIO = "OF";
  	static $CD_TP_DOC_NOTA_TECNICA = "NT";
  	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
  	static $CD_TP_DOC_NOTIFICACAO = "NO";
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_TP_DOC_OFICIO => "Ofício",
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota Técnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputação",
				self::$CD_TP_DOC_NOTIFICACAO => "Notificação"
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