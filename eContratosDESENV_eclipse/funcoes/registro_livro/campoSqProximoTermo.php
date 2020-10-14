<?php
include_once("../../config_lib.php");
include_once(caminho_funcoes . voRegistroLivro::getNmTabela(). "/biblioteca_htmlRegistro.php");

$chave = @$_GET ["chave"];
$array = explode ( CAMPO_SEPARADOR, $chave );

/*$ano = $array[0];
$setor = $array[1];
$tipo = $array[2];*/

$ano = $array[0];
$tp = $array[1];
$arrayColunasAlternativas = array(
		vocontrato::$nmAtrAnoContrato => $ano,
		vocontrato::$nmAtrTipoContrato => getVarComoString($tp),
);
$voregistro = new voRegistroLivro();
$dbRegistro = new dbRegistroLivro();
$sqProximoContratoRegistro = $dbRegistro->getProximoSequencialChaveComposta(vocontrato::$nmAtrCdContrato, $voregistro, $arrayColunasAlternativas);
$vodemandacontrato = new voDemandaContrato();
$dbDemandaContrato = new dbDemandaContrato();
$sqProximoContratoDemanda = $dbDemandaContrato->getProximoSequencialChaveComposta(vocontrato::$nmAtrCdContrato, $vodemandacontrato, $arrayColunasAlternativas);

$squltimoContratoRegistrado = $sqProximoContratoRegistro-1;
$squltimoContratoDemanda = $sqProximoContratoDemanda-1;
$obs = "Último instrumento registrado: $squltimoContratoRegistrado 
		<br>Último instrumento em demanda: $squltimoContratoDemanda";
			
//verifica se os sq dos contratos iguais tanto em registro como em demandas
if($sqProximoContratoDemanda <= $sqProximoContratoRegistro){
	if($sqProximoContratoDemanda < $sqProximoContratoRegistro){
		$retorno = getTextoHTMLDestacado("Confirme a existência de demandas com contratos não registrados", "blue", false) . ".<br>$obs.<br>";
	}
	$retorno .= "Próximo número A REGISTRAR será ". getTextoHTMLDestacado( $sqProximoContratoRegistro) . ".";
}else{
	$retorno = getTextoHTMLDestacado("Registros incorretos")
			. ".Verifique os instrumentos 'registrados' e 'em demanda'. <br>$obs.";
}

//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo $retorno;

?>