<?php
include_once("voTramitacao.php");
include_once("voContrato.php");
include_once("dbContratoTramitacao.php");

  Class voContratoTramitacao extends voTramitacao{
  	 
  	static $nmAtrCdContrato  = "ct_numero";
  	static $nmAtrAnoContrato  = "ct_exercicio";
  	static $nmAtrTipoContrato =  "ct_tipo";  	
  	static $nmAtrSqIndice =  "sq_indice";
  	    
	var $cdContrato;
	var $anoContrato;
	var $tipoContrato;
	var $sqIndice;

// ...............................................................
// Funcoes ( Propriedades e mÃ©todos da classe )

   function __construct() {
       parent::__construct();
       $class = self::getNmClassProcesso();       
       $this->dbprocesso= new $class();              
       //retira os atributos padrao que nao possui
       //remove tambem os que o banco deve incluir default
        $arrayAtribRemover = array(
       		self::$nmAtrDhInclusao,        		
        	self::$nmAtrDhUltAlteracao,
       		self::$nmAtrCdUsuarioInclusao,        	
        	self::$nmAtrCdUsuarioUltAlteracao
       );
       $this->removeAtributos($arrayAtribRemover);
       $this->varAtributosARemover = $arrayAtribRemover;  
       
       //spl_autoload_register(array($this, 'loader'));
   }
   
   private static function loader($class){
   		include_once $class . '.php';
   }
   
   public static function getTituloJSP(){
		return  "TRAMITAÇÃO CONTRATO";
   }
    
    public static function getNmTabela(){
        return  "contrato_tram";
    }
    
    public static function getNmClassProcesso(){
        return  "dbContratoTramitacao";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->cdContrato;
		$query.= " AND ". $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->anoContrato;
		$query.= " AND ". $nmTabela . "." . self::$nmAtrTipoContrato . "='" . $this->tipoContrato . "'";
		$query.= " AND ". $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getValoresWhereSQLChaveLogicaSemSQ($isHistorico){
    	$nmTabela = $this->getNmTabelaEntidade($isHistorico);
    	$query = $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->cdContrato;
    	$query.= " AND ". $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->anoContrato;
    	$query.= " AND ". $nmTabela . "." . self::$nmAtrTipoContrato . "='" . $this->tipoContrato . "'";
    
    	if($isHistorico)
    		$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
    
    		return $query;
    }
    
    function getAtributosFilho(){
    	//metodo da classe filha
    	$retorno = array(
    			self::$nmAtrCdContrato,
    			self::$nmAtrAnoContrato,
    			self::$nmAtrTipoContrato,
    			self::$nmAtrSq,
    			self::$nmAtrSqIndice
    	);
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrCdContrato,
    			self::$nmAtrAnoContrato,
    			self::$nmAtrTipoContrato,
    			self::$nmAtrSq
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
    	parent::getDadosRegistroBanco($registrobanco);
    	
		$this->cdContrato = $registrobanco[self::$nmAtrCdContrato];
		$this->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
		$this->tipoContrato= $registrobanco[self::$nmAtrTipoContrato];
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->sqIndice = $registrobanco[self::$nmAtrSqIndice];
		$this->nmUsuarioUltAlteracao = $registrobanco[self::$nmAtrNmUsuarioUltAlteracao];
	}   
	
	function getDadosFormulario(){		
		parent::getDadosFormulario();		

		$this->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->anoContrato = @$_POST[self::$nmAtrAnoContrato];
		$this->tipoContrato= @$_POST[self::$nmAtrTipoContrato];
		$this->sq= @$_POST[self::$nmAtrSq];
		$this->sqIndice= @$_POST[self::$nmAtrSqIndice];
	}
	  
	//para o caso da classe herdar de alguem
	function getVOPai(){		
		$voTramitacao = new voTramitacao();
		$voTramitacao->sq = $this->sq;
		$voTramitacao->obs = $this->obs;
		$voTramitacao->dtReferencia = $this->dtReferencia;
		$voTramitacao->voDoc = $this->voDoc;
				
		return $voTramitacao;
	}
	
	function toString(){
		$retorno = "";
		$retorno.= $this->anoContrato . ",";
		$retorno.= $this->tipoContrato . ",";
		$retorno.= $this->cdContrato . ",";
        $retorno.= $this->sq . ",";
        $retorno.= $this->sqIndice . ",";
        $retorno.= $this->obs;

        return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->anoContrato
		. CAMPO_SEPARADOR. $this->tipoContrato
		. CAMPO_SEPARADOR. $this->cdContrato
		. CAMPO_SEPARADOR. $this->sq;
	}
		
	function getChavePrimariaVOExplode($array){
		$this->anoContrato = $array[0];
		$this->tipoContrato= $array[1];
		$this->cdContrato = $array[2];		
		$this->sq = $array[3];
	}
}
?>