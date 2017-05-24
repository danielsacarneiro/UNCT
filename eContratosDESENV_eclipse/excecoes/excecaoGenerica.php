<?php
/**
 * Define uma classe de exce��o
 */
class excecaoGenerica extends Exception
{
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
    public function __construct($message = "Exce��o Gen�rica.", $code = 0, Exception $previous = null) {
        // c�digo
    
        // garante que tudo est� corretamente inicializado
        parent::__construct(get_class($this). ":". $message, $code, $previous);
    }

    // personaliza a apresenta��o do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>