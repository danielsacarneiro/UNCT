<?php
include_once ("../../config_lib.php");
include_once (caminho_vos . "dbpessoa.php");
include_once (caminho_vos . "vocontrato.php");
include_once (caminho_filtros . "filtroManterPessoa.php");
include_once (caminho_funcoes . "contrato/dominioEspeciesContrato.php");
include_once (caminho_funcoes . "pessoa/biblioteca_htmlPessoa.php");

function getDadosContrata($db) {
	if ($db == null)
		$db = new dbpessoa ();
	
	$chave = @$_GET ["chave"];
	
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
			
			//guarda para usar na pagina que chamou o metodo
			$arrayCdAutorizacao[] = $registro [vocontrato::$nmAtrCdAutorizacaoContrato];
		}
		
		putObjetoSessao("teste", $arrayCdAutorizacao);
		
	}
	
	return $retorno;
}

echo getDadosContrata ( null );

?>