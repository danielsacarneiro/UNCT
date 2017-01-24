/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado 
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser 
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */

/*
 Descri��o:
 - Cont�m todas as mensagens e fun��es de apoio utilizadas pelas 
   demais bibliotecas
 - Deve ser inclu�da em todas as JSPs
 
 Depend�ncias:
 - biblioteca_funcoes_principal.js
*/

// Mensagens
_mensagensGlobais = new Array(1500);

// 0-29 -> biblioteca_funcoes_principal.js
_mensagensGlobais[0] = "Obs: campo obrigat�rio (*).";
_mensagensGlobais[1] = "Obs: campo n�o obrigat�rio.";
_mensagensGlobais[2] = "Campo obrigat�rio (*)!";
_mensagensGlobais[3] = "Confirma?";
_mensagensGlobais[4] = "Aguarde...";
_mensagensGlobais[5] = "A �ltima opera��o foi interrompida!\nOs dados enviados podem ter sido processados ou n�o.\n\nContinua?";
_mensagensGlobais[6] = "A �ltima opera��o foi interrompida!\nOs dados enviados podem ter sido processados ou n�o.";
_mensagensGlobais[7] = "J� foi executada uma opera��o com sucesso neste formul�rio!\nContinua?";
_mensagensGlobais[8] = "Ocorreu um erro!"
_mensagensGlobais[9] = "Tente novamente. Se o problema persistir, contacte o suporte.";
_mensagensGlobais[10] = "Feche esta janela e volte para a janela principal do e-Fisco.\nSe o problema persistir, contacte o suporte.";
_mensagensGlobais[11] = "Confirma o encerramento da sess�o?";
_mensagensGlobais[12] = "Obs: campo obrigat�rio.";
_mensagensGlobais[13] = "Campo obrigat�rio!";
_mensagensGlobais[14] = "J� foi executada uma opera��o com sucesso neste formul�rio!\nN�o � poss�vel submet�-lo novamente.";
_mensagensGlobais[15] = "Ao encerrar a sess�o solicitaremos o fechamento\ndo browser para evitar o uso indevido do certificado digital.\n\nConfirma o encerramento da sess�o?";
_mensagensGlobais[16] = "Nenhuma resposta foi recebida at� o momento!\nTente novamente."
_mensagensGlobais[17] = "Caso n�o chegue uma resposta em at� 120 segundos, esta caixa de mensagem ser� fechada automaticamente e o bot�o ou o link acionado ser� liberado novamente.";
_mensagensGlobais[18] = "Caso n�o chegue uma resposta em at� 10 segundos, esta caixa de mensagem ser� fechada automaticamente.";
_mensagensGlobais[19] = "A �ltima opera��o foi interrompida!\nOs dados enviados podem ter sido processados ou n�o.\nN�o � poss�vel submeter o formul�rio novamente.";
_mensagensGlobais[20] = "Confirma a limpeza da unidade de aloca��o selecionada? \nCaso confirme, voc� ser� redirecionado para o menu principal.";
_mensagensGlobais[21] = "Confirma a limpeza da unidade de aloca��o selecionada?";


// 30-59 -> biblioteca_funcoes_text.js
_mensagensGlobais[30] = "Campo num�rico inv�lido!";
_mensagensGlobais[31] = "O n�mero deve ter " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[32] = "O valor m�ximo permitido � " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[33] = "O valor m�nimo permitido � " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[34] = "Campo alfanum�rico inv�lido!";
_mensagensGlobais[35] = "O campo deve ter no m�nimo " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[36] = "O campo deve ter no m�ximo " + CD_CAMPO_SUBSTITUICAO + " caracteres!";
_mensagensGlobais[37] = "Endere�o de e-mail inv�lido!";
_mensagensGlobais[38] = "Campo decimal inv�lido!";
_mensagensGlobais[39] = "A quantidade m�xima de casas decimais permitida � " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[40] = "Campo num�rico negativo inv�lido!";
_mensagensGlobais[41] = "Campo num�rico positivo inv�lido!";
_mensagensGlobais[42] = "Campo alfab�tico inv�lido!";
_mensagensGlobais[43] = "Caracter digitado inv�lido!";
_mensagensGlobais[44] = "Campo alfanum�rico extendido inv�lido!\nOs caracteres permitidos s�o [A-Z] (letras mai�sculas), [0-9] (n�meros)" + CD_CAMPO_SUBSTITUICAO + ".";
_mensagensGlobais[45] = "Campo texto inv�lido!\nOs caracteres n�o permitidos s�o " + CD_CAMPO_SUBSTITUICAO + ".";

// 60-89 -> biblioteca_funcoes_radiobuttom.js
_mensagensGlobais[60] = "Nenhum registro est� dispon�vel!";
_mensagensGlobais[61] = "Selecione um registro!";
_mensagensGlobais[62] = "Selecione uma op��o!";

// 90-119 -> biblioteca_funcoes_cpfcnpj.js
_mensagensGlobais[90] = "CPF inv�lido!";
_mensagensGlobais[91] = "CNPJ inv�lido!";
_mensagensGlobais[92] = "Radical do CNPJ inv�lido!";

// 120-149 -> biblioteca_funcoes_datahora.js
_mensagensGlobais[120] = "Data inv�lida!";
_mensagensGlobais[121] = "Hora inv�lida!";
_mensagensGlobais[122] = "A data final deve ser maior ou igual � data inicial!";
_mensagensGlobais[123] = "Os formatos das datas devem ser o mesmo (dd/mm/aaaa)!";
_mensagensGlobais[124] = "Per�odo inv�lido!"; 
_mensagensGlobais[125] = "Preencha a data e a hora iniciais!";
_mensagensGlobais[126] = "Preencha a data e a hora finais!";
_mensagensGlobais[127] = "A data deve ser maior que " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[128] = "A data deve ser menor que " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[129] = "A data deve ser igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[130] = "A data deve ser maior ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[131] = "A data deve ser menor ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[132] = "A data final deve ser maior que a data inicial!";
_mensagensGlobais[133] = "O ano deve ser maior ou igual a 1900!";
_mensagensGlobais[134] = "A data do servidor n�o est� dispon�vel!";
_mensagensGlobais[135] = "Utilize o formato mm/aaaa.";
_mensagensGlobais[136] = "Utilize o formato dd/mm.";
_mensagensGlobais[137] = "Os formatos das datas devem ser o mesmo (mm/aaaa)!";
_mensagensGlobais[138] = "A data final deve ser maior ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[139] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " dia(s)!";
_mensagensGlobais[140] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " m�s(es)!";
_mensagensGlobais[141] = "As datas devem estar no intervalo de " + CD_CAMPO_SUBSTITUICAO + " ano(s)!";
_mensagensGlobais[142] = "A hora do servidor n�o est� dispon�vel!";
_mensagensGlobais[143] = "O ano deve ser menor ou igual a " + CD_CAMPO_SUBSTITUICAO + "!";
_mensagensGlobais[144] = "Utilize o formato aaaa-b.";

// 150-179 -> biblioteca_funcoes_endereco.js
_mensagensGlobais[150] = "CEP inv�lido!";
_mensagensGlobais[151] = "Telefone inv�lido!";

// 180-209 -> biblioteca_funcoes_banco.js
_mensagensGlobais[180] = "C�digo de Banco inv�lido!";
_mensagensGlobais[181] = "C�digo de Ag�ncia inv�lido!";
_mensagensGlobais[182] = "N�mero de Conta inv�lido!";

// 210-239 -> biblioteca_funcoes_checkbox.js
_mensagensGlobais[210] = "Nenhum registro est� dispon�vel!";
_mensagensGlobais[211] = "Selecione pelo menos um registro!";
_mensagensGlobais[212] = "Selecione apenas um registro!";

// 240-269 -> biblioteca_funcoes_table.js
_mensagensGlobais[240] = "Nenhum registro est� dispon�vel!";

// 270-299 -> biblioteca_funcoes_ncm.js
_mensagensGlobais[270] = "C�digo NCM inv�lido!";

// 300-329 -> biblioteca_funcoes_ie.js
_mensagensGlobais[300] = "Inscri��o Estadual inv�lida!";
_mensagensGlobais[301] = "Inscri��o Estadual Antiga inv�lida!";

// 330-359 -> biblioteca_funcoes_nuregistronf.js
_mensagensGlobais[330] = "N�mero de Registro de Nota Fiscal inv�lido!";

// 360-389 -> biblioteca_funcoes_protocolo.js
_mensagensGlobais[360] = "N�mero de Protocolo inv�lido!";

// 390-419 -> biblioteca_funcoes_moeda.js e biblioteca_funcoes_decimal.js
_mensagensGlobais[390] = "Valor Monet�rio inv�lido!";
_mensagensGlobais[391] = "Valor Decimal inv�lido!";

// 420-449 -> biblioteca_funcoes_listbox.js
_mensagensGlobais[420] = "Selecione um item da lista!";
_mensagensGlobais[421] = "Selecione pelo menos um item da lista!";
_mensagensGlobais[422] = "Insira pelo menos um item na lista!";
_mensagensGlobais[423] = "Item duplicado na lista!";

// 450-479 -> biblioteca_funcoes_cnaefiscal.js
_mensagensGlobais[450] = "C�digo CNAE Fiscal inv�lido!";
_mensagensGlobais[451] = "Intervalo de CNAE Fiscal inv�lido!";

// 480-509 -> biblioteca_funcoes_programatrabalho.js
_mensagensGlobais[480] = "C�digo de Programa de Trabalho inv�lido!";

// 510-539 -> biblioteca_funcoes_naturezareceitaorcamentaria.js
_mensagensGlobais[510] = "C�digo da Natureza da Receita inv�lido!";

// 540-569 -> biblioteca_funcoes_naturezadespesa.js
_mensagensGlobais[540] = "C�digo da Natureza da Despesa inv�lido!";

// 570-599 -> biblioteca_funcoes_naturezareceita.js
_mensagensGlobais[570] = "C�digo da Natureza da Receita inv�lido!";
_mensagensGlobais[571] = "D�gito Verificador da Natureza da Receita inv�lido!";

// 600-629 -> biblioteca_funcoes_vigencia.js
_mensagensGlobais[600] = "N�o � poss�vel alterar!\nA vig�ncia deste registro termina hoje.\nInclua um novo registro.";
_mensagensGlobais[601] = "N�o � poss�vel alterar!\nEste registro n�o est� mais vigente.\nInclua um novo registro.";
_mensagensGlobais[602] = "N�o � poss�vel excluir!\nApenas registros futuros podem ser exclu�dos!"
_mensagensGlobais[603] = "A data de in�cio de vig�ncia deve ser maior ou igual � data de hoje!";
_mensagensGlobais[604] = "A data de fim de vig�ncia deve ser maior ou igual � data de hoje!\nObs: Campo n�o obrigat�rio.";
_mensagensGlobais[605] = "N�o � poss�vel alterar!\nO registro escolhido est� em hist�rico!"
_mensagensGlobais[606] = "N�o � poss�vel excluir!\nO registro escolhido est� em hist�rico!"
_mensagensGlobais[607] = "A data final deve ser maior que a data inicial!"
_mensagensGlobais[608] = "A data incial deve ser menor ou igual que a data final!"
_mensagensGlobais[609] = "Em registros vigentes s� s�o permitidas mudan�as na data inicial para datas anteriores � data inicial atual!"
_mensagensGlobais[610] = "O registro n�o pode ser exclu�do pois est� desativado!"

// 630-659 -> biblioteca_funcoes_nudi.js
_mensagensGlobais[630] = "N�mero de Documento de Importa��o inv�lido!";

// 660-689 -> biblioteca_funcoes_nupassefiscal.js
_mensagensGlobais[660] = "N�mero de Passe Fiscal inv�lido!";

// 690-719 -> biblioteca_funcoes_numerocarga.js
_mensagensGlobais[690] = "N�mero de Carga inv�lido!";

// 720-749 -> biblioteca_funcoes_inscricao_da.js
_mensagensGlobais[720] = "N�mero de Inscri��o DA inv�lido!";

// 750-779 -> biblioteca_funcoes_codigo_conta_contabil.js
_mensagensGlobais[750] = "C�digo de Conta Cont�bil inv�lido!";

// 780-809 -> biblioteca_funcoes_codigo_evento_contabil.js
_mensagensGlobais[780] = "C�digo de Evento Cont�bil inv�lido!";

// 810-839 -> biblioteca_funcoes_registrodocfiscal.js
_mensagensGlobais[810] = "N�mero de Registro de Nota Fiscal inv�lido!";

// 840-869 -> biblioteca_funcoes_inscricao_suframa.js
_mensagensGlobais[840] = "N�mero de Inscri��o SUFRAMA inv�lido!";

// 870-899 -> biblioteca_funcoes_crc.js
_mensagensGlobais[870] = "N�mero de CRC inv�lido!";

// 900-929 -> biblioteca_funcoes_inscricao_pessoa_estrangeira.js
_mensagensGlobais[900] = "N�mero de Inscri��o de Pessoa Estrangeira inv�lido!";

// 930-959 -> biblioteca_funcoes_protocolopge.js
_mensagensGlobais[930] = "N�mero de Protocolo PGE inv�lido!";

// 960-989 -> biblioteca_funcoes_file.js
_mensagensGlobais[960] = "Caminho do arquivo inv�lido!";

// 990-1019 -> bilioteca_funcoes_taxafusp.js
_mensagensGlobais[990] = "C�digo de Taxa Fusp inv�lido!";
_mensagensGlobais[991] = "C�digo de Taxa Fusp inv�lido! \nC�digo de taxa igual a \"00\"!";
_mensagensGlobais[992] = "C�digo de Taxa Fusp inv�lido! \nC�digo de servi�o igual a \"00\" com subservi�o posterior diferente de \"00\"!";
_mensagensGlobais[993] = "C�digo de Taxa Fusp inv�lido! \nC�digo de subservi�o1 igual a \"00\" com subservi�o posterior diferente de \"00\"!";
_mensagensGlobais[994] = "C�digo de Taxa Fusp inv�lido! \nC�digo de subservi�o2 igual a \"00\" com subservi�o posterior diferente de \"00\"!";
_mensagensGlobais[995] = "C�digo de Taxa Fusp inv�lido! \nC�digo de subservi�o3 igual a \"00\" com subservi�o posterior diferente de \"00\"!"; 
_mensagensGlobais[996] = "C�digo de Taxa Fusp inv�lido! \nC�digo de subservi�o4 igual a \"00\" com subservi�o posterior diferente de \"00\"!"; 
_mensagensGlobais[997] = "C�digo de Taxa Fusp inv�lido! \nC�digo de subservi�o5 igual a \"00\" com subservi�o posterior diferente de \"00\"!"; 

// 1020-1049 -> biblioteca_funcoes_nudi.js
_mensagensGlobais[1020] = "N�mero de Matr�cula do Autuante inv�lido!";

// 1050-1079 -> biblioteca_funcoes_enderecoip.js
_mensagensGlobais[1050] = "Endere�o IP inv�lido!";

// 1080-1109 -> biblioteca_funcoes_numerodocumentocontabil.js
_mensagensGlobais[1080] = "N�mero do Documento Cont�bil inv�lido!";
_mensagensGlobais[1081] = "Tipo do Documento Cont�bil inv�lido!";

// 1110-1139 -> biblioteca_funcoes_numero_nire.js
_mensagensGlobais[1110] = "NIRE inv�lido!";

// 1140-1169 -> biblioteca_funcoes_placaveiculo.js
_mensagensGlobais[1140] = "Placa inv�lida!";

// 1170-1199 ->biblioteca_funcoes_expressao_regular.js
_mensagensGlobais[1170] = "M�scara inv�lida!";
_mensagensGlobais[1171] = "Caractere alfab�tico esperado! \nObs: o campo aceita a m�scara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1172] = "Caractere num�rico esperado! \nObs: o campo aceita a m�scara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1173] = "Caractere alfanum�rico esperado! \nObs: o campo aceita a m�scara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1174] = "Tamanho inv�lido! ! \nObs: o campo aceita a m�scara \"" + CD_CAMPO_SUBSTITUICAO + "\".";
_mensagensGlobais[1175] = "Dado inv�lido! ! \nObs: o campo aceita a m�scara \"" + CD_CAMPO_SUBSTITUICAO + "\".";

// 1200-1229 ->biblioteca_funcoes_nuprocessolegado.js
_mensagensGlobais[1200] = "N�mero de Processo Legado inv�lido!";

// 1230-1259 ->biblioteca_funcoes_numeroprotocololegadonf.js
_mensagensGlobais[1230] = "N�mero do Protocolo de Legado de Nota Fiscal inv�lido!";
_mensagensGlobais[1231] = "N�mero de Registro ou Protocolo de Legado de Nota Fiscal inv�lido!";


// 1260-1289 ->biblioteca_funcoes_editortexto.js
_mensagensGlobais[1260] = "O limite de " + CD_CAMPO_SUBSTITUICAO + " caracteres foi ultrapassado.\nO n�mero de caracteres permitido � o texto digitado mais os caracteres ocultos de formata��o (negrito, tamanho, cor da fonte, etc).\nPara adequar seu texto ao n�mero de caracteres permitido utilize as seguintes sugest�es:\n1 - Diminua a quantidade de formata��o e o n�mero de par�grafos do texto.\n2 - Resuma o texto.";

// 1290-1319 ->biblioteca_funcoes_numeroextratofronteira.js
_mensagensGlobais[1290] = "N�mero de Extrato Fronteiras inv�lido!"; 

// 1320-1359 ->biblioteca_funcoes_unidadegestora.js
_mensagensGlobais[1320] = "Unidade Gestora inv�lida!"; 
_mensagensGlobais[1321] = "Unidade Gestora inv�lida!\nDigite um valor num�rico positivo."; 

// 1360-1389 -> biblioteca_funcoes_matricula_servidor.js
_mensagensGlobais[1360] = "N�mero de Matr�cula do Servidor P�blico inv�lido!"; 

// 1390-1399 -> biblioteca_funcoes_codigogtin.js
_mensagensGlobais[1390] = "C�digo GTIN inv�lido!";

//1400-1409 -> biblioteca_funcoes_autocompletar.js
_mensagensGlobais[1400] = "Valor digitado inv�lido para " + CD_CAMPO_SUBSTITUICAO + ".";
_mensagensGlobais[1401] = "Valor digitado corresponde a mais de um " + CD_CAMPO_SUBSTITUICAO + ".";

//1410-1419 -> biblioteca_funcoes_oab.js
_mensagensGlobais[1410] = "N�mero OAB inv�lido!";

//1420-1429 -> biblioteca_funcoes_numero_tate.js
_mensagensGlobais[1420] = "N�mero TATE inv�lido!";

// Retorna a mensagem com o c�digo informado
function mensagemGlobal(pCdMensagem) {
	return _mensagensGlobais[pCdMensagem];
}
