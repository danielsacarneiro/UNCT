<?php
// .................................................................................................................
// Classe select
// cria um combo select html

  Class voentidade {		
		var $varChaves;
		var $varAtributos;
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
        var $cdUsuarioUltAlteracao = id_user;
        var $nmUsuarioInclusao;
        var $nmUsuarioUltAlteracao;
        
        var $cdHistorico;
        var $sqHist;
    
	function __construct() {
		//exemplo de chamada de construtor da classe pai em caso de override
		//parent::__construct($altura,$grossura,$largura,$cor); 
		$this->varAtributos = array(
            voentidade::$nmAtrDhInclusao,
            voentidade::$nmAtrDhUltAlteracao,
            voentidade::$nmAtrCdUsuarioInclusao,
            voentidade::$nmAtrCdUsuarioUltAlteracao);
    }
    
    
    // ...............................................................
    // Funções ( Propriedades e métodos da classe )     
    function getSQLValuesInsertEntidade(){
		$retorno = "";        
        $userManutencao = $this-> cdUsuarioUltAlteracao;
        if($this-> cdUsuarioInclusao == null)
            $this-> cdUsuarioInclusao = $userManutencao;
        
		$retorno.= $this-> cdUsuarioInclusao . ",";
		$retorno.= $this-> cdUsuarioUltAlteracao;
                
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
    
	function getDadosBancoEntidade($registrobanco){		

        $this->dhInclusao = $registrobanco[voentidade::$nmAtrDhInclusao];
        $this->dhUltAlteracao = $registrobanco[voentidade::$nmAtrDhUltAlteracao];
        $this->cdUsuarioInclusao = $registrobanco[voentidade::$nmAtrCdUsuarioInclusao];
        $this->cdUsuarioUltAlteracao = $registrobanco[voentidade::$nmAtrCdUsuarioUltAlteracao];
        
        $this->nmUsuarioInclusao = $registrobanco[voentidade::$nmAtrNmUsuarioInclusao];
        $this->nmUsuarioUltAlteracao = $registrobanco[voentidade::$nmAtrNmUsuarioUltAlteracao]; 		
	}
    
	function getDadosBanco($registrobanco){		
		$this->getDadosRegistroBanco($registrobanco);        
        $this->getDadosBancoEntidade($registrobanco);
        		
	}
    
    function getTodosAtributos(){
        //metodo da classe filha
        $novosAtributos = $this->getAtributosFilho();
        //tamanho + 5
        $retorno = array_merge($novosAtributos, $this->varAtributos);        
        return $retorno;    
    }
    
    function getNmTabelaEntidade($isHistorico){
        $nmTabela = static::getNmTabela();
       if($isHistorico)
            $nmTabela = $this->getNmTabelaHistorico();
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
            
}
?>