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
	
	// SERVIÇO 1
	f1 = new Tree('Manutenção de Tabelas');	
    f1.adicionarItem(new Link('Manter Gestores', '<?=caminho_funcoesHTML?>gestor', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Pessoas');	
    f2.adicionarItem(new Link('Manter Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
    f2.adicionarItem(new Link('Manter Responsáveis', '<?=caminho_funcoesHTML?>gestor_pessoa', ''));    
	f.adicionarItem(f2);

	// SERVIÇO 2
	f3 = new Tree('Contratos');
	f3.adicionarItem(new Link("Manter Contratos", "<?=caminho_funcoesHTML?>contrato", ""));
	f.adicionarItem(f3);

	/*
    // SERVIÇO 4
	f3 = new Tree('Diária');
	f3.adicionarItem(new Link("Grupo de Diárias","/sfi_fin_gfu/PRManterGrupoDiaria", "25040"));
	f3.adicionarItem(new Link("Faixa de Diária", "/sfi_fin_gfu/PRManterFaixaDiaria", "25041"));
	f3.adicionarItem(new Link("Tabela de Diária", "/sfi_fin_gfu/PRManterTabelaDiaria", "25042"));
	f.adicionarItem(f3);

	// SERVIÇO 5
	f4 = new Tree('Credores');
	f4.adicionarItem(new Link("Credor Recebedor de Suprimento", "/sfi_fin_gfu/PRManterRecebedorSuprimento", "25052"));
	f4.adicionarItem(new Link("Transferências por Convênios", "/sfi_fin_gfu/PRManterPessoaTransferenciaVoluntaria", "25053"));
	f4.adicionarItem(new Link("Credenciar Ordenador de Despesa","/sfi_fin_gfu/PRManterOrdenadorDespesa", "25054"));
	f4.adicionarItem(new Link("Cadastro de Credores", "/sfi_fin_gfu/PRManterCadastroCredor", "25056"));
	f4.adicionarItem(new Link("Grupo de Credores","/sfi_fin_gfu/PRManterGrupoCredor", "25055"));
	f4.adicionarItem(new Link("Credor Recebedor de Repasse Financeiro", "/sfi_fin_gfu/PRManterRecebedorRepasseFinanceiro"));
	f.adicionarItem(f4);

	// SERVIÇO 6
	f5 = new Tree('Empenho');
	f5.adicionarItem(new Link("Cadastro de Solicitação de Empenho", '/sfi_fin_gfu/PRManterSolicitacaoEmpenho', '25061'));
	f5.adicionarItem(new Link("Cadastro de Item Externo ao Banco de Preço", '/sfi_fin_gfu/PRManterItemExternoBancoPreco', '25062'));
	f5.adicionarItem(new Link("Consultar Empenhos", "/sfi_fin_gfu/PRManterEmpenho", "25063"));
	f5.adicionarItem(new Link("Anulação de Empenhos", "/sfi_fin_gfu/PRManterAnulacaoEmpenho", "25064"));
	f5.adicionarItem(new Link("Consultar Empenhos do Legado", "/sfi_fin_gfu/PRManterEmpenhoLegado", "25065"));
	f5.adicionarItem(new Link("Consultar Empenho de Restos a Pagar", "/sfi_fin_gfu/PRConsultarRestosAPagar", "25066"));
	f.adicionarItem(f5);
	

	// SERVIÇO 7
	f6 = new Tree('Liquidação');
	f6.adicionarItem(new Link("Cadastro de Liquidação de Empenho", "/sfi_fin_gfu/PRManterLiquidacao", "25071"));
	f6.adicionarItem(new Link("Cadastro de Liquidação Coletiva", "/sfi_fin_gfu/PRManterLiquidacaoTotal", "25072"));
	f6.adicionarItem(new Link("Cadastro de Execução de Empenho Não Processado", "/sfi_fin_gfu/PRConsultarExecucao", "25073"));
	f.adicionarItem(f6);

	// SERVIÇO 10
	f8 = new Tree('Informações Gerenciais');
	f8.adicionarItem(new Link('Consultar Custos de uma Unidade Gestora', '/sfi_fin_gfu/PRConsultarCustosUnidadeGestora', '25101'));
	f8.adicionarItem(new Link('Resumo da despesa empenhada por Unidade Gestora', '/sfi_fin_gfu/PRResumoDespesaEmpenhadaUG', '25102'));
	f8.adicionarItem(new Link('<font color=#9933FF>Consultar despesa empenhada por Unidade Gestora</font>', '/sfi_fin_gfu/PRConsultarDespesaEmpenhadaUnidadeGestora', '25103'));
	f.adicionarItem(f8);

	// SERVIÇO 11
	f9 = new Tree('Unidade Gestora');
	f9.adicionarItem(new Link('Cadastro de Tipos de Unidades Gestoras', '/sfi_fin_gfu/PRManterTipoUnidadeGestora', '25110'));
	f9.adicionarItem(new Link('Cadastro de Unidades Gestoras', '/sfi_fin_gfu/PRManterUnidadeGestora', '25111'));
	f.adicionarItem(f9);

	/****************************************** FIM SERVIÇOS GFU *****************************************************************************/

	/*fPLO = new Tree('PLO', true);
	fPLF = new Tree('PLF', true);
	fGFE = new Tree('GFE', true);
	fCTB = new Tree('CTB', true);
	
	fCTB.adicionarItem(new Link("Fechamento Mensal Por UG","/sfi_fin_ctb/PRManterFechamentoMensalPorUG", "24080"));
	fCTB.adicionarItem(new Link("Fechamento Mensal","/sfi_fin_ctb/PRManterFechamentoMensal", "24080"));
	fCTB.adicionarItem(new Link("Movimentações Contábeis","/sfi_fin_ctb/PRManterMovimentacaoContabil", "24080"));
	fCTB.adicionarItem(new Link("Razão","/sfi_fin_ctb/PRManterRazao", "24080"));
	fCTB.adicionarItem(new Link("Gestores","/sfi_fin_ctb/PRManterGestorContabil", "24080"));
	
	fPLF.adicionarItem(new Link("Menu de Funções","/sfi_fin_plf/Menu", "24080"));

	fGFE.adicionarItem(new Link("Manter Previsao Desembolso","/sfi_fin_gfe/PRManterPrevisaoDesembolso", "24080"));
	fGFE.adicionarItem(new Link("Manter Ordem Bancária","/sfi_fin_gfe/PRManterOrdemBancaria", "24080"));
	
	fPLO.adicionarItem(new Link("Cadastro de Paineis Estrategicos","/sfi_orc_plo/PRManterPainel", "24080"));
	fPLO.adicionarItem(new Link("Cadastro de Provisao Sem Solicitacao","/sfi_orc_plo/PRManterProvisao", "24080"));
	fPLO.adicionarItem(new Link("Avaliacao de Solicitacao de Destaque de Credito","/sfi_orc_plo/PRManterAvaliacaoSolicitacaoDestaque", "24080"));
	
	fOutros = new Tree('Menu de Funcionalidades Ligadas ao GFU', true);
	fOutros.adicionarItem(fCTB);

	fOutros.adicionarItem(fPLF);

	fOutros.adicionarItem(fGFE);

	fOutros.adicionarItem(fPLO);*/

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