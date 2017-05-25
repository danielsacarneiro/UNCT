<?php
/**
 * Define uma classe de exce��o
*/
class excecaoObjetoSessaoInexistente extends excecaoGenerica
{
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($message, $code = 0, Exception $previous = null) {
		// c�digo
				
		$message = "Objeto inexistente na sess�o." . $message;

		// garante que tudo est� corretamente inicializado
		parent::__construct($message, $code, $previous);
	}

}
?>