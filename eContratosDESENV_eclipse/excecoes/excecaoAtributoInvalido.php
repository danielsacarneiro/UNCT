<?php
/**
 * Define uma classe de exce��o
*/
class excecaoAtributoInvalido extends excecaoGenerica
{
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($message = "Atributo Inv�lido.", Exception $previous = null) {
		// c�digo

		// garante que tudo est� corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_ATRIBUTO_INVALIDO , $previous);
	}

}
?>