<?php
include_once("../../config_lib.php");

$vo = new vocontrato();
$vo->getVOExplodeChave();

$arrayColunasAlternativas = array(
		vocontrato::$nmAtrAnoContrato => $vo->anoContrato,
		vocontrato::$nmAtrTipoContrato => getVarComoString($vo->tipo),
		vocontrato::$nmAtrCdContrato => $vo->cdContrato,
		vocontrato::$nmAtrCdEspecieContrato => getVarComoString($vo->cdEspecie),
);
$db = new dbcontrato();
$sqProximoContratoRegistro = $db->getProximoSequencialChaveComposta(vocontrato::$nmAtrSqEspecieContrato, $vo, $arrayColunasAlternativas);

$retorno .= "Próximo " . getTextoHTMLDestacado(dominioEspeciesContrato::getDescricao($vo->cdEspecie), "black") . " A REGISTRAR será ". getTextoHTMLDestacado( $sqProximoContratoRegistro) . ".";

//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo $retorno;

?>