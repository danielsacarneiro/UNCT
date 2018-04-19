<?php
include_once("mensagens.class.php");
include_once("dominioFeriados.php");
include_once("bibliotecaSQL.php");

function getDataHora($dataSQL) {
	return getDataHoraParam($dataSQL, true);
}

function getData($dataSQL) {
	return getDataHoraParam($dataSQL, false);
}

function getDataHoraParam($dataSQL, $temHora) {
	$retorno = null;
	if ($dataSQL != null){
		$dataSQL = str_replace("/", "-", $dataSQL);
		if($dataSQL == "0000-00-00"){
			//$retorno = mensagens::$msgDataErro;
			$retorno = "";
		}else if ($dataSQL != null && $dataSQL != "0000-00-00"){
			$retorno = date("d/m/Y", strtotime($dataSQL));				
			if($temHora){
				$retorno .= " " . date("H:i:s", strtotime($dataSQL));
			}
		}
	}
	return $retorno;
}
    
function getDataHoraSQLComoString($data) {
	//pega qualquer data e transforma no formato SQL	
	$retorno = "";
	if ($data != null){
		$retorno = getDataHora($data);
		/*$retorno = date("d/m/Y", strtotime($data)) . " ";
        $retorno .= date("H:i:s", strtotime($data));*/
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
	
	//echo "Intervalo Ã© de {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias";
			
	return $retorno*$fator;
}

function getDataContagemPrazoFinal($dtinicio, $prazo, $isDiasUteis=true) {
	//o prazo comeca a contar do primeiro dia util seguinte
	$dtinicio = somarOuSubtrairDias($dtinicio, 1, "+", true);
		
	$retorno = somarOuSubtrairDias($dtinicio, $prazo-1, "+", $isDiasUteis);
	if(!isDiaUtil($retorno)){
		$retorno = somarOuSubtrairDias($retorno, 1, "+", true);
	}
	return $retorno;
}

function isDiaUtil($data) {
	return  isDiaDaSemana($data) && !isFeriado($data);	
}
function isDiaDaSemana($data) {
	$data = getDataSQL($data);	
	$format = 'Y-m-d';
	$dt = DateTime::createFromFormat($format, $data);	
		 
	if ((date_format($dt, 'N') === '6') || (date_format($dt, 'N') === '7')) {
		return false;
	}else{
		return true;
	}
}

function somarOuSubtrairDias($dataHTML, $count_days, $operacao ="+", $isDiasUteis=true){
	if($isDiasUteis)
		return somarOuSubtrairDiasUteisNaData($dataHTML,$count_days, $operacao);
	else
		return somarOuSubtrairDiasNaData($dataHTML, $count_days, $operacao);
}

function somarOuSubtrairDiasNaData($dataHTML, $count_days, $operacao ="+"){
	$dataHTML = getData($dataHTML);
	$dataHTML = str_replace("/", "-", $dataHTML);

	return gmdate('d/m/Y',strtotime($operacao.$count_days.' day',strtotime($dataHTML)));
}

function somarOuSubtrairDiasUteisNaData($str_data,$int_qtd_dias_somar = 7, $operacao = "+") {
	$str_data = substr($str_data,0,10);

	if ( preg_match("@/@",$str_data) == 1 ) {
		$str_data = implode("-", array_reverse(explode("/",$str_data)));
	}

	$array_data = explode("-", $str_data);
	$count_days = 0;
	$int_qtd_dias_uteis = 0;

	while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {
		$count_days++;
		$dtAcomparar = gmdate('d/m/Y',strtotime($operacao.$count_days.' day',strtotime($str_data)));
		$dias_da_semana = gmdate('w', strtotime($operacao.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0])));

		//echo $dtAcomparar . "<br>";

		//if ( ( $dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0'
		if ( $dias_da_semana != '0'
				&& $dias_da_semana != '6'
				&& !isFeriado($dtAcomparar)) {
						
					$int_qtd_dias_uteis++;
				}
	}

	return gmdate('d/m/Y',strtotime($operacao.$count_days.' day',strtotime($str_data)));
}

function somarDiasUteisNaData($str_data,$int_qtd_dias_somar = 7) {
	return somarOuSubtrairDiasUteisNaData($str_data,$int_qtd_dias_somar, "+");
}

function isFeriado($data){	
	if($data == null || $data == "")
		throw new excecaoGenerica("Data inválida || verificacao feriado");
	
	$data = str_replace("/", "-", $data);	
	$acomparar = date('d/m', strtotime($data));
	
	//echo $acomparar;	
	
	return in_array($acomparar, dominioFeriados::getColecao());
	
}

function getAnoHoje(){
	return date ( 'Y' );
}

?>