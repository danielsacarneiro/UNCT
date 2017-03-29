<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voDemanda.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDemanda();
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
	
$titulo = voDemanda::getTituloJSP();
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
	if(!isCampoTextoValido(document.frm_principal.<?=voDemanda::$nmAtrTexto?>, true))	
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
	*/
		
	return confirm("Confirmar Alteracoes?");    
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
	        		        	
	        ?>	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrAno?>" name="<?=voDemanda::$nmAtrAno?>" value="<?=$vo->cdContrato?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>" value="<?=$vo->cdContrato?>">
	            <INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonly" size="17" readonly>	            	                        	                        
	        </TR>			            
	        <?php
	        }else{
	        	$vo->dtReferencia = dtHoje;
	        	$comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	            $selectExercicio = new selectExercicio();
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, anoDefault, true, "campoobrigatorio", false, " required ");?>			  
	        </TR>			            
	        <?php 
	       }	                    
	       
	       $comboSetor = new select(dominioSetor::getColecao());
	       ?>	           				
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Origem:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $vo->cdSetorOrigem, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Destino:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php //echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetorDestino,voDemanda::$nmAtrCdSetorDestino, $vo->cdSetorDestino, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Texto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" class="campoobrigatorio" required><?php echo($vo->texto);?></textarea>
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PRT:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrProtocolo?>" name="<?=voDemanda::$nmAtrProtocolo?>" value="<?php echo($vo->prt);?>"  class="camponaoobrigatorio" size="30">	            	                        	                        
	        </TR>			            	        
			<TR>
	            <TH class="campoformulario" nowrap>Dt.Abertura:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voDemanda::$nmAtrDtAbertura?>" 
	            	       name="<?=voDemanda::$nmAtrDtAbertura?>" 
	            			value="<?php echo($vo->dtReferencia);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
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
