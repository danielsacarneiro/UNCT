<?php
/**
 * Define uma classe de exce��o
*/
class excecaoConsultaVazia extends excecaoGenerica
{
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($message = "Consulta vazia.", $code = 0, Exception $previous = null) {
		// c�digo

		// garante que tudo est� corretamente inicializado
		parent::__construct($message, $code, $previous);
	}

}
?>