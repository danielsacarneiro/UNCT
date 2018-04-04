<?php
include_once("../../config_lib.php");
include_once(caminho_funcoes . voDocumento::getNmTabela(). "/biblioteca_htmlDocumento.php");

$chave = @$_GET ["chave"];
$array = explode ( CAMPO_SEPARADOR, $chave );

/*$ano = $array[0];
$setor = $array[1];
$tipo = $array[2];*/

//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo "Próximo número será " . getSqDocumentoAtual($array);

?>