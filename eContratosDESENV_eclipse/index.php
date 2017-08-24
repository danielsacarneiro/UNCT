<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();

$titulo = "MENU de Funções UNCT";
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
	f = new Tree('Menu de Funcionalidades finalizadas e entregue a SEFAZ-PE (Homologação)', true);
	
	f1 = new Tree('Serviço 01 (Tabelas)');	
    f1.adicionarItem(new Link('Documentos', '<?=caminho_funcoesHTML?>documento', ''));
    f1.adicionarItem(new Link('Gestores', '<?=caminho_funcoesHTML?>gestor', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Serviço 02 (Contratos)');
	f2.adicionarItem(new Link("Contratos-Planilha", "<?=caminho_funcoesHTML?>contrato", ""));
	f2.adicionarItem(new Link("Contratos-Informações Adicionais", "<?=caminho_funcoesHTML?>contrato_info", ""));	
	f.adicionarItem(f2);

	f3 = new Tree('Serviço 03 (Demanda)');
	f3.adicionarItem(new Link("Demandas", "<?=caminho_funcoesHTML?>demanda", ""));
	f.adicionarItem(f3);

	f4 = new Tree('Serviço 04 (Proc.Admin.)');	
    f4.adicionarItem(new Link('P.A.s de Aplicação de Penalidade (PAAP)', '<?=caminho_funcoesHTML?>pa', ''));    
	f.adicionarItem(f4);
	
	<?php if(isUsuarioAdmin()){?>
	f5 = new Tree('Serviço 05 (ADMINISTRADOR)');
    f5.adicionarItem(new Link('Usuários', '<?=caminho_funcoesHTML?>usuario_info', ''));
	f5.adicionarItem(new Link("Tramitação Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
	f5.adicionarItem(new Link("Alerta envio a SAD", "<?=caminho_funcoesHTML?>alertas/encaminhamento_SAD.php", ""));
	f5.adicionarItem(new Link('______', '#', ''));
	f5.adicionarItem(new Link('______', '#', ''));
	f5.adicionarItem(new Link('______', '#', ''));	
	f5.adicionarItem(new Link("IMPORTAR CSAFI", "<?=caminho_funcoesHTML?>contrato/importarContrato.php", ""));
	f5.adicionarItem(new Link("IMPORTAR CV-SAFI", "<?=caminho_funcoesHTML?>contrato/importarConvenio.php?tipo=V", ""));
	f5.adicionarItem(new Link("IMPORTAR PROFISCO", "<?=caminho_funcoesHTML?>contrato/importarConvenio.php?tipo=P", ""));		
	f5.adicionarItem(new Link("ATUALIZAR CONTRATADA", "<?=caminho_funcoesHTML?>contrato/atualizarContratada.php", ""));
	f.adicionarItem(f5);	
	<?php }?>
	

	/*
	exemplo
    // SERVIÇO 4
	f3 = new Tree('Diária');
	f3.adicionarItem(new Link("Grupo de Diárias","/sfi_fin_gfu/PRManterGrupoDiaria", "25040"));
	f3.adicionarItem(new Link("Faixa de Diária", "/sfi_fin_gfu/PRManterFaixaDiaria", "25041"));
	f3.adicionarItem(new Link("Tabela de Diária", "/sfi_fin_gfu/PRManterTabelaDiaria", "25042"));
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