<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");
include_once(caminho_vos . "voDemanda.php");
include_once(caminho_filtros . "filtroManterDemanda.php");

try{
//inicia os parametros
inicio();

$titulo = "CONSULTAR " . voDemanda::getTituloJSP();
setCabecalho($titulo);

$vo = new voDemanda();
$filtro  = new filtroManterDemanda();
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
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
	location.href="encaminhar.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="manterDemanda.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

function encaminhar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite encaminhamento.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;	
	location.href="encaminhar.php?funcao=<?=dbDemandaTramitacao::$NM_FUNCAO_ENCAMINHAR?>&chave=" + chave;
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
	        	$comboTipo = new select(dominioTipoDemanda::getColecao());
	        	$comboSituacao = new select(dominioSituacaoDemanda::getColecao());
	        	$comboSetor = new select(dominioSetor::getColecao());
	        	$comboPrioridade = new select(dominioPrioridadeDemanda::getColecao());
	            $selectExercicio = new selectExercicio();
			  ?>			            
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Demanda:</TH>
	            <TD class="campoformulario" nowrap width="1%">
	            <?php echo "Ano: " . $selectExercicio->getHtmlCombo(voDemanda::$nmAtrAno,voDemanda::$nmAtrAno, $filtro->vodemanda->ano, true, "camponaoobrigatorio", false, "");?>
			  Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDemanda::$nmAtrCd?>" name="<?=voDemanda::$nmAtrCd?>"  value="<?php echo(complementarCharAEsquerda($filtro->vodemanda->cd, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  <?php echo "Tipo: " . $comboTipo->getHtmlCombo(voDemanda::$nmAtrTipo, voDemanda::$nmAtrTipo, $filtro->vodemanda->tipo, true, "camponaoobrigatorio", false, "");?>
	            <TH class="campoformulario" nowrap width="1%">Intervalo.Demanda:</TH>
	            <TD class="campoformulario" >
				  Inicial: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$NmAtrCdDemandaInicial?>" name="<?=filtroManterDemanda::$NmAtrCdDemandaInicial?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemandaInicial, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
				  Final: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=filtroManterDemanda::$NmAtrCdDemandaFinal?>" name="<?=filtroManterDemanda::$NmAtrCdDemandaFinal?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdDemandaFinal, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
			  </TD>			  
			</TR>			            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Setor.Resp.:</TH>
                <TD class="campoformulario" width="1%" ><?php echo $comboSetor->getHtmlCombo(voDemanda::$nmAtrCdSetor,voDemanda::$nmAtrCdSetor, $filtro->vodemanda->cdSetor, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Setor.Atual.:</TH>
                <TD class="campoformulario" ><?php echo $comboSetor->getHtmlCombo(voDemandaTramitacao::$nmAtrCdSetorDestino,voDemandaTramitacao::$nmAtrCdSetorDestino, $filtro->vodemanda->cdSetorDestino, true, "camponaoobrigatorio", false, "");?></TD>
            </TR>            
            <TR>
                <TH class="campoformulario" nowrap width="1%">Situação:</TH>
                <TD class="campoformulario" width="1%"><?php echo $comboSituacao->getHtmlCombo(voDemanda::$nmAtrSituacao,voDemanda::$nmAtrSituacao, $filtro->vodemanda->situacao, true, "camponaoobrigatorio", false, "");?></TD>
                <TH class="campoformulario" nowrap width="1%">Prioridade:</TH>
                <TD class="campoformulario" ><?php echo $comboPrioridade->getHtmlCombo(voDemanda::$nmAtrPrioridade,voDemanda::$nmAtrPrioridade, $filtro->vodemanda->prioridade, true, "camponaoobrigatorio", false, "");?></TD>                                
            </TR>
	        <TR>
	            <TH class="campoformulario" nowrap width="1%">Título:</TH>
	            <TD class="campoformulario" nowrap width="1%">				
	            <INPUT type="text" id="<?=voDemanda::$nmAtrTexto?>" name="<?=voDemanda::$nmAtrTexto?>" value="<?=$filtro->vodemanda->texto?>"  class="camponaoobrigatorio" size="50">
	            </TD>
	            <TH class="campoformulario" nowrap width="1%">PRT:</TH>
	            <TD class="campoformulario">				
	            <INPUT type="text" onkeyup="formatarCampoPRT(this, event);" id="<?=voDemandaTramitacao::$nmAtrProtocolo?>" name="<?=voDemandaTramitacao::$nmAtrProtocolo?>" value="<?php echo($filtro->vodemanda->prt);?>" class="camponaoobrigatorio" size="30">	            	                        	                        
	            </TD>	            	                        	                        
	        </TR>            
			<TR>
				<?php
				require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");
				//$combo = new select(dominioAutorizacao::getColecao());				
				$nmCheckAutorizacaoArray = vocontrato::$nmAtrCdAutorizacaoContrato . "[]";
				$colecaoAutorizacao = $filtro->vocontrato->cdAutorizacao;
								
				require_once (caminho_util . "/selectOR_AND.php");
				$comboOuE = new selectOR_AND();
				?>
	            <TH class="campoformulario" nowrap>Autorização:</TH>
	            <TD class="campoformulario" width="1%">
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_SAD?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_SAD?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_SAD, $colecaoAutorizacao)?> >SAD
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_PGE?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_PGE?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_PGE, $colecaoAutorizacao)?>>PGE
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_GOV?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_GOV?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_GOV, $colecaoAutorizacao)?>>GOV
	            <INPUT type="checkbox" id="<?=vocontrato::$nmAtrCdAutorizacaoContrato.dominioAutorizacao::$CD_AUTORIZ_NENHUM?>" name="<?=$nmCheckAutorizacaoArray?>" value="<?=dominioAutorizacao::$CD_AUTORIZ_NENHUM?>>" <?=dominioAutorizacao::checkedTemAutorizacao(dominioAutorizacao::$CD_AUTORIZ_NENHUM, $colecaoAutorizacao)?>>Nenhum
	            <?php echo $comboOuE->getHtmlSelect(filtroManterDemanda::$NmAtrInOR_AND,filtroManterDemanda::$NmAtrInOR_AND, $filtro->InOR_AND, false, "camponaoobrigatorio", false);?>
	            <TH class="campoformulario" nowrap width="1%">Data.Últ.Movimentação:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=voDemanda::$nmAtrDtReferencia?>" 
	            	       name="<?=voDemanda::$nmAtrDtReferencia?>" 
	            			value="<?php echo($filtro->dtUltMovimentacao);?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">
				</TD>	            	            
	        </TR>
	        <?php	        
	        require_once (caminho_funcoes . vocontrato::getNmTabela() . "/biblioteca_htmlContrato.php");
	        $arrayCssClass = array("camponaoobrigatorio","camponaoobrigatorio", "camponaoobrigatorio");
	        ?>        
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
	            <TD class="campoformulario" ><?php getContratoEntradaDeDados($filtro->vocontrato->tipo, $filtro->vocontrato->cdContrato, $filtro->vocontrato->anoContrato, $arrayCssClass, null, null);?></TD>
	            <TH class="campoformulario" >Valor Global:</TH>
	            <TD class="campoformulario" ><INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalInicial?>"  value="<?php echo($filtro->vlGlobalInicial);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" >
	            							a <INPUT type="text" id="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>" name="<?=filtroManterDemanda::$NmAtrVlGlobalFinal?>"  value="<?php echo($filtro->vlGlobalFinal);?>"
	            							onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" ></TD>        	            
			</TR>
			<TR>
                <TH class="campoformulario" nowrap>Nome Contratada:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nmContratada);?>"  class="camponaoobrigatorio" size="50"></TD>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF Contratada:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDoc?>" name="<?=vopessoa::$nmAtrDoc?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($filtro->docContratada);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
            </TR>
            <?php            
            $comboTpDoc = new select(dominioTpDocumento::getColecao());
            $comboSetor = new select(dominioSetor::getColecao());

            $voUsuario = new voUsuarioInfo();
            $filtroUsu = new filtroManterUsuario(false);
            $colecaoUsu = $voUsuario->dbprocesso->consultarTelaConsulta($voUsuario, $filtroUsu);
            
            $comboUsuTramitacao = new select($colecaoUsu, voUsuarioInfo::$nmAtrID, voUsuarioInfo::$nmAtrName);
            ?>	                    
            <TR>
	            <TH class="campoformulario" nowrap width="1%">Usuário:</TH>
				<TD class="campoformulario" width="1%"><?php echo $comboUsuTramitacao->getHtmlSelect(filtroManterDemanda::$NmAtrCdUsuarioTramitacao,filtroManterDemanda::$NmAtrCdUsuarioTramitacao, $filtro->cdUsuarioTramitacao, true, "camponaoobrigatorio", true);?>
				<TH class="campoformulario" nowrap width="1%">Doc.Anexo:</TH>
				<TD class="campoformulario" >
				Tipo: <?php echo $comboTpDoc->getHtmlSelect(voDocumento::$nmAtrTp,voDocumento::$nmAtrTp, $filtro->tpDocumento, true, "camponaoobrigatorio", true);?>
				Setor: <?php echo $comboSetor->getHtmlCombo(voDocumento::$nmAtrCdSetor,voDocumento::$nmAtrCdSetor, $filtro->cdSetorDocumento, true, "camponaoobrigatorio", true, "");?> 
				Número: <INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)" id="<?=voDocumento::$nmAtrSq?>" name="<?=voDocumento::$nmAtrSq?>"  value="<?php echo(complementarCharAEsquerda($filtro->sqDocumento, "0", TAMANHO_CODIGOS));?>"  class="camponaoobrigatorio" size="6" maxlength="5">
								
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
                    <TH class="headertabeladados" width="1%">Núm.</TH>
                    <TH class="headertabeladados" width="1%">Origem</TH>
                    <TH class="headertabeladados" width="1%">Atual</TH>
                    <TH class="headertabeladados" width="1%">Tipo</TH>
                    <TH class="headertabeladados" width="40%">Contrato</TH>
                    <TH class="headertabeladados"width="50%" nowrap >Título</TH>
                    <TH class="headertabeladados" width="1%">Situação</TH>                    
                    <TH class="headertabeladados" width="1%">Prior.</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Usuário</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Dt.Abertura</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Últ.Movim.</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                //require_once ("dominioSituacaoPA.php");
                $dominioSituacao = new dominioSituacaoDemanda();
                $dominioSetor = new dominioSetor();
                $dominioTipo = new dominioTipoDemanda();
                $dominioPrioridade = new dominioPrioridadeDemanda();
                                
                $colspan=13;
                if($isHistorico){
                	$colspan++;
                }
                
                $dominioTipoContrato = new dominioTipoContrato();
                
                for ($i=0;$i<$tamanho;$i++) {
                		$contrato = "";
                        $voAtual = new voDemanda();
                        $voAtual->getDadosBanco($colecao[$i]);     
                                                
                        //$especie = getDsEspecie($voAtual);
                        $cdSituacao = $voAtual->situacao;
                        $situacao = $dominioSituacao->getDescricao($cdSituacao);
                        $classColunaSituacao = "tabeladadosdestacadoazulclaro";                        
                        if($cdSituacao == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_ABERTA){
                        	$classColunaSituacao = "tabeladadosdestacado";
                        }
                        
                        $setor = $dominioSetor->getDescricao($voAtual->cdSetor);
                        
                        $setorDestinoAtual = $colecao[$i][voDemandaTramitacao::$nmAtrCdSetorDestino];
                        $setorDestinoAtual = $dominioSetor->getDescricao($setorDestinoAtual);
                        
                        $tipo = $dominioTipo->getDescricao($voAtual->tipo);
                        $empresa = $colecao[$i][vopessoa::$nmAtrNome];
                        
                        $voDemandaContrato = new voDemandaContrato();
                        $voDemandaContrato->getDadosBanco($colecao[$i]);
                        
                        $qtContratos = $colecao[$i][filtroManterDemanda::$NmColQtdContratos];                        
                        if($voDemandaContrato->voContrato->cdContrato != null){
                        	
                        	if($qtContratos > 1){
                        		$contrato = "VÁRIOS";
                        	}else{                        	
	                        	$contrato = formatarCodigoAnoComplemento($voDemandaContrato->voContrato->cdContrato,
	                        			$voDemandaContrato->voContrato->anoContrato,
	                        			$dominioTipoContrato->getDescricao($voDemandaContrato->voContrato->tipo));
	                        	
	                        	if($empresa != null){
	                        		$contrato .= ": ".$empresa;
	                        	}
                        	}                        	 
                        	//$tipo = $tipo . ":". $contrato;
                        }
                        $prioridade = $dominioPrioridade->getDescricao($voAtual->prioridade);
                        
                        $nmUsuario = $voAtual->nmUsuarioInclusao;
                        if($isHistorico){
                        	$nmUsuario = $voAtual->nmUsuarioOperacao;
                        }
                        
                        $dataUltimaMovimentacao = $colecao[$i][filtroManterDemanda::$NmColDhUltimaMovimentacao];
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
                    <TD class="tabeladadosalinhadodireita"><?php echo $voAtual->ano;?></TD>
                    <TD class="tabeladadosdestacadonegrito"><?php echo complementarCharAEsquerda($voAtual->cd, "0", TAMANHO_CODIGOS)?></TD>
					<TD class="tabeladados" nowrap><?php echo $setor?></TD>
					<TD class="tabeladados" nowrap><?php echo $setorDestinoAtual?></TD>
					<TD class="tabeladados" nowrap><?php echo $tipo?></TD>
					<TD class="tabeladados" ><?php echo $contrato?></TD>
                    <TD class="tabeladados" ><?php echo $voAtual->texto;?></TD>
                    <TD class="<?=$classColunaSituacao;?>" nowrap><?php echo $situacao?></TD>                    
                    <TD class="tabeladados" nowrap><?php echo $prioridade?></TD>
                    <TD class="tabeladados" nowrap><?php echo $nmUsuario;?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($voAtual->dtReferencia);?></TD>
                    <TD class="tabeladados" nowrap><?php echo getData($dataUltimaMovimentacao);?></TD>
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
                            <?php
                            $arrayBotoesARemover = array(constantes::$CD_FUNCAO_EXCLUIR);
                            echo getBotoesRodapeComRestricao($arrayBotoesARemover);
                            //echo getBotoesRodape();
                            ?>
                            <TD class='botaofuncao'>
                            <?php echo getBotaoValidacaoAcesso("bttEncaminhar", "Encaminhar", "botaofuncaop", false, false,true,false,"onClick='javascript:encaminhar();' accesskey='e'");?>
                            </TD>
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
