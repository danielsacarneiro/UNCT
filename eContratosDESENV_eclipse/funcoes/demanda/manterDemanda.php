<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos."voDemandaTramitacao.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voDemanda();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;
$vo = $dbprocesso->consultarPorChaveTelaColecaoContrato($vo, $isHistorico);
putObjetoSessao($vo->getNmTabela(), $vo);

$nmFuncao = "ALTERAR ";
$titulo = $vo->getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
	$complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
	$isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);
?>

<!DOCTYPE html>
<HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarAlteracaoDemanda.php" onSubmit="return confirmar();">

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
            <?php if($isHistorico){?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sq.Hist:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->sqHist, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                <INPUT type="hidden" id="<?=voContratoTramitacao::$nmAtrSqHist?>" name="<?=voContratoTramitacao::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }                     
            
	        $comboTipo = new select(dominioTipoDemanda::getColecao());
	        $comboSetor = new select(dominioSetor::getColecao());
	        $comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        $comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	        $selectExercicio = new selectExercicio();	         
	        	        	        
	        $complementoHTML = "";	        
	        ?>	        	        
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" colspan=3>	            
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo("","", $vo->ano, true, "camporeadonly", false, " disabled ");?>	            	            
	            Número: <INPUT type="text" value="<?=complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS);?>"  class="camporeadonly" size="6" readonly>
	            <?php echo "Tipo: " . $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo, $vo->tipo, true, "campoobrigatorio", false, " required ");?>
	            
	            <INPUT type="hidden" id="<?=voDemanda::$nmAtrAno?>" name="<?=voDemanda::$nmAtrAno?>" value="<?=$vo->ano?>">
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>" value="<?=$vo->cd?>">	            			  
				<!-- <INPUT type="hidden" id="<?=voDemanda::$nmAtrTipo?>" name="<?=voDemanda::$nmAtrTipo?>" value="<?=$vo->tipo?>"> -->
				<INPUT type="hidden" id="<?=voDemanda::$nmAtrCdSetor?>" name="<?=voDemanda::$nmAtrCdSetor?>" value="<?=$vo->cdSetor?>">
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Setor Responsável:</TH>
	            <TD class="campoformulario" width="1%">
	            <?php echo $comboSetor->getHtmlCombo("","", $vo->cdSetor, true, "camporeadonly", false, " disabled ");?>
				</TD>
	            <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
	            <TD class="campoformulario" >
	            <?php 
	            //o setor destino da ultima tramitacao sera o origem da nova
	            echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $vo->prioridade, true, "campoobrigatorio", false, " required ");?>
				</TD>				
	        </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" colspan=3>				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value="<?=$vo->texto?>"  class="campoobrigatorio" size="80" required>	            	                        	                        
	        </TR>	        	        
	        <?php
 	        require_once (caminho_funcoes."contrato/biblioteca_htmlContrato.php");
 	        getColecaoContratoDet($vo->colecaoContrato);
	        ?>            
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Situação:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php 
	            echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao, $vo->situacao, true, "campoobrigatorio", false, " required ");?>
				</TD>
	        </TR>
				<?php 
				include_once 'biblioteca_htmlDemanda.php';
				$colecaoTramitacao = $vo->dbprocesso->consultarDemandaTramitacao($vo);
				mostrarGridDemanda($colecaoTramitacao, true);
				?>
       	    
	        <?php 
	            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";	        	
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