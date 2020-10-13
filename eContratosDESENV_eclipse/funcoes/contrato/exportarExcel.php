<?php 
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbcontrato.php");


// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"nome_arquivo.xls\"" );
header ("Content-Description: PHP Generated Data" );

inicio();
$colecao = getObjetoSessao(vocontrato::$ID_REQ_COLECAO_EXPORTAR_EXCEL);
$exportarExcel = true;

include_once 'grid_contrato.php';

/*$html = "<table>
    <tr>
        <td>Coluna 1</td>
        <td>Coluna 2</td>
    </tr>
    <tr>
        <td>Coluna 1</td>
        <td>Coluna 2</td>
    </tr>
</table>";

echo $html;*/

?>

<script>
window.close();
</script>