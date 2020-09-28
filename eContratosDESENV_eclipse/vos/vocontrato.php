<?php
include_once(caminho_lib."voentidade.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");
include_once (caminho_util."bibliotecaFuncoesUTF-8.php");
include_once(caminho_util."documentoPessoa.php");
include_once(caminho_funcoes."demanda/dominioTipoDemanda.php");
include_once(caminho_funcoes."documento/dominioTpDocumento.php");
include_once(caminho_funcoes."documento/biblioteca_htmlDocumento.php");
include_once(caminho_funcoes."contrato/dominioConsultaArquivoContrato.php");
include_once(caminho_funcoes. "contrato/biblioteca_htmlContrato.php");

include_once(caminho_funcoes. "contrato/dominioAutorizacao.php");
include_once(caminho_funcoes. "contrato/dominioEspeciesContrato.php");
include_once(caminho_funcoes. "contrato/dominioClassificacaoContrato.php");
include_once(caminho_funcoes. "contrato/dominioProrrogacaoContrato.php");
include_once(caminho_funcoes. "contrato/dominioEstudoTecnicoSAD.php");
include_once(caminho_funcoes. "contrato/dominioProrrogacaoFiltroConsolidacao.php");
include_once (caminho_util . "biblioteca_htmlArquivo.php");
include_once (caminho_util . "DocumentoPessoa.php");


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
  		static $DS_ESPECIE_MATER = "Mater"; 
  		static $ID_REQ_CAMPO_CONTRATO = "ID_REQ_CAMPO_CONTRATO";
  		static $matrizImportacao;
  		//static $ID_REQ_QTD_CONTRATOS = "ID_REQ_QTD_CONTRATOS";
		
  		static $nmAtrSqContrato  = "sq";
		static $nmAtrCdContrato  = "ct_numero";
		static $nmAtrAnoContrato  = "ct_exercicio";		
		static $nmAtrTipoContrato =  "ct_tipo";
		static $nmAtrEspecieContrato =  "ct_especie"; 
        static $nmAtrSqEspecieContrato =  "ct_sq_especie"; //sequencial da especie (primeiro, segundo TA, por ex)
        static $nmAtrCdEspecieContrato =  "ct_cd_especie"; //especie propriamente dita(TA, apostilamento)
        static $nmAtrCdSituacaoContrato =  "ct_cd_situacao";
		static $nmAtrObjetoContrato =  "ct_objeto";
		static $nmAtrGestorPessoaContrato =  "ct_gestor_pessoa";
        static $nmAtrCdPessoaGestorContrato =  "pe_cd_resp";
		static $nmAtrGestorContrato =  "ct_gestor";
        static $nmAtrCdGestorContrato =  "gt_cd";//vogestor::$nmAtrCd;
		static $nmAtrProcessoLicContrato =  "ct_processo_lic";
		static $nmAtrCdProcessoLicContrato =  "ct_cd_processo_lic";
		static $nmAtrAnoProcessoLicContrato =  "ct_ano_processo_lic";
		static $nmAtrCdModalidadeProcessoLicContrato =  "ct_cdmod_processo_lic";
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
        static $nmAtrLinkDoc =  	"ct_doc_link";
        static $nmAtrLinkMinutaDoc =  	"ct_doc_minuta";
		
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
		var $cdProcLic ;
		var $anoProcLic ;
		var $cdModalidadeLic;
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
		var $linkDoc ;
		var $linkMinutaDoc ;
		

// ...............................................................
// Construtor
   function __construct() {   	
       parent::__construct();
       $this->temTabHistorico = false;
       $this->sqEspecie = 1;
       $this->importacao = "N";
   }
    
   /*function __construct1($pArrayChavesOuSemSqDefault = null) {
   		if($pArrayChavesOuSemSqDefault == null){
   			echo "aqui1";
   			self::__construct();
   		}else if(is_array($pArrayChavesOuSemSqDefault)){
   			echo "aqui2";
   			parent::__construct1($pArrayChavesOuSemSqDefault);
   		}else{
   			echo "aqui3";
   			self::__construct2($pArrayChavesOuSemSqDefault, null);
   		}   	
	   	
	   	$this->importacao = "N";
   }
    
    function __construct2($pSemSqDefault = false, $pArrayChaves) {
		self::__construct1($pArrayChaves);		
		if($pSemSqDefault){
			$this->sqEspecie = null;
		}
	}*/
// ...............................................................
// FunÃ§Ãµes ( Propriedades e mÃ©todos da classe )
	public static function getTituloJSP(){
		return  "CONTRATO-PLANILHA";
	}
    
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
        	self::$nmAtrCdProcessoLicContrato,
        	self::$nmAtrAnoProcessoLicContrato,
        	self::$nmAtrCdModalidadeProcessoLicContrato,
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
        	self::$nmAtrCdPessoaContratada,
        	self::$nmAtrLinkDoc,
        	self::$nmAtrLinkMinutaDoc,
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
            self::$nmAtrVlMensalContrato,
        	self::$nmAtrLinkDoc,
        	self::$nmAtrLinkMinutaDoc,
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
        $this->cdModalidadeLic	 = $registrobanco[self::$nmAtrCdModalidadeProcessoLicContrato];
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
		$this->cdProcLic = $registrobanco[self::$nmAtrCdProcessoLicContrato];
		$this->anoProcLic = $registrobanco[self::$nmAtrAnoProcessoLicContrato];
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
		$this->linkDoc = $registrobanco[self::$nmAtrLinkDoc];
		$this->linkMinutaDoc = $registrobanco[self::$nmAtrLinkMinutaDoc];
		
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
        $this->cdModalidadeLic = @$_POST[self::$nmAtrCdModalidadeProcessoLicContrato];
        
        //garante o sqEspecie == 1 para o contrato MATER
        if($this->cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER){
        	$this->sqEspecie = 1;
        }        
        
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
		$this->cdProcLic = @$_POST[self::$nmAtrCdProcessoLicContrato];
		$this->anoProcLic = @$_POST[self::$nmAtrAnoProcessoLicContrato];
		
		$this->dtAssinatura = @$_POST[self::$nmAtrDtAssinaturaContrato];
		$this->dtPublicacao = @$_POST[self::$nmAtrDtPublicacaoContrato];
        $this->dtProposta = @$_POST[self::$nmAtrDtProposta];
		$this->empenho = @$_POST[self::$nmAtrNumEmpenhoContrato];		
		$this->tpAutorizacao = @$_POST[self::$nmAtrTipoAutorizacaoContrato];
		$this->cdAutorizacao = @$_POST[self::$nmAtrCdAutorizacaoContrato];
		$this->licom = @$_POST[self::$nmAtrInLicomContrato];
		$this->obs = @$_POST[self::$nmAtrObservacaoContrato];
		$this->linkDoc = @$_POST[self::$nmAtrLinkDoc];
		$this->linkMinutaDoc = @$_POST[self::$nmAtrLinkMinutaDoc];
        
        $this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
        $this->sqHist = @$_POST[self::$nmAtrSqHist];
        //usuario de ultima manutencao sempre sera o id_user
        $this->cdUsuarioUltAlteracao = id_user;
	}
	
	function isChaveLogicaValida(){
		$retorno = $this->tipo == null
		|| $this->anoContrato == null
		|| $this->cdContrato == null
		|| $this->cdEspecie == null
		|| $this->sqEspecie == null;
		
		return !$retorno;
	}
	
	function getValoresWhereSQLChave($isHistorico){
		$nmTabela = self::getNmTabelaStatic($isHistorico);
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ($isHistorico);
		
		//aqui so usa o sq se a chave logica nao estiver completa
		if(!$this->isChaveLogicaValida()){
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqContrato . "=" . $this->sq;
		}
		
		if($isHistorico)
			$query.= " AND ". $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
	
		return $query;
	}
	
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico){
		$nmTabela = self::getNmTabelaStatic($isHistorico);
		$query = $nmTabela . "." . self::$nmAtrTipoContrato . "=" . getVarComoString($this->tipo);
		$query.= " AND " . $nmTabela . "." . self::$nmAtrAnoContrato . "=" . $this->anoContrato;
		$query.= " AND " . $nmTabela . "." . self::$nmAtrCdContrato . "=" . $this->cdContrato;		
		$query.= " AND " . $nmTabela . "." . self::$nmAtrCdEspecieContrato . "=" . getVarComoString($this->cdEspecie);
		$query.= " AND " . $nmTabela . "." . self::$nmAtrSqEspecieContrato . "=" . $this->sqEspecie;
		
		return $query;
	}
            
    function getValorChavePrimaria(){    	
    	return $this->sq;
    }
    
    /*usada quando ha chave logica na entidade
     * */
    function getValorChaveLogica(){    	
    	return $this->anoContrato . CAMPO_SEPARADOR . $this->cdContrato . CAMPO_SEPARADOR . $this->tipo . CAMPO_SEPARADOR . $this->cdEspecie . CAMPO_SEPARADOR . $this->sqEspecie;
    }
    
    function getValorChaveMater(){
    	return $this->anoContrato . CAMPO_SEPARADOR . $this->cdContrato . CAMPO_SEPARADOR . $this->tipo . CAMPO_SEPARADOR . dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER . CAMPO_SEPARADOR . 1;
    }
    
    function toString(){
		
		$retorno = $this->sq . ",";				
		$retorno.= $this->anoContrato . ",";
		$retorno.= $this->cdContrato. ",";
        $retorno.= $this->tipo. ",";
        $retorno.= $this->cdEspecie. ",";
        $retorno.= $this->sqEspecie. ",";
        
        $retorno.= " formato contrato ";
        $retorno.= formatarCodigoContrato($this->cdContrato, $this->anoContrato, $this->tipo);
		
		return $retorno;		
	}   
		
	//se a chaveHTML for igual a getValorChavePrimaria nao precisa desse metodo
	//pq ja tem no voentidade
	function getValorChaveHTML(){
		 $chave = $this->sq
					. "*"
					. $this->anoContrato
					. "*"
					. $this->cdContrato
					. "*"
					. $this->tipo
					. "*"
					. $this->cdEspecie
					. "*"
					. $this->sqEspecie
					. "*"
					. $this->sqHist
					;
		 	
		return $chave;
	}
	
	/*	  
	 * @ deprecated	  
	 */	
	function getVOExplodeChave(){
		$chave = @$_GET["chave"];
		
		$array = explode("*",$chave);
		$this->sq = $array[0];
		$this->anoContrato = $array[1];		
		$this->cdContrato = $array[2];
		$this->tipo = $array[3];
		$this->cdEspecie = $array[4];
		$this->sqEspecie = $array[5];
		$this->sqHist = $array[6];
	}
	
	function getChavePrimariaVOExplode($array){
		$this->sq = $array[0];
		$this->anoContrato = $array[1];
		$this->cdContrato = $array[2];
		$this->tipo = $array[3];		
		$this->cdEspecie = $array[4];
		$this->sqEspecie = $array[5];
		$this->sqHist = $array[6];
	}	
	
	function getLinkDocumento(){
		return static::getEnredeçoDocumento($this->linkDoc);
	}
	function getLinkMinutaDocumento(){
		return static::getEnredeçoDocumento($this->linkMinutaDoc);
	}
	
	static function getEnredeçoDocumento($link){
		//para o caso de o link do doc vier em endereco relativo ("../")
		$pastaUNCTPrincipalSubs = dominioTpDocumento::$ENDERECO_DRIVE . "\\" . dominioTpDocumento::$ENDERECO_PASTABASE_UNCT;
		$link = str_ireplace("../", $pastaUNCTPrincipalSubs . "\\" , $link);
		$link = str_ireplace("..\\", $pastaUNCTPrincipalSubs . "\\" , $link);

		$link = str_ireplace("/", "\\" , $link);
		$link = str_ireplace(dominioTpDocumento::$UNIDADE_REDE_PLANILHA, dominioTpDocumento::$ENDERECO_DRIVE, $link);

		return $link; 
	}
	
	function getCodigoContratoFormatado($chaveCompleta = false) {
		if($chaveCompleta){
			$complemento = $this->sqEspecie . "º " . dominioEspeciesContrato::getDescricaoStatic($this->cdEspecie) . "-"; 
		}
				
		return $complemento . static::getCodigoContratoFormatadoStatic($this->cdContrato, $this->anoContrato, $this->tipo);
	}
	
	static function getCodigoContratoFormatadoStatic($cd, $ano, $tipo) {
		$dominioTipoContrato = new dominioTipoContrato ();
		$complemento = $dominioTipoContrato->getDescricao ( $tipo );
		return formatarCodigoAnoComplemento ( $cd, $ano, $complemento );
	}
	
	/**
	 * retorna atributos necessarios para o consulta de execucao do contrato mod
	 * @return NULL[]|number[]|unknown[]|string[]
	 */
	function getRegistroGenerico(){
		$retorno = array();
		
		$retorno[self::$nmAtrSqContrato]=$this->sq;		
		$retorno[self::$nmAtrCdContrato]=$this->cdContrato;
		$retorno[self::$nmAtrAnoContrato]=$this->anoContrato;
		$retorno[self::$nmAtrTipoContrato]=$this->tipo;
		$retorno[self::$nmAtrSqEspecieContrato]=$this->sqEspecie;
		$retorno[self::$nmAtrCdEspecieContrato]=$this->cdEspecie;
		$retorno[self::$nmAtrDtVigenciaInicialContrato]=$this->dtVigenciaInicial;		
		$retorno[self::$nmAtrDtVigenciaFinalContrato]=$this->dtVigenciaFinal;
		
		$vlMensal = getDecimalSQL($this->vlMensal);
		$vlGlobal = getDecimalSQL($this->vlGlobal);
		
		$retorno[self::$nmAtrVlMensalContrato]=$vlMensal;
		$retorno[self::$nmAtrVlGlobalContrato]=$vlGlobal;
		$retorno[self::$nmAtrDtAssinaturaContrato]=$this->dtAssinatura;
		
					
		$retorno[voContratoModificacao::$nmAtrVlMensalAtualizado]=$vlMensal;
		$retorno[voContratoModificacao::$nmAtrVlGlobalAtualizado]=$vlGlobal;			
		
		
		return $retorno; 
	}
	
}
?>