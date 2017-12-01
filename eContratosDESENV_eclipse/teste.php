<?php  
include_once("config_lib.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");
include_once(caminho_util."bibliotecaDataHora.php");
   

$_SESSION["dois"]["teste"] = "daniel";

echo $_SESSION["dois"]["teste"];

?>

<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
