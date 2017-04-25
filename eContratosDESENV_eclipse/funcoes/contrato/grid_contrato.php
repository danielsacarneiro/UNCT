<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
                    <TH class="headertabeladados" width="1%">Num.</TH>
                    <TH class="headertabeladados" width="1%">Tipo</TH>
                    <TH class="headertabeladados" width="1%">Espécie</TH>
                    <TH class="headertabeladados" width="20%">Contratada</TH>
                    <TH class="headertabeladados" width="1%">CNPJ/CNPF</TH>
                    <TH class="headertabeladados" width="50%">Objeto</TH>						
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Início</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Fim</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Vl.Mensal</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Vl.Global</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;	
                            
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecao[$i]);
                        $especie = getDsEspecie($voAtual);                    
                                                
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
                        
                        $tipo = $dominioTipoContrato->getDescricao($colecao[$i]["ct_tipo"]); 
                                                
                ?>
                <TR class="dados">
                    <TD class="tabeladados" <?=$msgAlertaSq?>>
					<?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>
                    </TD>
                    <TD class="tabeladadosalinhadodireita"><?php echo $colecao[$i]["ct_exercicio"];?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo complementarCharAEsquerda($colecao[$i]["ct_numero"], "0", 3)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipo?></TD>
                    <TD class="tabeladados"><?php echo $especie?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i]["ct_contratada"]?></TD>
                    <TD class="tabeladados" nowrap><?php echo documentoPessoa::getNumeroDocFormatado($colecao[$i]["ct_doc_contratada"])?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i]["ct_objeto"]?></TD>
                    <TD class="tabeladados"><?php echo getData($datainiSQL)?></TD>
                    <TD <?=$tagCelula?>>                    <?php echo $dataFinal?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_mensal"])?></TD>                    
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_global"])?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=12><?=$filtro->paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>				
            </TBODY>
        </TABLE>
        </DIV>
       </TD>
       </TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
                       <TD>
                        <TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                   	<TR>
	                   	<?=getBotoesRodape();?> 
                            <TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD>
                         </TR>
                         </TABLE>
	                   </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>