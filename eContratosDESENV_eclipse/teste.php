<?php  
include_once("config_lib.php");
include_once(caminho_vos."dbcontrato.php");
   
function teste(){
    $dbteste = new dbcontrato(null);
    
    /*$sqEspecie = "0123%5";
    $indiceEspecie = $dbteste->getIndicePosteriorAoUltimoNumeroAPartirDoComeco($sqEspecie);
    
    echo $indiceEspecie;*/
    
    $param = " CONVÊNIO DE INTERCÂMBIO DE EXPERIÊNCIAS E DE INFORMAÇÕES ECONÔMICO-FISCAIS";
    $cdEspecie = $dbteste->getCdEspecieContrato($param);
    
    echo "<br>especie considerada: " . $cdEspecie;    
}

function testarNomePasta(){        
    $aplicacao = "CadastroContratos";
    $ambiente = "";
    $arquivo = 'PROD'; 
    if (!file_exists($arquivo)) {
        $aplicacao.= "DESENV";
    } 
   
   $path = $_SERVER['DOCUMENT_ROOT'];
   echo $path .= "/wordpress/$aplicacao/";
   //include_once($path);   
}


//testarNomePasta();

$dbteste = new dbcontrato();
echo $dbteste->getCdAutorizacao("ge");


?>