<?php
include_once(caminho_lib."voentidade.php");
include_once("dbPATramitacao.php");
include_once("vodocumento.php");


  Class voPATramitacao extends voentidade{
  	  	 
  	static $nmAtrCdPA = "pa_cd"; //processo administrativo cd
  	static $nmAtrAnoPA = "pa_ex"; //processo administrativo ano  	
  	static $nmAtrSq = "sq";
  	static $nmAtrObservacao  = "patr_observacao";
  	
  	static $nmAtrCdSetorDoc = "ofic_cd_setor";
  	static $nmAtrAnoDoc = "ofic_ex";
  	static $nmAtrSqDoc = "ofic_sq";
  	static $nmAtrTpDoc = "ofic_tp_doc";
        
		var $sq  = "";		
		var $cdPA= "";
		var $anoPA =  "";		
        var $obs = "";
        
        var $voDoc;
        
        /*var $sqDoc  = "";
        var $cdSetorDoc = "";
        var $anoDoc =  "";
        var $tpDoc =  "";*/
        
        var $dbprocesso= "";

// ...............................................................
// Funcoes ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       $this->voDoc = null;
       $this->dbprocesso = new dbPATramitacao();
              
       //retira os atributos padrao que nao possui
       //remove tambem os que o banco deve incluir default
        $arrayAtribRemover = array(
       		self::$nmAtrDhInclusao,        		
        	self::$nmAtrDhUltAlteracao,
       		self::$nmAtrCdUsuarioInclusao        	
       );
       $this->removeAtributos($arrayAtribRemover);
              
   }
   
    public static function getNmTabela(){
        return  "pa_tramitacao";
    }
    
    public function getNmClassProcesso(){
        return  "dbPATramitacao";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){    

    	$retorno = array(
        	self::$nmAtrSq,
        	self::$nmAtrCdPA,            
        	self::$nmAtrAnoPA,
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
    			self::$nmAtrSq,
    			self::$nmAtrCdPA,
    			self::$nmAtrAnoPA
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->cdPA = $registrobanco[self::$nmAtrCdPA];
		$this->anoPA = $registrobanco[self::$nmAtrAnoPA];
		$this->obs = $registrobanco[self::$nmAtrObservacao];
		
		//echo "valor sqdoc" . $registrobanco[self::$nmAtrSqDoc];
		
		if($registrobanco[self::$nmAtrSqDoc] != null){
			$vodocumento = new voDocumento();
			$vodocumento->sq = $registrobanco[self::$nmAtrSqDoc];
			$vodocumento->cdSetor = $registrobanco[self::$nmAtrCdSetorDoc];
			$vodocumento->ano = $registrobanco[self::$nmAtrAnoDoc];
			$vodocumento->tpDoc = $registrobanco[self::$nmAtrTpDoc];
			
			$this->voDoc = $vodocumento; 
		}
		
	}   
	
	/*function getDadosFormulario(){
		$this->cd = @$_POST[self::$nmAtrCd];
		$this->id = @$_POST[self::$nmAtrIdUser];
        $this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
        $this->email = @$_POST[vopessoa::$nmAtrEmail];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->tel = @$_POST[vopessoa::$nmAtrTel]; 
        $this->endereco = @$_POST[vopessoa::$nmAtrEndereco];
        
        $this->dhUltAlteracao = @$_POST[vopessoa::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vopessoa::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}*/
                
	function toString(){						
		$retorno.= $this->sq . ",";
        $retorno.= $this->cdPA . ",";		
        $retorno.= $this->anoPA. ",";
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->sq . CAMPO_SEPARADOR . $this->cdPA. CAMPO_SEPARADOR . $this->anoPA;
	}
	
	function getVOExplodeChavePrimaria($array){
		$this->sq = $array[0];
		$this->cdPA= $array[1];
		$this->anoPA = $array[2];
	}	

}
?>