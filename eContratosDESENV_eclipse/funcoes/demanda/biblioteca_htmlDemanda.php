<?php
function getHtmlDocumento($voAtual, $comDescricaoPorExtenso = false) {
	$html .= "<TD class='tabeladadosalinhadodireita' nowrap> \n";
	if ($voAtual->voDoc->sq != null) {
		$voDoc = $voAtual->voDoc;
			
		/*
		 * $voDoc->dbprocesso = new dbDocumento();
		 * $registro = $voDoc->dbprocesso->consultarPorChave($voDoc, false);
		 */
			
		$endereco = $voDoc->getEnderecoTpDocumento ();
		$chave = $voDoc->getValorChavePrimaria ();
			
		$html .= $voDoc->formatarCodigo ($comDescricaoPorExtenso) . " \n";
		$html .= "<input type='hidden' name='" . $chave . "' id='" . $chave . "' value='" . $endereco . "'>" . " \n";
		// $html .= getBotaoValidacaoAcesso("bttabrir_arq", "Abrir Anexo", "botaofuncaop", false,true,true,true, "onClick=\"javascript:abrirArquivo('".$chave."');\"");
		$html .= getBotaoAbrirDocumento ( $chave );
	}
	$html .= "</TD> \n";
	
	return $html;	
}

function mostrarGridDemanda($colecaoTramitacao, $isDetalhamento) {
	// var_dump($colecaoTramitacao);
	
	if (is_array ( $colecaoTramitacao )) {
		$tamanho = sizeof ( $colecaoTramitacao );
	} else {
		$tamanho = 0;
	}
	
	$html = "";
	if ($tamanho > 0) {
		
		$numColunas = 9;
				
		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Histórico\n";
		
		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Número</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Origem</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Destino</TH> \n";
		$html .= "<TH class='headertabeladados' width='90%'>Texto</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Anexo</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usuário</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Dt.Referência</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Ult.Mov.</TH> \n";
		$html .= "</TR> \n";
		
		$sq = 1;
		
		$dominioSetor = new dominioSetor ();
		for($i = 0; $i < $tamanho; $i ++) {
			
			$voAtual = new voDemandaTramitacao ();
			$voAtual->getDadosBanco ( $colecaoTramitacao [$i] );
			
			$sq = $voAtual->sq;
			
			if ($voAtual != null) {
				$html .= "<TR class='dados'> \n";
				
				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorOrigem ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao ( $voAtual->cdSetorDestino ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $voAtual->textoTram . "</TD> \n";				
				$html .= getHtmlDocumento($voAtual);				
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dtReferencia ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dhInclusao ) . "</TD> \n";
				
				$html .= "</TR> \n";
				
				$sq ++;
			}
		}
				
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
		
		$isSetorAtual = $i = 0;
		// o setor origem vai ser o setor destino da ultima tramitacao
		//ATENCAo a ordenacao da consulta. O setor atual/origem serah o da ultima tramitacao em caso de ordenacao crescente. Caso contrario, sera o da primeira tramitacao.
		if($isSetorAtual){
			$html .= "<INPUT type='hidden' id='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' name='" . voDemandaTramitacao::$nmAtrCdSetorOrigem . "' value='" . $voAtual->cdSetorDestino . "'> \n";
		}
	}
	
	echo $html;
}

function mostrarGridDemandaContrato($colecaoTramitacao, $isDetalhamento) {
	// var_dump($colecaoTramitacao);

	if (is_array ( $colecaoTramitacao )) {
		$tamanho = sizeof ( $colecaoTramitacao );
	} else {
		$tamanho = 0;
	}

	$html = "";
	if ($tamanho > 0) {

		$numColunas = 9;

		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Anexos/Demandas\n";

		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";
		if (! $isDetalhamento) {
			$numColunas ++;
			$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
		}
		$html .= "<TH class='headertabeladados' width='1%'>Ano</TH>   \n";
		$html .= "<TH class='headertabeladados' width='1%'>Dem.</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Tram.</TH> \n";
		$html .= "<TH class='headertabeladados' width='20%'>Título</TH> \n";
		$html .= "<TH class='headertabeladados' width='30%'>Texto</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Anexo</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Usuário</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Ult.Mov.</TH> \n";
		$html .= "</TR> \n";

		$sq = 1;

		$dominioSetor = new dominioSetor ();
		for($i = 0; $i < $tamanho; $i ++) {
				
			$voAtual = new voDemandaTramitacao ();
			$voAtual->getDadosBanco ( $colecaoTramitacao [$i] );
				
			$sq = $voAtual->sq;
				
			if ($voAtual != null) {
				$html .= "<TR class='dados'> \n";

				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_tramitacao", "rdb_tramitacao", $i );
					$html .= "</TD> \n";
				}

				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->ano . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->cd, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->sq, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados'>" .  $voAtual->texto . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $voAtual->textoTram . "</TD> \n";
				
				$html .= getHtmlDocumento($voAtual, true);

				$html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getData ( $voAtual->dhInclusao ) . "</TD> \n";

				$html .= "</TR> \n";

				$sq ++;
			}
		}

		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
	}

	echo $html;
}
?>