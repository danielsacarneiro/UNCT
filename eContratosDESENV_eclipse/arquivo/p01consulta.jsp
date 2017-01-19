<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<%--
 Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE. 
--%>

<%@ page language="java" contentType="text/html; charset=ISO-8859-1"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.tiposdado.TextoCaixaAlta"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.tiposdado.Data"%>
<%@ page import="java.lang.String"%>
<%@ page import="java.util.Iterator"%>
<%@ page import="java.util.ArrayList"%>
<%@ page import="java.sql.Timestamp"%>
<%@ page import="br.gov.pe.sefaz.sfi.excecoes.ExcecaoGenerica"%>
<%@ page import="br.gov.pe.sefaz.sfi.excecoes.ExcecaoPaginaJSP"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.msgusuario.MensagemUsuarioAlerta"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.Parametros"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.VOGenerico"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.BibliotecaFuncoesPrincipal"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.BibliotecaFuncoesDataHora"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.otd.OTDRespostaConsulta"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.GeradorMolduraCabecalho"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.GeradorBarraPaginacao"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.GeradorMolduraRodape"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoLocalizar"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoDetalhar"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoIncluir"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoAlterar"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoExcluir"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoSelecionar"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoFechar"%>
<%@ page import="br.gov.pe.sefaz.sfi.util.web.BotaoGenerico"%>
<%@ page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.valueobjects.VOReferenciaLegal"%>
<%@ page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.filtros.FiltroManterReferenciaLegal"%>
<%@ page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.web.PRManterReferenciaLegal"%>
<%@ page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.web.RadioButtonTipoVigencia"%>
<%@ page import="br.gov.pe.sefaz.sfi.fin.gfu.util.ConstantesGFU"%>

<%@page import="br.gov.pe.sefaz.sfi.fin.gfu.util.web.RadioButtonIsRegistroDesativado"%>
<%@page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.web.SelectOrdenacaoConsultaReferenciaLegal"%>
<%@page import="br.gov.pe.sefaz.sfi.util.web.RadioButtonOrdenacaoConsulta"%>
<%@page import="br.gov.pe.sefaz.sfi.util.web.BotaoHistorico"%>
<%@page import="br.gov.pe.sefaz.sfi.fin.gfu.servico01.web.PRConsultarHistoricoReferenciaLegal"%>

<%@page import="br.gov.pe.sefaz.sfi.util.tiposdado.Texto"%><HTML>
<HEAD>
<TITLE>Cadastro de Referência Legal</TITLE>
<LINK href="<%=Parametros.getInstancia().getURLCSS()%>" rel="stylesheet" type="text/css">

<SCRIPT language="JavaScript" type="text/javascript" src="<%=Parametros.getInstancia().getURLBaseJavaScript()%>/biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<%=Parametros.getInstancia().getURLBaseJavaScript()%>/biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<%=Parametros.getInstancia().getURLBaseJavaScript()%>/biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<%=Parametros.getInstancia().getURLBaseJavaScript()%>/biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<%=Parametros.getInstancia().getURLBaseJavaScript()%>/biblioteca_funcoes_vigencia.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

// Submete o filtro de consulta 
function processarFiltroConsulta(pAcao, pEvento, pNaoUtilizarIdContextoSessao) {
	
	if (!isCampoTextoValido(document.frm_principal.dsReferenciaLegal, false, 0, 100))
	    return false;
	    	
	if (!isCampoNumericoValido(document.frm_principal.primeiro_campo, false, 0, 32767, null, false)) {
		return false
	}	    

	document.frm_principal.nao_utilizar_id_contexto_sessao.value = pNaoUtilizarIdContextoSessao;
	document.frm_principal.id_contexto_sessao.value = "";
	submeterFormulario(pAcao, pEvento);
}

// Exibe o formulario de inclusao
function exibirInclusao(pAcao, pEvento) {
	submeterFormulario(pAcao, pEvento);
}

// Exibe o formulario de alteracao
function exibirAlteracao(pAcao, pEvento) {
	var array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
    
    if (!isFormularioValido() || !validarVigenciaRegistroAlteracao(3,4))
			return;

	if(array[7] == '<%=ConstantesGFU.CD_SITUACAO_INATIVO%>'){
		exibirMensagem("Não é possível alterar um registro desativado.");
		return;
	}	
	
	submeterFormulario(pAcao, pEvento);
}


// Exibe o formulario de detalhamento para exclusao
function exibirExclusao(pAcao, pEvento) {
	var array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
    var dtAtual = getDataComoString(getDataHoje());
    var dtFimVigencia = array[4];
    
    if(dtFimVigencia != "" && dtFimVigencia != "null"){
    	dtFimVigencia = dtFimVigencia.substring(8,10) + "/" + dtFimVigencia.substring(5,7) + "/" + dtFimVigencia.substring(0,4); 
    }
    
	if (!isFormularioValido())
		return;
	
	if(dtFimVigencia != "" && dtFimVigencia != "null" && isDataIgual(dtAtual, dtFimVigencia, true)){
		exibirMensagem("Não é possível excluir! A vigência deste registro termina hoje.");
		return;
	}
	
	if(dtFimVigencia != "" && dtFimVigencia != "null" && isDataMenor(dtAtual, dtFimVigencia, true)){
		exibirMensagem("O registro não pode ser excluído pois está fora de vigência.");
		return;
	}
	
	if(array[7] == '<%=ConstantesGFU.CD_SITUACAO_INATIVO%>'){
		exibirMensagem("O registro não pode ser excluído pois está desativado.");
		return;
	}	

	submeterFormulario(pAcao, pEvento);
}

// Exibe o formulario de detalhamento
function exibirDetalhamentoConsulta(pAcao, pEvento) {
	if (isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta", false)) {
	
		if (!isFormularioValido())
			return;

		submeterFormulario(pAcao, pEvento);
	}
}

// Exibe o formulario do histórico
function exibirFiltroConsultaHistorico(pAcao, pEvento) {
	var exibeMensagem = false;
	   
	if (document.frm_principal.primeiro_campo.value == "")
	   exibeMensagem = true;	   
	   
	//Se os campos acima não estiverem preenchidos, verifica se existe um item selecionado	
	if (isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta", true))		
		exibeMensagem = false;

	if(exibeMensagem){
		exibirMensagem("Selecione um registro localizado ou informe o código da Referência Legal.");
		return;
	}			   	   

	document.frm_principal.nao_utilizar_id_contexto_sessao.value = '<%=ConstantesGFU.CD_VERDADEIRO%>';
	submeterFormularioJanelaAuxiliar(pAcao, pEvento);
}

// Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
		cdReferenciaLegal = array[0];
		dsReferenciaLegal = array[1];
		dtInicioVigenciaReferenciaLegal = array[2];
		dtFimVigenciaReferenciaLegal = array[3];
		window.opener.transferirDadosReferenciaLegal(cdReferenciaLegal, dsReferenciaLegal, dtInicioVigenciaReferenciaLegal, dtFimVigenciaReferenciaLegal);
		window.close();
	}
}


function desativarRegistro(pAcao, pEvento){

	var array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta");
    
	if (!isFormularioValido())
		return;

	if(array[7] == '<%=ConstantesGFU.CD_SITUACAO_INATIVO%>'){
		exibirMensagem("<%=MensagemUsuarioAlerta.getInstancia().toString(ConstantesGFU.SG_SISTEMA_FIN_GFU, ConstantesGFU.CD_MSGUSRALERTA_REGISTRO_JA_DESATIVADO)%>");
		return;
	}	
	
	submeterFormulario(pAcao, pEvento);
}


function habilitarDesativos(pCampo){
var aux, elemento;
	if(pCampo.value ==  '<%=ConstantesGFU.CD_OPCAO_VIGENTES%>'){
		elemento = document.getElementsByName('OpcaoRegistroDesativado');
		for(aux=0;aux<elemento.length;aux++){
			elemento[aux].disabled = true;
		}
	}else{
		elemento = document.getElementsByName('OpcaoRegistroDesativado');
		for(aux=0;aux<elemento.length;aux++){
			elemento[aux].disabled = false;
		}
	}
}
function init(){
var vigentes = document.getElementById("vigentes");
	habilitarDesativos(vigentes);
}

</SCRIPT>
<META name="GENERATOR" content="IBM WebSphere Studio">
</HEAD>

<%
try {

	// Declaracao de variaveis
	String cdTipoVigencia = "";		
	String cdReferenciaLegal = "";
	String dsReferenciaLegal = "";
	String idContextoSessao = "";
	VOReferenciaLegal voSelecionado = null;
	String indicadorFiltroOculto = "";
	OTDRespostaConsulta otdRespostaConsulta = null;
	Iterator dados;
	String dtReferenciaInicial = "";
	String dtReferenciaFinal = "";
	String dtRealizacaoInicial = "";
	String dtRealizacaoFinal = "";	
	String opcaoRegistroDesativado = "";	
	String nmAtributoOrdenacaoConsulta = "";
	String tpOrdenacaoConsulta = "";	

	// Obtem atributos/parametros do request
	cdTipoVigencia = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_CdTipoVigencia, true, request);
	cdReferenciaLegal = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_CdReferenciaLegal, true, request);
	dsReferenciaLegal = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_DsReferenciaLegal, true, request);
	voSelecionado = (VOReferenciaLegal)PRManterReferenciaLegal.getAtributoOpcional(PRManterReferenciaLegal.ID_REQ_REGISTRO_SELECIONADO, request);
	otdRespostaConsulta = (OTDRespostaConsulta)PRManterReferenciaLegal.getAtributoOpcional(OTDRespostaConsulta.ID_OBJETO, request);
	idContextoSessao = PRManterReferenciaLegal.getAtributoOuParametroStringOpcional(PRManterReferenciaLegal.ID_REQ_CONTEXTO_SESSAO, request);
	indicadorFiltroOculto = PRManterReferenciaLegal.getAtributoOuParametroStringOpcional(PRManterReferenciaLegal.ID_REQ_INDICADOR_FILTRO_OCULTO, request);
	dtReferenciaInicial = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_DtReferenciaInicial,true, request);
	dtReferenciaFinal = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_DtReferenciaFinal,true, request);
	dtRealizacaoInicial = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_DtRealizacaoInicial,true, request);
	dtRealizacaoFinal = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_DtRealizacaoFinal,true, request);
	opcaoRegistroDesativado = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_OpcaoRegistroDesativado, true, request);
	nmAtributoOrdenacaoConsulta = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_NmAtributoOrdenacaoConsulta, true, request);
	tpOrdenacaoConsulta = PRManterReferenciaLegal.getAtributoStringOpcional(PRManterReferenciaLegal.ID_REQ_TpOrdenacaoConsulta, true, request);

	if(tpOrdenacaoConsulta == null || tpOrdenacaoConsulta.equals(""))
		tpOrdenacaoConsulta = ConstantesGFU.CD_TIPO_ORDENACAO_DECRESCENTE;

	// Manipula variaveis
	if (otdRespostaConsulta != null)
		dados = otdRespostaConsulta.getColecaoObjetos().iterator();
	else
		dados = (new ArrayList()).iterator();
		
		
	if(cdTipoVigencia.equals("")){
		cdTipoVigencia = ConstantesGFU.CD_OPCAO_VIGENTES;
		
		if(opcaoRegistroDesativado.equals("")){
			opcaoRegistroDesativado = ConstantesGFU.CD_FALSO;
		}
	}
	
	if(cdTipoVigencia.equals(ConstantesGFU.CD_OPCAO_VIGENTES)){
		opcaoRegistroDesativado = ConstantesGFU.CD_FALSO;
	}
	

%>

<BODY class="paginadados" onload="init();">
<FORM name="frm_principal" method="post">

<INPUT type="hidden" id="id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_CONTEXTO_SESSAO%>" value="<%=idContextoSessao%>"> 
<INPUT type="hidden" id="evento" name="<%=PRManterReferenciaLegal.ID_REQ_EVENTO%>" value=""> 
<INPUT type="hidden" id="nao_utilizar_id_contexto_sessao" name="<%=PRManterReferenciaLegal.ID_REQ_NAO_UTILIZAR_ID_CONTEXTO_SESSAO%>" value=""> 
<INPUT type="hidden" id="indicador_filtro_oculto" name="<%=PRManterReferenciaLegal.ID_REQ_INDICADOR_FILTRO_OCULTO%>" value="<%=indicadorFiltroOculto%>">
 <INPUT type="hidden" id="vigentes" value="<%=cdTipoVigencia%>">

<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
        <%=GeradorMolduraCabecalho.getInstancia().getHTML(request, "Cadastro de Referência Legal", "", true, true)%>
        <TR>
            <TD class="conteinerfiltro">
            <DIV id="div_filtro" class="div_filtro">
            <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
                        <TH class="campoformulario" width="172">Código:</TH>
                        <TD class="campoformulario" colspan="3"><INPUT type="text" id="primeiro_campo" name="<%=PRManterReferenciaLegal.ID_REQ_CdReferenciaLegal%>"  value="<%=TextoCaixaAlta.toHTMLComMascara(cdReferenciaLegal, false)%>"  class="camponaoobrigatorio" size="6" maxlength="5" ></TD>
                    </TR>
                    <TR>
                        <TH class="campoformulario" width="172">Descrição:</TH>
                        <TD class="campoformulario" colspan="3" width="388"><INPUT type="text" id="dsReferenciaLegal" name="<%=PRManterReferenciaLegal.ID_REQ_DsReferenciaLegal%>"  value="<%=TextoCaixaAlta.toHTMLComMascara(dsReferenciaLegal, false)%>"  class="camponaoobrigatorio" size="80" maxlength="100" ></TD>
                    </TR>
 					<TR>
                        <TH class="campoformulario" width="18%">Período de Vigência:</TH>
                        <TD class="campoformulario" colspan="5">
                        	<INPUT type="text" 
                        	       id="dtReferenciaInicial" 
                        	       name="<%=PRManterReferenciaLegal.ID_REQ_DtReferenciaInicial%>"  
                        			value="<%=Data.toHTMLComMascara(dtReferenciaInicial, false)%>"
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="<%=Data.TAMANHO_COM_MASCARA%>" > a
                        	<INPUT type="text" 
                        		id="dtReferenciaFinal" 
	                        	name="<%=PRManterReferenciaLegal.ID_REQ_DtReferenciaFinal%>"  
                        		value="<%=Data.toHTMLComMascara(dtReferenciaFinal, false)%>"  
                        		onkeyup="formatarCampoData(this, event, false);" 
                        		class="camponaoobrigatorio" 
                        		size="10" maxlength="<%=Data.TAMANHO_COM_MASCARA%>" ></TD>
                    </TR>
                    <TR>
                        <TH class="campoformulario" width="18%">Período de Realização:</TH>
                        <TD class="campoformulario" colspan="5">
                        	<INPUT type="text" 
                        	       id="dtReferenciaInicial" 
                        	       name="<%=PRManterReferenciaLegal.ID_REQ_DtRealizacaoInicial%>"  
                        			value="<%=Data.toHTMLComMascara(dtRealizacaoInicial, false)%>"
                        			onkeyup="formatarCampoData(this, event, false);" 
                        			class="camponaoobrigatorio" 
                        			size="10" 
                        			maxlength="<%=Data.TAMANHO_COM_MASCARA%>" > a
                        	<INPUT type="text" 
                        		id="dtReferenciaFinal" 
	                        	name="<%=PRManterReferenciaLegal.ID_REQ_DtRealizacaoFinal%>"  
                        		value="<%=Data.toHTMLComMascara(dtRealizacaoFinal, false)%>"  
                        		onkeyup="formatarCampoData(this, event, false);" 
                        		class="camponaoobrigatorio" 
                        		size="10" maxlength="<%=Data.TAMANHO_COM_MASCARA%>" ></TD>
                    </TR>                                         
		    		<TR>
		    	        <TH class="campoformulario" width="172">Vigência:</TH>
                        <TD class="campoformulario" colspan="4"><%=RadioButtonTipoVigencia.getInstancia().getHTML(request, false, true, PRManterReferenciaLegal.ID_REQ_CdTipoVigencia, "cdTipoVigencia", cdTipoVigencia, "onchange=habilitarDesativos(this);")%>
                        </TD>
				    </TR>                                         
		    		<TR>
					<TH class="campoformulario" width="18%">Desativado:</TH>
						<TD class="campoformulario" colspan="3">
							<%=RadioButtonIsRegistroDesativado.getInstancia().getHTML(request, PRManterReferenciaLegal.ID_REQ_OpcaoRegistroDesativado, "opcaoRegistroDesativado", opcaoRegistroDesativado)%>
						</TD>		    		
		    		</TR>		    		
                    
  		    		<TR>
                        <TH class="campoformulario" width="18%">Ordenação:</TH>
                        <TD class="campoformulario">
                        	<%=SelectOrdenacaoConsultaReferenciaLegal.getInstancia().getHTML(request, PRManterReferenciaLegal.ID_REQ_NmAtributoOrdenacaoConsulta, "nmAtributoOrdenacaoConsulta", new String[] {nmAtributoOrdenacaoConsulta})%> 
                        	<%=RadioButtonOrdenacaoConsulta.getInstancia().getHTML(request, false, PRManterReferenciaLegal.ID_REQ_TpOrdenacaoConsulta, "tpOrdenacaoConsulta", tpOrdenacaoConsulta, "" )%>
							<TD class="campoformulario" width="35%"><%=BotaoLocalizar.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET, true)%></TD>
		    		</TR>
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
                        <TH class="headertabeladados" width="3%">&nbsp;&nbsp;X</TH>
                        <TH class="headertabeladados" width="5%" >Código</TH>
                        <TH class="headertabeladados" width="50%" >Descrição</TH>
                        <TH class="headertabeladados" width="10%" >Situação</TH>
                        <TH class="headertabeladados" width="10%" >Início de Vigência</TH>
                        <TH class="headertabeladados" width="10%" >Fim de Vigência</TH>
                        <TH class="headertabeladados" nowrap width="15%">Realização</TH>                                      
                    </TR>
                <% 
                        VOGenerico voAtual; 
                        String chavePrimaria = "";
                        Short auxCdReferenciaLegal = null;
                        Integer auxSqFinalidadeReferenciaLegal = null;
                        String auxDtRealizacao = "";

                        while (dados.hasNext()) {
                        	voAtual = (VOGenerico)dados.next();
                        	
                        	//Trecho de código responsável pela descrição da situação.
							String cdUsuario = (String) voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_CdUsuarioDesativado);
							String situacao = "";
							
							if(cdUsuario != null){
								situacao = ConstantesGFU.CD_SITUACAO_INATIVO;
							}else{
								situacao = ConstantesGFU.CD_SITUACAO_ATIVO;
							}
                        	
                        	chavePrimaria = voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_CdReferenciaLegal) 
                        	  + ConstantesGFU.CD_CAMPO_SEPARADOR + voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_SqReferenciaLegal)
                              + ConstantesGFU.CD_CAMPO_SEPARADOR + voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DsReferenciaLegal) 
                              + ConstantesGFU.CD_CAMPO_SEPARADOR + voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DtInicioVigenciaReferenciaLegal) 
                              + ConstantesGFU.CD_CAMPO_SEPARADOR + voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DtFimVigenciaReferenciaLegal)
                        	  + ConstantesGFU.CD_CAMPO_SEPARADOR + ((Timestamp)voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DhUltimaAlteracao)).getTime() 
                        	  + ConstantesGFU.CD_CAMPO_SEPARADOR + ((Timestamp)voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DhUltimaAlteracao)).getNanos()
                        	  + ConstantesGFU.CD_CAMPO_SEPARADOR + situacao;
                        	  
							auxDtRealizacao = BibliotecaFuncoesDataHora.getTimestampComoDataString(
									(Timestamp)voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DhUltimaAlteracao));                        	  
                        	%>
                    <TR>
                        <TD class="tabeladados">
                        <% 
                        	auxCdReferenciaLegal = new Short(((Integer) voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_CdReferenciaLegal)).shortValue());
                        	auxSqFinalidadeReferenciaLegal = (Integer) voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_SqReferenciaLegal);
                        	if (voSelecionado != null && auxCdReferenciaLegal.equals(voSelecionado.getCdReferenciaLegal()) && auxSqFinalidadeReferenciaLegal.equals(voSelecionado.getSqReferenciaLegal())) {
                        %>  <INPUT type="radio" id="rdb_consulta" name="<%=PRManterReferenciaLegal.ID_REQ_CHAVE_PRIMARIA%>" value="<%=chavePrimaria%>"          
                                     checked> <%
                        	} else {
                        %> <INPUT type="radio" id="rdb_consulta" name="<%=PRManterReferenciaLegal.ID_REQ_CHAVE_PRIMARIA%>" value="<%=chavePrimaria%>"> <%
                        	}
                        %></TD>
                        <TD class="tabeladados"><%=BibliotecaFuncoesPrincipal.toHTML(voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_CdReferenciaLegal), true)%></TD>
                        <TD class="tabeladados"><%=TextoCaixaAlta.toHTMLComMascara(voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DsReferenciaLegal), true)%></TD>
                        <TD class="tabeladados"><%=Texto.toHTMLComMascara(situacao, true)%></TD>
                        <TD class="tabeladados"><%=Data.toHTMLComMascara(voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DtInicioVigenciaReferenciaLegal), true)%></TD>
                        <TD class="tabeladados"><%=Data.toHTMLComMascara(voAtual.getAtributo(FiltroManterReferenciaLegal.NM_COL_DtFimVigenciaReferenciaLegal), true)%></TD>
                        <TD class="tabeladados"><%=Data.toHTMLComMascara(auxDtRealizacao, true)%></TD>                        
                    </TR>
                <%
                        }
                        	%>
                </TBODY>
            </TABLE>
            </DIV>
            </TD>
        </TR>
        <TR>
            <TD class="conteinerbarrapaginacao"><%=GeradorBarraPaginacao.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET, otdRespostaConsulta)%></TD>
        </TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>   
	                    	    <TD class="botaofuncao"><%=BotaoDetalhar.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET)%></TD>
		                        <TD class="botaofuncao"><%=BotaoIncluir.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET)%></TD>
		                        <TD class="botaofuncao"><%=BotaoAlterar.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET)%></TD>
		                        <TD class="botaofuncao"><%=BotaoExcluir.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET)%></TD>
		                        <TD class="botaofuncao"><%=BotaoGenerico.getInstancia().getHTML(request, "javascript:desativarRegistro",ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRManterReferenciaLegal.NM_SERVLET, PRManterReferenciaLegal.EVENTO_EXIBIR_DESATIVACAO_REGISTRO, BotaoGenerico.CD_TAM_BOTAO_PEQUENO, "btt_desativarRegistro", "Desati&var")%></TD>
	            	            <TD class="botaofuncao"><%=BotaoHistorico.getInstancia().getHTML(request, ConstantesGFU.NM_CONTEXTO_WEB_FIN_GFU, PRConsultarHistoricoReferenciaLegal.NM_SERVLET, PRConsultarHistoricoReferenciaLegal.EVENTO_EXIBIR_FILTRO_CONSULTA, BotaoGenerico.CD_TAM_BOTAO_MEDIO)%></TD>
		                        <TD class="botaofuncao" id="button_fechar"><%=BotaoFechar.getInstancia().getHTML(request)%></TD>
		                        <TD class="botaofuncao" id="button_selecionar"><%=BotaoSelecionar.getInstancia().getHTML(request)%></TD>
						    </TR>
		                    </TABLE>
	                    </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
        <%=GeradorMolduraRodape.getInstancia().getHTML(request)%>
    </TBODY>
</TABLE>
</FORM>

<%
} catch(Exception e) {
	ExcecaoGenerica excecao = new ExcecaoPaginaJSP("Erro fatal na página " + this.getClass().getName(), e);
	throw excecao;
}
%>

</BODY>
</HTML>
