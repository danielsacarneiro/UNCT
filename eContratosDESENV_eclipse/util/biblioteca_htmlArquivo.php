<?php
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

function localizarArquivosPorPasta($enderecoPasta, $nmArquivo){
	$arquivos = array();
	
	$iterator = new FileSystemIterator($enderecoPasta);
	foreach ($iterator as $file) {	
		$filename = $file->getRealpath();	
		//if (strpos($filename, $termo) !== false) {
		if (existeStr1NaStr2($nmArquivo, $filename)) {
			$arquivos[] = $filename;
		}
	}
	
	return $arquivos;
}

function acharArquivos($dir, $strFiltro) {

	$pasta = $dir . "\\\*";
	$dir = new GlobIterator ( $pasta );
	
	$i = 0;
	// atribui o valor de $dir para $file em loop
	foreach ( $dir as $file ) {
	//foreach(glob("$dir*.pdf") as $file){
//		echo $dname = $file->getFilename ();
		
		if ($file->isDir ()) {
			$dname = $file->getFilename ();
			$pastaTemp = "$pasta\\$dname\\\*";
			$retorno = acharArquivos($pastaTemp, $strFiltro);
			// foreach(glob("$pasta*.txt") as $arquivo)
			//echo $dname = $file->getFilename ();
			//$i++;
		}else if($file->isFile ()){
			$dname = $file->getFilename ();
			if(existeStr1NaStr2($strFiltro, $dname)){
				$retorno = $file;				
			}
			
		}

	}
	
	return $retorno;
}

/*$url = $_SERVER["PHP_SELF"];
if(preg_match("class.Upload.php", "$url"))
{
	header("Location: ../index.php");
}*/

function uploadArquivo($idArquivo, $pastaUpload){
	//define os tipos permitidos
	$tipos[0]=".pdf";
	
	if(isset($_FILES[$idArquivo]))	{
		$upArquivo = new Upload();
		if($upArquivo->UploadArquivo($_FILES[$idArquivo], $pastaUpload, $tipos)){
			$nome = $upArquivo->nome;
			$tipo = $upArquivo->tipo;
			$tamanho = $upArquivo->tamanho;
			echo "Envio com sucesso.<br>";
		}else{
			echo "Falha no envio.<br>";
		}
	}else{
		echo "Arquivo não encontrado.<br>";
	}
	
/*
//define os tipos permitidos
$tipos[0]=".gif";
$tipos[1]=".jpg";
$tipos[2]=".jpeg";
$tipos[3]=".png";

if(isset($HTTP_POST_FILES["userfile"]))
{
$upArquivo = new Upload;
if($upArquivo->UploadArquivo($HTTP_POST_FILES["userfile"], "Imagens/", $tipos))
{
$nome = $upArquivo->nome;
$tipo = $upArquivo->tipo;
$tamanho = $upArquivo->tamanho;
}else{
echo "Falha no envio<br />";
}
} 
 */	
}

class Upload {
	var $tipo;
	var $nome;
	var $tamanho;
	
	function Upload() {
		// Criando objeto
	}
	
	function UploadArquivo($arquivo, $pasta, $tipos) {
		if (isset ( $arquivo )) {
			$nomeOriginal = $arquivo ["name"];
			$nomeFinal = md5 ( $nomeOriginal . date ( "dmYHis" ) );
			$tipo = strrchr ( $arquivo ["name"], "." );
			$tamanho = $arquivo ["size"];
			
			for($i = 0; $i <= count ( $tipos ); $i ++) {
				if ($tipos [$i] == $tipo) {
					$arquivoPermitido = true;
				}
			}
			
			if ($arquivoPermitido == false) {
				echo "Extensão de arquivo não permitido!";
				exit ();
			}
			
			$pasta = './uploads/';
			/* VERIFICO SE A PASTA EXISTE, SE ELA NÃO EXISTIR, EU CRIO A PASTA */
			if(!file_exists($pasta)) {
				echo "criou pasta";
				mkdir($pasta, 0777);
			}else{
				echo ("pasta existente");
			}
			
			//echoo("antes de move");
			//if (move_uploaded_file ( $arquivo ["tmp_name"], $pasta . $nomeFinal . $tipo )) {
			if (move_uploaded_file ( $arquivo ["tmp_name"], $pasta . "teste.pdf" )) {
			//if (move_uploaded_file ($nomeOriginal, $pasta . $nomeFinal . $tipo )) {
				$this->nome = $pasta . $nomeFinal . $tipo;
				$this->tipo = $tipo;
				$this->tamanho = number_format ( $arquivo ["size"] / 1024, 2 ) . "KB";
				return true;
			} else {
				echoo("RETORNOU FALSE");
				return false;
			}
		}
	}
}

class FileFilter {

	private $diretorio;
	private $filtro;

	public function __construct($diretorio, $filtro) {

		$this->diretorio = $diretorio;
		$this->filtro = $filtro;

	}

	public function showFiles() {
		$pasta = str_replace(dominioTpDocumento::$ENDERECO_DRIVE, dominioTpDocumento::$ENDERECO_DRIVE_HTML, $this->diretorio);

		$dir = new DirectoryIterator($pasta);
		$filtro = $this->filtro;
		echoo($filtro);
		echo "pasta $dir" . $pasta;
		$filtro = new RegexIterator($dir, "/^". $this->filtro . "/");//  '/^test/'

		foreach ($filtro as $arquivo)  {
			var_dump($arquivo);
			;
		}

	}
}





?>