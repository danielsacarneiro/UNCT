<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "voOficio.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_util."dominioSetor.php");
include_once(caminho_filtros . "filtroManterOficio.php");

//inicia os parametros
inicio();

$titulo = "CONSULTAR OFÍCIOS";
setCabecalho($titulo);

$filtro  = new filtroManterOficio();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$vo = new voOficio();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarOficio($vo, $filtro);


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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
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
	exibirMensagem("Operação não permitida!");
	return;
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
	            $selectExercicio = new selectExercicio();
	            $domSetor = new dominioSetor();
	            $comboSetor = new select($domSetor->colecao);
			  ?>			            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Exercício:</TH>
                <TD class="campoformulario" nowrap width="1%"><?php echo $selectExercicio->getHtmlCombo(voOficio::$nmAtrAno,voOficio::$nmAtrAno, $filtro->ano, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario"><?php echo $comboSetor->getHtmlCombo(voOficio::$nmAtrCdSetor,voOficio::$nmAtrCdSetor, $filtro->cdSetor, true, "camponaoobrigatorio", true, "");?></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap>Número:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voOficio::$nmAtrSq?>" name="<?=voOficio::$nmAtrSq?>"  value="<?php if($filtro->sq != null) echo complementarCharAEsquerda($filtro->sq, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" ></TD>
            </TR>            
       <?php
        $comboOrdenacao = new select(voOficio::getAtributosOrdenacao($cdHistorico));
        
        echo getComponenteConsultaFiltro($comboOrdenacao, false, $filtro)?>
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
                    <TH class="headertabeladados" width="1%">Exercício</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Setor</TH>
                    <TH class="headertabeladados"  nowrap >Número</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=6;
                if($isHistorico){
                	$colspan=7;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voOficio();
                        $voAtual->getDadosBanco($colecao[$i]);     
                                                
                        $setor = $colecao[$i][voOficio::$nmAtrCdSetor];
                        $setor = $domSetor->getDescricao($setor);                        			 
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voOficio::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->ano;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $setor;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->sq, "0", TAMANHO_CODIGOS);?></TD>
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
