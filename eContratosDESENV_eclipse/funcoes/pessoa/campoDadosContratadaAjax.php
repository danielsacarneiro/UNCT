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
		$chave = @$_GET["chave"];		
						
		$vo = new vocontrato();
		$vo->getChavePrimariaVOExplodeParam($chave);
		
		/*$array = explode(CAMPO_SEPARADOR,$chave);
		$vo->cdContrato = $array[0];
		$vo->anoContrato= $array[1];
		$vo->tipo = $array[2];
		$vo->cdEspecie= dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;*/
		
		$filtro->cdContrato = $vo->cdContrato;
		$filtro->anoContrato= $vo->anoContrato;
		$filtro->tpContrato = $vo->tipo;
		$filtro->cdEspecieContrato= $vo->cdEspecie;
		$filtro->sqEspecieContrato= $vo->sqEspecie;
		$filtro->cdAtrOrdenacao = vocontrato::getNmTabela().".".vocontrato::$nmAtrSqContrato;	
		
		//$filtro->$dtReferenciaContrato= getDataHoje();
		//$filtro->$dtReferenciaContrato= "01/01/2016";

		$recordSet = $db->consultarPessoaPorContrato($filtro);
		$retorno = getCampoContratada("","","");
		if($recordSet != ""){
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