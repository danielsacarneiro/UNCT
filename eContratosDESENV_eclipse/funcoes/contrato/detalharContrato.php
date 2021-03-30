<?php
include_once("../../config_lib.php");
include(caminho_util."bibliotecaHTML.php");
include_once("dominioTipoContrato.php");
include_once(caminho_vos."dbcontrato.php");

//inicia os parametros
inicio();

$vo = $voContrato = new voContrato();

$classChaves = "camporeadonly";
$readonly = "readonly";		
	
	$voContrato->getVOExplodeChave($chave);	 
    $isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");    
        
	$dbprocesso = new dbcontrato();				
	$colecao = $dbprocesso->limpaResultado();
	$msgComplementar = null;
	
	//echo $voContrato->getValorChaveHTML();
	try{
		$colecao = $dbprocesso->consultarContratoPorChave($voContrato, $isHistorico);
	}catch (excecaoGenerica $excecao){
		//caso nao exista o contrato passado como parametro, tenta buscar sempre o mater
		$voContrato->cdEspecie = dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
		$voContrato->sqEspecie = 1;
		$colecao = $dbprocesso->consultarContratoPorChave($voContrato, $isHistorico);
		$msgComplementar = "TERMO BUSCADO INEXISTENTE - EXIBINDO O CONTRATO MATER.";
				
	}catch (excecaoGenerica $excecao){
		//nao existindo direciona para a pagina de erro;
		tratarExcecaoHTML($excecao);
	}	
	$registrobanco = $colecao[0];
	
	$voContrato->getDadosBanco($registrobanco);
	
	//echo $voContrato->linkMinutaDoc;
	
	$voContratoInfo = new voContratoInfo();
	$voContratoInfo->getDadosBanco($colecao[0]);
	
	putObjetoSessao($voContrato->getNmTabela(), $voContrato);

	$nmGestor  = $voContrato->gestor;
	$nmGestorPessoa  = $voContrato->nmGestorPessoa;
	$nmContratada  = $voContrato->contratada;
	$docContratada  = $voContrato->docContratada;	
	$dsObjeto  = $voContrato->objeto;
	$dtVigenciaInicial  = $voContrato->dtVigenciaInicial;
	$dtVigenciaFinal  = $voContrato->dtVigenciaFinal;	
	$vlMensal  = $voContrato->vlMensal;
	$vlGlobal  = $voContrato->vlGlobal;	
	$procLic = $voContrato->procLic;
	$modalidade = $voContrato->modalidade;
	$dtAssinatura = $voContrato->dtAssinatura;
	$dtPublicacao = $voContrato->dtPublicacao;
    $dataPublicacao = $voContrato->dataPublicacao;
	$empenho = $voContrato->empenho;
	$tpAutorizacao = $voContrato->tpAutorizacao;
	$licom = $voContrato->licom;
    $obs = $voContrato->obs;
    
//echo $voContrato->importacao;

$complementoTit = "";
$isExclusao = false;
if($isHistorico)
    $complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$titulo = "EXCLUIR CONTRATO";
    $isExclusao = true;
}else{
    $titulo = "DETALHAR CONTRATO";
}
$titulo .=  $complementoTit;
setCabecalho($titulo);    

?>
<!DOCTYPE html>

<HEAD>    
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<?=setTituloPagina($titulo)?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarManterContrato.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_CONTEXTO_SESSAO%>" value="<%=idContextoSessao%>"> 
<INPUT type="hidden" id="evento" name="<%=PRManterReferenciaLegal.ID_REQ_EVENTO%>" value=""> 
<INPUT type="hidden" id="nao_utilizar_id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_NAO_UTILIZAR_ID_CONTEXTO_SESSAO%>" value=""> 

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqHist?>" name="<?=vocontrato::$nmAtrSqHist?>" value="<?=$voContrato->sqHist?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqContrato?>" name="<?=vocontrato::$nmAtrSqContrato?>" value="<?=$voContrato->sq?>">
 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
<TBODY>
	<TR>
        <TD class="conteinerfiltro"><?=cabecalho?></TD>
	</TR>
    <TR>
        <TD class="conteinerfiltro">
        <DIV id="div_filtro" class="div_filtro">
        <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
            <TBODY>
            <?php if($isHistorico){?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sq.Hist:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->sqHist, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                <INPUT type="hidden" id="<?=voRegistroLivro::$nmAtrSqHist?>" name="<?=voRegistroLivro::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>
           <?php
			}
           if($msgComplementar != null){
           ?>
			<TR>
	            <TD class="campoformulario" colspan="4"><?=getTextoHTMLDestacado($msgComplementar)?></TD>
	        </TR>  
        <?php
          }
        require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
        //getContratoDet($voContrato, true);      
        getContratoDet($voContrato, true, true);
        ?>
		<TR>
            <TH class="campoformulario" nowrap>Unid.Demandante:</TH>
            <TD class="campoformulario" colspan="3">
			<INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="camporeadonly" size="50" readonly></TD>                </TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Objeto:</TH>
            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="dsObjeto" name="dsObjeto" class="camporeadonly" <?=$readonly?>><?php echo($dsObjeto);?></textarea>
			</TD>
        </TR>

		<TR>
            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
            <TD class="campoformulario" width="1%">
            <INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($procLic);?>"  class="camporeadonly" size="50" <?=$readonly?>>
            <?php            
            if($voContrato->cdProcLic != null){
            	$voproclictemp = new voProcLicitatorio();
            	$voproclictemp->cd = $voContrato->cdProcLic;
            	$voproclictemp->ano = $voContrato->anoProcLic;
            	$cdModalidade = $voproclictemp->cdModalidade = $voContrato->cdModalidadeLic;
            	 
            	echo "Resultado importação e-Conti: ".formatarCodigoAno($voContrato->cdProcLic, $voContrato->anoProcLic) 
            		. "-" . dominioModalidadeProcLicitatorio::getDescricao($cdModalidade);
            	            	 
            	echo getLinkPesquisa ( "../proc_licitatorio/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $voproclictemp->getValorChaveHTML() );
            }
            ?>
            </TD>
            <TH class="campoformulario" nowrap width="1%">Características:</TH>
            <TD class="campoformulario">
            <?php 
            echo dominioTipoDemandaContrato::getHtmlChecksBoxDetalhamento("", $voContrato->inCaracteristicas, 1);	             
             ?>
            </TD>            
        </TR>
		<!-- <TR>
            <TH class="campoformulario" nowrap>Modalidade:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrModalidadeContrato?>" name="<?=vocontrato::$nmAtrModalidadeContrato?>"  value="<?php echo($modalidade);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
        </TR> -->
		<TR>
            <TH class="campoformulario" nowrap>Valor Mensal:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrVlMensalContrato?>" name="<?=vocontrato::$nmAtrVlMensalContrato?>"  value="<?php echo($vlMensal);?>"  class="camporeadonlyalinhadodireita" size="15" <?=$readonly?>></TD>
        </TR>					
		<TR>
            <TH class="campoformulario" nowrap>Valor Global:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrVlGlobalContrato?>" name="<?=vocontrato::$nmAtrVlGlobalContrato?>"  value="<?php echo($vlGlobal);?>"  class="camporeadonlyalinhadodireita" size="15" <?=$readonly?>></TD>
        </TR>
		
		<TR>
            <TH class="campoformulario" nowrap>Período de Vigencia:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            			value="<?php echo($dtVigenciaInicial);?>" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>> a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo($dtVigenciaFinal);?>" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
            	<?php
            	//$numDias = getQtdDiasEntreDatas(getDataSQL($dtVigenciaInicial), getDataSQL($dtVigenciaFinal));
            	$numDias = getQtdDiasEntreDatas($dtVigenciaInicial, $dtVigenciaFinal);
            	?>		
            	<INPUT type="text" 
            	       id="<?=vocontrato::$ID_REQ_NumDias?>" 
            	       name="<?=vocontrato::$ID_REQ_NumDias?>" 
            			value="<?php echo($numDias);?>" 
            			class="camporeadonly" 
            			size="4" 
            			readonly> (dias aprox.)            			
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Assinatura:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            			value="<?php echo($voContrato->dtAssinatura);?>" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Publicação:</TH>
            <TD class="campoformulario">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtPublicacaoContrato?>" 
            			value="<?php echo($dtPublicacao);?>" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
			</TD>
            <TH class="campoformulario" nowrap>Campo Extenso Data Publicacao:</TH>
            <TD class="campoformulario">
            	<INPUT type="text" 
            			value="<?php echo($dataPublicacao);?>"
            			class="camporeadonly" 
            			size="50" 
            			readonly>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Proposta:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtProposta?>" 
            	       name="<?=vocontrato::$nmAtrDtProposta?>" 
            			value="<?php echo(getData($voContratoInfo->dtProposta));?>" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10"
                        readonly >
			</TD>
        </TR>                                
	<TR>
        <TH class="campoformulario" nowrap>Empenho:</TH>
        <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrNumEmpenhoContrato?>" name="<?=vocontrato::$nmAtrNumEmpenhoContrato?>"  value="<?php echo($empenho);?>"  class="camporeadonly" size="20" <?=$readonly?>></TD>
    </TR>
	<TR>
				<?php
			include_once("dominioAutorizacao.php");
			$autorizacao = new dominioAutorizacao();
			$combo = new select($autorizacao->colecao);						
			?>
	
        <TH class="campoformulario" nowrap>Autorizacao Previa:</TH>
        <TD class="campoformulario" colspan="3"><?php echo $combo->getHtmlCombo(vocontrato::$nmAtrCdAutorizacaoContrato,vocontrato::$nmAtrCdAutorizacaoContrato, $voContrato->cdAutorizacao, true, "camporeadonly", true, " disabled");?>
        <!-- <INPUT type="text" id="<?=vocontrato::$nmAtrTipoAutorizacaoContrato?>" name="<?=vocontrato::$nmAtrTipoAutorizacaoContrato?>"  value="<?php echo($tpAutorizacao);?>"  class="camporeadonly" size="10" <?=$readonly?>>--></TD> 
    </TR>
    <?php
    $licom = $registrobanco[voContratoLicon::$nmAtrSituacao];
    $incluidoLicon = array_key_exists($licom, dominioSituacaoContratoLicon::getColecaoIncluidoSucesso());
    ?>
	<TR>
        <TH class="campoformulario" nowrap>LICON:</TH>
        <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrInLicomContrato?>" name="<?=vocontrato::$nmAtrInLicomContrato?>"  value="<?php echo dominioSimNao::getDescricao($incluidoLicon);?>"  class="camporeadonly" size="10" <?=$readonly?>></TD>
    </TR>
	<TR>
        <TH class="campoformulario" nowrap>Observacao:</TH>
        <TD class="campoformulario" colspan="3">
			<textarea rows=5 cols="80" id="<?=vocontrato::$nmAtrObservacaoContrato?>" name="<?=vocontrato::$nmAtrObservacaoContrato?>" class="camporeadonly" <?=$readonly?>><?php echo($obs);?></textarea>  
		</TD>
    </TR>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">Documento:</TH>
		        <?php
		        $endereco = $voContrato->getLinkDocumento();
		        $enderecoMinuta = $voContrato->getEnredeçoDocumento($voContrato->linkMinutaDoc);
		        $isContratoPlanilha = $voContrato->importacao != constantes::$CD_NAO;
		        
		        $arrayRetorno = getHTMLDocumentosContrato($voContrato);
		        $temDocsAExibir = $arrayRetorno[0];
		        $docsAexibir = $arrayRetorno[1]; 
		        ?>
				<TD class="campoformulario" colspan=3>
				<?php
				if($isContratoPlanilha && !$temDocsAExibir){
				?>
				<TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
					<TR>						
						<TH class="campoformulario">
						Minuta:
						</TH>											
						<TD class="campoformulario">
				        <textarea id="<?=vocontrato::$nmAtrLinkMinutaDoc?>" name="<?=vocontrato::$nmAtrLinkMinutaDoc?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $enderecoMinuta;?></textarea>
					    	<?php    	
					    	echo getBotaoAbrirDocumento(vocontrato::$nmAtrLinkMinutaDoc);
					    	?>					        
				        </TD>
						</TR>
						<TR>
							<TH class="campoformulario">
							Assinado:
							</TH>			
							<TD class="campoformulario">
					        <textarea id="<?=vocontrato::$nmAtrLinkDoc?>" name="<?=vocontrato::$nmAtrLinkDoc?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $endereco;?></textarea>
					    	<?php    	
					    	echo getBotaoAbrirDocumento(vocontrato::$nmAtrLinkDoc);
					    	?>				
							</TD>						
					</TR>
			    </TABLE>
			   <?php
				}else{				
				?>
				<TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
					<TR>
				<?php
				echo $docsAexibir;
				?>					
					</TR>
			    </TABLE>
				<?php 
				}
				?>	
				</TD>								
			</TR>
    
	<TR><?=incluirUsuarioDataHoraDetalhamento($voContrato);?></TR>
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
							<TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick=\"javascript:movimentacoes('".$voContrato->getValorChaveHTML()."');\" accesskey='m'")?></TD>	                    	
							<?=getBotoesRodape();?>						    
							</TR>
		                    </TABLE>
	                    </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
