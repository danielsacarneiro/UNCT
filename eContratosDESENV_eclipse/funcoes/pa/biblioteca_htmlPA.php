<?php
include_once("../../config_lib.php");
include_once(caminho_vos . "vocontrato.php");
//include_once("dominioSituacaoPA.php");

function getComboSituacaoPA($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$dominio = new dominioSituacaoPA();
	$select = new select($dominio->colecao);

	$retorno = $select->getHtmlCombo($idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml);

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
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Tipo</TH> \n";
		$html .= "<TH class='headertabeladados' width='90%'>Fundamento</TH> \n";
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
				$html .= "<TD class='tabeladados' nowrap>" . $tipoPenalidade . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->fundamento . "</TD> \n";
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