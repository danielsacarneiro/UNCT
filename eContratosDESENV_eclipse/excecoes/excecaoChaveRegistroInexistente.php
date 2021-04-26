<?php
/**
 * Define uma classe de exce��o
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
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
    
        // garante que tudo est� corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>