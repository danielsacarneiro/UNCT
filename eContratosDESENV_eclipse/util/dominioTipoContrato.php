<?php
include_once("dominio.class.php");

  Class dominioTipoContrato extends dominio{

// ...............................................................
// Construtor
	function __construct () {        
		$this->colecao = array(				   
				   constantes::$CD_TIPO_CONTRATO => constantes::$DS_TIPO_CONTRATO,
                   constantes::$CD_TIPO_CONVENIO => constantes::$DS_TIPO_CONVENIO                   
				   );        
	}

// ...............................................................
// Funções ( Propriedades e métodos da classe )
	
}
?>