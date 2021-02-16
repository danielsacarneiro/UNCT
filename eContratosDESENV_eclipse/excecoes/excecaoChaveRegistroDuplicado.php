<?php
/**
 * Define uma classe de exceção
 */
class excecaoChaveRegistroDuplicado extends excecaoGenerica
{	
    // Redefine a exceção de forma que a mensagem não seja opcional
    public function __construct($message = "", Exception $previous = null, $vo=null) {
 
    	$code = excecaoGenerica::$CD_EXCECAO_CHAVE_DUPLICADA;
    	$str = "Chave Registro Duplicado.";
    	$str = strtoupper(getTextoHTMLDestacado("<BR><BR>$str", white));
    	if($vo != null){
    		$str = get_class($vo). "|$str.";
    	}
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    	
    	$str = $this->getFile() . " $str";
    
        // garante que tudo está corretamente inicializado
        parent::__construct($str, $code , $previous);
    }

}
?>