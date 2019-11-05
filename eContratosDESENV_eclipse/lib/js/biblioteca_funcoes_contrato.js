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

function isCampoValidoParaCalcular(pCampo){
	var temp = getValorCampoMoedaComoNumero(pCampo);
	var retorno = !isNaN(temp);
	return retorno && temp != 0;	
}


function getValorCampoMoedaComoNumeroValido(pCampo, pValorDefault){
	if(pValorDefault == null){
		pValorDefault = 0;
	}
	//alert(pCampo.name);
	var retorno = pValorDefault;
	if(pCampo.value != null && pCampo.value != ""){
		try{
			retorno = getValorCampoMoedaComoNumero(pCampo);
		}catch(ex){
			;
		}
	}
	return retorno;
}
function calcularModificacaoNovo(pArrayCampos) {
	
	var campoVlReferencial = pArrayCampos[0];
	var campoVlModAoContrato = pArrayCampos[1];
	var campoVlRealAoContrato = pArrayCampos[2];
	
	var campoVlMensalAtualizado = pArrayCampos[3];
	var campoVlGlobalAtualizado = pArrayCampos[4];
	var campoVlGlobalReal= pArrayCampos[5];
			
	var campoVlMensalBase = pArrayCampos[6];
	var campoVlGlobalBase = pArrayCampos[7];
	
	var campoDtModificacaoFim = pArrayCampos[8];
	var campoDtModificacao = pArrayCampos[9];
				
	var campoNumMesesAoFim = pArrayCampos[10];	
	var campoTpModificacao = pArrayCampos[11];
	var campoNumPercentual = pArrayCampos[12];
	var campoNumPrazo = pArrayCampos[13];
	
	var cdTpSupressao = pArrayCampos[14];
	var cdTpReajuste = pArrayCampos[15];
	var cdTpProrrogacao = pArrayCampos[16];
	var campoVlMensalModAtual = pArrayCampos[17];
	var campoVlBasePercentual = pArrayCampos[18];
	var campoNumPercentualGestor = pArrayCampos[19];
	var campoVlBasePercentualGestor = pArrayCampos[20];
	var campoNumPrazoMater = pArrayCampos[21];
	var campoVlGlobalModAtual = pArrayCampos[22];
	var campoInEscopo = pArrayCampos[23];

	var tpModificacao = campoTpModificacao.value
	var isSupressao = tpModificacao == cdTpSupressao; 
	var isReajuste = tpModificacao == cdTpReajuste;
	var isProrrogacao = tpModificacao == cdTpProrrogacao;
	var fator = 1;
	var percentual = 0;
	var numPrazo = null;
	var numPrazoMater = null;
	var inEscopo = null;
	if(campoInEscopo != null){
		inEscopo = campoInEscopo.value;
		//alert("tem escopo == " + inEscopo);
	}
	
	if(isSupressao){
		fator = -1;
	}	
		
	if(isProrrogacao){
		try{
			campoVlMensalAtualizado.value = campoVlMensalBase.value;
			campoVlGlobalAtualizado.value = campoVlGlobalBase.value;
		}catch(ex){
			;
		}
	}
	
	//so executa se satisfizer as condicoes abaixo
	if(!
		(campoDtModificacao.value == ""
		|| campoDtModificacaoFim.value == ""
			|| campoTpModificacao.value == ""
		|| (
				!isCampoValidoParaCalcular(campoVlReferencial)
				&& !isCampoValidoParaCalcular(campoVlModAoContrato)
				&& !isCampoValidoParaCalcular(campoVlMensalAtualizado)
				&& !isCampoValidoParaCalcular(campoVlGlobalAtualizado)
			)
		)
	){	
		
		var vlMensalModAtual = 0;
		var vlGlobalModAtual = 0;
		vlMensalModAtual = getValorCampoMoedaComoNumeroValido(campoVlMensalModAtual);
		vlGlobalModAtual = getValorCampoMoedaComoNumeroValido(campoVlGlobalModAtual);
		
		numPrazo = getValorCampoMoedaComoNumeroValido(campoNumPrazo, 0);
		numPrazoMater = getValorCampoMoedaComoNumeroValido(campoNumPrazoMater, 0);
	
		/*var numMesesAoFinal = getValorCampoMoedaComoNumeroValido(campoNumMesesAoFim,0);	
		if(numMesesAoFinal == 0){*/
			var numMeses = getQtDias(campoDtModificacao.value, campoDtModificacaoFim.value)/28;
			//alert("numeses" + numMeses);
			if(isNaN(numMeses)){
				numMeses = 0
			}			
			numMesesAoFinal = arredondarValorMoedaParaBaixo(Math.abs(numMeses), 0);
			
			//pelo menos 1 mes deve ser considerado
			if(numMesesAoFinal == 0){
				numMesesAoFinal = 1;
			}
		//}		
		
		//o numero de meses ao fim nao pode ser maior que o prazo referencial do contrato
		if(numMesesAoFinal > numPrazo > 0){
			exibirMensagem("Prazo restante ("+numMesesAoFinal+" meses) acima do prazo referencial do contrato("+numPrazo+" meses). Alterando o prazo restante.");
			numMesesAoFinal = numPrazo;
		}
		
		var vlReferencialNovo = 0;
		var vlModAoContratoNovo = 0;
		var vlMensalAtualizadoNovo = 0;
		var vlGlobalAtualizadoNovo = 0;

		var vlMensalBase = getValorCampoMoedaComoNumeroValido(campoVlMensalBase);	
		var vlGlobalBase = getValorCampoMoedaComoNumeroValido(campoVlGlobalBase);
		
		var vlReferencial = getValorCampoMoedaComoNumeroValido(campoVlReferencial);
		var vlModAoContrato = getValorCampoMoedaComoNumeroValido(campoVlModAoContrato);
		var vlMensalAtualizado = getValorCampoMoedaComoNumeroValido(campoVlMensalAtualizado);
		var vlGlobalAtualizado = getValorCampoMoedaComoNumeroValido(campoVlGlobalAtualizado);

		if(isProrrogacao){
			vlMensalAtualizadoNovo = vlMensalBase;
			vlGlobalAtualizadoNovo = vlGlobalBase;
		}else if(!isReajuste){
			var atualizarValorMensal = true;
			//quando NAO for reajuste, apenas o valor referencial ou o mod ou o mensal serao inseridos			
			if(eval(vlReferencial) != 0){
				//alert(1);
				vlReferencialNovo = Math.abs(vlReferencial);
				vlReferencialNovo = vlReferencialNovo*fator;
				vlModAoContratoNovo = eval(vlReferencialNovo*numPrazo); 
			}else if(eval(vlModAoContrato) != 0){
				//alert(2);
				vlModAoContratoNovo = Math.abs(vlModAoContrato);
				vlModAoContratoNovo = vlModAoContratoNovo*fator;								
				
				vlReferencialNovo = eval(vlModAoContratoNovo/numPrazo);
			}else{
				//alert(3);
				atualizarValorMensal = false;
				//mensal
				vlMensalAtualizadoNovo = vlMensalAtualizado;
				vlReferencialNovo = eval(vlMensalAtualizadoNovo-vlMensalBase);
				vlModAoContratoNovo = eval(vlReferencialNovo*numPrazo);
			}
			
			if(atualizarValorMensal){
				vlMensalAtualizadoNovo = eval(vlMensalBase + vlReferencialNovo);
			} 
			//vlGlobalAtualizadoNovo = vlGlobalBase + vlModAoContratoNovo;
			vlGlobalAtualizadoNovo = vlMensalAtualizadoNovo*numPrazoMater;
		}else{
			//quando for reajuste, apenas o valor mensal ou global serao inseridos
			if(eval(vlMensalAtualizado) != 0){
				//alert("vlmensal");
				vlMensalAtualizadoNovo = vlMensalAtualizado;
				vlGlobalAtualizadoNovo = eval(vlMensalAtualizadoNovo*numPrazo);
			}else if(eval(vlGlobalAtualizado) != 0){
				//alert("vlglobal");
				vlGlobalAtualizadoNovo = vlGlobalAtualizado;				
				vlMensalAtualizadoNovo = eval(vlGlobalAtualizadoNovo/numPrazo);
			}
			
			vlReferencialNovo = (vlMensalAtualizadoNovo - vlMensalBase);
			vlModAoContratoNovo = eval(vlReferencialNovo*numPrazo);
		}			
		
		setValorCampoMoedaComSeparadorMilhar(campoVlReferencial, vlReferencialNovo, 4);
		setValorCampoMoedaComSeparadorMilhar(campoVlModAoContrato, vlModAoContratoNovo, 4);
		setValorCampoMoedaComSeparadorMilhar(campoVlMensalAtualizado, vlMensalAtualizadoNovo, 4);
		setValorCampoMoedaComSeparadorMilhar(campoVlGlobalAtualizado, vlGlobalAtualizadoNovo, 4);
					
		var vlModReal = 0;		
		vlModReal = eval(vlReferencialNovo*numMesesAoFinal);

		setValorCampoMoedaComSeparadorMilhar(campoVlRealAoContrato, vlModReal, 2);
		setValorCampoMoedaComSeparadorMilhar(campoNumMesesAoFim, numMesesAoFinal, 2);
		
		//atualiza o percentual 
		//percentual = eval((vlMensalAtualizadoNovo-vlMensalBase)/vlMensalBase);
		var isContratoPorEscopo = inEscopo == 'S';
		var vlBasePercentualGestor = 0;		
		campoVlBasePercentualGestor.value = campoVlMensalBase.value;		
		if(isContratoPorEscopo){
			campoVlBasePercentualGestor.value = campoVlGlobalBase.value;
		}
		vlBasePercentualGestor = getValorCampoMoedaComoNumeroValido(campoVlBasePercentualGestor);
		 
		percentual = 0;
		var vlBasePercentual = vlMensalModAtual;
		var vlReferencialCalculadoPeloGestor = vlReferencialNovo;
		var vlBaseCalculadoPeloGestor = vlBasePercentualGestor;

		//verifica o percentual
		if(!isReajuste){		
			if(isContratoPorEscopo){
				//contrato por escopo nao tem referencial mensal
				percentual = eval(vlModAoContratoNovo/vlGlobalModAtual);
				vlBasePercentual = vlGlobalModAtual;
				vlReferencialCalculadoPeloGestor = vlModAoContratoNovo;
				vlBaseCalculadoPeloGestor = vlGlobalBase;
				//vlglobal ta vindo 167 ERRADO
				//campoVlGlobalBase EH O QUE TA CERTO
				//alert("vl blog mod base/vl contrato:" + vlGlobalBase);

			}else{
				//quando nao for por escopo, segue o referencial mensal
				percentual = eval(vlReferencialNovo/vlMensalModAtual);
			}
		}

		/*setValorCampoMoedaComSeparadorMilhar(campoNumPercentual, 100*percentual, 4);
		setValorCampoMoedaComSeparadorMilhar(campoVlBasePercentual, vlMensalModAtual, 2);		
		setValorCampoMoedaComSeparadorMilhar(campoNumPercentualGestor, 100*(vlReferencialNovo/vlBasePercentualGestor), 4);*/
	
		setValorCampoMoedaComSeparadorMilhar(campoNumPercentual, 100*percentual, 4);
		setValorCampoMoedaComSeparadorMilhar(campoVlBasePercentual, vlBasePercentual, 2);		
		//alert("vlreferencia:" + vlReferencialCalculadoPeloGestor + " valor base gestor:" + vlBaseCalculadoPeloGestor);		
		setValorCampoMoedaComSeparadorMilhar(campoNumPercentualGestor, 100*(vlReferencialCalculadoPeloGestor/vlBaseCalculadoPeloGestor), 4);
		
		//alert("vlcampogestor:" + campoNumPercentualGestor.value + " valor base gestor:" + vlBaseCalculadoPeloGestor + " valor referencia gestor:" + vlReferencialCalculadoPeloGestor);

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
	//alert("vl mensal base:" + vlMensalBase +  "vl referencial: " + vlReferencial);
	//setValorCampoMoedaComSeparadorMilhar(campoVlMensalAtual, vlMensalBase + vlReferencial, 2);	
	//setValorCampoMoedaComSeparadorMilhar(campoVlGlobalAtual, vlGlobalBase + vlModAoContrato, 2);
	setValorCampoMoedaComSeparadorMilhar(campoVlGlobalReal, vlGlobalBase + vlRealAoContrato, 2);
	
}