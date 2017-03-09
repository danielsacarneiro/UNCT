<?php
include_once(caminho_lib."voentidade.php");
include_once("dbTramitacao.php");
include_once("voDocumento.php");

  Class voTramitacao extends voentidade{
  	   		 
  	static $nmAtrSq = "sq_tram";
  	static $nmAtrObservacao = "tr_observacao";
  	 
  	static $nmAtrCdSetorDoc = "doc_cd_setor";
  	static $nmAtrAnoDoc = "doc_ex";
  	static $nmAtrTpDoc = "doc_tp";
  	static $nmAtrSqDoc = "doc_sq";
  	    
	var $sq  = "";
	var $obs = "";
	
	var $voDoc;
	
    var $dbprocesso = "";

// ...............................................................
// Funcoes ( Propriedades e mÃ©todos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       $class = self::getNmClassProcesso();
       $this->dbprocesso= new $class();
              
       //retira os atributos padrao que nao possui
       //remove tambem os que o banco deve incluir default
        $arrayAtribRemover = array(
       		self::$nmAtrDhInclusao,        		
        	self::$nmAtrDhUltAlteracao,
       		self::$nmAtrCdUsuarioInclusao        	
       );
       $this->removeAtributos($arrayAtribRemover);
       $this->varAtributosARemover = $arrayAtribRemover;
              
   }
   
   public static function getTituloJSP(){
		return  "TRAMITAÇÃO";
   }
    
    public static function getNmTabela(){
        return  "tramitacao";
    }
    
    public static function getNmClassProcesso(){
        return  "dbTramitacao";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){
    	$retorno = array(
        	self::$nmAtrSq,
    		self::$nmAtrObservacao,
        	self::$nmAtrCdSetorDoc,            
        	self::$nmAtrAnoDoc,
    		self::$nmAtrTpDoc,
    		self::$nmAtrSqDoc
        );
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrSq
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade   	 
		//$this->sq = $registrobanco[self::$nmAtrSq];
		$this->obs = $registrobanco[self::$nmAtrObservacao];

		if($registrobanco[self::$nmAtrSqDoc] != null){
			$vodocumento = new voDocumento();
			$vodocumento->sq = $registrobanco[self::$nmAtrSqDoc];
			$vodocumento->cdSetor = $registrobanco[self::$nmAtrCdSetorDoc];
			$vodocumento->ano = $registrobanco[self::$nmAtrAnoDoc];
			$vodocumento->tp = $registrobanco[self::$nmAtrTpDoc];
				
			$this->voDoc = $vodocumento;
		}
		
		/*$this->cdSetorDoc = $registrobanco[self::$nmAtrCdSetorDoc];
		$this->anoDoc = $registrobanco[self::$nmAtrAnoDoc];
		$this->tpDoc= $registrobanco[self::$nmAtrTpDoc];
		$this->sqDoc = $registrobanco[self::$nmAtrSqDoc];*/
	}   
	
	function getDadosFormulario(){
		//$this->sq = @$_POST[self::$nmAtrSq];
		$this->obs = @$_POST[self::$nmAtrObservacao];
		
		if(@$_POST[voDocumento::getNmTabela()] != null){			
			$chaveDoc = @$_POST[voDocumento::getNmTabela()];			
			$vodocumento = new voDocumento();
			$vodocumento->getChavePrimariaVOExplodeParam($chaveDoc);			
			$this->voDoc = $vodocumento;
		}		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();	
	}
	                
	function toString(){						
		$retorno.= $this->sq . ",";
        $retorno.= $this->voDoc->cdSetor . ",";		
        $retorno.= $this->voDoc->ano. ",";
        $retorno.= $this->voDoc->tp. ",";
        $retorno.= $this->voDoc->sq;
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->sq;
	}
		
	function getChavePrimariaVOExplode($array){
		$this->sq = $array[0];
	}
	
	static function getAtributosOrdenacao(){
		$varAtributos = array(
				self::$nmAtrSq => "Número",
				self::$nmAtrCdSetor=> "Setor",
				self::$nmAtrAno => "Ano",
				self::$nmAtrTpDoc => "Tp.Doc"
		);
		return $varAtributos;
	}
}
?>