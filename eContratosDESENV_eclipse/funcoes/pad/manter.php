<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voPAD.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voPAD();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$classChaves = "campoobrigatorio";
$readonlyChaves = "";

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

if($vo->dtAbertura == null|| $vo->dtAbertura == "")
	$vo->dtAbertura = dtHoje;
	
$titulo = "P.A.D.";
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
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
	return confirm("Confirmar Alteracoes?");    
}

function carregaDadosContratada(){    
	str = "";
		
	cdContrato = document.frm_principal.<?=voPAD::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=voPAD::$nmAtrAnoContrato?>.value;
	tpContrato = document.frm_principal.<?=voPAD::$nmAtrTipoContrato?>.value;

	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = cdContrato + '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + tpContrato;			

		//vai no ajax
		getDadosContratadaPorContrato(str, '<?=vopessoa::$nmAtrNome?>');

		/*campo = document.getElementById('<?=vopessoa::$nmAtrNome?>');
		alert(campo.innerHTML);
		if(campo.innerHTML=="" || campo.innerHTML=='<img src="../../imagens/loading/loading26.gif"/>')
			alert(0);*/

	}
	
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
	        include_once("../contrato/dominioTipoContrato.php");
	        $dominioTipoContrato = new dominioTipoContrato();
	        
	        $contrato = formatarCodigoAnoComplemento($colecao[voPAD::$nmAtrCdContrato],
	        		$colecao[voPAD::$nmAtrAnoContrato],
	        		$dominioTipoContrato->getDescricao($colecao[voPAD::$nmAtrTipoContrato]));
	         
	        $procAdm = formatarCodigoAno($colecao[voPAD::$nmAtrCdPA],
	        		$colecao[voPAD::$nmAtrAnoPA]);
	         
	        if(!$isInclusao){
	        ?>	        	        
			<TR>
		         <TH class="campoformulario" nowrap width="1%">P.A.D.:</TH>
		         <TD class="campoformulario" colspan=3>
		         <INPUT type="text" value="<?php echo($procAdm);?>"  class="camporeadonly" size="10" readonly>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
				<INPUT type="hidden" id="<?=voPAD::$nmAtrCdContrato?>" name="<?=voPAD::$nmAtrCdContrato?>" value="<?=$vo->cdContrato?>">
				<INPUT type="hidden" id="<?=voPAD::$nmAtrAnoContrato?>" name="<?=voPAD::$nmAtrAnoContrato?>" value="<?=$vo->anoContrato?>">
				<INPUT type="hidden" id="<?=voPAD::$nmAtrAnoPA?>" name="<?=voPAD::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
				<INPUT type="hidden" id="<?=voPAD::$nmAtrCdPA?>" name="<?=voPAD::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">	                    
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
	        	$combo = new select($dominioTipoContrato->colecao);
	            $selectExercicio = new selectExercicio();
			  ?>			            
			<TR>
		        <TH class="campoformulario" nowrap width="1%">P.A.D.:</TH>
		        <TD class="campoformulario" colspan=3>
		            	<?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPAD::$nmAtrAnoPA,voPAD::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required ");?>			            
			            Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPAD::$nmAtrCdPA?>" name="<?=voPAD::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" <?=$readonlyChaves?>>                               
	        </TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPAD::$nmAtrAnoContrato,voPAD::$nmAtrAnoContrato, $vo->anoContrato, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();'");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPAD::$nmAtrCdContrato?>" name="<?=voPAD::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->cdContrato, "0", 3));?>"  class="<?=$classChaves?>" size="4" maxlength="3" <?=$readonlyChaves?> required onBlur='carregaDadosContratada();'>
			  <?php echo $combo->getHtmlCombo(voPAD::$nmAtrTipoContrato,voPAD::$nmAtrTipoContrato, "", true, "camponaoobrigatorio", false, " onChange='carregaDadosContratada();' ");	
			  ?>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>			            
	        <?php 
	       }	                    
	       ?>	           				

			<TR>
	            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voPAD::$nmAtrProcessoLicitatorio?>" name="<?=voPAD::$nmAtrProcessoLicitatorio?>"  value="<?php echo($vo->processoLic);?>"  class="camponaoobrigatorio" size="50" ></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voPAD::$nmAtrObservacao?>" name="<?=voPAD::$nmAtrObservacao?>" class="camponaoobrigatorio" ><?php echo($vo->obs);?></textarea>
				</TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap>Data Abertura:</TH>
	            <TD class="campoformulario" colspan="3">
	            	<INPUT type="text" 
	            	       id="<?=voPAD::$nmAtrDtAbertura?>" 
	            	       name="<?=voPAD::$nmAtrDtAbertura?>" 
	            			value="<?php echo($vo->dtAbertura);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>
	        <?php if(!$isInclusao){
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
	        }?>
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
