<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos."vocontrato.php");
include_once(caminho_filtros."filtroManterPessoa.php");
include_once(caminho_funcoes."contrato/dominioEspeciesContrato.php");
include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");

function getDadosContrata($db){
	if($db == null)
		$db = new dbpessoa();

		$filtro = new filtroManterPessoa(false);
		$filtro->TemPaginacao = false;

		$filtro->cdContrato = 7;
		$filtro->anoContrato= 2004;
		$filtro->tpContrato = 'C';
		//$filtro->cdEspecieContrato= $vo->cdEspecie;
		//$filtro->sqEspecieContrato= $vo->sqEspecie;
		$filtro->cdAtrOrdenacao = vocontrato::getNmTabela().".".vocontrato::$nmAtrSqContrato;

		//$filtro->$dtReferenciaContrato= getDataHoje();
		//$filtro->$dtReferenciaContrato= "01/01/2016";

		$recordSet = $db->consultarPessoaPorContrato($filtro);
		$retorno = getCampoContratada("","",$chave);
		if($recordSet != ""){
				
			$colecaoColunasAgrupar = array(vopessoa::$nmAtrDoc, vopessoa::$nmAtrNome);
				
			$recordSet = getRecordSetGroupBy($recordSet, $colecaoColunasAgrupar);
			$tam = count($recordSet);
				
			$retorno = "";
			for($i=0;$i<$tam;$i++){
				$registro = $recordSet[$i];
				$retorno .= getCampoContratada($registro[vopessoa::$nmAtrNome], $registro[vopessoa::$nmAtrDoc], $chave). "<br>";
			}
		}

		/*if($recordSet != ""){
			$retorno = $recordSet[0][vopessoa::$nmAtrDoc] . " - ". $recordSet[0][vopessoa::$nmAtrNome];
			$sqContrato = $recordSet[0][vocontrato::$nmAtrSqContrato];
			}
			$javaScript = "onLoad=''";
			$retorno = "Contratada: <INPUT type='text' class='camporeadonly' size=50 readonly value='".$retorno."' ".$javaScript.">\n";
			$retorno .= "<INPUT type='hidden' id='" . vopessoa::$SQ_CONTRATO_DADOS_CONTRATADA . "' name='".vopessoa::$SQ_CONTRATO_DADOS_CONTRATADA."' value='".$sqContrato."' >\n";*/

		return $retorno ;
}

echo getDadosContrata(null);

?>