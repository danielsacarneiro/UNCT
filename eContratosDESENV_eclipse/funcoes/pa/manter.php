<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voPA.php");
include_once(caminho_vos."voPATramitacao.php");
include_once(caminho_vos."voDocumento.php");

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
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);
	
	$colecaoRegistroBanco = $dbprocesso->consultarTramitacao($vo);
	if($colecaoRegistroBanco != ""){
		$vo->setColecaoTramitacao($colecaoRegistroBanco);
		putObjetoSessao(voPA::$nmAtrColecaoTramitacao, $vo->colecaoTramitacao);
	}

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
		
	cdContrato = document.frm_principal.<?=voPA::$nmAtrCdContrato?>.value;
	anoContrato = document.frm_principal.<?=voPA::$nmAtrAnoContrato?>.value;
	tpContrato = document.frm_principal.<?=voPA::$nmAtrTipoContrato?>.value;

	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = cdContrato + '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + tpContrato;
		//vai no ajax
		getDadosContratadaPorContrato(str, '<?=vopessoa::$nmAtrNome?>');
	}
}

function incluirTramitacao(){
	textoFase = document.frm_principal.<?=voPATramitacao::$nmAtrObservacao?>.value;
	docFase = document.frm_principal.<?=voDocumento::getNmTabela()?>.value;
	if(textoFase != ""){
		manterDadosTramitacaoPA(textoFase, docFase, 'div_tramitacao', '<?=constantes::$CD_FUNCAO_INCLUIR?>');
		document.frm_principal.<?=voDocumento::getNmTabela()?>.value = "";
		document.frm_principal.<?=voDocumento::$nmAtrSq?>.value = "";
	}
	else
		exibirMensagem("Tramita��o n�o pode ser vazia!");	
}

function excluirTramitacao(){	 
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_tramitacao"))
        return;

	if(confirm("Confirmar Alteracoes?")){
		
		indice = document.frm_principal.rdb_tramitacao.value;		
		manterDadosTramitacaoPA("", "", 'div_tramitacao', '<?=constantes::$CD_FUNCAO_EXCLUIR?>', indice);
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
	        require_once ("../contrato/dominioTipoContrato.php");
	        $dominioTipoContrato = new dominioTipoContrato();
	        
	        $contrato = formatarCodigoAnoComplemento($colecao[voPA::$nmAtrCdContrato],
	        		$colecao[voPA::$nmAtrAnoContrato],
	        		$dominioTipoContrato->getDescricao($colecao[voPA::$nmAtrTipoContrato]));
	         
	        $procAdm = formatarCodigoAno($colecao[voPA::$nmAtrCdPA],
	        		$colecao[voPA::$nmAtrAnoPA]);
	         
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
				<INPUT type="hidden" id="<?=voPA::$nmAtrCdContrato?>" name="<?=voPA::$nmAtrCdContrato?>" value="<?=$vo->cdContrato?>">
				<INPUT type="hidden" id="<?=voPA::$nmAtrAnoContrato?>" name="<?=voPA::$nmAtrAnoContrato?>" value="<?=$vo->anoContrato?>">
				<INPUT type="hidden" id="<?=voPA::$nmAtrAnoPA?>" name="<?=voPA::$nmAtrAnoPA?>" value="<?=$vo->anoPA?>">
				<INPUT type="hidden" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>" value="<?=$vo->cdPA?>">	                    
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
		            	<?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoPA,voPA::$nmAtrAnoPA, $vo->anoPA, true, "campoobrigatorio", false, " required ");?>			            
			            N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdPA?>" name="<?=voPA::$nmAtrCdPA?>"  value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3));?>"  class="camponaoobrigatorioalinhadodireita" size="6" maxlength="5" <?=$readonlyChaves?>>                               
	        </TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voPA::$nmAtrAnoContrato,voPA::$nmAtrAnoContrato, $vo->anoContrato, true, "campoobrigatorio", false, " required onChange='carregaDadosContratada();'");?>
			  N�mero: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voPA::$nmAtrCdContrato?>" name="<?=voPA::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->cdContrato, "0", 3));?>"  class="<?=$classChaves?>" size="4" maxlength="3" <?=$readonlyChaves?> required onBlur='carregaDadosContratada();'>
			  <?php echo $combo->getHtmlCombo(voPA::$nmAtrTipoContrato,voPA::$nmAtrTipoContrato, "", true, "camponaoobrigatorio", false, " onChange='carregaDadosContratada();' ");	
			  ?>
			  <div id="<?=vopessoa::$nmAtrNome?>">
	          </div>
	        </TR>			            
	        <?php 
	       }	                    
	       ?>	           				
	        <?php 
	        //require_once ("dominioSituacaoPA.php");	        
	        $domSiPA = new dominioSituacaoPA();
	        $situacao = $colecao[voPA::$nmAtrSituacao];
	        $situacao = $domSiPA->getDescricao($situacao);	        
	        ?>
			<TR>
	            <TH class="campoformulario" nowrap>Situa��o:</TH>
	            <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(strtoupper($situacao));?>"  class="camporeadonly" size="20" readonly></TD>
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
	            <TH class="campoformulario" nowrap>Proc.Licitatorio:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voPA::$nmAtrProcessoLicitatorio?>" name="<?=voPA::$nmAtrProcessoLicitatorio?>"  value="<?php echo($vo->processoLic);?>"  class="camponaoobrigatorio" size="50" ></TD>
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
	            			value="<?php echo($vo->dtAbertura);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>
        	</TR>
    
<TR>
	<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
	<DIV class="campoformulario" id="div_tramitacao">
	<?php 
	$isDetalhamento = false;
	include_once 'gridTramitacaoAjax.php';
	?>
	</DIV>
	</TH>
</TR>
<TR>
	<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
	<DIV class="campoformulario">Incluir Tramita��o:
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>             
                <TR class="dados">
		            <TH class="campoformulario" width="1%" nowrap>Texto:</TH>
		            <TD class="campoformulario" width="1%" nowrap>
		            	<textarea rows="2" cols="60" id="<?=voPATramitacao::$nmAtrObservacao?>" name="<?=voPATramitacao::$nmAtrObservacao?>" class="camponaoobrigatorio" ></textarea>
					</TD>
		            <TH class="campoformulario" width="1%" nowrap>Documento:</TH>
		            <TD class="campoformulario" width="1%" nowrap>
		                    <INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>" class="camporeadonly" size="15" readonly>
		                    <INPUT type="hidden" id="<?=voDocumento::getNmTabela()?>" name="<?=voDocumento::getNmTabela()?>" value="">
		                    <?php echo getLinkPesquisa("../documento");?>
					</TD>
                    <TD class="campoformulario">
                    <?php 
                    echo getBotaoValidacaoAcesso("bttincluir_tram", "Incluir", "botaofuncaop", false,false,true, false, "onClick='incluirTramitacao();' accesskey='i'");
                    echo getBotaoValidacaoAcesso("bttexcluir_tram", "Excluir", "botaofuncaop", false,false,true, false, "onClick='excluirTramitacao();' accesskey='e'");                    
                    ?>
                    </TD>                    
                </TR>					
            </TBODY>
        </TABLE>
	
	</DIV>
	</TH>
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