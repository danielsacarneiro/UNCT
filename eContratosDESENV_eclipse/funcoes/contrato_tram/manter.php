<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voContratoTramitacao.php");
include_once(caminho_vos."vopessoa.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voContratoTramitacao();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "campoobrigatorio";
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
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);
		
    $nmFuncao = "ALTERAR ";
}

if($vo->dtReferencia == null|| $vo->dtReferencia == "")
	$vo->dtReferencia = dtHoje;
	
$titulo = voContratoTramitacao::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if(!isCampoTextoValido(document.frm_principal.<?=voContratoTramitacao::$nmAtrObservacao?>, true))	
		return false;		
	
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	/*if(!isFormularioValido())
		return false;
	alert(document.getElementsByName("<?=voDocumento::getNmTabela()?>").item(0).value);
	alert(document.getElementsByName("<?=voDocumento::$nmAtrSq?>").item(0).value);*/
		
	return confirm("Confirmar Alteracoes?");    
}

function carregaDadosContratada(){    
	str = "";
		
	cdContrato = document.frm_principal.<?=voContratoTramitacao::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=voContratoTramitacao::$nmAtrAnoContrato?>.value;
	tpContrato = document.frm_principal.<?=voContratoTramitacao::$nmAtrTipoContrato?>.value;

	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = cdContrato + '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + tpContrato;
		//vai no ajax
		getDadosContratadaPorContrato(str, '<?=vopessoa::$nmAtrNome?>');
	}
}

function transferirDadosDocumento(sq, cdSetor, ano, tpDoc){
	chave = sq
		+ CD_CAMPO_SEPARADOR +  cdSetor
		+ CD_CAMPO_SEPARADOR +  ano
		+ CD_CAMPO_SEPARADOR +  tpDoc;

	document.getElementsByName("<?=voDocumento::getNmTabela()?>").item(0).value = chave;
	document.getElementsByName("<?=voDocumento::$nmAtrSq?>").item(0).value = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc);

	//alert(chave);
}

</SCRIPT>

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
	        if(!$isInclusao){
	        	$contrato = formatarCodigoContrato($colecao[voContratoTramitacao::$nmAtrCdContrato],
	        			$colecao[voContratoTramitacao::$nmAtrAnoContrato],
	        			$dominioTipoContrato->getDescricao($colecao[voContratoTramitacao::$nmAtrTipoContrato]));	        	
	        ?>	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
				<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrCdContrato?>" name="<?=voContratoTramitacao::$nmAtrCdContrato?>" value="<?=$vo->cdContrato?>">
				<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrAnoContrato?>" name="<?=voContratoTramitacao::$nmAtrAnoContrato?>" value="<?=$vo->anoContrato?>">
				<INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrTipoContrato?>" name="<?=voContratoTramitacao::$nmAtrTipoContrato?>" value="<?=$vo->tipoContrato?>">
	            <INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonly" size="17" readonly>	            	                        	                        
	        </TR>			            
			<TR>
	            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
	            <TD class="campoformulario" width="1%"><INPUT type="text" id="nmContratada" name="nmContratada"  value="<?php echo($colecao[vopessoa::$nmAtrNome]);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
	            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="docContratada" name="docContratada"  value="<?php echo($colecao[vopessoa::$nmAtrDoc]);?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camporeadonly" size="20" maxlength="20" <?=$readonly?>></TD>
	        </TR>
	        <?php
	        }else{
	        	require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioTipoContrato.php");	        	
	        	$combo = new select(dominioTipoContrato::getColecao());
	            $selectExercicio = new selectExercicio();
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voContratoTramitacao::$nmAtrAnoContrato,voContratoTramitacao::$nmAtrAnoContrato, $vo->anoContrato, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();'");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voContratoTramitacao::$nmAtrCdContrato?>" name="<?=voContratoTramitacao::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"  class="<?=$classChaves?>" size="4" maxlength="3" <?=$readonlyChaves?> required onBlur='carregaDadosContratada();'>
			  <?php echo $combo->getHtmlCombo(voContratoTramitacao::$nmAtrTipoContrato,voContratoTramitacao::$nmAtrTipoContrato, "", true, "camponaoobrigatorio", false, " required onChange='carregaDadosContratada();' ");	
			  ?>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>			            
	        <?php 
	       }	                    
	       ?>	           				
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoTramitacao::$nmAtrObservacao?>" name="<?=voContratoTramitacao::$nmAtrObservacao?>" class="campoobrigatorio" required><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
	        <TR>
		        <TH class="campoformulario" width="1%" nowrap>Documento:</TH>
		        <TD class="campoformulario" nowrap>
		        	<INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>" class="camporeadonly" size="15" readonly>
		        	<INPUT type="hidden" id="<?=voDocumento::getNmTabela()?>" name="<?=voDocumento::getNmTabela()?>" value="">
		        	<?php 
		        	echo getLinkPesquisa("../documento");
		        	
		        	$nmCampo = array(voDocumento::getNmTabela(), voDocumento::$nmAtrSq);
		        	echo getBorracha($nmCampo);
		        	?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Referência:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voContratoTramitacao::$nmAtrDtReferencia?>" 
	            	       name="<?=voContratoTramitacao::$nmAtrDtReferencia?>" 
	            			value="<?php echo($vo->dtReferencia);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
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
