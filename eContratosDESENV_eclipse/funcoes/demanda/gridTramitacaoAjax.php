<?php

	$db = new dbDemanda();
	$isDetalhamento = true;
	
	$colecaoTramitacao = "";
	
	if($vo->cd != null)
		$colecaoTramitacao = $db->consultarDemandaTramitacao($vo);
					
	if (is_array($colecaoTramitacao))
		$tamanho = sizeof($colecaoTramitacao);
	else
		$tamanho = 0;
		
	if($tamanho > 0){
		
			$html = "";
							
			$html .= "<TR>\n";
			$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
			$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Hist�rico\n";
			
			$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";						
			$html .= " <TBODY>  \n";
			$html .= "        <TR>    \n"; 
			if(!$isDetalhamento){
				$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
			}
			$html .= "<TH class='headertabeladados' width='1%' nowrap>N�mero</TH>   \n";
			$html .= "<TH class='headertabeladados' width='1%'>Setor.Origem</TH> \n";
			$html .= "<TH class='headertabeladados' width='1%'>Setor.Destino</TH> \n";
			$html .= "<TH class='headertabeladados' width='90%'>Texto</TH> \n";
			$html .= "<TH class='headertabeladados' width='1%' nowrap>PRT</TH> \n";
			$html .= "<TH class='headertabeladados' width='1%' nowrap>Usu�rio</TH> \n";
			$html .= "<TH class='headertabeladados' width='1%' nowrap>Data</TH> \n";			
			$html .= "</TR> \n";
			       
			$sq = 1;
			$dominioSetor = new dominioSetor();
	        for ($i=0;$i<$tamanho;$i++) {
        	
	        	$voAtual = new voDemandaTramitacao();
	        	$voAtual->getDadosBanco($colecaoTramitacao[$i]);	        	
	        	
	        	$sq = $voAtual->sq;
	        	
	        	if($voAtual != null){
		            $html .= "<TR class='dados'> \n";
		            
		            if(!$isDetalhamento){
		            	$html .= "<TD class='tabeladados'> \n";
		            	$html .=getHTMLRadioButtonConsulta("rdb_tramitacao", "rdb_tramitacao", $i);
		            	$html .= "</TD> \n";
		            }
		            
		            $html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda($sq, "0", TAMANHO_CODIGOS) . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao($voAtual->cdSetorOrigem) . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $dominioSetor->getDescricao($voAtual->cdSetorDestino) . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $voAtual->textoTram . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $voAtual->prt . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $voAtual->nmUsuarioInclusao . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . getDataHora($voAtual->dhInclusao) . "</TD> \n";
		            
		            /*$html .= "<TD class='tabeladados' nowrap> \n";
		            
		            if($tram->voDoc->sq != null){
						$voDoc = $tram->voDoc;
						$voDoc->dbprocesso = new dbDocumento(); 
		            	
						$registro = $voDoc->dbprocesso->consultarPorChave($voDoc, false);
						$voDoc->linkDoc =  $registro[voDocumento::$nmAtrLinkDoc];						
		            	
		            	$endereco = $voDoc->getEnderecoTpDocumento();		            	
		            	$chave = $voDoc->getValorChavePrimaria();
		            	
		            	$html .= $voDoc->formatarCodigo() . " \n";
		            	$html .= "<input type='hidden' name='".$chave."' id='".$chave."' value='".$endereco."'>" . " \n";
		            	$html .= getBotaoValidacaoAcesso("bttabrir_arq", "Abrir Anexo", "botaofuncaop", false,true,true,true, "onClick=\"javascript:abrirArquivo('".$chave."');\"");
		            }
		            		            
		            $html .= "</TD> \n";*/		           
		            $html .= "</TR> \n";
		            $sq++;
	        	}
	              
	        }				
	                
	        $html .= "</TBODY> \n";
	        $html .= "</TABLE> \n";
	        $html .= "</DIV> \n";
	        $html .= "</TH>\n";
	        $html .= "</TR>\n";
	        
	        //inclui o setor origem que vai ser o setor destino da ultima tramitacao
	        $html .= "<INPUT type='hidden' id='". voDemandaTramitacao::$nmAtrCdSetorOrigem . "' name='". voDemandaTramitacao::$nmAtrCdSetorOrigem . "' value='" . $voAtual->cdSetorDestino . "'> \n";
	}	
        
	echo $html ;

?>