<?php 
include_once(caminho_util."biblioteca_htmlArquivo.php");


//$colecao = montarColecaoItens("../");
?>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>
<SCRIPT language="javascript">

<?php
ini_set('max_execution_time', 120);

$strFiltro = $filtro->contratada;
$MenuPai = new pasta("menu_pai", $strFiltro, 1, null);
$MenuPai->setDir(dominioTpDocumento::$ENDERECO_DRIVE . "\\\UNCT\\\CONTRATOS J� ASSINADOS");
//$MenuPai->setDir(dominioTpDocumento::$ENDERECO_DRIVE . "\\\UNCT");
$MenuPai->cdControleConsulta = pasta::$IN_FILTRAR_APENAS_PAI;

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
                    <TH class="headertabeladados" nowrap>Pasta</TH>                    
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
