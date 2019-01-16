<!DOCTYPE html>
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

<title>IMPORTAR PLANILHA CONTRATOS - C-SAFI</title>
</head>
<body>

<?php

header('Content-Type: text/html; charset=utf-8',true);

$isLimparContrato = getAtributoComoBooleano(@$_GET[dbcontrato::$ID_REQ_INICIAR_TAB_CONTRATO]);
if(!$isLimparContrato){
	include_once caminho_wordpress.'excel/Classes/PHPExcel.php';
	include_once caminho_wordpress.'excel/Classes/PHPExcel/Writer/Excel2007.php';
	include_once caminho_wordpress.'excel/Classes/PHPExcel/IOFactory.php';
	include_once(caminho_vos."dbcontrato.php");
	include "include_importarConvenio.php";
	
	//$inputFileName = caminho.'planilha/UNCT_contrato.xlsx';
	$inputFileName = caminho."planilha/".dbcontrato::$NM_ARQUIVO_PLANILHA_CONTRATOS;
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$objPHPExcel->setActiveSheetIndexByName(dbcontrato::$NM_PLANILHA_CONTRATOS);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
	echoo("IMPORTAÇÃO DE CONTRATOS INICIADA.");
	echo 'Lendo planilha ',pathinfo($inputFileName,PATHINFO_BASENAME),'<br />';
	echo '<hr />';
	
	$totalResultado = count($sheetData);
	//$totalResultado = 15;
	
	if($totalResultado <= 0){
		echoo("Planilha vazia ou não encontrada.");
	}else{	
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
		        	//echoo("link doc importar.php:$linkDoc");		        	
	
		        	$linkMinutaDoc = $objPHPExcel->getActiveSheet()->getCell('C'.$k)->getHyperlink()->getUrl();
		        	$linha[vocontrato::$nmAtrLinkMinutaDoc] = $linkMinutaDoc;
		        	
		        	//echo $linha[vocontrato::$nmAtrDocLink];        	     
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
		
		//libera memoria
		unset($objPHPExcel);
		unset($sheetData);
		
		//atualiza as contratadas
		echo "<br><br>Atualizando CNPJ das contratadas.<br><br>";
		$dbprocesso->atualizarPessoasContrato();
		//$dbprocesso->removerCaracterEspecial();	
		$dbprocesso->finalizar();
	}
	
}else{
	$dbcontrato = new dbcontrato();
	$dbcontrato->iniciarTabelaContrato();
	echoo("TABELA CONTRATO INICIADA.");
}
    
echo "FIM... <br><br>";

//importarConvenio("V");
//importarConvenio("P");

?>
<body>
</html>