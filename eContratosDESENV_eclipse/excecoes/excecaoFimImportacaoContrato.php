<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoFimImportacaoContrato extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message, $code = 0, Exception $previous = null) {
		// cуdigo

		$message = "Fim importaзгo contrato." . $message;

		// garante que tudo estб corretamente inicializado
		parent::__construct($message, $code, $previous);
	}

}
