<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once (caminho_funcoes . "contrato/biblioteca_htmlContrato.php");
include_once (caminho_funcoes . "demanda/biblioteca_htmlDemanda.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voPenalidadePA();
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
	$colecao = $dbprocesso->consultarPorChaveTela($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	
	$voContrato = new vocontrato();
	$voContrato->getDadosBanco($colecao);
	
	$voDemanda = new voDemanda();
	$voDemanda->getDadosBanco($colecao);
	
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}
	
$titulo = voPenalidadePA::getTituloJSP();
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
	if (!validarFundamento())
		return false;		
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

function carregaDadosContratada(){    
	str = "";

	cdPA = document.frm_principal.<?=voPenalidadePA::$nmAtrCdPA?>.value;
	anoPA = document.frm_principal.<?=voPenalidadePA::$nmAtrAnoPA?>.value;
	
	if(cdPA != "" && anoPA != ""){
		str = anoPA + '<?=CAMPO_SEPARADOR?>' + cdPA;
		//vai no ajax
		getDadosContratadaPorPAAP(str, '<?=vopessoa::$nmAtrNome?>');
	}
}

function validarFundamento(){
	fundamento = document.frm_principal.<?=voPenalidadePA::$nmAtrFundamento?>.value;	
	if(fundamento.indexOf("MODELO") != -1){
		//neste caso o campo fundamento está com o valor MODELO
		exibirMensagem("O MODELO do campo 'fundamento' deve ser alterado.");
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
	        $procAdm = formatarCodigoAno($colecao[voPenalidadePA::$nmAtrCdPA],
	        		$colecao[voPenalidadePA::$nmAtrAnoPA]);
	         
	        if(!$isInclusao){
	        ?>	        	        
			<TR>
		         <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo(getDetalhamentoHTMLCodigoAno($vo->anoPA, $vo->cdPA, TAMANHO_CODIGOS_SAFI));?>
			         <INPUT type="hidden" id="<?=voPenalidadePA::$nmAtrAnoPA?>" name="<?=voPenalidadePA::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
					 <INPUT type="hidden" id="<?=voPenalidadePA::$nmAtrCdPA?>" name="<?=voPenalidadePA::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">
				</TD>
	        </TR>
			<TR>
		         <TH class="campoformulario" nowrap width="1%">Número:</TH>
		         <TD class="campoformulario" colspan=3>
		         <?php echo getInputText(voPenalidadePA::$nmAtrSq, voPenalidadePA::$nmAtrSq, complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_READONLY);?>
				</TD>
	        </TR>
			<?php 
				getContratoDet($voContrato);			
	        }else{
	            $selectExercicio = new selectExercicio();
	            $vo->dtAbertura = dtHojeSQL;
			  ?>			            
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.A.P.:</TH>
		        <TD class="campoformulario" colspan=3>		        
		         <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPenalidadePA::$nmAtrAnoPA,voPenalidadePA::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();' ");?>			            
			     Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPenalidadePA::$nmAtrCdPA?>" name="<?=voPenalidadePA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" 
			     size="6" maxlength="5" required onBlur='carregaDadosContratada();'>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>			                                           
	        </TR>			            
	        <?php	        
	        $comboTipo = new select(dominioTipoPenalidade::getColecaoComReferenciaLegal());
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Tipo:</TH>
	            <TD class="campoformulario" nowrap colspan=3>
				  <?php echo $comboTipo->getHtmlCombo(voPenalidadePA::$nmAtrTipo, voPenalidadePA::$nmAtrTipo, $vo->tipo, true, "camponaoobrigatorio", true, " required ");?>
			  </TD>			  
			</TR>	        
	        <?php 
	       }
	       
			if($vo->fundamento == null){
				$vo->fundamento = voPenalidadePA::getCampoFundamentoModelo();
			}
	       
	       ?>	           				
			<TR>
	            <TH class="campoformulario" nowrap>Fundamento:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="3" cols="80" id="<?=voPenalidadePA::$nmAtrFundamento?>" name="<?=voPenalidadePA::$nmAtrFundamento?>" class="camponaoobrigatorio" required><?php echo($vo->fundamento);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voPenalidadePA::$nmAtrObservacao?>" name="<?=voPenalidadePA::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap>Data Aplicação:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voPenalidadePA::$nmAtrDtAplicacao?>" 
	            	       name="<?=voPenalidadePA::$nmAtrDtAplicacao?>" 
	            			value="<?php echo(getData($vo->dtAplicacao));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
	            <SCRIPT language="JavaScript" type="text/javascript">
	            	colecaoIDCamposRequired = [
		            	"<?=voPenalidadePA::$nmAtrDtAplicacao?>",
		            	"<?=voPenalidadePA::$nmAtrFundamento?>"];
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
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex);	
}
?>