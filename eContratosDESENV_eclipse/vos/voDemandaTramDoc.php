<?php
include_once("voDemandaTramitacao.php");
include_once("voDocumento.php");

  Class voDemandaTramDoc extends voentidade{
  	      	   		  	
  	static $nmAtrAnoDemanda = "dem_ex";
  	static $nmAtrCdDemanda = "dem_cd";
  	static $nmAtrSqDemandaTram = "dtm_sq";  	  	  	 
  	   	    
  	static $nmAtrCdSetorDoc = "doc_cd_setor";
  	static $nmAtrAnoDoc = "doc_ex";
  	static $nmAtrSqDoc = "doc_sq";
  	static $nmAtrTpDoc = "doc_tp";
  	 
  	var $sqDemandaTram = "";
  	var $anoDemanda = "";
  	var $cdDemanda = "";
  	
  	var $cdSetorDoc = "";
  	var $anoDoc = "";
  	var $sqDoc = "";
  	var $tpDoc = "";
  	 
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
       		self::$nmAtrCdUsuarioUltAlteracao,
       );
       $this->removeAtributos($arrayAtribRemover);
       $this->varAtributosARemover = $arrayAtribRemover;
   }
   
   public static function getTituloJSP(){
		return  "DEMANDA TRAMITAÇÃO DOCUMENTO";
   }
    
    public static function getNmTabela(){
        return  "demanda_doc";
    }
    
    public static function getNmClassProcesso(){
        return  "dbDemandaTramDoc";
    }      
    
    function getValoresWhereSQLChave($isHistorico){  	
    	$nmTabela = self::getNmTabelaStatic($isHistorico);
    	$query =  $nmTabela . "." . self::$nmAtrAnoDemanda . "=" . $this->anoDemanda;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrCdDemanda . "=" . $this->cdDemanda;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrSqDemandaTram . "=" . $this->sqDemandaTram;

    	$query .= " AND " . $nmTabela . "." . self::$nmAtrAnoDoc. "=" . $this->anoDoc;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrCdSetorDoc. "=" . $this->cdSetorDoc;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrTpDoc. "=" . getVarComoString($this->tpDoc);
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrSqDoc . "=" . $this->sqDoc;
    	 
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
        
    function getAtributosFilho(){    	 
    	$retorno = array(
    			self::$nmAtrAnoDemanda,
    			self::$nmAtrCdDemanda,
    			self::$nmAtrSqDemandaTram,
    			self::$nmAtrAnoDoc,
    			self::$nmAtrCdSetorDoc,
    			self::$nmAtrTpDoc,
    			self::$nmAtrSqDoc    			 
    			);        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	return self::getAtributosFilho();
    }
            
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade    	
    	$this->sqDemandaTram = $registrobanco[self::$nmAtrSqDemandaTram];
    	$this->cdDemanda = $registrobanco[self::$nmAtrCdDemanda];
    	$this->anoDemanda  = $registrobanco[self::$nmAtrAnoDemanda];
    	
    	$this->anoDoc = $registrobanco[self::$nmAtrAnoDoc];
    	$this->cdSetorDoc= $registrobanco[self::$nmAtrCdSetorDoc];
    	$this->tpDoc= $registrobanco[self::$nmAtrTpDoc];
    	$this->sqDoc  = $registrobanco[self::$nmAtrSqDoc];    	 
	}   
	
	function getDadosFormulario(){
		$this->sqDemandaTram = @$_POST[self::$nmAtrSqDemandaTram];
		$this->cdDemanda = @$_POST[self::$nmAtrCdDemanda];
		$this->anoDemanda  = @$_POST[self::$nmAtrAnoDemanda];
		 
		$this->anoDoc = @$_POST[self::$nmAtrAnoDoc];
		$this->cdSetorDoc= @$_POST[self::$nmAtrCdSetorDoc];
		$this->tpDoc= @$_POST[self::$nmAtrTpDoc];
		$this->sqDoc  = @$_POST[self::$nmAtrSqDoc];		
	}
	                
	function toString(){						
		$retorno.= $this->anoDemanda;
		$retorno.= "," . $this->cdDemanda;        
		$retorno.= "," . $this->sqDemandaTram;
		$retorno.= "," . $this->anoDoc;
		$retorno.= "," . $this->cdSetorDoc;
		$retorno.= "," . $this->tpDoc;
		$retorno.= "," . $this->sqDoc;
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->anoDemanda
				. CAMPO_SEPARADOR
				. $this->cdDemanda
				. CAMPO_SEPARADOR
				. $this->sqDemandaTram
				. CAMPO_SEPARADOR
				. $this->anoDoc
				. CAMPO_SEPARADOR
				. $this->cdSetorDoc
				. CAMPO_SEPARADOR
				. $this->tpDoc
				. CAMPO_SEPARADOR
				. $this->sqDoc;
	}
		
	function getChavePrimariaVOExplode($array){
		$this->anoDemanda = $array[0];
		$this->cdDemanda = $array[1];
		$this->sqDemandaTram = $array[2];
		$this->anoDoc= $array[3];
		$this->cdSetorDoc = $array[4];
		$this->tpDoc = $array[5];
		$this->sqDoc = $array[6];		
	}
	
}
?>