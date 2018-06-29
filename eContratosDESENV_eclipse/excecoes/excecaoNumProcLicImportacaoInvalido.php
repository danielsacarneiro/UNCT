<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoNumProcLicImportacaoInvalido extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Chave Proc Licitatуrio invбlido.", Exception $previous = null) {
		// cуdigo

		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_NUM_PROC_LIC_INVALIDO, $previous);
	}

}
?>