<?php
include_once (caminho_util . "dominio.class.php");
class dominioTpDocumento extends dominio {
	static $ENDERECO_DRIVE = "\\\\sf044836\\_dag$";
	// static $ENDERECO_DRIVE_HTML = "\\sf044836\\\\_dag$";
	static $ENDERECO_DRIVE_HTML = "H:";
	static $ENDERECO_PASTABASE = "ASSESSORIA JUR�DICA\ATJA";
	static $ENDERECO_PASTABASE_UNCT = "UNCT";
	static $ENDERECO_PASTA_DOCUMENTOS = "\Documentos";
	static $ENDERECO_PASTA_PA = "\PROCESSO ADMINISTRATIVO";	
	
	static $CD_TP_DOC_APOSTILAMENTO = "AP";
	static $CD_TP_DOC_CI = "CI";
	static $CD_TP_DOC_DECISAO = "DC";
	static $CD_TP_DOC_DESPACHO = "DE";
	static $CD_TP_DOC_INTIMACAO = "IN";
	static $CD_TP_DOC_NOTA_TECNICA = "NT";
	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
	static $CD_TP_DOC_NOTIFICACAO = "NO";
	static $CD_TP_DOC_OFICIO = "OF";
	static $CD_TP_DOC_OUTROS = "OT";
	static $CD_TP_DOC_PARECER = "PA";
	static $CD_TP_DOC_PLANILHA_CUSTOS = "PC";
	static $CD_TP_DOC_RELATORIO = "RE";
	static $CD_TP_DOC_RELATORIO_CONCLUSAO = "RC";
	
	static $DS_TP_DOC_APOSTILAMENTO = "Apostilamento";
	static $DS_TP_DOC_CI = "CI";
	static $DS_TP_DOC_DECISAO = "Decis�o";
	static $DS_TP_DOC_DESPACHO = "Despacho";
	static $DS_TP_DOC_INTIMACAO = "Intima��o";
	static $DS_TP_DOC_NOTA_TECNICA = "Nota T�cnica";
	static $DS_TP_DOC_NOTA_IMPUTACAO = "Nota de Imputa��o";
	static $DS_TP_DOC_NOTIFICACAO = "Notifica��o";
	static $DS_TP_DOC_OFICIO = "Of�cio";
	static $DS_TP_DOC_OUTROS = "Outros";
	static $DS_TP_DOC_PARECER = "Parecer";
	static $DS_TP_DOC_PLANILHA_CUSTOS = "Planilha de Custos";
	static $DS_TP_DOC_RELATORIO = "Relat�rio";
	static $DS_TP_DOC_RELATORIO_CONCLUSAO = "Relat�rio Com Alega��es Finais";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao ();
		ksort($this->colecao);
	}
	static function getColecao() {
		return array (
				self::$CD_TP_DOC_OFICIO => self::$DS_TP_DOC_OFICIO,
				self::$CD_TP_DOC_CI => self::$DS_TP_DOC_CI,
				self::$CD_TP_DOC_DECISAO => self::$DS_TP_DOC_DECISAO,
				self::$CD_TP_DOC_DESPACHO => self::$DS_TP_DOC_DESPACHO,				
				self::$CD_TP_DOC_INTIMACAO => self::$DS_TP_DOC_INTIMACAO,
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica",
				self::$CD_TP_DOC_PARECER => self::$DS_TP_DOC_PARECER,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => self::$DS_TP_DOC_NOTA_IMPUTACAO,
				self::$CD_TP_DOC_NOTIFICACAO => self::$DS_TP_DOC_NOTIFICACAO,
				self::$CD_TP_DOC_PLANILHA_CUSTOS => self::$DS_TP_DOC_PLANILHA_CUSTOS,
				self::$CD_TP_DOC_APOSTILAMENTO => self::$DS_TP_DOC_APOSTILAMENTO,
				self::$CD_TP_DOC_OUTROS => self::$DS_TP_DOC_OUTROS,
				self::$CD_TP_DOC_RELATORIO => self::$DS_TP_DOC_RELATORIO,
				//self::$CD_TP_DOC_RELATORIO_CONCLUSAO => self::$DS_TP_DOC_RELATORIO_CONCLUSAO,
		);
	}
	static function getColecaoDocsPAAP() {
		return array (
				self::$CD_TP_DOC_DECISAO => self::$DS_TP_DOC_DECISAO,
				self::$CD_TP_DOC_DESPACHO => self::$DS_TP_DOC_DESPACHO,
				self::$CD_TP_DOC_INTIMACAO => self::$DS_TP_DOC_INTIMACAO,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => self::$DS_TP_DOC_NOTA_IMPUTACAO,
				self::$CD_TP_DOC_RELATORIO => self::$DS_TP_DOC_RELATORIO,
				//self::$CD_TP_DOC_RELATORIO_CONCLUSAO => self::$DS_TP_DOC_RELATORIO_CONCLUSAO,
		);
	}
	static function getEnderecoPastaBase() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE;
	}
	static function getEnderecoPastaBaseUNCT() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE_UNCT;
	}
	static function getEnderecoPastaBasePorTpDocumento($tpDoc) {
		$retorno = self::getDescricaoStatic ( $tpDoc, self::getColecao () );
		
		if ($tpDoc == self::$CD_TP_DOC_APOSTILAMENTO) {
			$retorno = "Apostilamentos";
		}
		
		return $retorno;
	}
}
?>