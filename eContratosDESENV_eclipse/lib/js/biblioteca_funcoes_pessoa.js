/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
*/

function carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato, pNmCampoDiv){
	str = "";
		
	cdContrato = document.getElementById(pNmCampoCdContrato).value;
	anoContrato = document.getElementById(pNmCampoAnoContrato).value;
	tpContrato = document.getElementById(pNmCampoTipoContrato).value;
	cdEspecieContrato = document.getElementById(pNmCampoCdEspecieContrato).value;
	sqEspecieContrato = document.getElementById(pNmCampoSqEspecieContrato).value;
	//alert(cdContrato + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato);
	
	/*$this->sq = $array[0];
	$this->anoContrato = $array[1];
	$this->tipo = $array[2];
	$this->cdContrato = $array[3];
	$this->cdEspecie = $array[4];
	$this->sqEspecie = $array[5];
	$this->sqHist = $array[6];*/
	
	//fica assim por conta do formato da chave do vocontrato
	sqContrato = "";
	if(cdContrato != "" && anoContrato != "" && tpContrato != ""){
		str = "" + CD_CAMPO_SEPARADOR + anoContrato + CD_CAMPO_SEPARADOR + tpContrato + CD_CAMPO_SEPARADOR + cdContrato;
		
		if(cdEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + cdEspecieContrato;
		
		if(sqEspecieContrato != null)
			str = str + CD_CAMPO_SEPARADOR + sqEspecieContrato;

		//alert(str);
		//vai no ajax
		getDadosContratadaPorContrato(str, pNmCampoDiv);
	}
}
