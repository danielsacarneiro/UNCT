<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($message = "Chave Registro Inexistente.", $code = 0, Exception $previous = null) {
        // cуdigo
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

}
?>