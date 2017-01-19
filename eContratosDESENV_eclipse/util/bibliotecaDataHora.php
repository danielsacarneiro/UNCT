<?php
include_once("mensagens.class.php");
    
function getDataHoraSQLComoString($data) {
	//pega qualquer data e transforma no formato SQL	
	$retorno = "";
	if ($data != null){
		$retorno = date("d/m/Y", strtotime($data)) . " ";
        $retorno .= date("H:i:s", strtotime($data));
    }
						
	return $retorno;
}

function getDataFormatoSQL($data) {
	//pega qualquer data e transforma no formato SQL	
	$retorno = "";
	if ($data != null)
		$retorno = date("Y/m/d", strtotime($data));
						
	return $retorno;
}
	
//retorna negativo se a data inicio for maior que a data fim
function getQtdDiasEntreDatas($dataini, $datafim) {
    if($dataini == null || $datafim == null)
        throw new Exception("uma das datas nula");
    
	//usa o tipo DateTime
	$data1 = new DateTime(getDataFormatoSQL($dataini));
	$data2 = new DateTime(getDataFormatoSQL($datafim));
	
	$intervalo = $data1->diff( $data2 );
	
	$ano = $intervalo->y;
	$mes = $intervalo->m;
	$dia = $intervalo->d;
    
    $fator = 1;
    if($data1 > $data2){
        $fator = -1;
    }
	
	$retorno = 0;
	if($ano != null)
		$retorno = abs($ano)*365;

	if($mes != null)
		$retorno = $retorno  + (abs($mes)*30);

	if($dia != null)
		$retorno = $retorno  + abs($dia);
	
	//echo "Intervalo é de {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias";
			
	return $retorno*$fator;
}

?>