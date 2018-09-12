<?php
/**
 * Define uma classe de exce��o
*/
class excecaoSistemaEmManutencao extends excecaoGenerica
{
	static $FLAG_MANUTENCAO = false;
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($message = "Sistema em manuten��o. Aguarde alguns minutos pelo restabelecimento ou contate o Senhor Doutor Excelent�ssimo Administrador.", Exception $previous = null) {
		// c�digo
		// garante que tudo est� corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_SISTEMA_MANUTENCAO, $previous);
	}

}