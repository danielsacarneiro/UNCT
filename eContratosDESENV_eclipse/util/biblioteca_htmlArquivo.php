<?php
include_once("../../config_lib.php");
include_once 'bibliotecaFuncoesPrincipal.php';

Class arquivo{

	var $nome = "";
	var $nomeSemExtensao = "";
	var $isPasta = false;
	var $dir = "";

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
	var $filtrarFilhos = true;
	var $pathinfo;
	var $dir = "";
	static $barra = "\\\\";
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
	
	function setDir($caminho){
		$this->dir = $caminho;
		$this->pathinfo = pathinfo($this->dir);		
	}
	
	function getPasta(){
		return $this->pathinfo["dirname"];
	}
	
}

function incluirArquivo($nmMenuPai, $item){

	$nmclass = "'treelinkarquivo'";
	$linkParametrosComplementares = "'','',false,'','',".$nmclass.",''";
	//$linkParametrosComplementares = $nmclass;
	//$javaScript = "'javascript:alert(0);'";
	//$javaScript = "'". $nmMenuPai->nome . "\" . $item->nome . "'"
	//$javaScript = "'". realpath($nmMenuPai->getPasta() . "/" . $item->nome) . "'";
	$javaScript = "'". $nmMenuPai->getPasta() . pasta::$barra . $item->nome . "'";

	$objArquivo = "new Link('" . $item->nome . "', " . $javaScript . " , $linkParametrosComplementares)";

	echo $nmMenuPai->nomeObj . ".adicionarItem(" . $objArquivo . ");\n";
}

function criaMenu($item, $isMenuRaiz){	
	$menuRaiz = "";
	if($isMenuRaiz)
		$menuRaiz = ",true";

	echo $item->nomeObj . " = new Tree( '" .$item->nome. "'" . $menuRaiz . ");\n";
	
}

function montarColecaoItens($pastaMenuPai){
	$indice = $pastaMenuPai->indice;
	$dir = new FilesystemIterator($pastaMenuPai->dir);	
	$filtro = $pastaMenuPai->filtro;
	//$filter = new RegexIterator($dir, '/.(php|dat)$/');
	//$filter = new RegexIterator($dir, '/2d/');	
	//echo "pasta que executou o montarColecaoItens:". $enderecoPasta. "\n";	
	$strFiltro = $filtro->contratada;

	$i = 0;
	$retorno = "";
	// atribui o valor de $dir para $file em loop
	foreach($dir as $file){
		// verifica se o valor de $file é diferente de '.' ou '..'
		// e é um diretório (isDir)
		if ($file->isDir() || $file->isFile()){
			$dname = $file->getFilename();

			//verifica se deve filtrar o nome dos filhos
			//pega apenas os arquivos que satisfazem o filtro
			if(!$pastaMenuPai->filtrarFilhos || existeStr1NaStr2ComSeparador($dname, $strFiltro)){			
				//$item->isPasta = $file->isDir();
				
				$enderecoPasta = $pastaMenuPai->dir; 
				if ($file->isDir()){
					$item = new pasta($dname,$filtro, ++$indice);
					//se o item foi encontrado, eh pq seus filhos devem ser considerados
					//mesmo que eles nao tenham satisfacam o filtro
					//isto permite trazer os arquivos que nao satisfacam o filtro, mas pertencam ao pai que satisfacam
					$item->filtrarFilhos = false;
					$item->setDir($enderecoPasta.pasta::$barra.$item->nome);
					
				}else{
					$item = new arquivo($dname, ++$indice);
					$item->dir=$enderecoPasta;
				}
				$retorno[$i] = $item;
				$i++;
			}
		}
		//echo $i;
	}
	$pastaMenuPai->filtro->numTotalRegistros = $i;

	return $retorno;
}


function geraArvoreMenu($pastaMenuPai){	
	
	$colecao = montarColecaoItens($pastaMenuPai);
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
			geraArvoreMenu($item);
		}
		else{
			incluirArquivo($pastaMenuPai, $item);
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