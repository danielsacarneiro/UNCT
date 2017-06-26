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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
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

function formataFormClassificacao(pCampoChamada) {
	campoClassificacao = document.frm_principal.<?=voContratoInfo::$nmAtrCdClassificacao?>;
	campoMaodeObra = document.frm_principal.<?=voContratoInfo::$nmAtrInMaoDeObra?>;

	classificacao = campoClassificacao.value;

	if(classificacao == "<?=dominioClassificacaoContrato::$CD_MAO_OBRA?>"){
		campoMaodeObra.value = "<?=constantes::$CD_SIM?>";
	}
	else if(classificacao == "<?=dominioClassificacaoContrato::$CD_FORNECIMENTO_AQUISICAO?>"){
		campoMaodeObra.value = "<?=constantes::$CD_NAO?>";
	}
	else if(pCampoChamada == null || pCampoChamada.name != "<?=voContratoInfo::$nmAtrInMaoDeObra?>"){		
		campoMaodeObra.value = "";
	}
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="formataFormTpGarantia('<?=voContratoInfo::$nmAtrInTemGarantia?>', '<?=voContratoInfo::$nmAtrTpGarantia?>');">
	  
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
	            <TD class="campoformulario" colspan=3><?php getCampoDadosContratoSimples();//getContratoEntradaDeDados($tipoContrato, $anoContrato, $cdContrato, $arrayCssClass, $arrayComplementoHTML, $nmCampoDiv);?></TD>
	        </TR>	        
	        <?php 
	       }	       
	       ?>
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
	            echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: ";
	            echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, true, "camponaoobrigatorio", false,
	            		" onChange='formataFormClassificacao(this);' required ");
	            ?>
	        </TR>	       
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				$combo = new select(dominioAutorizacao::getColecao());				
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario" width="1%"><?php echo $combo->getHtmlCombo(voContratoInfo::$nmAtrCdAutorizacaoContrato,voContratoInfo::$nmAtrCdAutorizacaoContrato, $cdAutorizacao, true, "camponaoobrigatorio", true, " required ");?>
	            <TH class="campoformulario" nowrap width="1%">Data.Proposta:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            	       name="<?=voContratoInfo::$nmAtrDtProposta?>" 
	            			value="<?php echo(getData($vo->dtProposta));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">
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
	            Tem?: <?php echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInTemGarantia,voContratoInfo::$nmAtrInTemGarantia, $vo->inTemGarantia, true, "camponaoobrigatorio", false,
	            		" onChange=\"". $jsGarantia. "\" required ");?>
	            Tipo: <?php echo $comboGarantia->getHtmlCombo(voContratoInfo::$nmAtrTpGarantia,voContratoInfo::$nmAtrTpGarantia, $vo->tpGarantia, true, "camponaoobrigatorio", true, " disabled ");?>
	            </TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoInfo::$nmAtrObs?>" name="<?=voContratoInfo::$nmAtrObs?>" class="camponaoobrigatorio"><?=$vo->obs?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voContratoInfo::$nmAtrCdAutorizacaoContrato?>",
		            	"<?=voContratoInfo::$nmAtrCdClassificacao?>",
		            	"<?=voContratoInfo::$nmAtrInMaoDeObra?>",
	            		"<?=voContratoInfo::$nmAtrInTemGarantia?>",
	            		"<?=voContratoInfo::$nmAtrTpGarantia?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Assumo a responsabilidade de não incluir os valores obrigatórios.
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
