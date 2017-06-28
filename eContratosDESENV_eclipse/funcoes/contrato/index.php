<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbcontrato.php");

//inicia os parametros
try{
inicio();

$titulo = "CONSULTAR CONTRATOS";
setCabecalho($titulo);

$voContrato = new voContrato();

$filtro  = new filtroManterContrato();
$filtro = filtroManter::verificaFiltroSessao($filtro);

$cdContrato = $filtro->cdContrato;
$anoContrato = $filtro->anoContrato;
$tipo = $filtro->tipo;
    
$modalidade  = $filtro->modalidade;
$especie  = $filtro->especie;
$cdEspecie  = $filtro->cdEspecie;
$nmGestor  = $filtro->gestor;
$nmContratada  = $filtro->contratada;
$docContratada = $filtro->docContratada;
$dsObjeto  = $filtro->objeto;
$dtVigenciaInicial  = $filtro->dtVigenciaInicial;
$dtVigenciaFinal  = $filtro->dtVigenciaFinal;
$dtVigencia  = $filtro->dtVigencia;
$dtInicio1  = $filtro->dtInicio1;
$dtInicio2  = $filtro->dtInicio2;
$dtFim1  = $filtro->dtFim1;
$dtFim2  = $filtro->dtFim2;
$cdHistorico = $filtro->cdHistorico;
$isHistorico = ("S" == $cdHistorico); 

if($filtro->cdConsultarArquivo == null){
	$filtro->cdConsultarArquivo = "N";
}

$requiredArquivo = "";
/*if($filtro->cdConsultarArquivo == "S"){
	$requiredArquivo = "required";
}*/

$dbprocesso = new dbcontrato();
//$colecao = $dbprocesso->consultarComPaginacao($voContrato, $filtro, $numTotalRegistros, $pagina, $qtdRegistrosPorPag);
$colecao = $dbprocesso->consultarFiltroManterContrato($voContrato, $filtro);

//aqui verifica se pelo menos um filtro valido foi inserido
//se nao, seta os filtros defalts para diminuir o retorno da consulta
//o trecho deve ficar depois da consulta que eh quando sao setados no filtro os valores default
//if($filtro->temValorDefaultSetado){
	$anoContrato  = $filtro->anoContrato;
//}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<?=setTituloPagina(vocontrato::getTituloJSP())?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function formataForm() {
	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;

	if(isConsultarArquivo != "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_COMUM?>"){
		document.frm_principal.<?=filtroManterContrato::$nmAtrAnoArquivo?>.disabled = false;
	}else{
		document.frm_principal.<?=filtroManterContrato::$nmAtrAnoArquivo?>.disabled = true;
	}
	
	if(isConsultarArquivo == "<?=dominioConsultaArquivoContrato::$CD_CONSULTA_ARQUIVO_CONTRATO_ASSINADO?>"){
			//por conta da estrutura de arquivos da _dag$/UNCT, deve-se obrigar o nome da contratada			
		document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = true;
	}else{
		document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = false;
	}
}

function validaFormulario() {

	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;
	if(isConsultarArquivo != "N"){							
		var colecaoNmCamposForm = ["<?=vocontrato::$nmAtrCdContrato?>", "<?=vocontrato::$nmAtrContratadaContrato?>"];
		var colecaoDescricaoCamposForm = ["Número do Contrato", " ou Nome Contratada"];
		if(!isPeloMenosUmCampoFormularioSelecionado(colecaoNmCamposForm, colecaoDescricaoCamposForm, true))
			return false;	
	}

	return true;			
}

// Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
		cdReferenciaLegal = array[0];
		dsReferenciaLegal = array[1];
		dtInicioVigenciaReferenciaLegal = array[2];
		dtFimVigenciaReferenciaLegal = array[3];
		window.opener.transferirDadosReferenciaLegal(cdReferenciaLegal, dsReferenciaLegal, dtInicioVigenciaReferenciaLegal, dtFimVigenciaReferenciaLegal);
		window.close();
	}
}

function detalhar(isExcluir) {    
    if(isExcluir == null || !isExcluir)
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    else
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";
    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
    	
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="detalharContrato.php?funcao=" + funcao + "&chave=" + chave;
}


function movimentacoes(){    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
  	
	var array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "*", false);
	especie = array[4];
	
	if(especie != '<?=dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;?>'){
		alert("Operação permitida apenas para contrato Mater.");
		return;
	}

	chave = document.frm_principal.rdb_consulta.value;
    url = "movimentacaoContrato.php?chave=" + chave;
	
    abrirJanelaAuxiliar(url, true, false, false);
    
}

function excluir() {
    detalhar(true);
}

function incluir() {
	location.href="manterContrato.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manterContrato.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

function confirmar() {
	if (!validaFormulario()) {
		return false;
	}	
	    
	return true;
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="formataForm();">	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S" onSubmit="return confirmar();">

<INPUT type="hidden" id="evento" name="<%=PRManterReferenciaLegal.ID_REQ_EVENTO%>" value=""> 
<INPUT type="hidden" name="utilizarSessao" value="N"> 
<INPUT type="hidden" id="numTotalRegistros" value="<?=$numTotalRegistros?>">
<INPUT type="hidden" name="consultar" id="consultar" value="N">    

<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro">
        <?=cabecalho?>
		</TD>
		</TR>
<TR>
    <TD class="conteinerfiltro">
    <DIV id="div_filtro" class="div_filtro">
    <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
        <TBODY>
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" width="1%"><?php getContratoEntradaDeDados($tipo, $cdContrato, $anoContrato, $arrayCssClass, null, null, false);?></TD>
				<?php 
				$comboConsultaArquivo = new select(dominioConsultaArquivoContrato::getColecao());
				$selectExercicio = new selectExercicio();
				?>									
    			<TH class="campoformulario" nowrap>Procurar em arquivos:</TH>
               	<TD class="campoformulario" nowrap> 
               	<?php 
               	echo $comboConsultaArquivo->getHtmlCombo("cdConsultarArquivo","cdConsultarArquivo", $filtro->cdConsultarArquivo, false, "camponaoobrigatorio", false, " onChange='formataForm();' ");              	               	
               	echo "Ano Arquivo: " . $selectExercicio->getHtmlCombo(filtroManterContrato::$nmAtrAnoArquivo,filtroManterContrato::$nmAtrAnoArquivo, $filtro->anoArquivo, true, "camponaoobrigatorio", false, "");
               	?>
				</TD>
            </TR>
			<?php
            $dominioTipoContrato = new dominioTipoContrato();            
			$tiposContrato = new select($dominioTipoContrato->colecao);            
			
			$comboEspecies = new select(dominioEspeciesContrato::getColecao());
			$comboTpDemanda = new select(dominioTipoDemanda::getColecaoTipoDemandaContrato());				
			?>
    			<TH class="campoformulario" nowrap>Espécies:</TH>
                <TD class="campoformulario"><?php echo $comboEspecies->getHtmlCombo(vocontrato::$nmAtrCdEspecieContrato, vocontrato::$nmAtrCdEspecieContrato."[]", $filtro->cdEspecie, true, "camponaoobrigatorio", false, " multiple ")?>
                </TD>
                <TH class="campoformulario" nowrap>Demandas:</TH>
                <TD class="campoformulario"><?php echo $comboTpDemanda->getHtmlCombo(filtroManterContrato::$nmAtrTpDemanda, filtroManterContrato::$nmAtrTpDemanda."[]", $filtro->tpDemanda, true, "camponaoobrigatorio", false, " multiple ")?>
                </TD>                												                
	        </TR>                 
			<TR>
                 <TH class="campoformulario" nowrap>Gestor:</TH>
                 <TD class="campoformulario">
                                <INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="camponaoobrigatorio" size="50" ></TD>
                <TH class="campoformulario" nowrap>Modalidade:</TH>
				<?php
				include_once("dominioModalidadeLicitacao.php");
				$modalidades = new dominioModalidadeLicitacao();
				$combo = new select($modalidades->colecao);						
				?>
                 <TD class="campoformulario" nowrap><?php echo $combo->getHtml("cdModalidade","cdModalidade", $modalidade);?>
                    <INPUT type="text" id="<?=vocontrato::$nmAtrModalidadeContrato?>" name="<?=vocontrato::$nmAtrModalidadeContrato?>"  value="<?php echo($modalidade);?>"  class="camponaoobrigatorio" size="30" >
                 </TD>                                
            </TR>
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="50" <?=$requiredArquivo?>></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
			<TR>
				<TH class="campoformulario" nowrap>Objeto:</TH>
				<TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>"  value="<?php echo($dsObjeto);?>"  class="camponaoobrigatorio" size="50" ></TD>
               <TH class="campoformulario" nowrap>Data Inclusão:</TH>
               <TD class="campoformulario" >
                        	<INPUT type="text" 
                        	       id="<?=vocontrato::$nmAtrDhInclusao?>" 
                        	       name="<?=vocontrato::$nmAtrDhInclusao?>"
                        			value="<?php echo($filtro->dtInclusao);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                </TD>				
			</TR>
			<TR>
				<TH class="campoformulario" nowrap>Intervalo Data Inicial:</TH>
				<TD class="campoformulario">
                          	<INPUT type="text" 
                        	       id="dtInicio1" 
                        	       name="dtInicio1" 
                        			value="<?php echo($dtInicio1);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="dtInicio2" 
                        	       name="dtInicio2" 
                        			value="<?php echo($dtInicio2);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
																</TD>
                <TH class="campoformulario" nowrap>Intervalo Data Final:</TH>
                <TD class="campoformulario">
                        	<INPUT type="text" 
                        	       id="dtFim1" 
                        	       name="dtFim1" 
                        			value="<?php echo($dtFim1);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="dtFim2" 
                        	       name="dtFim2" 
                        			value="<?php echo($dtFim2);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
				</TD>						
         </TR>
		<TR>
               <TH class="campoformulario" nowrap>Vigente na Data:</TH>
               <TD class="campoformulario">
                        	<INPUT type="text" 
                        	       id="dtVigencia" 
                        	       name="dtVigencia" 
                        			value="<?php echo($dtVigencia);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >                        			                	 
               						<?php /*echo "Consolidado: " . $radioArquivo->getHtmlRadioButton(filtroManterContrato::$nmAtrInTrazerConsolidadoPorVigencia,
               												filtroManterContrato::$nmAtrInTrazerConsolidadoPorVigencia, 
               												$filtro->inTrazerConsolidadoVigencia, 
               												false, "");*/?>
                </TD>
		
               <TH class="campoformulario" nowrap>Vigente no Intervalo:</TH>
               <TD class="campoformulario">
                        	<INPUT type="text" 
                        	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
                        	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
                        			value="<?php echo($dtVigenciaInicial);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > a
                        	<INPUT type="text" 
                        	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
                        	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
                        			value="<?php echo($dtVigenciaFinal);?>"
                        			onkeyup="formatarCampoData(this, event, false);"
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
				</TD>
         </TR>
		<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");				
				$nmCheckAutorizacaoArray = vocontrato::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario" colspan=3>
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroManterContrato::$NmAtrInOR_AND,filtroManterContrato::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>
	    </TR>		
		<TR>
			<TH class="campoformulario" nowrap>Tp.Vigência:</TH>
			<?php
			include_once(caminho_util."dominioTpVigencia.php");
			$comboVigencia = new select(dominioTpVigencia::getColecao());						
			?>
            <TD class="campoformulario" nowrap colspan=3><?php echo $comboVigencia->getHtmlOpcao($filtro::$nmAtrTpVigencia,$filtro::$nmAtrTpVigencia, $filtro->tpVigencia, false);?></TD>
	    </TR>					
				<?php
				echo getComponenteConsultaFiltro($voContrato->temTabHistorico, $filtro);
				?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<?php 

if($filtro->cdConsultarArquivo != "N"){
	include("grid_arquivo.php");
}else{
	include("grid_contrato.php");
}

?>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);	
}
