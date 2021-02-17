<?php
/**
 * Define uma classe de exce��o
 */
class excecaoErroSintaxe extends excecaoGenerica 
{	
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
    public function __construct($message = "", Exception $previous = null, $vo=null) {
 
    	$code = excecaoGenerica::$CD_EXCECAO_ERRO_SINTAXE;
    	$str = "ERRO SINTAXE.";
    	$str = strtoupper(getTextoHTMLDestacado("<BR><BR>$str", white));
    	if($vo != null){
    		$str = get_class($vo). "|$str.";
    	}
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    	
    	$str = $this->getFile() . " $str";
    
        // garante que tudo est� corretamente inicializado
        parent::__construct($str, $code , $previous);
    }

}
?>