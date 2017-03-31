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
    
    function temPermissao($colecaoAtributos){
        return in_array(self::$cd_usuario_admin, $colecaoAtributos) || in_array(self::$cd_usuario_nivel1, $colecaoAtributos);
    }
    
    function temPermissaoExcluirHistorico($colecaoAtributos){
    	return in_array(self::$cd_usuario_admin, $colecaoAtributos);
    }
 }
?>