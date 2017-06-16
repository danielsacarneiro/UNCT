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
		
	cdContrato = document.getElementById(pNmCampoCdContrato).value;
	anoContrato = document.getElementById(pNmCampoAnoContrato).value;
	tpContrato = document.getElementById(pNmCampoTipoContrato).value;
	try{
		cdEspecieContrato = document.getElementById(pNmCampoCdEspecieContrato).value;
		sqEspecieContrato = document.getElementById(pNmCampoSqEspecieContrato).value;
	}catch(ex){		
		cdEspecieContrato = null;
		sqEspecieContrato = null;		
	}
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);

	//fica assim por conta do formato da chave do vocontrato
	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato + CD_CAMPO_SEPARADOR + cdContrato;
		
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
		nmContrata = "";
	}
	
	return nmContrata;
}
