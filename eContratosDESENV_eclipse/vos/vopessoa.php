<?php
include_once(caminho_vos."vopessoavinculo.php");
include_once(caminho_vos."vogestor.php");

  Class vopessoa extends voentidade{
        //var $nmTable = "contrato_import";
		//para teste                
		static $nmAtrCd  = "pe_cd";
		static $nmAtrIdUser  = "ID";
		static $nmAtrNome= "pe_nome";		
		static $nmAtrDoc =  "pe_doc";
        static $nmAtrTel =  "pe_tel";
		static $nmAtrEmail =  "pe_email";
		static $nmAtrEndereco =  "pe_endereco";
		
        
		var $cd  = "";        
		var $nome= "";		
		var $doc =  "";
		var $email =  "";
        var $tel =  "";

// ...............................................................
// Funções ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
   }
   
    public static function getNmTabela(){
        return  "pessoa";
    }
    
    public function getNmClassProcesso(){
        return  "dbpessoa";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $this->cd;
		//$query.= " AND ". $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $this->cd;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . vopessoa::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){                
        $retorno = array(
            self::$nmAtrCd,            
        	self::$nmAtrIdUser,            
            self::$nmAtrNome,
            self::$nmAtrDoc,
            self::$nmAtrTel,            
            self::$nmAtrEmail,
        	self::$nmAtrEndereco
        );
        
        return $retorno;    
    }
    
	function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco[vopessoa::$nmAtrCd];
		$this->id = $registrobanco[vopessoa::$nmAtrIdUser];
        
		$this->nome = $registrobanco[vopessoa::$nmAtrNome];
        $this->doc = $registrobanco[vopessoa::$nmAtrDoc];
        $this->tel = $registrobanco[vopessoa::$nmAtrTel];
        $this->email = $registrobanco[vopessoa::$nmAtrEmail];        		
        $this->endereco = $registrobanco[vopessoa::$nmAtrEndereco];
	}   
	
	function getDadosFormulario(){
		$this->cd = @$_POST[vopessoa::$nmAtrCd];
		$this->id = @$_POST[vopessoa::$nmAtrIdUser];
        
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
        $this->email = @$_POST[vopessoa::$nmAtrEmail];
        $this->doc = @$_POST[vopessoa::$nmAtrDoc];
        $this->tel = @$_POST[vopessoa::$nmAtrTel]; 
        $this->endereco = @$_POST[vopessoa::$nmAtrEndereco];
        
        $this->dhUltAlteracao = @$_POST[vopessoa::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vopessoa::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
        
        //vinculo
        $this->cdVinculo = @$_POST[vopessoavinculo::$nmAtrCd];
        $this->cdGestor = @$_POST[vogestor::$nmAtrCd];
            
	}
                
	function toString(){						
		$retorno.= $this->cd . ",";
        $retorno.= $this->nome . ",";		
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->cd;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];	
		$array = explode(CAMPO_SEPARADOR,$chave);
		$this->cd = $array[0];
	}
	
	static function getAtributosOrdenacao(){
		$varAtributos = array(
				self::$nmAtrNome => "Nome"
		);
		return $varAtributos;
	}	

}
?>