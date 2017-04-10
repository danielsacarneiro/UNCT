<?php
include_once("dominio.class.php");

  Class dominioPermissaoUsuario extends dominio{
  	//essas constantes vieram do wordpress wp-users
  	static $cd_usuario_admin = "administrator";
  	static $cd_usuario_nivel1 = "contributor";
  	static $cd_usuario_nivel2 = "subscriber";
  	 
  	static $DS_usuario_admin = "Administrator";
  	static $DS_usuario_nivel1 = "Nível1";
  	static $DS_usuario_nivel2 = "Nível2";
 
 // ...............................................................
// Construtor
	function __construct () {
        		$this->colecao = array(
				self::$cd_usuario_admin => self::$DS_usuario_admin,
        		self::$cd_usuario_nivel1 => self::$DS_usuario_nivel1,
        		self::$cd_usuario_nivel2 => self::$DS_usuario_nivel2
				);
	}
    
    static function isAdministrador($colecaoAtributos){        
        return in_array(self::$cd_usuario_admin, $colecaoAtributos);                
    }
    
    static function temPermissao($colecaoAtributos){ //RESOLVER!
        return in_array(self::$cd_usuario_admin, $colecaoAtributos) || in_array(self::$cd_usuario_nivel1, $colecaoAtributos);
    }
    
    static function temPermissaoExcluirHistorico($colecaoAtributos){
    	return in_array(self::$cd_usuario_admin, $colecaoAtributos);
    }
    
    static function temPermissaoPorFuncao($pCdFuncao, $pArrayPermissaoUsuario){
    	$retorno = true;
    	if($pCdFuncao != null && $pCdFuncao != ""){
    		$retorno = in_array($pCdFuncao, static::getPermissaoFuncaoTpUsuario($pArrayPermissaoUsuario));
    	}
    	
    	return $retorno;
    }
    
    static function getPermissaoFuncaoTpUsuario($pArrayPermissaoUsuario){ 
    	 
    	$arrayPermissao = array();
    	$arrayPermissao[] = constantes::$CD_FUNCAO_DETALHAR;
    	
    	$isUserAdmin = static::isAdministrador($pArrayPermissaoUsuario);
    	$isUserNivel1 = in_array(self::$cd_usuario_nivel1, $pArrayPermissaoUsuario);
    	$isUserNivel2 = in_array(self::$cd_usuario_nivel2, $pArrayPermissaoUsuario);
    	
    	if($isUserAdmin || $isUserNivel1 || $isUserNivel2){
    		$arrayPermissao[] = constantes::$CD_FUNCAO_INCLUIR;
    		
    		//echo "user 2";
    	}
    	
    	if($isUserAdmin || $isUserNivel1){
    		$arrayPermissao[] = constantes::$CD_FUNCAO_ALTERAR;
    		$arrayPermissao[] = constantes::$CD_FUNCAO_EXCLUIR;
    		//echo "user 1";
    	}
    	 
    	if($isUserAdmin){
    		$arrayPermissao[] = constantes::$CD_FUNCAO_HISTORICO;
    		$arrayPermissao[] = constantes::$CD_FUNCAO_TODAS;
    		//echo "user admin";
    	}   	
    	    	 
    	//var_dump($arrayPermissao);
    	
    	return $arrayPermissao;
    }
    
 }
?>