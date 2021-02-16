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
		//alert(cdEspecieContrato);
		sqEspecieContrato = campoSqEspecieContrato.value;
		isChaveCompleta = true;
		
		if(sqEspecieContrato != null){
			if(cdEspecieContrato == cdEspecieContratoMater && sqEspecieContrato != 1){
				if(sqEspecieContrato != ""){
					exibirMensagem("Alteração não permitida para Contrato Mater.");
				}
				sqEspecieContrato = 1;
				campoSqEspecieContrato.value = sqEspecieContrato; 
			}		
		}
	}catch(ex){		
		cdEspecieContrato = null;
		sqEspecieContrato = null;		
	}
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);
		
	/*var colecaoIDCamposRequired = [pNmCampoSqEspecieContrato];
	var required = cdEspecieContrato != cdEspecieContratoMater;	
	tornarRequiredCamposColecaoFormulario(colecaoIDCamposRequired, required);*/

	var isChavePermiteConsulta = anoContrato != "" && tpContrato != "";
	var isCdContratoInserido = cdContrato != null && cdContrato != "";
	
	if(isChavePermiteConsulta){
		//fica assim por conta do formato da chave do vocontrato
		var str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + cdContrato + CD_CAMPO_SEPARADOR + tpContrato;
		//alert(str);
		var permiteBuscarProxSqEspecie = false;
		if(cdEspecieContrato != null && cdEspecieContrato != ""){
			str = str + CD_CAMPO_SEPARADOR + cdEspecieContrato;
			
			//so coloca o sqEspecie se o cdEspecie tiver preenchido
			if(sqEspecieContrato != null && sqEspecieContrato != ""){
				str = str + CD_CAMPO_SEPARADOR + sqEspecieContrato;
			}else{
				//consideradno que o cdEspecie foi preenchido e o sq nao
				permiteBuscarProxSqEspecie = true;
			}
			
		}

		if(!isCdContratoInserido || (isCdContratoInserido && isChaveCompleta && permiteBuscarProxSqEspecie)){			
			//vai buscar o proximo termo quando possivel para exibir na tela
			link = "../contrato/campoSqProximoTermo.php";
			//biblio ajax
			getDadosPorChaveGenerica(str, link, pNmCampoDiv);
			return;			
		}
		
		//alem disso, completa a informacao da contratada se o contrato tiver sido inserido
		if(isCdContratoInserido){			
			//alert(str);
			//vai no ajax para pegar dados da contratada se o contrato estiver completo
			getDadosContratadaPorContrato(str, pNmCampoDiv);
		}
	
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
		nmContrata = "NÃO.ENCONTRADO";
	}
	
	nmContrata = nmContrata.replace('&', 'E');
	
	return nmContrata;
}
