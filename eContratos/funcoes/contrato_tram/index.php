<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos . "voContratoTramitacao.php");
include_once(caminho_filtros . "filtroManterContratoTramitacao.php");

//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voContratoTramitacao::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterContratoTramitacao();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$vo = new voContratoTramitacao();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultar($vo, $filtro);

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function limparFormulario() {	

	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
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
	lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
}

function excluir() {
    detalhar(true);
}

function incluir() {
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">

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
	        	require_once ("../contrato/dominioTipoContrato.php");
	        	$combo = new select(dominioTipoContrato::getColecao());
	            $selectExercicio = new selectExercicio();
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan=3>
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voContratoTramitacao::$nmAtrAnoContrato,voContratoTramitacao::$nmAtrAnoContrato, $filtro->anoContrato, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voContratoTramitacao::$nmAtrCdContrato?>" name="<?=voContratoTramitacao::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdContrato, "0", 3));?>"  class="camponaoobrigatorio" size="4" maxlength="3">
			  <?php echo $combo->getHtmlCombo(voContratoTramitacao::$nmAtrTipoContrato,voContratoTramitacao::$nmAtrTipoContrato, $filtro->tipoContrato, true, "camponaoobrigatorio", false, "");	
			  ?>
	        </TR>			            
            <TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=$filtro::$nmAtrNmContratada?>" name="<?=$filtro::$nmAtrNmContratada?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50" <?=$requiredArquivo?>></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=$filtro::$nmAtrDocContratada?>" name="<?=$filtro::$nmAtrDocContratada?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
            
       <?php
        /*$comboOrdenacao = new select(voPA::getAtributosOrdenacao($cdHistorico));
        $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
        echo getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, true, $cdHistorico)*/
       echo getComponenteConsultaFiltro($vo->temTabHistorico, $filtro);
        ?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>

<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                  <?php 
                  if($isHistorico){					                  	
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>
                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
                    <TH class="headertabeladados" width="1%">Num.</TH>
                    <TH class="headertabeladados" width="1%">Tipo</TH>                                        
                    <TH class="headertabeladados"width="1%" nowrap >Documento</TH>
                    <TH class="headertabeladados"width="90%" nowrap >Texto</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Usuário</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Dt.Referência</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
                include_once (caminho_funcoes."contrato/dominioTipoContrato.php");
                include_once (caminho_funcoes."documento/biblioteca_htmlDocumento.php");
                
                //require_once ("dominioSituacaoPA.php");
                $dominioTipoContrato = new dominioTipoContrato();
                                
                $colspan=8;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voContratoTramitacao();
                        $voAtual->getDadosBanco($colecao[$i]);     
                        
                        /*$contrato = formatarCodigoAnoComplemento($colecao[$i][$voAtual::$nmAtrCdContrato],
                        						$colecao[$i][$voAtual::$nmAtrAnoContrato], 
                        						$dominioTipoContrato->getDescricao($colecao[$i][$voAtual::$nmAtrTipoContrato]));*/
                        
                        $doc = formatarCodigoDocumento($voAtual->voDoc->sq, $voAtual->voDoc->cdSetor, $voAtual->voDoc->ano, $voAtual->voDoc->tp);
                        //$especie = getDsEspecie($voAtual);
                        $tipo = $dominioTipoContrato->getDescricao($voAtual->tipoContrato);
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][$voAtual::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladadosalinhadodireita"><?php echo $voAtual->anoContrato;?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo complementarCharAEsquerda($voAtual->cdContrato, "0", TAMANHO_CODIGOS_SAFI)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipo?></TD>					
                    <TD class="tabeladados" nowrap><?php echo $doc;?></TD>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->obs;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $voAtual->nmUsuarioUltAlteracao;?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtReferencia);?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>				
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
