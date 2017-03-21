<?php
include_once("voDemanda.php");

  Class voDemandaTramitacao extends voDemanda{
    	   		  	
  	static $nmAtrSq = "sq";
  	static $nmAtrTexto = "dtm_texto";
  	static $nmAtrProtocolo = "dtm_prt";
  	
  	static $nmAtrCdSetorOrigem = "dtm_cd_setor_origem";
  	static $nmAtrCdSetorDestino = "dtm_cd_setor_destino";
  	   	    
  	var $sq = "";
  	var $cdSetorOrigem = "";
  	var $cdSetorDestino = "";
  	var $textoTram  = "";
  	var $prt = "";
  	 
// ...............................................................
// Funcoes ( Propriedades e mÃ©todos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = true;
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
		return  "DEMANDA TRAMITAÇÃO";
   }
    
    public static function getNmTabela(){
        return  "demanda_tram";
    }
    
    public static function getNmClassProcesso(){
        return  "dbDemandaTramitacao";
    }      
    
    function getValoresWhereSQLChave($isHistorico){    	
    	$query = $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;		
        
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getValoresWhereSQLChaveLogicaSemSQ($isHistorico){
    	$nmTabela = self::getNmTabelaStatic($isHistorico);
    	$query =  $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
    	$query .= " AND " . $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;    	 
        
    	return $query;
    }
    
    function getAtributosFilho(){
    	$retorno = array(
    			self::$nmAtrAno,
    			self::$nmAtrCd,
    			self::$nmAtrSq,
    			self::$nmAtrCdSetorOrigem,
    			self::$nmAtrCdSetorDestino,
    			self::$nmAtrTexto,
    			self::$nmAtrProtocolo
    			);
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrAno,
    			self::$nmAtrCd,
    			self::$nmAtrSq
    	);
    
    	return $retorno;
    }
        
    function temTramitacaoParaIncluir(){
    	$retorno = $this->cdSetorDestino != null && $this->textoTram != null;   	
    	return $retorno;     	
    }
    
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
    	$this->sq = $registrobanco[self::$nmAtrSq];
    	$this->cd = $registrobanco[self::$nmAtrCd];
    	$this->ano  = $registrobanco[self::$nmAtrAno];
    	$this->cdSetorOrigem = $registrobanco[self::$nmAtrCdSetorOrigem];
    	$this->cdSetorDestino = $registrobanco[self::$nmAtrCdSetorDestino];
    	$this->textoTram  = $registrobanco[self::$nmAtrTexto];
    	$this->prt = $registrobanco[self::$nmAtrProtocolo];
	}   
	
	function getDadosFormulario(){
		parent::getDadosFormulario();
				
		$this->cdSetorOrigem = @$_POST[self::$nmAtrCdSetorOrigem];
		
		if($this->cdSetorOrigem == null){
			//se for vazio, eh pq o setor origem eh da demanda-pai
			$this->cdSetorOrigem = $this->cdSetor;
		}
		
		$this->cdSetorDestino = @$_POST[self::$nmAtrCdSetorDestino];		
		$this->textoTram  = @$_POST[self::$nmAtrTexto];
		$this->texto = @$_POST[parent::$nmAtrTexto];
		$this->prt = @$_POST[self::$nmAtrProtocolo];		
	}
	
	//para o caso da classe herdar de alguem
	function getVOPai(){
		$voDemanda = new voDemanda();
		$voDemanda->ano = $this->ano;
		$voDemanda->cd = $this->cd;
		$voDemanda->cdSetor = $this->cdSetor;
		$voDemanda->tipo  = $this->tipo;
		$voDemanda->situacao  = $this->situacao;		
		$voDemanda->texto  = $this->texto;
	
		return $voDemanda;
	}
	                
	function toString(){						
		$retorno.= $this->ano;
		$retorno.= "," . $this->cd;        
		$retorno.= "," . $this->sq;
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->ano
				. CAMPO_SEPARADOR
				. $this->cd
				. CAMPO_SEPARADOR
				. $this->sq;				
	}
		
	function getChavePrimariaVOExplode($array){
		$this->ano = $array[0];
		$this->cd = $array[1];
		$this->sq = $array[2];
	}
	
}
?>