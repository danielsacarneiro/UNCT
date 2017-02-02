<?php
include_once("../../config_lib.php");
include_once 'bibliotecaFuncoesPrincipal.php';

Class arquivo{

	var $nome = "";
	var $nomeSemExtensao = "";
	var $isPasta = false;

	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function __construct($nome, $indice) {
		$this->nome = $nome;
		$this->indice = $indice;
		$this->nomeObj = "obj".$indice;
		$indicePonto = getIndiceBarraOuPonto($nome);
		if($indicePonto != null)
			$this->nomeSemExtensao =  substr($nmPasta,0,$indicePonto);
	}

}

Class pasta{

	var $nome = "";
	var $colecaoItens = "";
	var $isPasta = true;
	var $filtro; 
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )

	function __construct($nmPasta, $filtro, $indice) {
		$this->nomeAExibir = str_replace(".", "_", $nmPasta);
		$this->nome = $nmPasta;
		//$this->nomeObj = $this->nomeAExibir.$indice;
		$this->nomeObj = "obj".$indice;
		$this->indice = $indice;
		
		$this->filtro = $filtro;
	}
				
}

function incluirArquivo($nmMenuPai, $item){

	$nmclass = "'treelinkarquivo'";
	$linkParametrosComplementares = "'','','','','',".$nmclass.",''";
	//$linkParametrosComplementares = $nmclass;
	$javaScript = "'javascript:alert(0);'";

	$objArquivo = "new Link('" . $item->nome . "', " . $javaScript . " , $linkParametrosComplementares)";

	echo $nmMenuPai . ".adicionarItem(" . $objArquivo . ");\n";
}

function criaMenu($item, $isMenuRaiz){	
	$menuRaiz = "";
	if($isMenuRaiz)
		$menuRaiz = ",true";

	echo $item->nomeObj . " = new Tree( '" .$item->nome. "'" . $menuRaiz . ");\n";
	
}

function montarColecaoItens($pastaMenuPai, $enderecoPasta){
	$indice = $pastaMenuPai->indice;
	$dir = new FilesystemIterator($enderecoPasta);	
	$filtro = $pastaMenuPai->filtro;
	//$filter = new RegexIterator($dir, '/.(php|dat)$/');
	$filter = new RegexIterator($dir, '/2d/');
	
	//echo "pasta que executou o montarColecaoItens:". $enderecoPasta. "\n";

	$i = 0;
	$retorno = "";
	// atribui o valor de $dir para $file em loop
	foreach($dir as $file){
		// verifica se o valor de $file é diferente de '.' ou '..'
		// e é um diretório (isDir)
		if ($file->isDir() || $file->isFile()){
			$dname = $file->getFilename();
			//$item->isPasta = $file->isDir();
			if ($file->isDir()){
				$item = new pasta($dname,$filtro, ++$indice);
			}else{
				$item = new arquivo($dname, ++$indice);
			}
			$retorno[$i] = $item;
			$i++;
		}
		//echo $i;
	}

	return $retorno;
}


function geraArvoreMenu($pastaMenuPai, $enderecoPasta){	
	
	$colecao = montarColecaoItens($pastaMenuPai, $enderecoPasta);
	if (is_array($colecao)){
		$tamanho = sizeof($colecao);
	}
	else {
		$tamanho = 0;
	}
	
	for ($i=0;$i<$tamanho;$i++) {
		$item = $colecao[$i];
	
		if($item->isPasta){
			criaMenu($item, false);
			echo $pastaMenuPai->nomeObj . ".adicionarItem(" . $item->nomeObj . ");\n";			
			geraArvoreMenu($item, $enderecoPasta."/".$item->nome);
		}
		else{
			incluirArquivo($pastaMenuPai->nomeObj, $item);
		}
	}
	
}

//Essa função gera um valor de String aleatório do tamanho recebendo por parametros
function randString($size){
	//String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
	$basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	$return= "";

	for($count= 0; $size > $count; $count++){
		//Gera um caracter aleatorio
		$return.= $basic[rand(0, strlen($basic) - 1)];
	}

	return $return;
}




?>