<?php
include_once("../../config_lib.php");
include(caminho_util."bibliotecaHTML.php");
include_once("dominioTipoContrato.php");
include_once("dominioEspeciesContrato.php");
include(caminho_vos."dbcontrato.php");

//inicia os parametros
inicio();

$voContrato = new voContrato();

$classChaves = "camporeadonly";
$readonly = "readonly";

$voContrato->getVOExplodeChave($chave);
$isHistorico = ($voContrato->sqHist != null && $voContrato->sqHist != "");

	$dbprocesso = new dbcontrato(null);				
	$colecao = $dbprocesso->limpaResultado();
	$colecao = $dbprocesso->consultarContratoPorChave($voContrato, $isHistorico);	
	$voContrato->getDadosBanco($colecao[0]);   

	$nmGestor  = $voContrato->gestor;
	$nmGestorPessoa  = $voContrato->nmGestorPessoa;
	$nmContratada  = $voContrato->contratada;
	$docContratada  = $voContrato->docContratada;	
	$dsObjeto  = $voContrato->objeto;
	$dtVigenciaInicial  = $voContrato->dtVigenciaInicial;
	$dtVigenciaFinal  = $voContrato->dtVigenciaFinal;	
	$vlMensal  = $voContrato->vlMensal;
	$vlGlobal  = $voContrato->vlGlobal;	
	$procLic = $voContrato->procLic;
	$modalidade = $voContrato->modalidade;
	$dtAssinatura = $voContrato->dtAssinatura;
	$dtPublicacao = $voContrato->dtPublicacao;
    $dataPublicacao = $voContrato->dataPublicacao;
	$empenho = $voContrato->empenho;
	$tpAutorizacao = $voContrato->tpAutorizacao;
	$licom = $voContrato->licom;
    $obs = $voContrato->obs;

$funcao = @$_GET["funcao"];

$titulo = "MOVIMENTAÇÕES";
setCabecalho($titulo);

$colecaoMov = $dbprocesso->consultarContratoMovimentacoes($voContrato, $isHistorico);
if (is_array($colecaoMov))
    $tamanho = sizeof($colecaoMov);
else 
    $tamanho = 0;

//pega a ultima data, que seria a mais recente
$dtFinalAConsiderar = $colecaoMov[$tamanho-1][vocontrato::$nmAtrDtVigenciaFinalContrato];

?>
<!DOCTYPE html>
<HTML lang="pt-BR">

<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	window.close();
	//location.href="index.php";	
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmarManterContrato.php">

<INPUT type="hidden" id="id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_CONTEXTO_SESSAO%>" value="<%=idContextoSessao%>"> 
<INPUT type="hidden" id="evento" name="<%=PRManterReferenciaLegal.ID_REQ_EVENTO%>" value=""> 
<INPUT type="hidden" id="nao_utilizar_id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_NAO_UTILIZAR_ID_CONTEXTO_SESSAO%>" value=""> 

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqHist?>" name="<?=vocontrato::$nmAtrSqHist?>" value="<?=$voContrato->sqHist?>">
<INPUT type="hidden" id="<?=vocontrato::$nmAtrSqContrato?>" name="<?=vocontrato::$nmAtrSqContrato?>" value="<?=$voContrato->sq?>">
 
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
        $dominioTipoContrato = new dominioTipoContrato();
        ?>
        <TR>
            <TH class="campoformulario" nowrap width="1%">Contrato:</TH>
            <TD class="campoformulario" colspan=3>
                    <INPUT type="hidden" id="<?=vocontrato::$nmAtrAnoContrato?>" name="<?=vocontrato::$nmAtrAnoContrato?>"  value="<?php echo($voContrato->anoContrato);?>">
                    <INPUT type="hidden" id="<?=vocontrato::$nmAtrCdContrato?>" name="<?=vocontrato::$nmAtrCdContrato?>"  value="<?php echo($voContrato->cdContrato);?>">
                    <INPUT type="text" value="<?php echo(complementarCharAEsquerda($voContrato->cdContrato, "0", 3)."/".$voContrato->anoContrato);?>"  class="<?=$classChaves?>" size="10" <?=$readonly?>>
                    <INPUT type="text" id="<?=vocontrato::$nmAtrTipoContrato?>" name="<?=vocontrato::$nmAtrTipoContrato?>"  value="<?php echo($dominioTipoContrato->getDescricao($voContrato->tipo));?>"  class="camporeadonly" size="7" maxlength="5" <?=$readonly?>>
            </TD>			
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Gestor:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="nmGestor" name="nmGestor"  value="<?php echo($nmGestor);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Responsavel:</TH>
            <TD class="campoformulario" colspan="3"><INPUT type="text" id="nmGestor" name="nmGestor"  value="<?php echo($nmGestorPessoa);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Nome Contratada:</TH>
            <TD class="campoformulario" width="1%"><INPUT type="text" id="nmContratada" name="nmContratada"  value="<?php echo($nmContratada);?>"  class="camporeadonly" size="50" <?=$readonly?>></TD>
            <TH class="campoformulario" width="1%" nowrap>CNPJ/CNPF Contratada:</TH>
            <TD class="campoformulario" ><INPUT type="text" id="docContratada" name="docContratada"  value="<?php echo(documentoPessoa::getNumeroDocFormatado($docContratada));?>"  onkeyup="formatarCampoCNPFouCNPJ(this, event);" class="camporeadonly" size="20" maxlength="20" <?=$readonly?>></TD>
        </TR>		
		<TR>
            <TH class="campoformulario" nowrap>Data Proposta:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtProposta?>" 
            	       name="<?=vocontrato::$nmAtrDtProposta?>" 
            			value="<?php echo($voContrato->dtProposta);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
			</TD>
        </TR>
		<TR>
            <TH class="campoformulario" nowrap>Período de Movimentação:</TH>
            <TD class="campoformulario" colspan="3">
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaInicialContrato?>" 
            			value="<?php echo($dtVigenciaInicial);?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>> a
            	<INPUT type="text" 
            	       id="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            	       name="<?=vocontrato::$nmAtrDtVigenciaFinalContrato?>" 
            			value="<?php echo(getData($dtFinalAConsiderar));?>"
            			onkeyup="formatarCampoData(this, event, false);" 
            			class="camporeadonly" 
            			size="10" 
            			maxlength="10" <?=$readonly?>>
			</TD>
        </TR>
        </TBODY>
    </TABLE>
    </DIV>
    </TD>
    </TR>
    
    <TR>
       <TD class="textoseparadorgrupocampos"> Movimentações</TD>
    </TR>
    
    <TR>
           <TD class='conteinertabeladados'>
            <DIV id='div_tabeladados' class='tabeladados'>
             <TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'>						
                 <TBODY>
                    <TR>
                      <!--<TH class='headertabeladados' width='1%'>&nbsp;&nbsp;X</TH>-->
                        <TH class='headertabeladados' width='1%'>Espécie</TH>
                        <TH class='headertabeladados' width='50%'>Objeto</TH>
                        <TH class='headertabeladados' width='1%' nowrap>Dt.Início</TH>
                        <TH class='headertabeladados' width='1%' nowrap>Dt.Fim</TH>
                        <TH class='headertabeladados' width='1%' nowrap>Dt.Assinatura</TH>
                        <TH class='headertabeladados' width='1%' nowrap>Vl.Mensal</TH>
                        <TH class='headertabeladados' width='1%' nowrap>Vl.Global</TH>
                    </TR>
                    <?php
                    $dominioTipoContrato = new dominioTipoContrato();                                
                                                    
                    for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vocontrato();
                        $voAtual->getDadosBanco($colecaoMov[$i]);
                        $especie = getDsEspecie($voAtual);
                        
                            $sq = $colecaoMov[$i][vocontrato::$nmAtrSqContrato];                    
                            $chave = $sq
                                    . "*"
                                    . $colecaoMov[$i][vocontrato::$nmAtrAnoContrato]
                                    . "*"
                                    . $colecaoMov[$i][vocontrato::$nmAtrCdContrato]
                                    ;
                    
                            $dtAssinatura = $colecaoMov[$i][vocontrato::$nmAtrDtAssinaturaContrato];
                                                
                            $classColuna = "tabeladados";
                            
                            $tipo = $dominioTipoContrato->getDescricao($colecaoMov[$i]["ct_tipo"]);                            
                            
                            //$especie = $especiesContrato->getDescricao($colecaoMov[$i]["ct_cd_especie"]);                        
                    ?>
                    <TR class='dados'>
                        <!--<TD class='tabeladados' <?=$msgAlertaSq?>>
                        <INPUT type='radio' id='rdb_consulta' name='rdb_consulta' value='<?php echo($chave);?>'>
                        </TD>-->
                        <TD class='tabeladados'><?php echo $especie?></TD>
                        <TD class='tabeladados'><?php echo $colecaoMov[$i]["ct_objeto"]?></TD>
                        <TD class='tabeladados' nowrap><?php echo getData($colecaoMov[$i][vocontrato::$nmAtrDtVigenciaInicialContrato])?></TD>
                        <TD class='tabeladados' nowrap><?php echo getData($colecaoMov[$i][vocontrato::$nmAtrDtVigenciaFinalContrato])?></TD>											
                        <TD class='tabeladados'><?php echo getData($dtAssinatura)?></TD>
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($colecaoMov[$i]["ct_valor_mensal"])?></TD>                    
                        <TD class='tabeladadosalinhadodireita' ><?php echo getMoeda($colecaoMov[$i]["ct_valor_global"])?></TD>
                    </TR>					
                    <?php
                    }		
                    ?>    
                    </TD>
    </TR>  
</TBODY>
</TABLE>
</TD>
</TR>
</TBODY>

        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="button" accesskey="f">Fechar</button></TD>
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
