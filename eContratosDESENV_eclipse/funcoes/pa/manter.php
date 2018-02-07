<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
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
		return confirm("VERIFIQUE SE A SITUA��O '<?=dominioSituacaoPA::$DS_SITUACAO_PA_AGUARDANDO_ACAO?>' EST� CORRETA!");		
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
		getDadosContratadaPorDemanda(str, '<?=vopessoa::$nmAtrNome?>');
	}
}

function validarPublicacao(){
	fundamento = document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value;	
	if(fundamento.indexOf("<?=constantes::$CD_MODELO?>") != -1){
		//neste caso o campo fundamento est� com o valor MODELO
		exibirMensagem("O MODELO do campo 'publica��o' deve ser alterado.");
		return false;
	} 

	return true;
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
	        $procAdm = formatarCodigoAno($colecao[voPA::$nmAtrCdPA],
	        		$colecao[voPA::$nmAtrAnoPA]);
	         
	        if(!$isInclusao){
	        ?>	        	        
			<TR>
		         <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo(getDetalhamentoHTMLCodigoAno($vo->anoPA, $vo->cdPA, TAMANHO_CODIGOS_SAFI));?>
			         <INPUT type="hidden" id="<?=voPA::$nmAtrAnoPA?>" name="<?=voPA::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
					 <INPUT type="hidden" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">		         
				</TD>
	        </TR>
			<?php 
			getDemandaDetalhamento($voDemanda);
			getContratoDet($voContrato);
			
			$domSiPA = new dominioSituacaoPA();
			$comboSituacao = new select($domSiPA::getColecao());				
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap>Situa��o:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboSituacao->getHtmlCombo(voPA::$nmAtrSituacao,voPA::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?></TD>
				</TD>
	        </TR>
			
			<?php			
	        }else{
	            $selectExercicio = new selectExercicio();
	            $vo->dtAbertura = dtHojeSQL;
	            
	            $domSiPA = new dominioSituacaoPA();
	            $comboSituacao = new select($domSiPA::getColecao());
	            echoo(getInputHidden(voPA::$nmAtrSituacao, voPA::$nmAtrSituacao, dominioSituacaoPA::$CD_SITUACAO_PA_INSTAURADO));
	        ?>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		        <TD class="campoformulario" colspan=3>		        
		            	<?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoPA,voPA::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required ");?>			            
			            N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($vo->cdPA, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" required>
				<SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCdNaoObrigatorio = ["<?=voPA::$nmAtrCdPA?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkCdNaoObrigatorio" name="checkCdNaoObrigatorio" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCdNaoObrigatorio, true);"> *Incluir c�digo automaticamente.	            			
			                                           
	        </TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoDemanda,voPA::$nmAtrAnoDemanda, $vo->anoDemanda, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();'");?>
			  N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdDemanda?>" name="<?=voPA::$nmAtrCdDemanda?>"  value="<?php echo(complementarCharAEsquerda($vo->cdDemanda, "0", 5));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required onBlur='carregaDadosContratada();'>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>			            
	        <?php 
	       }	                    
	       ?>	           				
            <TR>
				<TH class="campoformulario" nowrap>Servidor Respons�vel:</TH>
                <TD class="campoformulario" colspan="3">
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");                    
                    echo getComboPessoaRespPA(voPA::$nmAtrCdResponsavel, voPA::$nmAtrCdResponsavel, $vo->cdResponsavel, "camponaoobrigatorio", "required");                                        
                    ?>
            </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observa��o:</TH>
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
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Notifica��o Nota Imputa��o:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtNotificacao?>" 
	            	       name="<?=voPA::$nmAtrDtNotificacao?>" 
	            			value="<?php echo(getData($vo->dtNotificacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
	            <TH class="campoformulario">Dt.Notifica��o.�lt.Manifesta��o:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            	       name="<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>" 
	            			value="<?php echo(getData($vo->dtUlNotificacaoParaManifestacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required> *para fins de contagem de prazo
				</TD>				
        	</TR>
        	<?php        	
        		$modeloPublicacao = voPA::getTextoModeloPublicacaoPenalidade($voContrato);        	 
        	?>
			<TR>
	            <TH class="campoformulario" nowrap>Publica��o:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voPA::$nmAtrPublicacao?>" name="<?=voPA::$nmAtrPublicacao?>" class="camponaoobrigatorio" ><?php echo($vo->publicacao);?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = ["<?=voPA::$nmAtrDtNotificacao?>",
	            		"<?=voPA::$nmAtrDtUltNotificacaoParaManifestacao?>"];
	            </SCRIPT>
	            <br><INPUT type="checkbox" onClick="if(!this.checked){document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value='';}else{document.frm_principal.<?=voPA::$nmAtrPublicacao?>.value='<?=$modeloPublicacao?>'};"> *incluir Modelo Publica��o.
	            <br><INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Assumo a responsabilidade de n�o incluir os valores obrigat�rios.	            				            
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
