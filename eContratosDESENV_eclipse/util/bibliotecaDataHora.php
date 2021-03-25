<?php
include_once("mensagens.class.php");
include_once("dominioFeriados.php");
include_once("bibliotecaSQL.php");
include_once("selectExercicio.php");
include_once("dominioMeses.php");

function getDataHtmlDoFinaldoTexto($texto) {
	//limpa a string
	$retorno = str_replace(" ", "", $texto);
	$retorno = str_replace("'", "", $retorno);
	$retorno = str_replace(".", "/", $retorno);
	$retorno = str_replace("-", "/", $retorno);
	$tamanho = strlen($retorno);
	
	//verifica se o ano tem 4 digitos
	$ano = substr($retorno, $tamanho-4, 4);
	if(!is_numeric($ano)){
		$ano = substr($retorno, $tamanho-2, 2);
		//transforma em 4 digitos
		$ano =  $ano + 2000;
		
		$retorno = substr($retorno, $tamanho-8, 6) . $ano;
	}else{	
		$retorno = substr($retorno, $tamanho-10, 10);
	}

	return $retorno;
}
	
function checkDataValida($dataHtml) {
	$retorno = false;
	if(isAtributoValido($dataHtml)){
		$dataHtml= getVarComoDataSQL($dataHtml);
		$dataHtml = str_replace("'", "", $dataHtml);
		try{
			$tamanho = strlen($dataHtml);
			//o formato data deve ser 0000-00-00 ou 00-00-00
			if(!($tamanho == 8 || $tamanho == 10)){
				return false;
			}
			
			$date = new DateTime($dataHtml);
		}catch(Exception $ex){
			return false;
		}

		$ano = $date->format("Y");
		$mes = $date->format("m");
		$dia = $date->format("d");		
		//echoo("dia $dia mes $mes ano $ano");			
		if (is_numeric ( trim ( $dia ) ) && is_numeric ( trim ( $mes ) ) && is_numeric ( trim ( $ano ) )) {
			$retorno = checkdate ( $mes, $dia, $ano );
		}
	}
	
	return $retorno;
}


function getDataHora($dataSQL) {
	return getDataHoraParam($dataSQL, true);
}

function getData($dataSQL) {
	return getDataHoraParam($dataSQL, false);
}

function isDataValidaNaoVazia($dataSQL, $temHora=false) {
	$retorno = $dataSQL != null && $dataSQL != "" && $dataSQL != "0000-00-00";
	return $retorno;
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

function getQtdDiasEntreDatas($dataini, $datafim) {
	try{
		$dataini = getDataSQL($dataini);
		$datafim = getDataSQL($datafim);
		//media determinada para facilitar o calculo, considerando que ha anos bissextos e meses que nao tem 30 dias
		/*$numMediaDiasMes = 30;

		$date = new DateTime($dataini);
		$diferenca = $date->diff(new DateTime($datafim));
		$diferenca_mostra_anos = $diferenca->format('%Y')*12;
		$diferenca_mostra_meses = $diferenca->format('%m');
		$diferenca_mostra_dias = $diferenca->format('%d');
		$total_dias = ($diferenca_mostra_anos+$diferenca_mostra_meses)*$numMediaDiasMes+$diferenca_mostra_dias;*/
		
		// Calcula a diferença em segundos entre as datas
		$diferenca = strtotime($datafim) - strtotime($dataini);
		//Calcula a diferença em dias
		$total_dias = floor($diferenca/(60*60*24));
	}catch (Exception $ex){
		echo "Erro ao calcular. Verifique as datas do período.";
		$total_dias = 0;
	}

	return $total_dias;
}

function getQtdMesesEntreDatas($pdataini, $pdatafim) {
	try{
		$dataini = getDataSQL($pdataini);
		$datafim = getDataSQL($pdatafim);
		//media determinada para facilitar o calculo, considerando que ha anos bissextos e meses que nao tem 30 dias
		$numMediaDiasMes = 30;
		
		$date = new DateTime($dataini); 
		$diferenca = $date->diff(new DateTime($datafim)); 
		$diferenca_mostra_anos = $diferenca->format('%Y')*12;
		$diferenca_mostra_meses = $diferenca->format('%m');	
		$diferenca_mostra_dias = $diferenca->format('%d');
		//$total_dias = ($diferenca_mostra_anos+$diferenca_mostra_meses)*$numMediaDiasMes+$diferenca_mostra_dias;
		//echoo($total_dias);
		
		$total_dias = getQtdDiasEntreDatas($pdataini, $pdatafim);
		
		$total_meses = round(($total_dias/$numMediaDiasMes), 0, PHP_ROUND_HALF_UP); //funcao para arredondar para cima
		
		/*echo "data inicial $dataini e data final $datafim";
		echo "|total meses: $total_meses <br>";*/
		//$total_meses = $diferenca_mostra_anos+$diferenca_mostra_meses;
	}catch (Exception $ex){
		echo "Erro ao calcular. Verifique as datas do período.";
		$total_meses = 0;
	}
	
	return $total_meses;
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

function getAnoData($dataHtml){
	$dataSQL = getVarComoData($dataHtml);
	$dataSQL = str_replace("'", "", $dataSQL);
	$date = new DateTime($dataSQL);
	return $date->format("Y");
}

function getUltimoDiaMes($mes, $ano=null){
	if($ano == null){
		$ano = date("Y"); // Ano atual
	}
	$mes = complementarCharAEsquerda($mes, "0", 2);
	$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
	return $ultimo_dia;
}

function getDataMesHtml($dia, $mes, $ano=null){
	if($ano == null){
		$ano = date("Y"); // Ano atual
	}
	$dia = complementarCharAEsquerda($dia, "0", 2);
	$mes = complementarCharAEsquerda($mes, "0", 2);
	$date = "$dia/$mes/$ano";	
	return $date;
}

function getDataUltimoDiaMesHtml($mes, $ano=null){
	return getDataMesHtml(getUltimoDiaMes($mes, $ano), $mes, $ano);
}

?>