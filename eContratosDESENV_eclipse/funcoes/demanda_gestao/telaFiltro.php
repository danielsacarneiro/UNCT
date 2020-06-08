<?php
include_once(caminho_util. "dominioSimNao.php");
$comboSimNao = new select(dominioSimNao::getColecao());

$comboTipo = new select(dominioTipoDemanda::getColecao(false));
$comboSituacao = new select(dominioSituacaoDemanda::getColecaoHTMLConsulta());
$comboSetor = new select(dominioSetor::getColecao());
$comboSetorImplantacaoEconti = new select(dominioSetor::getColecaoImplantacaoEcontiDemanda());
$comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
$selectExercicio = new selectExercicio(constantes::$ANO_INICIO);

?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" nowrap width="1%">
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->vodemanda->cd, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			  </TD>
			  	<TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
                <TD class="campoformulario" ><?php echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $filtro->vodemanda->prioridade, true, "camponaoobrigatorio", false, "");?></TD>			  		  
			</TR>			            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Setor.Resp.:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                Setor.Resp.: <?php echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $filtro->vodemanda->cdSetor, true, "camponaoobrigatorio", false, "");?>                
				<font color=red><b>Setor.Atual</b>:</font> <?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $filtro->vodemanda->cdSetorDestino, true, "camponaoobrigatorio", false, "");?>
				Passou.por: <?php echo $comboSetor->getHtmlCombo(filtroManterDemanda::$NmAtrCdSetorPassagem,filtroManterDemanda::$NmAtrCdSetorPassagem, $filtro->cdSetorPassagem, true, "camponaoobrigatorio", false, "");?>
				A partir da implementação em: <?php echo $comboSetorImplantacaoEconti->getHtmlCombo(filtroManterDemanda::$NmAtrCdSetorImplementacaoEConti,filtroManterDemanda::$NmAtrCdSetorImplementacaoEConti, $filtro->cdSetorImplementacaoEconti, true, "camponaoobrigatorio", false, "");?>
				</TD>				
            </TR>           
            <TR>
                <TH class="campoformulario" nowrap width="1%">Situação:</TH>
                <TD class="campoformulario" width="1%"><?php echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao."[]", $filtro->vodemanda->situacao, true, "camponaoobrigatorio", false, " multiple ", true);?></TD>
                <TH class="campoformulario" nowrap width="1%" rowspan=3>Tipo:</TH>
                <TD class="campoformulario" rowspan=3>
	                <TABLE class="filtro" cellpadding="0" cellspacing="0">
	                <TR>
	                	<TD class="campoformulario" width="1%">Incluindo:</TD>
	                	<TD class="campoformulario" width="1%">
		                <?php //echo $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo, $filtro->vodemanda->tipo, true, "camponaoobrigatorio", false, "") . "<br>";
	                	  echo $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo."[]", $filtro->vodemanda->tipo, true, "camponaoobrigatorio", false, " multiple $disabledAll ");
	                	  $nmCampoTpDemandaContrato = voDemanda::$nmAtrTpDemandaContrato."[]";
	                	  //echo dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, $filtro->vodemanda->tpDemandaContrato, dominioTipoDemandaContrato::getColecaoConsulta(), 2, false, "", true);
		               	?>
	                	<TD class="campoformulario" width="1%">Excluindo</TD>
	                	<TD class="campoformulario" >
						<?php echo $comboTipo->getHtmlCombo(filtroManterDemanda::$NmAtrTipoExcludente, filtroManterDemanda::$NmAtrTipoExcludente."[]", $filtro->tipoExcludente, true, "camponaoobrigatorio", false, " multiple ");?>	                	
						</TD>
	                </TR>
	                <TR>
	                	<TD class="campoformulario" colspan=4>
	                	<?php
	                		echo dominioTipoDemandaContrato::getHtmlChecksBox($nmCampoTpDemandaContrato, $filtro->vodemanda->tpDemandaContrato, dominioTipoDemandaContrato::getColecaoConsulta(), 2, true, "", true);
	                		$comboTpReajuste = new select(dominioTipoReajuste::getColecao());
	                		echo "Reajuste: " . $comboTpReajuste->getHtmlComObrigatorio(voDemanda::$nmAtrInTpDemandaReajusteComMontanteA,voDemanda::$nmAtrInTpDemandaReajusteComMontanteA, $filtro->vodemanda->inTpDemandaReajusteComMontanteA, false,false);	                		 
	                	?>
	                	</TD>
	                </TR>
	                </TABLE>
                </TD>                                
            </TR>
	        <?php	        
	        require_once (caminho_funcoes . voProcLicitatorio::getNmTabela() . "/biblioteca_htmlProcLicitatorio.php");
	        require_once (caminho_funcoes . voPA::getNmTabela() . "/biblioteca_htmlPA.php");
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitatório:</TH>
	            <TD class="campoformulario"><?php getCampoDadosProcLicitatorio($filtro->voproclic);?>
	            </TD>
	        </TR>	                    
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PAAP:</TH>
	            <TD class="campoformulario" nowrap>
	            <?php getCampoDadosPAAP($filtro->voPA);
	            echo " tem PAAP?: " . $comboSimNao->getHtmlCombo(filtroManterDemanda::$NmAtrInComPAAPInstaurado,
	            								filtroManterDemanda::$NmAtrInComPAAPInstaurado, 
	            								$filtro->inComPAAPInstaurado, true, "camponaoobrigatorio", false,"");?>
	            </TD>
	        </TR>	                    
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" nowrap width="1%">				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value="<?=$filtro->vodemanda->texto?>"  class="camponaoobrigatorio" size="50">
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">PRT/SEI:</TH>
	            <TD class="campoformulario">				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" value="<?php echo($filtro->vodemanda->prt);?>" class="camponaoobrigatorio" size="30">
	            <?php 
	            echo " é SEI?: " . $comboSimNao->getHtmlCombo(filtroManterDemanda::$NmAtrInSEI,
	            								filtroManterDemanda::$NmAtrInSEI, 
	            								$filtro->inSEI, true, "camponaoobrigatorio", false,"");?>
	            </TD>	            	                        	                        
	        </TR>            
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Referência:</TH>
	            <TD class="campoformulario" width="1%">
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtReferenciaInicial?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtReferenciaInicial?>" 
	            			value="<?php echo($filtro->dtReferenciaInicial);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"> a 
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtReferenciaFinal?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtReferenciaFinal?>" 
	            			value="<?php echo($filtro->dtReferenciaFinal);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">					            
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">Dt.Últ.Mov.:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoInicial?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoInicial?>" 
	            			value="<?php echo($filtro->dtUltMovimentacaoInicial);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"> a 
	            	<INPUT type="text" 
	            	       id="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoFinal?>" 
	            	       name="<?=filtroManterDemanda::$NmAtrDtUltimaMovimentacaoFinal?>" 
	            			value="<?php echo($filtro->dtUltMovimentacaoFinal);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">				
				</TD>					            	            
	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%" ROWSPAN=2>Contrato:</TH>
	            <TD class="campoformulario" ROWSPAN=2><?php

	            $voContratoFiltro = new vocontrato();
	            $voContratoFiltro->tipo = $filtro->vocontrato->tipo;
	            $voContratoFiltro->cdContrato = $filtro->vocontrato->cdContrato;
	            $voContratoFiltro->anoContrato = $filtro->vocontrato->anoContrato;
	            $voContratoFiltro->cdEspecie = $filtro->vocontrato->cdEspecie;
	            $voContratoFiltro->sqEspecie = $filtro->vocontrato->sqEspecie;
	             
	            $pArray = array($voContratoFiltro,
	            		constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO,
	            		false,
	            		true,
	            		false,
	            		null,	            		
	            		null);
	             
	            getContratoEntradaArrayGenerico($pArray);
			             
	            		//getContratoEntradaDeDados($filtro->vocontrato->tipo, $filtro->vocontrato->cdContrato, $filtro->vocontrato->anoContrato, $arrayCssClass, null, null);
	            		
	            		?></TD>
	            <TH class="campoformulario" >Valor Global:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>"  value="<?php echo($filtro->vlGlobalInicial);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            							a <INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>"  value="<?php echo($filtro->vlGlobalFinal);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            							</TD>        	            
			</TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				//$combo = new select(dominioAutorizacao::getColecao());				
				$nmCheckAutorizacaoArray = vocontrato::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->vocontrato->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario">
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroManterDemanda::$NmAtrInOR_AND,filtroManterDemanda::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>					            	            
	        </TR>			
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50"></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioClassificacaoContrato.php");
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());
				?>
	            <TH class="campoformulario" nowrap>Classificação:</TH>
	            <TD class="campoformulario" width="1%" colspan=3>
	            <?php 
	            echo $comboClassificacao->getHtmlCombo(voContratoInfo::$nmAtrCdClassificacao,voContratoInfo::$nmAtrCdClassificacao, $filtro->cdClassificacaoContrato, true, "camponaoobrigatorio", true, "");
	            //$radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " required " );
	            
	            echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $filtro->inMaoDeObra, true, "camponaoobrigatorio", false,"");
	            ?>
	        </TR>
            <?php            
            $comboTpDoc = new select(dominioTpDocumento::getColecaoConsulta());

            $voUsuario = new voUsuarioInfo();
            $filtroUsu = new filtroManterUsuario(false);
            $filtroUsu->cdAtrOrdenacao = voUsuarioInfo::$nmAtrName;
            $colecaoUsu = $voUsuario->dbprocesso->consultarTelaConsulta($voUsuario, $filtroUsu);
            
            $comboUsuTramitacao = new select($colecaoUsu, voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName);
            ?>	                    
            <TR>
				<TH class="campoformulario" nowrap width="1%">Doc.Anexo:</TH>
				<TD class="campoformulario" colspan=3>				
				Ano: <?php echo $selectExercicio->getHtmlCombo(voDocumento::$nmAtrAno,voDocumento::$nmAtrAno, $filtro->anoDocumento, true, "camponaoobrigatorio", false, "");?>
				Setor: <?php 
				echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $filtro->cdSetorDocumento, true, "camponaoobrigatorio", false, "");				
				echo "Tipo: ". $comboTpDoc->getHtmlSelect(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tpDocumento, true, "camponaoobrigatorio", true);
				?>
				Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo(complementarCharAEsquerda($filtro->sqDocumento, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
				<?php 
				$nmCamposDoc = array(
						voDocumento::$nmAtrTp,
						voDocumento::$nmAtrAno,
						voDocumento::$nmAtrSq,
						voDocumento::$nmAtrCdSetor,
				);
				echo getBorracha($nmCamposDoc, "");
				?>
			</TR>
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Usuário:</TH>
				<TD class="campoformulario" colspan=3><?php echo $comboUsuTramitacao->getHtmlSelect(filtroManterDemanda::$NmAtrCdUsuarioTramitacao,filtroManterDemanda::$NmAtrCdUsuarioTramitacao, $filtro->cdUsuarioTramitacao, true, "camponaoobrigatorio", false);?>
			</TR>
            <TR>
				<TH class="campoformulario" nowrap width="1%">Tempo.Vida.Mínimo:</TH>
				<TD class="campoformulario" colspan=3>				
				Última Tramitação: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram?>" name="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram?>"  value="<?php echo(complementarCharAEsquerda($filtro->nuTempoVidaMinimoUltimaTram, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="3" maxlength="3"> (dias)|				
				Total: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo?>" name="<?=filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo?>"  value="<?php echo(complementarCharAEsquerda($filtro->nuTempoVidaMinimo, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="3" maxlength="3"> (dias)
				<?php 
				$nmCamposDoc = array(
						filtroManterDemanda::$ID_REQ_NuTempoVidaMinimo,
						filtroManterDemanda::$ID_REQ_NuTempoVidaMinimoUltimaTram,
				);
				echo getBorracha($nmCamposDoc, "");
				?>
			</TR>