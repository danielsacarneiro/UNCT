<?php
/**
 * Define uma classe de exce��o
 */
class excecaoChaveRegistroInexistente extends excecaoGenerica
{
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
    public function __construct($message = "Chave Registro Inexistente.", $code = 0, Exception $previous = null) {
        // c�digo
    
        // garante que tudo est� corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

}
?>