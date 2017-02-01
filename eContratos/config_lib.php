<?php
function getPastaRoot(){
    $aplicacao = "UNCT/eContratos";
    $ambiente = "";
    $arquivo = 'PROD'; 
    if (!file_exists($arquivo)) {
        $aplicacao.= "DESENV_eclipse";
    } 
    
    $path = $_SERVER['DOCUMENT_ROOT'];      
    $path .= "/wordpress/";
    define('caminho_wordpress', $path);
    
    $path .= $aplicacao;
    //include_once($path);
    
    return $path;
}

header ('Content-type: text/html; charset=ISO-8859-1');

$base = getPastaRoot();
define('caminho', $base."/");
define('caminho_lib', "$base/lib/");
define('caminho_util', "$base/util/");
define('caminho_vos', "$base/vos/");
define('caminho_filtros', "$base/filtros/");
define('caminho_funcoesHTML', "funcoes/");
define('caminho_funcoes', "$base/funcoes/");

include_once(caminho_util. "bibliotecaFuncoesPrincipal.php");

$isPastaRaiz  = isPastaRaiz();

$pastaRaiz = "../../";
$caminhoJS = "lib/js/";
$caminhoCSS = "lib/css/";
$caminhoIMG = "imagens/";

$caminhoMenu = "";

if(!$isPastaRaiz){    
    $caminhoJS = $pastaRaiz . $caminhoJS;
    $caminhoCSS = $pastaRaiz . $caminhoCSS;
    $caminhoIMG = $pastaRaiz . $caminhoIMG;
    
    $caminhoMenu = $pastaRaiz;
}

define('caminho_menu', $caminhoMenu);

//html
define('caminho_css', $caminhoCSS);
define('caminho_js', $caminhoJS);
define('caminho_imagens', $caminhoIMG);

//variaveis HTML
define('TAMANHO_CODIGOS', 5);
define('CAMPO_SEPARADOR', "*");

?>
