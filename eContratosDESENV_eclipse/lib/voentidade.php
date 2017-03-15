<?php
// .................................................................................................................
// Classe select
// cria um combo select html

  Class voentidade {		
		var $varChaves;
		var $varAtributos;
		var $varAtributosARemover;
        var $temTabHistorico = true;
        
        static $nmTabelaSufixoHistorico   =  	"_hist";
        static $nmTabelaSufixoSequencial   =  	"_seq";
        static $nmAtrSqHist   =  	"hist";
		static $nmAtrDhInclusao  =  	"dh_inclusao";
        static $nmAtrDhUltAlteracao  =  	"dh_ultima_alt";
        static $nmAtrCdUsuarioInclusao  =  	"cd_usuario_incl";
        static $nmAtrCdUsuarioUltAlteracao   =  	"cd_usuario_ultalt";

        static $nmAtrNmUsuarioInclusao  =  	"nm_usuario_incl";
        static $nmAtrNmUsuarioUltAlteracao   =  	"nm_usuario_ultalt";

		var $dhInclusao;
        var $dhUltAlteracao;
        var $cdUsuarioInclusao;
        //id_user eh o usuario logado no sistema
        //constante definida em bibliotecaHTML
        var $cdUsuarioUltAlteracao;
        var $nmUsuarioInclusao;
        var $nmUsuarioUltAlteracao;
        
        var $sqHist;
    
	function __construct() {
		//exemplo de chamada de construtor da classe pai em caso de override
		//parent::__construct($altura,$grossura,$largura,$cor); 
		$this->varAtributos = array(
            voentidade::$nmAtrDhInclusao,
            voentidade::$nmAtrDhUltAlteracao,
            voentidade::$nmAtrCdUsuarioInclusao,
            voentidade::$nmAtrCdUsuarioUltAlteracao);
		
		$this->cdUsuarioUltAlteracao = id_user;
    }
    
    
    // ...............................................................
    // Funcoes ( Propriedades e metodos da classe )     
    /*function getSQLValuesInsertEntidade(){
		$retorno = "";        
        $userManutencao = $this-> cdUsuarioUltAlteracao;
        if($this-> cdUsuarioInclusao == null)
            $this-> cdUsuarioInclusao = $userManutencao;
        
		$retorno.= $this-> cdUsuarioInclusao . ",";
		$retorno.= $this-> cdUsuarioUltAlteracao;
                
		return $retorno;                
    }*/
    
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
    
    function getSQLValuesUpdate(){
		$retorno = "";        
        $retorno.= self::$nmAtrDhUltAlteracao . " = now() ";
        $retorno.= ",";
        $retorno.= self::$nmAtrCdUsuarioUltAlteracao . " = " . $this->cdUsuarioUltAlteracao;

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
        $nmTabela = static::getNmTabela();
       if($isHistorico)
            $nmTabela = self::getNmTabelaHistorico();
        return $nmTabela;
    }
    
    static function getNmTabelaHistorico(){        
        return static::getNmTabela() . voentidade::$nmTabelaSufixoHistorico;        
    }
    
    static function getNmTabelaSequencial(){
        return static::getNmTabela() . voentidade::$nmTabelaSufixoSequencial;        
    }
    
    function isIgualChavePrimaria($voentidade){
    	$chaveEntidade = "";
    	if($voentidade != null)
    		$chaveEntidade = $voentidade->getValorChavePrimaria();
    	
    		/*echo "chave sessao:" . $chaveEntidade . "<br>";
    		echo "chave atual:" . $this->getValorChavePrimaria() . "<br>";*/
    	    	
    	return $this->getValorChavePrimaria() == $chaveEntidade; 
    	
    }    
    
    function getValorChaveHTML(){
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
    
    function getValoresWhereSQLChaveLogica($isHistorico){
    	//via de regra a chave logica eh igual a chave primaria
    	//quando for distinta, o metodo getValoresWhereSQLChaveLogica 
    	//devera ser implementado no vo especifico
    	return $this->getValoresWhereSQLChave($isHistorico);    	
    }
    
}
?>