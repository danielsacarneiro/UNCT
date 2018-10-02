/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
*/

var _globalQtdContrato = 1;
function carregaNovoCampoContrato(pNmCampoDiv, pIndice) {	

	getNovoCampoDadosContratoAjax(pNmCampoDiv,pIndice, false);
	_globalQtdContrato++;
	
	if(_globalQtdContrato > 9){
		exibirMensagem("ATENÇÃO: Muitos contratos podem provocar erro no sistema.");
	}
	//campoQtdContrato = document.getElementById(pNMCampoQtdContratos);
	//campoQtdContrato.value = _globalQtdContrato;		    
}

function limparCampoContrato(pNmCampoDiv, pIndice, pNmCampoDivContratada, pColecaoCamposALimpar, pIsAlterarDemanda) {
	if(pIsAlterarDemanda == null)
		pIsAlterarDemanda = false;
	
	if(pIndice == 1 || pIsAlterarDemanda){
		//biblioteca_funcoes_principal.js	
		//alert(pColecaoCamposALimpar);
		var colecaoElementos = pColecaoCamposALimpar.split(CD_CAMPO_SEPARADOR);
		limparCamposColecaoFormulario(colecaoElementos);
		limpaCampoDiv(pNmCampoDivContratada);
		return;
	}
	getNovoCampoDadosContratoAjax(pNmCampoDiv,pIndice, true);
	_globalQtdContrato--;
	
	//campoQtdContrato = document.getElementById(pNMCampoQtdContratos);
	//campoQtdContrato.value = _globalQtdContrato;
}

function carregaContratada(pIndice, pNmCampoCdContrato, pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato, pNmCampoDivNomePessoa) {
	//alert(pNmCampoCdContrato + " " + pNmCampoAnoContrato + " " + pNmCampoTipoContrato + " " + pNmCampoCdEspecieContrato + " " + pNmCampoSqEspecieContrato + " " + pNmCampoDivNomePessoa);
	//ta na biblioteca_funcoes_pessoa.js
	pNmCampoCdContrato = pNmCampoCdContrato + pIndice;
	pNmCampoAnoContrato = pNmCampoAnoContrato  + pIndice; 
	pNmCampoTipoContrato = pNmCampoTipoContrato + pIndice;
	pNmCampoCdEspecieContrato = pNmCampoCdEspecieContrato + pIndice;
	pNmCampoSqEspecieContrato = pNmCampoSqEspecieContrato + pIndice;
	pNmCampoDivNomePessoa = pNmCampoDivNomePessoa + pIndice;

	//alert(pNmCampoCdContrato + " " + pNmCampoAnoContrato + " " + pNmCampoTipoContrato + " " + pNmCampoCdEspecieContrato + " " + pNmCampoSqEspecieContrato + " " + pNmCampoDivNomePessoa);
	//chama biblioteca_funcoes_pessoa.js
	carregaDadosContratada(pNmCampoAnoContrato, pNmCampoTipoContrato, pNmCampoCdContrato, pNmCampoCdEspecieContrato, pNmCampoSqEspecieContrato,pNmCampoDivNomePessoa);		    
}

function formataFormTpGarantia(pNmCampoTemGarantia, pNmCampoTpGarantia) {
	//precisa da bibliotecafuncoesprincipal.js
	campoTemGarantia = document.getElementById(pNmCampoTemGarantia);
	campoTpGarantia = document.getElementById(pNmCampoTpGarantia);
	
	if(campoTemGarantia.value == "S"){
		habilitarElementoMais(pNmCampoTpGarantia, true, true);			
	}else{		
		habilitarElementoMais(pNmCampoTpGarantia, false, false);
	}		
}

/**
 * 
 * importar a biblioteca funcoes radiobutton
 * @returns
 */
function movimentacoes(chave){	
    url = "movimentacaoContrato.php?chave=" + chave;	
    abrirJanelaAuxiliar(url, true, false, false);    
}

function calcularModificacao(pArrayCampos) {
				
	var campoVlReferencial = pArrayCampos[0];
	var campoVlModAoContrato = pArrayCampos[1];
	var campoVlRealAoContrato = pArrayCampos[2];
	
	var campoVlMensalAtualizado = pArrayCampos[3];
	var campoVlGlobalAtualizado = pArrayCampos[4];
	var campoVlGlobalReal= pArrayCampos[5];
			
	var campoVlMensalBase = pArrayCampos[6];
	var campoVlGlobalBase = pArrayCampos[7];
	
	var campoDtFimVigencia = pArrayCampos[8];
	var campoDtModificacao = pArrayCampos[9];
				
	var campoNumMesesAoFim = pArrayCampos[10];	
	var campoTpModificacao = pArrayCampos[11];
	var campoNumPercentual = pArrayCampos[12];
	var campoNumPrazo = pArrayCampos[13];
	
	var cdTpSupressao = pArrayCampos[14];
	var cdTpReajuste = pArrayCampos[15];
	var pNaoAlterarPrazoMeses = pArrayCampos[16];
	var campoVlBaseReajuste = pArrayCampos[17];
	var campoVlBasePercentual = pArrayCampos[18];

	var tpModificacao = campoTpModificacao.value
	var isSupressao = tpModificacao == cdTpSupressao; 
	var isReajuste = tpModificacao == cdTpReajuste;
	var fator = 1;
	var percentual = 0;
	
	if(campoNumPercentual.value != ""){
		percentual = getValorCampoMoedaComoNumero(campoNumPercentual);	
	}
	if(isSupressao){
		fator = -1;
	} 
	//var campoNumPrazo = null;
	var numPrazo = null;
	
	//so executa se satisfizer as condicoes abaixo
	if(!
		(campoDtModificacao.value == ""
		|| (!isReajuste && campoVlReferencial.value == "")
		|| (isReajuste && percentual.value == "")
		)){	
		
		var vlBaseReajuste = 0;
		try{
			vlBaseReajuste = getValorCampoMoedaComoNumero(campoVlBaseReajuste);
		}catch (ex){
			;
		}

		try{
			numPrazo = getValorCampoMoedaComoNumero(campoNumPrazo);
		}catch (ex){
			numPrazo = 1;
		}
	
		var numMesesAoFinal = null;	
		try{
			if(!pNaoAlterarPrazoMeses){
				var numMeses = getQtDias(campoDtModificacao.value, campoDtFimVigencia.value)/29;
				numMesesAoFinal = arredondarValorMoeda(numMeses, 0);
			}else{
				numMesesAoFinal = getValorCampoMoedaComoNumero(campoNumMesesAoFim)
			}		
		}catch (ex){
			//numMesesAoFinal = 1;
		}
			
		var vlReferencial = 0;
		if(isReajuste){
			vlReferencial = eval((percentual/100)*vlBaseReajuste);			
		}else{		
			vlReferencial = Math.abs(getValorCampoMoedaComoNumero(campoVlReferencial));
		}
		setValorCampoMoedaComSeparadorMilhar(campoVlBasePercentual, vlBaseReajuste, 2);
		
		vlReferencial = vlReferencial*fator;
		setValorCampoMoedaComSeparadorMilhar(campoVlReferencial, vlReferencial, 4);
		
		var vlModAoContrato = eval(vlReferencial*numPrazo);
		setValorCampoMoedaComSeparadorMilhar(campoVlModAoContrato, vlModAoContrato, 2);
	
		var vlModReal = 0;
		if(!isReajuste){
			vlModReal = eval(vlReferencial*numMesesAoFinal);
		}else{
			vlModReal = vlModAoContrato;
		}
		setValorCampoMoedaComSeparadorMilhar(campoVlRealAoContrato, vlModReal, 2);

		setValorCampoMoedaComSeparadorMilhar(campoNumMesesAoFim, numMesesAoFinal, 2);	
	
		var vlMensalContrato = 0;
		try{
			vlMensalContrato = getValorCampoMoedaComoNumero(campoVlBaseReajuste);		
		}catch (ex){
		
		}
		
		//atualiza o percentual so se nao for reajuste
		if(vlMensalContrato > 0 && !isReajuste){
			percentual = vlReferencial/vlMensalContrato;
			setValorCampoMoedaComSeparadorMilhar(campoNumPercentual, 100*percentual, 4);
		}		
	
		var arrayCampos = new Array();
		arrayCampos[0] = campoVlReferencial;
		arrayCampos[1] = campoVlModAoContrato;
		arrayCampos[2] = campoVlRealAoContrato;
		
		arrayCampos[3] = campoVlMensalAtualizado;
		arrayCampos[4] = campoVlGlobalAtualizado;
		arrayCampos[5] = campoVlGlobalReal;
	
		arrayCampos[6] = campoVlMensalBase;
		arrayCampos[7] = campoVlGlobalBase;
		
		calcularValorModificacaoAtualizado(arrayCampos);
	}
}

function calcularValorModificacaoAtualizado(pArrayCampos) {
	campoVlReferencial = pArrayCampos[0];
	campoVlModAoContrato = pArrayCampos[1];
	campoVlRealAoContrato = pArrayCampos[2];

	campoVlMensalAtual = pArrayCampos[3];
	campoVlGlobalAtual = pArrayCampos[4];
	campoVlGlobalReal= pArrayCampos[5];
	
	campoVlMensalBase = pArrayCampos[6];
	campoVlGlobalBase = pArrayCampos[7];

	vlReferencial = getValorCampoMoedaComoNumero(campoVlReferencial);
	vlModAoContrato = getValorCampoMoedaComoNumero(campoVlModAoContrato);
	vlRealAoContrato = getValorCampoMoedaComoNumero(campoVlRealAoContrato);	

	vlMensalBase = getValorCampoMoedaComoNumero(campoVlMensalBase);
	vlGlobalBase = getValorCampoMoedaComoNumero(campoVlGlobalBase);	

	//seta campos
	setValorCampoMoedaComSeparadorMilhar(campoVlMensalAtual, vlMensalBase + vlReferencial, 2);	
	setValorCampoMoedaComSeparadorMilhar(campoVlGlobalAtual, vlGlobalBase + vlModAoContrato, 2);
	setValorCampoMoedaComSeparadorMilhar(campoVlGlobalReal, vlGlobalBase + vlRealAoContrato, 2);
	
}