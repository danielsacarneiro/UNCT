<!DOCTYPE html>
<html lang="pt-BR">
<?php
include_once("../../config_lib.php");

error_reporting(E_ALL);
set_time_limit(0);

include_once(caminho_util."bibliotecaHTML.php");
inicioComValidacaoUsuario(true);

?>
<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

<title>IMPORTAR PLANILHA CONVENIO/PROFISCO</title>

</head>
<body>

<?php

header('Content-Type: text/html; charset=utf-8',true);

include_once caminho_wordpress.'excel/Classes/PHPExcel.php';
include_once caminho_wordpress.'excel/Classes/PHPExcel/Writer/Excel2007.php';
include_once caminho_wordpress.'excel/Classes/PHPExcel/IOFactory.php';
include_once(caminho_vos."dbcontrato.php");

$tipoContrato = @$_GET["tipo"];
if($tipoContrato == null || $tipoContrato == "")
	throw new Exception("selecione o tipo do contrato!");

	/*if($tipoContrato == "V"){
	 //$inputFileName = caminho.'planilha/UNCT_convenio.xlsx';
	
	 }else{
	 $tipoContrato = "P";
	 $inputFileName = caminho.'planilha/UNCT_profisco.xls';
	 }*/
	
$inputFileName = caminho."planilha/".dbcontrato::$NM_ARQUIVO_PLANILHA_CONTRATOS;
$isBuscarPlanilhaPorNome = true;
//echoo("tipo contrato: " . $tipoContrato);
if($tipoContrato != "V"){
	$tipoContrato = "P";
	$inputFileName = caminho.'planilha/CONTRATOS-PROFISCO.xls';
	$isBuscarPlanilhaPorNome = false;
	echoo("IMPORTAÇÃO DE CONTRATOS PROFISCO INICIADA.");
}else{
	echoo("IMPORTAÇÃO DE CONVÊNIOS INICIADA.");
}

$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
if($isBuscarPlanilhaPorNome){
	$objPHPExcel->setActiveSheetIndexByName(dbcontrato::$NM_PLANILHA_CONVENIOS);
}
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

echo 'Lendo planilha ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br />';
echo '<hr />';

$totalResultado = count($sheetData);
//$totalResultado = 10;
if($totalResultado <= 0){
	echoo("Planilha vazia ou não encontrada.");
}else{
	echo "A planilha tem " . $totalResultado . " linhas <br>";		
	echo "iMPORTANDO tipo $tipoContrato ... <br><br>";
	
	$dbprocesso = new dbcontrato(null);
	
	for ($k=3; $k<=$totalResultado; $k++) {
	//for ($k=1; $k<=$totalResultado; $k++) {
			
			$linha = $sheetData[$k];		
			        
	        if($linha["A"] == "FIM")
	            break;
	        
	        try{
	        	$linkDoc = $objPHPExcel->getActiveSheet()->getCell('B'.$k)->getHyperlink()->getUrl();        	
	        	$linha[vocontrato::$nmAtrLinkDoc] = $linkDoc;
	        	
	        	$linkMinutaDoc = $objPHPExcel->getActiveSheet()->getCell('C'.$k)->getHyperlink()->getUrl();
	        	$linha[vocontrato::$nmAtrLinkMinutaDoc] = $linkMinutaDoc;        	
	
	        	//echo $linha[vocontrato::$nmAtrLinkDoc];        	
	            $result = $dbprocesso->incluirContratoImport($tipoContrato, $linha);
	        }catch(excecaoFimImportacaoContrato $ex){
	           	//encerra a busca
	           	break;            
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
	
	//atualiza as contratadas
	echo "<br><br>Atualizando CNPJ das contratadas.<br><br>";
	$dbprocesso->atualizarPessoasContrato();    
	$dbprocesso->finalizar();
}

echo "FIM... <br><br>";

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