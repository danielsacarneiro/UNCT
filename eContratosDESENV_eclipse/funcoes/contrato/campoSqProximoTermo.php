<?php
include_once("../../config_lib.php");

$vo = new vocontrato();
//$chave = @$_GET ["chave"];
//$array = explode ( CAMPO_SEPARADOR, $chave );
$vo->getVOExplodeChave();

/*$ano = $array[0];
$setor = $array[1];
$tipo = $array[2];*/

$ano = $array[0];
$tp = $array[1];
$arrayColunasAlternativas = array(
		vocontrato::$nmAtrAnoContrato => $vo->anoContrato,
		vocontrato::$nmAtrTipoContrato => getVarComoString($vo->tipo),
		vocontrato::$nmAtrCdContrato => $vo->cdContrato,
		vocontrato::$nmAtrCdEspecieContrato => getVarComoString($vo->cdEspecie),
);
$db = new dbcontrato();
$sqProximoContratoRegistro = $db->getProximoSequencialChaveComposta(vocontrato::$nmAtrSqEspecieContrato, $vo, $arrayColunasAlternativas);

$retorno .= "Próximo termo A REGISTRAR será ". getTextoHTMLDestacado( $sqProximoContratoRegistro) . ".";

//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo $retorno;

?>