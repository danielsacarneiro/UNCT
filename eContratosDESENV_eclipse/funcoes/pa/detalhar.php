<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once (caminho_funcoes . "pa/biblioteca_htmlPA.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

//inicia os parametros
inicio();

$vo = new voPA();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
$vo->getDadosBanco($colecao);

$voContrato = new vocontrato();
$voContrato->getDadosBanco($colecao);

$voDemanda = new voDemanda();
$voDemanda->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = voPA::getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
	$isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);
?>

<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
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
<?=setTituloPagina(voPA::getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">
<INPUT type="hidden" id="<?=voPA::$nmAtrAnoPA?>" name="<?=voPA::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
 
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
                <INPUT type="hidden" id="<?=voPA::$nmAtrSqHist?>" name="<?=voPA::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>  
            <?php }

            getDemandaDetalhamento($voDemanda);
			getContratoDet($voContrato);
	         
	        //require_once ("dominioSituacaoPA.php");	        
	        $domSiPA = new dominioSituacaoPA();
	        $situacao = $colecao[voPA::$nmAtrSituacao];
	        $situacao = $domSiPA->getDescricao($situacao);
	        ?>
            <TR>
				<TH class="campoformulario" nowrap>Servidor Responsável:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");                    
                    echo getComboPessoaRespPA(voPA::$nmAtrCdResponsavel, voPA::$nmAtrCdResponsavel, $vo->cdResponsavel, "camponaoobrigatorio", "disabled");                                        
                    ?>
            </TR>
            <?php 
            if(!isContratoValido($voContrato)){
            ?>
            <TR>
            	<TH class="campoformulario" nowrap>CNPJ/CPF imputada:</TH>
            	<TD class="campoformulario" colspan=3>
            	<?php 
            	echo getHTMLInputNumDocumentoPessoa(voPA::$nmAtrNumDocImputada, voPA::$nmAtrNumDocImputada, documentoPessoa::getNumeroDocFormatado($vo->numDocImputada), "camporeadonly");
            	?>
            	</TD>
            </TR>
			<?php 
       		}
   			?>
			<TR>
	            <TH class="campoformulario" nowrap>Situação:</TH>
	            <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(strtoupper($situacao));?>"  class="camporeadonly" size="20" readonly></TD>
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data Abertura:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtAbertura?>" 
	            	       name="<?=voPA::$nmAtrDtAbertura?>" 
	            			value="<?php echo(getData($vo->dtAbertura));?>"	            			 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
				</TD>
        	</TR>
			<!-- <TR>
	            <TH class="campoformulario" nowrap>Dt.Notificação Nota Imputação:</TH>
	            <TD class="campoformulario" colspan=3>
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtNotificacao?>" 
	            	       name="<?=voPA::$nmAtrDtNotificacao?>" 
	            			value="<?php echo(getData($vo->dtNotificacao));?>"	            			 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>	            			
				</TD>
        	</TR>-->
			<TR>
	            <TH class="campoformulario">Dt.Notificação.Últ.Manifestação:</TH>
	            <TD class="campoformulario" colspan=3>
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            	       name="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            			value="<?php echo(getData($vo->dtUlNotificacaoParaManifestacao));?>"
							class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
	            			
				<?php echo " Prazo:".getInputText(voPA::$nmAtrNumDiasPrazoUltNotificacao, voPA::$nmAtrNumDiasPrazoUltNotificacao, $vo->numDiasPrazoUltNotificacao, constantes::$CD_CLASS_CAMPO_READONLY, 3, "")?>
				Prazo Final:
							<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtUltNotificacaoPrazoEncerrado?>" 
	            	       name="<?=voPA::$nmAtrDtUltNotificacaoPrazoEncerrado?>" 
	            			value="<?php echo(getData($vo->dtUlNotificacaoPrazoEncerrado));?>"
							class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
				
				</TD>				
        	</TR>
        	<?php 
        	//$dtPublicacao = $colecao[voDemandaTramitacao::$nmAtrDtReferencia];        	
        	$dtPublicacao = $vo->dtPublicacao;
        	?>
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Publicação:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            			value="<?php echo(getData($dtPublicacao));?>"	            			 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>	            			
				</TD>
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Publicação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="3" cols="80" class="camporeadonly" readonly><?php echo($vo->publicacao);?></textarea>
				</TD>
	        </TR>
        	
				<?php				
				mostrarGridPenalidade($vo);
				
				$filtroTramitacaoContrato = new filtroConsultarDemandaContrato(false);
				$filtroTramitacaoContrato->vocontrato->cdContrato = $voContrato->cdContrato;
				$filtroTramitacaoContrato->vocontrato->anoContrato = $voContrato->anoContrato;
				$filtroTramitacaoContrato->vocontrato->tipo = $voContrato->tipo;
				$filtroTramitacaoContrato->vodemanda->cd = $voDemanda->cd;
				$filtroTramitacaoContrato->vodemanda->ano = $voDemanda->ano;
				//$filtroTramitacaoContrato->temDocumentoAnexo = constantes::$CD_SIM;
				$filtroTramitacaoContrato->TemPaginacao = false;
				$filtroTramitacaoContrato->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrDhInclusao;
				$filtroTramitacaoContrato->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
				
				$dbcontrato = new dbcontratoinfo();
				$colecaoTramitacao = $dbcontrato->consultarDemandaTramitacaoContrato($filtroTramitacaoContrato);
				mostrarGridDemandaContrato($colecaoTramitacao, true, false);
				?>	 
<TR>
	<TD halign="left" colspan="4">
	<DIV class="textoseparadorgrupocampos">&nbsp;</DIV>
	</TD>
</TR>        	
	        <?php 
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";	        	
	        ?>
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