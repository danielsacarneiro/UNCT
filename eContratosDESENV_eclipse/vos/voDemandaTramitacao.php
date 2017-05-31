<?php
include_once("voDemanda.php");
include_once("voDemandaContrato.php");

  Class voDemandaTramitacao extends voDemanda{
    	   		  	
  	static $nmAtrInResponsabilidadePRT = "nmAtrInResponsabilidadePRT";
  	
  	static $nmAtrSq = "dtm_sq";
  	static $nmAtrTexto = "dtm_texto";
  	static $nmAtrProtocolo = "dtm_prt";
  	
  	static $nmAtrCdSetorOrigem = "dtm_cd_setor_origem";
  	static $nmAtrCdSetorDestino = "dtm_cd_setor_destino";
  	static $nmAtrDtReferencia = "dtm_dtreferencia";
  	   	    
  	var $sq = "";
  	var $cdSetorOrigem = "";
  	var $cdSetorDestino = "";
  	var $textoTram  = "";
  	var $prt = "";
  	
  	var $voDoc = null;  	
  	 
// ...............................................................
// Funcoes ( Propriedades e mÃ©todos da classe )

   function __construct() {
       parent::__construct();
       $this->temTabHistorico = false;
       $class = self::getNmClassProcesso();
       $this->dbprocesso= new $class();
       $this->voDoc = new voDocumento();      
              
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
    
    //GAMBIARRA NAO REPETIR
    function getTelaRetornoConfirmar(){
    	//REGRA: retorna para a tela da demanda
    	$telaConsultaRetorno = voDemanda::getNmTabela();
    	
    	if ($this->NM_METODO_RETORNO_CONFIRMAR != null && $this->NM_METODO_RETORNO_CONFIRMAR != ""){
    		$telaConsultaRetorno = $this->NM_METODO_RETORNO_CONFIRMAR; 
    	}
    		
    	return  $telaConsultaRetorno;
    }
    
    function getValoresWhereSQLChave($isHistorico){    	
    	$nmTabela = self::getNmTabelaStatic($isHistorico);
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
    			self::$nmAtrProtocolo,
    			self::$nmAtrDtReferencia
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
    
    function temDocParaIncluir(){
    	$retorno = $this->voDoc != null && $this->voDoc->sq != null;
    	return $retorno;
    }
    
    function getDadosRegistroBanco($registrobanco){
    	parent::getDadosRegistroBanco($registrobanco);
    	
        //as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
    	$this->sq = $registrobanco[self::$nmAtrSq];
    	$this->cd = $registrobanco[self::$nmAtrCd];
    	$this->ano  = $registrobanco[self::$nmAtrAno];
    	$this->cdSetorOrigem = $registrobanco[self::$nmAtrCdSetorOrigem];
    	$this->cdSetorDestino = $registrobanco[self::$nmAtrCdSetorDestino];
    	$this->textoTram  = $registrobanco[self::$nmAtrTexto];
    	$this->prt = $registrobanco[self::$nmAtrProtocolo];
    	$this->dtReferencia = $registrobanco[self::$nmAtrDtReferencia];
    	
    	if($registrobanco[voDocumento::$nmAtrSq] != null){
    		$vodocumento = new voDocumento();
    		$vodocumento->sq = $registrobanco[voDocumento::$nmAtrSq];
    		$vodocumento->cdSetor = $registrobanco[voDocumento::$nmAtrCdSetor];
    		$vodocumento->ano = $registrobanco[voDocumento::$nmAtrAno];
    		$vodocumento->tp = $registrobanco[voDocumento::$nmAtrTp];
    		$vodocumento->link = $registrobanco[voDocumento::$nmAtrLink];
    	
    		$this->voDoc = $vodocumento;
    	}
    	 
	}   
	
	function getDadosFormulario(){
		parent::getDadosFormulario();
				
		$this->sq = @$_POST[self::$nmAtrSq];
		$this->cdSetorOrigem = @$_POST[self::$nmAtrCdSetorOrigem];
		
		if($this->cdSetorOrigem == null){
			//se for vazio, eh pq o setor origem eh da demanda-pai
			$this->cdSetorOrigem = $this->cdSetor;
		}
		
		//verifica se tem documento
		if(@$_POST[voDocumento::getNmTabela()] != null){
			$chaveDoc = @$_POST[voDocumento::getNmTabela()];
			$vodocumento = new voDocumento();
			$vodocumento->getChavePrimariaVOExplodeParam($chaveDoc);
			$this->voDoc = $vodocumento;
		}		
		
		$this->cdSetorDestino = @$_POST[self::$nmAtrCdSetorDestino];		
		$this->textoTram  = @$_POST[self::$nmAtrTexto];
		$this->texto = @$_POST[parent::$nmAtrTexto];
		$this->prt = @$_POST[self::$nmAtrProtocolo];
		$this->dtReferencia = @$_POST[self::$nmAtrDtReferencia];
	}
	
	//para o caso da classe herdar de alguem
	function getVOPaiChave(){
		$voDemanda = new voDemanda();
		$voDemanda->ano = $this->ano;
		$voDemanda->cd = $this->cd;
		
		return $voDemanda;
	}
	
	function getVOPai(){
		$voDemanda = $this->getVOPaiChave();
		$voDemanda->cdSetor = $this->cdSetor;
		$voDemanda->cdSetorAtual = $this->cdSetor;
		$voDemanda->tipo  = $this->tipo;
		$voDemanda->situacao  = $this->situacao;		
		$voDemanda->texto  = $this->texto;
		$voDemanda->prioridade  = $this->prioridade;
		$voDemanda->dtReferencia  = $this->dtReferencia;
		
		$voDemanda->colecaoContrato = $this->colecaoContrato;
	
		return $voDemanda;
	}
	                
	function getVODemandaTramDoc(){
		$voDemandaTramDoc = new voDemandaTramDoc();
		$voDemandaTramDoc->anoDemanda = $this->ano;
		$voDemandaTramDoc->cdDemanda = $this->cd;
		$voDemandaTramDoc->sqDemandaTram = $this->sq;
		
		$voDemandaTramDoc->anoDoc  = $this->voDoc->ano;
		$voDemandaTramDoc->cdSetorDoc  = $this->voDoc->cdSetor;
		$voDemandaTramDoc->tpDoc = $this->voDoc->tp;
		$voDemandaTramDoc->sqDoc = $this->voDoc->sq;	
			
		return $voDemandaTramDoc;
	}
	
	function toString(){						
		$retorno.= $this->ano;
		$retorno.= "," . $this->cd;        
		$retorno.= "," . $this->sq;
		$retorno.= "," . $this->voDoc->toString();
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