/*

 Descri��o:
 - Cont�m fun��es para tratamento de campos monet�rios

 Depend�ncias:
 - biblioteca_funcoes_principal.js
*/


// Valida o preenchimento do campo moeda "pCampo" passado como par�metro
function formatarCampoMoeda(pCampo, pQtCasasDecimais, pEvento) {
	var str = pCampo.value;
	var inTemCaracterInvalido = false;
	
	if (isTeclaFuncional(pEvento)) {
		return;
	}

	for (loop = 0; loop < str.length;) {
		if ((str.charAt(loop) < '0' || str.charAt(loop) > '9') && str.charAt(loop) != '-' && str.charAt(loop) != ',') {
			str = str.substring(0, loop) + str.substring(loop + 1);
			inTemCaracterInvalido = true;
		} else {
			loop++;
		}
	}
	while (str.indexOf(',') > 0 && str.indexOf(',') < str.length - pQtCasasDecimais - 1) {
		str = str.substring(0, str.length - 1);
		inTemCaracterInvalido = true;
	}
	while (str.indexOf('-') > 0 || str.indexOf('-') != str.lastIndexOf('-')) {
		str = str.substring(0, str.lastIndexOf('-')) + str.substring(str.lastIndexOf('-') + 1);
		inTemCaracterInvalido = true;
	}

	if (inTemCaracterInvalido) {
		pCampo.value = str;
	}
	
	return true;
}

// Valida o preenchimento do campo moeda "pCampo" passado como par�metro
function formatarCampoMoedaPositivo(pCampo, pQtCasasDecimais, pEvento) {
	var str = pCampo.value;
	var inTemCaracterInvalido = false;
	
	if (isTeclaFuncional(pEvento)) {
		return;
	}

	for (loop = 0; loop < str.length;) {
		if ((str.charAt(loop) < '0' || str.charAt(loop) > '9') && str.charAt(loop) != ',') {
			str = str.substring(0, loop) + str.substring(loop + 1);
			inTemCaracterInvalido = true;
		} else {
			loop++;
		}
	}
	while (str.indexOf(',') > 0 && str.indexOf(',') < str.length - pQtCasasDecimais - 1) {
		str = str.substring(0, str.length - 1);
		inTemCaracterInvalido = true;
	}

	if (inTemCaracterInvalido) {
		pCampo.value = str;
	}
	
	return true;
}

// Valida o preenchimento do campo moeda "pCampo" passado como par�metro
function formatarCampoMoedaComSeparadorMilhar(pCampo, pQtCasasDecimais, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;
	var isNegativo = false;

	if (isTeclaFuncional(pEvento, true)) {
		return;
	}

	if (tam == 0) {
		return;
	}

	// Tira os '.'
	while (vlCampo.indexOf(".") != -1) {
		vlCampo = vlCampo.replace(".", "");
	}
	// Tira as ','
	while (vlCampo.indexOf(",") != -1) {
		vlCampo = vlCampo.replace(",", "");
	}
	// Tira os espacos em branco
	while (vlCampo.indexOf(" ") != -1) {
		vlCampo = vlCampo.replace(" ", "");
	}

	var filtro = /^-{0,1}([0-9])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = pCampo.value.substr(0, tam - 1);
		pCampo.select();
		exibirMensagem(mensagemGlobal(390));
		focarCampo(pCampo);
		return;
	}

	// Trata sinal de menos
	if (vlCampo.indexOf("-") == 0) {
		vlCampo = vlCampo.replace("-", "");
		isNegativo = true;
	}

	// Quando o campo est� em branco � preciso completar com zeros a esquerda
	if (vlCampo.length <= pQtCasasDecimais) {
		var tamAux = vlCampo.length;
		for (i = tamAux; i < (pQtCasasDecimais); i++) {
			vlCampo = "0" + vlCampo;
		}
	}
	
	var auxStrInteiro = vlCampo.substring(0, vlCampo.length - pQtCasasDecimais);
	var auxStrDecimal = vlCampo.substring(vlCampo.length - pQtCasasDecimais, vlCampo.length);

	var vlCampoFinal = getValorMoedaComoStringComSeparadorMilhar(auxStrInteiro, 0, true);
	
	if (isNegativo) {
	  vlCampoFinal = "-" + vlCampoFinal;
	}

	pCampo.value = vlCampoFinal + auxStrDecimal;
}

// Valida o preenchimento do campo moeda "pCampo" passado como par�metro
function formatarCampoMoedaPositivoComSeparadorMilhar(pCampo, pQtCasasDecimais, pEvento) {
	var str = pCampo.value;
	var inTemCaracterInvalido = false;

	for (loop = 0; loop < str.length;) {
		if ((str.charAt(loop) < '0' || str.charAt(loop) > '9') && str.charAt(loop) != ',' && str.charAt(loop) != '.') {
			str = str.substring(0, loop) + str.substring(loop + 1);
			inTemCaracterInvalido = true;
		} else {
			loop++;
		}
	}

	if (inTemCaracterInvalido) {
		pCampo.value = str;
	}

	formatarCampoMoedaComSeparadorMilhar(pCampo, pQtCasasDecimais, pEvento);
}

// Verifica se existe um valor moeda v�lido no campo "pCampo" passado como parametro
function isCampoMoedaValido(pCampo, pQtCasasDecimais, pInObrigatorio, pVlMinimo, pVlMaximo, pSemMensagem, pSemFocarCampo) {
    return _isCampoMoedaValido(pCampo, pQtCasasDecimais, pInObrigatorio, pVlMinimo, pVlMaximo, pSemMensagem, pSemFocarCampo, false);
}

// Verifica se existe um valor moeda v�lido no campo "pCampo" passado como parametro
function isCampoMoedaComSeparadorMilharValido(pCampo, pQtCasasDecimais, pInObrigatorio, pVlMinimo, pVlMaximo, pSemMensagem, pSemFocarCampo) {
    return _isCampoMoedaValido(pCampo, pQtCasasDecimais, pInObrigatorio, pVlMinimo, pVlMaximo, pSemMensagem, pSemFocarCampo, true);
}

function _isCampoMoedaValido(pCampo, pQtCasasDecimais, pInObrigatorio, pVlMinimo, pVlMaximo, pSemMensagem, pSemFocarCampo, pInComSeparadorMilhar) {
	
	var str = pCampo.value;
	var strAux;
	
	if (pInComSeparadorMilhar) {
		while (str.indexOf('.') != -1) {
			str = str.replace('.', '');
		}
		str = str.replace(",", ".");
		
	} else {
		str = str.replace(",", ".");
	}

	var tam = str.length;
	var floatAux = parseFloat(str);
	
	if (pInObrigatorio) {	
		var expReg = "^-{0,1}[0-9]+(\\.[0-9]{0," + pQtCasasDecimais + "})?$";
		var filtro = new RegExp(expReg);
		var msg = "\n" + mensagemGlobal(0);
	} else {
		var expReg = "^-{0,1}[0-9]*(\\.[0-9]{0," + pQtCasasDecimais + "})?$";
		var filtro = new RegExp(expReg);
		var msg = "\n" + mensagemGlobal(1);
	}
		
	if (!filtro.test(str)) {
		if (!pSemFocarCampo) {
			pCampo.select();
		}
		if (!pSemMensagem) {
			if (str == "" || trim(str) == ".") {
				exibirMensagem(mensagemGlobal(2));
			} else {
				exibirMensagem(mensagemGlobal(390) + msg);
			}
		}
		if (!pSemFocarCampo) {
			pCampo.focus();
		}
		return false;
	}
	
	if (!isNaN(floatAux) && pVlMaximo != null && floatAux > pVlMaximo) {
		if (!pSemFocarCampo) {
			pCampo.select();
		}
		if (!pSemMensagem) {
		    if (pInComSeparadorMilhar) {
				strAux = getValorMoedaComoStringComSeparadorMilhar(pVlMaximo, pQtCasasDecimais, true);
			} else {
				strAux = getValorMoedaComoString(pVlMaximo, pQtCasasDecimais, true);
			}
			exibirMensagem((mensagemGlobal(32) + msg).replace(CD_CAMPO_SUBSTITUICAO, strAux));
		}
		if (!pSemFocarCampo) {
			pCampo.focus();
		}
		return false;
	}

	if (!isNaN(floatAux) && pVlMinimo != null && floatAux < pVlMinimo) {
		if (!pSemFocarCampo) {
			pCampo.select();
		}
		if (!pSemMensagem) {
		    if (pInComSeparadorMilhar) {
				strAux = getValorMoedaComoStringComSeparadorMilhar(pVlMinimo, pQtCasasDecimais, true);
			} else {
				strAux = getValorMoedaComoString(pVlMinimo, pQtCasasDecimais, true);
			}
			exibirMensagem((mensagemGlobal(33) + msg).replace(CD_CAMPO_SUBSTITUICAO, strAux));
		}
		if (!pSemFocarCampo) {
			pCampo.focus();
		}
		return false;
	}

	return true;
}

// Completa o preenchimento do campo moeda "pCampo" passado como par�metro
function completarCampoMoeda(pCampo, pQtCasasDecimais) {
	var str = pCampo.value;
	var menos = "";
	var tam = str.length;

	if (tam > 0) {
		// checa se eh negativo
		if (tam > 0 && str.charAt(0) == '-') {
			menos = "-";
			str = str.replace("-", "");
			tam--;
		}

		if (str.indexOf(",") != -1) {
			while (tam > (pQtCasasDecimais + 2) && str.charAt(0) == '0') { // pode ter zeros sobrando
				str = str.substr(1);
				tam--;
			}
		}

		if (str.indexOf(",") == -1) {
			while (tam <= pQtCasasDecimais) {
				str = "0" + str;
				tam++;
			}
			str = str.substr(0, tam - pQtCasasDecimais) + ',' + str.substr(tam - pQtCasasDecimais);
		}

		pCampo.value = menos + str;
	}
}

function getValorCampoMoeda(pCampo) {
	var str;
	var indice_separador_milhar;
	var tem_separador_milhar;
	var aux_str;

	str = pCampo.value;
	
	// elimina os separadores de milhar
	indice_separador_milhar = str.indexOf('.');
	tem_separador_milhar = indice_separador_milhar != -1;

	while (tem_separador_milhar) {
		aux_str = str.substring(0, indice_separador_milhar);
		str = aux_str + str.substring(indice_separador_milhar + 1);
	
		indice_separador_milhar = str.indexOf('.');
		tem_separador_milhar = indice_separador_milhar != -1;		
	}

	// altera o separador decimal
	str = str.replace(",", ".");
	
	return str;
}

function getValorCampoMoedaComoNumero(pCampo) {
	var str;
	var indice_separador_milhar;
	var tem_separador_milhar;
	var aux_str;

	str = pCampo.value;
	
	// elimina os separadores de milhar
	indice_separador_milhar = str.indexOf('.');
	tem_separador_milhar = indice_separador_milhar != -1;

	while (tem_separador_milhar) {
		aux_str = str.substring(0, indice_separador_milhar);
		str = aux_str + str.substring(indice_separador_milhar + 1);
	
		indice_separador_milhar = str.indexOf('.');
		tem_separador_milhar = indice_separador_milhar != -1;		
	}

	// altera o separador decimal
	str = str.replace(",", ".");
	
	return parseFloat(str);
}

// Atualiza o valor do campo 'pCampo' com 'pValor'
function setValorCampoMoeda(pCampo, pValor, pQtCasasDecimais) {
	pCampo.value = getValorMoedaComoString(pValor, pQtCasasDecimais);
}

// Atualiza o valor do campo 'pCampo' com 'pValor'
function setValorCampoMoedaComSeparadorMilhar(pCampo, pValor, pQtCasasDecimais) {
	pCampo.value = getValorMoedaComoString(pValor, pQtCasasDecimais, true);
	formatarCampoMoedaComSeparadorMilhar(pCampo, pQtCasasDecimais);
}

// Arredonda um valor moeda para o n�mero mais pr�ximo
function arredondarValorMoeda(pVlMoeda, pQtCasasDecimais) {
	var base = Math.pow(10, pQtCasasDecimais);
	var resultado = Math.round(pVlMoeda*base)/base;
	
	return resultado;
}

// Arredonda um valor moeda para cima
function arredondarValorMoedaParaCima(pVlMoeda, pQtCasasDecimais) {
	var base = Math.pow(10, pQtCasasDecimais);
	var resultado = Math.ceil(pVlMoeda * base) / base;
	
	return resultado;
}

// Arredonda um valor moeda para baixo
function arredondarValorMoedaParaBaixo(pVlMoeda, pQtCasasDecimais) {
	var base = Math.pow(10, pQtCasasDecimais);
	var resultado = Math.floor(pVlMoeda * base) / base;
	
	return resultado;
}

function getValorMoedaComoString(pVlMoeda, pQtCasasDecimais, pInColocarCasasDecimaisSeValorInteiro) {
	if (pVlMoeda == "") {
		pVlMoeda = 0;
	}
	var vlMoeda = parseFloat(pVlMoeda);

	if (vlMoeda.toString().indexOf(".") > -1) {
		var str = vlMoeda.toFixed(pQtCasasDecimais);
	} else {
		var str = vlMoeda.toString();
	}
	var pos;
	
	str = str.replace(".", ",");

	pos = str.indexOf(",");
	
	if ((pos == -1) && pInColocarCasasDecimaisSeValorInteiro) {
		str = str + ",";
		pos = pos = str.indexOf(",");
	}
	
	if ((pos != -1) && (pos >= str.length - pQtCasasDecimais)) {
		var tam = 0;
		while (str.indexOf(",") >= str.length - pQtCasasDecimais) {
			str = str + "0";
			tam++;
		}
	}
	
	return str;
}

function getValorMoedaComoStringComSeparadorMilhar(pVlMoeda, pQtCasasDecimais, pInColocarCasasDecimaisSeValorInteiro) {
	var vlMoeda = getValorMoedaComoString(pVlMoeda, pQtCasasDecimais, pInColocarCasasDecimaisSeValorInteiro);
	var tam = vlMoeda.length;
	var isNegativo = false;

	if (tam == 0) {
		return "";
	}

	// Tira os '.'
	while (vlMoeda.indexOf(".") != -1) {
		vlMoeda = vlMoeda.replace(".", "");
	}
	// Tira a ','
	while (vlMoeda.indexOf(",") != -1) {
		vlMoeda = vlMoeda.replace(",", "");
	}
	// Trata sinal de menos
	if (vlMoeda.indexOf("-") == 0) {
		vlMoeda = vlMoeda.replace("-", "");
		isNegativo = true;
	}

	var auxStrInteiro = vlMoeda.substring(0, vlMoeda.length - pQtCasasDecimais);
	var auxStrDecimal = vlMoeda.substring(vlMoeda.length - pQtCasasDecimais, vlMoeda.length);

	auxStrInteiro = parseInt(auxStrInteiro, 10) + "";

	var auxStr = "";
	var contagemReversa = 0;
	var i = auxStrInteiro.length - 1;

	for (i; i >= 0; i--) {
		if (contagemReversa != 0 && (contagemReversa - 3) % 3 == 0) {
			auxStr = "." + auxStr;
		}
		contagemReversa++;
		if (auxStrInteiro.charAt(i) != "0" || auxStrInteiro.charAt(i - 1) != "") {  
			auxStr = auxStrInteiro.charAt(i) + auxStr;
		}
	}
	
	if (auxStr == "") {
		auxStr = "0";
	}

	vlMoeda = auxStr + "," + auxStrDecimal;
	
	if (isNegativo) {
		vlMoeda = "-" + vlMoeda;
	}
	
	return vlMoeda;
}
