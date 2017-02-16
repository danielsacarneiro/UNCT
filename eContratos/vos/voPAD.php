<?php
include_once("dbPAD.php");

  Class voPAD extends voentidade{
  	
		static $nmAtrCdPA = "pad_cd"; //processo administrativo cd
		static $nmAtrAnoPA = "pad_ex"; //processo administrativo ano
		
		static $nmAtrCdContrato  = "ct_numero";
		static $nmAtrAnoContrato  = "ct_exercicio";
		static $nmAtrTipoContrato =  "ct_tipo";		
		
		static $nmAtrProcessoLicitatorio =  "pad_proc_licitatorio";
		static $nmAtrObservacao =  "pad_observacao";	
		static $nmAtrDtAbertura =  "pad_dt_abertura";
		static $nmAtrSituacao=  "pad_si";
		
		var $dbprocesso;
                
		var $cdPA= "";		
		var $anoPA =  "";
		var $cdContrato =  "";
		var $anoContrato =  "";
		var $tpContrato =  "";
        var $processoLic =  "";
        var $obs =  "";
        var $dtAbertura = "";
        var $situacao = "";

// ...............................................................
// Funcoes ( Propriedades e metodos da classe )

   function __construct() {
       parent::__construct();
       $this->dbprocesso = new dbPAD();
       $this->temTabHistorico = true;
   }
   
    public static function getNmTabela(){
        return  "pad";
    }
    
    public function getNmClassProcesso(){
        return  "dbPAD";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrAnoPA . "=" . $this->anoPA;
		$query.= " AND ". $nmTabela . "." . self::$nmAtrCdPA . "=" . $this->cdPA;
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){
        $retorno = array(            
        	self::$nmAtrCdPA,            
            self::$nmAtrAnoPA,
            self::$nmAtrCdContrato,
            self::$nmAtrAnoContrato,
            self::$nmAtrTipoContrato,
            self::$nmAtrProcessoLicitatorio,            
            self::$nmAtrObservacao,
        	self::$nmAtrDtAbertura,
        	self::$nmAtrSituacao
        );
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrCdPA,
    			self::$nmAtrAnoPA
    	);
    
    	return $retorno;
    }
    
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade        
		$this->cdContrato = $registrobanco[self::$nmAtrCdContrato];
        $this->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
        $this->cdPA= $registrobanco[self::$nmAtrCdPA];
        $this->anoPA = $registrobanco[self::$nmAtrAnoPA];        		
        $this->processoLic = $registrobanco[self::$nmAtrProcessoLicitatorio];
        $this->obs = $registrobanco[self::$nmAtrObservacao];
        $this->dtAbertura = getData($registrobanco[self::$nmAtrDtAbertura]);
        $this->situacao = $registrobanco[self::$nmAtrSituacao];
	}   
	
	function getDadosFormulario(){
		
		$this->cdPA = @$_POST[self::$nmAtrCdPA];        
		$this->anoPA = @$_POST[self::$nmAtrAnoPA];
		
        $this->cdContrato = @$_POST[self::$nmAtrCdContrato];
        $this->anoContrato = @$_POST[self::$nmAtrAnoContrato];
        $this->tpContrato = @$_POST[self::$nmAtrTipoContrato];
        
        $this->processoLic = @$_POST[self::$nmAtrProcessoLicitatorio];
        $this->obs = @$_POST[self::$nmAtrObservacao];
        $this->dtAbertura = @$_POST[self::$nmAtrDtAbertura];
        $this->situacao= @$_POST[self::$nmAtrSituacao];
        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
                    
	}
                
	function toString(){						
        $retorno.= $this->anoPA . ",";		
        $retorno.= $this->cdPA . ",";
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		$chave = $this->anoPA;
		$chave .= CAMPO_SEPARADOR.$this->cdPA;
		$chave .= CAMPO_SEPARADOR.$this->sqHist;
		
		return $chave;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->anoPA = $array[0];
		$this->cdPA = $array[1];
		$this->sqHist = $array[2];
	}
	
	static function getAtributosOrdenacao(){
		$varAtributos = array(
				self::$nmAtrCdContrato => "Contrato",
				self::$nmAtrCdPA=> "PA"
		);
		return $varAtributos;
	}	

}
?>