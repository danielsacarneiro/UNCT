/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
*/

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
	campoTpDemanda = document.getElementById(pIDCampoTipoDemanda);
	campoDIVMontanteA= document.getElementById(pIDCampoDivMontanteA);
	campoTpReajuste = document.getElementById(pIDCampoTipoReajuste);
	
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
	
	cdTpDemanda = campoTpDemanda.value;	
	isDemandaContrato = pColecaoTpDemandaContrato.indexOf(cdTpDemanda) != -1;
	var pColecaoIDCamposRequired = null;
	
	//biblioteca_funcoes_principal.js
	esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, !isDemandaContrato);		
	tornarCampoObrigatorio(campoTpReajuste, pIsReajusteSelecionado);
}

