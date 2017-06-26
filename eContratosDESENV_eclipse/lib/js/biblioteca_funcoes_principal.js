/*
 Descrição:
 - Contém todas as mensagens e funções de apoio utilizadas pelas 
   demais bibliotecas
 - Deve ser incluída em todas as JSPs

 Dependências:
 - nenhuma
*/

// Constantes
TAMANHO_CODIGOS = 5;
TAMANHO_CODIGOS_DOCUMENTOS = 3;
CD_CAMPO_SUBSTITUICAO = "[[*]]"; // Deve ser igual à constante br.gov.pe.sefaz.sfi.util.Constantes.CD_CAMPO_SUBSTITUICAO
CD_CAMPO_SUBSTITUICAO_MSGUSR = "#?#"; // Deve ser igual à constante br.gov.pe.sefaz.sfi.util.Constantes.CD_CAMPO_SUBSTITUICAO_MSGUSR

//CD_CAMPO_SEPARADOR = "[[*]]";
CD_CAMPO_SEPARADOR = "*";
CD_CAMPO_SEPARADOR_AUX = "[[**]]";

CD_CARACTERE_CORINGA_BD = '%';
CD_CARACTERE_CORINGA_USR = '*';
CD_CARACTERE_CORINGA_SIMPLES_USR = '?';

CD_OPCAO_NENHUM = "opcao_nenhum";
CD_OPCAO_TODOS = "opcao_todos";

CD_VERDADEIRO = "S";
CD_FALSO = "N";

NM_JANELA_AUXILIAR = "janelaAuxiliar";
ID_LINK_FRAMEWORK = "lnkFramework";
ID_BOTAO_LIMPARCAMPO = "btt_limparcampo";
IN_CONTENT_TYPE_APP_OCTETSTREAM = "in_content_type_app_octetstream";

TP_DISPOSITIVO_ACESSO_DESKTOP = "D";
TP_DISPOSITIVO_ACESSO_TABLET = "T";
TP_DISPOSITIVO_ACESSO_CELULAR = "C";

ID_REQ_EVENTO = "evento";
ID_REQ_ID_CONTEXTO_SESSAO = "id_contexto_sessao";
ID_REQ_ID_SESSAO = "id_sessao";
ID_REQ_CD_USUARIO = "cd_usuario";
ID_REQ_CD_MENU = "cd_menu";
ID_REQ_IN_AMBIENTE_TESTE_SELENIUM = "in_ambiente_teste_selenium";
ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO = "cd_tipo_rede_acesso_para_simulacao";
ID_REQ_NM_PATH_SERVLET_ANTERIOR = "nm_path_servlet_anterior";
ID_REQ_IN_JANELA_AUXILIAR = "in_janela_auxiliar";
ID_REQ_IN_SEM_BOTAO_SELECIONAR_JANELA_AUXILIAR = "in_sem_botao_selecionar_janela_auxiliar";
ID_REQ_IN_FORMULARIO_SUBMETIDO = "in_formulario_submetido";
ID_REQ_CD_ANCORA = "cd_ancora";
ID_REQ_NM_CAMPO_FOCO_ATUAL = "nm_campo_foco_atual";
ID_REQ_DT_HOJE = "dt_hoje_framework";
ID_REQ_HR_HOJE = "hr_hoje_framework";
ID_REQ_QT_REGISTROS_PAGINA = "qt_registros_pagina";
ID_REQ_IN_LIMPAR_UNIDADE_ALOCACAO_SELECIONADA = "in_limpar_unidade_alocacao_selecionada"; // Deve ser igual à constante br.gov.pe.sefaz.sfi.util.web.ProcessadorRequisicao.ID_REQ_IN_LIMPAR_UNIDADE_ALOCACAO_SELECIONADA
ID_REQ_NM_DISPOSITIVO_ACESSO = "nm_dispositivo_acesso";
ID_REQ_TP_DISPOSITIVO_ACESSO = "tp_dispositivo_acesso";

NM_PR_MONTAR_MENU_ACESSO="PRMontarMenuAcesso";

// Variáveis
var isNS4 = (document.layers) ? true : false;
var isNS6 = (!document.all && document.getElementById) ? true : false;
var isNS71	= isNS6 && ((typeof document.designMode) != "undefined");
var isIE4 = (document.all && !document.getElementById) ? true : false;
var isIE5 = (document.all && document.getElementById) ? true : false;
var isIE = (navigator.userAgent.indexOf("MSIE") != -1) ? true : false;
var isChrome = (navigator.userAgent.indexOf("Chrome") != -1) ? true : false;
var isOPR = (navigator.userAgent.indexOf("Opera") != -1) ? true : false;

window.onunload = tratarUnload;
window.onerror = tratarErroFatal;

// Integração com a biblioteca jsTrace.js
// Para utilizar, basta importar jsTrace.js na JSP e invocar a função trace em qualquer ponto do código.
// Exemplo: trace("Chamou evento onclick");
var trace;
if (typeof(jsTrace) != 'undefined'){
	trace = function(msg) {
		jsTrace.send(msg);
	};
} else {
  trace = function(){};
}

// Funcao "dummy"
function dummy() {
	return;
}

// Exibe uma mensagem pop-up no browser
function exibirMensagem(pMensagem, pFuncaoCallBack) {
	if (pFuncaoCallBack == null) {
		alert(pMensagem);
	} else {
		
		var mbxMensagemAlerta = {
				type: "alert",
				title: "Alerta",
				text: pMensagem,
				drag: true,
				modal: true,
				width: 600,
				top: 100,
				eventHandler: pFuncaoCallBack	
			};
		var md = new ModalWindow();
		md.open(mbxMensagemAlerta);
	}
}

function tratarErroFatal(pMsg, pURL, pLinha) {
	if ((isNS4 || isNS6 || isNS71) && (pLinha == 0) && (pMsg == "setting a property that has only a getter")) {
		// Desprezar erro que ocorre no Firefox, ao definir window.onerror, dentro do ambiente de teste do Selenium
		return;
	}

	campoIndicadorJanelaAuxiliar = eval("document.frm_principal." + ID_REQ_IN_JANELA_AUXILIAR);
	if (campoIndicadorJanelaAuxiliar != null && campoIndicadorJanelaAuxiliar.value == CD_VERDADEIRO) {
		exibirMensagem(mensagemGlobal(8) + "\n" + mensagemGlobal(10) + "\n\nMensagem:" + pMsg + "\nLinha:" + pLinha);
	} else {
		exibirMensagem(mensagemGlobal(8) + "\n" + mensagemGlobal(9) + "\n\nMensagem:" + pMsg + "\nLinha:" + pLinha);
	}
}

function tratarUnload() {
	ocultarMensagemAguarde();
}

function limparCampoFormularioEConti(element){
	if(element.type == 'select-one'){
		element[0].selected = true;
	}else if(element.type == 'checkbox'){
		element.checked = false;				
	}else {		
		element.value='';
	}
}

function validaFormRequiredCheckBox(campoCheckBoxValidacao, colecaoIDCampos){
	pIsRequired = !campoCheckBoxValidacao.checked;
	tornarRequiredCamposColecaoFormulario(colecaoIDCampos, pIsRequired);
}

function tornarRequiredCamposColecaoFormulario(colecaoIDCampos, pIsRequired){	
	for(i=0;i<colecaoIDCampos.length;i++){					
		id = colecaoIDCampos[i];		
		element = document.getElementById(id);
		element.required = pIsRequired;
	}
}

function limparCamposColecaoFormulario(colecaoIDCampos){
	
	for(i=0;i<colecaoIDCampos.length;i++){					
		id = colecaoIDCampos[i];		
		element = document.getElementById(id);		
		limparCampoFormularioEConti(element);
	}
}

function limpaCampoDiv(pNmCampoDiv){
	objectResult = document.getElementById(pNmCampoDiv);
	objectResult.innerHTML = "";
}

function limparFormularioGeral(){	

	frm_principal.reset();
	for(i=0;i<frm_principal.length;i++){		
					
		element = frm_principal.elements[i];		
		//retira os campos que nao serao resetados 
		naoValidar = element.name == "cdAtrOrdenacao" 
						|| element.name == "qtdRegistrosPorPag" 
						|| element.name == "utilizarSessao"
						|| element.name == "consultar"
						|| element.name == "cdHistorico"							
						|| element.name == "cdOrdenacao"; 
		if(!naoValidar){
			//alert(element.type);
			limparCampoFormularioEConti(element);
		}/*else{
			alert("tipo:" + element.type + " - nome:" + element.id);
		}*/
		
	}
}

// Confirma a execução de uma operação. Se pTexto for nulo, será exibida uma mensagem padrão de confirmação.
function solicitarConfirmacao(pTexto, pFuncaoCallBack) {
	var mensagem;

	if (pTexto == null) {
		mensagem = mensagemGlobal(3);
	} else {
		mensagem = pTexto;
	}	

	if (pFuncaoCallBack == null){
		resposta = window.confirm(mensagem);
		return resposta;
	} else {
		var mbxMensagemConfirmacao = {
				type: "confirm",
				title: "Confirmação",
				text: mensagem,
				drag: true,
				modal: true,
				width: 600,
				top: 100,
				eventHandler: pFuncaoCallBack	
			};
		var md = new ModalWindow();
		md.open(mbxMensagemConfirmacao);
	}
}

// Verifica se um elemento HTML é válido
function isElementoValido(pNmElemento) {
	var elemento = eval(pNmElemento);

	if (elemento == null) {
		return false;
	} else {
		return true;
	}
}

// Retorna a quantidade de itens com determinado id.
function getQtElementos(pIdElemento) {
	r = eval(pIdElemento);

	// não existe nenhum elemento com o id informado
	if (r == null) {
		return 0;
	} else {
		if (r.length == null) {
			return 1;
		} else {
			return r.length;
		}
	}
}

// Verifica se o campo está vazio
function isCampoVazio(pCampo, pComMensagem) {
	if (trim(pCampo.value) == "") {
		if (pComMensagem) {
			pCampo.select();
			exibirMensagem(mensagemGlobal(2));
			pCampo.focus();
		}
		return true;
	} else {
		return false;
	}
}

// Verifica se o campo tem coringa
function isCampoComCoringa(pCampo) {
	var str = pCampo.value;

	if (str.indexOf(CD_CARACTERE_CORINGA_BD) > -1 ||
		str.indexOf(CD_CARACTERE_CORINGA_USR) > -1) {
		return true;
	} else {
		return false;
	}
}

// Verifica se a tecla digitada trata-se de uma tecla funcional
function isTeclaFuncional(pEvento, pIgnorarBackSpace, pIgnorarDelete) {
	var retorno = false;

	if (pEvento != null) {
		keyCode = pEvento.keyCode;
		//alert(keyCode);
		
		switch(keyCode) {
			case 8  : // backspace
				if (pIgnorarBackSpace) {
					retorno = false;
				} else {
					retorno = true;
				}
				break; 
			case 9  : retorno = true;break; //
			case 13 : retorno = true;break; //enter
			case 16 : retorno = true;break; //
			case 18 : retorno = true;break; //alt
			case 17 : retorno = true;break; //
			case 27 : retorno = true;break; //esc
			case 35 : retorno = true;break; //
			case 36 : retorno = true;break; //
			case 37 : retorno = true;break; //
			case 38 : retorno = true;break; //
			case 39 : retorno = true;break; //
			case 40 : retorno = true;break;	//
			case 46 : // delete
				if (pIgnorarDelete) {
					retorno = false;
				} else {
					retorno = true;
				}
		}
	}

	return retorno;
}

function isBotaoFramework(pBotao) {
	nome = pBotao.name;
	id = pBotao.id;
	
	if (nome == "btt_reset" ||
		nome == "btt_filtro" ||
		nome == "btt_favoritos" ||
		nome == "btt_help" ||
		nome == "btt_encerrarsessao" ||
		nome == "btt_menuprincipal" ||
		id == "btt_combo_favoritos") {
		return true;
	} else {
		return false;
	}
}

function isTeclaAtalhoFramework(pTecla) {
	if (pTecla == "1" ||
		pTecla == "2" ||
		pTecla == "3" ||
		pTecla == "4" ||
		pTecla == "u" ||
		pTecla == "q" ||
		pTecla == "x") {
		return true;
	} else {
		return false;
	}
}

// Coloca o foco no campo pCampo
function focarCampo(pCampo){
    if ((!isAcessoMovel()) && (pCampo != null) && (pCampo != "undefined") && (pCampo.type != "hidden") && (pCampo.readOnly != true) && (pCampo.disabled != true)) {
        pCampo.focus();
    }
}

function selecionarCampo(pCampo){
	if (!isAcessoMovel()) {
		pCampo.select();
	}
}

// Limpa um campo
function limparCampo(pCampo) {
	try {
		pCampo.value = "";
	} catch(er) {
	}
}

function habilitarCampoElementoMais(pCampoElemento, pHabilitar, pIsObrigatorio) {
	//alert(pCampoElemento.value);
	return habilitarElementoMais(pCampoElemento.id, pHabilitar, pIsObrigatorio);
}

function habilitarElementoMais(pIdElemento, pHabilitar, pIsObrigatorio) {
	//alert(pIdElemento);
	elemento = eval(pIdElemento);
	if(pHabilitar){
		habilitarElemento(pIdElemento);
	}else{
		desabilitarElemento(pIdElemento);
		elemento.value = "";
	}	
	
	if(pIsObrigatorio){		
		//elemento.className = 'campoobrigatorio';		
		elemento.required = true;
	}else{
		//elemento.className = 'camponaoobrigatorio';
		elemento.required = false;
	}
}

function desabilitarElemento(pNmElemento) {
	elemento = eval(pNmElemento);
	if (elemento != null) {
		try {
			elemento.disabled = true;			
		} catch(er) {
		}
	}
}

function habilitarElemento(pNmElemento) {
	elemento = eval(pNmElemento);
	if (elemento != null) {
		try {
			elemento.disabled = false;
		} catch(er) {
		}
	}
}


function ltrim(pString) {
	return pString.replace(/^\s*/, "")
}

function rtrim(pString) {
	return pString.replace(/\s*$/, "");
}

function trim(pString) {
	return rtrim(ltrim(pString));
}

//
// Set cookie value. Expiration date is optional
//
function setCookie(name, value, expire) {
	document.cookie = name + "=" + escape(value) + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()));
}

//
// Retrieve cookie value
//
function getCookie(name) {
	var search = name + "=";
	if (document.cookie.length > 0) { // if there are any cookies
		offset = document.cookie.indexOf(search);
		if (offset != -1) { // if cookie exists
			offset += search.length;
			// set index of beginning of value
			end = document.cookie.indexOf(";", offset);
			// set index of end of cookie value
			if (end == -1) {
				end = document.cookie.length;
			}
			return unescape(document.cookie.substring(offset, end));
		}
	}
}

function getProtocoloWeb() {
	var url = window.location.toString();

	return url.substring(0, url.indexOf(":"))
}

function getNmHost() {
	var url = window.location.toString();
	var lArray = url.split("/");
	var lNmHost = lArray[2];

	lArray = lNmHost.split(":");
	lNmHost = lArray[0];
	
	return lNmHost;
}

function getNmPR() {
	var url = window.location.toString();
	var lArray = url.split("/");
	var lNmPR = lArray[4];

	return lNmPR;
}

function isAmbienteDesenvolvimento() {
	var url = window.location.toString();
	var resultado = false;
	
	if (url.substring(0, url.indexOf("8080")) || 
	    url.substring(0, url.indexOf("oceania")) || 
	    url.substring(0, url.indexOf("europa")) || 
	    url.substring(0, url.indexOf("asia")) || 
	    url.substring(0, url.indexOf("192.168"))) {
		resultado = true;
	}

	return resultado;
}

function montarURL(pNmContextoWeb, pNmPR, pQueryString, pNmHost) {
	var url = "";

	if (pNmHost == null || pNmHost == "") {
		url = "/" + pNmContextoWeb + "/" + pNmPR;
	} else {
		url = pNmHost + "/" + pNmContextoWeb + "/" + pNmPR;
	}
	
	if (pQueryString != null && pQueryString != "") {
		url = url + "?" + pQueryString;
	}

    return url;
}

function adicionarQueryStringURL(pUrl, pQueryString) {
	var url = "";
	
	if (pUrl.lastIndexOf("?") == -1) {
		caracterSeparador = "?";
	} else {
		caracterSeparador = "&";
	}

	url = pUrl + caracterSeparador + pQueryString;

	return url;
}

function getJanelaAuxiliar(pUrlJanela, pNmJanela, pInComToolBar) {
	if (isAcessoMovel()) {
		_abrirJanelaAuxiliarMovel(pUrlJanela, pNmJanela);
		return null;
	}
	
	var inComToolBar = "no";
	
	if (pInComToolBar) {
		inComToolBar = "yes";
	}
	
	var largura = screen.availWidth - 12; //720
	//var largura = screen.availWidth - 300; 

	var altura;
	if (!pInComToolBar) {
		altura = screen.availHeight - 51; //500
		//altura = screen.availHeight - 100; 
	} else {
		altura = screen.availHeight - 198; //98 fica igual a !pInComToolBar 
		//altura = screen.availHeight - 300; 
	}
	largura = 850;
	altura = 500;
	
	var janelaAuxiliar = window.open(pUrlJanela, pNmJanela, "width=" + largura + ", height=" + altura +", menubar=no, scrollbars=yes, toolbar=" + inComToolBar +", status=yes, resizable=yes, left=0, top=0, screenX=0, screenY=0");

	return janelaAuxiliar;
}

function getWindowOpener() {
	var janelaSuperior = null;
	
	if (isAcessoMovel()) {
		janelaSuperior = window.parent;
	} else {
		janelaSuperior = window.opener;
	}
	
	if (janelaSuperior && janelaSuperior === window) {
		janelaSuperior = null;
	}
	
	return janelaSuperior;
}

function fecharJanela() {
	var janelaSuperior = getWindowOpener();
	
	if (janelaSuperior != null && isAcessoMovel()) {
		janelaSuperior._fecharJanelaAuxiliarMovel();
		return;
	}
	
	window.close();
}

function getJanelaAuxiliarMovel() {
	return document.getElementById("janelaAuxiliarMovel");
}

var _janelaAuxiliarMovelElementos = null;

function _fecharJanelaAuxiliarMovel() {
	var janelaAuxiliar = getJanelaAuxiliarMovel();
	
	if (janelaAuxiliar == null) {
		return;
	}

	var body = document.body;

	if (_janelaAuxiliarMovelElementos != null) {
		for (var i = 0, j = _janelaAuxiliarMovelElementos.length; i < j; i++) {
			var dadosOriginaisElem = _janelaAuxiliarMovelElementos[i];
	
			dadosOriginaisElem[0].style.display = dadosOriginaisElem[1];
		}
		
		_janelaAuxiliarMovelElementos = null;
	}
	
	body.removeChild(janelaAuxiliar);
}

function _abrirJanelaAuxiliarMovel(pUrlJanela, pNmJanela) {
	var janelaAuxiliar = getJanelaAuxiliarMovel();
	
	if (janelaAuxiliar !== null) {
		_fecharJanelaAuxiliarMovel();
	}
	
	exibirMensagemAguardeAjax();

	janelaAuxiliar = document.createElement("iframe");
	janelaAuxiliar.src = pUrlJanela;
	janelaAuxiliar.name = pNmJanela;
	janelaAuxiliar.id = "janelaAuxiliarMovel";
	janelaAuxiliar.style.visibility = "hidden"
	
	var body = document.body;
	var bodyElems = body.children;
	
	janelaAuxiliar.onload = function() {
		if (_janelaAuxiliarMovelElementos !== null) {
			// Tratamento para o caso de refresh da página.
			return;
		}
		
		_janelaAuxiliarMovelElementos = [];
		
		// É necessário esconder todos os elementos porque o navegador nativo do Android 2.3
		// não suporta definir "overflow" (utilizado no document.documentElement).
		for (var i = 0, j = bodyElems.length; i < j; i++) {
			var elem = bodyElems[i];
			if (elem === janelaAuxiliar) continue;

			var elemStyle = elem.style;
			
			_janelaAuxiliarMovelElementos.push([ elem, elemStyle.display ]);
			
			elemStyle.display = "none";
		}
		
		ocultarMensagemAguardeAjax();
		
		this.style.background = "white";
		
		janelaAuxiliar.style.visibility = ""
	};

	body.appendChild(janelaAuxiliar);
}

var _nmJanelaAuxiliar = NM_JANELA_AUXILIAR;

function abrirJanelaAuxiliar(pUrlJanela, pInSubmeterCamposTextFormulario, pInComToolBar, pInSemParametrosFramework) {
	if (getWindowOpener() != null) {
		var timeStamp = new Date();
		_nmJanelaAuxiliar = _nmJanelaAuxiliar + timeStamp.getTime();
	}
		
	var idSessao = "";
	var cdUsuario = "";
	var cdMenu = "";
	
	campoIdSessao = eval("document.frm_principal." + ID_REQ_ID_SESSAO);
	if (campoIdSessao != null) {
		idSessao = campoIdSessao.value;
	}

	campoCdMenu = eval("document.frm_principal." + ID_REQ_CD_MENU); 
	if (campoCdMenu != null) {
		cdMenu = campoCdMenu.value;
	}

	campoCdUsuario = eval("document.frm_principal." + ID_REQ_CD_USUARIO);
	if (campoCdUsuario != null) {
		cdUsuario = campoCdUsuario.value;
	}

	campoCdTipoRedeAcessoSimulacao = eval("document.frm_principal." + ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO);
	if (campoCdTipoRedeAcessoSimulacao != null) {
		cdTipoRedeAcessoSimulacao = campoCdTipoRedeAcessoSimulacao.value;
	}

	var caracterSeparador = null;
	if (pUrlJanela.lastIndexOf("?") == -1) {
		caracterSeparador = "?";
	} else {
		caracterSeparador = "&";
	}
	
	var urlJanela = pUrlJanela + caracterSeparador + "_nmJanelaAuxiliar=" + _nmJanelaAuxiliar + "&lupa=S"; 

	if (!pInSemParametrosFramework) {
		urlJanela += "&" + ID_REQ_IN_JANELA_AUXILIAR + "=S" + "&" +
			ID_REQ_ID_SESSAO + "=" + idSessao + "&" + 
			ID_REQ_CD_USUARIO + "=" + cdUsuario;
		
		if (campoCdTipoRedeAcessoSimulacao != null) {	
			urlJanela = urlJanela + "&" + ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO + "=" + cdTipoRedeAcessoSimulacao;
		}
		
		var tpDispositivoAcesso = getTpDispositivoAcesso();
		if (tpDispositivoAcesso != null) {	
			urlJanela += "&" + ID_REQ_TP_DISPOSITIVO_ACESSO + "=" + tpDispositivoAcesso;
		}
		
		var nmDispositivoAcesso = getNmDispositivoAcesso();
		if (nmDispositivoAcesso != null) {	
			urlJanela += "&" + ID_REQ_NM_DISPOSITIVO_ACESSO + "=" + nmDispositivoAcesso;
		}
		
		var queryString = "";
		if (pInSubmeterCamposTextFormulario == true) {
			queryString = "&";
			elementos = document.forms.item(0).elements;
			for (var i = 0; i < elementos.length; i++) {
				if (elementos.item(i).type == "text") {
					queryString = queryString + elementos.item(i).name + "=" + elementos.item(i).value + "&";
				}
			}
		}

		urlJanela += queryString;
	}

	var janelaAuxiliar = getJanelaAuxiliar(urlJanela, _nmJanelaAuxiliar, pInComToolBar);
	if (janelaAuxiliar != null) {
		janelaAuxiliar.focus();
	}
}

function alterarUrlJanelaPrincipal(pUrlJanela, pInSubmeterCamposTextFormulario) {

	var idSessao = "";
	var cdUsuario = "";
	var cdMenu = "";

	campoIdSessao = eval("document.frm_principal." + ID_REQ_ID_SESSAO);
	if (campoIdSessao != null) {
		idSessao = campoIdSessao.value;
	}

	campoCdMenu = eval("document.frm_principal." + ID_REQ_CD_MENU); 
	if (campoCdMenu != null) {
		cdMenu = campoCdMenu.value;
	}

	campoCdUsuario = eval("document.frm_principal." + ID_REQ_CD_USUARIO);
	if (campoCdUsuario != null) {
		cdUsuario = campoCdUsuario.value;
	}

	campoCdTipoRedeAcessoSimulacao = eval("document.frm_principal." + ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO);
	if (campoCdTipoRedeAcessoSimulacao != null) {
		cdTipoRedeAcessoSimulacao = campoCdTipoRedeAcessoSimulacao.value;
	}

	var caracterSeparador = "";
	if (pUrlJanela.lastIndexOf("?") == -1) {
		caracterSeparador = "?";
	} else {
		caracterSeparador = "&";
	}
	
	var queryString = "";
	if (pInSubmeterCamposTextFormulario == true) {
		queryString = "&";
		elementos = document.forms.item(0).elements;
		for (var i = 0; i < elementos.length; i++) {
			if (elementos.item(i).type == "text") {
				queryString = queryString + elementos.item(i).name + "=" + elementos.item(i).value + "&";
			}
		}
	}
	
	var urlJanela = pUrlJanela + caracterSeparador + ID_REQ_ID_SESSAO + "=" + idSessao + "&" + 
		ID_REQ_CD_USUARIO + "=" + cdUsuario + "&" + 
		ID_REQ_CD_MENU + "=" + cdMenu;
		
	if (campoCdTipoRedeAcessoSimulacao != null) {	
		urlJanela = urlJanela + "&" + ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO + "=" + cdTipoRedeAcessoSimulacao;
	}
		
	urlJanela = urlJanela + queryString;

	window.location = urlJanela;
}

function abrirJanelaAjuda(pUrlJanela) {
	var janelaAjuda = getJanelaAuxiliar(pUrlJanela, "janelaAjuda", true);

	if (janelaAjuda != null) {
		janelaAjuda.focus();
	}
}

var mbxAguarde = {
		type: "wait",
		title: "",
		text: "",
		width: 97,
		top: 200,
		ok: false,
		modal: false,
		tooltip: ""
	};

var modalWindowAguarde = new ModalWindow();

function exibirMensagemAguarde() {
	window.status = mensagemGlobal(4);
	
	mbxAguarde.text = mensagemGlobal(4);
	mbxAguarde.tooltip = mensagemGlobal(17);
	mbxAguarde.top = document.body.scrollTop + (document.body.clientHeight / 2) - 20;
	
	modalWindowAguarde.open(mbxAguarde);
	
	setTimeout(ocultarMensagemAguarde, 120000);
}

function ocultarMensagemAguarde() {
	window.status = "";
	
	modalWindowAguarde.close();

	if (_nmBotaoAtivado != "") {
		// Habilita botão que iniciou transação
		botaoAtivado = eval("document.frm_principal." + _nmBotaoAtivado);
		if (botaoAtivado != null) {
			botaoAtivado.disabled = false;
			botaoAtivado.title = _nmTitleBotaoAtivado;
		}
	}

	// Habilita botões de ação da tela de mensagem ao usuário
	elementos = document.forms.item(0).elements;
	for (var i = 0; i < elementos.length; i++) {
		if (elementos.item(i).type == "button" &&
			elementos.item(i).name.indexOf("btt_pmu_acao_") > -1) {
			elementos.item(i).disabled = false;
		}
	}
}

var mbxAguardeAjax = {
		type: "wait",
		title: "",
		text: "",
		opacity: 0.0,
		width: 97,
		top: 200,
		ok: false,
		tooltip: ""
	};

var modalWindowAguardeAjax = new ModalWindow();
var inModalWindowAguardeAjaxVisivel = false;

function exibirMensagemAguardeAjax() {
	if (!inModalWindowAguardeAjaxVisivel) {
		mbxAguardeAjax.text = mensagemGlobal(4);
		mbxAguardeAjax.tooltip = mensagemGlobal(18);
		mbxAguardeAjax.top = document.body.scrollTop + (document.body.clientHeight / 2) - 20;
		modalWindowAguardeAjax.open(mbxAguardeAjax);
		inModalWindowAguardeAjaxVisivel = true;
	}
}

function ocultarMensagemAguardeAjax() {
	if (inModalWindowAguardeAjaxVisivel) {
		modalWindowAguardeAjax.close();
		inModalWindowAguardeAjaxVisivel = false;
	}
}

// Indica se o formulário já foi submetido. Após um history.back() está variável é sempre false.
var _inFormSubmetido = false;

// Nome e título do botão acionado no formulário.
var _nmBotaoAtivado;
var _nmTitleBotaoAtivado;

// Indica se algum botão que inicia uma transação foi ativado (Confirmar, etc...).
var _inBotaoOperacaoAtivado = false;

// Chamada quando se clica em qualquer botão do framework
// Se for o botão Confirmar, invoca setBotaoOperacaoAtivado
function setBotaoAtivado(pBotao) {
	if (pBotao == null) {
		_nmBotaoAtivado = "";
		_nmTitleBotaoAtivado = "";
		return;
	}

	_nmBotaoAtivado = pBotao.name;
	_nmTitleBotaoAtivado = pBotao.title;

	if (pBotao.name == "btt_confirmar" || pBotao.value == "Confirmar (c)") {
		setBotaoOperacaoAtivado(pBotao);
	}
}

// Verifica se existe no formulário um botão Confirmar
function existeBotaoOperacaoConfirmar() {
	var botao = document.getElementById("btt_confirmar");
	if (botao != null) {
		return true;
	} else {
		return false;
	}
}

// Deve ser chamada dentro da função JavaScript associada ao evento onClick de um botão que 
// dispara uma transação (processarXxxxxx), antes de chamar a função submeterFormulario
function setBotaoOperacaoAtivado(pBotao) {
	_inBotaoOperacaoAtivado = true;
}

// Deve ser chamada dentro da função JavaScript que submete o formulário para atualizar a página, após chamar a função submeterFormulario
// Ex.: função chamada pelo evento onchange de um select, função chamada pelo evento onblur de um text
function setFormNaoSubmetido() {
	_inFormSubmetido = false;
}

// Deve ser chamada dentro da página que é exibida quando um botão de operação é ativado e a operação é concluída com sucesso
function setOperacaoConcluidaComSucesso() {
	var today = new Date();
	var expires = new Date();
	expires.setTime(today.getTime() + 1000*60*60*24*365);
	setCookie("sfi.in_operacao_sucesso", CD_VERDADEIRO, expires);
}

// Deve ser chamada dentro da página que é exibida quando um botão de operação é ativado e a operação é concluída sem sucesso
function setOperacaoConcluidaSemSucesso() {
	var today = new Date();
	var expires = new Date();
	expires.setTime(today.getTime() + 1000*60*60*24*365);
	setCookie("sfi.in_operacao_sucesso", CD_FALSO, expires);
}

// Submete o formulario principal
function submeterFormulario(pAcao, pEvento, pInNaoExibirMensagemAguarde) {
	
	document.frm_principal.target = "";
	
	if (!pInNaoExibirMensagemAguarde) {
		exibirMensagemAguarde();
	}

	// Trata ressubmissão de formulários
	if (existeBotaoOperacaoConfirmar() || _inBotaoOperacaoAtivado) {

		var campoInFormularioSubmetido = eval("document.frm_principal." + ID_REQ_IN_FORMULARIO_SUBMETIDO);
		
		if (_inFormSubmetido) {
			if (_inBotaoOperacaoAtivado) {
				// Um botão de operação foi ativado mas a requisição foi interrompida pelo usuário.

				var inPermitirRessubmissaoFormularioAposInterrupcao = true;
				try {
					inPermitirRessubmissaoFormularioAposInterrupcao = isRessubmissaoFormularioAposInterrupcaoPermitida();
				} catch(er) {
				}

				if (inPermitirRessubmissaoFormularioAposInterrupcao) {
					if (!solicitarConfirmacao(mensagemGlobal(5))) {
						document.body.style.cursor = "default";
						ocultarMensagemAguarde();
						_inBotaoOperacaoAtivado = false;
						return;
					}
				} else {
					exibirMensagem(mensagemGlobal(19));
					document.body.style.cursor = "default";
					ocultarMensagemAguarde();
					_inBotaoOperacaoAtivado = false;
					return;
				}
			} else {
				exibirMensagem(mensagemGlobal(6));
			}
		
		} else {
			
			if (campoInFormularioSubmetido != null) {
				var inFormularioSubmetido = campoInFormularioSubmetido.value;
		
				if (inFormularioSubmetido == CD_VERDADEIRO && _inBotaoOperacaoAtivado) {
					// O formulário já foi submetido anteriormente e um botão de operação foi ativado na mesma página.
					
					var inOperacaoSucesso = getCookie("sfi.in_operacao_sucesso");
					
					if (inOperacaoSucesso == CD_VERDADEIRO) {
						// A operação anterior foi concluída com sucesso.

						var inPermitirRessubmissaoFormulario = true;
						try {
							inPermitirRessubmissaoFormulario = isRessubmissaoFormularioPermitida();
						} catch(er) {
						}
						
						if (inPermitirRessubmissaoFormulario) {
							if (!solicitarConfirmacao(mensagemGlobal(7))) {
								document.body.style.cursor = "default";
								ocultarMensagemAguarde();
								_inBotaoOperacaoAtivado = false;
								return;
							}

						} else {
							exibirMensagem(mensagemGlobal(14));
							document.body.style.cursor = "default";
							ocultarMensagemAguarde();
							_inBotaoOperacaoAtivado = false;
							return;
						}
					}
				}
			}
		}

		_inBotaoOperacaoAtivado = false;
		_inFormSubmetido = true;
		if (campoInFormularioSubmetido != null) {
			campoInFormularioSubmetido.value = CD_VERDADEIRO;
		}
	}

	document.frm_principal.action = pAcao;

	evento = eval("document.frm_principal.evento");
	if (evento != null) {
		evento.value = pEvento;
	}

	document.frm_principal.submit();
	
	if (_nmBotaoAtivado != "") {
		botaoAtivado = eval("document.frm_principal." + _nmBotaoAtivado);
		if (botaoAtivado != null) {
			botaoAtivado.disabled = true;
		}
	}
}

// Submete o formulario principal
function submeterFormularioJanelaAuxiliar(pAcao, pEvento, pInNaoAbrirNovaJanelaAuxiliar) {

	if (!pInNaoAbrirNovaJanelaAuxiliar) {
		if (getWindowOpener() != null || pInNaoAbrirNovaJanelaAuxiliar === null) {
			var timeStamp = new Date();
			_nmJanelaAuxiliar = _nmJanelaAuxiliar + timeStamp.getTime();
		}
	}

	var janelaAuxiliar = getJanelaAuxiliar("", _nmJanelaAuxiliar);

	var caracterSeparador = "";
	if (pAcao.lastIndexOf("?") == -1) {
		caracterSeparador = "?";
	} else {
		caracterSeparador = "&";
	}
	
	document.frm_principal.target = _nmJanelaAuxiliar;
	document.frm_principal.action = pAcao + caracterSeparador + ID_REQ_IN_JANELA_AUXILIAR + "=" + CD_VERDADEIRO + "&_nmJanelaAuxiliar=" + _nmJanelaAuxiliar;

	evento = eval("document.frm_principal.evento");
	if (evento != null) {
		evento.value = pEvento;
	}

	naoUtilizarIdContextoSessao = eval("document.frm_principal.nao_utilizar_id_contexto_sessao");
	if (naoUtilizarIdContextoSessao != null) {
		naoUtilizarIdContextoSessao.value = CD_VERDADEIRO;
	}

	document.frm_principal.submit();
}

function ativarItemMenu(pAcao, pCdMenu, pInJanelaAuxiliar, pInComParametrosFramework) {
	elementos = document.forms.item(0).elements;
	for (var i = 0; i < elementos.length; i++) {
		if (elementos.item(i).type == "hidden"
			|| elementos.item(i).type == "text"
			|| elementos.item(i).type == "password"
			|| elementos.item(i).type == "file"
			|| elementos.item(i).type == "textarea"
			|| elementos.item(i).type == "radio"
			|| elementos.item(i).type == "select-one"
			|| elementos.item(i).type == "select-multiple") {
			if (elementos.item(i).name != ID_REQ_ID_SESSAO &&
				elementos.item(i).name != ID_REQ_CD_USUARIO &&
				elementos.item(i).name != ID_REQ_NM_PATH_SERVLET_ANTERIOR &&
				elementos.item(i).name != ID_REQ_IN_AMBIENTE_TESTE_SELENIUM &&
				elementos.item(i).name != ID_REQ_CD_TIPO_REDE_ACESSO_PARA_SIMULACAO &&
				elementos.item(i).name != ID_REQ_TP_DISPOSITIVO_ACESSO &&
				elementos.item(i).name != ID_REQ_NM_DISPOSITIVO_ACESSO) {
				elementos.item(i).value = "";
			}
		}
	}

	campoCdMenu = eval("document.frm_principal." + ID_REQ_CD_MENU); 
	if (campoCdMenu != null) {
		campoCdMenu.value = pCdMenu;
	}
	
	if (isURLServlet(pAcao)) {
		if (pInJanelaAuxiliar) {
			submeterFormularioJanelaAuxiliar(pAcao, "");
		} else {
			submeterFormulario(pAcao, "");
		}
	} else {
		if (pInJanelaAuxiliar) {
			if (pInComParametrosFramework) {
				abrirJanelaAuxiliar(pAcao, false, true, false);
			} else {
				abrirJanelaAuxiliar(pAcao, false, true, true);
			}
		} else {
			window.location = pAcao;
		}
	}
}

function isURLServlet(pAcao) {
	if (pAcao.indexOf("/sfi_") > -1) {
		return true;
	} else {
		return false;
	}
}

function limparCampoHistorico() {
	campoHistorico = eval("document.frm_principal.historico"); 
	if (campoHistorico != null) {
		campoHistorico.value = "";
	}
}

// Encerra Sessão (utilizada pelo GerenciadorMolduraCabecalho)
function encerrarSessao(pAcao) {
	if (solicitarConfirmacao(mensagemGlobal(11))) {
		submeterFormulario(pAcao, "");
	}
}

// Encerra Sessão usando certificado digital (utilizada pelo GerenciadorMolduraCabecalho)
function encerrarSessaoComCertificado(pAcao) {
	if (solicitarConfirmacao(mensagemGlobal(15))) {
		submeterFormulario(pAcao, "");
		window.open("", "_parent"); // Exigida para fechar o browser no firefox e netscape, quando este não permite fechar o browser em suas configurações locais.
		window.close();
	}
}

// Limpa a unidade de alocação selecionada e persistida em memória, para quem tem mais de uma unidade de alocação
function limparUnidadeAlocacaoSelecionada(pAcao) {
	
	var cdMensagemGlobal;
	// Testa se a origem do limpar vem do Menu
	if (getNmPR()== NM_PR_MONTAR_MENU_ACESSO){
		cdMensagemGlobal = 21;
	}else{
		cdMensagemGlobal = 20;
	}
	
	if (solicitarConfirmacao(mensagemGlobal(cdMensagemGlobal))) {

		//Cria um input hidden dinamicamente.
	    var element = document.createElement("input");

	    //Atribuição dos atributos e seus valores.
	    element.setAttribute("type", "hidden");
	    element.setAttribute("name", ID_REQ_IN_LIMPAR_UNIDADE_ALOCACAO_SELECIONADA);
	    element.setAttribute("value", CD_VERDADEIRO);

	    //Adiciona o input hidden na pagina.
	    document.frm_principal.appendChild(element);

		submeterFormulario(pAcao, "");
	}
    
}

// Muda a classe do botao, quando ele for clicado
function afundarBotao(pBotao) {
	pBotao.className = pBotao.className + "flip";
}

// Muda a classe do botao, quando ele for desclicado
function levantarBotao(pBotao) {
	pBotao.className = pBotao.className.replace("flip", "");
}

// Muda a classe do botao, o mouse passar por cima do botao
function mudarBotaoOver(pBotao) {
	var novaClasse = pBotao.className.replace("flip", "").replace("out", "");
	pBotao.className = novaClasse;
}

// Muda a classe do botao, o mouse nao estiver acima do botao
function mudarBotaoOut(pBotao) {
	var novaClasse = pBotao.className.replace("flip", "").replace("out", "") + "out";
	pBotao.className = novaClasse;
}

function ocultarElemento(pIdElemento) {
	document.getElementById(pIdElemento).style.display = "none";
}

function exibirElemento(pIdElemento) {
	document.getElementById(pIdElemento).style.display = "";
}

function exibirElementoLinhaTabela(pIdLinhaTabela) {
	exibirElemento(pIdLinhaTabela);
}

function acionarBotaoSeTeclaEnterPressionada(pBotao, pEvento) {
	if (pEvento != null) {
		keyCode = pEvento.keyCode;
		if (keyCode == 13 && pBotao != null) {
			pBotao.click();
		}
	}
}

// Retorna o primeiro campo do formulário, caso exista
function getPrimeiroCampoFormulario() {
	testePrimeiroCampo = eval("document.frm_principal.primeiro_campo");
	var campo = null;
	var primeiroCampo = null;

	if (testePrimeiroCampo != null) {
		if (testePrimeiroCampo.type != "select-one" && testePrimeiroCampo.length > 0) {
			campo = testePrimeiroCampo.item(0);
		} else {
			campo = testePrimeiroCampo;
		}
		if (campo.type == "text" || campo.type == "password") {
			if (!campo.readOnly && !campo.disabled) {
				primeiroCampo = campo;
			}
		} else if (campo.type == "select-one") {
			if (!campo.disabled) {
				primeiroCampo = campo;
			}
		}
	}

	return primeiroCampo;
}

// Ativado quando se clica na borracha
function reiniciarFormulario() {
	filtro = document.getElementById("div_filtro");
	if (filtro != null) {
		// Se for uma tela de consulta com filtro, apaga todos os campos text
		document.frm_principal.reset();
		elementos = document.forms.item(0).elements;
		for (var i = 0; i < elementos.length; i++) {
			if ((elementos.item(i).type == "text" || elementos.item(i).type == "password") && elementos.item(i).readOnly != true) {
				elementos.item(i).value = "";
			} else if (elementos.item(i).type == "select-one" && elementos.item(i).name != ID_REQ_QT_REGISTROS_PAGINA) {
				if (elementos.item(i).length > 0) {
					if (elementos.item(i).length == 3 && elementos.item(i).options[1].text == "Ativo" && elementos.item(i).options[2].text == "Inativo") {
						// O SelectIndicadoAtivo deve reiniciar na opção Ativo
						elementos.item(i).selectedIndex = 1;
					} else {
						elementos.item(i).selectedIndex = 0;
					}
				}
			}
		}
	} else {
		// Se não, "reseta" o formulário
		document.frm_principal.reset();	
	}
	
	primeiroCampo = getPrimeiroCampoFormulario();

	if (primeiroCampo != null) {
		filtro = document.getElementById("div_filtro");
		if (filtro != null) {
			if (filtro.style.display != "none") {
				primeiroCampo.focus();
			}
		} else {
			primeiroCampo.focus();
		}
	}

	try {
		finalizarReinicioFormulario();
	} catch(er) {
	}
}

// Oculta ou exibe o filtro em uma tela de consulta
function chavearDisplayFiltro() {
	if (document.getElementById("div_filtro").style.display != "none") {
		document.getElementById("div_filtro").style.display = "none";

		campoIndicadorFiltroOculto = eval("document.frm_principal.indicador_filtro_oculto");
		if (campoIndicadorFiltroOculto != null) {
			campoIndicadorFiltroOculto.value = CD_VERDADEIRO;
		} else {
			campoNmCampoIndicadorFiltroOculto = eval("document.frm_principal.nm_campo_indicador_filtro_oculto");
			if (campoNmCampoIndicadorFiltroOculto != null) {
				campoIndicadorFiltroOculto = eval("document.frm_principal." + campoNmCampoIndicadorFiltroOculto.value);
				if (campoIndicadorFiltroOculto != null) {
					campoIndicadorFiltroOculto.value = CD_VERDADEIRO;
				}
			}
		}
		
		document.frm_principal.btt_filtro.title = "Exibir Filtro (2)";

	} else {
		document.getElementById("div_filtro").style.display = "";

		campoIndicadorFiltroOculto = eval("document.frm_principal.indicador_filtro_oculto");
		if (campoIndicadorFiltroOculto != null) {
			campoIndicadorFiltroOculto.value = CD_FALSO;
		} else {
			campoNmCampoIndicadorFiltroOculto = eval("document.frm_principal.nm_campo_indicador_filtro_oculto");
			if (campoNmCampoIndicadorFiltroOculto != null) {
				campoIndicadorFiltroOculto = eval("document.frm_principal." + campoNmCampoIndicadorFiltroOculto.value);
				if (campoIndicadorFiltroOculto != null) {
					campoIndicadorFiltroOculto.value = CD_FALSO;
				}
			}
		}

		primeiroCampo = getPrimeiroCampoFormulario();
		if (primeiroCampo != null) {
			primeiroCampo.focus();
		}

		document.frm_principal.btt_filtro.title = "Esconder Filtro (2)";
	}
}

// Função para testar se uma janela pai é uma janela do e-Fisco
function isJanelaAuxiliar(){
	campoIndicadorJanelaAuxiliar = eval("document.frm_principal." + ID_REQ_IN_JANELA_AUXILIAR);
	if (campoIndicadorJanelaAuxiliar != null) {
		if (campoIndicadorJanelaAuxiliar.value == CD_VERDADEIRO) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

// Função que indica se a aplicação está executando dentro do ambiente de teste do Selenium 
function isAmbienteTesteSelenium() {
	inAmbienteTesteSelenium = eval("document.frm_principal." + ID_REQ_IN_AMBIENTE_TESTE_SELENIUM);

	if (inAmbienteTesteSelenium != null && inAmbienteTesteSelenium.value == CD_VERDADEIRO) {
		return true;
	} else {
		return false;
	}
}


var _inQtMaxRegistros =false;
var _msgAlertaQtMaxRegistros = "";

// Configura a pagina de acordo com resolução e navegador
function configurarPagina() {

	if (isAmbienteDesenvolvimento()) {
		campoIdContextoSessao = eval("document.frm_principal." + ID_REQ_ID_CONTEXTO_SESSAO);
		
		if (campoIdContextoSessao == null) {
			exibirMensagem("ATENÇÃO: O campo oculto \"" + ID_REQ_ID_CONTEXTO_SESSAO + "\" não existe nesta jsp!");
		} else {
			if (campoIdContextoSessao.length > 1) {
				exibirMensagem("ATENÇÃO: Existem " + campoIdContextoSessao.length + " campos ocultos \"" + ID_REQ_ID_CONTEXTO_SESSAO + "\" nesta jsp!");
			}
		}

		campoEvento = eval("document.frm_principal." + ID_REQ_EVENTO);

		if (campoEvento == null) {
			exibirMensagem("ATENÇÃO: O campo oculto \"" + ID_REQ_EVENTO + "\" não existe nesta jsp!");
		} else {
			if (campoEvento.length > 1) {
				exibirMensagem("ATENÇÃO: Existem " + campoEvento.length + " campos ocultos \"" + ID_REQ_EVENTO + "\" nesta jsp!");
			}
		}
	}

	campoIndicadorSemBotaoSelecionarJanelaAuxiliar = eval("document.frm_principal." + ID_REQ_IN_SEM_BOTAO_SELECIONAR_JANELA_AUXILIAR);
	if (campoIndicadorSemBotaoSelecionarJanelaAuxiliar != null) {
		inSemBotaoSelecionarJanelaAuxiliar = true;
	} else {
		inSemBotaoSelecionarJanelaAuxiliar = false;
	}
	
	tdButtonSelecionar = document.getElementById("button_selecionar");
	buttonSelecionar = document.getElementById("btt_selecionar");

	if (tdButtonSelecionar != null) {
		if (getWindowOpener() != null) {
			if (isJanelaAuxiliar() && !inSemBotaoSelecionarJanelaAuxiliar) {
				buttonSelecionar.style.display = "";
			} else {
				buttonSelecionar.style.display = "none";
				tdButtonSelecionar.style.display = "none";
			}			
		} else {
			buttonSelecionar.style.display = "none";
			tdButtonSelecionar.style.display = "none";
		}
	}

	tdButtonFechar = document.getElementById("button_fechar");
	buttonFechar = document.getElementById("btt_fechar");

	if (tdButtonFechar != null) {
		if (getWindowOpener() != null) {
			if (isJanelaAuxiliar()) {
				buttonFechar.style.display = "";
			} else {
				buttonFechar.style.display = "none";
				tdButtonFechar.style.display = "none";
			}
		} else {
			buttonFechar.style.display = "none";
			tdButtonFechar.style.display = "none";
		}
	}

	// Se for uma tela de consulta e só tiver um registro no grid, seleciona
	filtro = document.getElementById("div_filtro");
	if (filtro != null) {
		rdbConsulta = eval("document.frm_principal.rdb_consulta");
		if (rdbConsulta != null && !(rdbConsulta.length > 0)) {
			if (!rdbConsulta.checked) {
				rdbConsulta.click();
			}
		}

		chkConsulta = eval("document.frm_principal.chk_consulta");
		if (chkConsulta != null && !(chkConsulta.length > 0)) {
			if (!chkConsulta.checked) {
				chkConsulta.click();
			}
		}
	}

	elementos = document.forms.item(0).elements;
	for (var i = 0; i < elementos.length; i++) {
		// Teste para evitar utilização das teclas de atalho do framework
		if (elementos.item(i).type == "button") {
			if (!isBotaoFramework(elementos.item(i))) {
				if (isTeclaAtalhoFramework(elementos.item(i).accessKey)) {
					if (isAmbienteDesenvolvimento()) {
						exibirMensagem("ATENÇÃO: A tecla de atalho (" + elementos.item(i).accessKey + ") do botão \"" + elementos.item(i).name + "\" já está associada a um botão do framework!");
					}
				}
			}
		}

	    if (elementos.item(i).className == "camporeadonly" &&
	    	(elementos.item(i).readOnly == false &&
	    	 elementos.item(i).disabled == false)) {
			if (isAmbienteDesenvolvimento()) {
				colBody = document.getElementsByTagName("BODY");
				if (colBody != null && colBody.length > 0) {
					body = colBody.item(0);
					if (body.onload == null) {
						elementos.item(i).style.background = "red";
						exibirMensagem("ATENÇÃO: O campo \"" + elementos.item(i).name + "\" usa a classe CSS \"camporeadonly\" mas não está definido como \"readOnly\" ou \"disabled\"!");
					}
				}
	        }
		}
	}

	primeiroCampo = getPrimeiroCampoFormulario();
	if (primeiroCampo != null) {
		primeiroCampo.focus();
	}

	campoIndicadorFiltroOculto = eval("document.frm_principal.indicador_filtro_oculto");
	if (campoIndicadorFiltroOculto != null) {
		if (campoIndicadorFiltroOculto.value == CD_VERDADEIRO) {
			if (document.getElementById("div_filtro") != null) {
				document.getElementById("div_filtro").style.display = "none";
			}
		}
	} else {
		campoNmCampoIndicadorFiltroOculto = eval("document.frm_principal.nm_campo_indicador_filtro_oculto");
		if (campoNmCampoIndicadorFiltroOculto != null) {
			campoIndicadorFiltroOculto = eval("document.frm_principal." + campoNmCampoIndicadorFiltroOculto.value);
			if (campoIndicadorFiltroOculto != null) {
				if (campoIndicadorFiltroOculto.value == CD_VERDADEIRO) {
					if (document.getElementById("div_filtro") != null) {
						document.getElementById("div_filtro").style.display = "none";
					}
				}
			}
		}
	}
	
	tratarCargaJQueryAsync(estilizarTabelaDados);

	if (_inQtMaxRegistros) {
		if (!isAmbienteTesteSelenium()) {
			exibirMensagem(_msgAlertaQtMaxRegistros);
		}
	}

	// Verifica se existe alguma âncora no formulário
	ancora = eval("document.frm_principal." + ID_REQ_CD_ANCORA);
	if (ancora != null) {
		if (ancora.value != "") {
			window.location.hash = ancora.value;
		}
	}

	// Seta o foco para o próximo campo
	campoNmCampoFocoAtual = eval("document.frm_principal." + ID_REQ_NM_CAMPO_FOCO_ATUAL);
	if (campoNmCampoFocoAtual != null){
		if (campoNmCampoFocoAtual.value != "") {
			campoFocoAtual = eval("document.frm_principal." + campoNmCampoFocoAtual.value);
			if (campoFocoAtual != null) {
				campoProximoFoco = getProximoElementoParaFocar(campoFocoAtual);
				campoProximoFoco.focus();
				//Seta o campoNmCampoFocoAtual para null para que o foco não fique voltando sempre para o mesmo campo
				campoNmCampoFocoAtual.value = "";
			}
		}
	}
	
	botaoLocalizar = eval("document.frm_principal.btt_localizar");
	if (botaoLocalizar != null) {
		try {
			if (document.frm_principal.onsubmit == null) {
				document.frm_principal.onsubmit = validarSubmitBotaoLocalizar;
			}
		} catch(er) {
		}
	}
	
	if (isAcessoMovel()) {
		document.body.style.marginBottom = (window.innerHeight / 2) + "px";
	}
}

function estilizarTabelaDados() {
	var $ = window.jQuery;
	var $tr = $("table.tabeladados").children("tbody").children("tr");
	
	var $trLength = $tr.length;
	if ($trLength === 0) return;

	if ($tr.first().children("th").length > 0) {
		$tr = $tr.slice(1);
	}

	if (window.inNavegadorIE8 && $trLength > 100) {
		$tr.filter(":odd").addClass("tabeladadosodd_ie8");
		$tr.filter(":even").addClass("tabeladadoseven_ie8");
	} else {
		$tr.filter(":odd").addClass("tabeladadosodd");
		$tr.filter(":even").addClass("tabeladadoseven");
	}
}

function estilizarTabelaDadosResponsiva() {
	var $ = window.jQuery;
	
	$('.grupoExpansivo h3.tituloSecao').click(function(){
		var $parent = $(this).parent();
		$parent.find('div.conteudoSecao').slideToggle("slow");
		$parent.toggleClass("expandir").toggleClass("contrair")
	});
	
	$(".tabeladados").footable();
}

function carregarJavascriptAsync(pTxPathBiblioteca, pFuncaoTratamento) {
	var elemJQuery = document.createElement('script');
	elemJQuery.type = 'text/javascript';
	elemJQuery.src = pTxPathBiblioteca;
	elemJQuery.onload = pFuncaoTratamento;
	
	document.getElementsByTagName('head')[0].appendChild(elemJQuery);
}

var arrFuncCargaJQueryAsync = [];

function tratarCargaJQueryAsync(pFuncao) {
	if (window.jQuery) {
		pFuncao();
	} else if (arrFuncCargaJQueryAsync !== null) {
		arrFuncCargaJQueryAsync.push(pFuncao);
	}
}

function executarFuncoesCargaJQueryAsync() {
	if (arrFuncCargaJQueryAsync === null) return;
	
	for (var i = 0, j = arrFuncCargaJQueryAsync.length; i < j; i++) {
		arrFuncCargaJQueryAsync[i]();
	}
	
	arrFuncCargaJQueryAsync = null;
}

function getPosicaoXElementoDOM(pElementoDOM) {
	for(var posX = 0; pElementoDOM.offsetParent; pElementoDOM = pElementoDOM.offsetParent ) {
		posX += pElementoDOM.offsetLeft;
	}
   	return posX;
}

function getPosicaoYElementoDOM(pElementoDOM) {
	for(var posY = 0; pElementoDOM.offsetParent; pElementoDOM = pElementoDOM.offsetParent ) {
		posY += pElementoDOM.offsetTop;
	}
   	return posY;
}

//Busca o nome do próximo campo para receber o foco
function getProximoElementoParaFocar(pCampoFocoAtual) {
	if (pCampoFocoAtual != null) {
		elementos = document.forms.item(0).elements;

		for (var i = 0; i < elementos.length; i++) {
			if (elementos.item(i).name == pCampoFocoAtual.name) {
				for (var j = i + 1 ; i < elementos.length; j++) {
					if (elementos.item(j).type == "checkbox"
						|| elementos.item(j).type == "radio"
						|| elementos.item(j).type == "select-one"
						|| elementos.item(j).type == "select-multiple"
						|| elementos.item(j).type == "button"
						|| elementos.item(j).type == "text"
						|| elementos.item(j).type == "password"
						|| elementos.item(j).type == "file"
						|| elementos.item(j).type == "textarea") {
						if (elementos.item(j).className != "camporeadonly"
							&& elementos.item(j).readOnly != true
							&& elementos.item(j).disabled != true) {
							return elementos.item(j);
						}
					}
				}  	
			}
		}
	}
}

// Configurar o cabeçalho fixo para tabela de dados. Esta função só deve ser chamada em páginas que contenham uma tabelaDados bem formatada.
function configurarTabelaDadosCabecalhoFixo() {
	// Adiciona uma tabela no final do div_tabeladados
	$("#div_tabeladados").append("<table id='cabecalhoFixo' class='tabeladados' cellpadding='0' cellspacing='0'></table>");
	var $header = $("#table_tabeladados > thead").clone();
	var $cabecalhoFixo = $("#cabecalhoFixo").append($header);
	
	$(window).bind("scroll", function() {
		var topoTabela = $("#table_tabeladados").offset().top;
	    var topoAtual = $(this).scrollTop();

	    // Se com o scrool, o cabeçalho deixou de aparecer, mostra o cabeçalho fixo
	    if (topoAtual >= topoTabela && $cabecalhoFixo.is(":hidden")) {
	        $cabecalhoFixo.show();
	    } else if (topoAtual < topoTabela) {
	    	// esconde o cabeçalho fixo
	        $cabecalhoFixo.hide();
	    }
	});
}

var Drag = {

	obj : null,

	init : function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper) {
    	o.onmousedown = Drag.start;
		o.style.cursor = 'move';

	    o.hmode      = bSwapHorzRef ? false : true ;
	    o.vmode      = bSwapVertRef ? false : true ;
	
	    o.root = oRoot && oRoot != null ? oRoot : o ;
	
	    if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
	    if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
	    if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
	    if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";
	
	    o.minX  = typeof minX != 'undefined' ? minX : null;
	    o.minY  = typeof minY != 'undefined' ? minY : null;
	    o.maxX  = typeof maxX != 'undefined' ? maxX : null;
	    o.maxY  = typeof maxY != 'undefined' ? maxY : null;
	
	    o.xMapper = fXMapper ? fXMapper : null;
	    o.yMapper = fYMapper ? fYMapper : null;
	
	    o.root.onDragStart  = new Function();
	    o.root.onDragEnd  = new Function();
	    o.root.onDrag    = new Function();
	},

	start : function(e) {
	    var o = Drag.obj = this;
	    e = Drag.fixE(e);
	    var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
	    var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
	    o.root.onDragStart(x, y);
	
	    o.lastMouseX  = e.clientX;
	    o.lastMouseY  = e.clientY;
	
	    if (o.hmode) {
	      if (o.minX != null)  o.minMouseX  = e.clientX - x + o.minX;
	      if (o.maxX != null)  o.maxMouseX  = o.minMouseX + o.maxX - o.minX;
	    } else {
	      if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
	      if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
	    }
	
	    if (o.vmode) {
	      if (o.minY != null)  o.minMouseY  = e.clientY - y + o.minY;
	      if (o.maxY != null)  o.maxMouseY  = o.minMouseY + o.maxY - o.minY;
	    } else {
	      if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
	      if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
	    }
	
	    document.onmousemove  = Drag.drag;
	    document.onmouseup    = Drag.end;
	
	    return false;
	},

	drag : function(e)   {
	    e = Drag.fixE(e);
	    var o = Drag.obj;
	
	    var ey  = e.clientY;
	    var ex  = e.clientX;
	    var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
	    var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
	    var nx, ny;
	
	    if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
	    if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
	    if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
	    if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);
	
	    nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
	    ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));
	
	    if (o.xMapper)    nx = o.xMapper(y)
	    else if (o.yMapper)  ny = o.yMapper(x)
	
	    Drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
	    Drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
	    Drag.obj.lastMouseX  = ex;
	    Drag.obj.lastMouseY  = ey;
	
	    Drag.obj.root.onDrag(nx, ny);
	    return false;
	},

	end : function() {
	    document.onmousemove = null;
	    document.onmouseup   = null;
	    Drag.obj.root.onDragEnd(  parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" : "right"]), 
	                  parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" : "bottom"]));
	    Drag.obj = null;
	},

	fixE : function(e) {
	    if (typeof e == 'undefined') e = window.event;
	    if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
	    if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
	    return e;
	}
};

ModalWindow.windows = new Array();

ModalWindow.getKeyCode = function(e) {
	if(!e) e = window.event ;
	return (e) ? (e.which || e.keyCode) : null ;
};

ModalWindow.keyup = function(e) {
	if( ModalWindow.getKeyCode(e) == 13 ) {
		if (ModalWindow.windows.length > 0) {
			ModalWindow.windows[ModalWindow.windows.length - 1].executeEventHandler(ModalWindow.ENTER_KEY);
		}
	}
	if (ModalWindow.getKeyCode(e) == 27) {
		if (ModalWindow.windows.length > 0) {
			ModalWindow.windows[ModalWindow.windows.length - 1].executeEventHandler(ModalWindow.ESC_KEY);
		}
	}
};

function ModalWindow() {

	ModalWindow.ESC_KEY = 1;
	ModalWindow.ENTER_KEY = 2;

	ModalWindow.CLOSE_BUTTON = 3;
	ModalWindow.OK_BUTTON = 4;
	ModalWindow.NO_BUTTON = 5;
	ModalWindow.CANCEL_BUTTON = 6;
	
	this.box = null;
	this.overlay = null;
	this.index = null; // Index in the window stack 
	
	this.open = function() {
		var options = arguments[0] || {};
		var defaults = {
			'type': "alert",
			'opacity': "0.5",
			'width': null,
			'height': 'auto',
			'top': null,
			'left': 'auto',
			'title': "Informação",
			'text': "",
			'text_align_center': false,
			'text_margin_top': '5px',
			'tooltip': "",
			'modal': false,
			'drag': false,
			'ok_button': "Ok",
			'accesskey_ok_button': "o",
			'cancel_button': "Cancelar",
			'accesskey_cancel_button': "c",
			'buttons': null,
			'eventHandler': null
		};

		for( key in defaults ) {
			this[key] = ( typeof options[key] != "undefined" ) ? options[key] : defaults[key] ;
		}
		
		if (this.modal) {
			this.initOverlay();
		}

		ModalWindow.windows.push(this);
		this.index = ModalWindow.windows.length - 1;

		this.initBox();
	};
	
	this.close = function() {
		if( this.box ) {
			this.remove(this.box);
			this.box = null;
		}
		if ( this.modal && this.overlay ) {
			this.remove( this.overlay );
			this.overlay = null;
		}

		if (this.type != "wait" && ModalWindow.windows.length == 1) {
			this.removeEvent(document, "keyup", ModalWindow.keyup);
		}

		ModalWindow.windows.pop();
		
		this.opened = false;
	};
	
	this.executeEventHandler = function(eventId) {
		var prompt;
		if (this.type == "prompt") {
			prompt = document.getElementById('modal_window_prompt').value;
		}

		this.close();

		functionEventHandler = eval(this.eventHandler);
		if (functionEventHandler != null) {
			if (this.type == "prompt") {
				functionEventHandler(eventId, prompt);
			} else {
				functionEventHandler(eventId);
			}
		}
	};
	
	this.initBox = function() {
		var dim = this.getPageSize();
		
		var boxWidth = ( ( this.width ) ? this.width : ( dim.pageWidth / 4 ) )  + "px";
		var boxHeight = ( typeof this.height == "string" ) ? "auto" : this.height + "px" ;

		var boxPosVCentre = ( typeof this.height == "string" ) ? 0 : ( ( dim.windowHeight / 2 ) - ( parseInt(boxHeight) / 2 ) + document.body.scrollTop) ;
		var boxPosHCentre = ( ( dim.pageWidth / 2 ) - ( parseInt(boxWidth) / 2 ) );
		
		var boxPosTop = ( typeof this.height == "string" ) ? "10px" : boxPosVCentre + "px" ;
		if (this.top != null) {
			boxPosTop = this.top + "px";
		}
		var boxPosLeft = boxPosHCentre + "px";
		
		if (this.type == "wait") {
			boxClass = "ModalWindowBoxRed";
		} else {
			boxClass = "ModalWindowBox";
		}
		
		id_div_box_modal_window = "div_box_modal_window_" + this.index;
		
		this.box = this.element("div", {
			'class': boxClass,
			'id': id_div_box_modal_window,
			'styles' : {
				'width': boxWidth,
				'height': boxHeight,
				'position': "absolute",
				'top': boxPosTop,
				'left': boxPosLeft,
				'zIndex': 1000
			}
		});
		
		this.inject(this.box);
		
		if (this.type == "wait") {
			containerClass = "ModalWindowContainerRed";
		} else {
			containerClass = "ModalWindowContainer";
		}

		var container = this.element("div", {
			'id': "div_modal_window",
			'class': containerClass,
			'tooltip': this.tooltip
		});

		if (this.type != "wait" && this.title != null && this.title != "") {
			container.appendChild(this.element("div", {
				'id': "div_title_modal_window_" + this.index,
				'html': "<table border='0'><tr><td width='98%' class='ModalWindowTitle'>" + this.title + "</td>" +
					     "<td><img src='/sfi/imagens/close_red.jpg' title='Fechar' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.CLOSE_BUTTON+");' style='cursor:pointer;cursor:hand';/></td></tr></table>",
				'class': "ModalWindowTitle"
			}));
		}

		if (this.type == "wait") {
			messageClass = "ModalWindowMessageWhite";
		} else {
			messageClass = "ModalWindowMessage";
		}

		var lHtml = this.text;
		var isRichContent = false;
		
		if (this.type == "prompt") {
			lHtml = lHtml + "&nbsp;&nbsp;" 
		}
		
		if (lHtml.indexOf("<TABLE") > -1) {
			isRichContent = true;
		}
		
		if (this.text_align_center) {
			var message = this.element("div", {
				'id': "div_message_modal_window_" + this.index,
				'class': messageClass
			});
			
			message.appendChild(this.element("center", {
				'html': lHtml
			}));
			
			if (this.type == "prompt") {
				this.input = this.element("input", {
					'name': "modal_window_prompt",
					'id': "modal_window_prompt",
					'type': "text"
				});
				
				message.appendChild(this.input);
			}

			container.appendChild(message);

		} else {
			var message = this.element("div", {
				'id': "div_message_modal_window_" + this.index,
				'class': messageClass,
				'html': lHtml
			});

			if (this.type == "prompt") {
				this.input = this.element("input", {
					'name': "modal_window_prompt",
					'id': "modal_window_prompt",
					'type': "text"
				});
				
				message.appendChild(this.input);
			}

			container.appendChild(message);
		}

		if (this.type != "wait") {
			if (isChrome) {
				message.style.height = ( typeof this.height == "string" ) ? "auto" : (this.height - 79) + "px";
			} else if (isIE) {
				message.style.height = ( typeof this.height == "string" ) ? "auto" : (this.height - 72) + "px";
			} else {
				message.style.height = ( typeof this.height == "string" ) ? "auto" : (this.height - 75) + "px";
			}
			
			if (!isRichContent) {
				message.style.paddingTop = "17px";
				message.style.paddingBottom = "20px";
			}
			
			var buttonContainer = this.element("div", {
				'class': "ModalWindowButtons"
			});
	
			switch( this.type ) {
				case "alert" :
					
					buttonContainer.appendChild(this.element("div", 
		                         { 'html': "<input type='button' accesskey='" + this.accesskey_ok_button + "' class='botaofuncaop' value='" + this.ok_button + " (" + this.accesskey_ok_button + ")' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.OK_BUTTON+");'/>"
								  }));
					container.appendChild(buttonContainer);
	
					break;
				case "confirm" :
	
					buttonContainer.appendChild(this.element("div", 
		                         { 'html': "<input type='button' accesskey='" + this.accesskey_ok_button + "' class='botaofuncaop' value='" + this.ok_button + " (" + this.accesskey_ok_button + ")' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.OK_BUTTON+");'/>&nbsp;&nbsp;" +
											"<input type='button' accesskey='" + this.accesskey_cancel_button + "' class='botaofuncaop' value='" + this.cancel_button + " (" + this.accesskey_cancel_button + ")' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.CANCEL_BUTTON+");'/>"
								  }));				
					container.appendChild(buttonContainer);
	
					break;
				case "prompt" :
					
					buttonContainer.appendChild(this.element("div", 
							{ 'html': "<input type='button' accesskey='" + this.accesskey_ok_button + "' class='botaofuncaop' value='" + this.ok_button + " (" + this.accesskey_ok_button + ")' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.OK_BUTTON+");'/>&nbsp;&nbsp;" +
										"<input type='button' accesskey='" + this.accesskey_cancel_button + "' class='botaofuncaop' value='" + this.cancel_button + " (" + this.accesskey_cancel_button + ")' onclick='ModalWindow.windows["+this.index+"].executeEventHandler("+ModalWindow.CANCEL_BUTTON+");'/>"
							}));				
	 				container.appendChild(buttonContainer);
				
	 				break;
			}
		}
		
		this.box.appendChild(container);
				
		if (this.type != "wait") {
			if (ModalWindow.windows.length == 1) {
				this.addEvent(document, "keyup", ModalWindow.keyup);
			}

			if (!isRichContent) {
				var divMessageStyle = document.getElementById("div_message_modal_window_" + this.index).style;
				divMessageStyle.paddingRight = "4px";
				divMessageStyle.paddingLeft = "4px";
			}
			
			document.getElementById("div_message_modal_window_" + this.index).focus();

			if (this.input) {
				this.input.focus();
			}

			if (this.drag) {
				Drag.init(document.getElementById("div_title_modal_window_" + this.index), document.getElementById(id_div_box_modal_window));
			}
		}
	};

	this.initOverlay = function() {
		var dim = this.getPageSize();
		this.overlay = this.element("div", {
			'styles': {
				'backgroundColor': "black",
				'opacity': this.opacity,
				'position': "absolute",
				'top': "0px",
				'left': "0px",
				'width': dim.pageWidth + "px",
				'height': dim.pageHeight + "px",
				'zIndex': 999
			}
		});
		this.inject(this.overlay);
	};
	
	this.inject = function(el) {
		document.body.appendChild( el );
	};
	
	this.remove = function(el) {
		document.body.removeChild( el );
	};
	
	this.element = function() {
		var tag = arguments[0], options = arguments[1];
		var el = document.createElement(tag);
		var attributes = {
			'id': 'id',
			'html': 'innerHTML',
			'class': 'className',
			'for': 'htmlFor',
			'text': 'innerText',
			'tooltip': 'title'
		};
		if( options ) {
			if( typeof options == "object" ) {
				for( name in options ) {
					var value = options[name];
					if(name == "styles") {
						this.setStyles(el, value);
					} else if (attributes[name]) { 
						el[attributes[name]] = value; 
					} else { 
						el.setAttribute(name, value); 
					}
				}
			}
		}
		return el;
	};
	
	this.addEvent = function( o, e, f ) {
		if(o) {
			if( o.addEventListener ) o.addEventListener(e, f, false );
			else if( o.attachEvent ) o.attachEvent( 'on'+e , f);
		}
	};
	
	this.removeEvent = function( o, e, f ) {
		if(o) {
			if( o.removeEventListener ) {o.removeEventListener( e, f, false );}
			else if( o.detachEvent ) {o.detachEvent( 'on'+e, f )};
		}
	};
	
	this.setStyles = function(e, o) {
		for( k in o ) {
			this.setStyle(e, k, o[k]);
		}
	};
	
	this.setStyle = function(e, p, v) {
		if ( p == 'opacity' ) {
			if (window.ActiveXObject) {
				e.style.filter = "alpha(opacity=" + v*100 + ")";
			}
			e.style.opacity = v;
		} else {
			e.style[p] = v;
		}
	};
		
	this.getPageSize = function() {
		var xScroll, yScroll;
		
		if (window.innerHeight && window.scrollMaxY) {	
			xScroll = window.innerWidth + window.scrollMaxX;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight) {
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else {
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
		
		var windowWidth, windowHeight;
	
		if (self.innerHeight) {
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth; 
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) {
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	

		if(yScroll < windowHeight){
			pageHeight = windowHeight;
		} else { 
			pageHeight = yScroll;
		}

		if(xScroll < windowWidth){	
			pageWidth = xScroll;		
		} else {
			pageWidth = windowWidth;
		}
	
		return { 'pageWidth': pageWidth, 'pageHeight': pageHeight, 'windowWidth' : windowWidth, 'windowHeight': windowHeight };
	};
}

function getNmDispositivoAcesso() {
	var campoNmDispositivo = document.getElementsByName(ID_REQ_NM_DISPOSITIVO_ACESSO);
	
	if (campoNmDispositivo == null || campoNmDispositivo.length === 0) {
		return null;
	}
	
	return campoNmDispositivo[0].value;
}

function getTpDispositivoAcesso() {
	var campoTpDispositivo = document.getElementsByName(ID_REQ_TP_DISPOSITIVO_ACESSO);
	
	if (campoTpDispositivo == null || campoTpDispositivo.length === 0) {
		return null;
	}
	
	return campoTpDispositivo[0].value;
	}
	
function isAcessoMovel() {
	var tpDispositivo = getTpDispositivoAcesso();
	
	if (tpDispositivo === null) return false;

	return tpDispositivo === TP_DISPOSITIVO_ACESSO_CELULAR || tpDispositivo === TP_DISPOSITIVO_ACESSO_TABLET;
}


/*
 * Funções utilitárias para programação OO.
 */
function _SuperClasse() {}
var _SuperClassePT = _SuperClasse.prototype;

function herdarClasse(pSubClasse, pSuperClasse) {
	var subClassePtype = pSubClasse.prototype;
	_SuperClasse.prototype = pSuperClasse.prototype;
	
	var superClasse = new _SuperClasse();
	for (var nmProp in subClassePtype) superClasse[nmProp] = subClassePtype[nmProp];
	
	superClasse.constructor = pSubClasse;
	pSubClasse.prototype = superClasse;
	_SuperClasse.prototype = _SuperClassePT;
}

/*
 * -
 */
function pararPropagacaoEvento(ev) {
	if (!ev) ev = window.event;
	if (ev.stopPropagation) ev.stopPropagation();
	else ev.cancelBubble = true;
}

function configurarVoltarAcessoMovel() {
	document.addEventListener("deviceready", dispositivoProntoAcessoMovel, false);
}

function dispositivoProntoAcessoMovel() {
	// Definindo o comportamento do back button do dispositivo movel
	if (window.voltarMovel !== undefined) {
		document.addEventListener("backbutton", window.voltarMovel, false);
	}
}
function getStringColecaoDescricao(colecaoDescricaoCampos) {
	var retorno = "";
	var tamanho = colecaoDescricaoCampos.length;
	if(colecaoDescricaoCampos != null && tamanho > 0){
		for (var i = 0; i < tamanho; i++) {
			retorno = retorno + colecaoDescricaoCampos[i];
		}		
	}
	
	return retorno;
}
function isPeloMenosUmCampoFormularioSelecionado(colecaoNmCamposForm, colecaoDescricaoCampos, comMensagem) {
	var tamanho = colecaoNmCamposForm.length;
	var temCampo = false;
	if (colecaoNmCamposForm != null && tamanho > 0){		
		for (var i = 0; i < tamanho; i++) {
			campoForm = eval(colecaoNmCamposForm[i]);
			if(campoForm.value != ""){
				temCampo = true;
				break;
			}			
		}
		
		if(!temCampo && comMensagem){
			var str = getStringColecaoDescricao(colecaoDescricaoCampos);
			exibirMensagem("Selecione ao menos um dos campos a seguir: " + str);		
		}
		
	}
	
	return temCampo;
}