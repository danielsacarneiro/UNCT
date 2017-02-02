<?php
include_once(caminho_lib."voentidade.php");

  Class vopessoavinculo extends voentidade{
		static $nmAtrCd  = "vi_cd";
		static $nmAtrCdPessoa  = "pe_cd";		
        
		var $cd  = "";		
        var $cdPessoa  = "";

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
        return  "pessoa_vinculo";
    }
    
    public function getNmClassProcesso(){
        return  "dbpessoavinculo";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrCdPessoa . "=" . $this->cdPessoa;
		//$query.= " AND ". $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $this->cd;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){                
        $retorno = array(
            self::$nmAtrCd,            
        	self::$nmAtrCdPessoa
        );
        
        return $retorno;    
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco[self::$nmAtrCd];
		$this->cdPessoa = $registrobanco[self::$nmAtrCdPessoa];
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
		$retorno.= $this->cd . ",";
        $retorno.= $this->cdPessoa . ",";		
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->cd . CAMPO_SEPARADOR . $this->cdPessoa;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->cd = $array[0];
		$this->cdPessoa = $array[1];
	}	

}
?>