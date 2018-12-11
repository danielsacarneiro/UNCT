<?php
/**
 * Define uma classe de exce��o
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
    public function __construct($message = "", Exception $previous = null) {
    	
    	$code = excecaoGenerica::$CD_EXCECAO_CHAVE_INEXISTENTE;    	
    	
    	$str = "Chave Registro Inexistente.";
    	if($message != null && $message != ""){
    		$str.= "<br>" . $message;
    	}
    
        // garante que tudo est� corretamente inicializado
        parent::__construct($str, $code, $previous);
    }

}
?>