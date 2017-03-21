<?php
include_once(caminho_lib."voentidade.php");
include_once("dbDemanda.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."dominioSetor.php");
include_once(caminho_funcoes. "demanda/dominioSituacaoDemanda.php");
include_once(caminho_funcoes. "demanda/dominioTipoDemanda.php");

  Class voDemanda extends voentidade{
    	   		 
  	static $nmAtrCd = "dem_cd";
  	static $nmAtrAno = "dem_ex";  	
  	static $nmAtrCdSetor = "dem_cd_setor";
  	static $nmAtrTipo = "dem_tipo";  	
  	static $nmAtrSituacao = "dem_situacao";
  	static $nmAtrTexto = "dem_texto";
  	   	    
  	var $cd = "";
  	var $ano  = "";  	
  	var $cdSetor  = "";
  	var $tipo = ""; 	
  	var $situacao  = "";
  	var $texto = "";
  	 
// ...............................................................
// Funcoes ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = true;
       $class = self::getNmClassProcesso();
       $this->dbprocesso= new $class();
              
       //retira os atributos padrao que nao possui
       //remove tambem os que o banco deve incluir default
       $arrayAtribRemover = array(
       		self::$nmAtrDhInclusao,
       		self::$nmAtrDhUltAlteracao
       );
       $this->removeAtributos($arrayAtribRemover);
       $this->varAtributosARemover = $arrayAtribRemover;
   }
   
   public static function getTituloJSP(){
		return  "DEMANDA";
   }
    
    public static function getNmTabela(){
        return  "demanda";
    }
    
    public static function getNmClassProcesso(){
        return  "dbDemanda";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
    	$nmTabela = $this->getNmTabelaEntidade($isHistorico);
		$query =  $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getValoresWhereSQLChaveLogicaSemSQ($isHistorico){
    	$nmTabela = $this->getNmTabelaEntidade($isHistorico);
    	$query = $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;    
        
    	return $query;
    }
    
    function getAtributosFilho(){
    	$retorno = array(
    			self::$nmAtrAno,
    			self::$nmAtrCd,
    			self::$nmAtrTipo,
    			self::$nmAtrCdSetor,    			
    			self::$nmAtrSituacao,
    			self::$nmAtrTexto
    			);
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrAno,
    			self::$nmAtrCd    			 
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
    	$this->cd = $registrobanco[self::$nmAtrCd];
    	$this->ano  = $registrobanco[self::$nmAtrAno];
    	$this->cdSetor = $registrobanco[self::$nmAtrCdSetor];
    	$this->tipo  = $registrobanco[self::$nmAtrTipo];
    	$this->situacao  = $registrobanco[self::$nmAtrSituacao];
    	$this->texto = $registrobanco[self::$nmAtrTexto];
	}   
	
	function getDadosFormulario(){
		//constante definida em bibliotecahtml
		$this->cd  = @$_POST[self::$nmAtrCd];
		$this->ano  = @$_POST[self::$nmAtrAno];
		$this->cdSetor = @$_POST[self::$nmAtrCdSetor];
		$this->tipo = @$_POST[self::$nmAtrTipo];
		$this->situacao  = @$_POST[self::$nmAtrSituacao];
		$this->texto = @$_POST[self::$nmAtrTexto];
		
		//completa com os dados da entidade
		$this->getDadosFormularioEntidade();	
	}
	                
	function toString(){						
		$retorno.= $this->ano;
		$retorno.= "," . $this->cd;        
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->ano
				. CAMPO_SEPARADOR
				. $this->cd;
	}
		
	function getChavePrimariaVOExplode($array){
		$this->ano = $array[0];
		$this->cd = $array[1];
	}
	
}
?>