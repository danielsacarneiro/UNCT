/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
*/

function carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoDiv){
	str = "";
		
	cdContrato = document.getElementById(pNmCampoCdContrato).value;
	anoContrato = document.getElementById(pNmCampoAnoContrato).value;
	tpContrato = document.getElementById(pNmCampoTipoContrato).value;

	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato;
		//vai no ajax
		getDadosContratadaPorContrato(str, pNmCampoDiv);
	}
}