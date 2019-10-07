/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
*/

function formataFormEditalPorTpDemanda(pNmCampoTpDemanda, pColecaoNmObjetosForm, pCdTpDemandaEdital, pArrayComplemento) {
	var pNmCampoPrioridadeDemanda = null;
	var pCdPrioridadeAlta = null;
	if(pArrayComplemento != null){
		pNmCampoPrioridadeDemanda = pArrayComplemento[0]; 
		pCdPrioridadeAlta = pArrayComplemento[1];
	}

	//biblioprincipal
	var campoPrioridadeDemanda = getElementByIdValido(pNmCampoPrioridadeDemanda);
	var campoTpDemanda = document.getElementById(pNmCampoTpDemanda);
	var cdTpDemanda = campoTpDemanda.value;
	
	var isDemandaEdital = cdTpDemanda == pCdTpDemandaEdital;
	if(isDemandaEdital && isCampoEditavel(campoPrioridadeDemanda) && pCdPrioridadeAlta != null){
		campoPrioridadeDemanda.value = pCdPrioridadeAlta;
	}else{
		campoPrioridadeDemanda.value = "";
	}
	habilitarCamposPorNome(pColecaoNmObjetosForm, isDemandaEdital);	
}

function formataFormTpDemanda(pNmCampoTpDemanda, pNmCampoAtributos) {
	//precisa da bibliotecafuncoesprincipal.js
	campoTpDemanda = document.getElementById(pNmCampoTpDemanda);
	campoCheckBoxAtributos = document.getElementsByName(pNmCampoAtributos);
			
	if(campoTpDemanda.value == 1){
		habilitarCampos(campoCheckBoxAtributos, true, true);			
	}else{
		desmarcarTodosCheckBoxes(pNmCampoAtributos);
		habilitarCampos(campoCheckBoxAtributos, false, false);
	}		
}

function formataFormTpDemandaReajuste(pIDCampoTipo, pIDCampoDivMontanteA, pColecaoTpDemandaReajuste, pIDCampoTipoReajuste, pExibirMensagemErro){
	if(pExibirMensagemErro == null){
		pExibirMensagemErro = true;
	}
	campoTpDemanda = document.getElementById(pIDCampoTipo);
	campoDIVMontanteA= document.getElementById(pIDCampoDivMontanteA);
	campoTpReajuste = document.getElementById(pIDCampoTipoReajuste);
	
	if(campoTpDemanda == null || campoDIVMontanteA == null){
		nmCampo = "";
		if(campoTpDemanda == null){
			nmCampo = pIDCampoTipo;
		}
		if(campoDIVMontanteA == null){
			nmCampo = pIDCampoDivMontanteA;
		}
	
		if(pExibirMensagemErro){
			exibirMensagem(nmCampo + " não encontrado.");
		}
		
		return;
	}
	
	cdTpDemanda = campoTpDemanda.value;	
	isDemandaReajuste = pColecaoTpDemandaReajuste.indexOf(cdTpDemanda) != -1;
	var pColecaoIDCamposRequired = null;
	/*if (pIDCampoTipoReajuste != null){
		pColecaoIDCamposRequired = [pIDCampoTipoReajuste];
	}*/
			
	if(isDemandaReajuste){
		tornarCampoObrigatorio(campoTpReajuste, true);
		//biblioteca_funcoes_principal.js
		esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, false);		
	}
	else{ 
		tornarCampoObrigatorio(campoTpReajuste, false);
		esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, true);
	}	
}

function formataFormTpDemandaReajusteContrato(pIDCampoTipoDemanda, 
		pIDCampoDivMontanteA, 
		pColecaoTpDemandaContrato, 
		pIDCampoTipoReajuste,
		pIsReajusteSelecionado,
		pExibirMensagemErro){
	
	if(pExibirMensagemErro == null){
		pExibirMensagemErro = true;
	}
	var campoTpDemanda = document.getElementById(pIDCampoTipoDemanda);
	var campoDIVMontanteA= document.getElementById(pIDCampoDivMontanteA);
	var campoTpReajuste = document.getElementById(pIDCampoTipoReajuste);
	
	if(campoTpDemanda == null || campoDIVMontanteA == null){
		nmCampo = "";
		if(campoTpDemanda == null){
			nmCampo = pIDCampoTipoDemanda;
		}
		if(campoDIVMontanteA == null){
			nmCampo = pIDCampoDivMontanteA;
		}
	
		if(pExibirMensagemErro){
			exibirMensagem(nmCampo + " não encontrado.");
		}
		
		return;
	}
	
	var cdTpDemanda = campoTpDemanda.value;	
	var isDemandaContrato = pColecaoTpDemandaContrato.indexOf(cdTpDemanda) != -1;
	var pColecaoIDCamposRequired = null;
	
	//biblioteca_funcoes_principal.js
	esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, !isDemandaContrato);		
	tornarCampoObrigatorio(campoTpReajuste, pIsReajusteSelecionado);
}

function formatarCampoPRT(pCampo, pEvento) {

	var vlCampo = pCampo.value;
	var tam = vlCampo.length;

	if (isTeclaFuncional(pEvento)) {
		return;
	}

	// Ignorando a tentativa de colocar máscara durante a digitação em dispositivos móveis
	if (pEvento && isAcessoMovel() && pEvento.type == "keyup") {
		return;
	}

	var filtro = /^([0-9])*$/;
	/*if (!filtro.test(vlCampo)) {
		pCampo.select();
		exibirMensagem(mensagemGlobal(43));
		pCampo.value = pCampo.value.substr(0, tam - 1);
		focarCampo(pCampo);
		return;
	}*/
	//se for inserido caracter especial, o prt ficara sem mascara
	if (filtro.test(vlCampo)) {
		// Tira os '.'
		while (vlCampo.indexOf('.') != -1) {
			vlCampo = vlCampo.replace('.', '');
		}
		// Tira os '-'
		while (vlCampo.indexOf('-') != -1) {
			vlCampo = vlCampo.replace('-', '');
		}
		while (vlCampo.indexOf('/') != -1) {
			vlCampo = vlCampo.replace('/', '');
		}

		var tamanho = vlCampo.length;
		
		if (tamanho > 4 && tamanho <= 9) {
			vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4);
		} else if (tamanho > 9 && tamanho <= 13) {
			vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9);
		} else if (tamanho > 13 && tamanho <= 16) {
			vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9,4) + '.' + vlCampo.substr(13);
		} else if (tamanho > 16 && tamanho <= 22) {
			vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9,4) + '.' + vlCampo.substr(13,3) + '-' + vlCampo.substr(16);
		} else if (tamanho > 22) {
			pCampo.value = vlCampo.substr(0, 22);
			formatarCampoPRT(pCampo,pEvento);
			
			return;
		}
	}
	
	pCampo.value = vlCampo;

}