<!DOCTYPE html>
<html lang="pt-BR">
<?php
include_once("../../config_lib.php");

error_reporting(E_ALL);
set_time_limit(0);

?>
<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

<title>IMPORTAR PLANILHA #01</title>

</head>
<body>

<?php

header('Content-Type: text/html; charset=utf-8',true);

include caminho_wordpress.'/excel/Classes/PHPExcel.php';
include caminho_wordpress.'/excel/Classes/PHPExcel/Writer/Excel2007.php';
include caminho_wordpress.'/excel/Classes/PHPExcel/IOFactory.php';
include_once(caminho_vos."dbgestorpessoa.php");

$inputFileName = caminho.'planilha/UNCT_gestores.xlsx';
//$inputFileName = 'planilha/dataPublicacao.xlsx';
echo 'Lendo planilha ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

echo '<hr />';

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$totalResultado = count($sheetData);
//$totalResultado = 15;

echo "A planilha tem " . $totalResultado . " linhas <br>";		
echo "iMPORTANDO... <br><br>";

$dbprocesso = new dbgestorpessoa(null);

for ($k=6; $k<=$totalResultado; $k++) {
//for ($k=1; $k<=$totalResultado; $k++) {
		
		$linha = $sheetData[$k];
        
        if($linha["A"] == "FIM")
            break;
        
        try{
            $result = $dbprocesso->importar($linha);
        }catch(Exception $e){
            $msgErro = $e->getMessage();
            echo $msgErro;
        }
		
		if(!$result){
			echo "<br> --- REGISTRO $k: ---";		
			imprimeLinha($linha);
		}
				
		echo "linha registro" . $k . " <BR>";
}
    
echo "FIM... <br><br>";

$dbprocesso->finalizar();

function imprimeLinha($linha){
	$totalResultado = count($linha);
	$chaves = array_keys($linha);
	
	echo " --- VALORES PARA CADA LINHA---: <br>";
	for ($i=0; $i<$totalResultado; $i++) {
		$cd = $chaves[$i];
		$ds = $linha[$cd];
			
		echo $ds . ",";		
	}	
}

?>
<body>
</html>