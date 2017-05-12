<?php
include_once("../../config_lib.php");
include_once 'bibliotecaFuncoesPrincipal.php';

Class arquivo{

	var $nome = "";
	var $nomeSemExtensao = "";
	var $isPasta = false;
	var $dir = "";
	var $pastaPai = null;

	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )
	function __construct($nome, $indice, $pastaPai) {
		$this->pastaPai = $pastaPai;
		$this->nome = $nome;
		$this->indice = $indice;
		$this->nomeObj = "obj".$indice;
		$indicePonto = getIndiceBarraOuPonto($nome);
		if($indicePonto != null)
			$this->nomeSemExtensao =  substr($nmPasta,0,$indicePonto);
	}

}

Class pasta{
	
	static $IN_NAO_FILTRAR = 1;
	static $IN_FILTRAR_TODOS= 2;
	static $IN_FILTRAR_APENAS_PAI = 2;
	static $IN_FILTRAR_APENAS_FILHO = 3;

	var $nome = "";
	var $colecaoItens = "";
	var $isPasta = true;
	var $filtro; 
	var $cdControleConsulta = false;
	var $pathinfo;
	var $dir = "";
	var $indice = "";
	var $numTotalRegistros = 0;
	var $isExibir = false;
	
	var $pastaPai = null;
	
	static $barra = "\\\\";
	// ...............................................................
	// Funcoes ( Propriedades e metodos da classe )

	function __construct($nmPasta, $filtro, $indice, $pastaPai) {
		$this->nomeAExibir = str_replace(".", "_", $nmPasta);
		$this->nome = $nmPasta;
		//$this->nomeObj = $this->nomeAExibir.$indice;
		$this->nomeObj = "obj".$indice;
		$this->indice = $indice;
		
		$this->filtro = $filtro;
		$this->cdControleConsulta = self::$IN_FILTRAR_TODOS;
		$this->pastaPai = $pastaPai;
	}
	
	function setDir($caminho){
		$this->dir = $caminho;
		$this->pathinfo = pathinfo($this->dir);		
	}
	
	/*function getPasta(){
		return $this->pathinfo["dirname"];
		//return $this->dir;
	}*/
}

function getEnderecoArquivoCorreto($endereco){
	$linkDoc = str_replace ( dominioTpDocumento::$ENDERECO_DRIVE, dominioTpDocumento::$ENDERECO_DRIVE_HTML, $endereco );
	return $linkDoc;
	
}
function getNomeArquivoCorreto($nome){
	$linkDoc = str_replace ( dominioTpDocumento::$ENDERECO_DRIVE, "", $nome );
	return $linkDoc;

}
function incluirArquivo($nmMenuPai, $item){

	$nmclass = "'treelink'";
	$linkParametrosComplementares = "'','',false,'','',".$nmclass.",'', true";

	$javaScript = "'". getEnderecoArquivoCorreto($item->dir). pasta::$barra . $item->nome . "'";
		
	$objArquivo = "new Link('" . getNomeArquivoCorreto($item->nome) . "', " . $javaScript . " , $linkParametrosComplementares)";	
	//$objArquivo = "new LinkArquivo('" . $item->nome . "', " . $javaScript . " , false, ".$nmclass.")";

	echo $nmMenuPai->nomeObj . ".adicionarItem(" . $objArquivo . ");\n";
}

function criaMenu($item, $isMenuRaiz){	
	$menuRaiz = "";
	if($isMenuRaiz)
		$menuRaiz = ",true";
	
	$nomeAexibir = $item->dir;
	if($item->indice == 1)
		$nomeAexibir = $item->nome;

	echo $item->nomeObj . " = new Tree( '" .getNomeArquivoCorreto($nomeAexibir). "'" . $menuRaiz . ");\n";
	
}

function montarColecaoItens($pastaMenuPai){
	$indice = $pastaMenuPai->indice;
	
	//$pasta = dirname(__FILE__).'/files/a*.*';
	
	$pasta = $pastaMenuPai->dir."\\\*";
	$dir = new GlobIterator($pasta);
	//echo "alert('".$pasta."');";
	
	//$dir = new FilesystemIterator($pastaMenuPai->dir);	
	//$filtro = $pastaMenuPai->filtro;
	//$filter = new RegexIterator($dir, '/.(php|dat)$/');
	//$filter = new RegexIterator($dir, '/2d/');	
	
	$strFiltro = $pastaMenuPai->filtro;
	
	//echo "alert('$strFiltro');";

	$i = 0;
	$retorno = "";
	// atribui o valor de $dir para $file em loop
	foreach($dir as $file){
		
		//echo "alert('".$i."');";
		// verifica se o valor de $file é diferente de '.' ou '..'
		// e é um diretório (isDir)
		if ($file->isDir() || $file->isFile()){
			
			//foreach(glob("$pasta*.txt") as $arquivo)
			
			$dname = $file->getFilename();
			//$dname = $file->getPathname();
			//echo "alert('".$dname."');";

			//verifica se deve filtrar o nome dos filhos
			//se cdControleConsulta = true, ele nao filtra, traz todos
			//pega apenas os arquivos que satisfazem o filtro
			$isTrazer = true;
			$inFilhos = pasta::$IN_NAO_FILTRAR;
			
			if($pastaMenuPai->cdControleConsulta != pasta::$IN_NAO_FILTRAR){
				$isTrazer = ($strFiltro == null) || existeStr1NaStr2ComSeparador($dname, $strFiltro);
				
				if($pastaMenuPai->cdControleConsulta == pasta::$IN_FILTRAR_APENAS_FILHO){
					$isTrazer = $file->isDir() || ($file->isFile() && $isTrazer);
					$inFilhos = pasta::$IN_FILTRAR_APENAS_FILHO;
				} else if($pastaMenuPai->cdControleConsulta == pasta::$IN_FILTRAR_APENAS_PAI){
					//$isTrazer = $file->isFile() || ($file->isDir() && $isTrazer);
					$isTrazer = ($file->isDir() && $isTrazer);
					$inFilhos = pasta::$IN_NAO_FILTRAR;
				}
			}			
			
			if($isTrazer){
				//echo "alert('entrou no for');";
				//$item->isPasta = $file->isDir();
				//echo "alert('".$dname."');\n";
				
				$enderecoPasta = $pastaMenuPai->dir; 
				if ($file->isDir()){
					$item = new pasta($dname,$strFiltro, ++$indice, $pastaMenuPai);					
					//se o item foi encontrado, eh pq seus filhos devem ser considerados
					//mesmo que eles nao satisfacam o filtro
					//isto permite trazer os arquivos que nao satisfacam o filtro, mas pertencam ao pai que satisfacam
					$item->cdControleConsulta = $inFilhos;
					$item->setDir($enderecoPasta.pasta::$barra.$item->nome);
					
					//seta o valor para dizer que tem uma pasta (dai o indicador ser vazio)
					//mas mantem os indicadores anteriores
					//$pastaMenuPai->strValidacao = $pastaMenuPai->strValidacao + "";
					
					//echo "alert('".$item->getPasta()."');\n";
					
					montarColecaoItens($item);
					
				}else{
					$item = new arquivo($dname, ++$indice, $pastaMenuPai);
					$item->dir=$enderecoPasta;
					//o asterisco serve para dizer que a pasta pai deve ser exibida, pois tem conteudo valido 
					//$pastaMenuPai->strValidacao = $pastaMenuPai->strValidacao + CAMPO_SEPARADOR;
					//marcarPastaParaExibir($pastaMenuPai);
					contaQtdArquivosEExibe($pastaMenuPai);
				}
				
				$pastaMenuPai->colecaoItens[$i] = $item;
				$i++;
			}
		}
		
	}

}

function marcarPastaParaExibir($pasta){
	$pasta->isExibir = true;
	
	$pastaPai = $pasta->pastaPai;
	//vai marcar o pai se ele for diferente de nulo e se ainda nao tiver sido marcado
	if($pastaPai != null && !$pastaPai->isExibir){
		marcarPastaParaExibir($pastaPai);
	}
}

function contaQtdArquivosEExibe($pasta){
	$pasta->isExibir = true;
	$pasta->numTotalRegistros++;
	
	$pastaPai = $pasta->pastaPai;
	//vai marcar o pai se ele for diferente de nulo e se ainda nao tiver sido marcado
	if($pastaPai != null){
		contaQtdArquivosEExibe($pastaPai);
	}
}

function geraArvoreMenu($pastaMenuPai){	
	montarColecaoItens($pastaMenuPai);
	geraArvoreEmDefinitivo($pastaMenuPai);
}

function geraArvoreEmDefinitivo($pastaMenuPai){
	
	$colecao = $pastaMenuPai->colecaoItens;
	if (is_array($colecao)){
		$tamanho = sizeof($colecao);
	}
	else {
		$tamanho = 0;
	}

	for ($i=0;$i<$tamanho;$i++) {
		$item = $colecao[$i];

		if($item->isPasta){
			if($item->isExibir){
				criaMenu($item, false);
				echo $pastaMenuPai->nomeObj . ".adicionarItem(" . $item->nomeObj . ");\n";
			}
			geraArvoreEmDefinitivo($item);				
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