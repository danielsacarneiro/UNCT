<?php  
include_once("config_lib.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");
include_once(caminho_util."bibliotecaDataHora.php");
   

$data = "28/09/2017";
echo "$data <br>";

//echo somarDiasUteisNaData($data, 1);

if(isFeriado($data)){
	echo "eh feriado";
}
else{ 
	echo "nao eh feriado";
}

$datafim = somarDiasUteisNaData($data, 10);
echo "<br> $datafim <br>"
?>

<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
