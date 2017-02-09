<?php
include_once("dbpenalidade.php");

  Class vopenalidade extends voentidade{
  	
        static $nmAtrCd  = "pn_cd";
		static $nmAtrCdPA = "pa_cd"; //processo administrativo cd
		static $nmAtrAnoPA = "pa_ex"; //processo administrativo ano
		
		static $nmAtrCdContrato  = "ct_numero";
		static $nmAtrAnoContrato  = "ct_exercicio";
		static $nmAtrTipoContrato =  "ct_tipo";		
		
		static $nmAtrProcessoLicitatorio =  "pn_proc_licitatorio";
		static $nmAtrObservacao =  "pn_observacao";	
		
		var $dbprocesso;
        
		var $cd  = "";        
		var $cdPA= "";		
		var $anoPA =  "";
		var $cdContrato =  "";
		var $anoContrato =  "";
		var $tpContrato =  "";
        var $processoLic =  "";
        var $obs =  "";

// ...............................................................
// Funcoes ( Propriedades e metodos da classe )

   function __construct() {
       parent::__construct();
       $this->dbprocesso = new dbpenalidade();
       $this->temTabHistorico = true;
   }
   
    public static function getNmTabela(){
        return  "penalidade";
    }
    
    public function getNmClassProcesso(){
        return  "dbpenalidade";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;		
		$query.= " AND ". $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
		$query.= " AND ". $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){
        $retorno = array(
            self::$nmAtrCd,            
        	self::$nmAtrCdPA,            
            self::$nmAtrAnoPA,
            self::$nmAtrCdContrato,
            self::$nmAtrAnoContrato,
            self::$nmAtrTipoContrato,
            self::$nmAtrProcessoLicitatorio,            
            self::$nmAtrObservacao
        );
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrCd,
    			self::$nmAtrCdPA,
    			self::$nmAtrAnoPA
    	);
    
    	return $retorno;
    }
    
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco[self::$nmAtrCd];        
		$this->cdContrato = $registrobanco[self::$nmAtrCdContrato];
        $this->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
        $this->cdPA= $registrobanco[self::$nmAtrCdPA];
        $this->anoPA = $registrobanco[self::$nmAtrAnoPA];        		
        $this->processoLic = $registrobanco[self::$nmAtrProcessoLicitatorio];
        $this->obs = $registrobanco[self::$nmAtrObservacao];
	}   
	
	function getDadosFormulario(){
				
		$this->cd = @$_POST[self::$nmAtrCd];
		$this->cdPA = @$_POST[self::$nmAtrCdPA];        
		$this->anoPA = @$_POST[self::$nmAtrAnoPA];
		
        $this->cdContrato = @$_POST[self::$nmAtrCdContrato];
        $this->anoContrato = @$_POST[self::$nmAtrAnoContrato];
        $this->tpContrato = @$_POST[self::$nmAtrTipoContrato];
        
        $this->processoLic = @$_POST[self::$nmAtrProcessoLicitatorio];
        $this->obs = @$_POST[self::$nmAtrObservacao];
        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
                    
	}
                
	function toString(){						
		$retorno.= $this->cd . ",";
        $retorno.= $this->anoPA . ",";		
        $retorno.= $this->cdPA . ",";
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		$chave = $this->cd;
		$chave .= CAMPO_SEPARADOR.$this->anoPA;
		$chave .= CAMPO_SEPARADOR.$this->cdPA;
		
		return $chave;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->cd = $array[0];
		$this->anoPA = $array[1];
		$this->cdPA = $array[2];
	}
	
	static function getAtributosOrdenacao(){
		$varAtributos = array(
				self::getNmTabela().".".self::$nmAtrCdContrato => "Contrato",
				self::getNmTabela().".".self::$nmAtrCdPA=> "PA"
		);
		return $varAtributos;
	}	

}
?>