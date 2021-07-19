<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");

try{
	//inicia os parametros
	inicioComValidacaoUsuario(true);

	$vo = new voContratoLicon();
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
		$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

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


	$titulo = voContratoLicon::getTituloJSP();
	$titulo = $nmFuncao . $titulo;
	setCabecalho($titulo);

?>
<!DOCTYPE html>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_contrato.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	var campoSituacao = document.frm_principal.<?=voContratoLicon::$nmAtrSituacao?>;
	var campoObs = document.frm_principal.<?=voContratoLicon::$nmAtrObs?>;
	var situacao = campoSituacao.value;
	if(situacao == <?=dominioSituacaoContratoLicon::$CD_SITUACAO_INCLUIDO?>){		
		var obs = campoObs.value.toUpperCase();
		if(obs.indexOf("DIGO") == -1 && obs.indexOf("RECEBIMENTO") == -1 ){
			exibirMensagem("É necessário informar o código de recebimento no formato: 'Código de Recebimento: XXXX...'");
			return false;
		}
	} 
	
	return true;
}

function cancelar() {
	location.href="<?=getLinkRetornoConsulta()?>";
}


function confirmar() {
	if(!isFormularioValido())
		return false;

	return confirm("Confirmar Alteracoes?");    
}

/*function formataForm() {
	var campoEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrCdEspecieContrato?>;
	var especie = campoEspecie.value;
	
	var colecaoIDCamposRequired = ["<?=vocontrato::$nmAtrSqEspecieContrato?>"];
	var required = especie != "<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>";
	
	tornarRequiredCamposColecaoFormulario(colecaoIDCamposRequired, required);
}*/

function insereCodigoRecebimento(){
	var campoObs = document.frm_principal.<?=voContratoLicon::$nmAtrObs?>;
	var campoSituacao = document.frm_principal.<?=voContratoLicon::$nmAtrSituacao?>;
	var isSituacaoOK = campoSituacao.value == <?=dominioSituacaoContratoLicon::$CD_SITUACAO_INCLUIDO?> 
				|| campoSituacao.value == <?=dominioSituacaoContratoLicon::$CD_SITUACAO_INCLUIDO_COM_OBS?>;
	var obs = campoObs.value;
	if(isSituacaoOK && (obs == null || obs == "")){
		campoObs.value = "Código do Recebimento: " + campoObs.value;
	}	
}

function carregaDadosContrato(){
	str = "";

	var cdContrato = document.frm_principal.<?=voContratoLicon::$nmAtrCdContrato?>.value;
	var anoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrAnoContrato?>.value;
	var tipoContrato = document.frm_principal.<?=voContratoLicon::$nmAtrTipoContrato?>.value;
	var cdEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrCdEspecieContrato?>.value;
	var sqEspecie = document.frm_principal.<?=voContratoLicon::$nmAtrSqEspecieContrato?>.value;
		
	if(cdContrato != "" && anoContrato != "" && tipoContrato != "" && cdEspecie != ""){

		if(cdEspecie == '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER?>')
			sqEspecie = 1;

		if(sqEspecie != ""){		
			str = "null"+ '<?=CAMPO_SEPARADOR?>' + anoContrato + '<?=CAMPO_SEPARADOR?>' + cdContrato + '<?=CAMPO_SEPARADOR?>' 
			+ tipoContrato + '<?=CAMPO_SEPARADOR?>' + cdEspecie
			+ '<?=CAMPO_SEPARADOR?>' + sqEspecie;
			//vai no ajax
			getDadosContratoLicon(str, '<?=voContratoLicon::$ID_REQ_DIV_DADOS_CONTRATO_LICON?>');
		}
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
	        $selectExercicio = new selectExercicio();	        
	        $complementoHTML = "";

	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        if(!$isInclusao){
	        	//ALTERACAO
	        	$complementoHTML = " required ";
	        	$readonlyChaves = " readonly ";
	        	
	        	getDemandaDetalhamentoComLupa($voDemanda, true);
	        	echo getLinkPortarias();
	
	        	getContratoDet($voContrato, false, true);
	        ?>
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">PL:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo getDetalhamentoHTML("", "", $voContrato->procLic)?>
				</TD>				
	        </TR>
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Datas</TH>
	            <TD class="campoformulario" width="1%"  colspan=3>
	            |Vigência: <?php echo getDetalhamentoHTML("", "", $voContrato->dtVigenciaInicial) . " a " . getDetalhamentoHTML("", "", $voContrato->dtVigenciaFinal)?>
	            |Dt.Publicação: <?php echo getDetalhamentoHTML("", "", $voContrato->dtPublicacao)?>
	            |Dt.Assinatura: <?php echo getDetalhamentoHTML("", "", $voContrato->dtAssinatura)?>
				</TD>				
	        </TR>
	        <TR>	       
	            <TH class="campoformulario" nowrap width="1%">Valores:</TH>
	            <TD class="campoformulario" colspan=3>
	            |Mensal: <?php echo getDetalhamentoHTML("", "", getMoeda($voContrato->vlMensalSQL,2))?>
	            |Global: <?php echo getDetalhamentoHTML("", "", getMoeda($voContrato->vlGlobalSQL,2))?>
				</TD>
	        </TR>
	        <?php
	        }else{
	        	//INCLUSAO
	        ?>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voContratoLicon::$nmAtrAnoDemanda,voContratoLicon::$nmAtrAnoDemanda, $vo->anoDemanda, true, "campoobrigatorio", false, " required ");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voContratoLicon::$nmAtrCdDemanda?>" name="<?=voContratoLicon::$nmAtrCdDemanda?>"  value="<?php echo(complementarCharAEsquerda($vo->cdDemanda, "0", 5));?>"  class="<?=$classChaves?>" size="6" maxlength="5" <?=$readonlyChaves?> required>
	        </TR>	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            //getCampoDadosContratoSimples(constantes::$CD_CLASS_CAMPO_OBRIGATORIO, "carregaDadosContrato()", false);	            
	            $pArray = array(null,constantes::$CD_CLASS_CAMPO_OBRIGATORIO,true,true,false,true,"carregaDadosContrato();");
	            getContratoEntradaArray($pArray);	            	 
	            ?>
				<div id="<?=voContratoLicon::$ID_REQ_DIV_DADOS_CONTRATO_LICON?>">
				</div>	            
	            </TD>
	        </TR>	        	        
	        <?php 
	       }	       
	       ?>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" colspan="3">
				<?php                        
				$comboSituacaoLicon = new select(dominioSituacaoContratoLicon::getColecaoManter());                        
				//cria o combo
				echo $comboSituacaoLicon->getHtmlCombo(voContratoLicon::$nmAtrSituacao, voContratoLicon::$nmAtrSituacao, $vo->situacao, true, "camponaoobrigatorio", false, " onChange='insereCodigoRecebimento();' required");
				?>
				</TD>				
	        </TR>	        
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Observação:</TH>
	            <TD class="campoformulario" colspan="3"><textarea rows="5" cols="80" id="<?=voContratoLicon::$nmAtrObs?>" name="<?=voContratoLicon::$nmAtrObs?>" class="camponaoobrigatorio"><?=$vo->obs?></textarea>
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
	tratarExcecaoHTML($ex, $vo);
}
?>
