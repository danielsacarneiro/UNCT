<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once("dominioTipoContrato.php");
include_once(caminho_util. "select.php");
include_once(caminho_vos . "dbcontrato.php");
include_once(caminho_filtros . "filtroConsultarContratoPGE.php");

//inicia os parametros
inicio();

$titulo = "RELATÓRIO CONTRATOS";
setCabecalho($titulo);

$voContrato = new voContrato();

$filtro  = new filtroConsultarContratoPGE(false);
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
	
$dbprocesso = new dbcontrato();
$colecao = $dbprocesso->consultarComPaginacao($voContrato, $filtro, $numTotalRegistros, $pagina, $qtdRegistrosPorPag);

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
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
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

function limparFormulario() {	

	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
	}	
	frm_principal.anoContrato.value = <?php echo(anoDefault);?>;
	frm_principal.dtVigenciaInicial.value = "<?php echo dtHoje;?>";
	frm_principal.dtVigenciaFinal.value = "<?php echo dtHoje;?>";	
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
	if(especie != <?=constantes::$CD_ESPECIE_CONTRATO_MATER;?>){
		alert("Operação permitida apenas para contrato Mater.");
		return;
	}

	chave = document.frm_principal.rdb_consulta.value;
    url = "movimentacaoContrato.php?chave=" + chave;
	
    abrirJanelaAuxiliar(url, true, false, false);
    
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">	  
<FORM name="frm_principal" method="post" action="relatorio.php?consultar=S">
 
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
                <TH class="campoformulario" nowrap>Ano Contrato Mater:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>"  value="<?php echo($anoContrato);?>"  class="camponaoobrigatorio" size="6" maxlength="4" ></TD>
                <TD class="campoformularioalinhadodireita" colspan="2">
                    <a href="javascript:limparFormulario();" ><img  title="Limpar" src="<?=caminho_imagens?>borracha.jpg" width="20" height="20"></a>
                </TD>
            </TR>
			<?php
            $dominioTipoContrato = new dominioTipoContrato();            
			$tiposContrato = new select($dominioTipoContrato->colecao);
			?>            
            <TR>
                <TH class="campoformulario" nowrap>Número/Tipo:</TH>
                <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo(complementarCharAEsquerda($cdContrato, "0", 3));?>"  class="camponaoobrigatorio" size="6" maxlength="5" >
                                                        <?php echo $tiposContrato->getHtml(vocontrato::$nmAtrTipoContrato,voContrato::$nmAtrTipoContrato, $tipo);?>
                </TD>
            </TR>					
            <TR>
			<?php
			include_once("dominioEspeciesContrato.php");
			$especiesContrato = new dominioEspeciesContrato();
			$combo = new select($especiesContrato->colecao);						
			?>
    			<TH class="campoformulario" nowrap>Espécies:</TH>
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
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vocontrato::$nmAtrContratadaContrato?>" name="<?=vocontrato::$nmAtrContratadaContrato?>"  value="<?php echo($nmContratada);?>"  class="camponaoobrigatorio" size="50" ></TD>
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
               <TH class="campoformulario" nowrap>Data Inclusão:</TH>
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
        $comboOrdenacao = new select(getAtributosOrdenacaoContrato());
        $cdOrdenacao = $filtro->cdOrdenacao;
        $cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
        //$cdHistorico = $filtro->cdHistorico;        
        
        echo getComponenteConsultaPaginacao($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, false, null, false, null)?>		
									
			</TR>
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
                  <!-- <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>-->
                    <TH class="headertabeladados" width="1%" nowrap>Ano</TH>
                    <TH class="headertabeladados" width="1%">Num.</TH>
                    <TH class="headertabeladados" width="1%">Tipo</TH>
                    <TH class="headertabeladados" width="1%">Espécie</TH>
                    <TH class="headertabeladados" width="20%">Contratada</TH>
                    <TH class="headertabeladados" width="1%">CNPJ/CNPF</TH>
                    <TH class="headertabeladados" width="50%">Objeto</TH>						
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Início</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Dt.Fim</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Licitação</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Vl.Mensal</TH>
                    <TH class="headertabeladados" width="1%" nowrap>Vl.Global</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;	
                            
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecao[$i]);
                        $especie = getDsEspecie($voAtual);                    
                                                
                        $sq = $colecao[$i][vocontrato::$nmAtrSqContrato];
                        $msgAlertaSq = "onMouseOver=toolTip('seq:".$sq."') onMouseOut=toolTip()";
                    
                        /*
                        $sqHist = "";
                        if($isHistorico)
                            $sqHist = $colecao[$i][vocontrato::$nmAtrSqHist];

                         $chave = $sq
                                . "*"
                                . $colecao[$i][vocontrato::$nmAtrAnoContrato]
                                . "*"
                                . $colecao[$i][vocontrato::$nmAtrCdContrato]
                                . "*"
                                . $cdHistorico
                                . "*"
                                . $sqHist
                                ;*/                        
                
                        $datainiSQL = $colecao[$i]["ct_dt_vigencia_inicio"];
                        $datafimSQL = $colecao[$i]["ct_dt_vigencia_fim"];																																									
                                                
                        $validaAlerta = false;
                        try{                                                   
                            $qtDiasFimVigencia = getQtdDiasEntreDatas(dtHojeSQL, $datafimSQL);
                        }catch (Exception $e){
                            $validaAlerta = false;
                        }                        
                                            
                        $classColuna = "tabeladados";
                        $mensagemAlerta = "";
                        if($validaAlerta){
                            if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_VERMELHO)
                                $classColuna = "tabeladadosdestacadovermelho";
                            else if($qtDiasFimVigencia <= constantes::$qts_dias_ALERTA_AMARELO)
                                $classColuna = "tabeladadosdestacadoamarelo";
                                
                            $mensagemAlerta = "onMouseOver=toolTip('".$qtDiasFimVigencia."dias') onMouseOut=toolTip()";
                        }                        
                        $tagCelula = "class='$classColuna' " . $mensagemAlerta;
                        
                        $tipo = $dominioTipoContrato->getDescricao($colecao[$i]["ct_tipo"]); 
                                                
                ?>
                <TR class="dados">
                    <!-- <TD class="tabeladados" <?=$msgAlertaSq?>>
					<?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>
                    </TD>-->
                    <TD class="tabeladadosalinhadodireita"><?php echo $colecao[$i]["ct_exercicio"];?></TD>
                    <TD class="tabeladadosalinhadodireita" ><?php echo complementarCharAEsquerda($colecao[$i]["ct_numero"], "0", 3)?></TD>
                    <TD class="tabeladados" nowrap><?php echo $tipo?></TD>
                    <TD class="tabeladados"><?php echo $especie?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i]["ct_contratada"]?></TD>
                    <TD class="tabeladados" nowrap><?php echo $colecao[$i]["ct_doc_contratada"]?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i]["ct_objeto"]?></TD>
                    <TD class="tabeladados"><?php echo getData($datainiSQL)?></TD>
                    <TD <?=$tagCelula?>>                    <?php echo getData($datafimSQL)?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vocontrato::$nmAtrProcessoLicContrato]?></TD>                                        
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_mensal"])?></TD>                    
                    <TD class="tabeladadosalinhadodireita" ><?php echo getMoeda($colecao[$i]["ct_valor_global"])?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s): <?=$i?></TD>
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
                            <TD class="botaofuncao"><button id="bttdetalhar" class="botaofuncaop" type="button" onClick="javascript:detalhar(false);" accesskey="d">Detalhar</button></TD>
                            <TD class="botaofuncao"><?=getBotao("bttMovimentacao", "Movimentações", null, false, "onClick='javascript:movimentacoes();' accesskey='m'")?></TD>
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
