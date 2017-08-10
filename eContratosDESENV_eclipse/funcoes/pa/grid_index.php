<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                    <TR>
		                <TH class="headertabeladados" rowspan="2" width="1%">&nbsp;&nbsp;X</TH>
		                  <?php if($isHistorico){?>
		                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
		                  <?php }?>
						<TH class="headertabeladados" colspan="2">
						<center>P.A.</center>
						</TH>
						<TH class="headertabeladados" colspan="3">
						<center>Contrato</center>
						</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap >Doc.Contratada</TH>
	                    <TH class="headertabeladados" rowspan="2" width="90%">Contratada</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap>Servidor.Resp.</TH>
	                    <TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Situação</TH>
                    </TR>
                    <TR>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
	                    <TH class="headertabeladados" width="1%">Tipo</TH>	                    
                    </TR>                 
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
                include_once (caminho_funcoes."contrato/dominioTipoContrato.php");
                //require_once ("dominioSituacaoPA.php");
                $dominioTipoContrato = new dominioTipoContrato();
                $domSiPA = new dominioSituacaoPA();
                
                $colspan=10;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voPA();
                        $voContratoAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecao[$i]);     
                        $voContratoAtual->getDadosBanco($colecao[$i]);
                        
                        /*$contrato = formatarCodigoAnoComplemento($colecao[$i][voPA::$nmAtrCdContrato],
                        						$colecao[$i][voPA::$nmAtrAnoContrato], 
                        						$dominioTipoContrato->getDescricao($colecao[$i][voPA::$nmAtrTipoContrato]));
                                                
                        $procAdm = formatarCodigoAno($colecao[$i][voPA::$nmAtrCdPA],
                        		$colecao[$i][voPA::$nmAtrAnoPA]);*/
                        
                        $situacao = $colecao[$i][voPA::$nmAtrSituacao];
                        $situacao = $domSiPA->getDescricao($situacao);  
                        $tipo = $dominioTipoContrato->getDescricao($voContratoAtual->tipo);
                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voPA::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->anoPA;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->cdPA, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voContratoAtual->anoContrato;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voContratoAtual->cdContrato, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipo;?></TD>
                    <TD class="tabeladados" nowrap><?php echo documentoPessoa::getNumeroDocFormatado($colecao[$i][vopessoa::$nmAtrDoc]);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][$filtro->nmColNomePessoaContrato];?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][$filtro->nmColNomePessoaResponsavel];?></TD>
                    <TD class="tabeladados" nowrap><?php echo $situacao;?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
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
                         </TR>
                         </TABLE>
	                   </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
