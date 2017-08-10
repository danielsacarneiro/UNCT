<?php
include_once ("../../config_lib.php");
include_once (caminho_vos . "dbpessoa.php");
include_once (caminho_vos . "vocontrato.php");
include_once (caminho_filtros . "filtroManterPessoa.php");
include_once (caminho_funcoes . "contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
function getDadosContrata($db) {
	$chave = @$_GET ["chave"];
	$voentidade = @$_GET ["voentidade"];
	
	$isConsultaPessoaPorDemanda = $voentidade == "vodemanda";
	
	if (! $isConsultaPessoaPorDemanda) {
		$vo = new vocontrato ();
		$vo->getChavePrimariaVOExplodeParam ( $chave );
		$recordSet = consultarPessoasContrato ( $vo );
		
		$retorno = getCampoContratada ( "", "", $chave );
		if ($recordSet != "") {
			// $colecaoColunasAgrupar = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
			// $recordSet = getRecordSetGroupBy($recordSet, $colecaoColunasAgrupar);
			$tam = count ( $recordSet );
			
			$retorno = "";
			for($i = 0; $i < $tam; $i ++) {
				$registro = $recordSet [$i];
				
				$retorno .= getCampoContratada ( $registro [vopessoa::$nmAtrNome], $registro [vopessoa::$nmAtrDoc], $chave ) . "<br>";
				
				// guarda para usar na pagina que chamou o metodo
				$arrayCdAutorizacao [] = $registro [vocontrato::$nmAtrCdAutorizacaoContrato];
			}
			
			putObjetoSessao ( "teste", $arrayCdAutorizacao );
		}
	} else {
		$vo = new voDemanda ();
		$vo->getChavePrimariaVOExplodeParam ( $chave );
		$colecaoContrato = consultarContratosDemanda ( $vo );
		
		$colecaoContrato = converteRecordSetEmColecaoVOsContrato ( $colecaoContrato );
		
		// vai na bibliotacontrato
		$retorno = getColecaoContratoDet ( $colecaoContrato );
	}
	
	return $retorno;
}
function converteRecordSetEmColecaoVOsContrato($colecao) {
	$retorno = "";
	if (! isColecaoVazia ( $colecao )) {
		foreach ( $colecao as $registrobanco ) {
			$vocontrato = new vocontrato ();
			$vocontrato->getDadosBanco ( $registrobanco );
			
			$retorno [] = $vocontrato;
		}
	}
	
	return $retorno;
}

echo getDadosContrata ( null );

?>