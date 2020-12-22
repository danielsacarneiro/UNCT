<?php
include_once("../../config_lib.php");

$chave = @$_GET ["chave"];
$voentidade = @$_GET ["voentidade"];
$vo = new vocontrato();
$vo->getVOExplodeChave();

$isCdContratoInserido = isAtributoValido($vo->cdContrato);
try {
	/*if ($isCdContratoInserido) {
		// pega o prox termo
		$arrayColunasAlternativas = array (
				vocontrato::$nmAtrAnoContrato => $vo->anoContrato,
				vocontrato::$nmAtrTipoContrato => getVarComoString ( $vo->tipo ),
				vocontrato::$nmAtrCdContrato => $vo->cdContrato,
				vocontrato::$nmAtrCdEspecieContrato => getVarComoString ( $vo->cdEspecie ) 
		);
		$db = new dbcontrato ();
		$sqProximoContratoRegistro = $db->getProximoSequencialChaveComposta ( vocontrato::$nmAtrSqEspecieContrato, $vo, $arrayColunasAlternativas );
		$complemento = "<BR>" . getDadosContratada ( $chave, $voentidade );
	} else {
		// pega o proximo contrato, consultando tanto a planilha contratos como demandacontratos
		$vo->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$arrayColunasAlternativas = array (
				vocontrato::$nmAtrAnoContrato => $vo->anoContrato,
				vocontrato::$nmAtrTipoContrato => getVarComoString ( $vo->tipo ) 
		);
		// incluir consulta com varios vos (vocontratodemanda e vocontrato, por ex) fazendo UNION em todos, passando as chaves pra unir, trazendo o prox seq
		$db = new dbcontrato ();
		// $sqProximoContratoRegistro = $db->getProximoSequencialChaveComposta(vocontrato::$nmAtrCdContrato, $vo, $arrayColunasAlternativas);
		
		$sqProximoContratoRegistro = $db->consultarProxSequencialTermoContrato ( $vo, vocontrato::$nmAtrCdContrato );
	}*/
	
	$db = new dbcontrato ();	
	if ($isCdContratoInserido) {
		// pega o prox termo e complementa com os dados do contrato
		$sqProximoContratoRegistro = $db->consultarProxSequencialTermoContrato ( $vo, vocontrato::$nmAtrSqEspecieContrato);
		$complemento = "<BR>" . getDadosContratada ( $chave, $voentidade );
	}else{
		//pega prox contrato
		$vo->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$sqProximoContratoRegistro = $db->consultarProxSequencialTermoContrato ( $vo, vocontrato::$nmAtrCdContrato );
	}
	
	$retorno .= "Próximo " . getTextoHTMLDestacado ( dominioEspeciesContrato::getDescricao ( $vo->cdEspecie ), "black" ) . " A REGISTRAR será " . getTextoHTMLDestacado ( $sqProximoContratoRegistro ) . ".";
	if ($complemento != null) {
		$retorno .= $complemento;
	}
} catch ( excecaoGenerica $ex ) {
	$retorno = $ex->getMessage ();
}


//NAO SE PERMITE DECLARACAO DE QUALQUER FUNCAO NESTA PAGINA
echo $retorno;

?>