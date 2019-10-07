<?php
include_once (caminho_util . "dominio.class.php");
class dominioTpDocumento extends dominio {
	static $UNIDADE_REDE_LOCAL = "J:";
	static $UNIDADE_REDE_PLANILHA = "G:";
	static $ENDERECO_DRIVE = "\\\\sf044836\\_dag$";
	// static $ENDERECO_DRIVE_HTML = "\\sf044836\\\\_dag$";
	static $ENDERECO_DRIVE_HTML = "J:";
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
	// static $CD_TP_DOC_INTIMACAO = "IN";
	static $CD_TP_DOC_EDITAL = "ED";
	static $CD_TP_DOC_LEGISLACAO = "LE";
	static $CD_TP_DOC_NOTA_TECNICA = "NT";
	static $CD_TP_DOC_NOTA_IMPUTACAO = "NI";
	// static $CD_TP_DOC_NOTIFICACAO = "NO";
	static $CD_TP_DOC_OFICIO = "OF";
	static $CD_TP_DOC_OUTROS = "OT";
	static $CD_TP_DOC_PARECER = "PA";
	static $CD_TP_DOC_PLANILHA_CUSTOS = "PC";
	static $CD_TP_DOC_PLANILHA_LICON = "LC";
	static $CD_TP_DOC_PROPOSTA_PRECOS = "PP";
	static $CD_TP_DOC_PUBLICACAO_PAAP = "PU";
	static $CD_TP_DOC_RELATORIO = "RE";	
	// static $CD_TP_DOC_RELATORIO_CONCLUSAO = "RC";
	static $CD_TP_DOC_MINUTA = "MI";
	static $CD_TP_DOC_CERTIDAO_TRANSITO_JULGADO = "TJ";
	static $CD_TP_DOC_TERMO_CONST_CREDITO = "TC";
	
	static $DS_TP_DOC_PAAP = "PAAP(digitalizado)";
	static $DS_TP_DOC_APOSTILAMENTO = "Apostilamento";
	static $DS_TP_DOC_CONTROLE_AQUISICAO = "Controle Aquisi��o";
	static $DS_TP_DOC_CHECKLIST = "Checklist";
	static $DS_TP_DOC_CI = "CI";
	static $DS_TP_DOC_DECISAO = "Decis�o";
	static $DS_TP_DOC_DEFESA = "Defesa";
	static $DS_TP_DOC_DESPACHO = "Despacho";
	// static $DS_TP_DOC_INTIMACAO = "Intima��o";
	static $DS_TP_DOC_EDITAL = "Edital";
	static $DS_TP_DOC_LEGISLACAO = "Legisla��o";
	static $DS_TP_DOC_NOTA_TECNICA = "Nota T�cnica";
	static $DS_TP_DOC_NOTA_IMPUTACAO = "Nota de Imputa��o";
	// static $DS_TP_DOC_NOTIFICACAO = "Notifica��o";
	static $DS_TP_DOC_OFICIO = "Of�cio";
	static $DS_TP_DOC_OUTROS = "Outros";
	static $DS_TP_DOC_PARECER = "Parecer";
	static $DS_TP_DOC_PLANILHA_CUSTOS = "Planilha";
	static $DS_TP_DOC_PLANILHA_LICON = "Planilha Licon";
	static $DS_TP_DOC_PROPOSTA_PRECOS = "Proposta de Pre�os";
	static $DS_TP_DOC_PUBLICACAO_PAAP = "Publica��o";
	static $DS_TP_DOC_RELATORIO = "Relat�rio";
	// static $DS_TP_DOC_RELATORIO_CONCLUSAO = "Relat�rio Com Alega��es Finais";
	static $DS_TP_DOC_MINUTA = "Minuta";
	static $DS_TP_DOC_CERTIDAO_TRANSITO_JULGADO = "Certid�o Transito Julgado";
	//static $DS_TP_DOC_TERMO_CONST_CREDITO = "Termo Const. Cr�dito";
	static $DS_TP_DOC_TERMO_CONST_CREDITO = "Termo.Const.Cr�dito";
	
	// ...............................................................
	// Construtor
	function __construct() {
		$this->colecao = self::getColecao ();
	}
	
	static function getColecao() {
		$retorno = static::getColecaoComum();
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoATJA(false));
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoUNCT(false));
		ksort ( $retorno );
		return $retorno;
	}
	
	static function getColecaoCPL($comComum = false) {
	
		$retorno = array (
				self::$CD_TP_DOC_EDITAL => self::$DS_TP_DOC_EDITAL,
		);
	
		if($comComum)
			$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoComum () );
			// return ksort($retorno);
		ksort ( $retorno );
		return $retorno;
	}
	
	static function getColecaoATJA($comComum = true) {
				
		$retorno = array (
				self::$CD_TP_DOC_CONTROLE_AQUISICAO => self::$DS_TP_DOC_CONTROLE_AQUISICAO,
				self::$CD_TP_DOC_LEGISLACAO => self::$DS_TP_DOC_LEGISLACAO,
				self::$CD_TP_DOC_PARECER => self::$DS_TP_DOC_PARECER,
				self::$CD_TP_DOC_PLANILHA_CUSTOS => self::$DS_TP_DOC_PLANILHA_CUSTOS,
				self::$CD_TP_DOC_PROPOSTA_PRECOS => self::$DS_TP_DOC_PROPOSTA_PRECOS,
				//self::$CD_TP_DOC_PLANILHA_LICON => self::$DS_TP_DOC_PLANILHA_LICON,
		);
		
		$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoDocsPAAP());
		
		if($comComum)
			$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoComum () );
		// return ksort($retorno);
		ksort ( $retorno );
		return $retorno;
	}
	static function getColecaoComum() {
		$retorno = array (
				self::$CD_TP_DOC_CHECKLIST => self::$DS_TP_DOC_CHECKLIST,
				self::$CD_TP_DOC_CI => self::$DS_TP_DOC_CI,
				self::$CD_TP_DOC_OFICIO => self::$DS_TP_DOC_OFICIO,
				self::$CD_TP_DOC_OUTROS => self::$DS_TP_DOC_OUTROS,
				self::$CD_TP_DOC_NOTA_TECNICA => "Nota T�cnica" 
		);
		
		ksort ( $retorno );
		return $retorno;
	}
	static function getColecaoUNCT($comComum = true) {
		$retorno = array (
				//self::$CD_TP_DOC_APOSTILAMENTO => self::$DS_TP_DOC_APOSTILAMENTO,
				self::$CD_TP_DOC_MINUTA => self::$DS_TP_DOC_MINUTA 
		);
		
		if($comComum)
			$retorno = putElementoArray2NoArray1ComChaves ( $retorno, static::getColecaoComum () );
		
		ksort ( $retorno );
		return $retorno;
	}
	static function getColecaoDocsPAAP() {
		$retorno = array (
				self::$CD_TP_DOC_DECISAO => self::$DS_TP_DOC_DECISAO,
				self::$CD_TP_DOC_DEFESA => self::$DS_TP_DOC_DEFESA,
				self::$CD_TP_DOC_DESPACHO => self::$DS_TP_DOC_DESPACHO,
				// self::$CD_TP_DOC_INTIMACAO => self::$DS_TP_DOC_INTIMACAO,
				self::$CD_TP_DOC_NOTA_IMPUTACAO => self::$DS_TP_DOC_NOTA_IMPUTACAO,
				self::$CD_TP_DOC_PUBLICACAO_PAAP => self::$DS_TP_DOC_PUBLICACAO_PAAP,
				self::$CD_TP_DOC_RELATORIO => self::$DS_TP_DOC_RELATORIO,
				self::$CD_TP_DOC_PAAP => self::$DS_TP_DOC_PAAP,
				self::$CD_TP_DOC_CERTIDAO_TRANSITO_JULGADO => self::$DS_TP_DOC_CERTIDAO_TRANSITO_JULGADO, 
				self::$CD_TP_DOC_TERMO_CONST_CREDITO => self::$DS_TP_DOC_TERMO_CONST_CREDITO,
		);

		ksort ( $retorno );
		return $retorno;
	}
	static function getEnderecoPastaBase() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE;
	}
	static function getEnderecoPastaBaseUNCT() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE_UNCT;
	}
	static function getEnderecoPastaBasePorTpDocumento($tpDoc) {
		$retorno = self::getDescricaoStatic ( $tpDoc, self::getColecao () );
		
		/*if ($tpDoc == self::$CD_TP_DOC_APOSTILAMENTO) {
			$retorno = "Apostilamentos";
		}*/
		
		return $retorno;
	}
}
?>