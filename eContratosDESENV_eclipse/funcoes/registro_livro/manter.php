<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voRegistroLivro();
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
	
$titulo = voRegistroLivro::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">
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
	        //require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        
	        if(!$isInclusao){
	        	getContratoDet($vo->voContrato, false, true);
	        }else{		        
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
				<TD class="campoformulario" colspan=3><?php
				//echo getCampoDadosContratoMultiplos();
				//echo getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, "", false);
				echo getContratoEntradaDeDadosVOSimples(null, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, true, false, true);
				?>
	        </TR>
	        <?php 
	       }
	       ?>
			<TR>
	            <TH class="campoformulario" nowrap>Livro:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo getInputText(voRegistroLivro::$nmAtrNumLivro, voRegistroLivro::$nmAtrNumLivro, $vo->numLivro, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, 3, 3, "required");
	            echo " Folha" . getInputText(voRegistroLivro::$nmAtrNumFolha, voRegistroLivro::$nmAtrNumFolha, $vo->numFolha, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, 3, 3, "required");
	            ?>
				</TD>
	        </TR>
            <TR>
	            <TH class="campoformulario" nowrap>Dt.Registro:</TH>
				<TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voRegistroLivro::$nmAtrDtRegistro?>" 
	            	       name="<?=voRegistroLivro::$nmAtrDtRegistro?>" 
	            			value="<?php echo(getData($vo->dtRegistro));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="campoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required> 				            
			</TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voRegistroLivro::$nmAtrObservacao?>" name="<?=voRegistroLivro::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = [
	            		"<?=voRegistroLivro::$nmAtrObservacao?>",	            		
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
