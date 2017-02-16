<?php
include_once(caminho_lib."voentidade.php");
include_once("dbOficio.php");


  Class voOficio extends voentidade{
  	  	 
  	static $nmAtrCdSetor = "ofic_cd_setor";
  	static $nmAtrAno = "ofic_ex";  	
  	static $nmAtrSq = "sq";
        
		var $sq  = "";		
		var $cdSetor = "";
		var $ano =  "";		
        
        var $dbprocesso = "";

// ...............................................................
// Funcoes ( Propriedades e mÃ©todos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       $this->dbprocesso= new dbOficio();
              
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
   
    public static function getNmTabela(){
        return  "oficio";
    }
    
    public function getNmClassProcesso(){
        return  "dbOficio";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrCdSetor . "=" . $this->cdSetor;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){    

    	$retorno = array(
        	self::$nmAtrSq,
        	self::$nmAtrCdSetor,            
        	self::$nmAtrAno
        );
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrSq,
    			self::$nmAtrCdSetor,
    			self::$nmAtrAno
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->cdSetor = $registrobanco[self::$nmAtrCdSetor];
		$this->ano = $registrobanco[self::$nmAtrAno];
	}   
	
	function getDadosFormulario(){
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->cdSetor = @$_POST[self::$nmAtrCdSetor];
		$this->ano = @$_POST[self::$nmAtrAno];
		        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
                
	function toString(){						
		$retorno.= $this->sq . ",";
        $retorno.= $this->cdSetor . ",";		
        $retorno.= $this->ano. ",";
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->sq 
				. CAMPO_SEPARADOR. $this->cdSetor
				. CAMPO_SEPARADOR. $this->ano;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->sq = $array[0];
		$this->cdSetor= $array[1];
		$this->ano = $array[2];
	}	
	
	static function getAtributosOrdenacao(){
		$varAtributos = array(
				self::$nmAtrSq => "Número",
				self::$nmAtrCdSetor=> "Setor",
				self::$nmAtrAno => "Ano"
		);
		return $varAtributos;
	}
}
?>