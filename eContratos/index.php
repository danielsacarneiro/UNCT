<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();

$titulo = "MENU de Fun��es UNCT";
setCabecalho($titulo);

cabecalho;

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<?=setTituloPagina(null)?>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>

<SCRIPT language="javascript">
	f = new Tree('Menu de Funcionalidades finalizadas e entregue a SEFAZ-PE (Homologa��o)', true);
	
	f1 = new Tree('Servi�o 01 (Tabelas)');	
    f1.adicionarItem(new Link('Documentos', '<?=caminho_funcoesHTML?>documento', ''));
    f1.adicionarItem(new Link('Gestores', '<?=caminho_funcoesHTML?>gestor', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
	f.adicionarItem(f1);
	
	f3 = new Tree('Servi�o 02 (Contratos)');
	f3.adicionarItem(new Link("Contratos", "<?=caminho_funcoesHTML?>contrato", ""));
	//f3.adicionarItem(new Link("Manuten��o Contrato Tramita��o (NAO USAR)", "<?=caminho_funcoesHTML?>contrato_tram", ""));	
	f.adicionarItem(f3);

	f4 = new Tree('Servi�o 03 (Demanda)');
	f4.adicionarItem(new Link("Demandas", "<?=caminho_funcoesHTML?>demanda", ""));
	<?php if(isUsuarioAdmin()){?>
		f4.adicionarItem(new Link("Tramita��o Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
	<?php }?>
	f.adicionarItem(f4);

	f5 = new Tree('Servi�o 04 (Proc.Admin.)');	
    f5.adicionarItem(new Link('P.A.s de Aplica��o de Penalidade (PAAP)', '<?=caminho_funcoesHTML?>pa', ''));    
	f.adicionarItem(f5);

	/*
	exemplo
    // SERVI�O 4
	f3 = new Tree('Di�ria');
	f3.adicionarItem(new Link("Grupo de Di�rias","/sfi_fin_gfu/PRManterGrupoDiaria", "25040"));
	f3.adicionarItem(new Link("Faixa de Di�ria", "/sfi_fin_gfu/PRManterFaixaDiaria", "25041"));
	f3.adicionarItem(new Link("Tabela de Di�ria", "/sfi_fin_gfu/PRManterTabelaDiaria", "25042"));
	f.adicionarItem(f3);
	*/


</SCRIPT>
</HEAD>

<BODY CLASS="paginadados">
	<FORM name="frm_principal" method="post">
		<INPUT type="hidden" id="id_contexto_sessao" name="id_contexto_sessao" value=""> 
		<INPUT type="hidden" id="evento" name="evento" value=""> 
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
        			<TR>
            			<TD class="conteinerconteudodados">
            			 <DIV id="div_conteudodados" class="conteudodados">
							<TABLE id="table_conteudodados" class="conteudodados" cellpadding="0" cellspacing="0">

                    				<TR>
                        				<TD valign="top" bgcolor="#A5B9D7">
											<SCRIPT>f.escrever(false, 0);</SCRIPT>
                        				</TD>
                    				</TR>
                    				
                     				<!--<TR>
                                    	<TH  class="titulopassoapasso" ><B>Outros Sistemas</B></TH>
                                	</TR>
                    				<TR>
                        				<TD valign="top" bgcolor="#A5B9D7">
											<SCRIPT>fOutros.escrever(false, 0);</SCRIPT>
                        				</TD>
                    				</TR>-->
                				
            				</TABLE>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
		</FORM>
</BODY>
</HTML>