/*
 Descricao:
 - Contem funcoes para tratamento de documentos/oficios

 Dependencias:
 - biblioteca_funcoes_text.js
*/

function abrirArquivo(pNmCampoLinkDoc){		
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;
	abrirArquivoLink(linkDoc);
	/*url = "../abrir_windowsexplorer.php?comando=" + linkDoc;
    window.open(url,'_blank');*/
}

function abrirArquivoLink(pLinkDoc){	
	url = "../abrir_windowsexplorer.php?comando=" + pLinkDoc;
    window.open(url,'_blank');
}

/*function abrirArquivoCliente(pNmCampoLinkDoc){
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;	
    url = "../download_arquivo.php?arquivo=" + linkDoc;
    window.open(url,'_blank');
}*/

function abrirArquivoCliente(pNmCampoLinkDoc){
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;
	abrirArquivoLinkCliente(linkDoc);
}

function abrirArquivoLinkCliente(pLinkDoc){	
    url = "../download_arquivo.php?arquivo=" + pLinkDoc;
    window.open(url,'_blank');
}

function getDescricaoTipoContrato(tipo){
	retorno = "";
	
	if(tipo == "C"){
		retorno = "C-SAFI";		
	}else if(tipo == "V"){
		retorno = "CV-SAFI";
	}else if(tipo == "P"){
		retorno = "C-PROFISCO";
	}
	
	return retorno;
}

/**
 * 
 * @param cdSetor
 * @returns
 * @deprecate
 * 
 */
function getDescricaoSetor(cdSetor){
	alert("FUNCAO DEPRECIADA");
}

/**
 * @param cd
 * @param colecaoCdDS
 * @returns
 * 
 * Esse metodo diferencia do metodo acima porque aceita uma colecao de setores
 * geralmente determinada pelo servidor PHP, que pode sofrer constantes atualizacoes
 */
function getDescricaoChaveDS(cd, colecaoCdDS){
	return colecaoCdDS[cd];
	
}

function formatarCodigoDocumento(sq, cdSetor, ano, tpDoc, colecaoSetor){
	str = "";
	conector = "";
	if(cdSetor != null && cdSetor != ""){
		//str = str + conector + getDescricaoSetor(cdSetor);
		if(colecaoSetor == null){
			exibirMensagem("Coleção dos Setores indefinida.");
		}
		str = str + conector + getDescricaoChaveDS(cdSetor,colecaoSetor);
		conector = " ";
	}

	if(tpDoc != null && tpDoc != ""){
		str = str + conector + tpDoc;
		conector = " ";
	}
	
	if(sq != null && sq != ""){
		str = str + conector + completarNumeroComZerosEsquerda(sq, TAMANHO_CODIGOS_DOCUMENTOS);
		conector = "-";
	}

	if(ano != null && ano != ""){
		str = str + conector + ano.substr(2,2);
		conector = "-";
	}
			
	return str;	
}

function formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento, colecaoSetor){	
	if(sq == "")
		sq = "XXX";	
	
	str = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc, colecaoSetor);	
	if(complemento != "")
		str = str  + complemento;
	
	return str;	
}

function getExtensaoDocumento(tpDoc) {
	retorno = ".doc";
	if(tpDoc == 'PC' || tpDoc == 'CA')
		retorno = ".xlsx";
	else if(tpDoc == 'PU')
		retorno = ".pdf";
	
	return retorno;	
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

	// Tira os '.'
	while (vlCampo.indexOf('.') != -1) {
		vlCampo = vlCampo.replace('.', '');
	}
	// Tira os '-'
	while (vlCampo.indexOf('-') != -1) {
		vlCampo = vlCampo.replace('-', '');
	}
	// Caso seja grande demais, trunca.
	var tamanho = vlCampo.length;

	var filtro = /^([0-9])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.select();
		exibirMensagem(mensagemGlobal(43));
		pCampo.value = pCampo.value.substr(0, tam - 1);
		focarCampo(pCampo);
		return;
	}

	if (tamanho > 4 && tamanho <= 9) {
		vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4);
	} else if (tamanho > 9 && tamanho <= 13) {
		vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9);
	} else if (tamanho > 13 && tamanho <= 16) {
		vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9,4) + '.' + vlCampo.substr(13);
	} else if (tamanho > 16 && tamanho <= 18) {
		vlCampo = vlCampo.substr(0, 4) + '.' + vlCampo.substr(4, 5) + '.' + vlCampo.substr(9,4) + '.' + vlCampo.substr(13,3) + '-' + vlCampo.substr(16);
	} else if (tamanho > 18) {
		pCampo.value = vlCampo.substr(0, 18);
		formatarCampoPRT(pCampo,pEvento);
		
		return;
	}

	pCampo.value = vlCampo;

}
