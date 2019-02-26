<?php
$arquivo = $_GET["arquivo"];
//echo "teste";
if(isset($arquivo) && file_exists($arquivo)){ // faz o teste se a variavel n�o esta vazia e se o arquivo realmente existe
	$extensao = strtolower(substr(strrchr(basename($arquivo),"."),1));
	//echo $extensao;
	switch($extensao){ // verifica a extens�o do arquivo para pegar o tipo
		case "pdf": $tipo="application/pdf"; break;
		case "exe": $tipo="application/octet-stream"; break;
		case "zip": $tipo="application/zip"; break;
		case "doc": $tipo="application/msword"; break;
		//case "xls": $tipo="application/vnd.ms-excel"; break;
		case "xls": $tipo="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;		
		case "ppt": $tipo="application/vnd.ms-powerpoint"; break;
		case "gif": $tipo="image/gif"; break;
		case "png": $tipo="image/png"; break;
		case "jpg": $tipo="image/jpg"; break;
		case "mp3": $tipo="audio/mpeg"; break;
		case "php": // deixar vazio por seuran�a
		case "htm": // deixar vazio por seuran�a
		case "html": // deixar vazio por seuran�a
	}
		
	//$expires = 60 * 60 * 24 * 14;
	//header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
	/*header("Cache-Control: maxage=" . $expires);
	header('Pragma: public');*/
	//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header('Pragma: no-cache');
	header('Expires: -1');
	header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
	header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
	header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que � tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo	
	header('Content-Transfer-Encoding: binary');
	//header('Cache-Control: must-revalidate');
	ob_clean();
	flush();	
	ob_flush();
	readfile($arquivo); // l� o arquivo
	ob_flush();
}
?>