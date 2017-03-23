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
		
    $nmFuncao = "ENCAMINHAR ";
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>

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

function carregaContratada() {
	<?php 
	$nmCampoDiv = vopessoa::$nmAtrNome;
	?>
	//ta na biblioteca_funcoes_pessoa.js
	NmCampoCdContrato = '<?=vocontrato::$nmAtrCdContrato;?>';
	NmCampoAnoContrato = '<?=vocontrato::$nmAtrAnoContrato;?>';
	NmCampoTipoContrato = '<?=vocontrato::$nmAtrTipoContrato;?>';
	NmCampoDiv = '<?=$nmCampoDiv;?>';
	
	carregaDadosContratada(NmCampoAnoContrato, NmCampoTipoContrato, NmCampoCdContrato, NmCampoDiv);    
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
	        $comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	        $selectExercicio = new selectExercicio();	         
	        
	        $votram->dhInclusao = dtHoje;
	        
	        $complementoHTML = "";
	        
	        if(!$isInclusao){
	        	//ALTERACAO
	        	$complementoHTML = " required ";
	        	$readonlyChaves = " readonly ";
	        ?>	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo("","", $vo->ano, true, "camporeadonly", false, " disabled ");?>
	            <?php echo "Tipo: " . $comboTipo->getHtmlCombo("","", $vo->tipo, true, "camporeadonly", false, " disabled ");?>
				Número: <INPUT type="text" value="<?=complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS);?>"  class="camporeadonly" size="6" readonly>	            
	            
	            <INPUT type="hidden" id="<?=voDemanda::$nmAtrAno?>" name="<?=voDemanda::$nmAtrAno?>" value="<?=$vo->ano?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>" value="<?=$vo->cd?>">	            			  
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrTipo?>" name="<?=voDemanda::$nmAtrTipo?>" value="<?=$vo->tipo?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCdSetor?>" name="<?=voDemanda::$nmAtrCdSetor?>" value="<?=$vo->cdSetor?>">
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Responsável:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo $comboSetor->getHtmlCombo("","", $vo->cdSetor, true, "camporeadonly", false, " disabled ");?>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario" >
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "camporeadonly", false, " disabled ");?>	            
				</TD>
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" value="<?=$vo->texto?>"  class="camporeadonly" size="80" readonly>	            	                        	                        
	        </TR>	        
	        <?php	        	        	        
	        //so exibe contrato se tiver
	        $voDemandaContrato = new voDemandaContrato();
	        $voDemandaContrato->getDadosBanco($colecao);
	         
	        if($voDemandaContrato->sqContrato != null){
	        	$voContrato = new vocontrato();
	        	$voContrato->getDadosBanco($colecao);
	        	
	        	require_once (caminho_funcoes."contrato/dominioTipoContrato.php");
	        	$dominioTipoContrato = new dominioTipoContrato();
	        	$contrato = formatarCodigoAnoComplemento($voContrato->cdContrato,
	        			$voContrato->anoContrato,
	        			$dominioTipoContrato->getDescricao($voContrato->tipo));
	        	
	        	include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");
	        	$nmpessoa = $colecao[vopessoa::$nmAtrNome];
	        	$docpessoa = $colecao[vopessoa::$nmAtrDoc];
	        	$campoContratado = getCampoContratada($nmpessoa, $docpessoa, $voContrato->sq);
	        ?>
			<TR>
	            <INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>" value="<?=$voContrato->anoContrato?>">
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>" value="<?=$voContrato->cdContrato?>">	            			  
				<INPUT type="hidden" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>" value="<?=$voContrato->tipo?>">
                <TH class="campoformulario" nowrap width=1%>Contrato:</TH>
				<TD class="campoformulario" colspan=3>Número:&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type="text" value="<?php echo($contrato);?>"  class="camporeadonlyalinhadodireita" size="<?=strlen($contrato)?>" readonly>				
				<div id=""><?=$campoContratado?></div></TD>
            </TR>
            <?php }?>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
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
	        <?php 
	        	require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioTipoContrato.php");	        	
	        	$combo = new select(dominioTipoContrato::getColecao());
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(vocontrato::$nmAtrAnoContrato,vocontrato::$nmAtrAnoContrato, $vo->anoContrato, true, "campoobrigatorio", false, " required onChange='carregaContratada();'");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($vo->cdContrato, "0", TAMANHO_CODIGOS_SAFI));?>"  class="<?=$classChaves?>" size="4" maxlength="3" <?=$readonlyChaves?> required onBlur='carregaContratada();'>
			  <?php echo $combo->getHtmlCombo(vocontrato::$nmAtrTipoContrato,vocontrato::$nmAtrTipoContrato, "", true, "camponaoobrigatorio", false, " required onChange='carregaContratada();' ");	
			  ?>
			  <div id="<?=$nmCampoDiv?>">
	          </div>
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
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value=""  class="campoobrigatorio" size="80" required>	            	                        	                        
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "campoobrigatorio", false, " required ");?>
				</TD>
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
