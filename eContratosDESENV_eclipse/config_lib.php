<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
/*ini_set('log_errors', 1);
ini_set('display_startup_erros',1);*/

//mysqli_report(MYSQLI_REPORT_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

date_default_timezone_set('America/Recife');
setlocale(LC_ALL, 'portuguese');
//setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
set_exception_handler("pegaExcecaoSemTratamento");

//função definida pelo usuário para pegar exceções não tratadas
function pegaExcecaoSemTratamento($e){
	//echo 'Exceção pega sem tratamento:</br>', $e->getMessage(), '</br></br></br>';
	throw new Exception($e->getMessage());
}

/*set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	
	if (2 === error_reporting()) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}	


	//throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});*/

function isClasseFrameWork($class_name, $tipoClasse){	
	$isClasseFramework = false;
	$pos = strpos($class_name, $tipoClasse);
	if($pos !== false && $pos == 0){
		$isClasseFramework = true;
	}
	
	return $isClasseFramework;	
}

/*a funcao abaixo serve para incluir a classe usada na confirmacao
 * o session precisa identificar qual classe ele serializa
 * dai o include
 */
spl_autoload_register(function ($class_name) {
	$caminhoClasse = caminho_vos;	
	$pos = stripos($class_name, "filtro");	
	if($pos !== false && $pos == 0){
		//eh classe filtro
		$caminhoClasse = caminho_filtros;
	}else{
		//poderia tambem usar o stripos (mas quis demonstrar como cria uma nova funcao parametrizavel)
		$needle = "Excecao";
		$pos = getMultiPos($class_name, array($needle), false);
		$pos = $pos[$needle];
		if($pos !== false && $pos == 0){			
			//eh classe EXCECAO
			$caminhoClasse = caminho_excecoes;
		}		
	}
		
	$isClasseFramework = isClasseFrameWork($class_name, "vo") || isClasseFrameWork($class_name, "filtro") || isClasseFrameWork($class_name, "excecao") || isClasseFrameWork($class_name, "db");
	
	//ECHO $class_name;
	if($isClasseFramework){
		//echo "ACHOU";
		include_once $caminhoClasse.$class_name . '.php';
	}/*else{
		echo "NAO ACHOU";
	}*/
	
});

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
define('caminho_excecoes', "$base/excecoes/");
define('caminho_funcoesHTML', "funcoes/");
define('caminho_funcoes', "$base/funcoes/");
define('url_sistema', 'http://sf300451/wordpress/UNCT/eContratosDesenv_eclipse/');

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

//define uma variavem javascript para que as imagens em js sejam recuperadas corretamente
$varGlobalJS = 
"<script type='text/javascript'>\n"
	. "var _pastaImagensGlobal = '" . caminho_imagens ."';\n"
	. "</script>\n";

echo $varGlobalJS;

include_once (caminho_util."constantes.class.php");
//variaveis HTML
define('TAMANHO_CODIGOS', constantes::$TAMANHO_CODIGOS);
define('TAMANHO_CODIGOS_SAFI', constantes::$TAMANHO_CODIGOS_SAFI);
define('CAMPO_SEPARADOR', constantes::$CD_CAMPO_SEPARADOR);
define('CAMPO_SUBSTITUICAO', constantes::$CD_CAMPO_SUBSTITUICAO);

?>
