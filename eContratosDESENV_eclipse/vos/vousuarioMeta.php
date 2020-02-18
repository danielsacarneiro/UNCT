<?php
include_once(caminho_lib."voentidade.php");

Class vousuarioMeta extends voentidade{
	
        static $nmAtrID  = "ID";        
        static $nmAtrMetaID  = "unmeta_id";
        static $nmAtrMetaValor = "meta_value";
        static $nmAtrMetaColuna = "meta_key";
        
        static $NM_CHAVE_META_LEVEL = "wp_user_level";        
		
		var $id;
		var $metaId;
	
	function __construct() {
		parent::__construct0();
		$this->temTabHistorico = false;

		$arrayAtribRemover = array (
		 self::$nmAtrDhInclusao,
		 self::$nmAtrCdUsuarioInclusao,
		 );
		$arrayAtribInclusaoDBDefault = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrDhUltAlteracao
		);
		$this->setaAtributosRemocaoEInclusaoDBDefault($arrayAtribRemover, $arrayAtribInclusaoDBDefault);		
	}	
	
	public static function getTituloJSP(){
		return  "DADOS META-USU�RIO";
	}
	
	public static function getNmTabela(){
		return  "wp_usermeta";
	}
		
	/*public static function getNmClassProcesso(){
		return  "dbusuario";
	}*/

	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = $this->getNmTabelaEntidade($isHistorico);
		$query = $nmTabela . "." . self::$nmAtrMetaID . "=" . $this->metaId;
	
		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
	
			return $query;
	}
	
	static function getAtributosFilho() {
		$array1 = static::getAtributosChavePrimaria();
	
		$array2 = array (
				self::$nmAtrID,
				self::$nmAtrMetaColuna,
				self::$nmAtrMetaValor,
		);
		$retorno = array_merge($array1, $array2);
	
		return $retorno;
	}
	
	/**
	 *  Chave primaria
	 */
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrID,
		);
	
		return $retorno;
	}
	
// ...............................................................
// Funções ( Propriedades e métodos da classe )
		
	function getUsuarioBanco($registrobanco){		
		$this->id = $registrobanco[static::$nmAtrID];
		$this->metaId = $registrobanco[static::$nmAtrMetaID];		
	}

}
?>