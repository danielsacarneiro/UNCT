<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voDemandaTramitacao.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDemanda();
$votram = new voDemandaTramitacao();
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
	if(!isCampoTextoValido(document.frm_principal.<?=voDemandaTramitacao::$nmAtrTexto?>, true))	
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
	        
	        $comboTipo = new select(dominioTipoDemanda::getColecao());
	        $comboSetor = new select(dominioSetor::getColecao());
	        $comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        $selectExercicio = new selectExercicio();	         
	        
	        $votram->dhInclusao = dtHoje;
	        
	        $complementoHTML = "";
	        
	        if(!$isInclusao){
	        	//ALTERACAO
	        	$complementoHTML = " required ";
	        ?>	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo("","", $vo->ano, true, "camporeadonly", false, " disabled ");?>
	            <?php echo "Tipo: " . $comboTipo->getHtmlCombo("","", $vo->tipo, true, "camporeadonly", false, " disabled ");?>
				N�mero: <INPUT type="text" value="<?=complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS);?>"  class="camporeadonly" size="6" readonly>	            
	            
	            <INPUT type="hidden" id="<?=voDemanda::$nmAtrAno?>" name="<?=voDemanda::$nmAtrAno?>" value="<?=$vo->ano?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>" value="<?=$vo->cd?>">	            			  
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrTipo?>" name="<?=voDemanda::$nmAtrTipo?>" value="<?=$vo->tipo?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCdSetor?>" name="<?=voDemanda::$nmAtrCdSetor?>" value="<?=$vo->cdSetor?>">
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Respons�vel:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo $comboSetor->getHtmlCombo("","", $vo->cdSetor, true, "camporeadonly", false, " disabled ");?>
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">T�tulo:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" value="<?=$vo->texto?>"  class="camporeadonly" size="80" readonly>	            	                        	                        
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situa��o:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao, $vo->situacao, true, "camporeadonly", false, " disabled ");?>
				</TD>
	        </TR>	        
				<?php 
				$isDetalhamento = true;
				include_once 'gridTramitacaoAjax.php';
				?>
	        <?php
	        }else{
	        	//INCLUSAO
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, anoDefault, true, "campoobrigatorio", false, " required ");?>
	            <?php echo "Tipo: " . $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo,voDemanda::$nmAtrTipo, $vo->tipo, true, "campoobrigatorio", false, " required ");?>			  
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Origem:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $votram->cdSetorDestino, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>	 
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">T�tulo:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value=""  class="campoobrigatorio" size="80" required>	            	                        	                        
	        </TR>	               
	        <?php 
	       }	       
	       ?>	       
			<TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
				<DIV class="campoformulario" id="div_tramitacao">&nbsp;&nbsp;Novo Encaminhamento
				</DIV>
				</TH>
			</TR>
			
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Destino:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $vo->cdSetorDestino, true, "camponaoobrigatorio", false, $complementoHTML);?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Texto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voDemandaTramitacao::$nmAtrTexto?>" name="<?=voDemandaTramitacao::$nmAtrTexto?>" class="camponaoobrigatorio" <?=$complementoHTML?>></textarea>
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">PRT:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" value=""  class="camponaoobrigatorio" size="30">	            	                        	                        
	        </TR>			            	        
			<TR>
	            <TH class="campoformulario" nowrap>Data:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voDemandaTramitacao::$nmAtrDhOperacao?>" 
	            	       name="<?=voDemandaTramitacao::$nmAtrDhOperacao?>" 
	            			value="<?php echo($votram->dhInclusao);?>"
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
