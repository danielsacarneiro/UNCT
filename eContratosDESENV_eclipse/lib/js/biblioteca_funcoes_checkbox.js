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
function isCheckBoxConsultaSelecionado(pNmCheckBox, pSemMensagem, pInTodosObrigatorios) {
	var i = 0;

	//checkBox = eval(pNmCheckBox);
	checkBox = document.getElementsByName(pNmCheckBox);
	
	if (pInTodosObrigatorios) {
		
		if (checkBox != null) {
			if (checkBox.checked) {
				return true;
			} else {
				if (checkBox.length == null)
					return false;
					
				for (i = 0; i < checkBox.length; i++) 
					if (!checkBox.item(i).checked) 
						return false;
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

function validaFormRequiredCheckBox(campoCheckBoxValidacao, colecaoIDCampos, pSetarVazio){
	pIsRequired = !campoCheckBoxValidacao.checked;
	tornarRequiredCamposColecaoFormulario(colecaoIDCampos, pIsRequired);
	
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
