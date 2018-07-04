<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaDataHora.php");

function getCampoDataPrazoFinal($idCampo, $valor) {
	
	if($valor != null){
	$retorno = "Prazo Final:
	<INPUT type='text'
	id='$idCampo'
	name='$idCampo'
	value='".getData($valor)."'
	 onkeyup='formatarCampoData(this, event, false);'
	 class='camponaoobrigatorio'
	 size='10'
	 maxlength='10' readonly>";
	}
	
	return $retorno;
	
}
function getDataPrazoFinal($dtinicio, $prazo, $idCampo, $isDiasUteis=true) {	
	//PARA FINS DE CONTAGEM DE PRAZO PROCESSUAL, A CONTAGEM SE INICIA NO PRIMEIRO DIA UTIL SEGUINTE AO PRAZO INICIAL
	$data = getDataContagemPrazoFinal($dtinicio, $prazo, $isDiasUteis);
	$retorno = getCampoDataPrazoFinal($idCampo, $data);
	
	return $retorno;
}

function isPAAPValido($vo) {
	// so exibe contrato se tiver
	return $vo != null && $vo->cdPA;
}

function getPAAPDetalhamento($vo,$temLupa=true) {
	if(isPAAPValido($vo)){

		?>
<TR>
	<TH class="campoformulario" nowrap width=1%>PAAP:</TH>
	<TD class="campoformulario" colspan=3>
	<?php
	$arrayParametroXNmAtributo = array ("cdPA" => voPA::$nmAtrCdPA,
			"anoPA" => voPA::$nmAtrAnoPA
	);
	
	echo getCampoDadosVOAnoCdDetalhamento($vo,$arrayParametroXNmAtributo,$temLupa);
	?>
	</TD>	
</TR>
<?php
	}
	
}

function getCampoDadosPAAP($voPAAP, $nmClass = "camponaoobrigatorio", $arrayComplementoHTML=null) {
	$arrayParametroXNmAtributo = array ("cdPA" => voPA::$nmAtrCdPA,
			"anoPA" => voPA::$nmAtrAnoPA
	);

	getCampoDadosVOAnoCd($voPAAP, $arrayParametroXNmAtributo, $nmClass, $arrayComplementoHTML);
}

function getComboSituacaoPA($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$select = new select(dominioSituacaoPA::getColecaoConsulta());
	$retorno = $select->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, false, $tagHtml);

	return $retorno;
}

function getComboGestorPessoa($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada){
    return getComboGestorPessoaMais($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada, "camponaoobrigatorio", "");
}

function getComboGestorPessoaMais($db, $idCampo, $nmCampo, $cdGestor, $cdOpcaoSelecionada, $classCampo, $tagHtml){
    if($db == null)
        $db = new dbgestorpessoa();

    if ($cdGestor == null)
        $cdGestor = @$_GET[vocontrato::$nmAtrGestorContrato];
                
    $recordSet = $db->consultarSelect($cdGestor);    
    $gestorSelect = new select(array());
    
    $retorno = "<select $tagHtml></select>";
    if($cdGestor != null){ 
        $gestorSelect->getRecordSetComoColecaoSelect(vogestorpessoa::$nmAtrCd, vogestorpessoa::$nmAtrNome, $recordSet);    
        $retorno = $gestorSelect->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);    
    }	
        
    return $retorno;
}

function mostrarGridPenalidade($voPA) {
	//$voPA = new voPA();
	// var_dump($colecao);

	$db = new dbPA();
	$colecao = $db->consultarPenalidade($voPA);	

	if (is_array ( $colecao )) {
		$tamanho = sizeof ( $colecao );
	} else {
		$tamanho = 0;
	}

	$html = "";
	if ($tamanho > 0) {

		$numColunas = 3;

		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Penalidades\n";

		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		/*if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}*/
		$html .= "<TH class='headertabeladados' width='1%'>Num</TH>   \n";
		$html .= "<TH class='headertabeladados' width='10%' nowrap>Tipo</TH> \n";
		$html .= "<TH class='headertabeladados' width='80%'>Fundamento</TH> \n";
		$html .= "</TR> \n";

		for($i = 0; $i < $tamanho; $i ++) {

			$voAtual = new voPenalidadePA();
			$voAtual->getDadosBanco ( $colecao [$i] );

			if ($voAtual != null) {
				$tipoPenalidade = dominioTipoPenalidade::getDescricaoStatic($voAtual->tipo);
				
				$html .= "<TR class='dados'> \n";

				/*if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}*/

				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->sq, "0", TAMANHO_CODIGOS_SAFI ) . "</TD> \n";
				$html .= "<TD class='tabeladados'>" . $tipoPenalidade . "</TD> \n";
				$html .= "<TD class='tabeladados'>" . $voAtual->fundamento . "</TD> \n";
				$html .= "</TR> \n";
			}							
		}
		$html .= "<TR>\n
		<TD class='totalizadortabeladadosalinhadodireita' colspan=$numColunas> Total registros: $i </TD>\n
		</TR>\n";
		
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
	}

	echo $html;
}

?>