/*
 Descri��o:
 - Cont�m fun��es de valida��o de campos num�rico, alfanum�rico e texto

 Depend�ncias:
 - biblioteca_funcoes_principal.js
*/


// ********************************************************************
// Fun��es para campos num�ricos

//Funcao js para truncar/cortar string e jogar "..." no final de acordo com o tamanho/size desejado
//@param str: string do texto pra truncar/cortar. Deve ter o tamanho minimo para comportar os 3 caracteres "..."
//@param size: tamanho da string antes dos "..."
function truncarTexto(str, size, pStringAcrescentarAoFim){
    if (str==undefined || str=='undefined' || str =='' || size==undefined || size=='undefined' || size ==''){
        return str;
    }
    
    strFim = "";    
    if(pStringAcrescentarAoFim != null)
    	strFim = pStringAcrescentarAoFim;
    
    tamanho = strFim.length;
    //alert(tamanho);
     
    var shortText = str;
    if(str.length >= size+tamanho){
        shortText = str.substring(0, size).concat(strFim);
    }
    return shortText;
}


// coloca zeros a esquerda at� o tamnanho m�ximo passado como par�metro
// Exemplo completarNumeroComZerosEsquerda(12, 4) = 0012
function completarNumeroComZerosEsquerda(pValor, pTamanhoMaximo){

	var result = "";
	result = pValor;
	var parada = result.length;
	zeros = "";
	quantidadeZeros = 0;	
	if (parada < pTamanhoMaximo){
		quantidadeZeros = pTamanhoMaximo - parada;
		for (var i = 0; i < quantidadeZeros; i++) {
			zeros = zeros + "0";
		}
		
	}
	result = zeros + "" +result;
	return result;
}

// coloca zeros a direita at� o tamnanho m�ximo passado como par�metro
// Exemplo completarNumeroComZerosDireita(12, 4) = 1200
function completarNumeroComZerosDireita(pValor, pTamanhoMaximo){

	var result = "";
	zeros = "";
	quantidadeZeros = 0;	
	result = pValor;
	var parada = result.length;
	
	if (parada < pTamanhoMaximo){
		quantidadeZeros = pTamanhoMaximo - parada;
		for (var i = 0; i < quantidadeZeros; i++) {
			zeros = zeros + "0";
		}
		
	}
	result = result + "" + zeros;
	return result;
}


// Valida o preenchimento do campo numerico "pCampo" passado como parametro
// Deve ser chamada no evento onkeyup do componente input text
function validarCampoNumerico(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^-{0,1}([0-9])*$/;

	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(30));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo numerico negativo "pCampo" passado como parametro
// Permite o valor zero
// Deve ser chamada no evento onkeyup do componente input text
function validarCampoNumericoNegativo(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;
	var intAux = parseInt(str, 10);

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^-{0,1}([0-9])*$/;

	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(40));
		focarCampo(pCampo);
	}
	
	if (intAux > 0) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(40));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo numerico "pCampo" passado como parametro
// Permite o valor zero
// Deve ser chamada no evento onkeyup do componente input text
function validarCampoNumericoPositivo(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;
	var intAux = parseInt(str, 10);

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([0-9])*$/;

	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(41));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo numerico "pCampo" passado como parametro
// Permite apenas n�meros positivos e caracteres coringa (* e ?)
// Deve ser chamada no evento onkeyup do componente input text
function validarCampoNumericoCoringa(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([0-9*?])*$/;

	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(30));
		focarCampo(pCampo);
	}
}

// Verifica se existe um valor num�rico v�lido no campo "pCampo" passado como parametro
function isCampoNumericoValido(pCampo, pInObrigatorio, pVlMinimo, pVlMaximo, pTamanho, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;
	var intAux = parseInt(str, 10);
	var msg;

	if (pInObrigatorio) {
		var filtro = /^-{0,1}([0-9])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^-{0,1}([0-9])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (str != "" && isNaN(intAux)) {
		if (pCampo.type != "select-one") {
			pCampo.select();
		}
		if(!pSemMensagem) {
			exibirMensagem(mensagemGlobal(30) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(30) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMaximo != null && intAux > pVlMaximo) {
		pCampo.select();
		if(!pSemMensagem) {
			exibirMensagem((mensagemGlobal(32) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMaximo));
		}		
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMinimo != null && intAux < pVlMinimo) {
		pCampo.select();
		if(!pSemMensagem) {
			exibirMensagem((mensagemGlobal(33) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pTamanho > 0 && str.length != pTamanho) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(31) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTamanho));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor num�rico negativo v�lido no campo "pCampo" passado como parametro
function isCampoNumericoNegativoValido(pCampo, pInObrigatorio, pVlMinimo, pVlMaximo, pTamanho, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;
	var intAux = parseInt(str, 10);
	var msg;

	if (pInObrigatorio) {
		var filtro = /^-{0,1}([0-9])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^-{0,1}([0-9])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (str != "" && (isNaN(intAux) || intAux < 0)) {
		pCampo.select();		
		if(!pSemMensagem){
			exibirMensagem(mensagemGlobal(40) + msg);		
		}
		focarCampo(pCampo);
		return false;
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(40) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMaximo != null && intAux > pVlMaximo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(32) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMaximo));
		}		
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMinimo != null && intAux < pVlMinimo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(33) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pTamanho > 0 && str.length != pTamanho) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(31) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTamanho));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor num�rico positivo v�lido no campo "pCampo" passado como parametro
function isCampoNumericoPositivoValido(pCampo, pInObrigatorio, pVlMinimo, pVlMaximo, pTamanho, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;
	var intAux = parseInt(str, 10);
	var msg;

	if (pInObrigatorio) {
		var filtro = /^([0-9])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^([0-9])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (str != "" && isNaN(intAux)) {
		if (pCampo.type != "select-one") {
			pCampo.select();
		}
		if(!pSemMensagem){
			exibirMensagem(mensagemGlobal(41) + msg);
		}
		focarCampo(pCampo);
		return false;
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(41) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMaximo != null && intAux > pVlMaximo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(32) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMaximo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pVlMinimo != null && intAux < pVlMinimo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(33) + msg).replace(CD_CAMPO_SUBSTITUICAO, pVlMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (!isNaN(intAux) && pTamanho > 0 && str.length != pTamanho) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(31) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTamanho));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor num�rico v�lido no campo "pCampo" passado como par�metro
// Permite caracteres coringa e campo vazio
function isCampoNumericoCoringaValido(pCampo, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;
	var tam = str.length;

	var filtro = /^([0-9*?])*$/;

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem(mensagemGlobal(30));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}



// ********************************************************************
// Fun��es para campos alfab�ticos

// Valida o preenchimento do campo alfab�tico "pCampo" passado como parametro
function validarCampoAlfabetico(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z])*$/;
	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(42));
		focarCampo(pCampo);
	}
}

function validarCampoAlfabeticoMaiusculo(pCampo, pEvento) {
	if (isTeclaFuncional(pEvento)) {
		return;
	}

	pCampo.value = pCampo.value.toUpperCase();
	validarCampoAlfabetico(pCampo, pEvento); 
}


// Valida o preenchimento do campo alfab�tico "pCampo" passado como parametro
// Permite caracteres coringa
function validarCampoAlfabeticoCoringa(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z*?])*$/;
	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1)
		exibirMensagem(mensagemGlobal(42));
		focarCampo(pCampo);
	}
}

// Verifica se existe um valor alfab�tico mai�sculo v�lido no campo "pCampo" passado como parametro
function isCampoAlfabeticoMaiusculoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	pCampo.value = pCampo.value.toUpperCase();
	return isCampoAlfabeticoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem); 
}

// Verifica se existe um valor alfab�tico v�lido no campo "pCampo" passado como parametro
function isCampoAlfabeticoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	var str = pCampo.value;
	var msg;

	if (pInObrigatorio) {
		var filtro = /^([a-zA-Z])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^([a-zA-Z])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(42) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor alfab�tico v�lido no campo "pCampo" passado como parametro
// Permite caracteres coringa e campo vazio
function isCampoAlfabeticoCoringaValido(pCampo, pSemMensagem) {
	var str = pCampo.value;
	var msg;

	var filtro = /^([a-zA-Z*?])*$/;

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem(mensagemGlobal(42) + msg);
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}



// ********************************************************************
// Fun��es para campos alfanum�ricos

// Valida o preenchimento do campo alfanumerico "pCampo" passado como parametro
function validarCampoAlfaNumerico(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z0-9])*$/;
	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1);
		exibirMensagem(mensagemGlobal(34));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo alfanumerico "pCampo" passado como parametro
function validarCampoAlfaNumericoCoringa(pCampo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z0-9*?])*$/;
	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1);
		exibirMensagem(mensagemGlobal(34));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo alfanumerico extendido maiusculo "pCampo" passado como parametro.
// Utilizado nos campos raz�o social.
function validarCampoAlfaNumericoExtendidoMaiusculo(pCampo, pEvento) {
	if (isTeclaFuncional(pEvento)) {
		return;
	}
	
	removerEspacamentoDuplo(pCampo);

	var str = pCampo.value;
	var tam = str.length;

	campoExpRegCampoAlfaNumericoExtendido = eval("document.frm_principal.ExpRegCampoAlfaNumericoExtendido");
	campoTxMsgExpRegCampoAlfaNumericoExtendido = eval("document.frm_principal.TxMsgExpRegCampoAlfaNumericoExtendido");

	if (campoExpRegCampoAlfaNumericoExtendido != null) {
		expReg = campoExpRegCampoAlfaNumericoExtendido.value;
		txMsgExpReg = campoTxMsgExpRegCampoAlfaNumericoExtendido.value;
	} else {
		expReg = ",\\.:;!?\"'�@#\\$%&*\\(\\)\\-_\\+<>/";
		txMsgExpReg = ", \n, (v�rgula), . (ponto), : (dois pontos), ; (ponto e v�rgula), ! (exclama��o), \n? (interroga��o), \" (aspa), ' (ap�strofo), � (acento agudo), @ (arroba), \n# (cerquilha), $ (cifr�o), % (percentagem), & (e comercial), * (asterisco), \n( (abre par�nteses), ) (fecha par�nteses), - (h�fen), _ (sublinhado), \n+ (adi��o), / (barra), < (menor) e > (maior)";
	}

	var filtro = eval("/^[A-Z0-9" + expReg + "\\s]*$/");

	if (!filtro.test(str)) {
		str = str.toUpperCase();
		pCampo.value = str;
	}

	if (!filtro.test(str)) {
		pCampo.value = str.substr(0, tam - 1);
		exibirMensagem(mensagemGlobal(44).replace(CD_CAMPO_SUBSTITUICAO, txMsgExpReg));
		focarCampo(pCampo);
	}
}

function removerEspacamentoDuplo(pCampo) {
	str = pCampo.value;

	while(str.indexOf("  ") != -1) {
		str = str.replace("  ", " "); 
	}

	pCampo.value = str;
}

// Verifica se existe um valor alfanumerico valido no campo "pCampo" passado como parametro
function isCampoAlfaNumericoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	var str = pCampo.value;
	var msg;

	if (pInObrigatorio) {
		var filtro = /^([a-zA-Z0-9])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^([a-zA-Z0-9])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(34) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor alfanum�rico v�lido no campo "pCampo" passado como parametro
// Permite caracteres coringa e campo vazio
function isCampoAlfaNumericoCoringaValido(pCampo, pSemMensagem) {
	var str = pCampo.value;

	var filtro = /^([a-zA-Z0-9*?])*$/;

	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem(mensagemGlobal(34));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}

// Verifica se existe um valor alfanumerico valido no campo "pCampo" passado como parametro
function isCampoAlfaNumericoExtendidoMaiusculoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	pCampo.value = pCampo.value.toUpperCase();

	var str = pCampo.value;
	var msg;

	campoExpRegCampoAlfaNumericoExtendido = eval("document.frm_principal.ExpRegCampoAlfaNumericoExtendido");
	campoTxMsgExpRegCampoAlfaNumericoExtendido = eval("document.frm_principal.TxMsgExpRegCampoAlfaNumericoExtendido");

	if (campoExpRegCampoAlfaNumericoExtendido != null) {
		expReg = campoExpRegCampoAlfaNumericoExtendido.value;
		txMsgExpReg = campoTxMsgExpRegCampoAlfaNumericoExtendido.value;
	} else {
		expReg = ",\\.:;!?\"'�@#\\$%&*\\(\\)\\-_\\+<>/";
		txMsgExpReg = ", \n, (v�rgula), . (ponto), : (dois pontos), ; (ponto e v�rgula), ! (exclama��o), \n? (interroga��o), \" (aspa), ' (ap�strofo), � (acento agudo), @ (arroba), \n# (cerquilha), $ (cifr�o), % (percentagem), & (e comercial), * (asterisco), \n( (abre par�nteses), ) (fecha par�nteses), - (h�fen), _ (sublinhado), \n+ (adi��o), / (barra), < (menor) e > (maior)";
	}

	if (pInObrigatorio) {
		var filtro = eval("/^[A-Z0-9" + expReg + "\\s]+$/");
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = eval("/^[A-Z0-9" + expReg + "\\s]*$/");
		msg = "\n" + mensagemGlobal(1);
	}
	
	if (!filtro.test(str)) {
		pCampo.select();
		if(!pSemMensagem) {
			if (str == "") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(44).replace(CD_CAMPO_SUBSTITUICAO, txMsgExpReg) + msg);
			}
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if(!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}

	return true;
}


// ********************************************************************
// Fun��es para campos texto

// Valida o preenchimento do campo texto "pCampo" passado como parametro
function validarCampoTexto(pCampo, pTmMaximo, pEvento, pCaracteresInvalidos) {
	var str = pCampo.value;
	var tam = str.length;

	var listaFiltro = "";
	var listaCaracteresInvalidos = "";
	var conector = "";

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	if (pTmMaximo > 0 && tam > pTmMaximo) {
		pCampo.value = str.substr(0, pTmMaximo);
		exibirMensagem(mensagemGlobal(36).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		focarCampo(pCampo);
	}

	if (pCaracteresInvalidos != null) {
		for (i = 0; i < pCaracteresInvalidos.length; i++) {
			listaFiltro = listaFiltro + pCaracteresInvalidos[i];
			listaCaracteresInvalidos = listaCaracteresInvalidos + conector + pCaracteresInvalidos[i];
			conector = ", ";
		}
	
		var filtro = eval("/^([^" + listaFiltro + "])*$/");
		if (!filtro.test(str)) {
			pCampo.value = str.substr(0, tam - 1);
			exibirMensagem((mensagemGlobal(45)).replace(CD_CAMPO_SUBSTITUICAO, listaCaracteresInvalidos));
			focarCampo(pCampo);
		}
	}
}

// Valida o preenchimento do campo texto alfabetico "pCampo" passado como parametro
function validarCampoTextoAlfabetico(pCampo, pTmMaximo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z��������������������\n\r\t ])*$/;
	if (!filtro.test(str)) {
		if (tam > pTmMaximo) {
			pCampo.value = str.substr(0, pTmMaximo);
		} else {
			pCampo.value = str.substr(0, tam - 1);
		}
		exibirMensagem(mensagemGlobal(42));
		focarCampo(pCampo);
	}

	if (pTmMaximo > 0 && tam > pTmMaximo) {
		pCampo.value = str.substr(0, pTmMaximo);
		exibirMensagem(mensagemGlobal(36).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo texto alfabetico com caracter coringa "pCampo" passado como parametro
function validarCampoTextoAlfabeticoCoringa(pCampo, pTmMaximo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = /^([a-zA-Z��������������������*\n\r\t ])*$/;
	if (!filtro.test(str)) {
		if (tam > pTmMaximo) {
			pCampo.value = str.substr(0, pTmMaximo);
		} else {
			pCampo.value = str.substr(0, tam - 1);
		}
		exibirMensagem(mensagemGlobal(42));
		focarCampo(pCampo);
	}

	if (pTmMaximo > 0 && tam > pTmMaximo) {
		pCampo.value = str.substr(0, pTmMaximo);
		exibirMensagem(mensagemGlobal(36).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		focarCampo(pCampo);
	}
}

// Valida o preenchimento do campo texto "pCampo" passado como parametro, aplicando a express�o regular pFiltro
function validarCampoTextoExpressaoRegular(pCampo, pFiltro, pTxMensagem, pTmMaximo, pEvento) {
	var str = pCampo.value;
	var tam = str.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	var filtro = pFiltro;
	if (!filtro.test(str)) {
		if (tam > pTmMaximo) {
			pCampo.value = str.substr(0, pTmMaximo);
		} else {
			pCampo.value = str.substr(0, tam - 1);
		}
		exibirMensagem(pTxMensagem);
		focarCampo(pCampo);
	}

	if (pTmMaximo > 0 && tam > pTmMaximo) {
		pCampo.value = str.substr(0, pTmMaximo);
		exibirMensagem(mensagemGlobal(36).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		focarCampo(pCampo);
	}
}

// Verifica se existe um texto valido no campo "pCampo" passado como parametro
function isCampoTextoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem, pCaracteresInvalidos) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;

	var listaFiltro = "";
	var listaCaracteresInvalidos = "";
	var conector = "";

	if (pInObrigatorio) {
		if (pCampo.className == "campoobrigatorio") {
			msg = "\n" + mensagemGlobal(0);
		} else {
			msg = "\n" + mensagemGlobal(12);
		}

	} else {
		msg = "\n" + mensagemGlobal(1);
	}

	if (pInObrigatorio && str == "") {
		pCampo.select();
		if (!pSemMensagem) {
			if (pCampo.className == "campoobrigatorio") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(13));
			}
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if (!pSemMensagem) {
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if (!pSemMensagem) {
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (pCaracteresInvalidos != null) {

		for (i = 0; i < pCaracteresInvalidos.length; i++) {
			listaFiltro = listaFiltro + pCaracteresInvalidos[i];
			listaCaracteresInvalidos = listaCaracteresInvalidos + conector + pCaracteresInvalidos[i];
			conector = ", ";
		}

		if (pInObrigatorio) {
			var filtro = eval("/^([^" + listaFiltro + "])+$/");
	
			if (pCampo.className == "campoobrigatorio") {
				msg = "\n" + mensagemGlobal(0);
			} else {
				msg = "\n" + mensagemGlobal(12);
			}
	
		} else {
			var filtro = eval("/^([^" + listaFiltro + "])*$/");
			msg = "\n" + mensagemGlobal(1);
		}

		if (!filtro.test(str)) {
			pCampo.select();
			if (!pSemMensagem){
				exibirMensagem((mensagemGlobal(45) + msg).replace(CD_CAMPO_SUBSTITUICAO, listaCaracteresInvalidos));
			}
			focarCampo(pCampo);
			return false;
		}
	}
	
	return true;
}

// Verifica se existe um texto alfab�tico valido no campo "pCampo" passado como parametro
function isCampoTextoAlfabeticoValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;

	if (pInObrigatorio) {
		var filtro = /^([a-zA-Z��������������������\n\r\t ])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^([a-zA-Z��������������������\n\r\t ])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem(mensagemGlobal(2));
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}
	
	return true;
}

// Verifica se existe um texto alfab�tico valido no campo "pCampo" passado como parametro
function isCampoTextoAlfabeticoCoringaValido(pCampo, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	pCampo.value = trim(pCampo.value);
	var str = pCampo.value;

	if (pInObrigatorio) {
		var filtro = /^([a-zA-Z��������������������*\n\r\t ])+$/;
		msg = "\n" + mensagemGlobal(0);
	} else {
		var filtro = /^([a-zA-Z��������������������*\n\r\t ])*$/;
		msg = "\n" + mensagemGlobal(1);
	}

	if (!filtro.test(str)) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem(mensagemGlobal(2));
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}
	
	return true;
}

// Verifica se existe um texto em "pCampo" v�lido de acordo com a express�o regular "pFiltro" passada como par�metro
function isCampoTextoExpressaoRegularValido(pCampo, pFiltro, pTxMensagem, pInObrigatorio, pTmMinimo, pTmMaximo, pSemMensagem) {
	var str = pCampo.value;
	var filtro = pFiltro;
	
	if (pInObrigatorio) {
		msg = "\n" + mensagemGlobal(0);
	} else {
		msg = "\n" + mensagemGlobal(1);
	}
	
	if (!filtro.test(str)) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem(pTxMensagem);
		}
		focarCampo(pCampo);
		return false;
	}
	
	if (str != "" && pTmMinimo > 0 && str.length < pTmMinimo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(35) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMinimo));
		}
		focarCampo(pCampo);
		return false;
	}

	if (str != "" && pTmMaximo > 0 && str.length > pTmMaximo) {
		pCampo.select();
		if (!pSemMensagem){
			exibirMensagem((mensagemGlobal(36) + msg).replace(CD_CAMPO_SUBSTITUICAO, pTmMaximo));
		}
		focarCampo(pCampo);
		return false;
	}
	
	return true;
}
