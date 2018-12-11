<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($message = "", Exception $previous = null) {
    	
    	$code = excecaoGenerica::$CD_EXCECAO_CHAVE_INEXISTENTE;    	
    	
    	$str = "Chave Registro Inexistente.";
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>