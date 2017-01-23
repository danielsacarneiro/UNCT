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
        static $nmAtrCdGestorPessoaContrato =  "gp_cd";//vogestorpessoa::$nmAtrCd;
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
		
		var $sq;
		var $cdContrato;
		var $anoContrato;
        var $tipo;
        var $situacao;
		var $contratada;
		var $docContratada;
		var $gestor;
		var $nmGestorPessoa;
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
            vocontrato::$nmAtrSqContrato,
            vocontrato::$nmAtrAnoContrato,            
            vocontrato::$nmAtrCdContrato,
            vocontrato::$nmAtrTipoContrato,
            vocontrato::$nmAtrEspecieContrato,
            vocontrato::$nmAtrSqEspecieContrato,
            vocontrato::$nmAtrCdEspecieContrato,
            vocontrato::$nmAtrCdSituacaoContrato,
            vocontrato::$nmAtrObjetoContrato,
            vocontrato::$nmAtrGestorPessoaContrato,
            vocontrato::$nmAtrCdGestorPessoaContrato,            
            vocontrato::$nmAtrGestorContrato,
            vocontrato::$nmAtrCdGestorContrato,
            vocontrato::$nmAtrProcessoLicContrato,
            vocontrato::$nmAtrModalidadeContrato,
            vocontrato::$nmAtrDataPublicacaoContrato,
            vocontrato::$nmAtrDtPublicacaoContrato,
            vocontrato::$nmAtrDtAssinaturaContrato ,
            vocontrato::$nmAtrDtVigenciaInicialContrato,
            vocontrato::$nmAtrDtVigenciaFinalContrato,
            vocontrato::$nmAtrContratadaContrato,
            vocontrato::$nmAtrDocContratadaContrato,
            vocontrato::$nmAtrNumEmpenhoContrato,
            vocontrato::$nmAtrTipoAutorizacaoContrato,
        	vocontrato::$nmAtrCdAutorizacaoContrato,
            vocontrato::$nmAtrInLicomContrato,
            vocontrato::$nmAtrInImportacaoContrato,
            vocontrato::$nmAtrObservacaoContrato,
            vocontrato::$nmAtrVlGlobalContrato,
            vocontrato::$nmAtrVlMensalContrato,
            vocontrato::$nmAtrDtProposta
        );
        
        return $retorno;    
    }
    
    function getAtributosMovimentacoes(){
        $retorno = array(
            vocontrato::$nmAtrSqContrato,
            vocontrato::$nmAtrAnoContrato,            
            vocontrato::$nmAtrCdContrato,
            vocontrato::$nmAtrTipoContrato,
            vocontrato::$nmAtrEspecieContrato,
            vocontrato::$nmAtrCdEspecieContrato,            
            vocontrato::$nmAtrSqEspecieContrato,
            vocontrato::$nmAtrObjetoContrato,
            vocontrato::$nmAtrDtAssinaturaContrato,
            vocontrato::$nmAtrDtVigenciaFinalContrato,            
            vocontrato::$nmAtrVlGlobalContrato,
            vocontrato::$nmAtrVlMensalContrato
        );
        
        return $retorno;    
    }
    

    function getAtributosInsertImportacao(){
        $novosAtributos = $this->getAtributosFilho();        
        return $retorno;    
    }

	function getContratoBanco($registrobanco){		
		$this->sq = $registrobanco[vocontrato::$nmAtrSqContrato];
		$this->cdContrato = $registrobanco[vocontrato::$nmAtrCdContrato];
		$this->anoContrato = $registrobanco[vocontrato::$nmAtrAnoContrato];
        $this->tipo = $registrobanco[vocontrato::$nmAtrTipoContrato];
        $this->especie	 = $registrobanco[vocontrato::$nmAtrEspecieContrato];
        $this->sqEspecie	 = $registrobanco[vocontrato::$nmAtrSqEspecieContrato];
        $this->cdEspecie	 = $registrobanco[vocontrato::$nmAtrCdEspecieContrato];
        $this->situacao	 = $registrobanco[vocontrato::$nmAtrCdSituacaoContrato];
        $this->modalidade	 = $registrobanco[vocontrato::$nmAtrModalidadeContrato];
		$this->contratada = $registrobanco[vocontrato::$nmAtrContratadaContrato];
		$this->docContratada = $registrobanco[vocontrato::$nmAtrDocContratadaContrato];
		$this->gestor = $registrobanco[vocontrato::$nmAtrGestorContrato];
        $this->cdGestor = $registrobanco[vocontrato::$nmAtrCdGestorContrato];
		$this->nmGestorPessoa = $registrobanco[vocontrato::$nmAtrGestorPessoaContrato];
        $this->cdGestorPessoa = $registrobanco[vocontrato::$nmAtrCdGestorPessoaContrato];
		$this->objeto = $registrobanco[vocontrato::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = getData($registrobanco[vocontrato::$nmAtrDtVigenciaInicialContrato]);
		$this->dtVigenciaFinal = getData($registrobanco[vocontrato::$nmAtrDtVigenciaFinalContrato]);
		$this->vlMensal = getMoeda($registrobanco[vocontrato::$nmAtrVlMensalContrato]);
		$this->vlGlobal = getMoeda($registrobanco[vocontrato::$nmAtrVlGlobalContrato]);
		$this->procLic = $registrobanco[vocontrato::$nmAtrProcessoLicContrato];				
		$this->dtAssinatura = getData($registrobanco[vocontrato::$nmAtrDtAssinaturaContrato]);
		
        $this->dtPublicacao = getData($registrobanco[vocontrato::$nmAtrDtPublicacaoContrato]);
        $this->dtProposta = getData($registrobanco[vocontrato::$nmAtrDtProposta]);
        $this->dataPublicacao = $registrobanco[vocontrato::$nmAtrDataPublicacaoContrato];
		$this->empenho = $registrobanco[vocontrato::$nmAtrNumEmpenhoContrato];		
		$this->tpAutorizacao = $registrobanco[vocontrato::$nmAtrTipoAutorizacaoContrato];
		$this->cdAutorizacao = $registrobanco[vocontrato::$nmAtrCdAutorizacaoContrato];
		$this->licom = $registrobanco[vocontrato::$nmAtrInLicomContrato];
        $this->importacao = $registrobanco[vocontrato::$nmAtrInImportacaoContrato];
		$this->obs = $registrobanco[vocontrato::$nmAtrObservacaoContrato];
        $this->dhInclusao = $registrobanco[vocontrato::$nmAtrDhInclusao];
        $this->dhUltAlteracao = $registrobanco[vocontrato::$nmAtrDhUltAlteracao];
        $this->cdUsuarioInclusao = $registrobanco[vocontrato::$nmAtrCdUsuarioInclusao];
        $this->cdUsuarioUltAlteracao = $registrobanco[vocontrato::$nmAtrCdUsuarioUltAlteracao];
        
        $this->nmUsuarioInclusao = $registrobanco[vocontrato::$nmAtrNmUsuarioInclusao];
        $this->nmUsuarioUltAlteracao = $registrobanco[vocontrato::$nmAtrNmUsuarioUltAlteracao];        
		
	}   
	
	function getDadosFormulario(){
        $this->sq = @$_POST[vocontrato::$nmAtrSqContrato];
		$this->cdContrato = @$_POST[vocontrato::$nmAtrCdContrato];
		$this->anoContrato = @$_POST[vocontrato::$nmAtrAnoContrato];
        $this->tipo = @$_POST[vocontrato::$nmAtrTipoContrato];
        
        //if(isset($_POST[vocontrato::$nmAtrSqEspecieContrato]))
            $this->sqEspecie = @$_POST[vocontrato::$nmAtrSqEspecieContrato];
            
		$this->especie = @$_POST[vocontrato::$nmAtrEspecieContrato];
        $this->sqEspecie = @$_POST[vocontrato::$nmAtrSqEspecieContrato];
        $this->cdEspecie = @$_POST[vocontrato::$nmAtrCdEspecieContrato]; 
        $this->modalidade = @$_POST[vocontrato::$nmAtrModalidadeContrato];
        
        $this->contratada = @$_POST[vocontrato::$nmAtrContratadaContrato];
		$this->docContratada = @$_POST[vocontrato::$nmAtrDocContratadaContrato];
		$this->gestor = @$_POST[vocontrato::$nmAtrGestorContrato];
        $this->cdGestor = @$_POST[vocontrato::$nmAtrCdGestorContrato];        
        $this->nmGestorPessoa = @$_POST[vocontrato::$nmAtrGestorPessoaContrato];
        $this->cdGestorPessoa = @$_POST[vocontrato::$nmAtrCdGestorPessoaContrato];		
		$this->objeto = @$_POST[vocontrato::$nmAtrObjetoContrato];
		$this->dtVigenciaInicial = @$_POST[vocontrato::$nmAtrDtVigenciaInicialContrato];
		$this->dtVigenciaFinal = @$_POST[vocontrato::$nmAtrDtVigenciaFinalContrato];        		
		$this->vlMensal = @$_POST[vocontrato::$nmAtrVlMensalContrato];
		$this->vlGlobal = @$_POST[vocontrato::$nmAtrVlGlobalContrato];
		$this->procLic = @$_POST[vocontrato::$nmAtrProcessoLicContrato];				
		$this->dtAssinatura = @$_POST[vocontrato::$nmAtrDtAssinaturaContrato];
		$this->dtPublicacao = @$_POST[vocontrato::$nmAtrDtPublicacaoContrato];
        $this->dtProposta = @$_POST[vocontrato::$nmAtrDtProposta];
		$this->empenho = @$_POST[vocontrato::$nmAtrNumEmpenhoContrato];		
		$this->tpAutorizacao = @$_POST[vocontrato::$nmAtrTipoAutorizacaoContrato];
		$this->cdAutorizacao = @$_POST[vocontrato::$nmAtrCdAutorizacaoContrato];
		$this->licom = @$_POST[vocontrato::$nmAtrInLicomContrato];
		$this->obs = @$_POST[vocontrato::$nmAtrObservacaoContrato];
        
        $this->dhUltAlteracao = @$_POST[vocontrato::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[vocontrato::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
    
    function getValoresWhereSQLChave($isHistorico){
        $nmTabela = $this->getNmTabelaEntidade($isHistorico);
        
		$query = $nmTabela . "." . vocontrato::$nmAtrCdContrato . "=" . $this->cdContrato;
		$query.= " AND " . $nmTabela . "." . vocontrato::$nmAtrAnoContrato . "=" . $this->anoContrato;
		$query.= " AND ". $nmTabela . "." . vocontrato::$nmAtrSqContrato . "=" . $this->sq;
        if($isHistorico)
            $query.= " AND ". $nmTabela . "." . vocontrato::$nmAtrSqHist . "=" . $this->sqHist;
        
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
		
		$retorno = $this->sq . "";				
		$retorno.= $this->anoContrato . ",";
        $retorno.= $this->tipo . ",";
		$retorno.= $this->cdContrato;
		
		return $retorno;		
	}   

}
?>