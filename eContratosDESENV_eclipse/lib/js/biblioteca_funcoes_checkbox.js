/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */

/*
 Descrição:
 - Contém funções para tratamento de radiobuttons
 Dependências:
 - biblioteca_funcoes_principal.js
*/


// Verifica se algum "checkbox" esta selecionado na colecao com nome "pNmCheckBox" passado como parametro.
// Se  pSemMensagem igual a true, não exibe mensagem.
// Utilizado em telas de consulta.
function isCheckBoxConsultaSelecionado(pNmCheckBox, pSemMensagem, pInTodosObrigatorios, pInPeloMenosUmObrigatorio) {
	var i = 0;

	//checkBox = eval(pNmCheckBox);
	checkBox = document.getElementsByName(pNmCheckBox);
	
	if (pInTodosObrigatorios || pInPeloMenosUmObrigatorio) {
		
		if (checkBox != null) {
			if (checkBox.checked) {
				return true;
			} else {
				if (checkBox.length == null){
					if (!pSemMensagem) {
						exibirMensagem(mensagemGlobal(210));
					}

					return false;
				}
					
				for (i = 0; i < checkBox.length; i++){ 
					if (checkBox.item(i).checked && pInPeloMenosUmObrigatorio && !pInTodosObrigatorios){ 
						return true;
					}

					if (!checkBox.item(i).checked){
						if (!pSemMensagem) {
							exibirMensagem(mensagemGlobal(210));
						}

						return false;
					}
				}
			}
		}	

		return true;
	}
	
	if (checkBox == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(210));
		}
		return false;
	}

	if (checkBox.checked) {
		// Quando existe apenas um checkbox no formulario
		return true;
	}
	
	for (i = 0; i < checkBox.length; i++) {
		if (checkBox.item(i).checked == true) {
			return true;
		}
	}
	
	if (!pSemMensagem) {
		exibirMensagem(mensagemGlobal(211));
	}

	return false;
}

function isCheckBoxSelecionado(pNmCheckBox, pSemMensagem, pInTodosObrigatorios) {
	var i = 0;
	var retorno = false;

	//checkBox = eval(pNmCheckBox);
	var checkBox = document.getElementsByName(pNmCheckBox);

		if (checkBox != null) {
			if (checkBox.checked) {
				// Quando existe apenas um checkbox no formulario
				retorno = true;				
			} else {
				if (checkBox.length == null){
					retorno = false;
				}
					
				for (i = 0; i < checkBox.length; i++){ 
					if (checkBox.item(i).checked){ 
						retorno = true;
						if(!pInTodosObrigatorios){
							break;
						}
					}else{
						if (pInTodosObrigatorios){
							retorno = false;
							break;
						}						
					}

				}
			}
		}	
		
	var campoFocus = checkBox;
	//alert(checkBox.length);
	if(checkBox.length > 1){
		campoFocus = checkBox.item((checkBox.length)-1);
	}
		
	if (!retorno && !pSemMensagem) {
		campoFocus.focus();
		exibirMensagem(mensagemGlobal(211));
	}

	return retorno;
}

// Retorna um Array com os checkBoxes "pNmCheckBox" passado como parâmetro
// Os campos devem estar separados pela constante CD_CAMPO_SEPARADOR
function retornarValoresCheckBoxesSelecionadosComoArray(pNmCheckBox) {
	var contador = 0;
	var aux = 0;
	var arrayRetorno = new Array();

	//checkBox = eval(pNmCheckBox);
	checkBox = document.getElementsByName(pNmCheckBox);
	//colecaoIDCampos.length
	
	if (checkBox == null) {
		exibirMensagem(mensagemGlobal(60));
		return false;
	}

	if (checkBox.checked) {
		// Quando existe apenas um checkbox no formulario
		valorCheckBox = checkBox.value;
		arrayRetorno[0] = valorCheckBox;
	}
	
	for (i = 0; i < checkBox.length; i++) {
		if (checkBox.item(i).checked) {
			valorCheckBox = checkBox.item(i).value;
			//alert(valorCheckBox);
			arrayRetorno[aux] = valorCheckBox;
			aux++;
		}
	}

	return arrayRetorno;
}

function isItemCheckBoxSelecionado(pNmCheckBox, pValorItem) {
	var itens = retornarValoresCheckBoxesSelecionadosComoString(pNmCheckBox);
	return 	itens.indexOf(pValorItem) != -1;
}

//Retorna uma String com os checkBoxes "pNmCheckBox" passado como parâmetro Separados por CD_CAMPO_SEPARADOR_AUX
// Os campos devem estar separados pela constante CD_CAMPO_SEPARADOR
function retornarValoresCheckBoxesSelecionadosComoString(pNmCheckBox) {
	var contador = 0;
	var stringRetorno = "";

	arrayCheckBoxes = retornarValoresCheckBoxesSelecionadosComoArray(pNmCheckBox);
	
	for (i = 0; i < arrayCheckBoxes.length; i++) {
		stringRetorno = stringRetorno + arrayCheckBoxes[i] + CD_CAMPO_SEPARADOR_AUX;
	}
		
	return stringRetorno;
}

/**
 * os checks dentro de pArrayCdsInalterados permanecerao com os valores originais, sem permissao de check ou uncheck
 * @param pNmCheckBox
 * @param pArrayCdsInalterados
 * @returns
 */
function manterCheckBoxesInalterados(pNmCheckBox, pArrayCdsInalterados) {
;			
}

//Determina o estado atual dos checkboxes da pagina;
//0 = desmarcado
//1 = marcado
var _estadoAtualCheckBox = 0;

function marcarTodosCheckBoxes(pNmCheckBox) {
	var i = 0;

	//checkBox = eval(pNmCheckBox);
	checkBox = document.getElementsByName(pNmCheckBox);
	_estadoAtualCheckBox = 1 - _estadoAtualCheckBox;
	
	if (checkBox == null)
		return;
	
	if(isNaN(checkBox.length)){
		checkBox.checked = _estadoAtualCheckBox;			
		return;
	}
	
	for (i = 0; i < checkBox.length; i++) {
		checkBox.item(i).checked = _estadoAtualCheckBox;			
	}	
}

function desmarcarTodosCheckBoxes(pNmCheckBox) {
	var i = 0;

	//checkBox = eval(pNmCheckBox);
	checkBox = document.getElementsByName(pNmCheckBox);
	
	if (checkBox == null)
		return;
	
	if(isNaN(checkBox.length)){
		checkBox.checked = 0;			
		return;
	}
	
	for (i = 0; i < checkBox.length; i++) {
		checkBox.item(i).checked = 0;			
	}	
}

// marca um checkbox através de um link
function marcarCheckBox(pNmCheckBox, pPosicaoNoArray, pInNaoDesmarcarTodosCheckBoxes) {
	
	if (!pInNaoDesmarcarTodosCheckBoxes) {
		desmarcarTodosCheckBoxes(pNmCheckBox);
	}
	
	checkBox = eval(pNmCheckBox);

	if (checkBox == null)
		return;

	// se só existir um único checkbox
	if (isNaN(checkBox.length)){
		checkBox.checked = 1;			
	} else {
		checkBox.item(pPosicaoNoArray).checked = 1;
	}
}

// Verifica se APENAS UM "checkbox" esta selecionado na colecao com nome "pNmCheckBox" passado como parametro.
// Se  pSemMensagem igual a true, não exibe mensagem.
// Utilizado em telas de consulta.
function isApenasUmCheckBoxSelecionado(pNmCheckBox, pSemMensagem) {
	
	checkBox = eval(pNmCheckBox);
	var contador = 0;
	
	if (checkBox == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(210));
		}
		return false;
	}

	if (checkBox.checked) {
		// Quando existe apenas um checkbox no formulario
		return true;
	}
	
	for (i = 0; i < checkBox.length; i++) {
		if (checkBox.item(i).checked == true) {
			contador = contador + 1;
		}
	}
	
	if (contador == 1) {
		return true;
	}
	
	if (!pSemMensagem) {
		exibirMensagem(mensagemGlobal(212));  
	}

	return false;
}

function marcaApenasUmCheckBox(checkBox, pSemMensagem) {
	if (checkBox == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(210));
		}
		return false;
	}
	
	var colecaocheckBox = document.getElementsByName(checkBox.name);	
	var contador = 0;
	var id = checkBox.id.split("*")[0];
	
	if (checkBox.checked) {
		//desmarca os outros
		for (var i = 0; i < colecaocheckBox.length; i++) {
			var checkAtual = colecaocheckBox[i];
			var idAtual = checkAtual.id.split("*")[0];
			if (checkAtual != checkBox && idAtual == id) {
				checkAtual.checked = false;
			}
		}
	}

	return true;
}

function validaFormRequiredCheckBox(campoCheckBoxValidacao, colecaoIDCampos, pSetarVazio, pExibirAlertErro = false){
	pIsRequired = !campoCheckBoxValidacao.checked;
	tornarRequiredCamposColecaoFormulario(colecaoIDCampos, pIsRequired, pExibirAlertErro);
	
	if(pSetarVazio != null && pSetarVazio){
		limparCamposColecaoFormulario(colecaoIDCampos);
	}
}

function validaFormReadOnlyCheckBox(campoCheckBoxValidacao, colecaoIDCampos, pSetarVazio, pIsCampoObrigatorio, pIsAlinhadoDireita){
	pIsReadOnly = !campoCheckBoxValidacao.checked;
	tornarReadOnlyCamposColecaoFormulario(colecaoIDCampos, pIsReadOnly, pIsCampoObrigatorio, pIsAlinhadoDireita);
	
	if(pSetarVazio != null && pSetarVazio){
		limparCamposColecaoFormulario(colecaoIDCampos);
	}
}

/**
 * funcao a ser usada no onclick
 * @param pCampoCheckBox
 * @param pArrayItensNaoPermitidos
 * @param pSemMensagem
 * @returns
 */
function isCheckBoxPermiteAlteracao(pCampoCheckBox, pPropriedadeValorCondicao, pArrayItensPermitidos, pNaoPermitidos, pSemMensagem) {
	var i = 0;
	var retorno = true;

	//checkBox = eval(pNmCheckBox);
	var checkBox = pCampoCheckBox;
	if(pPropriedadeValorCondicao != null){
		pArrayPropriedades  = pPropriedadeValorCondicao.split("*");
	}

	if (checkBox != null) {
		/*for (i = 0; i < checkBox.length; i++){
			var option = checkBox.item(i);
			var id = option.id;
			var checkedOrigem = option.checked;
			
			alert(id);
				
			if(pArrayItensNaoPermitidos.indexOf(id) != -1){
				retorno = false;
				option.checked = checkedOrigem;
				break;						
			}
		}*/		
			var id = checkBox.id;
			//var checkedOrigem = checkBox.checked;
			
			//alert(id);
			
		/*if(pArrayItensNaoPermitidos != null){
			if(pArrayItensNaoPermitidos.indexOf(id) != -1){
				retorno = false;
				checkBox.checked = !checkBox.checked;						
			}
		}*/

		if(pArrayItensPermitidos != null && pPropriedadeValorCondicao != null){
			//alert(1);
			//funcao indexOfChaveArray(chave, pArray, pSemMensagem) em biblio...checkbox
			//var validar = indexOfChaveArray(id, pArrayItensPermitidos, pSemMensagem) != -1;
			var validar = pArrayItensPermitidos[id] != null;
			if(validar){
				//alert(2);
				if (pArrayPropriedades != null) {					
					//valoresPermitidos eh uma string com campo separador
					var valoresPermitidos = pArrayItensPermitidos[id];				

					//valida pra cada valor de pArrayPropriedades
					//se pelo menos uma condicao se satisfizer, resolvido
					for (i = 0; i < pArrayPropriedades.length; i++) {
						var propri = pArrayPropriedades[i];						
						/*alert(propri);
						alert(valoresPermitidos);*/
						if(valoresPermitidos.indexOf(propri) == -1){
							retorno = false;	
							//alert("nao encontrou");
						}else{
							//alert("encontrou");
							//se encontrou pelo menos um, retorna verdadeiro
							retorno = true;
							break;
						}										

					}
				}				
				
			}/*else{
				retorno = false;
			}*/
		}

	}	
				
	if (!retorno) {
		checkBox.checked = !checkBox.checked;

		if (!pSemMensagem) {
			exibirMensagem("Sele��o n�o permitida para o usu�rio.");
		}

	}

	return retorno;
}
