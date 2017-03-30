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

function abrirArquivoCliente(pNmCampoLinkDoc){		
	linkDoc = document.getElementById(pNmCampoLinkDoc).value;	
    url = "../download_arquivo.php?arquivo=" + linkDoc;
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

function getDescricaoSetor(cdSetor){
	retorno = "";
  	/*static $CD_SETOR_SAFI= 1;
  	static $CD_SETOR_UNCT= 2;
  	static $CD_SETOR_ATJA= 3;
  	static $CD_SETOR_DIUC= 4;
  	static $CD_SETOR_PGE= 5;
  	static $CD_SETOR_SAD= 6;*/	
	
	if(cdSetor == 1){
		retorno = "SAFI";		
	}else if(cdSetor == 2){
		retorno = "UNCT";
	}else if(cdSetor == 3){
		retorno = "ATJA";
	}else if(cdSetor == 4){
		retorno = "DIUC";
	}else if(cdSetor == 5){
		retorno = "PGE";
	}else if(cdSetor == 6){
		retorno = "SAD";
	}
	
	return retorno;
}

function formatarCodigoDocumento(sq, cdSetor, ano, tpDoc){	
	str = "";
	conector = "";
	if(cdSetor != null && cdSetor != ""){
		str = str + conector + getDescricaoSetor(cdSetor);
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

function formatarNomeDocumento(sq, cdSetor, ano, tpDoc, complemento){	
	if(sq == "")
		sq = "XXX";	
	
	str = formatarCodigoDocumento(sq, cdSetor, ano, tpDoc);	
	if(complemento != "")
		str = str  + complemento;	
	
	return str;	
}
