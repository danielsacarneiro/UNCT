<?php
include_once("dominio.class.php");

  Class dominioPermissaoUsuario extends dominio{
    
// ...............................................................
// Construtor
	function __construct () {
        		$this->colecao = array(
				constantes::$cd_usuario_admin => "Administrador",
                constantes::$cd_usuario_colaborador => "Colaborador",
				constantes::$cd_usuario_visitante => "Visitante"
				);
	}
    
    function isAdministrador($colecaoAtributos){        
        return in_array(constantes::$cd_usuario_admin, $colecaoAtributos);                
    }
    
    function temPermissao($colecaoAtributos){
        return in_array(constantes::$cd_usuario_admin, $colecaoAtributos) || in_array(constantes::$cd_usuario_colaborador, $colecaoAtributos);
    }
    
    function temPermissaoExcluirHistorico($colecaoAtributos){
    	return in_array(constantes::$cd_usuario_admin, $colecaoAtributos);
    }
 }
?>