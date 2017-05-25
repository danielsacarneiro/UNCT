<!DOCTYPE html>
<?php
include_once("../../config_lib.php");

error_reporting(E_ALL);
set_time_limit(0);

?>
<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

<title>IMPORTAR PLANILHA CONTRATOS - C-SAFI</title>

</head>
<body>

<?php

header('Content-Type: text/html; charset=utf-8',true);

include_once caminho_wordpress.'excel/Classes/PHPExcel.php';
include_once caminho_wordpress.'excel/Classes/PHPExcel/Writer/Excel2007.php';
include_once caminho_wordpress.'excel/Classes/PHPExcel/IOFactory.php';
include_once(caminho_vos."dbcontrato.php");
include "include_importarConvenio.php";

$inputFileName = caminho.'planilha/UNCT_contrato.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

/*$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("test.xlsx");*/

echo 'Lendo planilha ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br />';
echo '<hr />';

$totalResultado = count($sheetData);
//$totalResultado = 15;

echo "A planilha tem " . $totalResultado . " linhas <br>";		
echo "iMPORTANDO... <br><br>";

$dbprocesso = new dbcontrato(null);
	
$tipoContrato = "C";

for ($k=6; $k<=$totalResultado; $k++) {
		
		$linha = $sheetData[$k];
        
        if($linha["A"] == "FIM")
            break;        
        
        try{
        	$linkDoc = $objPHPExcel->getActiveSheet()->getCell('B'.$k)->getHyperlink()->getUrl();
        	$linha[vocontrato::$nmAtrLinkDoc] = $linkDoc;
        	 
        	//echo $linha[vocontrato::$nmAtrDocLink];        	     
            $result = $dbprocesso->incluirContratoImport($tipoContrato, $linha);
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

//libera memoria
unset($objPHPExcel);
unset($sheetData);

//atualiza as contratadas
echo "<br><br>Atualizando CNPJ das contratadas.<br><br>";
$dbprocesso->atualizarPessoasContrato();
    
echo "FIM... <br><br>";

$dbprocesso->finalizar();


//importarConvenio("V");
//importarConvenio("P");

?>
<body>
</html>