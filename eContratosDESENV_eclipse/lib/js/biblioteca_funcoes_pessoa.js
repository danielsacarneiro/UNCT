/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
 - biblioteca_funcoes_oficio.js
*/

function carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato, pNmCampoDiv){
	str = "";
		
	var cdContrato = document.getElementById(pNmCampoCdContrato).value;
	var anoContrato = document.getElementById(pNmCampoAnoContrato).value;
	var tpContrato = document.getElementById(pNmCampoTipoContrato).value;
	var isChaveCompleta = false;
	
	var cdEspecieContratoMater = 'CM';
	try{
		var campoSqEspecieContrato = document.getElementById(pNmCampoSqEspecieContrato);
		
		cdEspecieContrato = document.getElementById(pNmCampoCdEspecieContrato).value;
		sqEspecieContrato = campoSqEspecieContrato.value;
		isChaveCompleta = true;
					
		if(cdEspecieContrato == cdEspecieContratoMater){
			sqEspecieContrato = 1;
			campoSqEspecieContrato.value = sqEspecieContrato; 
		}		
	}catch(ex){		
		cdEspecieContrato = null;
		sqEspecieContrato = null;		
	}
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);
		
	var colecaoIDCamposRequired = [pNmCampoSqEspecieContrato];
	var required = cdEspecieContrato != cdEspecieContratoMater;	
	tornarRequiredCamposColecaoFormulario(colecaoIDCamposRequired, required);

	//fica assim por conta do formato da chave do vocontrato
	if(cdContrato != "" && anoContrato != "" && tpContrato != ""
		&& ((isChaveCompleta && cdEspecieContrato != "" && sqEspecieContrato != "") || !isChaveCompleta)){
		var str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + cdContrato + CD_CAMPO_SEPARADOR + tpContrato;
		
		if(cdEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + cdEspecieContrato;
		
		if(sqEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + sqEspecieContrato;

		//alert(str);
		//vai no ajax
		getDadosContratadaPorContrato(str, pNmCampoDiv);
	}else{
		//limpa o campodiv da contratada
		limpaCampoDiv(pNmCampoDiv);		
	}
}

function getNomePessoaContratada(pNmCampoPessoa){
	try{
		camposContratada = document.getElementsByName(pNmCampoPessoa);
		numContratadas = camposContratada.length;

		campoNomeContratada = null;
		if(numContratadas == 1){
			campoNomeContratada = camposContratada[0];
		}else{
			//pega o ultimo
			campoNomeContratada = camposContratada[numContratadas-1];
		}		
		
		nmContrata = campoNomeContratada.value;		
		nmContrata = truncarTexto(nmContrata, 20, "");
	}catch(ex){
		nmContrata = "NAO.ENCONTRADO";
	}
	
	nmContrata = nmContrata.replace('&', 'E');
	
	return nmContrata;
}
