<?php
include_once(caminho_lib."voentidade.php");
include_once("dbDocumento.php");
include_once(caminho_funcoes."documento/dominioTpDocumento.php");
include_once(caminho_funcoes."documento/biblioteca_htmlDocumento.php");
include_once(caminho_util."dominioSetor.php");


  Class voDocumento extends voentidade{
  		 
  	static $nmAtrCdSetor = "doc_cd_setor";
  	static $nmAtrAno = "doc_ex";  	
  	static $nmAtrSq = "sq";
  	static $nmAtrTp = "doc_tp";
  	static $nmAtrLink = "doc_link";
  	    
		var $sq  = "";		
		var $cdSetor = "";
		var $ano =  "";		
		var $tp =  "";
		var $link =  "";
        
        var $dbprocesso = "";

// ...............................................................
// Funcoes ( Propriedades e métodos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       $this->dbprocesso= new dbDocumento();
              
       //retira os atributos padrao que nao possui
       //remove tambem os que o banco deve incluir default
        $arrayAtribRemover = array(
       		self::$nmAtrDhInclusao,        		
        	self::$nmAtrDhUltAlteracao,
       		self::$nmAtrCdUsuarioInclusao        	
       );
       $this->removeAtributos($arrayAtribRemover);
       $this->varAtributosARemover = $arrayAtribRemover;
              
   }
   
   public static function getTituloJSP(){
		return  "DOCUMENTOS";
   }
    
    public static function getNmTabela(){
        return  "documento";
    }
    
    public static function getNmClassProcesso(){
        return  "dbDocumento";
    }      
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);        
		$query = $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrCdSetor . "=" . $this->cdSetor;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrAno . "=" . $this->ano;
		$query .= "\n AND " . $nmTabela . "." . self::$nmAtrTp. "='" . $this->tp . "'";
		
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }    
    
    function getAtributosFilho(){    

    	$retorno = array(
        	self::$nmAtrSq,
        	self::$nmAtrCdSetor,            
        	self::$nmAtrAno,
    		self::$nmAtrTp,
    		self::$nmAtrLink
        );
        
        return $retorno;    
    }
    
    function getAtributosChavePrimaria(){
    	$retorno = array(
    			self::$nmAtrSq,
    			self::$nmAtrCdSetor,
    			self::$nmAtrAno,
    			self::$nmAtrTp
    	);
    
    	return $retorno;
    }
        
    function getDadosRegistroBanco($registrobanco){
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->sq = $registrobanco[self::$nmAtrSq];
		$this->cdSetor = $registrobanco[self::$nmAtrCdSetor];
		$this->ano = $registrobanco[self::$nmAtrAno];
		$this->tp= $registrobanco[self::$nmAtrTp];
		$this->link= $registrobanco[self::$nmAtrLink];
	}   
	
	function getDadosFormulario(){
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->cdSetor = @$_POST[self::$nmAtrCdSetor];
		$this->ano = @$_POST[self::$nmAtrAno];
		$this->tp= @$_POST[self::$nmAtrTp];
		$this->link= $_POST[self::$nmAtrLink];
		        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
	
	function getEnderecoTpumento(){
		$retorno = "";
		
		//$domDoc = new dominioTpDocumento();
		$domSetor = new dominioSetor();
		$retorno = dominioTpDocumento::getEnderecoPastaBase();
		
		if($this->tp != null){
			
			if($this->tp == dominioTpDocumento::$CD_TP_DOC_NOTA_TECNICA){
				$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTA_TECNICA;
				$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTA_TECNICA . " $this->ano\\";
				
				$retorno.=$this->link;
				
			}else if($this->tp == dominioTpDocumento::$CD_TP_DOC_OFICIO){
				$retorno.= dominioTpDocumento::$ENDERECO_PASTA_OFICIO;
				$retorno.= dominioTpDocumento::$ENDERECO_PASTA_OFICIO . " " . $domSetor->getDescricao($this->cdSetor). " $this->ano\\";				
				
				$retorno.=$this->link;
			}else if($this->tp == dominioTpDocumento::$CD_TP_DOC_NOTIFICACAO){				
					$retorno.= dominioTpDocumento::$ENDERECO_PASTA_PA;					
					$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTIFICACAO;
					$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTIFICACAO . " $this->ano\\";
				
					$retorno.=$this->link;
			}else if($this->tp == dominioTpDocumento::$CD_TP_DOC_NOTA_IMPUTACAO){
						$retorno.= dominioTpDocumento::$ENDERECO_PASTA_PA;
						$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTAS_IMPUTACAO;
						$retorno.= dominioTpDocumento::$ENDERECO_PASTA_NOTAS_IMPUTACAO . " $this->ano\\";
					
						$retorno.=$this->link;
			}				
		}
		
		return $retorno;
	}
	
	function formatarCodigo(){	
		return self::formatarCodigoDocumento($this->sq, $this->cdSetor, $this->ano, $this->tp);
	}
	
	static function formatarCodigoDocumento($sq, $cdSetor, $ano, $tp){	
		
		/*$dominioSetor = new dominioSetor();
		
		$retorno = "";
		if($sq != null){
			$retorno = $tp
				. " " . complementarCharAEsquerda($sq, "0", TAMANHO_CODIGOS_SAFI)
				. "-" . substr($ano, 2, 2)
				. "/" . $dominioSetor->getDescricao($cdSetor);
		}
	
		return $retorno;*/
		return formatarCodigoDocumento($sq, $cdSetor, $ano, $tpDoc);
		
	}
                
	function toString(){						
		$retorno.= $this->sq . ",";
        $retorno.= $this->cdSetor . ",";		
        $retorno.= $this->ano. ",";
        $retorno.= $this->tp. ",";
        $retorno.= $this->link. ",";
		return $retorno;		
	}   
	
	function getValorChavePrimaria(){
		return $this->sq 
				. CAMPO_SEPARADOR. $this->cdSetor
				. CAMPO_SEPARADOR. $this->ano
				. CAMPO_SEPARADOR. $this->tp;
	}
		
	function getChavePrimariaVOExplode($array){
		$this->sq = $array[0];
		$this->cdSetor= $array[1];
		$this->ano = $array[2];
		$this->tp = $array[3];
	}
}
?>