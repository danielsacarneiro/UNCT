<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

//inicia os parametros
inicio();

error_reporting(E_ALL);
set_time_limit(0);

?>
<html>
<head>

<title>IMPORTAR PLANILHA #01</title>

</head>
<body>

<?php

include caminho_wordpress.'/excel/Classes/PHPExcel.php';
include caminho_wordpress.'/excel/Classes/PHPExcel/Writer/Excel2007.php';
include caminho_wordpress.'/excel/Classes/PHPExcel/IOFactory.php';
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos."vopessoa.php");
include_once(caminho_vos."dbpessoavinculo.php");
include_once(caminho_vos."vopessoavinculo.php");

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

$dbprocesso = new dbpessoa(null);
$dbvinculo = new dbpessoavinculo(null);

//PRIMEIRO REGISTRO NA PLANILHA TA NA LINHA 6
$inicio = 6;
// o cdPessoa eh iniciado pelo banco
//na importacao nao ha registro algum na tabela
$cdPessoa = $inicio - $inicio +1;

for ($k=$inicio; $k<=$totalResultado; $k++) {
//for ($k=1; $k<=$totalResultado; $k++) {
		
		$linha = $sheetData[$k];
        
        if($linha["A"] == "FIM")
            break;
        
        try{
            $result = $dbprocesso->importar($linha);
            
            $vopessoavinculo = new vopessoavinculo();
            //vinculo responsavel
            $vopessoavinculo->cd = 1;
            $vopessoavinculo->cdPessoa = $cdPessoa;
            $result = $dbvinculo->incluir($vopessoavinculo);
            
            $cdPessoa = $cdPessoa +1;
            
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