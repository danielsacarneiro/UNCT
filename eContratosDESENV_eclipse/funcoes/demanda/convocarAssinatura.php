<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util."selectExercicio.php");

//inicia os parametros
inicio();

$vo = new voDemanda();
$vo->getVOExplodeChave();
//var_dump($vo->varAtributos);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$dbprocesso = new dbDemanda();
$colecao = $dbprocesso->consultarPorChaveTelaJoinContrato ( $vo, $isHistorico, false, true );
$registro = $colecao[0];

putObjetoSessao($vo->getNmTabela(), $vo);

$funcao = @$_GET["funcao"];

$inConsultaHTML = getInConsultarHTMLString();

$titulo = "Email convocação Assinatura";
setCabecalho($titulo);
	
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_demanda.js"></SCRIPT>

</HEAD>
<?=setTituloPagina($titulo)?>
<BODY CLASS="paginadados">
	<FORM name="frm_principal" method="post">
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
    			<TR>
          			<TD class="conteinerconteudodados">
					<TABLE id="table_conteudodados" class="conteudodados" cellpadding="0" cellspacing="0">
					<TR>
				        <?php				        	
				        	$mensagem .= getEmailConvocacaoAssinatura($registro);				        	
				        	echo $mensagem;				        	 
						  ?>			            
		            </TR>							

					</TABLE>
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