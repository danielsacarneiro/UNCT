<?php
include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

try{
//inicia os parametros
inicio();

$titulo = "MENU de Funções";
setCabecalho($titulo);

//var_dump(getEmailUsuarioLogado());
// echo getEmailUsuarioLogado();
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_oficio.js"></SCRIPT>

<SCRIPT language="javascript">
<?php
$countServicos = 0;
function getNumServico($count){
	$qtdfinalServicos = 2;
	return complementarCharAEsquerda($count, "0", $qtdfinalServicos);	
}

?>
	f = new Tree('Menu de Funcionalidades finalizadas e entregue a SEFAZ-PE (Homologação)', true);
	
	f1 = new Tree('Serviço <?=getNumServico(++$count)?> (Tabelas)');	
    f1.adicionarItem(new Link('Documentos', '<?=caminho_funcoesHTML?>documento', ''));
    f1.adicionarItem(new Link('Orgãos gestores', '<?=caminho_funcoesHTML?>gestor', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
    f1.adicionarItem(new Link('Registro Livro', '<?=caminho_funcoesHTML?>registro_livro', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Serviço <?=getNumServico(++$count)?> (Contratos)');
	f2.adicionarItem(new Link("Contratos", "<?=caminho_funcoesHTML?>contrato", ""));
	f2.adicionarItem(new Link("Contratos-Informações Adicionais", "<?=caminho_funcoesHTML?>contrato_info", ""));
	f2.adicionarItem(new Link("Contratos-Consolidação", "<?=caminho_funcoesHTML?>contrato_consolidacao", ""));
	f2.adicionarItem(new Link("Controle Sistemas Externos (LICON)", "<?=caminho_funcoesHTML?>contrato_licon", ""));
	f2.adicionarItem(new Link("Contrato Execução (Acréscimos, Supressões e Reajustes)", '<?=caminho_funcoesHTML.voContratoModificacao::getNmTabela()?>', ""));
	f2.adicionarItem(new Link("Contratos não incluídos!", "<?=caminho_funcoesHTML?>contrato/contratoAincluir.php", "", null,true));
	f.adicionarItem(f2);

	f3 = new Tree('Serviço <?=getNumServico(++$count)?> (Demanda)');
	f3.adicionarItem(new Link("Demandas", "<?=caminho_funcoesHTML?>demanda", ""));
	f3.adicionarItem(new Link("Demanda por Usuário", "<?=caminho_funcoesHTML?>demanda_gestao/detalharDemandaUsuario.php", "", null, true));
	<?php
	if(isUsuarioRodaImportacao()){
	?>	    
		f3.adicionarItem(new Link("ATENÇÃO: Tramitação Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
	<?php }?>	
	f.adicionarItem(f3);

	fgestao = new Tree('Serviço <?=getNumServico(++$count)?> (Gestão)');
	fgestao.adicionarItem(new Link("Contratos a vencer/Ano", "<?=caminho_funcoesHTML?>contrato_consolidacao/detalharContratosAnosAVencer.php", "", null, true));
	fgestao.adicionarItem(new Link("Rendimento Demanda", "<?=caminho_funcoesHTML?>demanda_gestao/indexRendimento.php", ""));
	fgestao.adicionarItem(new Link("Gestão Demanda", "<?=caminho_funcoesHTML?>demanda_gestao", ""));
	f.adicionarItem(fgestao);	

	f4 = new Tree('Serviço <?=getNumServico(++$count)?> (PAAP)');
    f4.adicionarItem(new Link('Processo Administrativo de Aplicação de Penalidade (PAAP)', '<?=caminho_funcoesHTML?>pa', ''));    
    f4.adicionarItem(new Link('Penalidades', '<?=caminho_funcoesHTML?>pa_penalidade', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades e-Fisco', 'http://efisco.sefaz.pe.gov.br/sfi_fin_gbp/PREmitirFornecedorPenalidade', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades e-Fisco (Por empresa)', 'https://efisco.sefaz.pe.gov.br/sfi_fin_gbp/PRManterFornecedorOcorrencia', ''));
    f4.adicionarItem(new Link('EXTERNO: Penalidades Portal da Transparência', 'http://www.portaltransparencia.gov.br/sancoes/ceis?ordenarPor=nome&direcao=asc', ''));
	f.adicionarItem(f4);

	fprocLic = new Tree('Serviço <?=getNumServico(++$count)?> (Proc.Licitatório)');
	fprocLic.adicionarItem(new Link('Proc.Licitatório', '<?=caminho_funcoesHTML.voProcLicitatorio::getNmTabela()?>', ''));
	fprocLic.adicionarItem(new Link('Portarias das CPL´s', '<?=caminho_funcoesHTML.voProcLicitatorio::getNmTabela()?>/portarias.php', "", null,true));
	fprocLic.adicionarItem(new Link('EXTERNO: Mural PEIntegrado', 'https://www.peintegrado.pe.gov.br/Portal/Mural.aspx', "", null,true));	
	f.adicionarItem(fprocLic);	
	
	fSolicCompra = new Tree('Serviço <?=getNumServico(++$count)?> (Solic.Compra)');
	fSolicCompra.adicionarItem(new Link('Solic.Compra', '<?=caminho_funcoesHTML.voSolicCompra::getNmTabela()?>', ''));
	f.adicionarItem(fSolicCompra);	

	fMensageria = new Tree('Servico <?=getNumServico(++$count)?> (Mensageria)');
	fMensageria.adicionarItem(new Link('Mensageria', '<?=caminho_funcoesHTML.voMensageria::getNmTabela()?>', ''));	
	fMensageria.adicionarItem(new Link('Mensageria Registro', '<?=caminho_funcoesHTML.voMensageriaRegistro::getNmTabela()?>', ''));
	f.adicionarItem(fMensageria);

	fADM = new Tree('ADMINISTRATIVO');
	fADM.adicionarItem(new Link("PAINEL (Atividades relevantes)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=N", ""));
	fADM.adicionarItem(new Link("Pagina TESTE", "teste.php", "", null,true));
	f.adicionarItem(fADM);

	<?php
		//documento de designacao do SAFI
		$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
	?>
	f7 = new Tree('LEGISLAÇÃO)');
	//f7.adicionarItem(new Link('Designação SAFI','<?=caminho_funcoesHTML."exibir_pdf.php?chave=". $vodocumento->getValorChaveHTML()?>', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: Decreto Estadual Penalidade (DECRETO Nº 42.191/2015)', 'http://legis.alepe.pe.gov.br/texto.aspx?id=15904&tipo=', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: Lei Execução Fiscal Multa (LEI Nº 13.178/2006)', 'http://legis.alepe.pe.gov.br/texto.aspx?id=2257&tipo=', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: Boletins SCGE', 'http://www.scge.pe.gov.br/?page_id=781', "", null,true));
	f7.adicionarItem(new Link('EXTERNO: SCGEOrienta', 'https://www.scgeorienta.pe.gov.br/scgeorienta/login.jsf', "", null,true));

	
	f.adicionarItem(f7);
	
	/*flegislacao = new Tree('Serviço 05 (Legislação)');    
	flegislacao.adicionarItem(new Link('Decreto 42.191 - Aplicação de penalidade(PAAP)', 'h:\\ASSESSORIA JURÍDICA\\ATJA\\LEGISLAÇÃO\\DECRETO Nº 42.191 DE 2015 - APLICAÇÃO DE PENALIDADES A LICITANTES.pdf', ''));
	f.adicionarItem(flegislacao);*/

	<?php 
	if(isUsuarioRodaImportacao()){?>
		f5 = new Tree('ADMINISTRADOR');
		<?php if(isUsuarioAdmin()){?>
		    f5.adicionarItem(new Link('Usuários', '<?=caminho_funcoesHTML?>usuario_info', ''));
		    f5.adicionarItem(new Link('______', '#', ''));    
			f5.adicionarItem(new Link("Agendamento de alertas (COM EMAIL)", "agendamento.php?<?=constantes::$ID_REQ_IN_ENVIAR_EMAIL?>=S", ""));
		<?php }?>
		/*f5.adicionarItem(new Link('______', '#', ''));
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
		//f5.adicionarItem(new Link("REMOVER CARACTERES ESPECIAIS", "<?=caminho_funcoesHTML."contrato/atualizarContratada.php?". dbcontrato::$ID_REQ_REMOVER_CARACTER_ESPECIAL?>=S", "", null,true));*/
		f5.adicionarItem(new Link('______', '#', ''));		
		f5.adicionarItem(new Link("ATUALIZAR CONTRATADA", "<?=caminho_funcoesHTML?>contrato/atualizarContratada.php", "", null,true));
		f.adicionarItem(f5);	
	<?php
	}
	//} else if(isUsuarioPermissaoIntermediaria()){
	?>
		/*finter = new Tree('AVANÇADO');    
		finter.adicionarItem(new Link("Tramitação Demanda", "<?=caminho_funcoesHTML?>demanda_tram", ""));
		f.adicionarItem(finter);*/	
	<?php 
	//}
	?>


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
                        				<TD class="tabeladadosdestacadonegrito">ORIENTAÇÃO:
                        				</TD>
                    				</TR>
                    				<?php                   				
                    				/*$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 3));
                    				echo imprimeBotaoDocumento($vodocumento, "Manual Visto Edital");*/
                    				
                    				echo imprimeLinkDocumento("https://drive.google.com/open?id=1Iv6zfreYXtT4yttK9betEI-82HoRwWu8"
                    						, getTextoHTMLNegrito("ECONTI - MANUAL (em constante evolução)"));

                    				echo imprimeLinkDocumento("https://drive.google.com/open?id=1KqUtXE6Pxq5q7d8vhK3XIuyA4NBQ6jON"
                    						, getTextoHTMLNegrito("UNCT - MANUAL (em constante evolução)"));
                    				
                    				/*$vodocumento = new voDocumento(array(2021, dominioSetor::$CD_SETOR_UNCT,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, getTextoHTMLNegrito("MANUAL DE BOAS PRÁTICAS ECONTI (em constante evolução)"));*/
                    				
                    				/*$vodocumento = new voDocumento(array(2021, dominioSetor::$CD_SETOR_UNCT,dominioTpDocumento::$CD_TP_DOC_OUTROS, 34));
                    				echo imprimeBotaoDocumento($vodocumento, "UNCT - ORIENTAÇÕES GERAIS");*/
                    				
                    				
                    				$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Procedimento ATJA/CPL para visto em Edital.");
                    				
                    				/*$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 4));
                    				echo imprimeBotaoDocumento($vodocumento, "Quadro Resumo Competência e Autorização Prévia SAD");*/
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 47));
                    				echo imprimeBotaoDocumento($vodocumento, "Documentação exigida pela SAD");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 3));
                    				echo imprimeBotaoDocumento($vodocumento, "Compilação Decreto 42.191/15 - PAAP");
                    				                    				
                    				/*$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_PGE,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Checklist PGE para inscrição em dívida ativa.");*/
                    				
                    				$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 2));
                    				echo imprimeBotaoDocumento($vodocumento, "Manual Publicação CEPE.DOE.");
                    				
                    				?>
                    				<TR>
                        				<TD class="tabeladadosdestacadonegrito">PROCESSOS:
                        				</TD>
                    				</TR>
                    				<?php 
                    				//verificar a possibilidade de usar o documento checklist pra fazer um laco e buscar esses documentos dinamicamente no econti
                    				//facilitando a exibicao nesta tela direto quando incluido no banco
                    				//SUGESTAO: usar o tipo documento CHECKLIST
                    				/*$vodocumento = new voDocumento(array(2020, dominioSetor::$CD_SETOR_UNCT,dominioTpDocumento::$CD_TP_DOC_OUTROS, 44));
                    				echo imprimeBotaoDocumento($vodocumento, "FLUXOGRAMA CONTRATOS");*/
                    				
                    				echo imprimeLinkDocumento("https://drive.google.com/open?id=1Ngzidm2fXYZC8wZPKuYODTH1Rnr74YBy"
                    						, "FLUXOGRAMA CONTRATOS");
                    				
                    				$vodocumento = new voDocumento(array(2020, dominioSetor::$CD_SETOR_UNCT,dominioTpDocumento::$CD_TP_DOC_OUTROS, 50));
                    				echo imprimeBotaoDocumento($vodocumento, "COMO CADASTRAR FORNECEDOR EXTERNO NO SEI");
                    				
                    				$vodocumento = new voDocumento(array(2020, dominioSetor::$CD_SETOR_UNCT,dominioTpDocumento::$CD_TP_DOC_OUTROS, 45));
                    				echo imprimeBotaoDocumento($vodocumento, "COMO REGISTRAR APOSTILAMENTOS");
                    				
                    				$vodocumento = new voDocumento(array(2019, dominioSetor::$CD_SETOR_DILC,dominioTpDocumento::$CD_TP_DOC_OUTROS, 2));
                    				echo imprimeBotaoDocumento($vodocumento, "MACROPROCESSO LICITAÇÃO");
                    				
                    				?>
                    				<TR>
                        				<TD class="tabeladadosdestacadonegrito">LEGISLAÇÃO:
                        				</TD>
                    				</TR>                    				
                    				<?php 
                    				/*$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Decreto Penalidade");*/
                    				
                    				$vodocumento = new voDocumento(array(2020, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_OUTROS, 116));
                    				echo imprimeBotaoDocumento($vodocumento, "Resolucao TCE 003/16 - Publicação extrato contratos");
                    				
                    				$vodocumento = new voDocumento(array(2020, dominioSetor::$CD_SETOR_GOV,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Resolucao CPF 001/20 - suspensao contratacoes CORONAVIRUS");
                    				
                    				$vodocumento = new voDocumento(array(2018, dominioSetor::$CD_SETOR_PGE,dominioTpDocumento::$CD_TP_DOC_CHECKLIST, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Consulta boletins PGE");
                    				
                    				$vodocumento = new voDocumento(array(2015, dominioSetor::$CD_SETOR_ATJA,dominioTpDocumento::$CD_TP_DOC_LEGISLACAO, 1));
                    				echo imprimeBotaoDocumento($vodocumento, "Centralização SAD");
                    				
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
