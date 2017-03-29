<?php
include_once("../../config_lib.php");
include_once(caminho_vos."dbPA.php");
include_once(caminho_vos."voDocumento.php");
include_once(caminho_util."bibliotecaHTML.php");

//inicioComValidacaoUsuario(true);

function incluirTramitacao($textoTramitacao, $doc, $colecaoTramitacao){
	
	if(count($colecaoTramitacao) < 1)
		$indice = 0;
	else	
		$indice = count($colecaoTramitacao);
	
	if($textoTramitacao != null && $textoTramitacao != ""){
		$voTramitacao = new voPATramitacao();
		$voTramitacao->obs = $textoTramitacao;
		$voTramitacao->dhUltAlteracao = getDataHoraAtual();
		
		if($doc != null){
			putDadosDocumentoTramitacao($voTramitacao, $doc);
			
			//var_dump($voTramitacao->voDoc);
		}
		
		/*echo id_user."<br>";
		echo $voTramitacao->cdUsuarioUltAlteracao."<br>";*/
		$colecaoTramitacao[$indice] = $voTramitacao;
	}
		
	return $colecaoTramitacao;
}

function putDadosDocumentoTramitacao($voTramitacao, $chaveDoc){
	$voDoc = new voDocumento();
	$voDoc->getVOExplodeChaveParam($chaveDoc);
	
	/*$voTramitacao->sqDoc =$voDoc->sq; 
	$voTramitacao->anoDoc =$voDoc->ano;
	$voTramitacao->tpDoc =$voDoc->tpDoc;
	$voTramitacao->cdSetorDoc =$voDoc->cdSetor;*/
	$voTramitacao->voDoc =$voDoc;
}

function excluirTramitacao($indice, $colecaoTramitacao){	
	//echo "<br>".$indice;
	//var_dump($colecaoTramitacao);
	if($indice != null){
		$colecaoTramitacao[$indice] = null;
	}
	
	//var_dump($colecaoTramitacao);
	return reordenarArray($colecaoTramitacao);
}

function reordenarArray($colecaoTramitacao){
	
	if ($colecaoTramitacao != null && is_array($colecaoTramitacao))
		$tamanho = sizeof($colecaoTramitacao);
	else
		$tamanho = 0;
	
	$retorno = array();
	$k = 0;
	for ($i=0;$i<$tamanho;$i++) {
		
		if($colecaoTramitacao[$i] != null){
			$retorno[$k] = $colecaoTramitacao[$i];
			$k++;
		}				
	}

	return $retorno;
}

function getDadosTramitacao($db, $isDetalhamento){
	
	if($db == null)
		$db = new dbPA();
	
	//vem do ajax
	//biblioteca_funcoes_ajax.js
	$textoTramitacao = @$_GET["textoTramitacao"];
	$doc= @$_GET["docFase"];
	$colecaoTramitacao = array();
	
	 if(existeObjetoSessao(voPA::$nmAtrColecaoTramitacao)){
	 	//echo "tem colecao";
	 	$colecaoTramitacao = getObjetoSessao(voPA::$nmAtrColecaoTramitacao);
	 }
	 //else echo "NAO TEM COLECAO";
	 
	 $funcao = @$_GET["funcao"];	 	
	 $isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;	 	 
	 $isExclusao = $funcao == constantes::$CD_FUNCAO_EXCLUIR;
	 
	if($isInclusao){
		$colecaoTramitacao = incluirTramitacao($textoTramitacao, $doc, $colecaoTramitacao);		
	}else if($isExclusao){
		$indice = @$_GET["indice"];
		$colecaoTramitacao = excluirTramitacao($indice, $colecaoTramitacao);
	}
					
	if (is_array($colecaoTramitacao))
		$tamanho = sizeof($colecaoTramitacao);
	else
		$tamanho = 0;
		
	if($tamanho > 0){
			$html = "";
			
			//$html = "<SCRIPT language='JavaScript' type='text/javascript' src='". caminho_js. "biblioteca_funcoes_oficio.js'></SCRIPT>";			
			
			$html .= "Tramitação: \n";
			$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";						
			$html .= " <TBODY>  \n";
			$html .= "        <TR>    \n"; 
			if(!$isDetalhamento){
				$html .= "<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>  \n";
			}
			$html .= "<TH class='headertabeladados' width='1%' nowrap>Número</TH>   \n";
			$html .= "<TH class='headertabeladados' width='50%'>Fase</TH> \n";
			$html .= "<TH class='headertabeladados' width='48%'>Anexo</TH> \n";
			$html .= "<TH class='headertabeladados' width='1%' nowrap>Data</TH> \n";
			$html .= "</TR> \n";
			       
			$sq = 1;
	        for ($i=0;$i<$tamanho;$i++) {
	        	//$sq = $i+1; 
	        	$tram = new voPATramitacao();
	        	$tram = $colecaoTramitacao[$i];
	        	
	        	if($tram != null){
		            $html .= "<TR class='dados'> \n";
		            
		            if(!$isDetalhamento){
		            	$html .= "<TD class='tabeladados'> \n";
		            	$html .=getHTMLRadioButtonConsulta("rdb_tramitacao", "rdb_tramitacao", $i);
		            	$html .= "</TD> \n";
		            }
		            
		            $html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda($sq, "0", TAMANHO_CODIGOS_SAFI) . "</TD> \n";
		            $html .= "<TD class='tabeladados' nowrap>" . $tram->obs . "</TD> \n";
		            
		            $html .= "<TD class='tabeladados' nowrap> \n";
		            
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
		            		            
		            $html .= "</TD> \n";
		            
		            $html .= "<TD class='tabeladados' nowrap>" . $tram->dhUltAlteracao . "</TD> \n";
		            $html .= "</TR> \n";
		            $sq++;
	        	}
	              
	        }				
	                
	        $html .= "</TBODY> \n";
	        $html .= "</TABLE> \n";
	}
	
	putObjetoSessao(voPA::$nmAtrColecaoTramitacao, $colecaoTramitacao);
        
	return $html ;
		
}

echo getDadosTramitacao(null, $isDetalhamento);

?>