<?php
include_once (caminho_util . "dominio.class.php");
class dominioCoresCrescente extends dominio {		
	// ...............................................................
	// Construtor
	static function getColecao() {
		$retorno = array(
			 'Khaki' => 'Black',
			 'Khaki' => 'Black',
			 'Yellow' => 'Black',
			 'Yellow' => 'Black',			
			 'Gold' => 'Black',
			 'Gold' => 'Black',
			 'Orange' => 'Black',
			 'Orange' => 'Black',
			 'DarkOrange' => 'Black',
			 'DarkOrange' => 'Black',
			 'Red' => 'White',
			 'Red' => 'White'
			);
		
		return $retorno;
	}
	
}
?>