<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

//try{
//inicia os parametros
inicio();
$chave = @$_GET["chave"];
$titulo = "DETALHAR " . voContratoInfo::getTituloJSPContratosAVencerAno();
setCabecalho($titulo);


$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

<?php 
//getFuncaoJSDetalhar()
?>

function detalharDemandaRendimento(){
	funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta")){
            return;
    }
	chave = document.frm_principal.rdb_consulta.value;
    url = "../demanda_gestao/detalharDemandaRendimento.php?funcao=" + funcao + "&chave=" + chave + "&lupa=S";	
    abrirJanelaAuxiliar(url, true, false, false);
}

</SCRIPT>
<?=setTituloPagina($titulo)?>
</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="detalharContratosAnosAVencer.php?consultar=S">

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
                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
                <TD class="campoformulario" colspan=3>
                <?php                   
                /*$data = @$_POST[filtroManterContrato::$nmAtrDtVigencia];
                if($data == null){
                	$data = getDataHoje();
                }                
                $ano = getAnoData($data);                
                echo getTextoHTMLNegrito($ano);*/

                $ano = @$_POST[vocontrato::$nmAtrAnoContrato];
                if($ano == null){
                	$ano = getAnoHoje();
                }
                
                $selectExercicio = new selectExercicio();
				echo $selectExercicio->getHtmlCombo(vocontrato::$nmAtrAnoContrato,vocontrato::$nmAtrAnoContrato, $ano, true, "camponaoobrigatorio", false, " onChange='document.frm_principal.submit();'");
				?>                
                </TD>
             </TR>
			 <!-- <TR>
                <TH class="campoformulario" nowrap width="1%">Ano:</TH>
                <TD class="campoformulario" colspan=3>
                <INPUT type="text" 
                        	       id="<?=filtroManterContrato::$nmAtrDtVigencia?>" 
                        	       name="<?=filtroManterContrato::$nmAtrDtVigencia?>" 
                        			value="<?php echo($data);?>" 
                        			onkeyup="formatarCampoData(this, event, false);"
                        			onBlur="document.frm_principal.submit();"  
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="10" > * serão retornados os contratos vigentes nesta data, que se encerram nos meses abaixo
                </TD>
            </TR> -->
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
                    <TH class="headertabeladados" width="90%">Mês</TH>
                    <TH class="headertabeladados"width="1%" nowrap >Num.Demandas</TH>                                       
                </TR>
                <?php	
                $colecao = getContratosAVencerAno($ano);
                
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                                
                $colspan=1;
                //laco para calcular o total
                
                $numTotalDemandas = 0;
                
                $voAtual = new voDemanda();
                for ($i=0;$i<$tamanho;$i++) {
                	$registro = $colecao[$i];
                	$voAtual->getDadosBanco($registro);
                	
                	$mes = $registro[vocontrato::$nmAtrDtVigenciaFinalContrato];
                	$mes = dominioMeses::getDescricao($mes);
                	
                	
                	$numDemandas = $registro[filtroManterContrato::$NmColCOUNTFiltroManter];
                	$numTotalDemandas += $numDemandas; 
                ?>
                <TR class="dados">
                    <TD class="tabeladados"><?php echo $mes?></TD>
                    <TD class="tabeladadosalinhadodireita" nowrap>
                    <?php 
                    $str = complementarCharAEsquerda($numDemandas, "0", constantes::$TAMANHO_CODIGOS_SAFI);
                    echo $str;
                    ?>
                    </TD>                    
                </TR>					
                <?php
                	$numTotalRegistros++;
				}				
                ?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total:</TD>
					<TD class="totalizadortabeladadosalinhadodireita"><?=complementarCharAEsquerda($numTotalDemandas, "0", constantes::$TAMANHO_CODIGOS_SAFI)?></TD>
                </TR>
				<TR>
                    <TD class="totalizadortabeladados" colspan=<?=$colspan+1?>>Total de registros(s): <?=$numTotalRegistros?></TD>
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
                            <TD class='botaofuncao'>
                            <?php 
                            echo getBotaoFechar();
                            ?>                                                        
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
/*}catch(Exception $ex){
	tratarExcecaoHTML($ex, $vo);
}*/
?>
