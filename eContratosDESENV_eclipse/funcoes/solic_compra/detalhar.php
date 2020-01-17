<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

//inicia os parametros
inicio();

$vo = new voSolicCompra();
$vo->getVOExplodeChave();
//var_dump($vo);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
$vo->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = voSolicCompra::getTituloJSP();
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
<?=setTituloPagina(voSolicCompra::getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">

<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrCd?>" name="<?=voSolicCompra::$nmAtrCd?>" value="<?=$vo->cd?>">
<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrAno?>" name="<?=voSolicCompra::$nmAtrAno?>" value="<?=$vo->ano?>">
<INPUT type="hidden" id="<?=voSolicCompra::$nmAtrUG?>" name="<?=voSolicCompra::$nmAtrUG?>" value="<?=$vo->ug?>">
 
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
                <INPUT type="hidden" id="<?=voSolicCompra::$nmAtrSqHist?>" name="<?=voSolicCompra::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>  
            <?php }
            if($vo->anoDemanda != null){
	            $voDemanda = new voDemanda(array($vo->anoDemanda, $vo->cdDemanda));
	            $voDemanda->tipo = dominioTipoDemanda::$CD_TIPO_DEMANDA_SOLIC_COMPRA;
	            getDemandaDetalhamento($voDemanda, true);
            }         
            
            ?>
            <TR>
		         <TH class="campoformulario" nowrap width="1%">Solic.Compra:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo(getDetalhamentoHTML("", "", formatarCodigoAnoComplementoArgs ( $vo->cd, $vo->ano, TAMANHO_CODIGOS, $vo->ug.".", false)));?>
				 </TD>
	        </TR>                         
	        <?php	       			
			$comboTipo = new select(dominioTipoSolicCompra::getColecao());
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap>Tipo:</TH>
	            <TD class="campoformulario" width="1%" colspan=3><?php echo dominioTipoSolicCompra::getHtmlDetalhamento(voSolicCompra::$nmAtrTipo, voSolicCompra::$nmAtrTipo, $vo->tipo, false);?></TD>
				</TD>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Objeto:</TH>
	            <TD class="campoformulario" colspan="3">
	            <textarea rows="5" cols="80" id="<?=voSolicCompra::$nmAtrObjeto?>" name="<?=voSolicCompra::$nmAtrObjeto?>" class="camporeadonly" readonly><?php echo($vo->objeto);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voSolicCompra::$nmAtrValor?>" name="<?=voSolicCompra::$nmAtrValor?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            class="camporeadonly" size="15" readonly></TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3">
	            <textarea rows="5" cols="80" id="<?=voSolicCompra::$nmAtrObservacao?>" name="<?=voSolicCompra::$nmAtrObservacao?>" class="camporeadonly" readonly><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>        	
				<?php				
				/*if($voDemanda->cd != null){
					$filtroTramitacaoContrato = new filtroConsultarDemandaContrato(false);
					$filtroTramitacaoContrato->vodemanda->cd = $voDemanda->cd;
					$filtroTramitacaoContrato->vodemanda->ano = $voDemanda->ano;
					//$filtroTramitacaoContrato->temDocumentoAnexo = constantes::$CD_SIM;
					$filtroTramitacaoContrato->TemPaginacao = false;
					$filtroTramitacaoContrato->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrSq;
					$filtroTramitacaoContrato->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;
					
					$dbcontrato = new dbcontratoinfo();
					$colecaoTramitacao = $dbcontrato->consultarDemandaTramitacaoContrato($filtroTramitacaoContrato);
					mostrarGridDemandaContrato($colecaoTramitacao, true, false);
				}*/
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