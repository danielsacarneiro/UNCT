<?php
include_once(caminho_lib."voentidade.php");
//include_once(caminho_vos."vogestor.php");
//include_once(caminho_vos."vogestorpessoa.php");

// .................................................................................................................
// Classe select
// cria um combo select html

  Class vocontrato extends voentidade{
        //var $nmTable = "contrato_import";
		//para teste        
        /*var $nmEntidade = "contrato";
        var $nmTabela = "contrato";
		static $nmEntidadeStatic = "contrato";*/
        
		static $nmAtrSqContrato  = "sq";
		static $nmAtrCdContrato  = "ct_numero";
		static $nmAtrAnoContrato  = "ct_exercicio";		
		static $nmAtrTipoContrato =  "ct_tipo";
		static $nmAtrEspecieContrato =  "ct_especie";
        static $nmAtrSqEspecieContrato =  "ct_sq_especie";
        static $nmAtrCdEspecieContrato =  "ct_cd_especie";
        static $nmAtrCdSituacaoContrato =  "ct_cd_situacao";
		static $nmAtrObjetoContrato =  "ct_objeto";
		static $nmAtrGestorPessoaContrato =  "ct_gestor_pessoa";
        static $nmAtrCdPessoaGestorContrato =  "pe_cd_resp";
		static $nmAtrGestorContrato =  "ct_gestor";
        static $nmAtrCdGestorContrato =  "gt_cd";//vogestor::$nmAtrCd;
		static $nmAtrProcessoLicContrato =  "ct_processo_lic";
		static $nmAtrModalidadeContrato =  "ct_modalidade_lic";
		static $nmAtrDataPublicacaoContrato =  "ct_data_public";
        static $nmAtrDtPublicacaoContrato =  "ct_dt_public";
		static $nmAtrDtAssinaturaContrato  =  "ct_dt_assinatura";
		static $nmAtrDtVigenciaInicialContrato =  "ct_dt_vigencia_inicio";
		static $nmAtrDtVigenciaFinalContrato =  "ct_dt_vigencia_fim";		
		static $nmAtrContratadaContrato =  	"ct_contratada";
		static $nmAtrDocContratadaContrato =  	"ct_doc_contratada";
		static $nmAtrNumEmpenhoContrato =  	"ct_num_empenho";
		static $nmAtrTipoAutorizacaoContrato =  	"ct_tp_autorizacao";
		static $nmAtrCdAutorizacaoContrato =  	"ct_cd_autorizacao";
		static $nmAtrInLicomContrato =  	"ct_in_licom";
        static $nmAtrInImportacaoContrato =  	"ct_in_importacao";
		static $nmAtrObservacaoContrato =  	"ct_observacao";
		static $nmAtrVlGlobalContrato =  	"ct_valor_global";
		static $nmAtrVlMensalContrato =  	"ct_valor_mensal";
        static $nmAtrDtProposta =  	"ct_dt_proposta";
        static $nmAtrCdPessoaContratada =  	"pe_cd_contratada";
		
		var $sq;
		var $cdContrato;
		var $anoContrato;
        var $tipo;
        var $situacao;
		var $contratada;
		var $cdPessoaContratada;
		var $docContratada;
		var $gestor;
		var $nmGestorPessoa;
		var $cdPessoaGestor;
		var $objeto;
		var $dtVigenciaInicial;
		var $dtVigenciaFinal;	
		var $vlMensal;
		var $vlGlobal ; 			
		var $procLic ;			
		var $modalidade	;
        var $especie;
        var $sqEspecie;
        var $cdEspecie;
		var $dtAssinatura; 
		var $dtPublicacao;
        var $dtProposta;
        var $dataPublicacao; 
		var $empenho ;			
		var $tpAutorizacao ;
		var $cdAutorizacao ;
		var $licom ;
        var $importacao ;
		var $obs ;
		

// ...............................................................
// Construtor
   function __construct() {
       parent::__construct();
       $this->temTabHistorico = true;
   }

// ...............................................................
// Funções ( Propriedades e métodos da classe )

    public static function getNmTabela(){
        return  "contrato";
    }
    
    public function getNmClassProcesso(){
        return  "dbcontrato";
    }      
        	
    function getAtributosFilho(){
        $retorno = array(
            self::$nmAtrSqContrato,
            self::$nmAtrAnoContrato,            
            self::$nmAtrCdContrato,
            self::$nmAtrTipoContrato,
            self::$nmAtrEspecieContrato,
            self::$nmAtrSqEspecieContrato,
            self::$nmAtrCdEspecieContrato,
            self::$nmAtrCdSituacaoContrato,
            self::$nmAtrObjetoContrato,
            self::$nmAtrGestorPessoaContrato,
            self::$nmAtrCdPessoaGestorContrato,            
            self::$nmAtrGestorContrato,
            self::$nmAtrCdGestorContrato,
            self::$nmAtrProcessoLicContrato,
            self::$nmAtrModalidadeContrato,
            self::$nmAtrDataPublicacaoContrato,
            self::$nmAtrDtPublicacaoContrato,
            self::$nmAtrDtAssinaturaContrato ,
            self::$nmAtrDtVigenciaInicialContrato,
            self::$nmAtrDtVigenciaFinalContrato,
            self::$nmAtrContratadaContrato,
            self::$nmAtrDocContratadaContrato,
            self::$nmAtrNumEmpenhoContrato,
            self::$nmAtrTipoAutorizacaoContrato,
        	self::$nmAtrCdAutorizacaoContrato,
            self::$nmAtrInLicomContrato,
            self::$nmAtrInImportacaoContrato,
            self::$nmAtrObservacaoContrato,
            self::$nmAtrVlGlobalContrato,
            self::$nmAtrVlMensalContrato,
            self::$nmAtrDtProposta,
        	self::$nmAtrCdPessoaContratada
        );
        
        return $retorno;    
    }
    
    function getAtributosMovimentacoes(){
        $retorno = array(
            self::$nmAtrSqContrato,
            self::$nmAtrAnoContrato,            
            self::$nmAtrCdContrato,
            self::$nmAtrTipoContrato,
            self::$nmAtrEspecieContrato,
            self::$nmAtrCdEspecieContrato,            
            self::$nmAtrSqEspecieContrato,
            self::$nmAtrObjetoContrato,
            self::$nmAtrDtAssinaturaContrato,
        	self::$nmAtrDtVigenciaInicialContrato,
        	self::$nmAtrDtVigenciaFinalContrato,            
            self::$nmAtrVlGlobalContrato,
            self::$nmAtrVlMensalContrato
        );
        
        return $retorno;    
    }
    

    function getAtributosInsertImportacao(){
        $novosAtributos = $this->getAtributosFilho();        
        return $retorno;    
    }

	function getDadosRegistroBanco($registrobanco){		
		$this->sq = $registrobanco[self::$nmAtrSqContrato];
		$this->cdContrato = $registrobanco[self::$nmAtrCdContrato];
		$this->anoContrato = $registrobanco[self::$nmAtrAnoContrato];
        $this->tipo = $registrobanco[self::$nmAtrTipoContrato];
        $this->especie	 = $registrobanco[self::$nmAtrEspecieContrato];
        $this->sqEspecie	 = $registrobanco[self::$nmAtrSqEspecieContrato];
        $this->cdEspecie	 = $registrobanco[self::$nmAtrCdEspecieContrato];
        $this->situacao	 = $registrobanco[self::$nmAtrCdSituacaoContrato];
        $this->modalidade	 = $registrobanco[self::$nmAtrModalidadeContrato];
		$this->cdPessoaContratada= $registrobanco[self::$nmAtrCdPessoaContratada];
		$this->contratada = $registrobanco[self::$nmAtrContratadaContrato];
		$this->docContratada = $registrobanco[self::$nmAtrDocContratadaContrato];
		$this->gestor = $registrobanco[self::$nmAtrGestorContrato];
        $this->cdGestor = $registrobanco[self::$nmAtrCdGestorContrato];
		$this->nmGestorPessoa = $registrobanco[self::$nmAtrGestorPessoaContrato];
        $this->cdPessoaGestor = $registrobanco[self::$nmAtrCdPessoaGestorContrato];
		$this->objeto = $registrobanco[self::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = getData($registrobanco[self::$nmAtrDtVigenciaInicialContrato]);
		$this->dtVigenciaFinal = getData($registrobanco[self::$nmAtrDtVigenciaFinalContrato]);
		$this->vlMensal = getMoeda($registrobanco[self::$nmAtrVlMensalContrato]);
		$this->vlGlobal = getMoeda($registrobanco[self::$nmAtrVlGlobalContrato]);
		$this->procLic = $registrobanco[self::$nmAtrProcessoLicContrato];				
		$this->dtAssinatura = getData($registrobanco[self::$nmAtrDtAssinaturaContrato]);
		
        $this->dtPublicacao = getData($registrobanco[self::$nmAtrDtPublicacaoContrato]);
        $this->dtProposta = getData($registrobanco[self::$nmAtrDtProposta]);
        $this->dataPublicacao = $registrobanco[self::$nmAtrDataPublicacaoContrato];
		$this->empenho = $registrobanco[self::$nmAtrNumEmpenhoContrato];		
		$this->tpAutorizacao = $registrobanco[self::$nmAtrTipoAutorizacaoContrato];
		$this->cdAutorizacao = $registrobanco[self::$nmAtrCdAutorizacaoContrato];
		$this->licom = $registrobanco[self::$nmAtrInLicomContrato];
        $this->importacao = $registrobanco[self::$nmAtrInImportacaoContrato];
		$this->obs = $registrobanco[self::$nmAtrObservacaoContrato];
        $this->dhInclusao = $registrobanco[self::$nmAtrDhInclusao];
        $this->dhUltAlteracao = $registrobanco[self::$nmAtrDhUltAlteracao];
        $this->cdUsuarioInclusao = $registrobanco[self::$nmAtrCdUsuarioInclusao];
        $this->cdUsuarioUltAlteracao = $registrobanco[self::$nmAtrCdUsuarioUltAlteracao];
        
        $this->nmUsuarioInclusao = $registrobanco[self::$nmAtrNmUsuarioInclusao];
        $this->nmUsuarioUltAlteracao = $registrobanco[self::$nmAtrNmUsuarioUltAlteracao];        
		
	}   
	
	function getDadosFormulario(){
        $this->sq = @$_POST[self::$nmAtrSqContrato];
		$this->cdContrato = @$_POST[self::$nmAtrCdContrato];
		$this->anoContrato = @$_POST[self::$nmAtrAnoContrato];
        $this->tipo = @$_POST[self::$nmAtrTipoContrato];
        
        //if(isset($_POST[self::$nmAtrSqEspecieContrato]))
            $this->sqEspecie = @$_POST[self::$nmAtrSqEspecieContrato];
            
		$this->especie = @$_POST[self::$nmAtrEspecieContrato];
        $this->sqEspecie = @$_POST[self::$nmAtrSqEspecieContrato];
        $this->cdEspecie = @$_POST[self::$nmAtrCdEspecieContrato]; 
        $this->modalidade = @$_POST[self::$nmAtrModalidadeContrato];
        
        $this->cdPessoaContratada= @$_POST[self::$nmAtrCdPessoaContratada];
        $this->contratada = @$_POST[self::$nmAtrContratadaContrato];
		$this->docContratada = @$_POST[self::$nmAtrDocContratadaContrato];
		$this->gestor = @$_POST[self::$nmAtrGestorContrato];
        $this->cdGestor = @$_POST[self::$nmAtrCdGestorContrato];        
        $this->nmGestorPessoa = @$_POST[self::$nmAtrGestorPessoaContrato];
        $this->cdPessoaGestor = @$_POST[self::$nmAtrCdPessoaGestorContrato];		
		$this->objeto = @$_POST[self::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = @$_POST[self::$nmAtrDtVigenciaInicialContrato];
		$this->dtVigenciaFinal = @$_POST[self::$nmAtrDtVigenciaFinalContrato];        		
		$this->vlMensal = @$_POST[self::$nmAtrVlMensalContrato];
		$this->vlGlobal = @$_POST[self::$nmAtrVlGlobalContrato];
		$this->procLic = @$_POST[self::$nmAtrProcessoLicContrato];				
		$this->dtAssinatura = @$_POST[self::$nmAtrDtAssinaturaContrato];
		$this->dtPublicacao = @$_POST[self::$nmAtrDtPublicacaoContrato];
        $this->dtProposta = @$_POST[self::$nmAtrDtProposta];
		$this->empenho = @$_POST[self::$nmAtrNumEmpenhoContrato];		
		$this->tpAutorizacao = @$_POST[self::$nmAtrTipoAutorizacaoContrato];
		$this->cdAutorizacao = @$_POST[self::$nmAtrCdAutorizacaoContrato];
		$this->licom = @$_POST[self::$nmAtrInLicomContrato];
		$this->obs = @$_POST[self::$nmAtrObservacaoContrato];
        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);
        
		$query = $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->cdContrato;
		$query.= " AND " . $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->anoContrato;
		$query.= " AND ". $nmTabela . "." . self::$nmAtrSqContrato . "=" . $this->sq;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
        
        return $query;        
    }
    
    function getValoresWhereSQL($voEntidade, $colecaoAtributos){
        $sqlConector = "";
        $retorno = "";
        $nmTabela = $voEntidade->getNmTabelaEntidade(false);        
        
        $tamanho = sizeof($colecaoAtributos);                   
        $chaves = array_keys($colecaoAtributos);        
            
        for ($i=0;$i<$tamanho;$i++) {
            $nmAtributo = $chaves[$i];
            $retorno .= $sqlConector . $this->getAtributoValorSQL($nmAtributo, $colecaoAtributos[$nmAtributo]);
            $sqlConector = " AND ";
        }
        return $retorno;        
    }
        
    function getValorChavePrimaria(){    	
    	return $this->sq;
    }
    
	function toString(){
		
		$retorno = $this->sq . ",";				
		$retorno.= $this->anoContrato . ",";
		$retorno.= $this->cdContrato. ",";
        $retorno.= $this->tipo;		
		
		return $retorno;		
	}   
		
	//se a chaveHTML for igual a getValorChavePrimaria nao precisa desse metodo
	//pq ja tem no voentidade
	function getValorChaveHTML(){
		 $chave = $this->sq
					. "*"
					. $this->anoContrato
					. "*"
					. $this->tipo
					. "*"
					. $this->cdContrato
					. "*"					
					. $this->cdEspecie
					. "*"
					. $this->sqHist
					;
		 	
		return $chave;
	}
	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];
		
		$array = explode("*",$chave);		
		$this->sq = $array[0];
		$this->anoContrato = $array[1];
		$this->tipo = $array[2];
		$this->cdContrato = $array[3];
		$this->cdEspecie = $array[4];
		$this->sqHist = $array[5];
	}	

}
?>