<?php
include_once (caminho_util . "multiplosConstrutores.php");

  Class voentidade extends multiplosConstrutores{
  		var $NM_METODO_RETORNO_CONFIRMAR;
  	
		var $varChaves;
		var $varAtributos;
		var $varAtributosARemover;
        var $temTabHistorico;
        
        //atributo que indica se a entidade implementa a desativacao
        //a desativacao soh eh necessaria quando ha tabelas de relacionamento que impedem a exclusao direta        
        var $temTabsRelacionamentoQueImpedemExclusaoDireta;
        
        static $nmTabelaSufixoHistorico   =  	"_hist";
        static $nmTabelaSufixoSequencial   =  	"_seq";
        static $nmAtrSqHist   =  	"hist";
		static $nmAtrDhInclusao  =  	"dh_inclusao";
        static $nmAtrDhUltAlteracao  =  	"dh_ultima_alt";
        static $nmAtrDhOperacao =  	"dh_operacao";
        static $nmAtrCdUsuarioInclusao  =  	"cd_usuario_incl";
        static $nmAtrCdUsuarioUltAlteracao   =  	"cd_usuario_ultalt";
        static $nmAtrCdUsuarioOperacao =  	"cd_usuario_operacao";

        static $nmAtrNmUsuarioInclusao  =  	"nm_usuario_incl";
        static $nmAtrNmUsuarioUltAlteracao   =  	"nm_usuario_ultalt";
        static $nmAtrNmUsuarioOperacao =  	"nm_usuario_operacao";
        
        static $nmAtrInDesativado = "in_desativado";

		var $dhInclusao;
        var $dhUltAlteracao;
        var $dhOperacao;
        var $cdUsuarioInclusao;
        var $cdUsuarioOperacao;
        //id_user eh o usuario logado no sistema
        //constante definida em bibliotecaHTML
        var $cdUsuarioUltAlteracao;
        var $nmUsuarioInclusao;
        var $nmUsuarioUltAlteracao;
        
        var $nmUsuarioOperacao;
        
        var $sqHist;
        
        //var $dbprocesso;
    
	function __construct0() {
		//exemplo de chamada de construtor da classe pai em caso de override
		//parent::__construct($altura,$grossura,$largura,$cor);
		$this->varAtributos = array(
            voentidade::$nmAtrDhInclusao,
            voentidade::$nmAtrDhUltAlteracao,
            voentidade::$nmAtrCdUsuarioInclusao,
            voentidade::$nmAtrCdUsuarioUltAlteracao);
		
		$this->cdUsuarioUltAlteracao = id_user;
		$this->NM_METODO_RETORNO_CONFIRMAR = null;
		$this->temTabHistorico = true;
		$this->temTabsRelacionamentoQueImpedemExclusaoDireta = true;
		
		//cria a classe processo para todo vo
		/*$class = static::getNmClassProcesso();
		$this->dbprocesso= new $class();*/				
    }    
    
    // ...............................................................
    // Funcoes ( Propriedades e metodos da classe )     
    
    function getSQLValuesInsertEntidade(){
    	$userManutencao = $this->cdUsuarioUltAlteracao;
    	if($this-> cdUsuarioInclusao == null)
    		$this-> cdUsuarioInclusao = $userManutencao;    		 
    	
    	$temAtributosParaChecar = $this->varAtributos != null;    	
    	$temUsuarioInc = false;
    	$temUsuarioAlt = false;
    	$temDtInc = false;
    	$temDtAlt = false;
    	
    	if($temAtributosParaChecar){
    		$temUsuarioInc = array_search(self::$nmAtrCdUsuarioInclusao, $this->varAtributos);
    		$temUsuarioAlt = array_search(self::$nmAtrCdUsuarioUltAlteracao, $this->varAtributos);
    		$temDtInc = array_search(self::$nmAtrDhInclusao, $this->varAtributos);
    		$temDtAlt = array_search(self::$nmAtrDhUltAlteracao, $this->varAtributos);    		
    	}    	
    	
    	$retorno = "";
    	$conector = ",";
    	if($temUsuarioInc){
    		$retorno.= $conector . $this-> cdUsuarioInclusao ;
    		$conector = ",";
    		
    		//ECHO "TEM USU INCLUSAO";
    	}//ELSE ECHO "NAO TEM USU INCLUSAO";
    	
    	if($temUsuarioAlt){
    		$retorno.= $conector . $this-> cdUsuarioUltAlteracao;
    		//$conector = ",";
    	}    		
    
   		return $retorno;
    }
    
    /**
     * @deprecated
     */
    function getSQLValuesUpdate(){
    	$retorno = "";
    	$retorno.= self::$nmAtrDhUltAlteracao . " = now() ";
    	$retorno.= ",";
    	$retorno.= self::$nmAtrCdUsuarioUltAlteracao . " = " . $this->cdUsuarioUltAlteracao;
    
    	return $retorno;
    }
    
    function getSQLValuesEntidadeUpdate(){
    	$temUsuarioAlt = array_search(self::$nmAtrCdUsuarioUltAlteracao, $this->varAtributos);
    	$temDtAlt = array_search(self::$nmAtrDhUltAlteracao, $this->varAtributos);
    
    	$retorno = "";
    	$conector = ",";
    	if($temUsuarioAlt){
    		$retorno.= $conector. self::$nmAtrCdUsuarioUltAlteracao . " = " . $this->cdUsuarioUltAlteracao;
    		$conector = ",";
    	}
    	if($temDtAlt){
    		$retorno.= $conector . self::$nmAtrDhUltAlteracao . " = now() ";
    	}
    
    	return $retorno;
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
    
    function getAtributoValorSQL($atributo, $valor){
        return $atributo . " = " . $valor;
    }    
    
    function getDadosFormularioEntidade(){
    	$this->dhUltAlteracao = @$_POST[self::$nmAtrDhUltAlteracao];
    	$this->sqHist = @$_POST[self::$nmAtrSqHist];
    	//usuario de ultima manutencao sempre sera o id_user
    	$this->cdUsuarioUltAlteracao = id_user;
    }
    
	function getDadosBancoEntidade($registrobanco){		

        $this->dhInclusao = $registrobanco[voentidade::$nmAtrDhInclusao];
        $this->dhUltAlteracao = $registrobanco[voentidade::$nmAtrDhUltAlteracao];        
        
        $this->cdUsuarioInclusao = $registrobanco[voentidade::$nmAtrCdUsuarioInclusao];
        $this->cdUsuarioUltAlteracao = $registrobanco[voentidade::$nmAtrCdUsuarioUltAlteracao];
        $this->sqHist= $registrobanco[voentidade::$nmAtrSqHist];
        //$this->cdHistorico = $registrobanco[voentidade::$nmAtrcdSqHist];
        
        $this->nmUsuarioInclusao = $registrobanco[voentidade::$nmAtrNmUsuarioInclusao];
        $this->nmUsuarioUltAlteracao = $registrobanco[voentidade::$nmAtrNmUsuarioUltAlteracao];        
        
        if($this->sqHist != null){
	        $this->dhOperacao = $registrobanco[voentidade::$nmAtrDhOperacao];
	        $this->nmUsuarioOperacao = $registrobanco[voentidade::$nmAtrNmUsuarioOperacao];
        }
	}
    
	function getDadosBanco($registrobanco){		
		$this->getDadosRegistroBanco($registrobanco);        
        $this->getDadosBancoEntidade($registrobanco);
	}
    
    function removeAtributos($arrayAtribRemover){    	
    	$this->varAtributos = removeColecaoAtributos($this->varAtributos, $arrayAtribRemover);
    }
    
    function removeTodosAtributosPai(){
    	unset($this->varAtributos);
    }
    
    function getTodosAtributos(){
    	//metodo da classe filha
    	$novosAtributos = $this->getAtributosFilho();
    	$retorno = $novosAtributos; 
    	//tamanho + 5
    	if($this->varAtributos != null)
    		$retorno = array_merge($novosAtributos, $this->varAtributos);
    	
    	return $retorno;
    }
    
    function getNmTabelaEntidade($isHistorico){
       /*$nmTabela = static::getNmTabela();
       if($isHistorico)
            $nmTabela = self::getNmTabelaHistorico();
        return $nmTabela;*/
       
       return self::getNmTabelaStatic($isHistorico);
    }
    
    static function getNmTabelaStatic($isHistorico){
    	$nmTabela = static::getNmTabela();
    	if($isHistorico){
    		$nmTabela = self::getNmTabelaHistorico();
    	}
    	return $nmTabela;
    }
    
    static function getNmTabelaHistorico(){        
        return static::getNmTabela() . voentidade::$nmTabelaSufixoHistorico;        
    }
    
    /*static function getNmTabelaSequencial(){
        return static::getNmTabela() . voentidade::$nmTabelaSufixoSequencial;        
    }*/
    
    function isIgualChavePrimaria($voentidade){
    	$chaveEntidade = "";
    	
    	if($voentidade != null){    		
    		//$chaveEntidade = call_user_func_array(array($voentidade,$nmMetodo),array(""));
    		$chaveEntidade = $voentidade->getValorChaveLogica();
    	}
    	    	 
    	$chaveAComparar = $this->getValorChaveLogica();
    	
    	/*echo "chave a comparar:" . $chaveEntidade . "<br>";
    	echo "chave referencia:" . $chaveAComparar . "<br>";*/    	
    
    	return $chaveAComparar == $chaveEntidade;    	 
    }
    
    function getValorChaveHTML(){
    	//pega do filho
    	return $this->getValorChavePrimaria();
    }
    
    function getValorChaveLogica(){
    	//pega do filho
    	return $this->getValorChavePrimaria();
    }
    
    function getNmClassVO(){
    	$classProcesso = static::getNmClassProcesso();    	 
    	return  str_replace("db", "vo", $classProcesso);
    }
    
    function getVOExplodeChave(){
    	$chave = @$_GET["chave"];
    	$this->getChavePrimariaVOExplodeParam($chave);
    }
    
    function getChavePrimariaVOExplodeParam($chave){    	
    	$array = explode(CAMPO_SEPARADOR,$chave);
    	$this->getChavePrimariaVOExplode($array);
    }
    
    function getAtributosComIdentificacaoTabela($colecaoAtributos, $isHistorico){
    	$retorno = array();
    	foreach ($colecaoAtributos as $nmAtributo) {
    		$retorno[] = $this->getNmTabelaEntidade($isHistorico) . "." . $nmAtributo;
    	}    	
    	return $retorno; 
    }
    
    function getValoresWhereSQLChaveLogicaSemSQ($isHistorico){
    	//via de regra a chave logica eh igual a chave primaria
    	//quando for distinta, o metodo getValoresWhereSQLChaveLogica 
    	//devera ser implementado no vo especifico
    	return $this->getValoresWhereSQLChave($isHistorico);    	
    }
    
    function getTelaRetornoConfirmar(){
    	//se a filha nao tiver sobrescrito esse metodo
    	//pega o metodo da classe filha via de REGRA 
    	return $this->getNmTabela();
    }
    
    function getMensagemComplementarTelaSucesso(){
    	return "";    	
    }
    
    function getMensagemComplementarTelaSucessoPadrao($titulo, $cd, $descricao){
    	$retorno = "$titulo: " . $descricao . " (".complementarCharAEsquerda($cd, "0", TAMANHO_CODIGOS).")";
    	return $retorno;
    }    
    
    function isHistorico(){    	
    	return $this->sqHist != null && $this->sqHist != "";
    }
    
    function temTabHistorico(){
    	return $this->temTabHistorico;
    }
    
    function temTabsRelacionamentoQueImpedemExclusaoDireta(){
    	return $this->temTabsRelacionamentoQueImpedemExclusaoDireta;
    }
    
    function getValoresWhereSQLChaveSemNomeTabela($isHistorico) {    	
    	return str_replace ( $this->getNmTabelaEntidade($isHistorico) . ".", "", $this->getValoresWhereSQLChave($isHistorico) );    	
    }
    /*function validaExclusaoRelacionamentoHistorico(){
    	$retorno = false;
    	//so exclui os relacionamentos se a exclusao for de registro historico
    	//e nao existir outro registro vigente que possa utilizar os relacionamentos
    	if($this->isHistorico() && !$this->dbprocesso->existeRegistroVigente($this)){    		
    		$retorno = true;
    	}    	
    	return $retorno;
    }*/   
}
?>