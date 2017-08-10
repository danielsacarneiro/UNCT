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
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	/*if(!isFormularioValido())
		return false;*/
	
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
				 </TD>
	        </TR>
			<?php 
			getDemandaDetalhamento($voDemanda);
			getContratoDet($voContrato);
			
	        }else{
	            $selectExercicio = new selectExercicio();
			  ?>			            
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.D.:</TH>
		        <TD class="campoformulario" colspan=3>
		            	<?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoPA,voPA::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required ");?>			            
			            N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" <?=$readonlyChaves?>>                               
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
	        <?php 
	        //require_once ("dominioSituacaoPA.php");	        
	        $domSiPA = new dominioSituacaoPA();
	        $comboSituacao = new select($domSiPA::getColecao());	        
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap>Situa��o:</TH>
	            <TD class="campoformulario" colspan=3><?php echo $comboSituacao->getHtmlCombo(voPA::$nmAtrSituacao,voPA::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?></TD>
				</TD>
	        </TR>
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
