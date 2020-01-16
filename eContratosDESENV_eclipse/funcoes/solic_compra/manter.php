<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voSolicCompra();
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
	
$titulo = voSolicCompra::getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
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
	        if(!$isInclusao){
	        ?>	        	        
			<TR>
		         <TH class="campoformulario" nowrap width="1%">Solic.Compra.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php 
		         $codigo = formatarCodigoAnoComplementoArgs ( $vo->cd, $vo->ano, TAMANHO_CODIGOS, $vo->ug.".");
		         echo getDetalhamentoHTML("", "", $codigo);?>
			         <INPUT type="hidden" id="<?=voSolicCompra::$nmAtrAno?>" name="<?=voSolicCompra::$nmAtrAno?>" value="<?=$vo->ano?>">
					 <INPUT type="hidden" id="<?=voSolicCompra::$nmAtrCd?>" name="<?=voSolicCompra::$nmAtrCd?>" value="<?=$vo->cd?>">		         
					 <INPUT type="hidden" id="<?=voSolicCompra::$nmAtrUG?>" name="<?=voSolicCompra::$nmAtrUG?>" value="<?=$vo->ug?>">
				</TD>
	        </TR>
			<?php			
			$comboSituacao = new select(dominioSituacaoSolicCompra::getColecao());
	        ?>
			<TR>
	            <TH class="campoformulario" width="1%" nowrap>Situação:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboSituacao->getHtmlCombo(voSolicCompra::$nmAtrSituacao,voSolicCompra::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?></TD>
				</TD>
	        </TR>
			<?php			
	        }else{
	            $selectExercicio = new selectExercicio();
	            //$vo->dtAbertura = dtHojeSQL;	            
	            echo getInputHidden(voSolicCompra::$nmAtrSituacao, voSolicCompra::$nmAtrSituacao, dominioSituacaoSolicCompra::$CD_SITUACAO_ABERTA);
	        ?>
			<TR>
		        <TH class="campoformulario" nowrap width="1%">Solic.Compra:</TH>
		        <TD class="campoformulario" colspan=3>		        
		        <?php 
		        echo "Ano: " . $selectExercicio->getHtmlCombo(voSolicCompra::$nmAtrAno,voSolicCompra::$nmAtrAno, $vo->ano, true, "campoobrigatorio", false, " required ");
		        $comboUG = new select(dominioUGSolicCompra::getColecaoConsulta());
		        echo "UG: ".$comboUG->getHtmlSelect(voSolicCompra::$nmAtrUG , voSolicCompra::$nmAtrUG, $filtro->ug, true, "campoobrigatorio", "");
		        ?>			            
			    Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voSolicCompra::$nmAtrCd?>" name="<?=voSolicCompra::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($vo->cd, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" required>			    
						    
				<SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCdNaoObrigatorio = ["<?=voSolicCompra::$nmAtrCd?>"];
	            </SCRIPT>
	            <INPUT type="checkbox" id="checkCdNaoObrigatorio" name="checkCdNaoObrigatorio" value="" onClick="validaFormRequiredCheckBox(this, colecaoIDCdNaoObrigatorio, true);"> *Incluir código automaticamente.			                                           
	        </TR>	        			            
	        <?php 
	       }	       
	       $comboTipo = new select(dominioTipoSolicCompra::getColecao());
	       ?>
			<TR>
	            <TH class="campoformulario" nowrap>Tipo:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboTipo->getHtmlCombo(voSolicCompra::$nmAtrTipo,voSolicCompra::$nmAtrTipo, $vo->tipo, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Objeto:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voSolicCompra::$nmAtrObjeto?>" name="<?=voSolicCompra::$nmAtrObjeto?>" class="camponaoobrigatorio" required><?php echo($vo->objeto);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Valor:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voSolicCompra::$nmAtrValor?>" name="<?=voSolicCompra::$nmAtrValor?>"  value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
	        </TR>						        
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voSolicCompra::$nmAtrObservacao?>" name="<?=voSolicCompra::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = [
	            		"<?=voSolicCompra::$nmAtrTipo?>",	            		
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
