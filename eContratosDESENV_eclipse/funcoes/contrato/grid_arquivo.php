<?php 
include_once(caminho_util."biblioteca_htmlArquivo.php");
?>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>
<SCRIPT language="javascript">

<?php
ini_set('max_execution_time', 120);

$strFiltro = $filtro->contratada;
$temContratoSelecionado = false;
if($filtro->cdContrato != null){
	$strAnoContrato = "";
	if($filtro->anoContrato != null){
		$strAnoContrato = " - " . substr($filtro->anoContrato,2,2);
	}
	
	//prioriza o filtro pelo numero do contrato
	$strFiltro .= constantes::$CD_CAMPO_SEPARADOR . complementarCharAEsquerda($filtro->cdContrato, "0", TAMANHO_CODIGOS_SAFI) . $strAnoContrato;	
	
	$temContratoSelecionado = true;
}

$barra = pasta::$barra;
$MenuPai = new pasta("menu_pai", $strFiltro, 1, null);

$anoArquivo = $filtro->anoArquivo;
if($anoArquivo == null){
	$anoArquivo = anoDefault;
}
$MenuPai->cdControleConsulta = pasta::$IN_FILTRAR_APENAS_FILHO;
$endereco = dominioTpDocumento::$ENDERECO_DRIVE . $barra. "UNCT".$barra;

if($filtro->cdConsultarArquivo == dominioConsultaArquivoContrato::$CD_CONSULTA_ARQUIVO_CONTRATO_ASSINADO){
	//if(!$temContratoSelecionado){
		$MenuPai->cdControleConsulta = pasta::$IN_FILTRAR_APENAS_PAI;
	//}
	$endereco .= "CONTRATOS JÁ ASSINADOS";
}else if($filtro->cdConsultarArquivo == dominioConsultaArquivoContrato::$CD_CONSULTA_ARQUIVO_CONTRATO_MATER){
	$endereco .= "ANO " . $anoArquivo .$barra."CONTRATOS";
}else if($filtro->cdConsultarArquivo == dominioConsultaArquivoContrato::$CD_CONSULTA_ARQUIVO_TERMO_ADITIVO){
	$endereco .= "ANO " . $anoArquivo .$barra."TERMOS ADITIVOS";
}	
	
	
$MenuPai->setDir($endereco);
//$MenuPai->setDir(dominioTpDocumento::$ENDERECO_DRIVE . "\\\UNCT");

criaMenu($MenuPai, true);
geraArvoreMenu($MenuPai);

$numTotalRegistros = $MenuPai->numTotalRegistros;

?>

</SCRIPT>
<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>                  
                    <TH class="headertabeladados" nowrap>Pasta: <?PHP ECHO $endereco;?></TH>                    
                </TR>
                
                <TR class="dados">
                    <TD valign="top" bgcolor="#A5B9D7">
					<SCRIPT><?=$MenuPai->nomeObj?>.escrever(false, 0);</SCRIPT>
					
					<div id="nomeDIV">
	                <?php	                 
	                 //echo getComboGestorResponsavel(new dbgestorpessoa(), vocontrato::$nmAtrCdGestorPessoaContrato, vocontrato::$nmAtrCdGestorPessoaContrato, $voContrato->cdGestor, $voContrato->cdGestorPessoa);                    
	                //echo getComboGestorResponsavel("", "");
	                 ?>
	                </div>                    
					
					</TD>                                                         
                </TR>					

                 <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=3><?=$filtro->paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=3>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>				
            </TBODY>
        </TABLE>
        </DIV>
       </TD>
</TR>
