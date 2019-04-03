/*
 Descricao:
 - Contem funcoes para tratamento de documentos/oficios

 Dependencias:
 - biblioteca_funcoes_text.js
*/

function abrirArquivo(pNmCampoLinkDoc, pIsMenuSistema){		
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;
	abrirArquivoLink(linkDoc, pIsMenuSistema);
	/*url = "../abrir_windowsexplorer.php?comando=" + linkDoc;
    window.open(url,'_blank');*/
}

function abrirArquivoLink(pLinkDoc, pIsMenuSistema){
	if(pIsMenuSistema){
		pPaginaAlternativa = "funcoes/abrir_windowsexplorer.php"; 
	}else{
		pPaginaAlternativa = "../abrir_windowsexplorer.php";
	}
	url = pPaginaAlternativa + "?comando=" + pLinkDoc;
    window.open(url,'_blank');
}

/*function abrirArquivoCliente(pNmCampoLinkDoc){
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;	
    url = "../download_arquivo.php?arquivo=" + linkDoc;
    window.open(url,'_blank');
}*/

function abrirArquivoCliente(pNmCampoLinkDoc, pIsMenuSistema){
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;
	abrirArquivoLinkCliente(linkDoc, pIsMenuSistema);
}

function abrirArquivoLinkCliente(pLinkDoc, pIsMenuSistema){	
	if(pIsMenuSistema){
		pPaginaAlternativa = "funcoes/download_arquivo.php"; 
	}else{
		pPaginaAlternativa = "../download_arquivo.php";
	}
		
    url = pPaginaAlternativa + "?arquivo=" + pLinkDoc;
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

function formatarCodigoDocumento(sq, cdSetor, ano, tpDoc, colecaoSetor, separador){
	str = "";
	conector = "";
	if(separador == null){
		separador = "-";
	}
	if(cdSetor != null && cdSetor != ""){
		//str = str + conector + getDescricaoSetor(cdSetor);
		if(colecaoSetor == null){
			exibirMensagem("Coleção dos Setores indefinida.");
		}
		str = str + conector + getDescricaoChaveDS(cdSetor,colecaoSetor);
		conector = " ";
	}

	if(tpDoc != null && tpDoc != ""){
		//mascara para o documento PAAP
		if(tpDoc == "PE"){
			tpDoc = "PAAP";
		}
			
		str = str + conector + tpDoc;
		conector = " ";
	}
	
	if(sq != null && sq != ""){
		str = str + conector + completarNumeroComZerosEsquerda(sq, TAMANHO_CODIGOS_DOCUMENTOS);
		conector = separador;
	}

	if(ano != null && ano != ""){
		str = str + conector + ano.substr(2,2);
		conector = separador;
	}
			
	return str;	
}

function formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento, colecaoSetor){	
	if(sq == "")
		sq = "XXX";	
	
	var str = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc, colecaoSetor);	
	if(complemento != ""){
		complemento = complemento.replace("\"", "_");
		complemento = complemento.replace("/", "_");
		str = str  + complemento;
	}
	
	return str;	
}

function getExtensaoDocumento(tpDoc) {
	retorno = ".doc";
	if(tpDoc == 'PC' || tpDoc == 'CA' || tpDoc == 'LC')
		retorno = ".xlsx";
	else if(tpDoc == 'PU' || tpDoc == 'PP' || tpDoc == 'DC')
		retorno = ".pdf";
	
	return retorno;	
}
