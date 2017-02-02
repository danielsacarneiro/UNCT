<?php
include_once(caminho_util. "dominio.class.php");

  Class dominioEspeciesContrato extends dominio{
    
// ...............................................................
// Construtor
	function __construct () {
        		$this->colecao = array(
				"01" => "Mater",
                "02" => "Apostilamento",
				"03" => "Termo Aditivo",
                "04" => "Termo de Ajuste",
                "05" => "Termo de Cessуo de Uso",
                "06" => "Termo de Rerratificaчуo",
                "07" => "Termo de Cooperaчуo",
                "08" => "Termo de Convalidaчуo",
                "09" => "Termo de Rescisуo Amigсvel",
                "10" => "Termo de Rescisуo Unilateral",
                "11" => "Termo de Rescisуo Encerramento",
				);
	}
    
	function getDominioImportacaoPlanilha() {
        //cooperacao e convalidacao seram considerados como MATER                
        	$array = array(
				"01" => "Mater*Convъnio*Cooperaчуo*Convalidaчуo",
                "02" => "Apostilamento*Apostuilamento",
				"03" => "T.A",
                "04" => "Ajuste",
                "05" => "Cessуo",                
                "06" => "Rerratificaчуo",
                "09" => "Amigсvel",
                "10" => "Unilateral",
                "11" => "Encerramento"
				);
            
            return $array;
	}	
}

?>