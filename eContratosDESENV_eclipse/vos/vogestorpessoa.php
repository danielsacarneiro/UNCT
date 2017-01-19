<?php
include_once(caminho_lib."voentidade.php");

  Class vogestorpessoa extends voentidade{
        //var $nmTable = "contrato_import";
		//para teste                
		static $nmAtrCd  = "gp_cd";
        static $nmAtrCdGestor  = "gt_cd";
		static $nmAtrNome= "gp_nome";		
		static $nmAtrDoc =  "gp_doc";
        static $nmAtrTel =  "gp_tel";
		static $nmAtrEmail =  "gp_email";
        
		var $cd  = "";
        var $cdGestor  = "";
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
        return  "gestor_pessoa";
    }
    
    public function getNmClassProcesso(){
        return  "dbgestorpessoa";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . vogestorpessoa::$nmAtrCd . "=" . $this->cd;
		//$query.= " AND ". $nmTabela . "." . vogestorpessoa::$nmAtrCd . "=" . $this->cd;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . vogestorpessoa::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){                
        $retorno = array(
            self::$nmAtrCd,            
            self::$nmAtrCdGestor,
            self::$nmAtrNome,
            self::$nmAtrDoc,
            self::$nmAtrTel,            
            self::$nmAtrEmail
        );
        
        return $retorno;    
    }
    
	function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco[vogestorpessoa::$nmAtrCd];
        $this->cdGestor = $registrobanco[vogestorpessoa::$nmAtrCdGestor];
		$this->nome = $registrobanco[vogestorpessoa::$nmAtrNome];
        $this->doc = $registrobanco[vogestorpessoa::$nmAtrDoc];
        $this->tel = $registrobanco[vogestorpessoa::$nmAtrTel];
        $this->email = $registrobanco[vogestorpessoa::$nmAtrEmail];        		
	}   
	
	function getDadosFormulario(){
		$this->cd = @$_POST[vogestorpessoa::$nmAtrCd];
        $this->cdGestor = @$_POST[vogestorpessoa::$nmAtrCdGestor];
		$this->nome = @$_POST[vogestorpessoa::$nmAtrNome];
        $this->email = @$_POST[vogestorpessoa::$nmAtrEmail];
        $this->doc = @$_POST[vogestorpessoa::$nmAtrDoc];
        $this->tel = @$_POST[vogestorpessoa::$nmAtrTel]; 
        
        $this->dhUltAlteracao = @$_POST[vogestorpessoa::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vogestorpessoa::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
                
	function toString(){		
		$retorno = $this->cdGestor . "";				
		$retorno.= $this->cd . ",";
        $retorno.= $this->nome . ",";		
		return $retorno;		
	}   

}
?>