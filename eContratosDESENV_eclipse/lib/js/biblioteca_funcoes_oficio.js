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

function getDescricaoSetor(cdSetor){
	retorno = "";
  	/*static $CD_SETOR_SAFI= 1;
  	static $CD_SETOR_UNCT= 2;
  	static $CD_SETOR_ATJA= 3;
  	static $CD_SETOR_DIlC= 4;
  	static $CD_SETOR_PGE= 5;
  	static $CD_SETOR_SAD= 6;
  	static $CD_SETOR_UNCP= 7;
  	static $CD_SETOR_CPL= 8;	
	static $CD_SETOR_UNSG= 9;*/
	
	if(cdSetor == 1){
		retorno = "SAFI";		
	}else if(cdSetor == 2){
		retorno = "UNCT";
	}else if(cdSetor == 3){
		retorno = "ATJA";
	}else if(cdSetor == 4){
		retorno = "DILC";
	}else if(cdSetor == 5){
		retorno = "PGE";
	}else if(cdSetor == 6){
		retorno = "SAD";
	}else if(cdSetor == 7){
		retorno = "UNCP";
	}else if(cdSetor == 8){
		retorno = "CPL";
	}else if(cdSetor == 9){
		retorno = "UNSG";
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

function getExtensaoDocumento(tpDoc) {
	retorno = ".doc";
	if(tpDoc == 'PC')
		retorno = ".xls";
	
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
