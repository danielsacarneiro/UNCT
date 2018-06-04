<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

try{
//inicia os parametros
inicio();

$titulo = "MENU de Fun��es";
setCabecalho($titulo);
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>

<SCRIPT language="javascript">
	f = new Tree('Menu de Funcionalidades finalizadas e entregue a SEFAZ-PE (Homologa��o)', true);
	
	f1 = new Tree('Servi�o 01 (Tabelas)');	
    f1.adicionarItem(new Link('Documentos', '<?=caminho_funcoesHTML?>documento', ''));
    f1.adicionarItem(new Link('Gestores', '<?=caminho_funcoesHTML?>gestor', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Servi�o 02 (Contratos)');
	f2.adicionarItem(new Link("Contratos-Planilha", "<?=caminho_funcoesHTML?>contrato", ""));
	f2.adicionarItem(new Link("Contratos-Informa��es Adicionais", "<?=caminho_funcoesHTML?>contrato_info", ""));
	f2.adicionarItem(new Link("Contratos-Consolida��o", "<?=caminho_funcoesHTML?>contrato_consolidacao", ""));	
	f.adicionarItem(f2);

	f3 = new Tree('Servi�o 03 (Demanda)');
	f3.adicionarItem(new Link("Demandas", "<?=caminho_funcoesHTML?>demanda", ""));
	f.adicionarItem(f3);

	f4 = new Tree('Servi�o 04 (Proc.Admin.)');
    f4.adicionarItem(new Link('P.A.s de Aplica��o de Penalidade (PAAP)', '<?=caminho_funcoesHTML?>pa', ''));    
    f4.adicionarItem(new Link('Penalidades (PAAP)', '<?=caminho_funcoesHTML?>pa_penalidade', ''));
	f.adicionarItem(f4);

	fprocLic = new Tree('Servi�o 05 (Proc.Licitat�rio)');
	fprocLic.adicionarItem(new Link('Proc.Licitat�rio', '<?=caminho_funcoesHTML.voProcLicitatorio::getNmTabela()?>', ''));
	f.adicionarItem(fprocLic);

	fadm = new Tree('Servi�o 06 (Administrativo)');
	fadm.adicionarItem(new Link("Agendamento de alertas (SEM EMAIL)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=N", ""));
	fadm.adicionarItem(new Link("Controle Sistemas Externos (LICON)", "<?=caminho_funcoesHTML?>contrato_licon", ""));	
	f.adicionarItem(fadm);

	/*flegislacao = new Tree('Servi�o 05 (Legisla��o)');    
	flegislacao.adicionarItem(new Link('Decreto 42.191 - Aplica��o de penalidade(PAAP)', 'h:\\ASSESSORIA JUR�DICA\\ATJA\\LEGISLA��O\\DECRETO N� 42.191 DE 2015 - APLICA��O DE PENALIDADES A LICITANTES.pdf', ''));
	f.adicionarItem(flegislacao);*/

	<?php if(isUsuarioAdmin()){?>
	f5 = new Tree('ADMINISTRADOR');
    f5.adicionarItem(new Link('Usu�rios', '<?=caminho_funcoesHTML?>usuario_info', ''));    
	f5.adicionarItem(new Link("Tramita��o Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
	f5.adicionarItem(new Link("Agendamento de alertas (COM EMAIL)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=S", ""));
	f5.adicionarItem(new Link("Pagina TESTE", "teste.php", ""));
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
    // SERVI�O 4
	f3 = new Tree('Di�ria');
	f3.adicionarItem(new Link("Grupo de Di�rias","/sfi_fin_gfu/PRManterGrupoDiaria", "25040"));
	f3.adicionarItem(new Link("Faixa de Di�ria", "/sfi_fin_gfu/PRManterFaixaDiaria", "25041"));
	f3.adicionarItem(new Link("Tabela de Di�ria", "/sfi_fin_gfu/PRManterTabelaDiaria", "25042"));
	f.adicionarItem(f3);
	*/


</SCRIPT>
</HEAD>
<?=setTituloPagina($titulo)?>
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
                    				<TR>
                        				<TD class="tabeladadosdestacadonegrito">ORIENTA��O:
                        				</TD>
                    				</TR>
                    				<?php 
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 47));
                    				echo imprimeBotaoDocumento($vodocumento, "Documenta��o exigida pela SAD");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 3));
                    				echo imprimeBotaoDocumento($vodocumento, "Visto Edital");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 4));
                    				echo imprimeBotaoDocumento($vodocumento, "Visto Contratos");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 3));
                    				echo imprimeBotaoDocumento($vodocumento, "Compila��o Decreto 42.191/15 - PAAP");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 4));
                    				echo imprimeBotaoDocumento($vodocumento, "Quadro Resumo Compet�ncia e Autoriza��o Pr�via SAD");
                    				?>
                    				<TR>
                        				<TD class="tabeladadosdestacadonegrito">LEGISLA��O:
                        				</TD>
                    				</TR>                    				
                    				<?php 
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Decreto Penalidade");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_PGE,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Consulta boletins PGE");
                    				
                    				$vodocumento = new voDocumento(array(2015, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Centraliza��o SAD");                    				
                    				?>
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
<?php 
}catch(Exception $ex){
	tratarExcecaoHTML($ex, null, "funcoes/mensagemErro.php");
}
?>
