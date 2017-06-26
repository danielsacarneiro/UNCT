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

// Verifica se algum "radiobutton" esta selecionado na colecao com nome "pNmRadioButton" passado como parametro.
// Se  pSemMensagem igual a true, não exibe mensagem
// Utilizado em telas de consulta.
function isRadioButtonConsultaSelecionado(pNmRadioButton, pSemMensagem) {
	var i = 0;

	radioButton = eval(pNmRadioButton);
	
	if (radioButton == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(60));
		}
		return false;
	}
	
	if (radioButton.checked) {
		// Quando existe apenas um radio button no formulario
		return true;
	}
	
	for (i = 0; i < radioButton.length; i++) {
		if (radioButton.item(i).checked == true) {
			return true;
		}
	}
	
	if (!pSemMensagem) {
		exibirMensagem(mensagemGlobal(61));
	}
	return false;
}

// Verifica se algum "radiobutton" esta selecionado na colecao com nome "pNmRadioButton" passado como parametro.
// Se  pSemMensagem igual a true, não exibe mensagem
// Utilizado em telas de consulta.
function isRadioButtonSelecionado(pNmRadioButton, pSemMensagem) {
	var i = 0;

	radioButton = eval(pNmRadioButton);
	
	if (radioButton == null) {
		return false;
	}
	
	if (radioButton.checked) {
		// Quando existe apenas um radio button no formulario
		return true;
	}
	
	for (i = 0; i < radioButton.length; i++) {
		if (radioButton.item(i).checked == true) {
			return true;
		}
	}
	
	if (!pSemMensagem) {
		exibirMensagem(mensagemGlobal(62));
	}
	return false;
}

// Retorna um Array com os campos que compõem o valor do radiobutton "pRadioButton" passado como parâmetro
// Os campos devem estar separados pela constante CD_CAMPO_SEPARADOR
function retornarValorRadioButtonSelecionadoComoArray(pNmRadioButton, pSemMensagem) {
	var i;

	radioButton = eval(pNmRadioButton);
	
	if (radioButton == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(60));
		}	
		return false;
	}
	
	if (radioButton.checked) {
		// Quando existe apenas um radio button no formulario
		valorRadioButton = radioButton.value + CD_CAMPO_SEPARADOR;
		return valorRadioButton.split(CD_CAMPO_SEPARADOR);
	}

	for (i = 0; i < radioButton.length; i++) {
		if (radioButton.item(i).checked) {
			valorRadioButton = radioButton.item(i).value;
			return valorRadioButton.split(CD_CAMPO_SEPARADOR);
		}
	}

	return "";
}


// Retorna o valor de todos os radiobuttons "pRadioButton" passado como parametro
function retornarValoresTodosRadioButtonComoString(pNmRadioButton, pSemMensagem) {
	var i;
	var retorno  = "";

	radioButton = eval(pNmRadioButton);
	
	if (radioButton == null) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(60));
		}
		return false;
	} 

	if (!radioButton.length) {
		retorno = radioButton.value + CD_CAMPO_SEPARADOR_AUX;
	}
	
	for (i = 0; i < radioButton.length; i++) {
		retorno = retorno + radioButton.item(i).value + CD_CAMPO_SEPARADOR_AUX;
	}

	return retorno;
}

// Retorna o valor do radiobutton "pRadioButton" passado como parametro
function retornarValorRadioButtonSelecionado(pNmRadioButton) {
	var i;

	radioButton = eval(pNmRadioButton);
	
	if (radioButton == null) {
		exibirMensagem(mensagemGlobal(60));
		return false;
	}
	
	if (radioButton.checked) {
		// Quando existe apenas um radio button no formulario
		return radioButton.value;
	}

	for (i = 0; i < radioButton.length; i++) {
		if (radioButton.item(i).checked) {
			return radioButton.item(i).value;
		}
	}

	return "";
}

// Retorna um Array de Arrays com os campos que compõem o valor do radiobutton "pRadioButton" passado como parâmetro
// Os campos devem estar separados pela constante CD_CAMPO_SEPARADOR
function retornarValoresTodosRadioButtonComoArrayDeArray(pNmRadioButton, pSemMensagem) {
    var i;
    var retorno;

    radioButton = eval(pNmRadioButton);

    if (radioButton == null) {
        if (!pSemMensagem) {
           exibirMensagem(mensagemGlobal(60));
        }
        retorno = ""; 
    } else {
        // Caso exista só um registro no formulário
		if (!radioButton.length) {
            valorRadioButton = radioButton.value;
            retorno = new Array(1); 
            retorno[0] = valorRadioButton.split(CD_CAMPO_SEPARADOR); 
        } else {        
            retorno = new Array(radioButton.length); 
            for (i = 0; i < radioButton.length; i++) { 
                valorRadioButton = radioButton.item(i).value;
                retorno[i] = valorRadioButton.split(CD_CAMPO_SEPARADOR); 
            }
        }
    } 

    return retorno;
}

// Seleciona o radiobutton com valor igual a "pVlRadioButton" passado como parametro
function selecionarRadioButton(pRadioButton, pVlRadioButton) {
	var i = 0;
	
	for (i = 0; i < pRadioButton.length; i++) {
		if (pRadioButton.item(i).value == pVlRadioButton) {
			pRadioButton.item(i).checked = true;
			return;
		}
	}
}

//Seleciona o radiobutton com valor igual a "pVlRadioButton" passado como parametro
function esvaziarRadioButton(pRadioButton) {
	var i = 0;
	
	for (i = 0; i < pRadioButton.length; i++) {
		pRadioButton.item(i).checked = false;
	}
}
