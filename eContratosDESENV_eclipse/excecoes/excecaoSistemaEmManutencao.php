<?php
/**
 * Define uma classe de exceзгo
*/
class excecaoSistemaEmManutencao extends excecaoGenerica
{
	static $FLAG_MANUTENCAO = false;
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Sistema em manutenзгo. Aguarde alguns minutos pelo restabelecimento ou contate o Senhor Doutor Excelentнssimo Administrador.", Exception $previous = null) {
		// cуdigo
		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_SISTEMA_MANUTENCAO, $previous);
	}

}