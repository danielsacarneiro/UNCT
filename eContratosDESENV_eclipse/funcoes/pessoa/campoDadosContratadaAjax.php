<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos."vocontrato.php");
include_once(caminho_filtros."filtroManterPessoa.php");
include_once(caminho_funcoes."contrato/dominioEspeciesContrato.php");
      

function getDadosContrata($db){
	if($db == null)
		$db = new dbpessoa();

		$filtro = new filtroManterPessoa();
		$filtro->TemPaginacao = false;
		$chave = @$_GET["chave"];
		$array = explode(CAMPO_SEPARADOR,$chave);
		
		$vo = new vocontrato();
		$vo->cdContrato = $array[0];
		$vo->anoContrato= $array[1];
		$vo->tipo = $array[2];
		$vo->sqEspecie= dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		
		$filtro->cdContrato = $vo->cdContrato;
		$filtro->anoContrato= $vo->anoContrato;
		$filtro->tpContrato = $vo->tipo;
		$filtro->sqEspecieContrato= $vo->sqEspecie;

		$recordSet = $db->consultarPessoaPorContrato($filtro);
		$retorno = "";
		if($recordSet != ""){
			$retorno = $recordSet[0][vopessoa::$nmAtrDoc] . " - ". $recordSet[0][vopessoa::$nmAtrNome];
		}//else throw new Exception("Contrato inexistente.");
		
		$javaScript = "onLoad='alert(0);'";
		$retorno = "Contratada: <INPUT type='text' id='testasdasdade' name='testasdasdade' class='camporeadonly' size=50 readonly value='".$retorno."' required ".$javaScript.">\n";
		//$retorno = "<SCRIPT language='JavaScript' type='text/javascript'> documenti.frm_principal.".vopessoa::$nmAtrNome.".value='".$retorno."'; </SCRIPT>";
		return $retorno ;
		
}

echo getDadosContrata(null);

?>