/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */

/*
 Descriï¿½ï¿½o:
 - Contï¿½m funï¿½ï¿½es de validaï¿½ï¿½o e formataï¿½ï¿½o de campos data e hora
 Dependï¿½ncias:
 - biblioteca_funcoes_principal.js
*/
function formatarDataWeb(data) {
    var d = new Date(data),
        mes = '' + (d.getMonth() + 1),
        dia = '' + d.getDate(),
        ano = d.getFullYear();

    if (mes.length < 2) mes = '0' + mes;
    if (dia.length < 2) dia = '0' + dia;

    return [dia, mes, ano].join('/'); // "join" Ã© o caracter para separar a formataÃ§Ã£o da data, neste caso, a barra (/)
}

function formatarDataParaInternacional(data) {
	var anoParts = data.split('/');
	var dia =anoParts[0];
	var mes =anoParts[1];
	var ano =anoParts[2];

    return [mes,dia, ano].join('/'); // "join" Ã© o caracter para separar a formataÃ§Ã£o da data, neste caso, a barra (/)
}

function getAnoData(data) {
	data=formatarDataParaInternacional(data); 
    var d = new Date(data);
    ano = d.getFullYear();

    return ano;
}

function isCampoDataValidoPorCampoAno(pCampoData, pCampoAno) {
	retorno = true;
	if(pCampoData != null && pCampoAno != null){
		data = pCampoData.value;
	    ano = getAnoData(data);
	    
	    //alert("data:" + data + " ano:" + ano + " data:" + pCampoAno.value);
	    
	    if(ano != pCampoAno.value){
	    	pCampoData.focus();
	    	retorno = false;	    	
	    }
	}
    return retorno;
}

function getAnoAtual() {
	
	/*var mydate=new Date();
	var year=mydate.getYear();
	if (year < 1000)
		year+=1900;
	
	*/
	return new Date().getFullYear();

	//return year;
}

function calculaIdade(dataNasc){ 
	var dataAtual = new Date();
	var anoAtual = dataAtual.getFullYear();
	var anoNascParts = dataNasc.split('/');
	var diaNasc =anoNascParts[0];
	var mesNasc =anoNascParts[1];
	var anoNasc =anoNascParts[2];
	var idade = anoAtual - anoNasc;
	var mesAtual = dataAtual.getMonth() + 1; 
	//se mÃªs atual for menor que o nascimento, nao fez aniversario ainda; (26/10/2009) 
	if(mesAtual < mesNasc){
		idade--; 
	}else {
		//se estiver no mes do nasc, verificar o dia
		if(mesAtual == mesNasc){ 
			if(dataAtual.getDate() < diaNasc ){ 
				//se a data atual for menor que o dia de nascimento ele ainda nao fez aniversario
				idade--; 
			}
		}
	} 
	return idade; 
}

function formatarCampoData(pCampo, pEvento, pInMesAno) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;
	
	if (isTeclaFuncional(pEvento)) {
		return;
	}
	
	if (pEvento != null && pEvento.keyCode == 111) {
		if (vlCampo.length != 3 && vlCampo.length != 6) {
			pCampo.value = vlCampo.substr(0, tam - 1);
		}
		return;
	}

	var filtro = /^([0-9\/])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = vlCampo.substr(0, tam - 1);
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo + '/';
		pCampo.value = vlCampo;
	}
	if (vlCampo.length > 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo.substring(0, 2) + '/' + vlCampo.substring(2);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length == 5) {
		if (pInMesAno) {
			if (vlCampo.substr(3, 4) <= 12) {
				vlCampo = vlCampo + '/';
				pCampo.value = vlCampo;
			}
		} else {
			vlCampo = vlCampo + '/';
			pCampo.value = vlCampo;
		}
	}
	if (vlCampo.length > 5 && vlCampo.charAt(5) != '/') {
		vlCampo = vlCampo.substring(0, 5) + '/' + vlCampo.substring(5);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length > 6 && vlCampo.charAt(6) == '0') {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + '\n' + mensagemGlobal(133));
		focarCampo(pCampo);
		return;
	} else if (vlCampo.length > 7 && parseInt(vlCampo.substring(6, 8)) < 19) {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + '\n' + mensagemGlobal(133));
		focarCampo(pCampo);
		return;
	} else if (vlCampo.length > 7 && parseInt(vlCampo.substring(6, 10)) > anoLimite) {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + '\n' + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite));
		focarCampo(pCampo);
		return;
	}

	if (pInMesAno && vlCampo.length >= 7) {
		if (vlCampo.substr(3, 4) > 12) {
			isCampoDataValido(pCampo, null);
		}
	}

	if (vlCampo.length == 10) {
		isCampoDataValido(pCampo, null);
	}
}

// Verifica se existe uma data vï¿½lida no campo "pCampo" passado como parametro
function isCampoDataValido(pCampo, pInObrigatorio, pInMesAno, pSemMensagem, pSemFocarCampo) {
	var msg = "";
	var vlCampo = pCampo.value;
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;
	
	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") {
		if (pInObrigatorio) {
			msg = "\n" + mensagemGlobal(0);

		} else {
			msg = "\n" + mensagemGlobal(1);

			if (vlCampo == "")
				return true;
		}
	}

	if (pInMesAno && vlCampo.length == 7) {
		var filtro = /^[0-9]{2}\/[0-9]{4}$/;
		if (!filtro.test(vlCampo)) {
			if (!pSemFocarCampo) {
				selecionarCampo(pCampo);
			}
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(120) + msg);
			}
			if (!pSemFocarCampo) {
				focarCampo(pCampo);
			}
	
			return false;
		}
	
		dia = 01;
		mes = (vlCampo.substring(0, 2));
		ano = (vlCampo.substring(3, 7));

	} else {
		var filtro = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		if (!filtro.test(vlCampo)) {
			if (!pSemFocarCampo) {
				selecionarCampo(pCampo);
			}
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(120) + msg);
			}
			if (!pSemFocarCampo) {				
				focarCampo(pCampo);
			}
	
			return false;
		}
	
		dia = (vlCampo.substring(0, 2));
		mes = (vlCampo.substring(3, 5));
		ano = (vlCampo.substring(6, 10));
	}
	
	//exibirMensagem('dia '+dia);
	//exibirMensagem('mes '+mes);
	//exibirMensagem('ano '+ano);

	situacao = "";
	// verifica o dia valido para cada mes 
	if ((dia < 1) || (dia < 1 || dia > 30) && (mes == 4 || mes == 6 || mes == 9 || mes == 11) || dia > 31) {
		situacao = "falsa";
	}

	// verifica se o mes e valido 
	if (mes < 1 || mes > 12) {
		situacao = "falsa";
	}

	// verifica se ano eh valido
	/*if (ano < 1900) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(133);
	}
	if (ano > anoLimite) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite);
	}*/

	// verifica se e ano bissexto 
	if (mes == 2 && (dia < 1 || dia > 29 || (dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
		situacao = "falsa";
	}

	if (pCampo.value == "") {
		situacao = "falsa";
	}

	if (situacao == "falsa") {
		if (!pSemFocarCampo) {
			selecionarCampo(pCampo);
		}
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + msg);
		}
		if (!pSemFocarCampo) {
			focarCampo(pCampo);
		}

		return false;
	}

	return true;
}

// Formato o campo data "pCampo" passado como parï¿½metro no formato mm/aaaa
function formatarCampoMesAno(pCampo, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;
	
	if (isTeclaFuncional(pEvento)) {
		return;
	}

	if (pEvento != null && pEvento.keyCode == 111) {
		if (vlCampo.length != 3) {
			pCampo.value = vlCampo.substr(0, tam - 1);
		}
		return;
	}

	var filtro = /^([0-9\/])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = vlCampo.substr(0, tam - 1);
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(135));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo + '/';
		pCampo.value = vlCampo;
	}
	if (vlCampo.length > 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo.substring(0, 2) + '/' + vlCampo.substring(2);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length > 3 && vlCampo.charAt(3) == '0') {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(135) + '\n' + mensagemGlobal(133));
		focarCampo(pCampo);
		return;
	} else if (vlCampo.length > 4 && parseInt(vlCampo.substring(3, 5)) < 19) {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(135) + '\n' + mensagemGlobal(133));
		focarCampo(pCampo);
		return;
	} else if (vlCampo.length > 7 && parseInt(vlCampo.substring(6, 10)) > (anoAtual + 100)) {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + '\n' + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoAtual + 100));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 7) {
		isCampoMesAnoValido(pCampo, null);
	}
}

// Verifica se existe uma data vï¿½lida no campo "pCampo" passado como parametro
function isCampoMesAnoValido(pCampo, pInObrigatorio, pSemMensagem) {
	var msg = "";
	var vlCampo = pCampo.value;
	var dataHoje = getDataHoje();
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;

	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") {
		if (pInObrigatorio) {
			msg = "\n" + mensagemGlobal(0);

		} else {
			msg = "\n" + mensagemGlobal(1);

			if (vlCampo == "")
				return true;
		}
	}

	var filtro = /^[0-9]{2}\/[0-9]{4}$/;
	if (!filtro.test(vlCampo)) {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(135) + msg);
		}
		focarCampo(pCampo);

		return false;
	}
	
	dia = 01;
	mes = (vlCampo.substring(0, 2));
	ano = (vlCampo.substring(3, 7));
	
	//exibirMensagem('dia '+dia);
	//exibirMensagem('mes '+mes);
	//exibirMensagem('ano '+ano);

	situacao = "";
	// verifica o dia valido para cada mes 
	if ((dia < 1) || (dia < 1 || dia > 30) && (mes == 4 || mes == 6 || mes == 9 || mes == 11) || dia > 31) {
		situacao = "falsa";
	}

	// verifica se o mes e valido 
	if (mes < 1 || mes > 12) {
		situacao = "falsa";
	}

	// verifica se ano eh valido
	if (ano < 1900) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(133);
	}
	if (ano > anoLimite) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite);
	}

	// verifica se e ano bissexto 
	if (mes == 2 && (dia < 1 || dia > 29 || (dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
		situacao = "falsa";
	}

	if (pCampo.value == "") {
		situacao = "falsa";
	}

	if (situacao == "falsa") {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(135) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	return true;
}

// Formato o campo data "pCampo" passado como parï¿½metro no formato dd/mm
function formatarCampoDiaMes(pCampo, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	if (pEvento != null && pEvento.keyCode == 111) {
		if (vlCampo.length != 3) {
			pCampo.value = vlCampo.substr(0, tam - 1);
		}
		return;
	}

	var filtro = /^([0-9\/])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = vlCampo.substr(0, tam - 1);
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(136));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo + '/';
		pCampo.value = vlCampo;
	}
	if (vlCampo.length > 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo.substring(0, 2) + '/' + vlCampo.substring(2);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length == 5) {
		isCampoDiaMesValido(pCampo, null);
	}
}

// Verifica se existe uma data vï¿½lida no campo "pCampo" passado como parametro
function isCampoDiaMesValido(pCampo, pInObrigatorio, pSemMensagem) {
	var msg = "";
	var vlCampo = pCampo.value;
	var dataHoje = getDataHoje();
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;

	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") {
		if (pInObrigatorio) {
			msg = "\n" + mensagemGlobal(0);

		} else {
			msg = "\n" + mensagemGlobal(1);

			if (vlCampo == "")
				return true;
		}
	}

	var filtro = /^[0-9]{2}\/[0-9]{2}$/;
	if (!filtro.test(vlCampo)) {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(136) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	dia = (vlCampo.substring(0, 2));
	mes = (vlCampo.substring(3, 5));
	ano = 2000
	
	//exibirMensagem('dia '+dia);
	//exibirMensagem('mes '+mes);
	//exibirMensagem('ano '+ano);

	situacao = "";
	// verifica o dia valido para cada mes 
	if ((dia < 1) || (dia < 1 || dia > 30) && (mes == 4 || mes == 6 || mes == 9 || mes == 11) || dia > 31) {
		situacao = "falsa";
	}

	// verifica se o mes e valido 
	if (mes < 1 || mes > 12) {
		situacao = "falsa";
	}

	// verifica se ano eh valido
	if (ano < 1900) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(133);
	}
	if (ano > anoLimite) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite);
	}

	// verifica se e ano bissexto 
	if (mes == 2 && (dia < 1 || dia > 29 || (dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
		situacao = "falsa";
	}

	if (pCampo.value == "") {
		situacao = "falsa";
	}

	if (situacao == "falsa") {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(136) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	return true;
}

// Formato o campo hora "pCampo" passado como parï¿½metro no formato hh:mm
function formatarCampoHora(pCampo, pEvento) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	if (pEvento != null && pEvento.keyCode == 191) {
		if (vlCampo.length != 3) {
			pCampo.value = vlCampo.substr(0, tam - 1);
		}
		return;
	}

	var filtro = /^([0-9:])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = vlCampo.substr(0, tam - 1);
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(121));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 2 && vlCampo.charAt(2) != ':') {
		vlCampo = vlCampo + ':';
		pCampo.value = vlCampo;
	}
	if (vlCampo.length > 2 && vlCampo.charAt(2) != ':') {
		vlCampo = vlCampo.substring(0, 2) + ':' + vlCampo.substring(2);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length == 5) {
		isCampoHoraValido(pCampo, null);
	}
}

// Verifica se existe uma hora vï¿½lida no campo "pCampo" passado como parametro
function isCampoHoraValido(pCampo, pInObrigatorio, pSemMensagem) {
	var msg = "";
	var vlCampo = pCampo.value;

	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") {
		if (pInObrigatorio) {
			msg = "\n" + mensagemGlobal(0);

		} else {
			msg = "\n" + mensagemGlobal(1);

			if (vlCampo == "")
				return true;
		}
	}

	var filtro = /^[0-9]{2}:[0-9]{2}$/;
	if (!filtro.test(vlCampo)) {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(121) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	hrs = (vlCampo.substring(0, 2));
	min = (vlCampo.substring(3, 5));

	//exibirMensagem('hrs '+hrs);
	//exibirMensagem('min '+min);

	situacao = "";

	if (min.length == 0) {
		situacao = "falsa";
	}

	// verifica hora e minuto
	if ((hrs < 00) || (hrs > 23) || (min < 00) || (min > 59)) {
		situacao = "falsa";
	}

	if (trim(pCampo.value) == "") {
		situacao = "falsa";
	}

	if (situacao == "falsa") {
		selecionarCampo(pCampo);
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(121) + msg);
		}
		focarCampo(pCampo);

		return false;
	}

	return true;
}


// Formato o campo data "pCampo" passado como parï¿½metro no formato aaaa-b 
function formatarCampoAnoBimestre(pCampo, pEvento) { 
	var vlCampo = pCampo.value; 
	var tam = vlCampo.length; 

	if (isTeclaFuncional(pEvento)) { 
		return; 
	} 

	if (pEvento != null && pEvento.keyCode == 109) { 
		if (vlCampo.length != 3) { 
			pCampo.value = vlCampo.substr(0, tam - 1); 
		} 

		return; 
	} 

	var filtro = /^([0-9\-])*$/; 
	if (!filtro.test(vlCampo)) { 
		pCampo.value = vlCampo.substr(0, tam - 1); 
		selecionarCampo(pCampo); 
		exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(144)); 
		focarCampo(pCampo); 
		return; 
	} 

	if (vlCampo.length == 4 && vlCampo.charAt(4) != '-') { 
		vlCampo = vlCampo + '-'; 
		pCampo.value = vlCampo; 
	} 

	if (vlCampo.length > 4 && vlCampo.charAt(4) != '-') { 
		vlCampo = vlCampo.substring(0, 4) + '-' + vlCampo.substring(4); 
		pCampo.value = vlCampo; 
	} 

	if (vlCampo.length == 6) { 
		isCampoAnoBimestreValido(pCampo, null); 
	} 
} 

// Verifica se existe uma data vï¿½lida no campo "pCampo" passado como parametro 
function isCampoAnoBimestreValido(pCampo, pInObrigatorio, pSemMensagem) { 
	var msg = ""; 
	var vlCampo = pCampo.value; 
	var dataHoje = getDataHoje(); 
	var anoAtual = getAnoAtual(); 
	var anoLimite = anoAtual + 100; 

	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") { 
		if (pInObrigatorio) { 
			msg = "\n" + mensagemGlobal(0); 
		} else { 
			msg = "\n" + mensagemGlobal(1); 

			if (vlCampo == "") {
				return true; 
			}
		} 
	} 

	var filtro = /^[0-9]{4}\-[0-9]{1}$/; 
	if (!filtro.test(vlCampo)) { 
		selecionarCampo(pCampo); 

		if (!pSemMensagem) { 
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(144) + msg); 
		} 

		focarCampo(pCampo); 

		return false; 
	} 

	bimestre = (vlCampo.substring(5, 6)); 
	ano = (vlCampo.substring(0, 4)); 
        
	//exibirMensagem('dia '+dia); 
	//exibirMensagem('mes '+mes); 
	//exibirMensagem('ano '+ano); 

	situacao = ""; 

	// verifica se o mes e valido 
	if (bimestre < 1 || bimestre > 6) { 
		situacao = "falsa"; 
	} 

	// verifica se ano eh valido 
	if (ano < 1900) { 
		situacao = "falsa"; 
		msg = "\n" + mensagemGlobal(133); 
	} 
	
	if (ano > anoLimite) { 
		situacao = "falsa"; 
		msg = "\n" + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite); 
	} 

	if (pCampo.value == "") { 
		situacao = "falsa"; 
	} 

	if (situacao == "falsa") { 
		selecionarCampo(pCampo); 
		if (!pSemMensagem) { 
			exibirMensagem(mensagemGlobal(120) + "\n" + mensagemGlobal(144) + msg); 
		} 
		focarCampo(pCampo); 

		return false; 
	} 

	return true; 
} 

// Verifica se o perï¿½odo informado ï¿½ vï¿½lido
function isPeriodoValido(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
	var dtInicial = trim(pCampoDataInicial.value);
	var dtFinal = trim(pCampoDataFinal.value);

	if (!isCampoDataValido(pCampoDataInicial, pInCampoDataInicialObrigatoria, true, pSemMensagem))
		return false;
	if (!isCampoDataValido(pCampoDataFinal, !pInCampoDataFinalOpcional, true, pSemMensagem))
		return false;

	if (dtFinal != "") {

		if ((dtFinal.length != dtInicial.length) && (dtFinal.length > 0 && dtInicial.length > 0)) {
			if (pColocarFocoNaDataFinal == true) {
				pCampoDataFinal.select();
			} else {
				pCampoDataInicial.select();
			}

			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(123));
			}

			if (pColocarFocoNaDataFinal == true) {
				focarCampo(pCampoDataFinal);
			} else {
				focarCampo(pCampoDataInicial);
			}

			return false;
		}

		if (dtInicial.length == 7) {
			dtInicial = "01/" + dtInicial;
		}

		if (dtFinal.length == 7) {
			dtFinal = "01/" + dtFinal;
		}

		dia = (dtInicial.substring(0, 2));
		mes = (dtInicial.substring(3, 5)) - 1;
		ano = (dtInicial.substring(6, 10));
		dateInicial = new Date(ano, mes, dia);

		dia = (dtFinal.substring(0, 2));
		mes = (dtFinal.substring(3, 5)) - 1;
		ano = (dtFinal.substring(6, 10));
		dateFinal = new Date(ano, mes, dia);

		if (dtFinal == dtInicial && pInNaoPermitirDatasIguais) {
			if (pColocarFocoNaDataFinal == true) {
				pCampoDataFinal.select();
			} else {
				pCampoDataInicial.select();
			}

			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(132));
			}

			if (pColocarFocoNaDataFinal == true) {
				focarCampo(pCampoDataFinal);
			} else {
				focarCampo(pCampoDataInicial);
			}

			return false;
		}

		if (dateFinal < dateInicial) {
			if (pColocarFocoNaDataFinal == true) {
				pCampoDataFinal.select();
			} else {
				pCampoDataInicial.select();
			}

			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(122));
			}

			if (pColocarFocoNaDataFinal == true) {
				focarCampo(pCampoDataFinal);
			} else {
				focarCampo(pCampoDataInicial);
			}

			return false;
		}

		return true;
	}
	else {
		return true;
    }
}

// Verifica se o perï¿½odo informado ï¿½ vï¿½lido
function isPeriodoMesAnoValido(pCampoMesAnoInicial, pCampoMesAnoFinal, pColocarFocoNoMesAnoFinal, pInCampoMesAnoFinalOpcional, pInCampoMesAnoInicialObrigatorio, pSemMensagem) {
	var dtInicial = trim(pCampoMesAnoInicial.value);
	var dtFinal = trim(pCampoMesAnoFinal.value);

	if (!isCampoMesAnoValido(pCampoMesAnoInicial, pInCampoMesAnoInicialObrigatorio, pSemMensagem)) {
		return false;
	} else if (!isCampoMesAnoValido(pCampoMesAnoFinal, !pInCampoMesAnoFinalOpcional, pSemMensagem)) {
		return false;
	}
	
	if (dtFinal != "") {
		if (dtInicial.length != dtFinal.length && dtInicial.length > 0) {
			if(pColocarFocoNoMesAnoFinal) {
				pCampoMesAnoFinal.select();
			} else {
				pCampoMesAnoInicial.select();
			}
			
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(137));
			}
			
			if (pColocarFocoNoMesAnoFinal) {
				focarCampo(pCampoMesAnoFinal);
			} else {
				focarCampo(pCampoMesAnoInicial);
			}
			return false;
		}
		
		var dia = "01";
		var mes = dtInicial.substring(0, 2) - 1;
		var ano = dtInicial.substring(3, 7);
		dateInicial = new Date(ano, mes, dia);
		
		mes = dtFinal.substring(0, 2) - 1;
		ano = dtFinal.substring(3, 7);
		dateFinal = new Date(ano, mes, dia);
		
		if (dateFinal < dateInicial) {
			if (pColocarFocoNoMesAnoFinal) {
				pCampoMesAnoFinal.select();
			} else {
				pCampoMesAnoInicial.select();
			}
			
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(122));
			}
			
			if (pColocarFocoNoMesAnoFinal) {
				focarCampo(pCampoMesAnoFinal);
			} else {
				focarCampo(pCampoMesAnoInicial);
			}

			return false;
		}
		
		return true;
	}
	
	return true;
}

function isPeriodoMesAnoDataValido(pCampoMesAnoInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInDataFinalMaiorUltimaDataMesAnoInicial, pSemMensagem) {
	var dtInicial = trim(pCampoMesAnoInicial.value);
	var dtFinalOriginal = trim(pCampoDataFinal.value);
	var dtFinal = trim(pCampoDataFinal.value);

	dtFinal = trim(dtFinal.substring(3,10));
	
	if (!isCampoDataValido(pCampoDataFinal, true, true, pSemMensagem))
		return false;

	if (!pInDataFinalMaiorUltimaDataMesAnoInicial) {
		var dia = "01";
		var mes = dtInicial.substring(0, 2) - 1;
		var ano = dtInicial.substring(3, 7);
		dateInicial = new Date(ano, mes, dia);
		
		mes = dtFinal.substring(0, 2) - 1;
		ano = dtFinal.substring(3, 7);
		dateFinal = new Date(ano, mes, dia);
		
		if (dateFinal < dateInicial) {
			if (pColocarFocoNaDataFinal) {
				pCampoDataFinal.select();
			} else {
				pCampoDataFinal.select();
			}
			
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(138).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateInicial)));
			}
			
			if (pColocarFocoNaDataFinal) {
				focarCampo(pCampoDataFinal);
			} else {
				focarCampo(pCampoDataFinal);
			}

			return false;
		}

		return true;
	
	} else {
		var dia = "01";
		var mes = dtInicial.substring(0, 2);
		var ano = dtInicial.substring(3, 7);
		dateInicial = new Date(ano, mes, dia);
		//alert(dateInicial);
		
		dia = dtFinalOriginal.substring(0, 2);
		mes = dtFinalOriginal.substring(3, 5) - 1;
		ano = dtFinalOriginal.substring(6, 10);
		dateFinal = new Date(ano, mes, dia);
		//alert(dateFinal);
		
		if (dateFinal < dateInicial) {
			if (pColocarFocoNaDataFinal) {
				pCampoDataFinal.select();
			} else {
				pCampoDataFinal.select();
			}
			
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(138).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateInicial)));
			}
			
			if (pColocarFocoNaDataFinal) {
				focarCampo(pCampoDataFinal);
			} else {
				focarCampo(pCampoDataFinal);
			}

			return false;
		}

		return true;
	} 
	
	return true;
}

// Verifica se o perï¿½odo de data e hota informado ï¿½ vï¿½lido
function isPeriodoDataHoraValido(pCampoDataInicial, pCampoHoraInicial, pCampoDataFinal, pCampoHoraFinal, pColocarFocoNaDataFinal, pInCampoDataHoraFinalOpcional, pInCampoDataHoraInicialObrigatorio, pSemMensagem) {
	var dtInicial = trim(pCampoDataInicial.value);
	var dtFinal = trim(pCampoDataFinal.value);
	var strInicio = trim(pCampoHoraInicial.value);
	var strFim = trim(pCampoHoraFinal.value);
	
	var hrInicial;
	var minInicial;
	var hrFinal;
	var minFinal;
	
	// Valida campos data e hora inciais
	if (pCampoDataInicial.value == "" && pCampoHoraInicial.value != "") {
		if (!pInCampoDataHoraInicialObrigatorio) {
			msg = "\n" + mensagemGlobal(1);
		} else {
			msg = "\n" + mensagemGlobal(0);
		}
		pCampoDataInicial.select();

		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(125) + msg);
		}
		
		focarCampo(pCampoDataInicial);
		return false;
	}

	if (pCampoDataInicial.value != "" && pCampoHoraInicial.value == "") {
		if (!pInCampoDataHoraInicialObrigatorio) {
			msg = "\n" + mensagemGlobal(1);
		} else {
			msg = "\n" + mensagemGlobal(0);
		}
		pCampoHoraInicial.select();

		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(125) + msg);
		}
		
		focarCampo(pCampoHoraInicial);
		return false;
	}	
	
	if (!isCampoDataValido(pCampoDataInicial, pInCampoDataHoraInicialObrigatorio, true, pSemMensagem)) {
		return false;
	}

	if (!isCampoHoraValido(pCampoHoraInicial, pInCampoDataHoraInicialObrigatorio, pSemMensagem)) {
		return false;
	}
	
	// Valida campos data e hora finais
	if (pCampoDataFinal.value == "" && pCampoHoraFinal.value != "") {
		if (pInCampoDataHoraFinalOpcional) {
			msg = "\n" + mensagemGlobal(1);
		} else {
			msg = "\n" + mensagemGlobal(0);
		}
		pCampoDataFinal.select();

		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(126) + msg);
		}
		
		focarCampo(pCampoDataFinal);
		return false;
	}

	if (pCampoDataFinal.value != "" && pCampoHoraFinal.value == "") {
		if (pInCampoDataHoraFinalOpcional) {
			msg = "\n" + mensagemGlobal(1);
		} else {
			msg = "\n" + mensagemGlobal(0);
		}
		pCampoHoraFinal.select();

		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(126) + msg);
		}
		
		focarCampo(pCampoHoraFinal);
		return false;
	}
	
	if (!isCampoDataValido(pCampoDataFinal, !pInCampoDataHoraFinalOpcional, true, pSemMensagem)) {
		return false;
	}

	if (!isCampoHoraValido(pCampoHoraFinal, !pInCampoDataHoraFinalOpcional, pSemMensagem)) {
		return false;
	}
		
	dia = (dtInicial.substring(0, 2));
	mes = (dtInicial.substring(3, 5)) - 1;
	ano = (dtInicial.substring(6, 10));	
	hrInicial = strInicio.substring(0, 2);
	minInicial = strInicio.substring(3, 5);
	
	dateInicial = new Date(ano, mes, dia, hrInicial, minInicial);

	dia = (dtFinal.substring(0, 2));
	mes = (dtFinal.substring(3, 5)) - 1;
	ano = (dtFinal.substring(6, 10));		
	hrFinal = strFim.substring(0, 2);
	minFinal = strFim.substring(3, 5);	
	
	dateFinal = new Date(ano, mes, dia, hrFinal, minFinal);
	
	if (dateFinal < dateInicial && pCampoDataFinal.value != "") {
		if (pColocarFocoNaDataFinal == true) {
			pCampoDataFinal.select();
		} else {
			pCampoDataInicial.select();
		}

		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(124));
		}

		if (pColocarFocoNaDataFinal == true) {
			focarCampo(pCampoDataFinal);
		} else {
			focarCampo(pCampoDataInicial);
		}

		return false;
	}

	return true;
}

// Funï¿½ï¿½o que verifica se duas datas estï¿½o dentro de um intervalo em dias definido.
function isPeriodoValidoEmDias(pCampoDataInicial, pCampoDataFinal, pPeriodoEmDias, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
	
	if (!isPeriodoValido(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais)) {
		return false;
	}

	var dataIniciaMaisPeriodoEmDias = avancarDataComQtDias(pCampoDataInicial.value, pPeriodoEmDias);
		
	if (!isCampoDataMenorOuIgual(dataIniciaMaisPeriodoEmDias, pCampoDataFinal, true)) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(139).replace(CD_CAMPO_SUBSTITUICAO, pPeriodoEmDias));
		}

		return false;
	}

	return true;
}

// Funï¿½ï¿½o que verifica se duas datas estï¿½o dentro de um intervalo em meses definido.
function isPeriodoValidoEmMeses(pCampoDataInicial, pCampoDataFinal, pPeriodoEmMeses, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
	
	if (!isPeriodoValido(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais)) {
		return false;
	}
	
	var dataIniciaMaisPeriodoEmMeses = avancarDataComQtMeses(pCampoDataInicial.value, pPeriodoEmMeses);
		
	if (!isCampoDataMenorOuIgual(dataIniciaMaisPeriodoEmMeses, pCampoDataFinal, true)) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(140).replace(CD_CAMPO_SUBSTITUICAO, pPeriodoEmMeses));
		}

		return false;
	}

	return true;
}

// Funï¿½ï¿½o que verifica se duas datas estï¿½o dentro de um intervalo em anos definido.
function isPeriodoValidoEmAnos(pCampoDataInicial, pCampoDataFinal, pPeriodoEmAnos, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais) {
	
	if (!isPeriodoValido(pCampoDataInicial, pCampoDataFinal, pColocarFocoNaDataFinal, pInCampoDataFinalOpcional, pInCampoDataInicialObrigatoria, pSemMensagem, pInNaoPermitirDatasIguais))
		return false;

	var dataIniciaMaisPeriodoEmAnos = avancarDataComQtAnos(pCampoDataInicial.value, pPeriodoEmAnos);
		
	if (!isCampoDataMenorOuIgual(dataIniciaMaisPeriodoEmAnos, pCampoDataFinal, true)) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(141).replace(CD_CAMPO_SUBSTITUICAO, pPeriodoEmAnos));
		}

		return false;
	}

	return true;
}

function isDataMaior(pDataReferencia, pDataComparacao, pSemMensagem) {
		
		try {
			dia = pDataReferencia.getDate();
			dateReferencia = pDataReferencia;			
		} catch(e) {		
			var dtReferencia = trim(pDataReferencia);
		
			dia = (dtReferencia.substring(0, 2));
			mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
			ano = (dtReferencia.substring(6, 10));
			dateReferencia = new Date(ano, mes, dia);
		}
		
		try {
			dia = pDataComparacao.getDate();
			dateComparacao = pDataComparacao;
		} catch(e) {					
			var dtComparacao = trim(pDataComparacao);		
				
			dia = (dtComparacao.substring(0, 2));
			mes = parseInt((dtComparacao.substring(3, 5)), 10) - 1;
			ano = (dtComparacao.substring(6, 10));
			dateComparacao = new Date(ano, mes, dia);
			
		}
		
		if (dateComparacao <= dateReferencia){
			if(!pSemMensagem)	{
				exibirMensagem(mensagemGlobal(127).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
			}			
			return false;			
		}
	
		return true;
}

function isDataMenor(pDataReferencia, pDataComparacao, pSemMensagem) {
		
		try {
			dia = pDataReferencia.getDate();
			dateReferencia = pDataReferencia;			
		} catch(e) {		
			var dtReferencia = trim(pDataReferencia);
		
			dia = (dtReferencia.substring(0, 2));
			mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
			ano = (dtReferencia.substring(6, 10));
			dateReferencia = new Date(ano, mes, dia);
		}
		
		try {
			dia = pDataComparacao.getDate();
			dateComparacao = pDataComparacao;
		} catch(e) {					
			var dtComparacao = trim(pDataComparacao);		
				
			dia = (dtComparacao.substring(0, 2));
			mes = parseInt((dtComparacao.substring(3, 5)), 10) - 1;
			ano = (dtComparacao.substring(6, 10));
			dateComparacao = new Date(ano, mes, dia);
			
		}
				
		if (dateComparacao >= dateReferencia) {
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(128).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
			}			
			return false;			
		}
	
		return true;
}

function isDataIgual(pDataReferencia, pDataComparacao, pSemMensagem) {
		
		try {
			dia = pDataReferencia.getDate();
			dateReferencia = pDataReferencia;			
		} catch(e) {		
			var dtReferencia = trim(pDataReferencia);
		
			dia = (dtReferencia.substring(0, 2));
			mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
			ano = (dtReferencia.substring(6, 10));
			dateReferencia = new Date(ano, mes, dia);
		}
		
		try {
			dia = pDataComparacao.getDate();
			dateComparacao = pDataComparacao;
		} catch(e) {					
			var dtComparacao = trim(pDataComparacao);		
				
			dia = (dtComparacao.substring(0, 2));
			mes = parseInt((dtComparacao.substring(3, 5)), 10) - 1;
			ano = (dtComparacao.substring(6, 10));
			dateComparacao = new Date(ano, mes, dia);
			
		}
				
		// == nï¿½o funciona com Objetos
		if (getDataComoString(dateComparacao) != getDataComoString(dateReferencia)) {
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(129).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
			}			
			return false;			
		}
	
		return true;
}

function isDataMaiorOuIgual(pDataReferencia, pDataComparacao, pSemMensagem) {
	var retorno = true;

	try {
		dia = pDataReferencia.getDate();
		dateReferencia = pDataReferencia;			
	} catch(e) {		
		var dtReferencia = trim(pDataReferencia);
	
		dia = (dtReferencia.substring(0, 2));
		mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
		ano = (dtReferencia.substring(6, 10));
		dateReferencia = new Date(ano, mes, dia);
	}

	try {
		dia = pDataComparacao.getDate();
		dateComparacao = pDataComparacao;			
	} catch(e) {		
		var dtComparacao = trim(pDataComparacao);
	
		dia = (dtComparacao.substring(0, 2));
		mes = parseInt((dtComparacao.substring(3, 5)), 10) - 1;
		ano = (dtComparacao.substring(6, 10));
		dateComparacao = new Date(ano, mes, dia);
	}

	if (!isDataIgual(dateReferencia, dateComparacao, true) && !isDataMaior(dateReferencia, dateComparacao, true)) {
		retorno = false;
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(130).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
		}
	}

	return retorno;
}

function isDataMenorOuIgual(pDataReferencia, pDataComparacao, pSemMensagem) {
	var retorno = true;

	try {
		dia = pDataReferencia.getDate();
		dateReferencia = pDataReferencia;			
	} catch(e) {		
		var dtReferencia = trim(pDataReferencia);
	
		dia = (dtReferencia.substring(0, 2));
		mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
		ano = (dtReferencia.substring(6, 10));
		dateReferencia = new Date(ano, mes, dia);
	}

	try {
		dia = pDataComparacao.getDate();
		dateComparacao = pDataComparacao;			
	} catch(e) {		
		var dtComparacao = trim(pDataComparacao);
	
		dia = (dtComparacao.substring(0, 2));
		mes = parseInt((dtComparacao.substring(3, 5)), 10) - 1;
		ano = (dtComparacao.substring(6, 10));
		dateComparacao = new Date(ano, mes, dia);
	}

	if (!isDataIgual(dateReferencia, dateComparacao, true) && !isDataMenor(dateReferencia, dateComparacao, true)) {
		retorno = false;
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(131).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
		}
	}

	return retorno;
}

function isCampoDataMaior(pDataReferencia, pCampoDataComparacao, pSemMensagem) {
	var retorno = false;

	retorno = isDataMaior(pDataReferencia, pCampoDataComparacao.value, pSemMensagem);
	if (!retorno) {
		if (!pSemMensagem) {
			focarCampo(pCampoDataComparacao);
		}
	}

	return retorno;
}

function isCampoDataMaiorOuIgual(pDataReferencia, pCampoDataComparacao, pSemMensagem) {
	var retorno = false;

	retorno = isDataMaior(pDataReferencia, pCampoDataComparacao.value, true);
	if (!retorno) {
		retorno = isDataIgual(pDataReferencia, pCampoDataComparacao.value, true);
		if (!retorno) {
			if (!pSemMensagem) {
				try {
					dia = pDataReferencia.getDate();
					dateReferencia = pDataReferencia;			
				} catch(e) {
					var dtReferencia = trim(pDataReferencia);
				
					dia = (dtReferencia.substring(0, 2));
					mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
					ano = (dtReferencia.substring(6, 10));
					dateReferencia = new Date(ano, mes, dia);
				}
				
				exibirMensagem(mensagemGlobal(130).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
				focarCampo(pCampoDataComparacao);
			}
		}
	}

	return retorno;
}

function isCampoDataMenor(pDataReferencia, pCampoDataComparacao, pSemMensagem) {
	var retorno = false;

	retorno = isDataMenor(pDataReferencia, pCampoDataComparacao.value, pSemMensagem);
	if (!retorno) {
		focarCampo(pCampoDataComparacao);
	}

	return retorno;
}

function isCampoDataMenorOuIgual(pDataReferencia, pCampoDataComparacao, pSemMensagem) {
	var retorno = false;

	retorno = isDataMenor(pDataReferencia, pCampoDataComparacao.value, true);
	if (!retorno) {
		retorno = isDataIgual(pDataReferencia, pCampoDataComparacao.value, true);
		if (!retorno) {
			if (!pSemMensagem) {
				try {
					dia = pDataReferencia.getDate();
					dateReferencia = pDataReferencia;			
				} catch(e) {
					var dtReferencia = trim(pDataReferencia);
				
					dia = (dtReferencia.substring(0, 2));
					mes = parseInt((dtReferencia.substring(3, 5)), 10) - 1;
					ano = (dtReferencia.substring(6, 10));
					dateReferencia = new Date(ano, mes, dia);
				}
				
				exibirMensagem(mensagemGlobal(131).replace(CD_CAMPO_SUBSTITUICAO, getDataComoString(dateReferencia)));
				focarCampo(pCampoDataComparacao);
			}
		}
	}

	return retorno;
}

function getDataHoje() {
	var campoDtHoje = eval("document.frm_principal." + ID_REQ_DT_HOJE);
	
	if (campoDtHoje != null) {
		dia = (campoDtHoje.value.substring(0, 2));
		mes = parseInt((campoDtHoje.value.substring(3, 5)), 10) - 1;
		ano = (campoDtHoje.value.substring(6, 10));
		return new Date(ano, mes, dia);
	} else {
		exibirMensagem(mensagemGlobal(134));
		return null;
	}
}

/*function getAnoAtual() {
	var campoDtHoje = eval("document.frm_principal." + ID_REQ_DT_HOJE);
	
	if (campoDtHoje != null) {
		ano = (campoDtHoje.value.substring(6, 10));
		return parseInt(ano);
	} else {
		exibirMensagem(mensagemGlobal(134));
		return null;
	}
}*/

function getDataHoraAtual() {
	var campoDtHoje = eval("document.frm_principal." + ID_REQ_DT_HOJE);
	var campoHrHoje = eval("document.frm_principal." + ID_REQ_HR_HOJE);

	if (campoDtHoje != null &&
		campoHrHoje != null) {
		dia = (campoDtHoje.value.substring(0, 2));
		mes = parseInt((campoDtHoje.value.substring(3, 5)), 10) - 1;
		ano = (campoDtHoje.value.substring(6, 10));

		hora = (campoHrHoje.value.substring(0, 2));
		minuto = (campoHrHoje.value.substring(3, 5));
		segundo = (campoHrHoje.value.substring(6, 8));

		return new Date(ano, mes, dia, hora, minuto, segundo);
	} else {
		if (campoDtHoje == null) {
			exibirMensagem(mensagemGlobal(134));
		} else {
			exibirMensagem(mensagemGlobal(142));
		}
	
		return null;
	}
}

function getStringComoData(pStrData) {
	var strData = trim(pStrData);
	
	dia = (strData.substring(0, 2));
	mes = parseInt((strData.substring(3, 5)), 10) - 1;
	ano = (strData.substring(6, 10));
	data = new Date(ano, mes, dia);
	
	return data;
}

function getDataComoString(pData) {
	try {
		dia = pData.getDate();
		mes = pData.getMonth() + 1;
		ano = pData.getFullYear();
			
		if(dia < 10)
			dia = "0" + dia;
		
		if(mes < 10)
			mes = "0" + mes;				
		
		strData = dia + "/" + mes + "/" + ano;
		
		return strData;		
	} catch(e) {
		return "";	
	}
}

function avancarDataComQtDias(pDataReferencia, pQtDias) {

	try {
		mes = parseInt(pDataReferencia.substring(3, 5), 10) - 1;
		data = new Date(pDataReferencia.substring(6,10), mes, pDataReferencia.substring(0,2));		
	} catch (e) {		
		data = pDataReferencia;
	}

	dataMiliSeconds = data.getTime();

	pDataFinal = dataMiliSeconds + (pQtDias * 86400000);
	
	dtRetorno = new Date(pDataFinal);
	
	return dtRetorno;		
}

// Funï¿½ï¿½o que avanï¿½a uma Data de referencia em uma quantidade definida de meses
function avancarDataComQtMeses(pDataReferencia, pQtMeses) {
	var dia = 0;
	var mes = 0;
	var ano = 0;
	var mesFinal = 0;
	var anoFinal = 0;	
	var qtMeses = 0;
	
	qtMeses = parseInt(pQtMeses);
	
	try {
		dia = parseInt(pDataReferencia.substring(0, 2), 10);
		mes = parseInt(pDataReferencia.substring(3, 5), 10);
		ano = parseInt(pDataReferencia.substring(6, 10), 10);
		
	} catch (e) {
		dia = pDataReferencia.getDate();
		mes = pDataReferencia.getMonth() + 1;
		ano = pDataReferencia.getFullYear();
	}

	mesFinal = mes + qtMeses;
	anoFinal = ano;	

	if (mesFinal > 12) {
		anoFinal = ano + parseInt(mesFinal/12);
		mesFinal = (mesFinal % 12);
		
	} else if (mesFinal < 1) {
		anoFinal = ano + parseInt( (mesFinal-12) /12);
		mesFinal = 12 + (mesFinal % 12);
	}

	if (mesFinal == 2 && dia > 28) {
		if ((parseInt(ano / 4)) != anoFinal/4) {
			dia = 28;	
		} else {
			dia = 29;
		}
	}

	if ( (mesFinal == 4 || mesFinal == 6 || mesFinal == 9 || mesFinal == 11) && dia > 30) {
		dia = 30;
	}
	
	return new Date(anoFinal, mesFinal-1, dia);
}

// Funï¿½ï¿½o que avanï¿½a uma Data de referencia em uma quantidade definida de anos
function avancarDataComQtAnos(pDataReferencia, pQtAnos) {
	
	var qtAnos = 0;
	var strQtAnos = "";
	
	qtAnos = parseInt(pQtAnos);
	qtAnos = qtAnos * 12;
	strQtAnos = qtAnos + "";
	return avancarDataComQtMeses(pDataReferencia, strQtAnos);		
}

//Retorna a quantidade de dias entre as duas datas passadas como parï¿½metros
function getQtDias(pDataInicial, pDataFinal){

	try {
		mesInicio = parseInt(pDataInicial.substring(3, 5), 10) - 1;
		mesFim = parseInt(pDataFinal.substring(3, 5), 10) - 1;
		
		dtInicial = new Date(pDataInicial.substring(6,10), mesInicio, pDataInicial.substring(0,2));
		dtFinal = new Date(pDataFinal.substring(6,10), mesFim, pDataFinal.substring(0,2));	
		
	} catch (e) {	
		dtInicial = pDataInicial;
		dtFinal = pDataFinal;			
	}
	
	diferenca = dtFinal - dtInicial;
	
	strDias = new String(diferenca/86400000);     //calculate days and convert to string
	pos = strDias.indexOf(".");    			//find the decimal point

	if (pos > -1) {
		dias = strDias.substring(0, pos);    //get just the whole days
	} else {
		dias = strDias;
	}

	return dias;	
}

function getQtMesesAuxiliar(pDataInicial, pDataFinal, pSemMensagem){
	var numDias = getQtDias(pDataInicial, pDataFinal);
	var meses = numDias/30;
	var anos = meses/12;
	
	mesesExatos = Math.round(meses);
	anos = Math.round(anos);
	
	//a operacao abaixo verifica se o numero de anos esta com a aproximacao correta
	//caso contrario, a corrige
	var restodivisaoAnoMes = meses % 12;
	//alert(restodivisaoAnoMes);
	if(restodivisaoAnoMes >= 0.55){
		//arredonda para baixo
		mesesExatos = Math.floor(meses); 
	}
	
	if(!pSemMensagem){
		//exibirMensagem("O período aproximado é de "+mesesExatos + " mese(s), ou "+ anos +" ano(s). Confirme se está correto.");
		exibirMensagem("O período aproximado é de "+mesesExatos + " mese(s). Confirme se está correto.");
	}
	
	return mesesExatos ;
}
//Retorna a quantidade de meses entre as duas datas passadas como parï¿½metros
function getQtMeses(pDataInicial, pDataFinal) {

	var retorno;

	dtInicial = "";
	dtFinal = "";

	//Verifica qual ï¿½ a maior e menor data.
	if (isDataIgual(pDataFinal, pDataInicial, true)) {
		return 0;
	} else if (isDataMenor(pDataFinal, pDataInicial, true)) {
		if (typeof(pDataInicial) == "string") {
			dtInicial = pDataInicial;
		} else {
			dtInicial = getDataComoString(pDataInicial);
		}
		if (typeof(pDataFinal) == "string") {
			dtFinal = pDataFinal;
		} else {
			dtFinal = getDataComoString(pDataFinal);
		}		
	} else if (isDataMaior(pDataFinal, pDataInicial, true)) {
		if (typeof(pDataInicial) == "string") {
			dtInicial = pDataFinal;
		} else {
			dtInicial = getDataComoString(pDataFinal);
		}
		if (typeof(pDataFinal) == "string") {
			dtFinal = pDataInicial;
		} else {
			dtFinal = getDataComoString(pDataInicial);
		}
	}

	var mes1 = parseInt(dtInicial.substring(3, 5), 10);
	var ano1 = parseInt(dtInicial.substring(6));

	var mes2 = parseInt(dtFinal.substring(3, 5), 10);
	var ano2 = parseInt(dtFinal.substring(6));

	//Se os anos das datas sï¿½o iguais, retorna-se a diferenï¿½a entre os meses.
	//Caso contrï¿½rio, retorna-se a quantidade de meses que falta pra o ano1(ano menor) acabar,
	//somada ï¿½ quantidade de meses que jï¿½ se passaram do ano2(ano maior) e, caso exista,
	//os 12 meses referentes aos anos que estï¿½o entre o ano1 e ano2.
	if (ano2 == ano1) {
		retorno = mes2 - mes1;
	} else {
		var qtMesesFimAno1 = 12 - mes1;
		var qtMesesInicioAno2 = mes2;
		retorno = qtMesesFimAno1 + ((ano2 - ano1 - 1) * 12) + qtMesesInicioAno2;
	}
	
	if (isDataMaior(pDataFinal, pDataInicial, true)) {
		retorno = -retorno;
	}

	return retorno;
}

function getStringTimestampComoStringData(pStringTimestamp) {
	dia = pStringTimestamp.substring(8, 10);
	mes = pStringTimestamp.substring(5, 7);
	ano = pStringTimestamp.substring(0, 4);

	return dia + '/' + mes + '/' + ano;
}

function getStringTimestampComoStringHora(pStringTimestamp) {
	hh = pStringTimestamp.substring(11, 13);
	mm = pStringTimestamp.substring(14, 16);

	return (hh + ':' + mm);
}