<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%">P.A.</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Contrato</TH>
                    <TH class="headertabeladados" width="1%" nowrap >Doc.Contratada</TH>
                    <TH class="headertabeladados" width="90%">Contratada</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Servidor.Resp.</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Situação</TH>
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
                
                $colspan=7;
                if($isHistorico){
                	$colspan=8;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voPA();
                        $voAtual->getDadosBanco($colecao[$i]);     
                        
                        $contrato = formatarCodigoAnoComplemento($colecao[$i][voPA::$nmAtrCdContrato],
                        						$colecao[$i][voPA::$nmAtrAnoContrato], 
                        						$dominioTipoContrato->getDescricao($colecao[$i][voPA::$nmAtrTipoContrato]));
                                                
                        $procAdm = formatarCodigoAno($colecao[$i][voPA::$nmAtrCdPA],
                        		$colecao[$i][voPA::$nmAtrAnoPA]);
                        
                        $situacao = $colecao[$i][voPA::$nmAtrSituacao];
                        $situacao = $domSiPA->getDescricao($situacao);                        			 
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
                    <TD class="tabeladados" nowrap><?php echo $procAdm;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $contrato;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i][vopessoa::$nmAtrDoc];?></TD>
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
