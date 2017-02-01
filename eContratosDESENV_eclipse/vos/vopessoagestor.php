<?php
include_once(caminho_lib."voentidade.php");

  Class vopessoagestor extends voentidade{
        //var $nmTable = "contrato_import";
		//para teste                
		static $nmAtrCdPessoa  = "pe_cd";
        static $nmAtrCdGestor  = "gt_cd";
        
		var $cdPessoa  = "";
        var $cdGestor  = "";

// ...............................................................
// Funcoes ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       
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
        return  "pessoa_gestor";
    }
    
    public function getNmClassProcesso(){
        return  "dbpessoagestor";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . vopessoagestor::$nmAtrCdPessoa . "=" . $this->cdPessoa;
		$query.= " AND ". $nmTabela . "." . vopessoagestor::$nmAtrCdGestor . "=" . $this->cdGestor;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . vopessoagestor::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){                
        $retorno = array(
            self::$nmAtrCdPessoa,            
            self::$nmAtrCdGestor
        );
        
        return $retorno;    
    }
    
	function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPessoa = $registrobanco[vopessoagestor::$nmAtrCdPessoa];
        $this->cdGestor = $registrobanco[vopessoagestor::$nmAtrCdGestor];
	}   
	
	function getDadosFormulario(){
		$this->cdPessoa = @$_POST[vopessoagestor::$nmAtrCdPessoa];
        $this->cdGestor = @$_POST[vopessoagestor::$nmAtrCdGestor];
        
        $this->dhUltAlteracao = @$_POST[vopessoagestor::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vopessoagestor::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
                
	function toString(){		
		$retorno = $this->cdPessoa . "";				
		$retorno.= $this->cdGestor;		
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->cdPessoa . CAMPO_SEPARADOR . $this->cdGestor;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->cdPessoa = $array[0];
		$this->cdGestor = $array[1];
	}	

}
?>