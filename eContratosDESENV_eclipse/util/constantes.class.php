<?php
header ('Content-type: text/html; charset=ISO-8859-1');

  Class constantes {	
	
	static $nomeSistema = "e-Contr@tos";
	static $DATA_INICIO = "01/01/1900";
	static $DATA_FIM = "01/01/2099";
	
	static $qts_dias_ALERTA_VERMELHO = 20;
	static $qts_dias_ALERTA_AMARELO = 45;
    
    static $CD_TIPO_CONTRATO  = "C";
    static $CD_TIPO_CONVENIO  = "V";

    static $DS_TIPO_CONTRATO  = "C-SAFI";
    static $DS_TIPO_CONVENIO  = "CV-SAFI";
    
    static $CD_FUNCAO_EXCLUIR = "E";
    static $CD_FUNCAO_INCLUIR = "I";
    static $CD_FUNCAO_ALTERAR = "A";
    static $CD_FUNCAO_DETALHAR = "D";
    
    static $cd_usuario_admin = "administrator";
    static $cd_usuario_colaborador = "contributor";
    static $cd_usuario_visitante = "subscriber";    

    static $DS_SIM  = "SIM";
    static $DS_NAO  = "NAO";
     
    static $CD_SIM  = "S";
    static $CD_NAO  = "N";    
    
    static $TAMANHO_CODIGOS = 5;    	
}
?>