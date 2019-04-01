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
    f1.adicionarItem(new Link('Unidades Administrativas', '<?=caminho_funcoesHTML?>gestor', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Servi�o 02 (Contratos)');
	f2.adicionarItem(new Link("Contratos-Planilha", "<?=caminho_funcoesHTML?>contrato", ""));
	f2.adicionarItem(new Link("Contratos-Informa��es Adicionais", "<?=caminho_funcoesHTML?>contrato_info", ""));
	f2.adicionarItem(new Link("Contratos-Consolida��o", "<?=caminho_funcoesHTML?>contrato_consolidacao", ""));
	f2.adicionarItem(new Link("Controle Sistemas Externos (LICON)", "<?=caminho_funcoesHTML?>contrato_licon", ""));
	f2.adicionarItem(new Link("Contrato Execu��o (Acr�scimos, Supress�es e Reajustes)", '<?=caminho_funcoesHTML.voContratoModificacao::getNmTabela()?>', ""));
	f.adicionarItem(f2);

	f3 = new Tree('Servi�o 03 (Demanda)');
	f3.adicionarItem(new Link("Demandas", "<?=caminho_funcoesHTML?>demanda", ""));
	f.adicionarItem(f3);

	f4 = new Tree('Servi�o 04 (PAAP)');
    f4.adicionarItem(new Link('Processo Administrativo de Aplica��o de Penalidade (PAAP)', '<?=caminho_funcoesHTML?>pa', ''));    
    f4.adicionarItem(new Link('Penalidades', '<?=caminho_funcoesHTML?>pa_penalidade', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades e-Fisco', 'http://efisco.sefaz.pe.gov.br/sfi_fin_gbp/PREmitirFornecedorPenalidade', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades e-Fisco (Por empresa)', 'https://efisco.sefaz.pe.gov.br/sfi_fin_gbp/PRManterFornecedorOcorrencia', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades Portal da Transpar�ncia', 'http://www.portaltransparencia.gov.br/sancoes/ceis?ordenarPor=nome&direcao=asc', ''));
	f.adicionarItem(f4);

	fprocLic = new Tree('Servi�o 05 (Proc.Licitat�rio)');
	fprocLic.adicionarItem(new Link('Proc.Licitat�rio', '<?=caminho_funcoesHTML.voProcLicitatorio::getNmTabela()?>', ''));
	f.adicionarItem(fprocLic);	
	
	fmsg = new Tree('Servi�o 06 (Administrativo)');
	fmsg.adicionarItem(new Link("PAINEL (Atividades relevantes)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=N", ""));
	fmsg.adicionarItem(new Link('Mensageria', '<?=caminho_funcoesHTML.voMensageria::getNmTabela()?>', ''));	
	fmsg.adicionarItem(new Link('Mensageria Registro', '<?=caminho_funcoesHTML.voMensageriaRegistro::getNmTabela()?>', ''));
	f.adicionarItem(fmsg);

	<?php
		//documento de designacao do SAFI
		$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
	?>
	f7 = new Tree('Servi�o 07 (Legisla��o)');
	//f7.adicionarItem(new Link('Designa��o SAFI','<?=caminho_funcoesHTML."exibir_pdf.php?chave=". $vodocumento->getValorChaveHTML()?>', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: Decreto Estadual Penalidade (DECRETO N� 42.191/2015)', 'http://legis.alepe.pe.gov.br/texto.aspx?id=15904&tipo=', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: Lei Execu��o Fiscal Multa (LEI N� 13.178/2006)', 'http://legis.alepe.pe.gov.br/texto.aspx?id=2257&tipo=', "", null,true));
	f.adicionarItem(f7);
	
	/*flegislacao = new Tree('Servi�o 05 (Legisla��o)');    
	flegislacao.adicionarItem(new Link('Decreto 42.191 - Aplica��o de penalidade(PAAP)', 'h:\\ASSESSORIA JUR�DICA\\ATJA\\LEGISLA��O\\DECRETO N� 42.191 DE 2015 - APLICA��O DE PENALIDADES A LICITANTES.pdf', ''));
	f.adicionarItem(flegislacao);*/

	<?php 
	if(isUsuarioAdmin()){?>
		f5 = new Tree('ADMINISTRADOR');
	    f5.adicionarItem(new Link('Usu�rios', '<?=caminho_funcoesHTML?>usuario_info', ''));    
		f5.adicionarItem(new Link("Tramita��o Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
		f5.adicionarItem(new Link("Agendamento de alertas (COM EMAIL)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=S", ""));
		f5.adicionarItem(new Link("Pagina TESTE", "teste.php", "", null,true));
		f5.adicionarItem(new Link('______', '#', ''));
		f5.adicionarItem(new Link('______', '#', ''));
		f5.adicionarItem(new Link("LIMPAR TABELA CONTRATO", "<?=caminho_funcoesHTML. "contrato/importarContrato.php?". dbcontrato::$ID_REQ_INICIAR_TAB_CONTRATO?>=S", "", null,true));
		f5.adicionarItem(new Link('______', '#', ''));
		f5.adicionarItem(new Link('______', '#', ''));	
		f5.adicionarItem(new Link("IMPORTAR CSAFI", "<?=caminho_funcoesHTML?>contrato/importarContrato.php", "", null,true));
		f5.adicionarItem(new Link('______', '#', ''));

		//Link(label, href, seq, background, pInJanelaAuxiliar, pValue, pInSelecionado, pNmClasseCSS, pInComParametrosFramework, isLinkArquivo)
		//abre com janela auxiliar
		f5.adicionarItem(new Link("IMPORTAR CV-SAFI", "<?=caminho_funcoesHTML?>contrato/importarConvenio.php?tipo=V", "", null,true));
		f5.adicionarItem(new Link('______', '#', ''));
		f5.adicionarItem(new Link("IMPORTAR PROFISCO", "<?=caminho_funcoesHTML?>contrato/importarConvenio.php?tipo=P", "", null,true));
		f5.adicionarItem(new Link('______', '#', ''));		
		f5.adicionarItem(new Link("ATUALIZAR CONTRATADA", "<?=caminho_funcoesHTML?>contrato/atualizarContratada.php", "", null,true));
		f5.adicionarItem(new Link("REMOVER CARACTERES ESPECIAIS", "<?=caminho_funcoesHTML."contrato/atualizarContratada.php?". dbcontrato::$ID_REQ_REMOVER_CARACTER_ESPECIAL?>=S", "", null,true));
		f.adicionarItem(f5);	
	<?php 
	} else if(isUsuarioPermissaoIntermediaria()){?>
		finter = new Tree('AVAN�ADO');    
		finter.adicionarItem(new Link("Tramita��o Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
		f.adicionarItem(finter);	
	<?php 
	}?>


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

                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 7));
                    				echo imprimeBotaoDocumento($vodocumento, "Procedimento para visto em Edital.");
                    				
                    				$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_PGE,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Checklist PGE para inscri��o em d�vida ativa.");                    				
                    				?>
                    				<TR>
                        				<TD class="tabeladadosdestacadonegrito">LEGISLA��O:
                        				</TD>
                    				</TR>                    				
                    				<?php 
                    				/*$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Decreto Penalidade");*/
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_PGE,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Consulta boletins PGE");
                    				
                    				$vodocumento = new voDocumento(array(2015, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Centraliza��o SAD");
                    				
                    				$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Designacao SAFI");
                    				
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
