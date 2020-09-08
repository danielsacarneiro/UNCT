<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoMetodoNaoImplementado extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Mйtodo nгo implementado.", Exception $previous = null) {
		// cуdigo

		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_METODO_NAO_IMPLEMENTADO, $previous);
	}

}
?>