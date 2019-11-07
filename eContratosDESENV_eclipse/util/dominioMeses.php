<?php
include_once (caminho_util . "dominio.class.php");
class dominioMeses extends dominio {		
	// ...............................................................
	// Construtor
	static function getColecao() {
		$retorno = array(
			 '01' => 'Janeiro',
			 '02' => 'Fevereiro',
			 '03' => 'Março',
			 '04' => 'Abril',
			 '05' => 'Maio',
			 '06' => 'Junho',
			 '07' => 'Julho',
			 '08' => 'Agosto',
			 '09' => 'Setembro',
			 '10' => 'Outubro',
			 '11' => 'Novembro',
			 '12' => 'Dezembro'
			);
		
		return $retorno;
	}
	
}
?>