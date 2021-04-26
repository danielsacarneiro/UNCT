<?php
/**
 * Define uma classe de exceção
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message = "", Exception $previous = null, $vo=null) {
    	
    	$code = excecaoGenerica::$CD_EXCECAO_CHAVE_INEXISTENTE;    	
    	
    	$str = "<br>Chave Registro Inexistente";
    	if($vo != null){
    		$str = "<br>".get_class($vo). ".<br>|$str.";
    	}
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    	
    	$str = $this->getFile() . " $str";
    
        // garante que tudo está corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>