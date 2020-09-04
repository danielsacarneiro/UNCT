<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voProcLicitatorio();
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
	
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}
	
$titulo = voProcLicitatorio::getTituloJSP();
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">
<?php
		//guarda os setores do econti
		$varColecaoCdPregoeiroPorCPL = "_globalCdPregoeiroPorCPL";
		echo getColecaoComoVariavelJS(dominioComissaoProcLicitatorio::getColecaoCdPregoeiroPorCPL(), $varColecaoCdPregoeiroPorCPL);
?>

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	/*if (!validarPublicacao())
		return false;*/		
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

function setCdPregoeiro(){
	var colecao=<?=$varColecaoCdPregoeiroPorCPL?>;
	var cpl = document.frm_principal.<?=voProcLicitatorio::$nmAtrCdCPL?>.value;
	document.frm_principal.<?=voProcLicitatorio::$nmAtrCdPregoeiro?>.value = colecao[cpl];	
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
	        $procAdm = formatarCodigoAno($colecao[voProcLicitatorio::$nmAtrCd],
	        		$colecao[voProcLicitatorio::$nmAtrAno]);
	         
	        if(!$isInclusao){
	        ?>	        	        
			<TR>
		         <TH class="campoformulario" nowrap width="1%">P.L.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo(getDetalhamentoHTMLCodigoAno($vo->ano, $vo->cd, TAMANHO_CODIGOS_SAFI));?>
			         <INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrAno?>" name="<?=voProcLicitatorio::$nmAtrAno?>" value="<?=$vo->ano?>">
					 <INPUT type="hidden" id="<?=voProcLicitatorio::$nmAtrCd?>" name="<?=voProcLicitatorio::$nmAtrCd?>" value="<?=$vo->cd?>">		         
				</TD>
	        </TR>
			<?php			
			$comboSituacao = new select(dominioSituacaoPL::getColecao());
	        ?>
			<TR>
	            <TH class="campoformulario" width="1%" nowrap>Situação:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboSituacao->getHtmlCombo(voProcLicitatorio::$nmAtrSituacao,voProcLicitatorio::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?></TD>
				</TD>
	        </TR>
			<?php			
	        }else{
	            $selectExercicio = new selectExercicio();
	            //$vo->dtAbertura = dtHojeSQL;
	            
	            echo getInputHidden(voProcLicitatorio::$nmAtrSituacao, voProcLicitatorio::$nmAtrSituacao, dominioSituacaoPL::$CD_SITUACAO_PL_ABERTO);
	        ?>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.L.:</TH>
		        <TD class="campoformulario" colspan=3>		        
		        <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voProcLicitatorio::$nmAtrAno,voProcLicitatorio::$nmAtrAno, $vo->ano, true, "campoobrigatorio", false, " required ");?>			            
			    Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voProcLicitatorio::$nmAtrCd?>" name="<?=voProcLicitatorio::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($vo->cd, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" required>
				<SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCdNaoObrigatorio = ["<?=voProcLicitatorio::$nmAtrCd?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkCdNaoObrigatorio" name="checkCdNaoObrigatorio" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCdNaoObrigatorio, true);"> *Incluir código automaticamente.			                                           
	        </TR>	        			            
	        <?php 
	       }
	       
	       $comboModalidade = new select(dominioModalidadeProcLicitatorio::getColecao());
	       $comboTipo = new select(dominioTipoProcLicitatorio::getColecao());
	       $comboCPL = new select(dominioComissaoProcLicitatorio::getColecao());
	       ?>
			<TR>
				<TH class="campoformulario" nowrap width="1%">Modalidade:</TH>
				<TD class="campoformulario" colspan=3>
				<?php 
				echo $comboModalidade->getHtmlCombo(voProcLicitatorio::$nmAtrCdModalidade,voProcLicitatorio::$nmAtrCdModalidade, $vo->cdModalidade, true, "campoobrigatorio", false, " required ");
				echo " Número: "
				?>
				<INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voProcLicitatorio::$nmAtrNumModalidade?>" name="<?=voProcLicitatorio::$nmAtrNumModalidade?>"  value="<?php echo(complementarCharAEsquerda($vo->numModalidade, "0", 5));?>"  class="campoobrigatorio" size="6" maxlength="5" required>
				</TD>
			</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Tipo:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboTipo->getHtmlCombo(voProcLicitatorio::$nmAtrTipo,voProcLicitatorio::$nmAtrTipo, $vo->tipo, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>
			<TR>
				<TH class="campoformulario" width="1%" nowrap>CPL:</TH>
                <TD class="campoformulario" width="1%">
                <?php echo $comboCPL->getHtmlCombo(voProcLicitatorio::$nmAtrCdCPL,voProcLicitatorio::$nmAtrCdCPL, $vo->cdCPL, true, "campoobrigatorio", false, " onChange='setCdPregoeiro();' required ");?>
				</TD>
				<TH class="campoformulario" width="1%" nowrap>Pregoeiro:</TH>
                <TD class="campoformulario" >
                     <?php
                    include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");
                    echo getComboPessoaPregoeiro(voProcLicitatorio::$nmAtrCdPregoeiro, voProcLicitatorio::$nmAtrCdPregoeiro, $vo->cdPregoeiro, "camponaoobrigatorio", "required");                                        
                    ?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data Abertura:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voProcLicitatorio::$nmAtrDtAbertura?>" 
	            	       name="<?=voProcLicitatorio::$nmAtrDtAbertura?>" 
	            			value="<?php echo(getData($vo->dtAbertura));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Publicação:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voProcLicitatorio::$nmAtrDtPublicacao?>" 
	            	       name="<?=voProcLicitatorio::$nmAtrDtPublicacao?>" 
	            			value="<?php echo(getData($vo->dtPublicacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>
			<TR>
	            <TH class="campoformulario" nowrap>Objeto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voProcLicitatorio::$nmAtrObjeto?>" name="<?=voProcLicitatorio::$nmAtrObjeto?>" class="camponaoobrigatorio" required><?php echo($vo->objeto);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor.Total:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voProcLicitatorio::$nmAtrValor?>" name="<?=voProcLicitatorio::$nmAtrValor?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
	        </TR>						        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voProcLicitatorio::$nmAtrObservacao?>" name="<?=voProcLicitatorio::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = [
	            		//"<?=voProcLicitatorio::$nmAtrTipo?>",
	            		"<?=voProcLicitatorio::$nmAtrDtAbertura?>",
	            		//"<?=voProcLicitatorio::$nmAtrValor?>",
	            		"<?=voProcLicitatorio::$nmAtrDtPublicacao?>",	            		
	            		];
	            </SCRIPT>
	            <br><INPUT type="checkbox" id="checkResponsabilidade" name="checkResponsabilidade" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCamposRequired);"> *Assumo a responsabilidade de não incluir os valores obrigatórios.	            				            
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
