<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_DRIVE = "\\\\sf044836\\_dag$";
  	//static $ENDERECO_DRIVE_HTML = "\\sf044836\\\\_dag$";
  	static $ENDERECO_DRIVE_HTML = "H:";
  	static $ENDERECO_PASTABASE = "ASSESSORIA JURÍDICA\ATJA";
  	static $ENDERECO_PASTABASE_UNCT = "UNCT";
  	
  	static $ENDERECO_PASTA_DOCUMENTOS = "\Documentos";
  	  	  	
  	static $CD_TP_DOC_OFICIO = "OF";
  	static $CD_TP_DOC_CI= "CI";
  	static $CD_TP_DOC_NOTA_TECNICA = "NT";
  	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
  	static $CD_TP_DOC_NOTIFICACAO = "NO";
  	static $CD_TP_DOC_OUTROS = "OT";
  	static $CD_TP_DOC_PLANILHA_CUSTOS = "PC";
  	static $CD_TP_DOC_PARECER = "PA";
  	static $CD_TP_DOC_APOSTILAMENTO = "AP";  	

  	static $DS_TP_DOC_OFICIO = "Ofício";
  	static $DS_TP_DOC_CI= "CI";
  	static $DS_TP_DOC_NOTA_TECNICA = "Nota Técnica";
  	static $DS_TP_DOC_NOTA_IMPUTACAO = "Nota Imputação";
  	static $DS_TP_DOC_NOTIFICACAO = "Notificação";
  	static $DS_TP_DOC_OUTROS = "Outros";
  	static $DS_TP_DOC_PLANILHA_CUSTOS = "Planilha de Custos";
  	static $DS_TP_DOC_PARECER = "Parecer";
  	static $DS_TP_DOC_APOSTILAMENTO = "Apostilamento";
  	 
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_TP_DOC_OFICIO => self::$DS_TP_DOC_OFICIO,
				self::$CD_TP_DOC_CI => self::$DS_TP_DOC_CI,
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota Técnica",
				self::$CD_TP_DOC_PARECER => self::$DS_TP_DOC_PARECER,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => "Nota Imputação",
				self::$CD_TP_DOC_NOTIFICACAO => self::$DS_TP_DOC_NOTIFICACAO,
				self::$CD_TP_DOC_PLANILHA_CUSTOS => self::$DS_TP_DOC_PLANILHA_CUSTOS,
				self::$CD_TP_DOC_APOSTILAMENTO => self::$DS_TP_DOC_APOSTILAMENTO,
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
		$retorno = self::getDescricaoStatic($tpDoc, self::getColecao());
		if($tpDoc == self::$CD_TP_DOC_APOSTILAMENTO){
			$retorno = "Apostilamentos";
		}
		
		return $retorno;
	}
	
}
?>