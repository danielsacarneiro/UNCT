
/*
 Descrição:
 - Contém funções de validação e formatação de campos CNPF e CNPJ

 Dependências:
 - biblioteca_funcoes_principal.js
*/


// Formata o valor como campo CNPF ou CNPJ de acordo como tamanho
function formatarCampoCNPFouCNPJ(pCampo, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	// Tira os '.'
	while (vlCampo.indexOf('.') != -1) {
		vlCampo = vlCampo.replace('.', '');
	}
	// Tira os '-'
	while (vlCampo.indexOf('-') != -1) {
		vlCampo = vlCampo.replace('-', '');
	}
	
	// Tira os '/'
	while (vlCampo.indexOf('/') != -1) {
		vlCampo = vlCampo.replace('/', '');
	}
	
	tam = vlCampo.length;
	
	
	/*if (tam > 14) {
		tam = 14;
		vlCampo = vlCampo.substr(0, 14);
	}*/
			
	if ((tam < 11 ) || (tam > 11 && tam < 14)){
		return;	
	}
	if (tam == 11 && isCampoCNPFouCNPJValido(pCampo, true, true)){
		formatarCampoCNPF(pCampo, pEvento)
	}else if (tam == 14 ){
		formatarCampoCNPJ(pCampo, pEvento)
	}
}

// Valida o valor como campo CNPF ou CNPJ de acordo como tamanho
function isCampoCNPFouCNPJValido(pCampo, pInObrigatorio, pSemMensagem) {
  var vlCampo = pCampo.value;
  var tam = vlCampo.length;

	if (tam <= 14) {
		return isCampoCNPFValido(pCampo, pInObrigatorio, pSemMensagem);
	}else{
		return isCampoCNPJValido(pCampo, pInObrigatorio, pSemMensagem);	
	}
}

// Formata o campo CNPF "pCampo" passado como parâmetro
function formatarCampoCNPF(pCampo, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	// Ignorando a tentativa de colocar máscara durante a digitação em dispositivos móveis
	if (pEvento && isAcessoMovel() && pEvento.type == "keyup") {
		return;
	}

	// Tira os '.'
	while (vlCampo.indexOf('.') != -1) {
		vlCampo = vlCampo.replace('.', '');
	}
	// Tira os '-'
	while (vlCampo.indexOf('-') != -1) {
		vlCampo = vlCampo.replace('-', '');
	}
	// Caso seja grande demais, trunca.
	var tamanho = vlCampo.length;
	/*if (tamanho > 11) {
		tamanho = 11;
		vlCampo = vlCampo.substr(0, 11);
	}*/

	var filtro = /^([0-9])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.select();
		exibirMensagem("CPF Inválido.");
		pCampo.value = pCampo.value.substr(0, tam - 1);
		focarCampo(pCampo);
		return;
	}

	if (tamanho > 3 && tamanho <= 6) {
		vlCampo = vlCampo.substr(0, 3) + '.' + vlCampo.substr(3);
	} else if (tamanho > 6 && tamanho <= 9) {
		vlCampo = vlCampo.substr(0, 3) + '.' + vlCampo.substr(3, 3) + '.' + vlCampo.substr(6);
	} else if (tamanho > 9) {
		vlCampo = vlCampo.substr(0, 3) + '.' + vlCampo.substr(3, 3) + '.' + vlCampo.substr(6, 3) + '-' + vlCampo.substr(9);
	}

	pCampo.value = vlCampo;

	/*if (tamanho >= 11) {
		isCampoCNPFValido(pCampo);
	}*/
}

// Valida o campo CNPF "pCampo" passado como parâmetro
function isCampoCNPFValido(pCampo, pInObrigatorio, pSemMensagem) {
	var msg = "";
	var vlCampo = pCampo.value;

	if (pInObrigatorio != null) {
		if (pInObrigatorio) {
			if (pCampo.className == "campoobrigatorio") {
				msg = "\n" + "Campo obrigatório.";
			} else {
				msg = "\n" + "Campo NÃO obrigatório.";
			}

		} else {
			msg = "\n" + "Campo NÃO obrigatório.";

			if (vlCampo == "")
				return true;
		}
	}

	var filtro = /^([0-9.-])*$/;
	if (!filtro.test(vlCampo)) {
		if (!pSemMensagem) {
			selecionarCampo(pCampo);
			exibirMensagem("CPF Inválido." + msg);
			focarCampo(pCampo);
		}

		return false;
	}

	if (vlCampo.length != 11) {
		if (!pSemMensagem) {
			selecionarCampo(pCampo);
			exibirMensagem("CPF Inválido." + msg);
			focarCampo(pCampo);
		}

		return false;
	}
	
	numcnpf = vlCampo;
	numcnpf = numcnpf.toString().replace("-", "");
	numcnpf = numcnpf.toString().replace(".", "");
	numcnpf = numcnpf.toString().replace(".", "");
	numcnpf = numcnpf.toString().replace("/", "");
	numcnpf1 = "";

	x = 0;
	soma = 0;
	dig1 = 0;
	dig2 = 0;
	texto = "";

    if (numcnpf == "00000000000" || numcnpf == "11111111111" || 
   	 	numcnpf == "22222222222" || numcnpf == "33333333333" || 
    	numcnpf == "44444444444" || numcnpf == "55555555555" || 
    	numcnpf == "66666666666" || numcnpf == "77777777777" || 
    	numcnpf == "88888888888" || numcnpf == "99999999999") {
    	
		if (!pSemMensagem) {
			selecionarCampo(pCampo);
			exibirMensagem("CPF Inválido." + msg);
			focarCampo(pCampo);
		}
		
		return false;
    }

	len = numcnpf.length;
	x = len - 1;

	for (var i = 0; i <= len - 3; i++) {
		y = numcnpf.substring(i, i + 1);
		soma = soma + (y * x);
		x = x - 1;
		texto = texto + y;
	}

	// retorna o resto da divisão por 11
	dig1 = 11 - (soma % 11);

	if (dig1 == 10)
		dig1 = 0;
	if (dig1 == 11)
		dig1 = 0;

	numcnpf1 = numcnpf.substring(0, len - 2) + dig1;
	x = 11;
	soma = 0;

	for (var i = 0; i <= len - 2; i++) {
		soma = soma + (numcnpf1.substring(i, i + 1) * x);
		x = x - 1;

	}
	dig2 = 11 - (soma % 11);

	if (dig2 == 10)
		dig2 = 0;
	if (dig2 == 11)
		dig2 = 0;

	if (((dig1 + "" + dig2) == numcnpf.substring(len, len - 2)) && numcnpf != 0) {
		return true;
	}else{
		if (!pSemMensagem) {
			selecionarCampo(pCampo);
			exibirMensagem("CPF Inválido." + msg);
			focarCampo(pCampo);
		}
	
		return false;
	}
	
	return true;
}

// Formata o campo CNPJ "pCampo" passado como parâmetro
function formatarCampoCNPJ(pCampo, pEvento) {

	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	// Tira os '.'
	while (vlCampo.indexOf('.') != -1) {
		vlCampo = vlCampo.replace('.', '');
	}
	// Tira os '-'
	while (vlCampo.indexOf('-') != -1) {
		vlCampo = vlCampo.replace('-', '');
	}
	// Tira os '/'
	while (vlCampo.indexOf('/') != -1) {
		vlCampo = vlCampo.replace('/', '');
	}

	// Caso seja grande demais, trunca.
	var tamanho = vlCampo.length;
	/*if (tamanho > 14) {
		tamanho = 14;
		vlCampo = vlCampo.substr(0, 14);
	}*/

	var filtro = /^([0-9])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.select();
		exibirMensagem("CNPJ inválido.");
		pCampo.value = vlCampo.substr(0, tam - 1);
		focarCampo(pCampo);
		return;
	}

	if (tamanho > 2 && tamanho <= 5) {
		vlCampo = vlCampo.substr(0, 2) + '.' + vlCampo.substr(2);
	} else if (tamanho > 5 && tamanho <= 8) {
		vlCampo = vlCampo.substr(0, 2) + '.' + vlCampo.substr(2, 3) + '.' + vlCampo.substr(5);
	} else if (tamanho > 8 && tamanho <= 12) {
		vlCampo = vlCampo.substr(0, 2) + '.' + vlCampo.substr(2, 3) + '.' + vlCampo.substr(5, 3) + '/' + vlCampo.substr(8);
	} else if (tamanho > 12) {
		vlCampo =
			vlCampo.substr(0, 2)
				+ '.'
				+ vlCampo.substr(2, 3)
				+ '.'
				+ vlCampo.substr(5, 3)
				+ '/'
				+ vlCampo.substr(8, 4)
				+ '-'
				+ vlCampo.substr(12);
	}

	pCampo.value = vlCampo;

	if (vlCampo.length >= 18) {
		isCampoCNPJValido(pCampo);
	}
}

// Valida o campo CNPJ "pCampo" passado como parâmetro
function isCampoCNPJValido(pCampo, pInObrigatorio, pSemMensagem) {
	var msg = "";
	var vlCampo = pCampo.value;

	if (pInObrigatorio != null) {
		if (pInObrigatorio) {
			msg = "\n" + "Campo obrigatório.";

		} else {
			msg = "\n" + "Campo NÃO obrigatório.";

			if (vlCampo == "")
				return true;
		}
	}

	var filtro = /^([0-9\.\-\/])*$/;
	if (!filtro.test(vlCampo)) {
		if (!pSemMensagem) {
			pCampo.select();
			exibirMensagem("CNPJ inválido." + msg);
			focarCampo(pCampo);
		}

		return false;
	}

	if (vlCampo.length != 18) {
		if (!pSemMensagem) {
			pCampo.select();
			exibirMensagem("CNPJ inválido." + msg);
			focarCampo(pCampo);
		}

		return false;
	}

	numcnpj = vlCampo;
	numcnpj = numcnpj.toString().replace("-", "");
	numcnpj = numcnpj.toString().replace("/", "");
	numcnpj = numcnpj.toString().replace(".", "");
	numcnpj = numcnpj.toString().replace(".", "");

	var varFirstChr = numcnpj.charAt(0);
	var vlMult, vlControle, s1, s2 = "";
	var i, j, vlDgito, vlSoma = 0;
	vaCharCGC = false;

	for (var i = 0; i <= 13; i++) {

		var c = numcnpj.charAt(i);
		if (!(c >= "0") && (c <= "9")) {
			if (!pSemMensagem) {
				pCampo.select();
				exibirMensagem("CNPJ inválido." + msg);
				focarCampo(pCampo);
			}

			return false;
		}
		if (c != varFirstChr) {
			vaCharCGC = true;
		}
	}

	if (!vaCharCGC) {
		if (!pSemMensagem) {
			pCampo.select();
			exibirMensagem("CNPJ inválido." + msg);
			focarCampo(pCampo);
		}

		return false;
	}

	s1 = numcnpj.substring(0, 12);
	s2 = numcnpj.substring(12, 15);
	vlMult = "543298765432";
	vlControle = "";

	for (j = 1; j < 3; j++) {

		vlSoma = 0;
		for (i = 0; i < 12; i++) {
			vlSoma += eval(s1.charAt(i)) * eval(vlMult.charAt(i));
		}
		if (j == 2) {
			vlSoma += (2 * vlDgito);
		}
		vlDgito = ((vlSoma * 10) % 11);
		if (vlDgito == 10) {
			vlDgito = 0;
		}
		vlControle = vlControle + vlDgito;
		vlMult = "654329876543";
	}

	if (vlControle != s2) {
		if (!pSemMensagem) {
			pCampo.select();
			exibirMensagem("CNPJ inválido." + msg);
			focarCampo(pCampo);
		}

		return false;

	} else {
		return true;
	}
}

function isCampoRadicalCNPJValido(pCampo, pInObrigatorio) {	
	var msg = "";
	var vlCampo = pCampo.value;

	if (pInObrigatorio != null) {
		if (pInObrigatorio) {
			msg = "\n" + "Campo obrigatório.";

		} else {
			msg = "\n" + "Campo NÃO obrigatório.";
			if (vlCampo == "")
				return true;
		}
	}
		
	var filtro = /^([0-9.])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.select();
		exibirMensagem("CNPJ inválido." + msg);
		focarCampo(pCampo);

		return false;
	}	
		
	if (vlCampo.length > 10) {
		pCampo.select();
		exibirMensagem("CNPJ inválido." + msg);
		focarCampo(pCampo);

		return false;
	} else {

		return true;			
	}
}
