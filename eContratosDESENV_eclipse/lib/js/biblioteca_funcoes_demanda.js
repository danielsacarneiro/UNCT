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
		
	/*if(isNaN(campoCheckBoxAtributos.length)){
		campoCheckBoxAtributos.checked = _estadoAtualCheckBox;			
	}else{
		for (i = 0; i < checkBox.length; i++) {
			checkBox.item(i).checked = _estadoAtualCheckBox;			
		}	
	}*/
	
	if(campoTpDemanda.value == 1){
		habilitarCampos(campoCheckBoxAtributos, true, true);			
	}else{
		desmarcarTodosCheckBoxes(pNmCampoAtributos);
		habilitarCampos(campoCheckBoxAtributos, false, false);
	}		
}

function formataFormTpDemandaReajuste(pIDCampoTipo, pIDCampoDivMontanteA, pColecaoTpDemandaReajuste){		
	campoTpDemanda = document.getElementById(pIDCampoTipo);
	cdTpDemanda = campoTpDemanda.value;	
	isDemandaReajuste = pColecaoTpDemandaReajuste.indexOf(cdTpDemanda) != -1;	

	campoDIVMontanteA= document.getElementById(pIDCampoDivMontanteA);
	pColecaoIDCamposRequired = null;
	if(isDemandaReajuste){
		//biblioteca_funcoes_principal.js
		esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, false);		
	}
	else{ 
		esconderDiv(campoDIVMontanteA, pColecaoIDCamposRequired, true);
	}	
}

