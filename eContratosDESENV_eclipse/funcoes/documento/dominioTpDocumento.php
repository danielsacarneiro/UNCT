<?php
include_once (caminho_util . "dominio.class.php");
class dominioTpDocumento extends dominio {
	static $ENDERECO_DRIVE = "\\\\sf044836\\_dag$";
	// static $ENDERECO_DRIVE_HTML = "\\sf044836\\\\_dag$";
	static $ENDERECO_DRIVE_HTML = "H:";
	static $ENDERECO_PASTABASE = "ATJA";
	static $ENDERECO_PASTABASE_UNCT = "UNCT";
	static $ENDERECO_PASTA_DOCUMENTOS = "\Documentos";
	static $ENDERECO_PASTA_PA = "\PROCESSO ADMINISTRATIVO";	
	
	static $CD_TP_DOC_PAAP = "PE";
	static $CD_TP_DOC_APOSTILAMENTO = "AP";
	static $CD_TP_DOC_CONTROLE_AQUISICAO = "CA";
	static $CD_TP_DOC_CHECKLIST = "CK";
	static $CD_TP_DOC_CI = "CI";	
	static $CD_TP_DOC_DECISAO = "DC";
	static $CD_TP_DOC_DEFESA = "DF";
	static $CD_TP_DOC_DESPACHO = "DE";
	//static $CD_TP_DOC_INTIMACAO = "IN";
	static $CD_TP_DOC_LEGISLACAO = "LE";
	static $CD_TP_DOC_NOTA_TECNICA = "NT";
	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
	//static $CD_TP_DOC_NOTIFICACAO = "NO";
	static $CD_TP_DOC_OFICIO = "OF";
	static $CD_TP_DOC_OUTROS = "OT";
	static $CD_TP_DOC_PARECER = "PA";
	static $CD_TP_DOC_PLANILHA_CUSTOS = "PC";
	static $CD_TP_DOC_PROPOSTA_PRECOS = "PP";
	static $CD_TP_DOC_PUBLICACAO_PAAP = "PU";
	static $CD_TP_DOC_RELATORIO = "RE";
	//static $CD_TP_DOC_RELATORIO_CONCLUSAO = "RC";
	static $CD_TP_DOC_MINUTA = "MI";
	static $CD_TP_DOC_APRECIACAO_RECURSO = "AR";
	
	static $DS_TP_DOC_PAAP = "PAAP(digitalizado)";
	static $DS_TP_DOC_APOSTILAMENTO = "Apostilamento";
	static $DS_TP_DOC_CONTROLE_AQUISICAO = "Controle Aquisição";
	static $DS_TP_DOC_CHECKLIST = "Checklist";
	static $DS_TP_DOC_CI = "CI";
	static $DS_TP_DOC_DECISAO = "Decisão";
	static $DS_TP_DOC_DEFESA = "Defesa";
	static $DS_TP_DOC_DESPACHO = "Despacho";
	//static $DS_TP_DOC_INTIMACAO = "Intimação";
	static $DS_TP_DOC_LEGISLACAO = "Legislação";
	static $DS_TP_DOC_NOTA_TECNICA = "Nota Técnica";
	static $DS_TP_DOC_NOTA_IMPUTACAO = "Nota de Imputação";
	//static $DS_TP_DOC_NOTIFICACAO = "Notificação";
	static $DS_TP_DOC_OFICIO = "Ofício";
	static $DS_TP_DOC_OUTROS = "Outros";
	static $DS_TP_DOC_PARECER = "Parecer";
	static $DS_TP_DOC_PLANILHA_CUSTOS = "Planilha de Custos";
	static $DS_TP_DOC_PROPOSTA_PRECOS = "Proposta de Preços";
	static $DS_TP_DOC_PUBLICACAO_PAAP = "Publicação Penalidade";
	static $DS_TP_DOC_RELATORIO = "Relatório";
	//static $DS_TP_DOC_RELATORIO_CONCLUSAO = "Relatório Com Alegações Finais";
	static $DS_TP_DOC_MINUTA = "Minuta";
	static $DS_TP_DOC_APRECIACAO_RECURSO = "Apreciação Recurso";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao ();
		ksort($this->colecao);
	}
	static function getColecao() {
		return array (
				//self::$CD_TP_DOC_APOSTILAMENTO => self::$DS_TP_DOC_APOSTILAMENTO,
				self::$CD_TP_DOC_CONTROLE_AQUISICAO => self::$DS_TP_DOC_CONTROLE_AQUISICAO,
				self::$CD_TP_DOC_CHECKLIST => self::$DS_TP_DOC_CHECKLIST,
				self::$CD_TP_DOC_CI => self::$DS_TP_DOC_CI,
				self::$CD_TP_DOC_DECISAO => self::$DS_TP_DOC_DECISAO,
				self::$CD_TP_DOC_DEFESA => self::$DS_TP_DOC_DEFESA,
				self::$CD_TP_DOC_DESPACHO => self::$DS_TP_DOC_DESPACHO,
				//self::$CD_TP_DOC_INTIMACAO => self::$DS_TP_DOC_INTIMACAO,
				self::$CD_TP_DOC_LEGISLACAO => self::$DS_TP_DOC_LEGISLACAO,
				self::$CD_TP_DOC_OFICIO => self::$DS_TP_DOC_OFICIO,
				self::$CD_TP_DOC_OUTROS => self::$DS_TP_DOC_OUTROS,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => self::$DS_TP_DOC_NOTA_IMPUTACAO,
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota Técnica",
				//self::$CD_TP_DOC_NOTIFICACAO => self::$DS_TP_DOC_NOTIFICACAO,
				self::$CD_TP_DOC_PARECER => self::$DS_TP_DOC_PARECER,
				self::$CD_TP_DOC_PLANILHA_CUSTOS => self::$DS_TP_DOC_PLANILHA_CUSTOS,
				self::$CD_TP_DOC_PROPOSTA_PRECOS => self::$DS_TP_DOC_PROPOSTA_PRECOS,
				self::$CD_TP_DOC_PUBLICACAO_PAAP => self::$DS_TP_DOC_PUBLICACAO_PAAP,
				self::$CD_TP_DOC_RELATORIO => self::$DS_TP_DOC_RELATORIO,
				self::$CD_TP_DOC_PAAP => self::$DS_TP_DOC_PAAP,
				self::$CD_TP_DOC_MINUTA => self::$DS_TP_DOC_MINUTA,
				self::$CD_TP_DOC_APRECIACAO_RECURSO => self::$DS_TP_DOC_APRECIACAO_RECURSO,
				//self::$CD_TP_DOC_RELATORIO_CONCLUSAO => self::$DS_TP_DOC_RELATORIO_CONCLUSAO,
		);
	}
	static function getColecaoDocsPAAP() {
		return array (
				self::$CD_TP_DOC_DECISAO => self::$DS_TP_DOC_DECISAO,
				self::$CD_TP_DOC_DEFESA => self::$DS_TP_DOC_DEFESA,
				self::$CD_TP_DOC_DESPACHO => self::$DS_TP_DOC_DESPACHO,
				//self::$CD_TP_DOC_INTIMACAO => self::$DS_TP_DOC_INTIMACAO,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => self::$DS_TP_DOC_NOTA_IMPUTACAO,
				self::$CD_TP_DOC_PUBLICACAO_PAAP => self::$DS_TP_DOC_PUBLICACAO_PAAP,
				self::$CD_TP_DOC_RELATORIO => self::$DS_TP_DOC_RELATORIO,
				self::$CD_TP_DOC_PAAP => self::$DS_TP_DOC_PAAP,
				self::$CD_TP_DOC_APRECIACAO_RECURSO => self::$DS_TP_DOC_APRECIACAO_RECURSO,
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