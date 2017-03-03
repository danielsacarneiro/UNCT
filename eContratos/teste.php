<?php  
include_once("config_lib.php");
include_once(caminho_vos."dbcontrato.php");
include_once(caminho_util."bibliotecaFuncoesPrincipal.php");
   
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

include_once(caminho_vos."dbpessoavinculo.php");
include_once(caminho_vos."vopessoavinculo.php");
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos."vopessoa.php");

include_once(caminho_util."DocumentoPessoa.php");

$vo = new vopessoa();
//var_dump($vo->getTodosAtributos());
//echo "<br>";

$db = new dbcontrato();
echo $db->atualizarPessoasContrato();


/*$a=array("a"=>"5","b"=>5,"c"=>"5");
$a=array("a", "b", "c");
echo array_search("c",$a);*/

/*$array = array(vopessoa::$nmAtrCdUsuarioInclusao);

$vo->varAtributos = removeColecaoAtributos($vo->varAtributos, $array);

var_dump($vo->varAtributos);*/


?>