<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($message = "", $code = 0, Exception $previous = null) {
    	$str = "Chave Registro Inexistente.";
    	if($message != null && $message != ""){
    		$str.= "/n" . $message;
    	}
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>