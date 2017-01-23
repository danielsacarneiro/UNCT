<?php
include_once(caminho_lib."voentidade.php");

  Class vogestor extends voentidade{
        //var $nmTable = "contrato_import";
		//para teste                
		static $nmAtrCd  = "gt_cd";
		static $nmAtrDescricao= "gt_descricao";

		var $cd  = "";
		var $descricao= "";		

// ...............................................................
// Funções ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
   }
   
    public static function getNmTabela(){
        return  "gestor";
    }  
    
    public function getNmClassProcesso(){
        return  "dbgestor";
    }  

    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . vogestor::$nmAtrCd . "=" . $this->cd;
		//$query.= " AND ". $nmTabela . "." . vogestor::$nmAtrCd . "=" . $this->cd;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . vogestor::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){                
        $retorno = array(
            vogestor::$nmAtrCd,            
            vogestor::$nmAtrDescricao
        );
        
        return $retorno;    
    }
    
	function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco[vogestor::$nmAtrCd];
		$this->descricao = $registrobanco[vogestor::$nmAtrDescricao];
	}   
	
	function getDadosFormulario(){
		$this->cd = @$_POST[vogestor::$nmAtrCd];
		$this->descricao = @$_POST[vogestor::$nmAtrDescricao];

        $this->dhUltAlteracao = @$_POST[vogestor::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vogestor::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
	
	function getValorChavePrimaria(){
		return $this->cd;
	}	
                
	function toString(){		
		$retorno.= $this->cd . ",";
        $retorno.= $this->descricao . ",";		
		return $retorno;		
	}   

}
?>