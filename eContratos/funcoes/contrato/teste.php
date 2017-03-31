<!DOCTYPE html>
<html lang="pt-BR">
<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");
//inicio();

error_reporting(E_ALL);
set_time_limit(0);

?>
<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

<title>TESTE PLANILHA EXCEL</title>

</head>
<body>

<?php

header('Content-Type: text/html; charset=utf-8',true);

include caminho_wordpress.'excel/Classes/PHPExcel.php';
include caminho_wordpress.'excel/Classes/PHPExcel/Writer/Excel2007.php';
include caminho_wordpress.'excel/Classes/PHPExcel/IOFactory.php';
include_once(caminho_vos."dbcontrato.php");

$inputFileName = caminho.'planilha/UNCT_teste.xlsx';

$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

echo 'Lendo planilha ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br />';
echo '<hr />';

$totalResultado = count($sheetData);
//$totalResultado = 15;

echo "A planilha tem " . $totalResultado . " linhas <br>";		
echo "iMPORTANDO tipo $tipoContrato ... <br><br>";

$dbprocesso = new dbcontrato(null);

//var_dump($objPHPExcel->getActiveSheet()->getCell('B3')->getHyperlink());
//var_dump($objPHPExcel->getActiveSheet()->getCell('B3')->getHyperlink()->getUrl());
//echo $objPHPExcel->getActiveSheet()->getCell('B3')->getHyperlink()->getUrl();

$documento = new documentoPessoa();
$retorno->docContratada = $documento->getNumDoc();

for ($k=6; $k<=$totalResultado; $k++) {

	$linha = $sheetData[$k];
	$VALOR = $linha["O"];
	
	ECHO "VALOR ENCONTRADO:" . $VALOR . "<BR>";
	ECHO "VALOR PROCESSADO:" . getMoedaMascaraImportacao($VALOR) . "<BR>";

	if($linha["A"] == "FIM")
		break;

	echo "linha registro" . $k . " <BR>";
}


    
echo "FIM... <br><br>";

$dbprocesso->finalizar();

?>
<body>
</html>