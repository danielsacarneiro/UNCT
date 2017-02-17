<?php
include_once("dbPA.php");

  Class voPA extends voentidade{
  	
  		static $nmAtrColecaoTramitacao =  "ColecaoTramitacao";
		static $nmAtrCdPA = "pa_cd"; //processo administrativo cd
		static $nmAtrAnoPA = "pa_ex"; //processo administrativo ano
		
		static $nmAtrCdContrato  = "ct_numero";
		static $nmAtrAnoContrato  = "ct_exercicio";
		static $nmAtrTipoContrato =  "ct_tipo";		
		
		static $nmAtrCdResponsavel=  "pa_cd_responsavel";
		static $nmAtrProcessoLicitatorio =  "pa_proc_licitatorio";
		static $nmAtrObservacao =  "pa_observacao";	
		static $nmAtrDtAbertura =  "pa_dt_abertura";
		static $nmAtrSituacao=  "pa_si";
		
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
        var $cdResponsavel = "";
        
        var $colecaoTramitacao;

// ...............................................................
// Funcoes ( Propriedades e metodos da classe )

   function __construct() {
       parent::__construct();
       $this->dbprocesso = new dbPA();
       $this->temTabHistorico = true;
   }
   
    public static function getNmTabela(){
        return  "pa";
    }
    
    public static function getNmClassProcesso(){
        return  "dbPA";
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
        	self::$nmAtrSituacao,
        	self::$nmAtrCdResponsavel
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
    
    function setColecaoTramitacao($colecaoRegistroBanco){
    	    	
    	if($colecaoRegistroBanco != ""){
    		
    		$tam = count($colecaoRegistroBanco);
    		
    		$retorno = "";
    		for($i=0;$i<$tam;$i++){
    			$voTram = new voPADTramitacao();
    			$voAtual = $colecaoRegistroBanco[$i];
    			
    			$voTram->getDadosBanco($voAtual);    			
    			$retorno[$i] = $voTram;
    			
    		}
    		
    		$this->colecaoTramitacao = $retorno;
    	}
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
        $this->cdResponsavel = $registrobanco[self::$nmAtrCdResponsavel];
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
        $this->cdResponsavel = @$_POST[self::$nmAtrCdResponsavel];
        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
                    
	}
	
	function montarColecaoTramitacao($colecao){		
		if($colecao != ""){
			$tamanho = sizeof($colecao);
			
			$retorno = array();
			for ($i=0;$i<$tamanho;$i++) {
				$vo = new voPADTramitacao();
				$vo->getDadosBanco($colecao[$i]);				
				$retorno[$i] = $vo;
			}
			
			$this->colecaoTramitacao = $retorno;
		}
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
				self::$nmAtrCdPA=> "PA",
				self::$nmAtrCdContrato => "Contrato"
		);
		return $varAtributos;
	}	

}
?>