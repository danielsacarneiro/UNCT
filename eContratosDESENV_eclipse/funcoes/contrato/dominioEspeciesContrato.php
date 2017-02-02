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
                "05" => "Termo de Cess�o de Uso",
                "06" => "Termo de Rerratifica��o",
                "07" => "Termo de Coopera��o",
                "08" => "Termo de Convalida��o",
                "09" => "Termo de Rescis�o Amig�vel",
                "10" => "Termo de Rescis�o Unilateral",
                "11" => "Termo de Rescis�o Encerramento",
				);
	}
    
	function getDominioImportacaoPlanilha() {
        //cooperacao e convalidacao seram considerados como MATER                
        	$array = array(
				"01" => "Mater*Conv�nio*Coopera��o*Convalida��o",
                "02" => "Apostilamento*Apostuilamento",
				"03" => "T.A",
                "04" => "Ajuste",
                "05" => "Cess�o",                
                "06" => "Rerratifica��o",
                "09" => "Amig�vel",
                "10" => "Unilateral",
                "11" => "Encerramento"
				);
            
            return $array;
	}	
}

?>