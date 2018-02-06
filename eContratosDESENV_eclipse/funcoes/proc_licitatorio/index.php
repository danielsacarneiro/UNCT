<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voProcLicitatorio();
$titulo = "CONSULTAR " . voProcLicitatorio::getTituloJSP();
setCabecalho($titulo);

$filtro  = new filtroManterProcLicitatorio();
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = "S" == $cdHistorico; 

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsulta($vo, $filtro);

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
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Proc.Licitatório:</TH>
	            <TD class="campoformulario" nowrap width="1%" colspan="3">
	            <?php
	            $selectExercicio = new selectExercicio();
	            echo "Ano: " . $selectExercicio->getHtmlCombo(voProcLicitatorio::$nmAtrAno,voProcLicitatorio::$nmAtrAno, $filtro->anoProc, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voProcLicitatorio::$nmAtrCd?>" name="<?=voProcLicitatorio::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdProc, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			</TR>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" nowrap width="1%" colspan="3">
	            <?php

	            echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->anoDemanda, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemanda, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			</TR>			            
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" colspan="3"><?php getContratoEntradaDeDados($filtro->tipoContrato, $filtro->cdContrato, $filtro->anoContrato, $arrayCssClass, null, null);?></TD>        	            
			</TR>        
			<TR>
                <TH class="campoformulario" nowrap>Contratada:</TH>
                <TD class="campoformulario" width="1%">
                Cód: <INPUT type="text" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>"  value="<?php echo complementarCharAEsquerda($filtro->cdPessoa, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" >
                Nome: <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="30" ></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario"><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
        
            <?php            
            $comboTpDoc = new select(dominioTpDocumento::getColecaoDocsPAAP());
            ?>	                    
            <TR>
	            <TH class="campoformulario" nowrap>Situação:</TH>
				<TD class="campoformulario" width="1%">
				<?php
				$comboSituacaoPL = new select(dominioSituacaoPL::getColecao());
				echo $comboSituacaoPL->getHtmlSelect(voProcLicitatorio::$nmAtrSituacao, voProcLicitatorio::$nmAtrSituacao, $filtro->situacao, true, "camponaoobrigatorio", "");
				?>
				<TH class="campoformulario" nowrap width="1%">Doc.Anexo:</TH>
				<TD class="campoformulario" >
				<?php echo $comboTpDoc->getHtmlSelect(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tpDocumento, true, "camponaoobrigatorio", true);?>								
			</TR>
            <TR>
				<TH class="campoformulario" nowrap>Pregoeiro:</TH>
                <TD class="campoformulario" colspan="3">
				<?php
				include_once(caminho_funcoes."pessoa/biblioteca_htmlPessoa.php");
				echo getComboPessoaPregoeiro(voProcLicitatorio::$nmAtrCdPregoeiro, voProcLicitatorio::$nmAtrCdPregoeiro, $filtro->cdPregoeiro, "camponaoobrigatorio", "");                                        
				?>
            </TR>            
       <?php
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
		                <TH class="headertabeladados" rowspan="2" width="1%">&nbsp;&nbsp;X</TH>
		                  <?php if($isHistorico){?>
		                  	<TH class="headertabeladados" rowspan="2" width="1%">Sq.Hist</TH>
		                  <?php }?>
						<TH class="headertabeladados" colspan="2">
						<center>P.L.</center>
						</TH>
	                    <TH class="headertabeladados" rowspan="2" width="90%">Objeto</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap>Modalidade</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap>Tipo</TH>
	                    <TH class="headertabeladados" rowspan="2" width="1%" nowrap>Pregoeiro</TH>
	                    <TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Dt.Abertura</TH>
	                    <TH class="headertabeladados" rowspan="2"  width="1%" nowrap>Situação</TH>
                    </TR>
                    <TR>
	                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
	                    <TH class="headertabeladados" width="1%">Num.</TH>
                    </TR>                 
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=9;
                if($isHistorico){
                	$colspan++;
                }
                
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new voProcLicitatorio();                        
                        $registroAtual = $colecao[$i];
                        $voAtual->getDadosBanco($registroAtual);
                        
                        $cdSituacaoDemanda = $registroAtual[voDemanda::$nmAtrSituacao];
                        $cdSituacao = $voAtual->situacao;
                        if($cdSituacaoDemanda == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_EM_ANDAMENTO){
                        	$situacao = dominioSituacaoDemanda::getDescricaoStatic($cdSituacaoDemanda);
                        	$classColunaSituacao = "tabeladadosdestacadoverde";
                        }else{
                        	$situacao = dominioSituacaoPL::getDescricaoStatic($cdSituacao);
                        	
                        	$classColunaSituacao = "tabeladadosdestacado";
                        	if($cdSituacao == dominioSituacaoPL::$CD_SITUACAO_PL_CONCLUIDO){
                        				$classColunaSituacao = "tabeladadosdestacadoazulclaro";
                        	}                        	
                        }
                                                
                        $tipo = dominioTipoProcLicitatorio::getDescricaoStatic($voAtual->tipo);
                        $modalidade = dominioModalidadeProcLicitatorio::getDescricaoStatic($voAtual->cdModalidade);
                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][voProcLicitatorio::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados" nowrap><?php echo $voAtual->ano;?></TD>
                    <TD class="tabeladados" nowrap><?php echo complementarCharAEsquerda($voAtual->cd, "0", TAMANHO_CODIGOS_SAFI);?></TD>
                    <TD class="tabeladados"><?php echo $voAtual->objeto;?></TD>
                    <TD class="tabeladados"><?php echo $modalidade;?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipo;?></TD>
					<TD class="tabeladados" nowrap><?php echo $colecao[$i][filtroManterProcLicitatorio::$nmColNomePregoeiro];?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtAbertura);?></TD>
                    <TD class="<?=$classColunaSituacao?>" nowrap><?php echo $situacao;?></TD>
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
