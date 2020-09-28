<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoAtributoInvalido extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Atributo Invбlido.", Exception $previous = null) {
		// cуdigo

		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_ATRIBUTO_INVALIDO , $previous);
	}

}
?>