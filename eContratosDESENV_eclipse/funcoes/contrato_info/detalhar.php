<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
try{
	inicio();

$vo = new voContratoInfo();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
$vo->getDadosBanco($colecao);
putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "DETALHAR ";
$titulo = $vo->getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Hist�rico";

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido())
		return false;

	return confirm("Confirmar Alteracoes?");    
}

function iniciar(){
	var pNmCampoDiv = "<?=voContratoInfo::$NM_DIV_CONTRATO_SUBS?>";
	var pIDCampo = "<?=voContratoInfo::$nmAtrSEIContratoSubstituto?>";		
	getContratoSubstituto(pIDCampo, pNmCampoDiv);
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
 
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
                <INPUT type="hidden" id="<?=voContratoInfo::$nmAtrSqHist?>" name="<?=voContratoInfo::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }	        	        	        
	        
			$voContrato = $vo->getVOContrato($colecao);
			//echo $voContrato->vlMensal . "teste";
	          
 	        require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
 	        //getContratoDetalhamento($voContrato, $colecao);
 	        getContratoDet($voContrato);
 	        //getColecaoContratoDet($vo->colecaoContrato);
 	        
 	        include_once(caminho_util. "dominioSimNao.php");
 	        $comboSimNao = new select(dominioSimNao::getColecao());
 	        
			?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Estudo T�cnico:</TH>
	            <TD class="campoformulario" width="1%" colspan=3>
	            <?php 
	            
	            echo dominioEstudoTecnicoSAD::getHtmlDetalhamento(
	            		voContratoInfo::$nmAtrInEstudoTecnicoSAD,
	            		voContratoInfo::$nmAtrInEstudoTecnicoSAD, 
	            		$vo->inEstudoTecnicoSAD,
	            		false,
	            		dominioEstudoTecnicoSAD::getColecaoFormatada()
	            		);?>
	            </TD>	
	        </TR>
	        
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				$combo = new select(dominioAutorizacao::getColecao());
				$dadosContratoCompilado = consultarDadosContratoCompilado($voContrato);
				//por enquanto ta pegando do registro mais antigo (na teoria eh o contrato mater)
				//MELHORAR
				if($dadosContratoCompilado!=null){
					$cdAutorizacaoPlanilha =  $dadosContratoCompilado[vocontrato::$nmAtrCdAutorizacaoContrato];
				}
				?>
	            <TH class="campoformulario" width="1%" nowrap>Autoriza��o:</TH>
	            <TD class="campoformulario" nowrap>
	            Planilha: <?php echo $combo->getHtmlCombo("","", $cdAutorizacaoPlanilha, true, "camporeadonly", true, " disabled ");?>	            
	            Atual: <?php echo $combo->getHtmlCombo(voContratoInfo::$nmAtrCdAutorizacaoContrato,voContratoInfo::$nmAtrCdAutorizacaoContrato, $vo->cdAutorizacao, true, "camporeadonly", true, " disabled ");?>
				</TD>	            	        	            		            
	            <TH class="campoformulario" nowrap width="1%">Pend�ncias:</TH>
	            <TD class="campoformulario" colspan=1>
	            <?php 
	            echo dominioAutorizacao::getHtmlChecksBoxDetalhamento("", $vo->inPendencias, 1);	             
	             ?>
	            </TD>	            
	        </TR>
	        			
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioClassificacaoContrato.php");
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());				
				?>
	            <TH class="campoformulario" width="1%" nowrap>Classifica��o:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo dominioClassificacaoContrato::getHtmlDetalhamento(voContratoInfo::$nmAtrCdClassificacao, voContratoInfo::$nmAtrCdClassificacao, $vo->cdClassificacao);
	            $radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;Planilha de custos/forma��o de pre�o?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " disabled " );	             
	            echo "&nbsp;&nbsp;Planilha de custos/forma��o de pre�o?: " 
						. $radioMaodeObra->getHTMLDetalhamento( 
						voContratoInfo::$nmAtrInMaoDeObra 
						,voContratoInfo::$nmAtrInMaoDeObra
						, $vo->inMaoDeObra);
	            ?>
	        </TR>			
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Data.Proposta de pre�os:</TH>
	            <TD class="campoformulario" width="1%">
	            	<INPUT type="text" 
	            	       id="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            	       name="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            			value="<?php echo(getData($vo->dtProposta));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10"
	            			readonly>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Data.Base Reajuste:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voContratoInfo::$nmAtrDtBaseReajuste?>" 
	            	       name="<?=voContratoInfo::$nmAtrDtBaseReajuste?>" 
	            			value="<?php echo(getData($vo->dtBaseReajuste));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10"
	            			readonly>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Prorroga��o:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo dominioProrrogacaoContrato::getHtmlDetalhamento(voContratoInfo::$nmAtrInPrazoProrrogacao,voContratoInfo::$nmAtrInPrazoProrrogacao, $vo->inPrazoProrrogacao);?>
	            </TD>
	            <TH class="campoformulario" width="1%">
	            <?=getTextoHTMLTagMouseOver("SEI.Contrato.Substituto", voContratoInfo::$TEXTO_TAG_CONTRATO_SUBST)?>:
	            </TH>
	            <TD class="campoformulario">
	            <?php
	            echo getDetalhamentoHTML(voContratoInfo::$nmAtrSEIContratoSubstituto
	            		, voContratoInfo::$nmAtrSEIContratoSubstituto
	            		, voDemandaTramitacao::getNumeroPRTComMascara($vo->SEIContratoSubstituto, false));
	            ?>
						<div id="<?=voContratoInfo::$NM_DIV_CONTRATO_SUBS?>">				  
				        </div>	
				</TD>	            
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Caracter�sticas:</TH>
	            <TD class="campoformulario" colspan=3>
	            <abbr title="Sem valor referencial mensal?">� por escopo?:</abbr>
	            <?php 
	            echo dominioSimNao::getHtmlDetalhamentoRadio(voContratoInfo::$nmAtrInEscopo, voContratoInfo::$nmAtrInEscopo, $vo->inEscopo);
	            echo " | � credenciamento?:" 
					. dominioSimNao::getHtmlDetalhamentoRadio(voContratoInfo::$nmAtrInCredenciamento, voContratoInfo::$nmAtrInCredenciamento, $vo->inCredenciamento);
	            echo " | Ser� prorrogado?:" 
					. dominioSimNao::getHtmlDetalhamentoRadio(voContratoInfo::$nmAtrInSeraProrrogado, voContratoInfo::$nmAtrInSeraProrrogado, $vo->inSeraProrrogado);
	            ?>
	        	</TD>
	        </TR>	        
	        <?php	        
	        include_once(caminho_funcoes. "contrato/dominioTpGarantiaContrato.php");
	        $comboGarantia = new select(dominioTpGarantiaContrato::getColecao());
	        $jsGarantia = "formataFormTpGarantia('".voContratoInfo::$nmAtrInTemGarantia."', '".voContratoInfo::$nmAtrTpGarantia."');"
	        ?>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Garantia:</TH>
	            <TD class="campoformulario" colspan="3">
	            <?php 
	            echo "Tem?: " 
					//. $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInTemGarantia,voContratoInfo::$nmAtrInTemGarantia, $vo->inTemGarantia, true, "camporeadonly", false, " disabled ");
	            	. dominioSimNao::getHtmlDetalhamentoRadio(voContratoInfo::$nmAtrInTemGarantia, voContratoInfo::$nmAtrInTemGarantia, $vo->inTemGarantia);
	            if($vo->inTemGarantia != constantes::$CD_NAO){
	            	echo "Tipo: " . $comboGarantia->getHtmlCombo(voContratoInfo::$nmAtrTpGarantia,voContratoInfo::$nmAtrTpGarantia, $vo->tpGarantia, true, "camporeadonly", false, " disabled ");
	            }
	            ?>
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Gestor:</TH>
	            <TD class="campoformulario" colspan="3">
                    C�digo:<INPUT type="text" id="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" name="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" value="<?=complementarCharAEsquerda($colecao[voContratoInfo::$nmAtrCdPessoaGestor], "0", TAMANHO_CODIGOS)?>"  class="camporeadonly" size="5" readonly>
                    Nome: <INPUT type="text" id="<?=voContratoInfo::$IDREQNmPessoaGestor?>" name="<?=voContratoInfo::$IDREQNmPessoaGestor?>" value="<?=$colecao[voContratoInfo::$IDREQNmPessoaGestor]?>"   class="camporeadonly" size="30" readonly>
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observa��o:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoInfo::$nmAtrObs?>" name="<?=voContratoInfo::$nmAtrObs?>" class="camporeadonly" readonly><?=$vo->obs?></textarea>
				</TD>
	        </TR>
				<?php 
				if(!$isHistorico){
					include_once '../demanda/biblioteca_htmlDemanda.php';
					$nmTabelaDemandaTramDoc = voDemandaTramDoc::getNmTabela();
					$filtroTramitacaoContrato = new filtroConsultarDemandaContrato(false);
					$filtroTramitacaoContrato->vocontrato->cdContrato = $vo->cdContrato;
					$filtroTramitacaoContrato->vocontrato->anoContrato = $vo->anoContrato;
					$filtroTramitacaoContrato->vocontrato->tipo = $vo->tipo;				
					$filtroTramitacaoContrato->temDocumentoAnexo = constantes::$CD_SIM;
					//agrupa por documentos
					$filtroTramitacaoContrato->groupby = voDocumento::getAtributosChavePrimaria();
					$filtroTramitacaoContrato->TemPaginacao = false;
					/*$filtroTramitacaoContrato->cdAtrOrdenacao = voDemandaTramitacao::$nmAtrDhUltAlteracao;
					$filtroTramitacaoContrato->cdOrdenacao = constantes::$CD_ORDEM_DECRESCENTE;*/
	
					$filtroTramitacaoContrato->cdAtrOrdenacao = 
					" $nmTabelaDemandaTramDoc.".voDemandaTramDoc::$nmAtrAnoDoc . " " . constantes::$CD_ORDEM_DECRESCENTE
					. ", $nmTabelaDemandaTramDoc.".voDemandaTramDoc::$nmAtrDhInclusao . " " . constantes::$CD_ORDEM_DECRESCENTE;
					
					$colecaoTramitacao = $vo->dbprocesso->consultarDemandaTramitacaoContrato($filtroTramitacaoContrato);
					mostrarGridDemandaContrato($colecaoTramitacao, true);
				}
				?>	        
	        
<TR>
	<TD halign="left" colspan="4">
	<DIV class="textoseparadorgrupocampos">&nbsp;</DIV>
	</TD>
</TR>        	        	
	        <?php
	        if(!$isInclusao){
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
	        }
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
<?php 
}catch (excecaoGenerica $ex){
	tratarExcecaoHTML($ex);
	//echo $ex->getMessage();
}?>

</BODY>
</HTML>