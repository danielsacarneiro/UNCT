<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($message = "", Exception $previous = null, $vo=null) {
    	
    	$code = excecaoGenerica::$CD_EXCECAO_CHAVE_INEXISTENTE;    	
    	
    	$str = "Chave Registro Inexistente.";
    	if($vo != null){
    		$str = get_class($vo). "|$str.";
    	}
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    	
    	$str = $this->getFile() . " $str";
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>