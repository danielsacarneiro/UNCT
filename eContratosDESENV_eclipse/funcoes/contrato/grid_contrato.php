<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
		                  <?php if($isHistorico){?>
		                  	<TH class="headertabeladados"  width="1%">Sq.Hist</TH>
		                  <?php }?>                  
                    <TH class="headertabeladados" width="1%" nowrap>Contrato</TH>
                    <TH class="headertabeladados" width="1%">Espécie</TH>
                    <TH class="headertabeladados" width="1%">Proc.Lic</TH>
					<TH class="headertabeladados" width="20%">Contratada</TH>
                    <TH class="headertabeladados" width="70%">Objeto</TH>						
                    <TH class="headertabeladados" width="1%" nowrap>Assinatura</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Publicação</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Início</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Fim</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Mensal</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Global</TH>
                </TR>
                <?php
                $colspan = 12;
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;	
                
                 if($isHistorico){
                 	$colspan++;
                 }
                        
                            
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecao[$i]);
                        $especie = getDsEspecie($voAtual, false, false, true);                    
                                                
                        $sq = $colecao[$i][vocontrato::$nmAtrSqContrato];
                        $msgAlertaSq = "onMouseOver=toolTip('seq:".$sq."') onMouseOut=toolTip()";
                    
                        /*
                        $sqHist = "";
                        if($isHistorico)
                            $sqHist = $colecao[$i][vocontrato::$nmAtrSqHist];

                         $chave = $sq
                                . "*"
                                . $colecao[$i][vocontrato::$nmAtrAnoContrato]
                                . "*"
                                . $colecao[$i][vocontrato::$nmAtrCdContrato]
                                . "*"
                                . $cdHistorico
                                . "*"
                                . $sqHist
                                ;*/                        
                
                        $datainiSQL = $colecao[$i]["ct_dt_vigencia_inicio"];
                        $datafimSQL = $colecao[$i]["ct_dt_vigencia_fim"];
                        $dataFinal = getData($datafimSQL);
                                                
                        $validaAlerta = $dataFinal != "";
                        try{                                                   
                            $qtDiasFimVigencia = getQtdDiasEntreDatas(dtHojeSQL, $datafimSQL);
                        }catch (Exception $e){
                            $validaAlerta = false;
                        }                        
                                     
                        /*if($validaAlerta)
                        	echo "verdadeiro";
                        else
                        	echo "falso";*/
                        
                        //valida alerta somente quandoa consultar for por contratos vigentes
                        $validaAlerta = $validaAlerta && ($filtro->tpVigencia == dominioTpVigencia::$CD_OPCAO_VIGENTES);
                        $classColuna = "tabeladados";
                        $mensagemAlerta = "";                        
                        
                        if($validaAlerta){
                            if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_VERMELHO)
                                $classColuna = "tabeladadosdestacadovermelho";
                            else if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_AMARELO)
                                $classColuna = "tabeladadosdestacadoamarelo";
                                
                            $mensagemAlerta = "onMouseOver=toolTip('".$qtDiasFimVigencia."dias') onMouseOut=toolTip()";
                        }                        
                        $tagCelula = "class='$classColuna' " . $mensagemAlerta;
                        
                        $tipo = dominioTipoContrato::getDescricao($colecao[$i]["ct_tipo"]);
                        
                        $contrato = formatarCodigoAnoComplemento($voAtual->cdContrato,
                        		$voAtual->anoContrato,
                        		dominioTipoContrato::getDescricao($voAtual->tipo));  
                        
                        $procLicitatorio = $voAtual->procLic;
                        $objeto = $voAtual->objeto;
                        //$objeto = truncarStringHTMLComDivExpansivel(vocontrato::$nmAtrObjetoContrato, $objeto);
                ?>
                <TR class="dados">
                    <TD class="tabeladados" <?=$msgAlertaSq?>>
					<?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voRegistroLivro::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                                        
                    <TD class="tabeladadosalinhadodireita" nowrap><?php echo $contrato;?></TD>
                    <TD class="tabeladados"><?php echo $especie?></TD>
                    <TD class="tabeladados"><?php echo $procLicitatorio?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i]["ct_contratada"]?></TD>                    
                    <!-- <TD class="tabeladados" nowrap><?php echo documentoPessoa::getNumeroDocFormatado($colecao[$i]["ct_doc_contratada"])?></TD> -->
                    <TD class="tabeladados"><?php echo $objeto?></TD>
                    <TD class="tabeladados"><?php echo getData($voAtual->dtAssinatura)?></TD>
                    <TD class="tabeladados"><?php echo getData($voAtual->dtPublicacao)?></TD>
                    <TD class="tabeladados"><?php echo getData($datainiSQL)?></TD>
                    <TD <?=$tagCelula?>>                    <?php echo $dataFinal?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_mensal"])?></TD>                    
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_global"])?></TD>
                </TR>					
                <?php
				}
				
				if(!$exportarExcel){
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$filtro->paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>
                <?php 
				}
				?>				
            </TBODY>
        </TABLE>
        </DIV>
       </TD>
       </TR>
       <?php 
       if(!$exportarExcel){
       ?>              
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
                       <TD>
                        <TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                   	<TR>
	                   	    <!-- <TD class="botaofuncao"><?=getBotao("bttEstatistica", "Estatísticas", null, false, "onClick='javascript:estatisticas();' accesskey='e'")?></TD> -->
	                   	    <TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD>
	                   		<?php
	                   		/*$arrayBotoesARemover = array(constantes::$CD_FUNCAO_EXCLUIR,
	                   				constantes::$CD_FUNCAO_INCLUIR,
	                   				constantes::$CD_FUNCAO_ALTERAR,
	                   		);*/
	                   		echo getBotoesRodapeComRestricao($arrayBotoesARemover, TRUE);
	                   		?> 
                         </TR>
                         </TABLE>
	                   </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
        <?php 
		}
		?>				
        