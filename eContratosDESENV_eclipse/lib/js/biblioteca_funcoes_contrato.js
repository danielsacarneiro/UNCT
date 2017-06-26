/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
*/

var _globalQtdContrato = 1;
function carregaNovoCampoContrato(pNmCampoDiv, pIndice) {	

	getNovoCampoDadosContratoAjax(pNmCampoDiv,pIndice, false);
	_globalQtdContrato++;
	
	if(_globalQtdContrato > 9){
		exibirMensagem("ATENÇÃO: Muitos contratos podem provocar erro no sistema.");
	}
	//campoQtdContrato = document.getElementById(pNMCampoQtdContratos);
	//campoQtdContrato.value = _globalQtdContrato;		    
}

function limparCampoContrato(pNmCampoDiv, pIndice, pNmCampoDivContratada, pColecaoCamposALimpar) {	
	if(pIndice == 1){
		//biblioteca_funcoes_principal.js	
		//alert(pColecaoCamposALimpar);
		var colecaoElementos = pColecaoCamposALimpar.split(CD_CAMPO_SEPARADOR);
		limparCamposColecaoFormulario(colecaoElementos);
		limpaCampoDiv(pNmCampoDivContratada);
		return;
	}
	getNovoCampoDadosContratoAjax(pNmCampoDiv,pIndice, true);
	_globalQtdContrato--;
	
	//campoQtdContrato = document.getElementById(pNMCampoQtdContratos);
	//campoQtdContrato.value = _globalQtdContrato;
}

function carregaContratada(pIndice, pNmCampoCdContrato, pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato, pNmCampoDivNomePessoa) {
	//alert(pNmCampoCdContrato + " " + pNmCampoAnoContrato + " " + pNmCampoTipoContrato + " " + pNmCampoCdEspecieContrato + " " + pNmCampoSqEspecieContrato + " " + pNmCampoDivNomePessoa);
	//ta na biblioteca_funcoes_pessoa.js
	pNmCampoCdContrato = pNmCampoCdContrato + pIndice;
	pNmCampoAnoContrato = pNmCampoAnoContrato  + pIndice; 
	pNmCampoTipoContrato = pNmCampoTipoContrato + pIndice;
	pNmCampoCdEspecieContrato = pNmCampoCdEspecieContrato + pIndice;
	pNmCampoSqEspecieContrato = pNmCampoSqEspecieContrato + pIndice;
	pNmCampoDivNomePessoa = pNmCampoDivNomePessoa + pIndice;

	//alert(pNmCampoCdContrato + " " + pNmCampoAnoContrato + " " + pNmCampoTipoContrato + " " + pNmCampoCdEspecieContrato + " " + pNmCampoSqEspecieContrato + " " + pNmCampoDivNomePessoa);
	carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato,pNmCampoDivNomePessoa);		    
}

function formataFormTpGarantia(pNmCampoTemGarantia, pNmCampoTpGarantia) {
	//precisa da bibliotecafuncoesprincipal.js
	campoTemGarantia = document.getElementById(pNmCampoTemGarantia);
	campoTpGarantia = document.getElementById(pNmCampoTpGarantia);
	
	if(campoTemGarantia.value == "S"){
		habilitarElementoMais(pNmCampoTpGarantia, true, true);			
	}else{		
		habilitarElementoMais(pNmCampoTpGarantia, false, false);
	}		
}
