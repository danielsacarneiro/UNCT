<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_DRIVE = "\\\\sf044836\_dag$";
  	static $ENDERECO_PASTABASE = "ASSESSORIA JUR�DICA\ATJA";
  	static $ENDERECO_PASTABASE_UNCT = "UNCT";
  	
  	static $ENDERECO_PASTA_DOCUMENTOS = "\Documentos";
  	  	  	
  	static $CD_TP_DOC_OFICIO = "OF";
  	static $CD_TP_DOC_CI= "CI";
  	static $CD_TP_DOC_NOTA_TECNICA = "NT";
  	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
  	static $CD_TP_DOC_NOTIFICACAO = "NO";
  	static $CD_TP_DOC_OUTROS = "OT";
  	static $CD_TP_DOC_PLANILHA_CUSTOS = "PC";

  	static $DS_TP_DOC_OFICIO = "Of�cio";
  	static $DS_TP_DOC_CI= "Com. Interna";
  	static $DS_TP_DOC_NOTA_TECNICA = "Nota T�cnica";
  	static $DS_TP_DOC_NOTA_IMPUTACAO = "Nota Imputa��o";
  	static $DS_TP_DOC_NOTIFICACAO = "Notifica��o";
  	static $DS_TP_DOC_OUTROS = "Outros";
  	static $DS_TP_DOC_PLANILHA_CUSTOS = "Planilha de Custos";
  	 
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_TP_DOC_OFICIO => self::$DS_TP_DOC_OFICIO,
				self::$CD_TP_DOC_CI => self::$DS_TP_DOC_CI,
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica",
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputa��o",
				self::$CD_TP_DOC_NOTIFICACAO => self::$DS_TP_DOC_NOTIFICACAO,
				self::$CD_TP_DOC_PLANILHA_CUSTOS => self::$DS_TP_DOC_PLANILHA_CUSTOS,
				self::$CD_TP_DOC_OUTROS => self::$DS_TP_DOC_OUTROS
				);
	}	
	
	static function getEnderecoPastaBase() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE;
	}
	static function getEnderecoPastaBaseUNCT() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE_UNCT;
	}
	
	static function getEnderecoPastaBasePorTpDocumento($tpDoc) {		
		return self::getDescricaoStatic($tpDoc, self::getColecao());
	}
	
}
?>