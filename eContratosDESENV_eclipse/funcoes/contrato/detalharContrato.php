<?php
include_once("../../config_lib.php");
include(caminho_util."bibliotecaHTML.php");
include_once("dominioTipoContrato.php");
include_once(caminho_vos."dbcontrato.php");

//inicia os parametros
inicio();

$voContrato = new voContrato();

$classChaves = "camporeadonly";
$readonly = "readonly";		
	
	$voContrato->getVOExplodeChave($chave);	 
    $isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");    
        
	$dbprocesso = new dbcontrato();				
	$colecao = $dbprocesso->limpaResultado();
	$colecao = $dbprocesso->consultarContratoPorChave($voContrato, $isHistorico);	
	$voContrato->getDadosBanco($colecao[0]);	
	
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
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
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
        <?php                    
        $dominioTipoContrato = new dominioTipoContrato();
        ?>
        <TR>
            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
            <TD class="campoformulario" colspan=3>
                    <INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>"  value="<?php echo($voContrato->anoContrato);?>">
                    <INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo($voContrato->cdContrato);?>">
                    <INPUT type="text" value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3)."/".$voContrato->anoContrato);?>"  class="<?=$classChaves?>" size="10" <?=$readonly?>>
                    <INPUT type="text" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>"  value="<?php echo($dominioTipoContrato->getDescricao($voContrato->tipo));?>"  class="camporeadonly" size="7" maxlength="5" <?=$readonly?>>
            </TD>			
        </TR>
        <TR>
            <TH class="campoformulario" nowrap>Espécie/Ordem:</TH>
            <TD class="campoformulario" colspan="3">
                    <?php                        
                    $dsEspecie = getDsEspecie($voContrato);
                    ?>
                     <INPUT type="text" value="<?php echo(strtoupper($dsEspecie));?>"  class="camporeadonlydestacado" size="30" <?=$readonly?>>
                     <INPUT type="hidden" id="<?=vocontrato::$nmAtrEspecieContrato?>" name="<?=vocontrato::$nmAtrEspecieContrato?>"  value="<?=$voContrato->especie;?>">
                     <INPUT type="hidden" id="<?=vocontrato::$nmAtrSqEspecieContrato?>" name="<?=vocontrato::$nmAtrSqEspecieContrato?>"  value="<?=$voContrato->sqEspecie;?>">
        </TR>                    
		<TR>
            <TH class="campoformulario" nowrap>Gestor:</TH>
            <TD class="campoformulario" colspan="3">
                <?php
                 include_once(caminho_funcoes. "gestor/biblioteca_htmlGestor.php");
                 if($voContrato->cdGestor != null)
                    echo getComboGestorMais(new dbgestor(), vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $voContrato->cdGestor, "camporeadonly", " disabled ");
                 
                 
                 if($voContrato->gestor != null){
                    echo "<INPUT type='text' id='nmGestor' name='nmGestor'  value='". $nmGestor ."'  class='camporeadonly' size='50' " . $readonly . ">";
                }
                ?>
                </TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Responsavel:</TH>
            <TD class="campoformulario" colspan="3">
                <?php
                 include_once(caminho_funcoes. "pessoa/biblioteca_htmlPessoa.php");
                 if($voContrato->cdGestorPessoa != null)
                    echo getComboGestorPessoaMais(new dbgestorpessoa(), vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $voContrato->cdGestor, $voContrato->cdGestorPessoa, "camporeadonly", " disabled ");
                 
                 if($voContrato->nmGestorPessoa != null){
                    echo "<INPUT type='text' id='nmGestor' name='nmGestor'  value='". $nmGestorPessoa ."'  class='camporeadonly' size='50' " . $readonly . ">";                
                 }
                ?>
                </TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
            <TD class="campoformulario" width="1%"><INPUT type="text" id="nmContratada" name="nmContratada"  value="<?php echo($nmContratada);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="docContratada" name="docContratada"  value="<?php echo(documentoPessoa::getNumeroDocFormatado($docContratada));?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camporeadonly" size="20" maxlength="20" <?=$readonly?>></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Objeto:</TH>
            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="dsObjeto" name="dsObjeto" class="camporeadonly" <?=$readonly?>><?php echo($dsObjeto);?></textarea>
			</TD>
        </TR>

		<TR>
            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrProcessoLicContrato?>" name="<?=vocontrato::$nmAtrProcessoLicContrato?>"  value="<?php echo($procLic);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Modalidade:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrModalidadeContrato?>" name="<?=vocontrato::$nmAtrModalidadeContrato?>"  value="<?php echo($modalidade);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
        </TR>
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
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>> a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo($dtVigenciaFinal);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Data Assinatura:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtAssinaturaContrato?>" 
            			value="<?php echo($voContrato->dtAssinatura);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
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
            			onkeyup="formatarCampoData(this, event, false);" 
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
            			value="<?php echo($voContrato->dtProposta);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
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
	<TR>
        <TH class="campoformulario" nowrap>LICON:</TH>
        <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrInLicomContrato?>" name="<?=vocontrato::$nmAtrInLicomContrato?>"  value="<?php if($licom=="S")echo("SIM"); else echo("NÃO");?>"  class="camporeadonly" size="10" <?=$readonly?>></TD>
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
        ?>                
        <TD class="campoformulario" colspan=3><textarea id="<?=vocontrato::$nmAtrLinkDoc?>" name="<?=vocontrato::$nmAtrLinkDoc?>" rows="2" cols="80" class="camporeadonly" readonly><?php echo  $endereco;?></textarea>
    	<?php    	
    	echo getBotaoAbrirDocumento(vocontrato::$nmAtrLinkDoc);
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
