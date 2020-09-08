<?php
/**
 * Define uma classe de exce��o
*/
class excecaoMetodoNaoImplementado extends excecaoGenerica
{
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($message = "M�todo n�o implementado.", Exception $previous = null) {
		// c�digo

		// garante que tudo est� corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_METODO_NAO_IMPLEMENTADO, $previous);
	}

}
?>