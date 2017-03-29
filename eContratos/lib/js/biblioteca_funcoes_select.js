/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */

/*
 Descrição:
 - Contém funções para tratamento de selects

 Dependências:
 - biblioteca_funcoes_principal.js
*/

function isCampoSelectValido(pCampoSelect, pSemMensagem, pInNaoPermitirOpcaoTodos) {

	var msg = "";
	var vlCampo = pCampoSelect.value;
	
	if (vlCampo.length == 0) {
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(2));
			focarCampo(pCampoSelect);
		}
		return false;
	}

	if (pInNaoPermitirOpcaoTodos) {
		if (vlCampo == CD_OPCAO_TODOS) {
			return false;	
		}
	}

	return true;
}

function retornarValorSelectComoArray(pCampoSelect) {
	var valorSelect = pCampoSelect.value + CD_CAMPO_SEPARADOR;

	return valorSelect.split(CD_CAMPO_SEPARADOR);
}
