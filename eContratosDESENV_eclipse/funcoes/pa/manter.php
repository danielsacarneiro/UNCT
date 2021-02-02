<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
include_once(caminho_funcoes . "pa/biblioteca_htmlPA.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voPA();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "camponaoobrigatorioalinhadodireita";
$readonlyChaves = "";

session_start();

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
	
	$voContrato = new vocontrato();
	$voContrato->getDadosBanco($colecao);
	
	$voDemanda = new voDemanda();
	$voDemanda->getDadosBanco($colecao);
		
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}
	
$titulo = voPA::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!validarPublicacao())
		return false;

	campoDataUltNotificacao = document.frm_principal.<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>;
	campoSituacao = document.frm_principal.<?=voPA::$nmAtrSituacao?>;
	if (campoDataUltNotificacao.value != "" && campoSituacao.value == <?=dominioSituacaoPA::$CD_SITUACAO_PA_AGUARDANDO_ACAO?>){
		campoSituacao.focus();		
		return confirm("VERIFIQUE SE A SITUAÇÃO '<?=dominioSituacaoPA::$DS_SITUACAO_PA_AGUARDANDO_ACAO?>' ESTÁ CORRETA!");		
	}
			
	campoNumDocImputada = document.frm_principal.<?=voPA::$nmAtrNumDocImputada?>;
	//if (campoNumDocImputada != null && !isCampoCNPFouCNPJValido(campoNumDocImputada, true)){
	if (campoNumDocImputada != null && !isCampoTextoValido(campoNumDocImputada, true)){				
		return false;		
	}

	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido()){
		return false;
	}
	
	return confirm("Confirmar Alteracoes?");
}

function carregaDadosContratada(){    
	str = "";

	cdDemanda = document.frm_principal.<?=voPA::$nmAtrCdDemanda?>.value;
	anoDemanda = document.frm_principal.<?=voPA::$nmAtrAnoDemanda?>.value;
	
	if(cdDemanda != "" && anoDemanda != ""){
		str = anoDemanda + '<?=CAMPO_SEPARADOR?>' + cdDemanda;
		//vai no ajax
		getDadosContratadaPorDemanda(str, '<?=vopessoa::$nmAtrNome?>', '<?=constantes::$CD_FUNCAO_INCLUIR?>');
	}
}

function validarPublicacao(){
	fundamento = document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value;	
	if(fundamento.indexOf("<?=constantes::$CD_MODELO?>") != -1){
		//neste caso o campo fundamento está com o valor MODELO
		exibirMensagem("O MODELO do campo 'publicação' deve ser alterado.");
		return false;
	} 

	return true;
}

function getDataPrazo(){
	pNmCampoDiv = "<?=voPA::$ID_REQ_DIV_PRAZO?>";
	pIDCampoData = "<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>";
	pIDCampoDataFim = "<?=voPA::$nmAtrDtUltNotificacaoPrazoEncerrado?>";
	pIDCampoPrazo = "<?=voPA::$nmAtrNumDiasPrazoUltNotificacao?>";
	pCampoInDiasUteis = document.frm_principal.<?=voPA::$ID_REQ_InDiasUteisPrazoUltNotificacao?>;

	inDiasUteis = "N";
	if(pCampoInDiasUteis.checked){
		inDiasUteis = "S";
	}

	//alert(inDiasUteis);
	dtNotificacao = document.getElementById(pIDCampoData).value;
	prazo = document.getElementById(pIDCampoPrazo).value;
	
	if(prazo != "" && dtNotificacao != "" ){
		//alert("aqui");
		//vai no ajax
		chave = pIDCampoDataFim + '<?=CAMPO_SEPARADOR?>' + dtNotificacao + '<?=CAMPO_SEPARADOR?>' + prazo + '<?=CAMPO_SEPARADOR?>' + inDiasUteis;			
		getDataFimPrazo(chave, pNmCampoDiv) ;		
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}	
}
</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
</HEAD>
<BODY class="paginadados" onload="">
	  
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
	        $domSiPA = new dominioSituacaoPA();
	        $comboSituacao = new select($domSiPA::getColecao());
	        
	        if(!$isInclusao){        
	        getDemandaDetalhamento($voDemanda);
			getContratoDet($voContrato);
			
			if(!isContratoValido($voContrato)){
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap>CNPJ/CPF imputada:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo getHTMLInputNumDocumentoPessoa(voPA::$nmAtrNumDocImputada, voPA::$nmAtrNumDocImputada, documentoPessoa::getNumeroDocFormatado($vo->numDocImputada), "campoobrigatorio");
	            ?>
	            </TD>
	        </TR>
	        <?php 
			}
			?>
			<TR>
	            <TH class="campoformulario" nowrap>Situação:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo $comboSituacao->getHtmlCombo(voPA::$nmAtrSituacao,voPA::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?>
	            </TD>
	        </TR>			
			<?php			
	        }else{
	            $selectExercicio = new selectExercicio();
	            $vo->dtAbertura = dtHojeSQL;
	            
	            echoo(getInputHidden(voPA::$nmAtrSituacao, voPA::$nmAtrSituacao, dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO));
	        ?>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		        <TD class="campoformulario" colspan=3>		        
		            	<?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoPA,voPA::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required ");?>			            
			            Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($vo->cdPA, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" required>
				<SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCdNaoObrigatorio = ["<?=voPA::$nmAtrCdPA?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkCdNaoObrigatorio" name="checkCdNaoObrigatorio" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCdNaoObrigatorio, true);"> *Incluir código automaticamente.	            			
			                                           
	        </TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoDemanda,voPA::$nmAtrAnoDemanda, $vo->anoDemanda, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();'");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdDemanda?>" name="<?=voPA::$nmAtrCdDemanda?>"  value="<?php echo(complementarCharAEsquerda($vo->cdDemanda, "0", 5));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required onBlur='carregaDadosContratada();'>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>			            
	        <?php 
	       }	                    
	       ?>	           				
            <TR>
				<TH class="campoformulario" nowrap>Servidor Responsável:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");                    
                    echo getComboPessoaRespPA(voPA::$nmAtrCdResponsavel, voPA::$nmAtrCdResponsavel, $vo->cdResponsavel, "camponaoobrigatorio", "required");                                        
                    ?>
            </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voPA::$nmAtrObservacao?>" name="<?=voPA::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data Abertura:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtAbertura?>" 
	            	       name="<?=voPA::$nmAtrDtAbertura?>" 
	            			value="<?php echo(getData($vo->dtAbertura));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>
			<!-- <TR>
	            <TH class="campoformulario" nowrap>Dt.Notificação Nota Imputação:</TH>
	            <TD class="campoformulario" colspan=3>
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtNotificacao?>" 
	            	       name="<?=voPA::$nmAtrDtNotificacao?>" 
	            			value="<?php echo(getData($vo->dtNotificacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>-->
			<TR>
	            <TH class="campoformulario">Dt.Notificação.Últ.Manifestação:</TH>
	            <TD class="campoformulario" colspan=3>
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            	       name="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            			value="<?php echo(getData($vo->dtUlNotificacaoParaManifestacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);"
		            		onBlur="getDataPrazo()" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
	            				            			
				<?php echo " Prazo:".getInputText(voPA::$nmAtrNumDiasPrazoUltNotificacao, voPA::$nmAtrNumDiasPrazoUltNotificacao, $vo->numDiasPrazoUltNotificacao, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, 3,3, " onKeyUp='validarCampoNumericoPositivo(this)' onBlur='getDataPrazo()' ")
				
				/*include_once(caminho_util. "dominioSimNao.php");
				$comboSimNao = new select(dominioSimNao::getColecao());
				echo "&nbsp;&nbsp;Mão de obra incluída (planilha de custos)?: ";
				echo $comboSimNao->getHtmlCombo(voContratoInfo::$nmAtrInMaoDeObra,voContratoInfo::$nmAtrInMaoDeObra, $vo->inMaoDeObra, true, "camponaoobrigatorio", false,
						" onChange='formataFormClassificacao(this);' required ");*/
				
				?> 
				
				<INPUT type="checkbox" id="<?=voPA::$ID_REQ_InDiasUteisPrazoUltNotificacao?>" name="<?=voPA::$ID_REQ_InDiasUteisPrazoUltNotificacao?>"
				onClick ="getDataPrazo()" checked>				
				(dias úteis?) *para fins de contagem de prazo
	            <?php	            
	            $nmCampos = array(vocontrato::$nmAtrAnoContrato,
	            		voPA::$nmAtrDtUltNotificacaoParaManifestacao,
	            		voPA::$nmAtrNumDiasPrazoUltNotificacao,
	            		voPA::$nmAtrDtUltNotificacaoPrazoEncerrado,
	            );
	            echo getBorracha($nmCampos, "getDataPrazo();");
	            ?>

			  <div id="<?=voPA::$ID_REQ_DIV_PRAZO?>">
			  <?=getCampoDataPrazoFinal(voPA::$nmAtrDtUltNotificacaoPrazoEncerrado, $vo->dtUlNotificacaoPrazoEncerrado);?>
	          </div>
				</TD>				
        	</TR>
        	<?php        	
        		$modeloPublicacao = voPA::getTextoModeloPublicacaoPenalidade($voContrato);        	 
        	?>
			<TR>
	            <TH class="campoformulario" nowrap>Publicação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voPA::$nmAtrPublicacao?>" name="<?=voPA::$nmAtrPublicacao?>" class="camponaoobrigatorio" ><?php echo($vo->publicacao);?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voPA::$nmAtrDtNotificacao?>",
	            		"<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>",
	            		"<?=voPA::$nmAtrNumDiasPrazoUltNotificacao?>"];
	            </SCRIPT>
	            <br><INPUT type="checkbox" onClick="if(!this.checked){document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value='';}else{document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value='<?=$modeloPublicacao?>'};"> *incluir Modelo Publicação.
	            <br><INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Assumo a responsabilidade de não incluir os campos obrigatórios.	            				            
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
