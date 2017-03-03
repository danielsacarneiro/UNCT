<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once("dominioTipoContrato.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbcontrato.php");

//inicia os parametros
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
if($filtro->cdConsultarArquivo == "S"){
	$requiredArquivo = "required";
}

$dbprocesso = new dbcontrato();
//$colecao = $dbprocesso->consultarComPaginacao($voContrato, $filtro, $numTotalRegistros, $pagina, $qtdRegistrosPorPag);
$colecao = $dbprocesso->consultarFiltroManterContrato($voContrato, $filtro);

//aqui verifica se pelo menos um filtro valido foi inserido
//se nao, seta os filtros defalts para diminuir o retorno da consulta
//o trecho deve ficar depois da consulta que eh quando sao setados no filtro os valores default
if($filtro->temValorDefaultSetado){
	$anoContrato  = $filtro->anoContrato;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function validaFormulario() {

	isConsultarArquivo = document.frm_principal.cdConsultarArquivo.value;
	if(isConsultarArquivo == "S")
		document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = true;
	else
		document.frm_principal.<?=vocontrato::$nmAtrContratadaContrato?>.required = false;
		
}

// Submete o filtro de consulta 
function processarFiltroConsulta(pAcao, pEvento, pNaoUtilizarIdContextoSessao) {
	
	if (!isCampoTextoValido(document.frm_principal.dsReferenciaLegal, false, 0, 100))
	    return false;
	    	
	if (!isCampoNumericoValido(document.frm_principal.primeiro_campo, false, 0, 32767, null, false)) {
		return false
	}	    

	document.frm_principal.nao_utilizar_id_contexto_sessao.value = pNaoUtilizarIdContextoSessao;
	document.frm_principal.id_contexto_sessao.value = "";
	submeterFormulario(pAcao, pEvento);
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

/*function limparFormulario() {	

	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
	}	
	frm_principal.<?=vocontrato::$nmAtrAnoContrato?>.value = <?php echo(anoDefault);?>;
	frm_principal.<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>.value = "<?php echo dtHoje;?>";
	frm_principal.<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>.value = "<?php echo dtHoje;?>";	
}*/

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
	if(especie != <?=constantes::$CD_ESPECIE_CONTRATO_MATER;?>){
		alert("Opera��o permitida apenas para contrato Mater.");
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

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">

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
			$colecaoExer = array();
            for ($i=anoDefault;$i>2000;$i--){            	
            	$colecaoExer[$i]=$i;
            }			            
			$comboExercicio = new select($colecaoExer);
			?>        
            <TR>
                <TH class="campoformulario" nowrap>Ano Contrato Mater:</TH>
                <TD class="campoformulario" ><?php echo $comboExercicio->getHtml(vocontrato::$nmAtrAnoContrato,voContrato::$nmAtrAnoContrato, $anoContrato);?>
                <!-- <INPUT type="text" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>"  value="<?php echo($anoContrato);?>"  class="camponaoobrigatorio" size="6" maxlength="4" >--></TD>                
				<?php 
				include_once(caminho_util."radiobutton.php");
				$arraySimNao = array("S" => "Sim",
						"N" => "N�o");		
				$radioArquivo = new radiobutton($arraySimNao);				
				?>									
               <TH class="campoformulario" nowrap>Procurar em arquivos: 
               	<?php echo $radioArquivo->getHtmlRadioButton("cdConsultarArquivo","cdConsultarArquivo", $filtro->cdConsultarArquivo, false, "onClick='validaFormulario();'");?>&nbsp;&nbsp;
				</TH>
                <TD class="campoformularioalinhadodireita" colspan="1">
                    <a href="javascript:limparFormulario();" ><img  title="Limpar" src="<?=caminho_imagens?>borracha.jpg" width="20" height="20"></a>
                </TD>
            </TR>
			<?php
            $dominioTipoContrato = new dominioTipoContrato();            
			$tiposContrato = new select($dominioTipoContrato->colecao);
			?>            
            <TR>
                <TH class="campoformulario" nowrap>N�mero/Tipo:</TH>
                <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($cdContrato, "0", 3));?>"  class="camponaoobrigatorio" size="6" maxlength="5" >
                                                        <?php echo $tiposContrato->getHtml(vocontrato::$nmAtrTipoContrato,voContrato::$nmAtrTipoContrato, $tipo);?>
                </TD>
            </TR>					
            <TR>
			<?php
			//include_once("dominioEspeciesContrato.php");
			$especiesContrato = new dominioEspeciesContrato();
			$combo = new select($especiesContrato->colecao);						
			?>
    			<TH class="campoformulario" nowrap>Esp�cies:</TH>
                <TD class="campoformulario"><?php echo $combo->getHtmlSelect(vocontrato::$nmAtrCdEspecieContrato,vocontrato::$nmAtrCdEspecieContrato, $cdEspecie, true, "camponaoobrigatorio", true);?>
                <INPUT type="text" id="<?=vocontrato::$nmAtrEspecieContrato?>" name="<?=vocontrato::$nmAtrEspecieContrato?>"  value="<?php echo($especie);?>"  class="camponaoobrigatorio" size="30" >
                </TD>												                
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
                 <TH class="campoformulario" nowrap>Gestor:</TH>
                 <TD class="campoformulario" colspan="3">
                                <INPUT type="text" id="<?=vocontrato::$nmAtrGestorContrato?>" name="<?=vocontrato::$nmAtrGestorContrato?>"  value="<?php echo($nmGestor);?>"  class="camponaoobrigatorio" size="50" ></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="50" <?=$requiredArquivo?>></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrDocContratadaContrato?>" name="<?=vocontrato::$nmAtrDocContratadaContrato?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
			<TR>
				<TH class="campoformulario" nowrap>Objeto:</TH>
				<TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrObjetoContrato?>" name="<?=vocontrato::$nmAtrObjetoContrato?>"  value="<?php echo($dsObjeto);?>"  class="camponaoobrigatorio" size="50" ></TD>
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
               <TH class="campoformulario" nowrap>Vigente no Intervalo:</TH>
               <TD class="campoformulario" colspan="3">
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
               <TH class="campoformulario" nowrap>Vigente na Data:</TH>
               <TD class="campoformulario" colspan="3">
                        	<INPUT type="text" 
                        	       id="dtVigencia" 
                        	       name="dtVigencia" 
                        			value="<?php echo($dtVigencia);?>" 
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" >
                </TD>
		</TR>										
		 <TR>
               <TH class="campoformulario" nowrap>Data Inclus�o:</TH>
               <TD class="campoformulario" colspan="3">
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
				<?php
				include_once(caminho_util."dominioQtdObjetosPagina.php");
				$objetosPorPagina = new dominioQtdObjetosPagina();
				$comboQtdRegistros  = new select($objetosPorPagina->colecao);
				$comboOrdem = new select(getOrdemAtributos());
				$comboOrdenacao = new select(getAtributosOrdenacaoContrato());
				//$cdOrdenacao = @$_POST["cdOrdenacao"];
				//$cdAtrOrdenacao = @$_POST["cdAtrOrdenacao"];                      
                $cdOrdenacao = $filtro->cdOrdenacao;
                $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
                
                $radioHistorico  = new radiobutton($arraySimNao);
				?>
  		    <TR>
               <TH class="campoformulario" width="1%">Ordena��o:</TH>
			   <TD class="campoformulario" colspan="3"> <?php echo $comboOrdenacao->getHtmlOpcao("cdAtrOrdenacao","cdAtrOrdenacao", $cdAtrOrdenacao, false);?>
																Ordem:
														<?php echo $comboOrdem->getHtmlOpcao("cdOrdenacao","cdOrdenacao", $cdOrdenacao, false);?>
														Num.Registros por p�gina:<?php echo $comboQtdRegistros->getHtmlOpcao("qtdRegistrosPorPag","qtdRegistrosPorPag", $qtdRegistrosPorPag, false);?>&nbsp;
                                                        Hist�rico: <?php echo $radioHistorico->getHtmlRadio("cdHistorico","cdHistorico", $cdHistorico, false, false);?>
                                                        &nbsp;<button id="localizar" class="botaoconsulta" type="submit">Consultar</button></TD>
			</TR>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<?php 

if($filtro->cdConsultarArquivo == "S"){
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
