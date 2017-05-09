<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoMaisDeUmRegistroRetornado extends Exception
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($message = "Existe mais de um registro.", $code = 0, Exception $previous = null) {
        // cуdigo
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

    // personaliza a apresentaзгo do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>