<?php  
include_once("config_lib.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."bibliotecaDataHora.php");
   

/*$_SESSION["dois"]["teste"] = "daniel";
echo $_SESSION["dois"]["teste"];*/
$teste = "444444444555698a36";
$format = voDemandaTramitacao::getNumeroPRTComMascara($teste, true);
$formatSem = voDemandaTramitacao::getNumeroPRTSemMascara($teste, true);

echoo($format);
echoo($formatSem);

?>

<html>
<head>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
