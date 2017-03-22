<?php
include_once("voDemanda.php");
include_once("vocontrato.php");

  Class voDemandaContrato extends voentidade{
    	   		  	
  	static $nmAtrAnoDemanda = "dem_ex";
  	static $nmAtrCdDemanda = "dem_cd";
  	static $nmAtrSqContrato = "ct_sq";  	  	  	 
  	   	    
  	var $sqContrato = "";
  	var $anoDemanda = "";
  	var $cdDemanda = "";
  	var $dbprocesso = "";
  	 
// ...............................................................
// Funcoes ( Propriedades e métodos da classe )

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
		return  "DEMANDA CONTRATO";
   }
    
    public static function getNmTabela(){
        return  "demanda_contrato";
    }
    
    public static function getNmClassProcesso(){
        return  "dbDemandaContrato";
    }      
    
    function getValoresWhereSQLChave($isHistorico){    	
    	$nmTabela = self::getNmTabelaStatic($isHistorico);
    	$query =  $nmTabela . "." . self::$nmAtrAnoDemanda . "=" . $this->anoDemanda;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrCdDemanda . "=" . $this->cdDemanda;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrSqContrato . "=" . $this->sqContrato;
    	 
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
        
    function getAtributosFilho(){
    	$retorno = array(
    			self::$nmAtrAnoDemanda,
    			self::$nmAtrCdDemanda,
    			self::$nmAtrSqContrato
    			);
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrAnoDemanda,
    			self::$nmAtrCdDemanda,
    			self::$nmAtrSqContrato
    			  );
    
    	return $retorno;
    }
            
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
    	$this->sqContrato = $registrobanco[self::$nmAtrSqContrato];
    	$this->cdDemanda = $registrobanco[self::$nmAtrCdDemanda];
    	$this->anoDemanda  = $registrobanco[self::$nmAtrAnoDemanda];
	}   
	
	function getDadosFormulario(){				
		$this->sqContrato = @$_POST[self::$nmAtrSqContrato];
		$this->cdDemanda = @$_POST[self::$nmAtrCdDemanda];
		$this->anoDemanda  = @$_POST[self::$nmAtrAnoDemanda];				
	}
	                
	function toString(){						
		$retorno.= $this->anoDemanda;
		$retorno.= "," . $this->cdDemanda;        
		$retorno.= "," . $this->sqContrato;
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->anoDemanda
				. CAMPO_SEPARADOR
				. $this->cdDemanda
				. CAMPO_SEPARADOR
				. $this->sqContrato;				
	}
		
	function getChavePrimariaVOExplode($array){
		$this->anoDemanda = $array[0];
		$this->cdDemanda = $array[1];
		$this->sqContrato = $array[2];
	}
	
}
?>