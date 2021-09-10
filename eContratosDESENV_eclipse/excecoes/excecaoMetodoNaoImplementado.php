<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoMetodoNaoImplementado extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($nmMetodo = null, $voentidade = null,Exception $previous = null) {
		// cуdigo
		$message = "Mйtodo nгo implementado";
		if($voentidade != null){
			$message .= "|Funcao '".$voentidade::getTituloJSP(). "'";
		}
		
		if(isAtributoValido($nmMetodo)){
			$message .= "|'$nmMetodo'";
		}
		
		$message .= ".";

		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_METODO_NAO_IMPLEMENTADO, $previous);
	}

}
?>