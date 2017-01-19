<?php
/*include_once("mensagens.class.php");
include_once("constantes.class.php");
include_once("bibliotecaDataHora.php");
include_once("dominioPermissaoUsuario.php");
include_once("../../../wp-config.php");*/
include_once("mensagens.class.php");
include_once("constantes.class.php");
include_once("bibliotecaDataHora.php");
include_once("dominioPermissaoUsuario.php");
include_once("dominioQtdObjetosPagina.php");
include_once("radiobutton.php");

include_once(caminho_wordpress. "wp-config.php");

// .................................................................................................................
    
  //Class bibliotecaHTML {
	function inicio() {         
        inicioComValidacaoUsuario(false);
	}
    
    function inicioComValidacaoUsuario($validarPermissaoAcesso) {        
        
        //include_path = ".:/usr/share/pear:/home/SEU_LOGIN_DE_FTP/SEU_DIRETORIO";
        set_include_path(dirname(__FILE__));        
        
        $nomeUsuario = "Visitante";
        $idUsuario = "-1";
        //redireciona o user para o login se n tiver logado
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $nomeUsuario = $current_user->display_name;
            $idUsuario = get_current_user_id();
            
        }else{
            if($validarPermissaoAcesso)
                auth_redirect();        
        }
        
        define('id_user', $idUsuario);
        define('name_user', $nomeUsuario);
        
        define('anoDefault', date('Y')-1);
        define('dtHoje', date('d/m/Y'));
        define('dtHojeSQL', date('Y/m/d'));        
    }
           
    function setTituloPagina($titulo) {
        return setTituloPaginaPorNivel($titulo, null);
    }

    function setCabecalho($titulo) {
        return setCabecalhoPorNivel($titulo, null);
    }
    
    function setTituloPaginaPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina) {        
        $pastaCSS = caminho_css;        
        $pastaCSS = subirNivelPasta($pastaCSS, $qtdNiveisAcimaEmSeEncontraPagina);
        
        if($titulo == null)
            $titulo = constantes::$nomeSistema." :: U N C T";
        
        $codificacaoHTML = "\n<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
        $html = $codificacaoHTML;
        $html.= "\n<TITLE>$titulo</TITLE>";
        $html.= "<LINK href='" . $pastaCSS . "sefaz_pe.css' rel='stylesheet' type='text/css'>";        
        return $html;
    }
    
	function setCabecalhoPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina) {        
        
        $pastaImagens = subirNivelPasta(caminho_imagens, $qtdNiveisAcimaEmSeEncontraPagina);
        $pastaMenu = subirNivelPasta(caminho_menu, $qtdNiveisAcimaEmSeEncontraPagina);

        if($titulo != null)
            $titulo = " - " . $titulo;
        
        $cabecalho =
                "		<TABLE id='table_conteiner' class='conteiner' cellpadding='0' cellspacing='0'>
                        <TBODY>
                                <TR>
                                <TH class=headertabeladados colspan=2>
                                    <img id=imgLogotipoSefaz src='" . $pastaImagens . "marca_sefaz.png' alt='SEFAZ-PE'> SAFI - UNIDADE DE CONTRATOS, "
                                . date('l jS \of F Y')
                                . "
                                </TH>
                                </TR>                                
                                <TR>
                                <TH class=headertabeladados>&nbsp;"
                                . constantes::$nomeSistema
                                . "$titulo<br></TH>
                                <TH class=headertabeladadosalinhadodireita width='1%' nowrap>&nbsp"
                                . utf8_decode(name_user)
                                .",
                                <a class='linkbranco' href='" . $pastaMenu. "index.php' >Menu</a>
                                <a href='" .$pastaMenu . "login.php?funcao=I' ><img  title='Entrar' src='" . $pastaImagens . "botao_home_laranja.gif' width='20' height='20'></a>
                                <a href='" . $pastaMenu. "login.php?funcao=O' ><img  title='Sair' src='" . $pastaImagens . "logout.gif' width='25' height='20'></a>
                                </TH>                                                                                                
                                </TR>
                        </TBODY>
                    </TABLE>";
                    //<a href='javascript:limparFormulario();' ><img  title='Limpar' src='imagens/borracha.jpg' width='20' height='20'></a>
                    //<a href='http:/wordpress' ><img  title='Home' src='imagens/botao_home_laranja.png' width='20' height='20'></a>
                    
        define('cabecalho', $cabecalho);        
	}    
    
	function complementarCharAEsquerda($texto, $char, $qtdfinal) {
		$retorno = $texto;
		if($texto != null && $texto != ""){			
			$tam = strlen("$texto");            
            //echo $tam. "<br>";
			if($tam < $qtdfinal){		
				for($i=1;$i<=($qtdfinal-$tam);$i++){
					$retorno = $char . $retorno	;
                    //echo $retorno."<br>";
				}
			}
		}
		
		return $retorno;
	}
	
	function getData($dataSQL) {
		$retorno = null;

		if ($dataSQL != null){            
            if($dataSQL == "0000-00-00")
                $retorno = mensagens::$msgDataErro;
            else if ($dataSQL != null && $dataSQL != "0000-00-00")
                $retorno = date("d/m/Y", strtotime($dataSQL));            
        }
        
		return $retorno;
	}	

	function getMoeda($valorSQL) {
		$retorno = "";
		if ($valorSQL != null)
			$retorno = number_format($valorSQL, 2, ',', '.');
		return $retorno;
	}
	
	function getOrdemAtributos(){
	 $varAtributos = array(
				"DESC" => "Decrescente",
				"" => "Crescente"
				);
	 return $varAtributos;	
	}
	
	function getAtributosOrdenacaoContrato(){
	 $varAtributos = array(
				"ct_exercicio" => "Ano",
				"ct_numero" => "Numero",
				"ct_tipo" => "Tipo",
				"ct_especie" => "Especie",
                "ct_contratada" => "Contratada",
				"ct_dt_vigencia_inicio" => "Dt.Inicio",
				"ct_dt_vigencia_fim"  => "Dt.Fim",
				"ct_valor_global" => "Vl.Global" 
				);
	 return $varAtributos;	
	}    
    
    function incluirUsuarioDataHoraDetalhamento($voEntidade){
        $USUARIO_BATCH = "IMPORT.PLANILHA";
        $nmusuinclusao = $voEntidade->nmUsuarioInclusao;
        $nmusualteracao = $voEntidade->nmUsuarioUltAlteracao;        
        if($voEntidade->cdUsuarioInclusao == null)
            $nmusuinclusao = $USUARIO_BATCH;
        if($voEntidade->cdUsuarioUltAlteracao == null)
            $nmusualteracao = $USUARIO_BATCH;
                    
        $retorno =
            "<TH class='campoformulario' nowrap>Data Inclusão:</TH>
            <TD class='campoformulario'>
            	<INPUT type='text' 
            	       id='" . voentidade::$nmAtrDhInclusao . "' 
            	       name='".voentidade::$nmAtrDhInclusao."' 
            			value='". getDataHoraSQLComoString($voEntidade->dhInclusao)."'
            			class='camporeadonly' 
            			size='20' 
            			maxlength='10' readonly>
			</TD>
            <TH class='campoformulario' nowrap>Data Ult.Alteração:</TH>
            <TD class='campoformulario'>
            	<INPUT type='text' 
            	       id='".voentidade::$nmAtrDhUltAlteracao."' 
            	       name='".voentidade::$nmAtrDhUltAlteracao."' 
            			value='".getDataHoraSQLComoString($voEntidade->dhUltAlteracao)."'
            			class='camporeadonly' 
            			size='20' 
            			maxlength='10' readonly>
			</TD>
        </TR>        
		<TR>
            <TH class='campoformulario' nowrap>Usuário Inclusão:</TH>
            <TD class='campoformulario'>
            	<INPUT type='text' 
            	       id='".voentidade::$nmAtrCdUsuarioInclusao."' 
            	       name='".voentidade::$nmAtrCdUsuarioInclusao."' 
            			value='".$nmusuinclusao."'            			
            			class='camporeadonly' 
            			size='20' 
            			readonly>
			</TD>
            <TH class='campoformulario' nowrap>Usuário Ult.Alteração:</TH>
            <TD class='campoformulario'>
            	<INPUT type='text' 
            	       id='".voentidade::$nmAtrCdUsuarioUltAlteracao."' 
            	       name='".voentidade::$nmAtrCdUsuarioUltAlteracao."' 
            			value='".$nmusualteracao."'            			
            			class='camporeadonly' 
            			size='20' 
            			readonly>
			</TD>";        
        
        
    //    return utf8_decode($retorno);
        return $retorno;
    }
    
    function getDsEspecie($voContrato){
        $retorno = null;
        $especiesContrato = new dominioEspeciesContrato();
        
        $especie = $voContrato->especie;
        $sqEspecie = $voContrato->sqEspecie;
        $cdEspecie = $voContrato->cdEspecie;        
        if($especie != null || $cdEspecie != null){                               
            if($sqEspecie != null)
                $sqEspecie = $sqEspecie . "º";
                
            if($cdEspecie != null){            
                $retorno = $sqEspecie . " " . $especiesContrato->getDescricao($cdEspecie);                
            }
            else
                $retorno = $especie;                
        }
        return $retorno;
    }
        
    function getBotao($idBotao, $descricao, $classe, $isSubmit, $complementoHTML) {
        $retorno = "";
        $tipo = "button";
        if($isSubmit)
            $tipo = "submit";
            
        if($classe == null)
            $classe = "botaofuncaop";
                        
        $retorno = "<button id='$idBotao' class='$classe' type='$tipo' $complementoHTML>$descricao</button>";
                
        return  $retorno;        
    }
    
    function getBotaoValidacaoAcesso($idBotao, $descricao, $classe, $isSubmit, $complementoHTML) {
        $retorno = getBotao($idBotao, $descricao, $classe, $isSubmit, $complementoHTML);        
        
        if(!temPermissao())
            $retorno = "";
        return  $retorno;        
    }
    
    function getBotaoConfirmar(){
        return getBotaoValidacaoAcesso("bttconfirmar", "Confirmar", "botaofuncaop", true, " accesskey='c'");
    }

    function getBotaoAlterar(){
        return getBotaoValidacaoAcesso("bttalterar", "Alterar", "botaofuncaop", false, "onClick='javascript:alterar();' accesskey='l'");
    }

    function getBotaoExcluir(){
        return getBotaoValidacaoAcesso("bttexcluir", "Excluir", "botaofuncaop", false, "onClick='javascript:excluir();' accesskey='x'");
    }

    function getBotaoIncluir(){
        return getBotaoValidacaoAcesso("bttincluir", "Incluir", "botaofuncaop", false, "onClick='javascript:incluir();' accesskey='n'");
    }

    function temPermissao(){
        $current_user = wp_get_current_user();
        $permissao_user = $current_user->roles;
                
        $dominioPermissaoUsuario = new dominioPermissaoUsuario();
        return $dominioPermissaoUsuario->temPermissao($permissao_user);
    }
    
    function getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, $temHistorico, $cdHistorico){
        $html = "";
                
        $objetosPorPagina = new dominioQtdObjetosPagina();
        $comboQtdRegistros  = new select($objetosPorPagina->colecao);
        $comboOrdem = new select(getOrdemAtributos());          
        
        $arraySimNao = array("S" => "Sim",
                             "N" => "Não");
        $radioHistorico  = new radiobutton($arraySimNao);
        
  		 $html = "<TR>
                    <TH class='campoformulario' width='1%'>Consulta:</TH>
                    <TD class='campoformulario' colspan='3'>";
                    
        if($comboOrdenacao != null){ 
            $html .= "Coluna:"
                    . $comboOrdenacao->getHtmlOpcao("cdAtrOrdenacao","cdAtrOrdenacao", $cdAtrOrdenacao, false)
                    . " Ordem: "
                    . $comboOrdem->getHtmlOpcao("cdOrdenacao","cdOrdenacao", $cdOrdenacao, false);            
        }        
        		
        $html .=    " Num.Registros por página: "
                    . $comboQtdRegistros->getHtmlOpcao("qtdRegistrosPorPag","qtdRegistrosPorPag", $qtdRegistrosPorPag, false);
        
        if($temHistorico)
                $html .=    " &nbsp;Histórico: "
                    . $radioHistorico->getHtmlRadio("cdHistorico","cdHistorico", $cdHistorico, false, false);

        $html .= "&nbsp;<button id='localizar' class='botaoconsulta' type='submit'>Consultar</button></TD>
                    </TR>";
    
        return $html;        
    }
    
    function getSelectGestor(){        
        $dbgestor = new dbgestor();
        $registros = $dbgestor->consultarSelect();
        
        $select = new select($registros);
        
    }
?>