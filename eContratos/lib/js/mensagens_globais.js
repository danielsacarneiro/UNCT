/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */

/*
 Descrição:
 - Contém todas as mensagens e funções de apoio utilizadas pelas 
   demais bibliotecas
 - Deve ser incluída em todas as JSPs
 
 Dependências:
 - biblioteca_funcoes_principal.js
*/

// Mensagens
_mensagensGlobais = new Array(1500);

// 0-29 -> biblioteca_funcoes_principal.js
_mensagensGlobais[0] = "Obs: campo obrigatório (*).";
_mensagensGlobais[1] = "Obs: campo não obrigatório.";
_mensagensGlobais[2] = "Campo obrigatório (*)!";
_mensagensGlobais[3] = "Confirma?";
_mensagensGlobais[4] = "Aguarde...";
_mensagensGlobais[5] = "A última operação foi interrompida!\nOs dados enviados podem ter sido processados ou não.\n\nContinua?";
_mensagensGlobais[6] = "A última operação foi interrompida!\nOs dados enviados podem ter sido processados ou não.";
_mensagensGlobais[7] = "Já foi executada uma operação com sucesso neste formulário!\nContinua?";
_mensagensGlobais[8] = "Ocorreu um erro!"
_mensagensGlobais[9] = "Tente novamente. Se o problema persistir, contacte o suporte.";
_mensagensGlobais[10] = "Feche esta janela e volte para a janela principal do e-Fisco.\nSe o problema persistir, contacte o suporte.";
_mensagensGlobais[11] = "Confirma o encerramento da sessão?";
_mensagensGlobais[12] = "Obs: campo obrigatório.";
_mensagensGlobais[13] = "Campo obrigatório!";
_mensagensGlobais[14] = "Já foi executada uma operação com sucesso neste formulário!\nNão é possível submetê-lo novamente.";
_mensagensGlobais[15] = "Ao encerrar a sessão solicitaremos o fechamento\ndo browser para evitar o uso indevido do certificado digital.\n\nConfirma o encerramento da sessão?";
_mensagensGlobais[16] = "Nenhuma resposta foi recebida até o momento!\nTente novamente."
_mensagensGlobais[17] = "Caso não chegue uma resposta em até 120 segundos, esta caixa de mensagem será fechada automaticamente e o botão ou o link acionado será liberado novamente.";
_mensagensGlobais[18] = "Caso não chegue uma resposta em até 10 segundos, esta caixa de mensagem será fechada automaticamente.";
_mensagensGlobais[19] = "A última operação foi interrompida!\nOs dados enviados podem ter sido processados ou não.\nNão é possível submeter o formulário novamente.";
_mensagensGlobais[20] = "Confirma a limpeza da unidade de alocação selecionada? \nCaso confirme, você será redirecionado para o menu principal.";
_mensagensGlobais[21] = "Confirma a limpeza da unidade de alocação selecionada?";


// 30-59 -> biblioteca_funcoes_text.js
_mensagensGlobais[30] = "Campo numérico inválido!";
_mensagensGlobais[31] = "O número deve ter " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[32] = "O valor máximo permitido é " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[33] = "O valor mínimo permitido é " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[34] = "Campo alfanumérico inválido!";
_mensagensGlobais[35] = "O campo deve ter no mínimo " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[36] = "O campo deve ter no máximo " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[37] = "Endereço de e-mail inválido!";
_mensagensGlobais[38] = "Campo decimal inválido!";
_mensagensGlobais[39] = "A quantidade máxima de casas decimais permitida é " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[40] = "Campo numérico negativo inválido!";
_mensagensGlobais[41] = "Campo numérico positivo inválido!";
_mensagensGlobais[42] = "Campo alfabético inválido!";
_mensagensGlobais[43] = "Caracter digitado inválido!";
_mensagensGlobais[44] = "Campo alfanumérico extendido inválido!\nOs caracteres permitidos são [A-Z] (letras maiúsculas), [0-9] (números)" + CD_CAMPO_SUBSTITUICAO + ".";
_mensagensGlobais[45] = "Campo texto inválido!\nOs caracteres não permitidos são " + CD_CAMPO_SUBSTITUICAO + ".";

// 60-89 -> biblioteca_funcoes_radiobuttom.js
_mensagensGlobais[60] = "Nenhum registro está disponível!";
_mensagensGlobais[61] = "Selecione um registro!";
_mensagensGlobais[62] = "Selecione uma opção!";

// 90-119 -> biblioteca_funcoes_cpfcnpj.js
_mensagensGlobais[90] = "CPF inválido!";
_mensagensGlobais[91] = "CNPJ inválido!";
_mensagensGlobais[92] = "Radical do CNPJ inválido!";

// 120-149 -> biblioteca_funcoes_datahora.js
_mensagensGlobais[120] = "Data inválida!";
_mensagensGlobais[121] = "Hora inválida!";
_mensagensGlobais[122] = "A data final deve ser maior ou igual à data inicial!";
_mensagensGlobais[123] = "Os formatos das datas devem ser o mesmo (dd/mm/aaaa)!";
_mensagensGlobais[124] = "Período inválido!"; 
_mensagensGlobais[125] = "Preencha a data e a hora iniciais!";
_mensagensGlobais[126] = "Preencha a data e a hora finais!";
_mensagensGlobais[127] = "A data deve ser maior que " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[128] = "A data deve ser menor que " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[129] = "A data deve ser igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[130] = "A data deve ser maior ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[131] = "A data deve ser menor ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[132] = "A data final deve ser maior que a data inicial!";
_mensagensGlobais[133] = "O ano deve ser maior ou igual a 1900!";
_mensagensGlobais[134] = "A data do servidor não está disponível!";
_mensagensGlobais[135] = "Utilize o formato mm/aaaa.";
_mensagensGlobais[136] = "Utilize o formato dd/mm.";
_mensagensGlobais[137] = "Os formatos das datas devem ser o mesmo (mm/aaaa)!";
_mensagensGlobais[138] = "A data final deve ser maior ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[139] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " dia(s)!";
_mensagensGlobais[140] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " mês(es)!";
_mensagensGlobais[141] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " ano(s)!";
_mensagensGlobais[142] = "A hora do servidor não está disponível!";
_mensagensGlobais[143] = "O ano deve ser menor ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[144] = "Utilize o formato aaaa-b.";

// 150-179 -> biblioteca_funcoes_endereco.js
_mensagensGlobais[150] = "CEP inválido!";
_mensagensGlobais[151] = "Telefone inválido!";

// 180-209 -> biblioteca_funcoes_banco.js
_mensagensGlobais[180] = "Código de Banco inválido!";
_mensagensGlobais[181] = "Código de Agência inválido!";
_mensagensGlobais[182] = "Número de Conta inválido!";

// 210-239 -> biblioteca_funcoes_checkbox.js
_mensagensGlobais[210] = "Nenhum registro está disponível!";
_mensagensGlobais[211] = "Selecione pelo menos um registro!";
_mensagensGlobais[212] = "Selecione apenas um registro!";

// 240-269 -> biblioteca_funcoes_table.js
_mensagensGlobais[240] = "Nenhum registro está disponível!";

// 270-299 -> biblioteca_funcoes_ncm.js
_mensagensGlobais[270] = "Código NCM inválido!";

// 300-329 -> biblioteca_funcoes_ie.js
_mensagensGlobais[300] = "Inscrição Estadual inválida!";
_mensagensGlobais[301] = "Inscrição Estadual Antiga inválida!";

// 330-359 -> biblioteca_funcoes_nuregistronf.js
_mensagensGlobais[330] = "Número de Registro de Nota Fiscal inválido!";

// 360-389 -> biblioteca_funcoes_protocolo.js
_mensagensGlobais[360] = "Número de Protocolo inválido!";

// 390-419 -> biblioteca_funcoes_moeda.js e biblioteca_funcoes_decimal.js
_mensagensGlobais[390] = "Valor Monetário inválido!";
_mensagensGlobais[391] = "Valor Decimal inválido!";

// 420-449 -> biblioteca_funcoes_listbox.js
_mensagensGlobais[420] = "Selecione um item da lista!";
_mensagensGlobais[421] = "Selecione pelo menos um item da lista!";
_mensagensGlobais[422] = "Insira pelo menos um item na lista!";
_mensagensGlobais[423] = "Item duplicado na lista!";

// 450-479 -> biblioteca_funcoes_cnaefiscal.js
_mensagensGlobais[450] = "Código CNAE Fiscal inválido!";
_mensagensGlobais[451] = "Intervalo de CNAE Fiscal inválido!";

// 480-509 -> biblioteca_funcoes_programatrabalho.js
_mensagensGlobais[480] = "Código de Programa de Trabalho inválido!";

// 510-539 -> biblioteca_funcoes_naturezareceitaorcamentaria.js
_mensagensGlobais[510] = "Código da Natureza da Receita inválido!";

// 540-569 -> biblioteca_funcoes_naturezadespesa.js
_mensagensGlobais[540] = "Código da Natureza da Despesa inválido!";

// 570-599 -> biblioteca_funcoes_naturezareceita.js
_mensagensGlobais[570] = "Código da Natureza da Receita inválido!";
_mensagensGlobais[571] = "Dígito Verificador da Natureza da Receita inválido!";

// 600-629 -> biblioteca_funcoes_vigencia.js
_mensagensGlobais[600] = "Não é possível alterar!\nA vigência deste registro termina hoje.\nInclua um novo registro.";
_mensagensGlobais[601] = "Não é possível alterar!\nEste registro não está mais vigente.\nInclua um novo registro.";
_mensagensGlobais[602] = "Não é possível excluir!\nApenas registros futuros podem ser excluídos!"
_mensagensGlobais[603] = "A data de início de vigência deve ser maior ou igual à data de hoje!";
_mensagensGlobais[604] = "A data de fim de vigência deve ser maior ou igual à data de hoje!\nObs: Campo não obrigatório.";
_mensagensGlobais[605] = "Não é possível alterar!\nO registro escolhido está em histórico!"
_mensagensGlobais[606] = "Não é possível excluir!\nO registro escolhido está em histórico!"
_mensagensGlobais[607] = "A data final deve ser maior que a data inicial!"
_mensagensGlobais[608] = "A data incial deve ser menor ou igual que a data final!"
_mensagensGlobais[609] = "Em registros vigentes só são permitidas mudanças na data inicial para datas anteriores à data inicial atual!"
_mensagensGlobais[610] = "O registro não pode ser excluído pois está desativado!"

// 630-659 -> biblioteca_funcoes_nudi.js
_mensagensGlobais[630] = "Número de Documento de Importação inválido!";

// 660-689 -> biblioteca_funcoes_nupassefiscal.js
_mensagensGlobais[660] = "Número de Passe Fiscal inválido!";

// 690-719 -> biblioteca_funcoes_numerocarga.js
_mensagensGlobais[690] = "Número de Carga inválido!";

// 720-749 -> biblioteca_funcoes_inscricao_da.js
_mensagensGlobais[720] = "Número de Inscrição DA inválido!";

// 750-779 -> biblioteca_funcoes_codigo_conta_contabil.js
_mensagensGlobais[750] = "Código de Conta Contábil inválido!";

// 780-809 -> biblioteca_funcoes_codigo_evento_contabil.js
_mensagensGlobais[780] = "Código de Evento Contábil inválido!";

// 810-839 -> biblioteca_funcoes_registrodocfiscal.js
_mensagensGlobais[810] = "Número de Registro de Nota Fiscal inválido!";

// 840-869 -> biblioteca_funcoes_inscricao_suframa.js
_mensagensGlobais[840] = "Número de Inscrição SUFRAMA inválido!";

// 870-899 -> biblioteca_funcoes_crc.js
_mensagensGlobais[870] = "Número de CRC inválido!";

// 900-929 -> biblioteca_funcoes_inscricao_pessoa_estrangeira.js
_mensagensGlobais[900] = "Número de Inscrição de Pessoa Estrangeira inválido!";

// 930-959 -> biblioteca_funcoes_protocolopge.js
_mensagensGlobais[930] = "Número de Protocolo PGE inválido!";

// 960-989 -> biblioteca_funcoes_file.js
_mensagensGlobais[960] = "Caminho do arquivo inválido!";

// 990-1019 -> bilioteca_funcoes_taxafusp.js
_mensagensGlobais[990] = "Código de Taxa Fusp inválido!";
_mensagensGlobais[991] = "Código de Taxa Fusp inválido! \nCódigo de taxa igual a \"00\"!";
_mensagensGlobais[992] = "Código de Taxa Fusp inválido! \nCódigo de serviço igual a \"00\" com subserviço posterior diferente de \"00\"!";
_mensagensGlobais[993] = "Código de Taxa Fusp inválido! \nCódigo de subserviço1 igual a \"00\" com subserviço posterior diferente de \"00\"!";
_mensagensGlobais[994] = "Código de Taxa Fusp inválido! \nCódigo de subserviço2 igual a \"00\" com subserviço posterior diferente de \"00\"!";
_mensagensGlobais[995] = "Código de Taxa Fusp inválido! \nCódigo de subserviço3 igual a \"00\" com subserviço posterior diferente de \"00\"!"; 
_mensagensGlobais[996] = "Código de Taxa Fusp inválido! \nCódigo de subserviço4 igual a \"00\" com subserviço posterior diferente de \"00\"!"; 
_mensagensGlobais[997] = "Código de Taxa Fusp inválido! \nCódigo de subserviço5 igual a \"00\" com subserviço posterior diferente de \"00\"!"; 

// 1020-1049 -> biblioteca_funcoes_nudi.js
_mensagensGlobais[1020] = "Número de Matrícula do Autuante inválido!";

// 1050-1079 -> biblioteca_funcoes_enderecoip.js
_mensagensGlobais[1050] = "Endereço IP inválido!";

// 1080-1109 -> biblioteca_funcoes_numerodocumentocontabil.js
_mensagensGlobais[1080] = "Número do Documento Contábil inválido!";
_mensagensGlobais[1081] = "Tipo do Documento Contábil inválido!";

// 1110-1139 -> biblioteca_funcoes_numero_nire.js
_mensagensGlobais[1110] = "NIRE inválido!";

// 1140-1169 -> biblioteca_funcoes_placaveiculo.js
_mensagensGlobais[1140] = "Placa inválida!";

// 1170-1199 ->biblioteca_funcoes_expressao_regular.js
_mensagensGlobais[1170] = "Máscara inválida!";
_mensagensGlobais[1171] = "Caractere alfabético esperado! \nObs: o campo aceita a máscara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1172] = "Caractere numérico esperado! \nObs: o campo aceita a máscara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1173] = "Caractere alfanumérico esperado! \nObs: o campo aceita a máscara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1174] = "Tamanho inválido! ! \nObs: o campo aceita a máscara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1175] = "Dado inválido! ! \nObs: o campo aceita a máscara \"" + CD_CAMPO_SUBSTITUICAO + "\".";

// 1200-1229 ->biblioteca_funcoes_nuprocessolegado.js
_mensagensGlobais[1200] = "Número de Processo Legado inválido!";

// 1230-1259 ->biblioteca_funcoes_numeroprotocololegadonf.js
_mensagensGlobais[1230] = "Número do Protocolo de Legado de Nota Fiscal inválido!";
_mensagensGlobais[1231] = "Número de Registro ou Protocolo de Legado de Nota Fiscal inválido!";


// 1260-1289 ->biblioteca_funcoes_editortexto.js
_mensagensGlobais[1260] = "O limite de " + CD_CAMPO_SUBSTITUICAO + " caracteres foi ultrapassado.\nO número de caracteres permitido é o texto digitado mais os caracteres ocultos de formatação (negrito, tamanho, cor da fonte, etc).\nPara adequar seu texto ao número de caracteres permitido utilize as seguintes sugestões:\n1 - Diminua a quantidade de formatação e o número de parágrafos do texto.\n2 - Resuma o texto.";

// 1290-1319 ->biblioteca_funcoes_numeroextratofronteira.js
_mensagensGlobais[1290] = "Número de Extrato Fronteiras inválido!"; 

// 1320-1359 ->biblioteca_funcoes_unidadegestora.js
_mensagensGlobais[1320] = "Unidade Gestora inválida!"; 
_mensagensGlobais[1321] = "Unidade Gestora inválida!\nDigite um valor numérico positivo."; 

// 1360-1389 -> biblioteca_funcoes_matricula_servidor.js
_mensagensGlobais[1360] = "Número de Matrícula do Servidor Público inválido!"; 

// 1390-1399 -> biblioteca_funcoes_codigogtin.js
_mensagensGlobais[1390] = "Código GTIN inválido!";

//1400-1409 -> biblioteca_funcoes_autocompletar.js
_mensagensGlobais[1400] = "Valor digitado inválido para " + CD_CAMPO_SUBSTITUICAO + ".";
_mensagensGlobais[1401] = "Valor digitado corresponde a mais de um " + CD_CAMPO_SUBSTITUICAO + ".";

//1410-1419 -> biblioteca_funcoes_oab.js
_mensagensGlobais[1410] = "Número OAB inválido!";

//1420-1429 -> biblioteca_funcoes_numero_tate.js
_mensagensGlobais[1420] = "Número TATE inválido!";

// Retorna a mensagem com o código informado
function mensagemGlobal(pCdMensagem) {
	return _mensagensGlobais[pCdMensagem];
}
