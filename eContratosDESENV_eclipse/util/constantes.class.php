<?php
//header ('Content-type: text/html; charset=ISO-8859-1');
include_once 'normativos.php';

  Class constantes {
  	STATIC $DATA_INVALIDA_CONVERTIDA_DE_STRING = "31/12/1969";
  	static $NM_SERVIDOR = 'sf300451';
	static $nomeSistema = "e-CONTi";
	static $TAMANHO_MAXIMO_NM_ARQUIVO = 40;
	
	static $ANO_INICIO = "2016";
	static $DATA_INICIO = "01/01/1900";
	static $DATA_FIM = "01/01/2099";
	
	static $qts_dias_ALERTA_VERMELHO = 20;
	static $qts_dias_ALERTA_AMARELO = 45;
	static $qts_dias_ALERTA_DEMANDA_CONTRATO_AVENCER = 10;
    
    static $CD_TIPO_CONTRATO  = "C";
    static $CD_TIPO_CONVENIO  = "V";
    static $CD_TIPO_PROFISCO  = "P";

    static $DS_TIPO_CONTRATO  = "C-SAFI";
    static $DS_TIPO_CONVENIO  = "CV-SAFI";
    static $DS_TIPO_PROFISCO  = "C-PROFISCO";
    
    static $CD_FUNCAO_EXCLUIR = "E";
    static $CD_FUNCAO_EXCLUIR_VARIOS = "excluirVarios";
    static $CD_FUNCAO_HISTORICO = "H";
    static $CD_FUNCAO_INCLUIR = "I";
    static $CD_FUNCAO_ALTERAR = "A";
    static $CD_FUNCAO_DETALHAR = "D";
    static $CD_FUNCAO_SELECIONAR = "S";
    static $CD_FUNCAO_TODAS = "T";
    
    static $DS_SIM  = "Sim";
    static $DS_NAO  = "Não";
    static $DS_OPCAO_TODOS = "Todos";
    static $DS_OPCAO_NENHUM = "Nenhum";
    static $DS_OPCAO_NAO_INFORMADO = "*Não informado*";
    static $DS_OPCAO_NAO_SEAPLICA = "*Não se aplica*";
    static $DS_OPCAO_SELECIONE = "--Selec.--";
     
    static $CD_SIM  = "S";
    static $CD_NAO  = "N"; 
    static $CD_OPCAO_TODOS  = "op_todos";
    static $CD_OPCAO_VAZIO = "op_vazio";
    static $CD_OPCAO_NENHUM = "op_nenhum";
    static $CD_OPCAO_NAO_SEAPLICA = "00";
    static $CD_OPCAO_CONSULTA_IGUAL = "=";
    static $CD_OPCAO_CONSULTA_DIFERENTE = "<>";
    
    static $CD_ORDEM_CRESCENTE = "ASC";
    static $CD_ORDEM_DECRESCENTE = "DESC";
    
    static $CD_OPCAO_OR = "OR";
    static $CD_OPCAO_AND = "AND";
    static $DS_OPCAO_OR = "OU";
    static $DS_OPCAO_AND = "E";
        
    static $CD_CLASS_CAMPO_OBRIGATORIO = "campoobrigatorio";
    static $CD_CLASS_CAMPO_NAO_OBRIGATORIO = "camponaoobrigatorio";
    static $CD_CLASS_CAMPO_READONLY = "camporeadonly";
    static $CD_CLASS_CAMPO_OBRIGATORIO_DIREITA = "campoobrigatorioalinhadodireita";
    static $CD_CLASS_CAMPO_NAO_OBRIGATORIO_DIREITA = "camponaoobrigatorioalinhadodireita";
    static $CD_CLASS_CAMPO_READONLY_DIREITA = "camporeadonlyalinhadodireita";
    
    static $CD_NOVA_LINHA = "\n";
    
    static $TAMANHO_CODIGOS = 5;
    static $TAMANHO_CODIGOS_SAFI = 3;
    static $CD_CAMPO_SEPARADOR = "*";
    static $CD_CAMPO_SEPARADOR_FILTRO = "SEPARADOR*FILTRO*SEPARADOR";
    static $CD_CAMPO_SUBSTITUICAO = "[[*]]";
    static $DS_CAMPO_NAO_ENCONTRADO= "NÃO.ENCONTRADO";
    static $CD_CAMPO_NULO = "null";
    static $ID_REQ_SESSAO_VO = "vo";
    static $ID_REQ_CD_LUPA = "lupa";
    static $ID_REQ_CD_CONSULTAR = "consultar";
    
    static $ID_REQ_MULTISELECAO = "multiSelecao";
    static $ID_REQ_IN_ENVIAR_EMAIL = "ENVIAR_EMAIL";
    static $ID_REQ_GETPARAM_GET = "ID_REQ_GETPARAM_GET";

    static $CD_COLUNA_CHAVE  = "COLUNA_CD";
    static $CD_COLUNA_VALOR = "COLUNA_VALOR";
    static $CD_COLUNA_TP_DADO = "COLUNA_TP_DADO";
    static $CD_COLUNA_VL_REFERENCIA = "COLUNA_VL_REFERENCIA";
    static $CD_COLUNA_TP_VALIDACAO = "CD_COLUNA_TP_VALIDACAO";
    static $CD_COLUNA_NM_CLASSE_DOMINIO = "CD_COLUNA_NM_CLASSE_DOMINIO";
    static $CD_ALERTA_TP_VALIDACAO_MAIORQUE = "MAIORQUE";
    static $CD_ALERTA_TP_VALIDACAO_MENORQUE = "MENORQUE";
    static $CD_ALERTA_TP_VALIDACAO_IGUAL = "IGUAL";
    static $CD_COLUNA_CONTRATO = "CONTRATO";
    
    //constantes que comporao o $CD_COLUNA_TP_DADO
    static $CD_TP_DADO_DATA = "DATA";
    static $CD_TP_DADO_DOMINIO= "DOMINIO";
    
    static $CD_MODELO_TEXTO = "[XXX MODELO - ALTERAR XXX]";
    static $CD_MODELO = "MODELO";
    
    static $TAMANHO_CARACTERES_PRT = 18;
    static $TAMANHO_CARACTERES_SEI = 22;
    
    static $TAMANHO_CAMPO_VALOR = 15;
    static $TAMANHO_CAMPO_DATA = 10;
    
    //os dados do usuario batch estao no banco wordpress
    static $CD_USUARIO_BATCH = 26;
    static $NM_USUARIO_BATCH = "USUÁRIO BATCH";
    
    static $VL_GLOBAL_ENVIO_PGE = 2000000;
    static $VL_GLOBAL_ENVIO_SAD = 3000000;
    
    static $DS_BANCO = "*BANCO*";
    static $NM_FUNCAO_JS_COPIADADOS_TERMO_ANTERIOR = "copiarDadosTermoAnterior";
    static $ID_REQ_CHECK_RESPONSABILIDADE = 'checkResponsabilidade';
    
    static $ID_REQ_COLECAO_EXPORTAR_PLANILHA = "ID_REQ_COLECAO_EXPORTAR_PLANILHA";
    static $CD_TEXTO_MARCADO = "CD_TEXTO_MARCADO";
}
?>