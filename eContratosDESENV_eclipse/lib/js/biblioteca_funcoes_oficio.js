/*
 Descricao:
 - Contem funcoes para tratamento de documentos/oficios

 Dependencias:
 - biblioteca_funcoes_text.js
*/

function abrirArquivo(pNmCampoLinkDoc){		
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;	
    url = "../abrir_windowsexplorer.php?comando=" + linkDoc;
    window.open(url,'_blank');
}

function getDescricaoSetor(cdSetor){
	retorno = "";
	
	if(cdSetor == 1){
		retorno = "SAFI";		
	}else if(cdSetor == 2){
		retorno = "UNCT";
	}else if(cdSetor == 3){
		retorno = "ATJA";
	}
	
	return retorno;
}

function formatarCodigoDocumento(sq, cdSetor, ano, tpDoc){
	
	str = tpDoc
		+ " " + completarNumeroComZerosEsquerda(sq, TAMANHO_CODIGOS_DOCUMENTOS)
		+ "-" + ano.substr(2, 2)
		+ "/" + getDescricaoSetor(cdSetor);
	
	return str;
	
}