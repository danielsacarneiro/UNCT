<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "voDocumento.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_util."dominioSetor.php");
include_once(caminho_filtros . "filtroManterDocumento.php");

//inicia os parametros
inicio();
$vo = new voDocumento();

$titulo = "CONSULTAR " . $vo::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterDocumento();
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarDocumento($vo, $filtro);


$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

$isUsuarioAdmin = "false";
if(isUsuarioAdmin())
	$isUsuarioAdmin = "true";

?>

<!DOCTYPE html>
<HTML>
<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
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
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>

    chave = document.frm_principal.rdb_consulta.value;	
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;
}

function abrir() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
            
	chave = document.frm_principal.rdb_consulta.value;
	nmCampo = "<?=voDocumento::$nmAtrLink?>"+chave;	
	linkDoc = document.getElementById(nmCampo).value;

	if(<?=$isUsuarioAdmin?>){
		abrirArquivoLink(linkDoc);
	}else{
		abrirArquivoLinkCliente(linkDoc);
	}
}

//Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "<?=CAMPO_SEPARADOR?>");

		ano = array[0];
		cdSetor= array[1];
		tp = array[2];
		sq = array[3];
		sq = completarNumeroComZerosEsquerda(sq, TAMANHO_CODIGOS_DOCUMENTOS);
		
		window.opener.transferirDadosDocumento(sq, cdSetor, ano, tp);
		window.close();
	}
}

</SCRIPT>
<?=setTituloPagina($vo->getTituloJSP())?>
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
	            $domTp = new dominioTpDocumento();
	            $comboTp= new select($domTp->colecao);
			  ?>			            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
                <TD class="campoformulario" nowrap width="1%"><?php echo $selectExercicio->getHtmlCombo(voDocumento::$nmAtrAno,voDocumento::$nmAtrAno, $filtro->ano, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Setor:</TH>
                <TD class="campoformulario"><?php echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $filtro->cdSetor, true, "camponaoobrigatorio", true, "");?></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width="1%">Tp.Documento:</TH>
                <TD class="campoformulario"><?php echo $comboTp->getHtmlCombo(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tp, true, "camponaoobrigatorio", true, "");?></TD>			
                <TH class="campoformulario" nowrap>Número:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php if($filtro->sq != null) echo complementarCharAEsquerda($filtro->sq, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" ></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width="1%">Assunto:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voDocumento::$nmAtrLink?>" name="<?=voDocumento::$nmAtrLink?>"  value="<?php echo($filtro->link);?>"  class="camponaoobrigatorio" size="50" ></TD>			
            </TR>                                    
       <?php
        //$comboOrdenacao = new select(voDocumento::getAtributosOrdenacao($cdHistorico));        
        //echo getComponenteConsultaFiltro($comboOrdenacao, false, $filtro);
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
                    <TH class="headertabeladados" width="1%">Ano</TH>
                    <TH class="headertabeladados" width="1%" nowrap >Setor</TH>
                    <TH class="headertabeladados" width="1%" nowrap >Tp.Documento</TH>
                    <TH class="headertabeladados" width="1%" nowrap >Número</TH>
                    <TH class="headertabeladados" width="90%" nowrap >Link</TH>
                    <TH class="headertabeladados"  nowrap >Data</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=7;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voDocumento();
                        $voAtual->getDadosBanco($colecao[$i]);     
                                                
                        $setor = $colecao[$i][voDocumento::$nmAtrCdSetor];
                        $setor = $domSetor->getDescricao($setor);
                        
                        $tp= $colecao[$i][voDocumento::$nmAtrTp];
                        $tp = $domTp->getDescricao($tp);
                        
                        $chave = $voAtual->getValorChaveHTML();
                        $chave =  voDocumento::$nmAtrLink.$chave;
                        echo "<INPUT type='hidden' id='".$chave."' name='".$chave."'  value='" . $voAtual->getEnderecoTpDocumento() ."'>";                        
                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voDocumento::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->ano;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $setor;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tp;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->sq, "0", TAMANHO_CODIGOS);?></TD>
                    <TD class="tabeladados"><?php echo $voAtual->link;?></TD>
                    <TD class="tabeladados" nowrap><?php echo getDataHora($voAtual->dhUltAlteracao);?></TD>
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
							<TD class='botaofuncao'><?php echo getBotaoValidacaoAcesso("bttabrirpasta", "Abrir", "botaofuncaop", false,true,true,true, "onClick=javascript:abrir(); accesskey='m'");?></TD>
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
