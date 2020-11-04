<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

try{
	//inicia os parametros
	inicioComValidacaoUsuario(true);

	$vo = new voContratoInfo();
	$funcao = @$_GET["funcao"];

	$readonly = "";
	$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

	$classChaves = "campoobrigatorio";
	$readonlyChaves = "";
	
	$nmFuncao = "";
	if($isInclusao){
		$nmFuncao = "INCLUIR ";
	}else{
		$classChaves = "camporeadonly";
		$readonlyChaves = "readonly";

		$readonly = "readonly";
		$vo->getVOExplodeChave();
		$isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");

		$dbprocesso = $vo->dbprocesso;
		$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);
		$vo->getDadosBanco($colecao);

		putObjetoSessao($vo->getNmTabela(), $vo);

		$nmFuncao = "ALTERAR ";
	}


	$titulo = voContratoInfo::getTituloJSP();
	$titulo = $nmFuncao . $titulo;
	setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

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

function formataFormEscopo() {
	campoInEscopo = document.frm_principal.<?=voContratoInfo::$nmAtrInEscopo?>;
	campoProrrogacao = document.frm_principal.<?=voContratoInfo::$nmAtrInPrazoProrrogacao?>;

	var isEscopo =  false;
	try{
		isEscopo =  campoInEscopo.value == "S";
	}catch(ex){
	}

	if(isEscopo && campoProrrogacao.value != ""
		&& campoProrrogacao.value != "<?=dominioProrrogacaoContrato::$CD_ART57_PAR_1?>"){
		exibirMensagem("A prorrogação do contrato por escopo fundamenta-se no art.57, §º1, lei 8666/93.");
		campoProrrogacao.focus();
		campoProrrogacao.value = "";
	}
}


function formataFormClassificacao(pCampoChamada) {
	campoClassificacao = document.frm_principal.<?=voContratoInfo::$nmAtrCdClassificacao?>;
	campoMaodeObra = document.frm_principal.<?=voContratoInfo::$nmAtrInMaoDeObra?>;

	classificacao = campoClassificacao.value;

	if(classificacao == "<?=dominioClassificacaoContrato::$CD_MAO_OBRA?>"){
		campoMaodeObra.value = "<?=constantes::$CD_SIM?>";
	}
	else if(classificacao != "<?=dominioClassificacaoContrato::$CD_SERVICOS?>"){
		campoMaodeObra.value = "<?=constantes::$CD_NAO?>";
	}
	else if(pCampoChamada == null || pCampoChamada.name != "<?=voContratoInfo::$nmAtrInMaoDeObra?>"){		
		campoMaodeObra.value = "";
	}

	if(classificacao == "<?=dominioClassificacaoContrato::$CD_LOCACAO_IMOVEL?>"){
		exibirMensagem("<?=voContratoInfo::getTextoAlertaContratoLocação()?>");
	}
}

function transferirDadosPessoa(cd, nm) {		
	document.getElementById("<?=voContratoInfo::$nmAtrCdPessoaGestor?>").value = completarNumeroComZerosEsquerda(cd, <?=TAMANHO_CODIGOS?>);
	document.getElementById("<?=voContratoInfo::$IDREQNmPessoaGestor?>").value = nm;	

	/*document.getElementsByName("<?=voContratoInfo::$nmAtrCdPessoaGestor?>").item(0).value = completarNumeroComZerosEsquerda(cd, <?=TAMANHO_CODIGOS?>);
	document.getElementsByName("<?=vopessoa::$nmAtrNome?>").item(0).value = nm;*/
}

function iniciar(){
	var pNmCampoDiv = "<?=voContratoInfo::$NM_DIV_CONTRATO_SUBS?>";
	var pIDCampo = "<?=voContratoInfo::$nmAtrSEIContratoSubstituto?>";	
	
	getContratoSubstituto(pIDCampo, pNmCampoDiv);
	formataFormTpGarantia('<?=voContratoInfo::$nmAtrInTemGarantia?>', '<?=voContratoInfo::$nmAtrTpGarantia?>');
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
	        <?php	        
	        $selectExercicio = new selectExercicio();
	        $comboEstudoTecnico = new select(dominioEstudoTecnicoSAD::getColecaoFormatada());
	        $complementoHTML = "";

	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        if(!$isInclusao){
	        	$cdAutorizacao = $vo->cdAutorizacao;
	        	//ALTERACAO
	        	$complementoHTML = " required ";
	        	$readonlyChaves = " readonly ";
	          
	        	$voContrato = $vo->getVOContrato();	 	        
	        	getContratoDet($voContrato);	 	         
	        }else{
	        	//INCLUSAO

	        /*$arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        $arrayComplementoHTML = array(" required onChange='carregaContratada();' ",
	        		" required onBlur='carregaContratada();' ",
	        		" required onChange='carregaContratada();' "	        		
	        );*/	        
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" nowrap colspan=3><?php getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, null, false);//getContratoEntradaDeDados($tipoContrato, $anoContrato, $cdContrato, $arrayCssClass, $arrayComplementoHTML, $nmCampoDiv);?></TD>
	        </TR>	        
	        <?php 
	       }	       
	       ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Estudo Técnico:</TH>
	            <TD class="campoformulario" width="1%"><?php echo $comboEstudoTecnico->getHtmlCombo(voContratoInfo::$nmAtrInEstudoTecnicoSAD,voContratoInfo::$nmAtrInEstudoTecnicoSAD, $vo->inEstudoTecnicoSAD, true, "camponaoobrigatorio", false, " onChange='' required ");?>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				$combo = new select(dominioAutorizacao::getColecao());				
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario"><?php echo $combo->getHtmlCombo(voContratoInfo::$nmAtrCdAutorizacaoContrato,voContratoInfo::$nmAtrCdAutorizacaoContrato, $cdAutorizacao, true, "camponaoobrigatorio", true, " required ");?>
	        </TR>	       
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioClassificacaoContrato.php");
				$comboClassificacao = new select(dominioClassificacaoContrato::getColecao());
				?>
	            <TH class="campoformulario" nowrap>Classificação:</TH>
	            <TD class="campoformulario" width="1%" colspan=3>
	            <?php 
	            echo $comboClassificacao->getHtmlCombo(voContratoInfo::$nmAtrCdClassificacao,voContratoInfo::$nmAtrCdClassificacao, $vo->cdClassificacao, true, "camponaoobrigatorio", true, " onChange='formataFormClassificacao();' required ");
	            //$radioMaodeObra = new radiobutton ( dominioSimNao::getColecao());
	            //echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: " . $radioMaodeObra->getHtmlRadioButton ( voContratoInfo::$nmAtrInMaoDeObra, voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, false, " required " );
	            
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());	             
	            echo "&nbsp;&nbsp;Planilha de custos/formação de preço?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, true, "camponaoobrigatorio", false,
	            		" onChange='formataFormClassificacao(this);' required ");
	            ?>
	        </TR>	       
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Data.Proposta de preços:</TH>
	            <TD class="campoformulario" width="1%">
	            	<INPUT type="text" 
	            	       id="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            	       name="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            			value="<?php echo(getData($vo->dtProposta));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"
	            			required>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Data.Base Reajuste:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voContratoInfo::$nmAtrDtBaseReajuste?>" 
	            	       name="<?=voContratoInfo::$nmAtrDtBaseReajuste?>" 
	            			value="<?php echo(getData($vo->dtBaseReajuste));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10"
	            			required>
				</TD>
	        </TR>	        
	        <?php 	        
	        $comboProrrogacao = new select(dominioProrrogacaoContrato::getColecao());
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Prorrogação:</TH>
	            <TD class="campoformulario" width="1%"><?php echo $comboProrrogacao->getHtmlCombo(voContratoInfo::$nmAtrInPrazoProrrogacao,voContratoInfo::$nmAtrInPrazoProrrogacao, $vo->inPrazoProrrogacao, true, "campoobrigatorio", false," onChange='formataFormEscopo();' ");?>
	            <TH class="campoformulario" nowrap width="1%">
	            <?=getTextoHTMLTagMouseOver("SEI.Contrato.Substituto", "SEI da demanda do contrato MATER substituto da presente contratação.")?>:
	            </TH>
	            <TD class="campoformulario">
			            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voContratoInfo::$nmAtrSEIContratoSubstituto?>" 
			            		name="<?=voContratoInfo::$nmAtrSEIContratoSubstituto?>" 
			            		value="<?=voDemandaTramitacao::getNumeroPRTComMascara($vo->SEIContratoSubstituto, false)?>"  
			            		class="camponaoobrigatorio" size="30" onBlur='getContratoSubstituto()'>       
 							<?php	            
				            $nmCampos = array(voContratoInfo::$nmAtrSEIContratoSubstituto,
				            );
				            echo getBorracha($nmCampos, "getContratoSubstituto();");
				            ?>
						<div id="<?=voContratoInfo::$NM_DIV_CONTRATO_SUBS?>">				  
				        </div>	
				</TD>
	        </TR>
	        <?php	        
	        include_once(caminho_funcoes. "contrato/dominioTpGarantiaContrato.php");
	        $comboGarantia = new select(dominioTpGarantiaContrato::getColecao());
	        $jsGarantia = "formataFormTpGarantia('".voContratoInfo::$nmAtrInTemGarantia."', '".voContratoInfo::$nmAtrTpGarantia."');"
	        ?>
			<TR>
			<?php 
			//$mouseover = " onMouseOver=\"toolTip('sem valor referencial mensal?')\" onMouseOut='toolTip()' ";
			?>
	            <TH class="campoformulario" nowrap width="1%">Características:</TH>
	            <TD class="campoformulario" colspan=3>
	            <abbr title="Sem valor referencial mensal?">É por escopo?:</abbr>
	            <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInEscopo,voContratoInfo::$nmAtrInEscopo, $vo->inEscopo, true, "camponaoobrigatorio", false, " onChange='formataFormEscopo();' required ");?>
	            | É credenciamento?:
	            <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInCredenciamento,voContratoInfo::$nmAtrInCredenciamento, $vo->inCredenciamento, true, "camponaoobrigatorio", false, " required ");?>
	            | Será prorrogado?:
	            <?php 
	            $seraProrrogTemp = $vo->inSeraProrrogado;
	            if($seraProrrogTemp == null){
	            	$seraProrrogTemp = constantes::$CD_SIM;
	            }
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInSeraProrrogado,voContratoInfo::$nmAtrInSeraProrrogado, $seraProrrogTemp, true, "camponaoobrigatorio", false, " required ");?>
	        	</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Garantia:</TH>
	            <TD class="campoformulario" colspan="3">
	            Tem?: <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInTemGarantia,voContratoInfo::$nmAtrInTemGarantia, $vo->inTemGarantia, true, "camponaoobrigatorio", false,
	            		" onChange=\"". $jsGarantia. "\" required ");?>
	            Tipo: <?php echo $comboGarantia->getHtmlCombo(voContratoInfo::$nmAtrTpGarantia,voContratoInfo::$nmAtrTpGarantia, $vo->tpGarantia, true, "camponaoobrigatorio", true, " disabled ");?>
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Gestor:</TH>
	            <TD class="campoformulario" colspan="3">
                    Código:<INPUT type="text" id="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" name="<?=voContratoInfo::$nmAtrCdPessoaGestor?>" value="<?=complementarCharAEsquerda($colecao[voContratoInfo::$nmAtrCdPessoaGestor], "0", TAMANHO_CODIGOS)?>"  class="camporeadonly" size="5" readonly>
                    Nome: <INPUT type="text" id="<?=voContratoInfo::$IDREQNmPessoaGestor?>" name="<?=voContratoInfo::$IDREQNmPessoaGestor?>" value="<?=$colecao[voContratoInfo::$IDREQNmPessoaGestor]?>"   class="camporeadonly" size="30" readonly>
                    <?php 
                    echo getLinkPesquisa("../pessoa");
                    
                    $nmCamposDocApagar = array(
                    		voContratoInfo::$nmAtrCdPessoaGestor,
                    		voContratoInfo::$IDREQNmPessoaGestor,
                    );
                    echo getBorracha($nmCamposDocApagar, "");
                    
                    ?>
	            </TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoInfo::$nmAtrObs?>" name="<?=voContratoInfo::$nmAtrObs?>" class="camponaoobrigatorio"><?=$vo->obs?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voContratoInfo::$nmAtrCdAutorizacaoContrato?>",
	            		"<?=voContratoInfo::$nmAtrInEstudoTecnicoSAD?>",
	            		"<?=voContratoInfo::$nmAtrInEscopo?>",
	            		"<?=voContratoInfo::$nmAtrInSeraProrrogado?>",
		            	"<?=voContratoInfo::$nmAtrCdClassificacao?>",
		            	"<?=voContratoInfo::$nmAtrInMaoDeObra?>",
		            	"<?=voContratoInfo::$nmAtrDtProposta?>",
		            	"<?=voContratoInfo::$nmAtrDtBaseReajuste?>",
		            	"<?=voContratoInfo::$nmAtrInPrazoProrrogacao?>",
	            		"<?=voContratoInfo::$nmAtrTpGarantia?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> <?=voMensageria::$DS_RESPONSABILIDADE_CAMPO_OBR?>
				</TD>
	        </TR>	        
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

</BODY>
</HTML>
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);
}
?>
