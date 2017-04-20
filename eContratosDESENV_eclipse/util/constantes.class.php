<?php
header ('Content-type: text/html; charset=ISO-8859-1');

  Class constantes {	
	
	static $nomeSistema = "e@CONTi";
	static $DATA_INICIO = "01/01/1900";
	static $DATA_FIM = "01/01/2099";
	
	static $qts_dias_ALERTA_VERMELHO = 20;
	static $qts_dias_ALERTA_AMARELO = 45;
    
    static $CD_TIPO_CONTRATO  = "C";
    static $CD_TIPO_CONVENIO  = "V";
    static $CD_TIPO_PROFISCO  = "P";

    static $DS_TIPO_CONTRATO  = "C-SAFI";
    static $DS_TIPO_CONVENIO  = "CV-SAFI";
    static $DS_TIPO_PROFISCO  = "C-PROFISCO";
    
    static $CD_FUNCAO_EXCLUIR = "E";
    static $CD_FUNCAO_HISTORICO = "H";
    static $CD_FUNCAO_INCLUIR = "I";
    static $CD_FUNCAO_ALTERAR = "A";
    static $CD_FUNCAO_DETALHAR = "D";
    static $CD_FUNCAO_TODAS = "T";
    
    static $DS_SIM  = "SIM";
    static $DS_NAO  = "NAO";
    static $DS_OPCAO_TODOS = "Todos";
     
    static $CD_SIM  = "S";
    static $CD_NAO  = "N"; 
    static $CD_OPCAO_TODOS  = "op_todos";
    static $CD_OPCAO_VAZIO = "op_vazio";
    
    static $CD_ORDEM_CRESCENTE = "ASC";
    static $CD_ORDEM_DECRESCENTE = "DESC";
    
    /*"01" => "Mater",
    "02" => "Apostilamento",
    "03" => "Termo Aditivo",
    "04" => "Termo de Ajuste",
    "05" => "Termo de Cessуo de Uso",
    "06" => "Termo de Rerratificaчуo",
    "07" => "Termo de Cooperaчуo",
    "08" => "Termo de Convalidaчуo",
    "09" => "Termo de Rescisуo Amigсvel",
    "10" => "Termo de Rescisуo Unilateral",
    "11" => "Termo de Rescisуo Encerramento",*/
    
    
    
    static $TAMANHO_CODIGOS = 5;    	
}
?>