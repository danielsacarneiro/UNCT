<?php
include_once (caminho_wordpress . "wp-config.php");
include_once ("mensagens.class.php");
include_once ("constantes.class.php");
include_once ("bibliotecaDataHora.php");
include_once ("dominioPermissaoUsuario.php");
include_once ("dominioSimNao.php");
include_once ("dominioQtdObjetosPagina.php");
include_once ("radiobutton.php");
include_once ("dominioCoresCrescente.php");
include_once ("sessao.php");
include_once (caminho_vos . "vousuario.php");
include_once (caminho_vos . "voUsuarioInfo.php");
require_once (caminho_funcoes . "contrato/dominioTipoContrato.php");
require_once (caminho_funcoes . "usuario_info/biblioteca_htmlUsuario.php");

// .................................................................................................................

// Class bibliotecaHTML {
function listarObjetoSessaoPorString($param) {
	$arraychave = array_keys($_SESSION);
	$tam = sizeof($_SESSION);
	
	for($i=0; $i<$tam;$i++){
		$chave = $arraychave[$i];
		
		if(existeStr1NaStr2($param, $chave, true)){
			echoo($chave);
		}
	}	
}

function inicio($validarRedirecionamentoEmCasoDeErro = true) {		
	inicioComValidacaoUsuario ( false, $validarRedirecionamentoEmCasoDeErro);		
}

function inicioComValidacaoUsuario($validarPermissaoAcesso, $validarRedirecionamentoEmCasoDeErro = true) {
	session_start ();
	
	if($validarRedirecionamentoEmCasoDeErro 
			&& !isUsuarioAdmin() 
			&& excecaoSistemaEmManutencao::$FLAG_MANUTENCAO){		
		$ex = new excecaoSistemaEmManutencao();
		$pagina = url_sistema . "funcoes/mensagemErro.php";
		tratarExcecaoHTML($ex, null, $pagina);
		//header ( "Location: $pagina?texto=" . $msg);
	}	
	
	// include_path = ".:/usr/share/pear:/home/SEU_LOGIN_DE_FTP/SEU_DIRETORIO";
	set_include_path ( dirname ( __FILE__ ) );
	
	$nomeUsuario = "Visitante";
	$idUsuario = "-1";
	// redireciona o user para o login se n tiver logado
	if (is_user_logged_in ()) {
		$current_user = wp_get_current_user ();
		$nomeUsuario = $current_user->display_name;
		$idUsuario = get_current_user_id ();
		//wp_redirect('econti.localhost');
	} else {
		if ($validarPermissaoAcesso) {
			auth_redirect ();
		}
	}
	
	define ( 'id_user', $idUsuario );
	//echo $idUsuario;
	define ( 'name_user', utf8_decode($nomeUsuario));
	
	define ( 'anoDefault', date ( 'Y' ) );
	define ( 'dtHoje', getDataHoje () );
	define ( 'dtHojeSQL', date ( 'Y/m/d' ) );	
}
function setTituloPagina($titulo) {
	return setTituloPaginaPorNivel ( $titulo, null );
}
function setCabecalho($titulo) {
	return setCabecalhoPorNivel ( $titulo, null );
}
function setCabecalhoEmail($titulo, $mail) {
	return setCabecalhoPorNivel ( $titulo, null , $mail);
}
function setTituloPaginaPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina) {
	$pastaCSS = caminho_css;
	$pastaCSS = subirNivelPasta ( $pastaCSS, $qtdNiveisAcimaEmSeEncontraPagina );
	
	$pastaJS = caminho_js;
	$pastaJS = subirNivelPasta ( $pastaJS, $qtdNiveisAcimaEmSeEncontraPagina );
	
	if ($titulo == null) {
		$titulo = constantes::$nomeSistema . " :: U N C T";
	} else {
		$titulo = constantes::$nomeSistema . " : $titulo";
	}
	
	$codificacaoHTML = "\n<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
	$html = $codificacaoHTML;
	$html .= "\n<TITLE>$titulo</TITLE>";
	$html .= "\n<LINK href='" . $pastaCSS . "sefaz_pe.css' rel='stylesheet' type='text/css'>\n";
	$html .= "\n<SCRIPT language='JavaScript' type='text/javascript' src='" . $pastaJS . "mensagens_globais.js'></SCRIPT>\n";
	
	return $html;
}
function getPastaImagens() {
	return getPastaImagensPorNivel ( null );
}
function getPastaImagensPorNivel($qtdNiveisAcimaEmSeEncontraPagina) {
	$pastaImagens = subirNivelPasta ( caminho_imagens, $qtdNiveisAcimaEmSeEncontraPagina );
	return $pastaImagens;
}
function setCabecalhoPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina, $mail = null) {
	// se precisar fazer o mesmo para pasta menu
	$pastaImagens = getPastaImagensPorNivel ( $qtdNiveisAcimaEmSeEncontraPagina );
	$pastaMenu = subirNivelPasta ( caminho_menu, $qtdNiveisAcimaEmSeEncontraPagina );
	$nmServidor = constantes::$NM_SERVIDOR;
	
	define ( 'pasta_imagens', $pastaImagens );
	if ($titulo != null) {
		$titulo = " - " . $titulo;
	}
	
	date_default_timezone_set ( 'America/Recife' );
	setlocale ( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
	/*
	 * 	//descomentar a linha extension=php_intl.dll no php.ini
	 * $data = new DateTime();
	 * $formatter = new IntlDateFormatter('pt_BR',
	 * IntlDateFormatter::FULL,
	 * IntlDateFormatter::GREGORIAN);
	 * //$diaExtenso = $formatter->format($data);
	 */
	
	$hour = date ( "H" );
	$minute = date ( "i" );
	$diaExtenso = strftime ( '%A, %d de %B de %Y', strtotime ( 'today' ) ) . ", " . $hour . ":" . $minute;
	
	$ehMontagemPorEmail = $mail != null; 
	if(!$ehMontagemPorEmail){
		$imagemEntrar = "<img  title='Entrar' src='" . $pastaImagens . "botao_home_laranja.gif' width='20' height='20'>";
		$imagemSair = "<img  title='Sair' src='" . $pastaImagens . "logout.gif' width='25' height='20'>";
		$imagemWordPress = "<img  title='WORDPRESS' src='" . $pastaImagens . "w-logo-white.png' width='25' height='20'>";
		$imagemSefaz = "<img id=imgLogotipoSefaz src='" . $pastaImagens . "marca_sefaz.png' alt='SEFAZ-PE'>";
	}else{
		/*$imagemEntrar = "<img  title='Entrar' src='" . $pastaImagens . "botao_home_laranja.gif' width='20' height='20'>";
		$imagemSair = "<img  title='Sair' src='" . $pastaImagens . "logout.gif' width='25' height='20'>";
		$imagemWordPress = "<img  title='WORDPRESS' src='" . $pastaImagens . "w-logo-white.png' width='25' height='20'>";*/		
		$imagemSefaz = "<img src='cid:".email_sefaz::$CD_IMAGEM_SEFAZLOGO."'>";		
		;
	}
	
	if(!isLupa()){
		$linkmenu = ", <a class='linkbranco' href='" . $pastaMenu . "index.php' >Menu</a> ";
		$linkmenu .= "<a href='" . $pastaMenu . "login.php?funcao=I' >$imagemEntrar</a>
		<a href='" . $pastaMenu . "login.php?funcao=O' >$imagemSair</a>";
		
		if (isUsuarioAdmin ()) {
			$linkmenu .= "<a href='http://$nmServidor/wordpress/wp-admin/' target='_blank'>$imagemWordPress</a>";
		}
		
	}
	
	$nmSetor = "<br>SAFI/DILC-DIRETORIA DE LICITAÇÕES E CONTRATOS";
	
	if(!$ehMontagemPorEmail){
		$colspan = "colspan=2";
	}
	
	$cabecalho = "		<TABLE id='table_conteiner' class='conteiner' cellpadding='0' cellspacing='0'>
                        <TBODY>
                                <TR>
                                <TH class=headertabeladados $colspan>
                                    $imagemSefaz $nmSetor, " . $diaExtenso . "
                                </TH>
                                </TR>                                
                                <TR>
                                <TH class=headertabeladados>&nbsp;" . constantes::$nomeSistema . "$titulo<br></TH>";
                                
	if(!$ehMontagemPorEmail){
		$cabecalho .= "<TH class=headertabeladadosalinhadodireita width='1%' nowrap>&nbsp" . name_user . "$linkmenu" . "</TH>";
	}
	
	$cabecalho .= "\n
                                </TR>
                        </TBODY>
                    </TABLE>";
	// <a href='javascript:limparFormulario();' ><img title='Limpar' src='imagens/borracha.jpg' width='20' height='20'></a>
	// <a href='http:/wordpress' ><img title='Home' src='imagens/botao_home_laranja.png' width='20' height='20'></a>
	
	define ( 'cabecalho', $cabecalho );
	//retorna para a funcao setCabecalhoEmail
	return $cabecalho;
}
function complementarCharAEsquerda($texto, $char, $qtdfinal) {
	$retorno = $texto;
	if ($texto != null && $texto != "") {
		$tam = strlen ( "$texto" );
		// echo $tam. "<br>";
		if ($tam < $qtdfinal) {
			for($i = 1; $i <= ($qtdfinal - $tam); $i ++) {
				$retorno = $char . $retorno;
				// echo $retorno."<br>";
			}
		}
	}
	
	return $retorno;
}
function getVarComoStringHTML($string) {
	$retorno = "";
	if ($string != null){
		$retorno = str_ireplace("\"", "'", $string);
	}
	return $retorno;
}
function getMoeda($valorSQL, $qtCasasDecimais=2) {
	$retorno = "";

	if ($valorSQL == 0){
		$retorno = "0,".complementarCharAEsquerda("0", "0", $qtCasasDecimais);
	}else if ($valorSQL != null){
		$retorno = number_format ( $valorSQL, $qtCasasDecimais, ',', '.' );
	}
	return $retorno;
}
function getOrdemAtributos() {
	$varAtributos = array (
			constantes::$CD_ORDEM_CRESCENTE => "Crescente",
			constantes::$CD_ORDEM_DECRESCENTE => "Decrescente" 
	);
	return $varAtributos;
}
function incluirUsuarioDataHoraDetalhamento($voEntidade) {
	$USUARIO_BATCH = constantes::$NM_USUARIO_BATCH;
	$nmusuinclusao = $voEntidade->nmUsuarioInclusao;
	$nmusualteracao = $voEntidade->nmUsuarioUltAlteracao;
	
	if ($voEntidade->cdUsuarioInclusao == null)
		$nmusuinclusao = $USUARIO_BATCH;
	if ($voEntidade->cdUsuarioUltAlteracao == null)
		$nmusualteracao = $USUARIO_BATCH;
	
	$retorno = "";
	if ($voEntidade->dhInclusao != null) {
		$retorno .= "<TR>
		            <TH class='campoformulario' nowrap>Data Inclusão:</TH>
		            <TD class='campoformulario' width='1%'>
		            	<INPUT type='text' 
		            	       id='" . voentidade::$nmAtrDhInclusao . "' 
		            	       name='" . voentidade::$nmAtrDhInclusao . "' 
		            			value='" . getDataHoraSQLComoString ( $voEntidade->dhInclusao ) . "'
		            			class='camporeadonly' 
		            			size='20' 
		            			maxlength='10' readonly>
					</TD>
		            <TH class='campoformulario' width='1%' nowrap>Usuário Inclusão:</TH>
		            <TD class='campoformulario'>
		            	<INPUT type='text' 
		            	       id='" . voentidade::$nmAtrCdUsuarioInclusao . "' 
		            	       name='" . voentidade::$nmAtrCdUsuarioInclusao . "' 
		            			value='" . $nmusuinclusao . "'            			
		            			class='camporeadonly' 
		            			size='20' 
		            			readonly>
					</TD>            					
		        </TR>";
	}
	
	if ($voEntidade->dhUltAlteracao != null) {
		$retorno .= "<TR>
	            <TH class='campoformulario' nowrap>Data Ult.Alteração:</TH>
	            <TD class='campoformulario' width='1%'>
	            	<INPUT type='text'	            	        
	            			value='" . getDataHoraSQLComoString ( $voEntidade->dhUltAlteracao ) . "'
	            			class='camporeadonly' 
	            			size='20' 
	            			maxlength='10' readonly>
	            	<INPUT type='hidden'
	            	       id='" . voentidade::$nmAtrDhUltAlteracao . "' 
	            	       name='" . voentidade::$nmAtrDhUltAlteracao . "' 
	            			value='" . $voEntidade->dhUltAlteracao . "'>
	            					
				</TD>
	            <TH class='campoformulario' nowrap width='1%'>Usuário Ult.Alteração:</TH>
	            <TD class='campoformulario'>
	            	<INPUT type='text' 
	            	       id='" . voentidade::$nmAtrCdUsuarioUltAlteracao . "' 
	            	       name='" . voentidade::$nmAtrCdUsuarioUltAlteracao . "' 
	            			value='" . $nmusualteracao . "'            			
	            			class='camporeadonly' 
	            			size='20' 
	            			readonly>
				</TD>
			</TR>";
	}
	
	if ($voEntidade->sqHist != null) {
		$nmusuHistorico = $voEntidade->nmUsuarioOperacao;
		
		$retorno .= "\n<TR>\n" . "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>" . "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Dados da Operação</DIV>" . "</TH>" . "</TR>";
		
		$retorno .= "<TR>
		            <TH class='campoformulario' nowrap>Data:</TH>
		            <TD class='campoformulario'>
		            	<INPUT type='text'
		            	       id='" . voentidade::$nmAtrDhOperacao . "'
		            	       name='" . voentidade::$nmAtrDhOperacao . "'
		            			value='" . getDataHoraSQLComoString ( $voEntidade->dhOperacao ) . "'
		            			class='camporeadonly'
		            			size='20'
		            			maxlength='10' readonly>
					</TD>
		            <TH class='campoformulario' nowrap>Usuário:</TH>
		            <TD class='campoformulario'>
		            	<INPUT type='text'
		            	       id='" . voentidade::$nmAtrCdUsuarioOperacao . "'
		            	       name='" . voentidade::$nmAtrCdUsuarioOperacao . "'
		            			value='" . $nmusuHistorico . "'
		            			class='camporeadonly'
		            			size='20'
		            			readonly>
					</TD>
				</TR>";
	}
	
	// return utf8_decode($retorno);
	return $retorno;
}
function getDsEspecie($voContrato, $porExtenso = true, $omitirEspecieMater=false, $apenasMaterPorExtenso=false) {
	$retorno = null;
	
	$cdEspecie = $voContrato->cdEspecie;
	$especie = $voContrato->especie;
	//so pega o sq especie para termo diferente do MATER
	if ($cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER) {
		$sqEspecie = $voContrato->sqEspecie;
	}
	
	$isEspecieMater = $cdEspecie == dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER;
	$omitirEspecieMater = $isEspecieMater && $omitirEspecieMater;
	$apenasMaterPorExtenso = $isEspecieMater && $apenasMaterPorExtenso;
	
	if ($especie != null || $cdEspecie != null) {
		
		if ($sqEspecie != null)
			$sqEspecie = $sqEspecie . "º";
		
		if ($cdEspecie != null) {
			//$dsEspecie = !$porExtenso?$cdEspecie:dominioEspeciesContrato::getDescricao ( $cdEspecie );			
			if(!$porExtenso){
				if($apenasMaterPorExtenso){
					$cdEspecie = vocontrato::$DS_ESPECIE_MATER;
				}
				
				if(!$omitirEspecieMater){
					$dsEspecie = $cdEspecie;
				}
			}else{
				$dsEspecie = dominioEspeciesContrato::getDescricao ( $cdEspecie );
			}
			
			$retorno = $sqEspecie . $dsEspecie;
		} else
			$retorno = $especie;
	}
	return $retorno;
}
function getBotao($idBotao, $descricao, $classe, $isSubmit, $complementoHTML) {
	$retorno = "";
	$tipo = "button";
	if ($isSubmit)
		$tipo = "submit";
	
	if ($classe == null)
		$classe = "botaofuncaop";
	
	$retorno = "<button id='$idBotao' class='$classe' type='$tipo' $complementoHTML>$descricao</button>";
	
	return $retorno;
}
function getCdLupa() {
	// vem do linkPesquisa ou do campo formulario
	$lupa = @$_GET [constantes::$ID_REQ_CD_LUPA];
	if ($lupa == null || $lupa == "") {
		$lupa = @$_POST [constantes::$ID_REQ_CD_LUPA];
	}

	if ($lupa == null){
		$lupa = "N";
	}
	
	//echoo("eh lupa?" . $lupa);

	return $lupa;
}
function isMultiSelecao() {
	// vem do linkPesquisa ou do campo formulario
	$flag = @$_GET [constantes::$ID_REQ_MULTISELECAO];
	if ($flag == null || $flag == "") {
		$flag = @$_POST [constantes::$ID_REQ_MULTISELECAO];
	}
	return $flag == "S";
}
function isLupa() {
	$lupa = getCdLupa();
	return $lupa == "S";
}
function getBotaoValidacaoAcesso($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML) {
	return getBotaoGeral ( $idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML, "" );
}
function getBotaoPorFuncao($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $complementoHTML, $cdFuncaoBotao) {
	return getBotaoGeral ( $idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, false, $complementoHTML, $cdFuncaoBotao );
}
function getBotaoGeral($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML, $cdFuncaoBotao) {
	$retorno = getBotao ( $idBotao, $descricao, $classe, $isSubmit, $complementoHTML );
	
	$isLupa = isLupa ();
	
	if ($isLupa) {
		//echo "EH LUPA";
		if (! $imprimirNaLupa){
			$retorno = "";
			//echo "NAO IMPRIME CANCELAR";
		}
	} else {
		// echo "NAO EH LUPA";
		if ($imprimirNaManutencao) {
			if (! temPermissao ( $cdFuncaoBotao ) && ! $todosTemAcesso)
				$retorno = "";
		} else
			$retorno = "";
	}
	
	return $retorno;
}
function getBotaoConfirmar() {
	return getBotaoValidacaoAcesso ( "bttconfirmar", "Confirmar", "botaofuncaop", true, false, true, false, " accesskey='c'" );
}
function getBotaoCancelar() {
	return getBotaoValidacaoAcesso ( "bttcancelar", "Cancelar", "botaofuncaop", false, true, true, true, "onClick='javascript:cancelar();' accesskey='r'" );
}
function getBotaoAlterar() {
	return getBotaoPorFuncao ( "bttalterar", "Alterar", "botaofuncaop", false, false, true, "onClick='javascript:alterar();' accesskey='l'", constantes::$CD_FUNCAO_ALTERAR );
}
function getBotaoExcluir() {
	return getBotaoPorFuncao ( "bttexcluir", "Excluir", "botaofuncaop", false, false, true, "onClick='javascript:excluir();' accesskey='x'", constantes::$CD_FUNCAO_EXCLUIR );
}
function getBotaoIncluir() {
	// return getBotaoValidacaoAcesso ( "bttincluir", "Incluir", "botaofuncaop", false, false, true, false, "onClick='javascript:incluir();' accesskey='n'" );
	return getBotaoPorFuncao ( "bttincluir", "Incluir", "botaofuncaop", false, false, true, "onClick='javascript:incluir();' accesskey='n'", constantes::$CD_FUNCAO_INCLUIR );
}
function getBotaoDetalhar($javaScriptComplementar = null) {
	return getBotaoValidacaoAcesso ( "bttdetalhar", "Detalhar", "botaofuncaop", false, true, true, true, "$javaScriptComplementar onClick='javascript:detalhar(false);' accesskey='d'" );
}
function getBotaoSelecionar() {
	return getBotaoValidacaoAcesso ( "bttselecionar", "Selecionar", "botaofuncaop", false, true, false, true, "onClick='javascript:selecionar();' accesskey='s'" );
}
function getBotaoFechar() {
	return getBotaoValidacaoAcesso ( "bttfechar", "Fechar", "botaofuncaop", false, true, false, true, "onClick='javascript:window.close();' accesskey='f'" );
}
function getRodape() {
	$isLupa = isLupa ();
	// aqui guarda a informacao de ser o contexto de LUPA, quando ocultara alguns botoes
	$lupa = "N";
	if ($isLupa)
		$lupa = "S";
	
	$nmobjeto = constantes::$ID_REQ_CD_LUPA;
	
	$retorno = "<INPUT type='hidden' name='$nmobjeto' id='$nmobjeto' value='" . $lupa . "'>\n";
	return $retorno;
}
function getBotoesRodape() {
	return getBotoesRodapeComRestricao ( null );
}
function exibeBotao($arrayBotoesARemover, $nmFuncaoBotao, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao) {
	return ! existeItemNoArray ( $nmFuncaoBotao, $arrayBotoesARemover ) || ($usuarioLogadoTemPermissao && ! $restringeBotaoSemValidarPermissao);
}
function getBotoesRodapeManter() {
	// o administrador pode ver todos os botoes
	//$usuarioLogadoTemPermissao = dominioPermissaoUsuario::isAdministrador ( getColecaoPermissaoUsuarioLogado () );
	$usuarioLogadoTemPermissao = dominioPermissaoUsuario::isUsuarioPermissaoIntermediaria();
	$isManutencao = true;
	$isDetalhamento = false;
	// considera que qq funcao chamado que nao sejam as funcoes basicas (alterar, excluir, incluir...) caira nessa opcao
	// marreta: verifica pelo tamanho do nome da funcao
	// um exemplo eh o metodo encaminhar chamado no encaminhamento de demanda (voDemandaTramitacao)
	$isMetodoChamadoEspecifico = strlen ( $funcao ) > 2;

	$html = "";
	if (getBotaoConfirmar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoConfirmar () . "</TD>\n";	

	 if (getBotaoCancelar () != "" && ($isDetalhamento || $isManutencao))
	 	$html .= "<TD class='botaofuncao'>" . getBotaoCancelar () . "</TD>\n";

	$html .= getRodape ();

	return $html;
}

function getBotoesRodapeComRestricao($arrayBotoesARemover, $restringeBotaoSemValidarPermissao = false) {	
	// o administrador pode ver todos os botoes
	//$usuarioLogadoTemPermissao = dominioPermissaoUsuario::isAdministrador ( getColecaoPermissaoUsuarioLogado () );
	$usuarioLogadoTemPermissao = dominioPermissaoUsuario::isUsuarioPermissaoIntermediaria();
	
	// falta fazer para os outros botoes
	$temIncluir = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_INCLUIR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	$temAlterar = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_ALTERAR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	$temExcluir = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_EXCLUIR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	$temSelecionar = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_SELECIONAR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	
	$isManutencao = false;
	$isDetalhamento = false;
	$funcao = @$_GET ["funcao"]; 
	
	// considera que qq funcao chamado que nao sejam as funcoes basicas (alterar, excluir, incluir...) caira nessa opcao
	// marreta: verifica pelo tamanho do nome da funcao
	// um exemplo eh o metodo encaminhar chamado no encaminhamento de demanda (voDemandaTramitacao)
	$isMetodoChamadoEspecifico = strlen ( $funcao ) > 2;
	
	if ($funcao == constantes::$CD_FUNCAO_DETALHAR) {
		$isDetalhamento = true;
	} else if ($funcao == constantes::$CD_FUNCAO_EXCLUIR || $funcao == constantes::$CD_FUNCAO_INCLUIR || $funcao == constantes::$CD_FUNCAO_ALTERAR || $isMetodoChamadoEspecifico) {
		
		$isManutencao = true;
	}
	
	$html = "";
	
	if (! $isManutencao && ! $isDetalhamento && getBotaoDetalhar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoDetalhar () . "</TD>\n";
	
	if (!$isDetalhamento && getBotaoSelecionar () != "" && $temSelecionar)
		$html .= "<TD class='botaofuncao'>" . getBotaoSelecionar () . "</TD>\n";
	
	if (! $isManutencao) {
		if (! $isDetalhamento) {
			if (getBotaoIncluir () != "" && $temIncluir)
				$html .= "<TD class='botaofuncao'>" . getBotaoIncluir () . "</TD>\n";
			if (getBotaoAlterar () != "" && $temAlterar)
				$html .= "<TD class='botaofuncao'>" . getBotaoAlterar () . "</TD>\n";
			if (getBotaoExcluir () != "" && $temExcluir)
				$html .= "<TD class='botaofuncao'>" . getBotaoExcluir () . "</TD>\n";
		}
	} else {
		if (getBotaoConfirmar () != "")
			$html .= "<TD class='botaofuncao'>" . getBotaoConfirmar () . "</TD>\n";
	}
		
	//if (getBotaoCancelar () != "" && (($isDetalhamento && VEMDETELADECONSULTA) || $isManutencao))
	
	/*$paginaAnterior = $_SERVER['HTTP_REFERER'];	
	$isPaginaAnteriorDetalhamento = mb_stripos ( $paginaAnterior, "detalhar" );	
	$mostrarCancelamento = !isLupa() || $isPaginaAnteriorDetalhamento === false;*/
	
	/*if($isPaginaAnteriorDeConsulta)
		echo "eh de consulta";
	else
	echo "NAO eh de consulta";*/
		
	if (getBotaoCancelar () != "" && !isLupa()  && ($isDetalhamento || $isManutencao))
		$html .= "<TD class='botaofuncao'>" . getBotaoCancelar () . "</TD>\n";
	
	if (getBotaoFechar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoFechar () . "</TD>\n";
	
	$html .= getRodape ();
	
	return $html;
}
function getLinkPesquisa($link) {
	return getImagemLink ( "javascript:abrirJanelaAuxiliar('" . $link . "',true, false, false);\" ", "lupa.png" );
}
function getImagemLink($href, $nmImagem, $nmLink='lnkFramework') {
	
	// $pasta = pasta_imagens . "//";
	$pasta = getPastaImagens () . "//";
	
	$html = "<A id='$nmLink' name='$nmLink' " . "href=\"" . $href . "\"" . " class='linkNormal'>" . "<img src='" . $pasta . $nmImagem . "'  width='22' height='22' border='0'></A>";
	
	// echo $pasta;
	
	return $html;
}
function getTextoLink($texto, $href, $javascript=null, $isJanelaAuxiliar=false, $isAbrirOutraJanela=false) {
	if($isJanelaAuxiliar){
		$href = "javascript:abrirJanelaAuxiliar('" . $href . "',true, false, false);\" ";
	}
	
	if($isAbrirOutraJanela){
		$novaJanela = " target='_blank' ";
	}
	
	$html = "<A id='lnkFramework$texto' name='lnkFramework$texto' " . "href=\"" . $href . "\"" . " $novaJanela class='linkNormal' $javascript>$texto</A>";
	return $html;
}

function getColecaoPermissaoUsuarioLogado() {
	$current_user = wp_get_current_user ();
	$permissao_user = $current_user->roles;
	
	return $permissao_user;
}
function getEmailUsuarioLogado() {
	$current_user = wp_get_current_user ();
	$retorno = $current_user->user_email;

	return $retorno;
}
function getIdUsuarioLogado() {
	return get_current_user_id ();
}

function temPermissao($cdFuncaoBotao) {
	return temPermissaoPorFuncao ( $cdFuncaoBotao, false );
}
function isUsuarioAdmin() {
	return dominioPermissaoUsuario::isAdministrador ( getColecaoPermissaoUsuarioLogado () );
}
function isUsuarioRodaImportacao() {
	return isUsuarioAdmin() || getEmailUsuarioLogado() == 'eduardo.s-goncalves@sefaz.pe.gov.br';
}

function isUsuarioPermissaoIntermediaria() {
	return dominioPermissaoUsuario::isUsuarioPermissaoIntermediaria();
}
function temPermissaoParamHistorico($isHistorico) {
	return temPermissaoPorFuncao ( constantes::$CD_FUNCAO_HISTORICO, $isHistorico );
}
function temPermissaoPorFuncao($cdFuncaoBotao, $isHistorico) {	
	
	$permissao_user = getColecaoPermissaoUsuarioLogado();	
	$retorno = true;
	if ($isHistorico) {
		$retorno = dominioPermissaoUsuario::temPermissaoPorFuncao ( constantes::$CD_FUNCAO_HISTORICO, $permissao_user );
	} else {
		// $retorno= dominioPermissaoUsuario::temPermissao($permissao_user);
		$retorno = dominioPermissaoUsuario::temPermissaoPorFuncao ( $cdFuncaoBotao, $permissao_user );
	}
	return $retorno;
}

function getCaracteristicasUsuarioLogado() {

	$idusuario = getIdUsuarioLogado();
	$vousuarioinfo = new voUsuarioInfo();
	$vousuarioinfo->id = $idusuario;
	$dbusuario = new dbUsuarioInfo();
	$vousuarioinfo = $dbusuario->consultarPorChaveVO($vousuarioinfo);
	
	//$vousuarioinfo = new voUsuarioInfo();
	$retorno = $vousuarioinfo->inCaracteristicas;
	if($retorno == null){
		$retorno = constantes::$CD_OPCAO_NENHUM;
	}
	
	return $retorno;	
}

function getComboColecaoGenerico($colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml) {
	$select = new select ( $colecao );
	$retorno = $select->getHtmlCombo ( $idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml );
	
	return $retorno;
}
function getComponenteConsultaFiltro($temHistorico, $filtro, $comComboOrdenacao=true, $colecaoExportarExcel=null) {
	// $comboOrdenacao = $filtro->getAtributosOrdenacao();
	if($comComboOrdenacao){		
			$comboOrdenacao = $filtro->getComboOrdenacao ();
	}
	
	/*$arrayParam = array(
		$comboOrdenacao,
		$filtro->cdAtrOrdenacao,
		$filtro->cdOrdenacao,
		$filtro->TemPaginacao,
		$filtro->qtdRegistrosPorPag,
		$temHistorico,
		$filtro->cdHistorico,
		$colecaoExportarExcel,
	);*/
	
	$arrayParam = array(
			$filtro,
			$temHistorico,
			$comComboOrdenacao,
			$colecaoExportarExcel,
			null,
	);
	
	return getComponenteConsultaPaginacaoArray($arrayParam);
}

/*function getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, $temHistorico, $cdHistorico) {
	return getComponenteConsultaPaginacao ( $comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, true, $qtdRegistrosPorPag, $temHistorico, $cdHistorico );
}


 function getComponenteConsultaPaginacao($comboOrdenacao, 
		$cdAtrOrdenacao, 
		$cdOrdenacao, 
		$temPaginacao, 
		$qtdRegistrosPorPag, 
		$temHistorico, 
		$cdHistorico) {
	
	$arrayParam = array(
			$comboOrdenacao,
			$cdAtrOrdenacao,
			$cdOrdenacao,
			$temPaginacao,
			$qtdRegistrosPorPag,
			$temHistorico,
			$cdHistorico					
	);
	
	return getComponenteConsultaPaginacaoArray($arrayParam);
	
}*/

/*function getComponenteConsultaPaginacaoArray($arrayParam) {
	$comboOrdenacao = $arrayParam[0];
	$cdAtrOrdenacao = $arrayParam[1];
	$cdOrdenacao = $arrayParam[2];
	$temPaginacao = $arrayParam[3];
	$qtdRegistrosPorPag = $arrayParam[4];
	$temHistorico = $arrayParam[5];
	$cdHistorico = $arrayParam[6];
	$colecaoExportarExcel = $arrayParam[7];
	$complementoHTML = $arrayParam[8];
	
	return getComponenteConsultaPaginacaoArrayFiltro($arrayParam);

}*/

function getComponenteConsultaPaginacaoArray($arrayParam) {
	
	/*$comboOrdenacao = $arrayParam[0];
	 $cdAtrOrdenacao = $arrayParam[1];
	 $cdOrdenacao = $arrayParam[2];
	 $temPaginacao = $arrayParam[3];
	 $qtdRegistrosPorPag = $arrayParam[4];
	 $temHistorico = $arrayParam[5];
	 $cdHistorico = $arrayParam[6];
	 $colecaoExportarExcel = $arrayParam[7];
	 $complementoHTML = $arrayParam[8];*/
		
	$filtro = $arrayParam[0];
	$temHistorico = $arrayParam[1];
	$comComboOrdenacao = $arrayParam[2];
	$complementoHTML = $arrayParam[3];
	
	if($comComboOrdenacao){
		$comboOrdenacao = $filtro->getComboOrdenacao();
	}
	$cdAtrOrdenacao = $filtro->cdAtrOrdenacao;
	$cdOrdenacao = $filtro->cdOrdenacao;
	$temPaginacao = $filtro->TemPaginacao;
	$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
	$cdHistorico = $filtro->cdHistorico;	
	
	$html = "";
	
	$objetosPorPagina = new dominioQtdObjetosPagina ();
	$comboQtdRegistros = new select ( $objetosPorPagina->colecao );
	$comboOrdem = new select ( getOrdemAtributos () );
	$radioHistorico = new radiobutton ( dominioSimNao::getColecao () );
	
	if ($cdHistorico == null) {
		$cdHistorico = constantes::$CD_NAO;
	}
	
	$html = "<TR>
                    <TH class='campoformulario' width='1%'>Consulta:</TH>
                    <TD class='campoformulario' valign='bottom' colspan='3' width='90%' nowrap>\n";
	
	// var_dump($comboOrdenacao);
	if ($comboOrdenacao != null && $comboOrdenacao != "") {
		// echo $cdOrdenacao;
		$html .= "Coluna:" . $comboOrdenacao->getHtmlOpcao ( "cdAtrOrdenacao", "cdAtrOrdenacao", $cdAtrOrdenacao, false ) . " Ordem: " . $comboOrdem->getHtmlOpcao ( "cdOrdenacao", "cdOrdenacao", $cdOrdenacao, false );
	}
	
	if ($temPaginacao) {
		$html .= " Num.Registros por página: " . $comboQtdRegistros->getHtmlOpcao ( "qtdRegistrosPorPag", "qtdRegistrosPorPag", $qtdRegistrosPorPag, false );
	}
	
	if ($temHistorico)
		$html .= " &nbsp;Histórico: " . $radioHistorico->getHtmlRadio ( "cdHistorico", "cdHistorico", $cdHistorico, false, false );
	
	$html .= "&nbsp;<button id='localizar' class='botaoconsulta' type='submit'>Consultar</button>\n";
	$html .= "&nbsp;".getBorrachaTotalConsulta();
	
	if($filtro->isFiltroPlanilhaExportar()){
		//quando eh somente o nome do filtro exportar
		//$filtro = new filtroConsultarContratoConsolidacao();
		$nmFiltroExportar = $filtro->nmFiltro;
		putObjetoSessao($nmFiltroExportar, $filtro);
		$nmFiltroExportar = filtroManter::$ID_REQ_NmFiltroExportarPlanilha . "=$nmFiltroExportar";		
		$html .= "&nbsp;". getTextoLink("exportarPlanilha", "../exportarPlanilha.php?$nmFiltroExportar", "", true);
	}
	
	$html .= "$complementoHTML</TD></TR>";
	// $html .= "<imput type='hidden' id='javascript:ID_REQ_DT_HOJE' name='javascript:ID_REQ_DT_HOJE'>\n";
	
	return $html;
}

function getBorrachaTotalConsulta() {
	$html = "&nbsp;&nbsp;<a href='javascript:limparFormularioGeral();' ><img  title='Limpar' src='" . caminho_imagens . "borracha.jpg' width='20' height='20'></a>\n";
	return $html;
}

/**
 * 
 * @param unknown $nmCampos
 * @return string
 */
function getBorracha($nmCampos, $jsComplementar=null) {
	$tam = count ( $nmCampos );

	$js = "";
	for($i = 0; $i < $tam; $i ++) {
		$nmCampoAtual = $nmCampos [$i];
		//$js .= "try{document.frm_principal." . $nmCampoAtual . ".value='';}catch(ex){;}";
		//$js .= "try{var campos = document.getElementsByName('$nmCampoAtual');[0].value='';}catch(ex){;}";
		$js .= "try{var campos = document.getElementsByName('$nmCampoAtual');if(campos.length == 1){campos[0].value=''}else{limparCamposColecaoDeCamposFormulario(campos)};}catch(ex){;}";		
	}
	if($jsComplementar !=null){
		$js .= $jsComplementar;
	}
	
	$html = "&nbsp;&nbsp;<a onClick=\"javascript:" . $js . "\" ><img  title='Limpar' src='" . caminho_imagens . "borracha.jpg' width='15' height='15' A style='CURSOR: POINTER'></a>\n";

	return $html;
}

function getHTMLRadioButtonConsulta($nmRadio, $idRadio, $voAtualOuChaveString, $comVerificacaoObjetoSessao = true) {
	return getHTMLGridConsulta ( $nmRadio, $idRadio, $voAtualOuChaveString, false, $comVerificacaoObjetoSessao);
}
function getHTMLCheckBoxConsulta($nmRadio, $idRadio, $voAtualOuChaveString) {
	return getHTMLGridConsulta ( $nmRadio, $idRadio, $voAtualOuChaveString, true );
}
function getHTMLGridConsulta($nmRadio, $idRadio, $voAtualOuChaveString, $isCheckBox, $comVerificacaoObjetoSessao = true) {
	$isSelecionado = false;

	$ID = "";
	if ($voAtualOuChaveString instanceof voentidade) {
		$ID = $voAtualOuChaveString->getNmTabela ();
		
		if($comVerificacaoObjetoSessao){
			$voSessao = getObjetoSessao ( $ID );
			$isSelecionado = $voAtualOuChaveString->isIgualChavePrimaria ( $voSessao );
		}		
		$chave = $voAtualOuChaveString->getValorChaveHTML ();
	} else {
		// voAtual nao existe
		// a chave eh o proprio parametro
		$chave = $voAtualOuChaveString;
	}
	//echo $chave;

	/*
	 * if($voAtual != null){
	 * $voSessao = getObjetoSessao($voAtual->getNmTabela());
	 * $isSelecionado = $voAtual->isIgualChavePrimaria($voSessao);
	 * $chave = $voAtual->getValorChaveHTML();
	 * }
	 */

	$checked = "";
	if ($isSelecionado)
		$checked = "checked";

		$tipoComponente = "radio";
		if ($isCheckBox) {
			$tipoComponente = "checkbox";
		}

		$retorno = "<INPUT type='$tipoComponente' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $checked . ">";
		// echo $chave;

		return $retorno;
}
function getXGridConsulta($nmRadio, $isCheckBox, $comParenteses = null) {
	if ($comParenteses == null) {
		$comParenteses = false;
	}
	$retorno = "&nbsp;&nbsp;X";
	if ($comParenteses) {
		$retorno = "&nbsp;&nbsp;(X)";
	}

	if ($isCheckBox) {
		// $js = "try{marcarTodosCheckBoxes('$nmRadio');}catch(erro){;}";
		$js = "marcarTodosCheckBoxes('$nmRadio');";
		$retorno = "<a onClick=\"javascript:" . $js . "\" A style='CURSOR: POINTER'>$retorno</a>\n";
	}

	return $retorno;
}
function getRadioButton($idRadio, $nmRadio, $chave, $checked, $complementoHTML) {
	$retorno = "<INPUT type='radio' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $checked . " $complementoHTML>";
	return $retorno;
}

function getInputFile($id, $nm, $complementoHTML) {
	$retorno = "<INPUT type='file' id='$id' name='$nm' $complementoHTML>";
	return $retorno;
}

/**
 * @deprecated
 * checkbox que recebe string pra dizer que esta checado
 * @param unknown $idRadio
 * @param unknown $nmRadio
 * @param unknown $chave
 * @param unknown $checked
 * @param unknown $complementoHTML
 * @return string
 */
function getCheckBox($idRadio, $nmRadio, $chave, $checked = null, $complementoHTML = null) {
	$retorno = "<INPUT type='checkbox' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $checked . " $complementoHTML>";
	return $retorno;
}

/**
 * checkbox que recebe um booleano pra dizer check
 * @param unknown $idRadio
 * @param unknown $nmRadio
 * @param unknown $chave
 * @param unknown $checked
 * @param unknown $complementoHTML
 * @return string
 */
function getCheckBoxBoolean($idRadio, $nmRadio, $chave, $checked = null, $complementoHTML = null, $descricao=null) {
	if ($checked == null) {
		$checked = false;
	}
	if ($checked) {
		$strchecked = "checked";
	}

	$retorno = "<INPUT type='checkbox' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $strchecked . " $complementoHTML>";
	if($descricao != null){
		$retorno.= "$descricao";		
	}
	return $retorno; 
}

/**
 * monta um check booleano sim-nao para a opcao
 * @param unknown $idRadio
 * @param unknown $nmRadio
 * @param unknown $chave
 * @param unknown $checked
 * @param unknown $complementoHTML
 * @return string
 */
function getCheckBoxArray($pArray) {
	$idRadio = $pArray[0]; 
	$nmRadio = $pArray[1]; 
	$chave = $pArray[2]; 
	$checked = $pArray[3]; 
	$complementoHTML = $pArray[4];
	$descricao = $pArray[5];
	$isCheckSimNao = $pArray[6];
	$isDetalhamento = $pArray[7];
	
	if($isDetalhamento){
		//$complementoHTML .= " readonly ";
		$complementoHTML .= " onClick='this.checked = true;' ";
		$idRadio = "";
		$nmRadio = "";
		//echoo("aPAGOU");
	}
	
	if(!$isCheckSimNao){
		$retorno = getCheckBoxBoolean($idRadio, $nmRadio, $chave, $checked, $complementoHTML, $descricao);
	}else{
		//por enquanto eh usado somente em consultas
		/*if($chave == ""){
			asdas ad sf
		}*/
		$arrayAtrib = explode ( CAMPO_SEPARADOR, $chave );
		$chavesimples = $arrayAtrib[0];
		$chaveSim = getIdComponenteHtmlCheckSimNao($chavesimples, constantes::$CD_SIM);
		$chaveNao = getIdComponenteHtmlCheckSimNao($chavesimples, constantes::$CD_NAO);
		
		//echoo("chavesimples $chavesimples && idRadio $idRadio");
				
		$complementoHTML .= " onClick='marcaApenasUmCheckBox(this);' ";
		$separador = "|";
		$retorno = $separador . getTextoHTMLNegrito($descricao) 
			. getCheckBoxBoolean(getIdComponenteHtmlCheckSimNao($idRadio, constantes::$CD_SIM), $nmRadio, 
					$chaveSim, $chaveSim == $chave, $complementoHTML, constantes::$DS_SIM) 
					
			. getCheckBoxBoolean(getIdComponenteHtmlCheckSimNao($idRadio, constantes::$CD_NAO), $nmRadio, 
					$chaveNao, $chaveNao == $chave, $complementoHTML, constantes::$DS_NAO);
	}
	
	return $retorno;
}

function getIdComponenteHtmlCheckSimNao($idRadio, $tipo){
	return $idRadio . constantes::$CD_CAMPO_SEPARADOR . $tipo; 
}

function getInputText($idText, $nmText, $value, $class = null, $size = null, $maxlength = null, $complementoHTML = null) {
	if ($maxlength == null) {
		$maxlength = 20;
	}
	
	if ($size == null) {
		if($value != null){
			$size = strlen($value) + 2;
		}else{
			$size = 20;
		}
	}
	if ($class == null) {
		$class = "camponaoobrigatorio";
	}
	
	if($class == constantes::$CD_CLASS_CAMPO_READONLY){
		$complementoHTML .= " readonly ";
	}

	$retorno .= "<INPUT type='text' id='" . $idText . "' name='" . $nmText . "' value='" . $value . "' class='$class' size='$size' maxlength='$maxlength' $complementoHTML>";

	return $retorno;
}
function getInputHidden($idText, $nmText, $value, $complementoHTML = null) {
	$retorno = "<INPUT type='hidden' id='" . $idText . "' name='" . $nmText . "' value='" . $value . "' $complementoHTML>";
	return $retorno;
}

/**
 * funcao que inclui um hidden na pagina com o valor do atributo na base, para posterior comparacao
 * geralmente usada na validacao de VOs, na verificacao se tais atributos sofreram ou nao alteracao na pagina
 * @param unknown $idText
 * @param unknown $nmText
 * @param unknown $value
 * @param unknown $complementoHTML
 * @return string
 */
function getInputHiddenACompararBanco($idText, $nmText, $value, $complementoHTML = null) {
	$idText .= constantes::$DS_BANCO;
	$nmText .= constantes::$DS_BANCO;
	
	$retorno = getInputHidden($idText, $nmText, $value, $complementoHTML);
	return $retorno;
}

function getInputTextArea($idText, $nmText, $value, $class = null, $rows = null, $cols = null, $maxlength = null, $complementoHTML = null) {
	if ($maxlength == null) {
		$maxlength = 300;
	}
	if ($rows == null) {
		$rows = 3;
	}
	if ($cols == null) {
		$cols = 80;
	}
	if ($class == null) {
		$class = "camponaoobrigatorio";
	}
	
	if($class == constantes::$CD_CLASS_CAMPO_READONLY){
		$complementoHTML .= " readonly ";
	}
	
	$retorno = "<textarea rows='$rows' cols='$cols' id='$idText' name='$nmText' class='$class' maxlength='$maxlength' $complementoHTML>$value</textarea>";
	return $retorno;
}
function getSelectGestor() {
	$dbgestor = new dbgestor ();
	$registros = $dbgestor->consultarSelect ();
	
	$select = new select ( $registros );
}

function formatarCodigoContrato($cd, $ano, $tipo) {
	return vocontrato::getCodigoContratoFormatadoStatic($cd, $ano, $tipo);
	/*$dominioTipoContrato = new dominioTipoContrato ();
	$complemento = $dominioTipoContrato->getDescricao ( $tipo );
	return formatarCodigoAnoComplemento ( $cd, $ano, $complemento );*/
}
function formatarCodigoAnoComplemento($cd, $ano, $complemento) {
	return formatarCodigoAnoComplementoArgs ( $cd, $ano, null, $complemento );
}
function formatarCodigoAnoComplementoArgs($cd, $ano, $pTamanhoCodigo, $complemento, $comEspacoEmBranco=true) {
	$retorno = "";
	if ($complemento != null && $complemento != "") {
		$retorno .= $complemento . " ";
	}
	
	$tamanhoCodigo = TAMANHO_CODIGOS_SAFI;
	if ($pTamanhoCodigo != null) {
		$tamanhoCodigo = $pTamanhoCodigo;
	}
	
	$retorno .= complementarCharAEsquerda ( $cd, "0", $tamanhoCodigo ) . "/" . substr ( $ano, 2, 2 );
	if(!$comEspacoEmBranco){
		$retorno = str_replace(" ", "", $retorno);
	}
	
	return $retorno;
}
function formatarCodigoAno($cd, $ano) {
	return formatarCodigoAnoComplemento ( $cd, $ano, null );
}
function getDataHoraAtual() {
	return date ( 'd/m/Y H:i:s' );
}
function getDataHoje() {
	return date ( 'd/m/Y' );
}
function tratarExcecaoHTML($ex, $vo = null, $paginaErro="../mensagemErro.php") {
	if ($vo != null) {
		putObjetoSessao ( $vo->getNmTabela (), $vo );
		// a debaixo eh para a tela de msg de erro
		putObjetoSessao ( constantes::$ID_REQ_SESSAO_VO, $vo );
	}
	$msg = $ex->getMessage ();
	$msg = str_replace ( "\n", ".", $msg );
	$msg = str_replace ( "<br>", ".", $msg );
	echo $msg;

	$queryComplemento = constantes::$ID_REQ_CD_LUPA . "=" . getAtributoBooleanoComoString(isLupa());
	header ( "Location: $paginaErro?$queryComplemento&texto=" . $msg, TRUE, 307 );
	
	/*<script language="JavaScript">
	window.location="pagina1.php";
	</script>*/
}
function getStrComPuloLinhaHTML($str) {
	return getStrComPuloLinhaGenerico ( $str, "<br>" );
}
function getStrComPuloLinha($str) {
	return getStrComPuloLinhaGenerico ( $str, "\n" );
}
function getStrComPuloLinhaGenerico($str, $pulo) {
	return "$str$pulo";
}
function getDetalhamentoHTMLCodigoAno($ano, $cd, $tamanhoCodigo = null) {
	if($tamanhoCodigo == null){
		$tamanhoCodigo = TAMANHO_CODIGOS;
	}
	$retorno .= "Ano: <INPUT type='text' value='$ano'  class='camporeadonly' size='5' readonly>";
	$retorno .= "Número: <INPUT type='text' value='" . complementarCharAEsquerda ( $cd, "0", $tamanhoCodigo ) . "'  class='camporeadonlyalinhadodireita' size='6' readonly>";	
	return $retorno;
}

function getDetalhamentoHTML($id, $nm, $texto, $tamanhoCodigo = null) {
	$retorno = getInputText($id, $nm, $texto, constantes::$CD_CLASS_CAMPO_READONLY, $tamanhoCodigo)."\n";
	return $retorno;
}

function getEntradaDadosCdAno($cd, $ano, $pNmCampoCd, $pNmCampoAno, $arrayCssClass, $arrayComplementoHTML) {
	$selectExercicio = new selectExercicio ();
	$cssCd = $arrayCssClass [0];
	$cssAno = $arrayCssClass [1];

	$htmlCd = $arrayComplementoHTML [0];
	$htmlAno = $arrayComplementoHTML [1];

	echo "Núm: <INPUT type='text' onkeyup='validarCampoNumericoPositivo(this)' id='" . $pNmCampoCd . "' name='" . $pNmCampoCd . "'  value='" . complementarCharAEsquerda ( $cd, "0", TAMANHO_CODIGOS_SAFI ) . "'  class='" . $cssCd . "' size='5' maxlength='5'  " . $htmlCd . ">";
	echo "&nbsp;Ano: " . $selectExercicio->getHtmlCombo ( $pNmCampoAno, $pNmCampoAno, $ano, true, $cssAno, false, $htmlAno );
}


function getColecaoComoVariavelJS($colecao, $nmVariavelJS, $isChaveString=false, $isValorString=true){
	if(!isColecaoVazia($colecao)){		
		$jsvarColecao = "var $nmVariavelJS = new Array();\n";
		$chaves = array_keys($colecao);
		for ($i=0; $i<count($colecao);$i++){
			$chave = $chaves[$i];
			$valor = $colecao[$chave];
			if($isChaveString){
				$chave = "'$chave'";
			}
			
			if($isValorString){
				$valor = "'$valor'";
			}
				
			$jsvarColecao .=  $nmVariavelJS."[$chave]=$valor;\n";
		}
	}
	return 	$jsvarColecao;
}

function getHTMLTextoObjetoNaoEncontrato(){
	return "<INPUT type='text' class='camporeadonly' size=30 readonly value='NÃO ENCONTRADO'>\n";
}

function getCampoDadosVOAnoCd($vo, $arrayParametroXNmAtributo, $nmClass = "camponaoobrigatorio", $arrayComplementoHTML=null) {
	if($arrayParametroXNmAtributo == null)
		throw new excecaoGenerica("Atributo de parâmetros de códigos vazio.");

		$required = "";
		if ($nmClass == constantes::$CD_CLASS_CAMPO_OBRIGATORIO) {
			$required = "required";
		}
		//a class sera sempre nao obrigatorio
		//pois o atributo required eh utilizado para alterar a class do css
		$nmClass = constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO;

		$arrayCssClass = array (
				$nmClass,
				$nmClass,
		);
		$arrayComplementoHTML = array (
				" $required $arrayComplementoHTML[0] ",
				" $required $arrayComplementoHTML[1] ",
		);

		$chaves = array_keys($arrayParametroXNmAtributo);
		$atribCd = $chaves[0];
		$atribAno = $chaves[1];
		if ($vo != null) {
			$cd = $vo->$atribCd;
			$ano = $vo->$atribAno;
		}

		$selectExercicio = new selectExercicio ();

		$cssCd = $arrayCssClass [0];
		$cssAno = $arrayCssClass [1];

		$htmlCd = $arrayComplementoHTML [0];
		$htmlAno = $arrayComplementoHTML [1];

		$pNmCampoCd = $arrayParametroXNmAtributo[$atribCd];
		$pNmCampoAno = $arrayParametroXNmAtributo[$atribAno];

		$pIDCampoCd = $pNmCampoCd;
		$pIDCampoAno = $pNmCampoAno;
		//$strCamposALimparSeparador = $pIDCampoCd . "*" . $pIDCampoAno;
		?>
	Núm:
<INPUT type="text" onkeyup="validarCampoNumericoPositivo(this)"
	id="<?=$pIDCampoCd?>" name="<?=$pNmCampoCd?>"
	value="<?php echo(complementarCharAEsquerda($cd, "0", TAMANHO_CODIGOS_SAFI));?>"
	class="<?=$cssCd?>" size="4" maxlength="3" <?=$htmlCd?>>
<?php
	echo "Ano: " . $selectExercicio->getHtmlCombo ( $pIDCampoAno, $pNmCampoAno, $ano, true, $cssAno, false, $htmlAno );	
}

/**
 * exibe detalhamento das informacoes relevantes do VO e grava a chave no hidden
 * Recebe o array no seguinte formato 
 * 	$arrayParametroXNmAtributo = 
    array ("cd" => VO::$nmAtrCd,
			"ano" => VO::$nmAtrAno
			...
	);
 * @param unknown $vo
 * @param unknown $arrayParametroXNmAtributo
 * @param string $temLupa
 * @throws excecaoGenerica
 */
function getCampoDetalhamentoVOChave($vo,$arrayParametroXNmAtributo,$temLupa=true) {
	if($arrayParametroXNmAtributo == null)
		throw new excecaoGenerica("Atributo de parâmetros de códigos vazio.");
				
		$arrayAtributosChave = array_keys($arrayParametroXNmAtributo);
		for ($i=0;$i<sizeof($arrayParametroXNmAtributo);$i++){
			$item = $arrayParametroXNmAtributo[$i];
			
			$nmAtributoVO = $arrayAtributosChave[$i];
			$nmAtributoStaticVO = $arrayParametroXNmAtributo[$nmAtributoVO];
			
			echo getInputHidden($nmAtributoStaticVO, $nmAtributoStaticVO, $vo->$nmAtributoVO);
			
			//considera que o codigo eh o primeiro da lista e ano o segundo
			if($i == 0){
				$codigo = $nmAtributoVO;
			}else if($i == 1){
				$ano = $nmAtributoVO;
			}else{
				$complemento.= $vo->$nmAtributoVO.".";
			}				
		}
		//$complemento = removerUltimaString(".", $complemento);
		$cdStr = formatarCodigoAnoComplementoArgs ( $vo->$codigo,$vo->$ano, TAMANHO_CODIGOS, $complemento, false);
	?>
		
	<INPUT type="text" value="<?php echo($cdStr);?>" class="camporeadonlyalinhadodireita" size="<?=strlen($cdStr)+1?>" readonly>	
	<?php	
	if ($temLupa) {
		$chave = $vo->getValorChaveHTML();
		echo getLinkPesquisa ( "../".$vo::getNmTabela()."/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chave );
	}
	
}

function getCampoDadosVOAnoCdDetalhamento($vo,$arrayParametroXNmAtributo,$temLupa=true) {
	if($arrayParametroXNmAtributo == null)
		throw new excecaoGenerica("Atributo de parâmetros de códigos vazio.");	

		$chaves = array_keys($arrayParametroXNmAtributo);
		$atribCd = $chaves[0];
		$atribAno = $chaves[1];
		if ($vo != null) {
			$cd = $vo->$atribCd;
			$ano = $vo->$atribAno;
		}
		
		$cdStr = formatarCodigoAnoComplemento ( $cd, $ano, "");
		// $voContrato = new vocontrato();
		$chave = $vo->getValorChaveHTML ();
		
		$pNmCampoCd = $arrayParametroXNmAtributo[$atribCd];
		$pNmCampoAno = $arrayParametroXNmAtributo[$atribAno];
		
		?>
	<INPUT type="hidden" id="<?=$pNmCampoCd?>"
		name="<?=$pNmCampoCd?>"
		value="<?=$cd?>">
	<INPUT type="hidden" id="<?=$pNmCampoAno?>"
		name="<?=$pNmCampoAno?>"
		value="<?=$ano?>">
		
	<INPUT type="text" value="<?php echo($cdStr);?>" class="camporeadonlyalinhadodireita" size="<?=strlen($cdStr)+1?>" readonly>	
	<?php
	
	//$vo = new voPA();
	if ($temLupa) {
		echo getLinkPesquisa ( "../".$vo::getNmTabela()."/detalhar.php?funcao=" . constantes::$CD_FUNCAO_DETALHAR . "&chave=" . $chave );
	}
	
}

function getAtributoComoBooleano($param) {
	//echoo($param);
	$retorno = false;
	if ($param != null && ($param == constantes::$CD_SIM || $param == 1)) {
		$retorno = true;
	}
	return $retorno;
}

function getAtributoBooleanoComoString($param) {
	$retorno = constantes::$CD_NAO;
	if ($param != null && $param) {
		$retorno = constantes::$CD_SIM;
	}	
	return $retorno;
}

function getCampoRequest($nmCampo, $setarNulo=false) {
	$retorno = null;
	$retorno = @$_POST[$nmCampo];
	if($retorno == null && $setarNulo){
		$retorno = constantes::$CD_CAMPO_NULO;
	}
	return $retorno;
}

/**
 * serve para retornar a pagina de consulta sem acionar o filtro quando nao solicitado
 * @return number
 */
function getLinkRetornoConsulta(){
	$inConsultaHTML = getInConsultarHTMLString();

	$complem = constantes::$ID_REQ_CD_LUPA . "=" . getAtributoBooleanoComoString(isLupa());
	
	$retorno = "index.php?consultar=$inConsultaHTML&$complem";
	
	return $retorno;
}

/**
 * funcao chamada na pagina index da entidade
 * @param unknown $paginamanter
 * @param unknown $funcao
 * @return string
 */
function getLinkManter($paginamanter, $funcao){
	$inConsultaHTML = getInConsultarHTMLString();

	$complem = "&" . constantes::$ID_REQ_CD_LUPA . "=" . getAtributoBooleanoComoString(isLupa());
	$complem .= "&funcao=$funcao";	

	$retorno = "$paginamanter?consultar=$inConsultaHTML" . $complem;

	return $retorno;
}

function getInConsultarHTMLString(){
	return getAtributoBooleanoComoString(filtroManter::isConsultarHTML());
}

function getTagHTMLAbreJavaScript(){
	return "<SCRIPT language='JavaScript' type='text/javascript'>";
}

function getTagHTMLFechaJavaScript(){
	return "</SCRIPT>";
}

function getTagHTMLDIV($nmDiv){
	return "<div id='$nmDiv'></div>";	
}
function getTagHtmlJavaScript($script){
	return getTagHTMLAbreJavaScript() . "\n" . $script . getTagHTMLFechaJavaScript();
}

function getTagHTMLAbreFormulario($inConsulta=null){
	return "\n<FORM name='frm_principal' method='post' action='index.php?consultar=$inConsulta'>\n";
}

function getTagHTMLFechaFormulario(){
	return "\n</FORM>\n";
}

function getNmFuncaoDetalharJSPorVO($vo){
	return "detalhar" . $vo->getNmTabela();	
}

function getFuncaoJSDetalharEmailPorVO($vo){
	$nmServidor = constantes::$NM_SERVIDOR;
	$caminhoPagina = "http://$nmServidor/wordpress/UNCT/eContratosDESENV_eclipse/funcoes/" . $vo->getNmTabela() . "/";
	$nmFuncaoJS = getNmFuncaoDetalharJSPorVO($vo);
	return	getFuncaoJSDetalhar($caminhoPagina, true, $nmFuncaoJS);
}

function getFuncaoJSDetalhar($caminhoPagina=null, $isNovaGuia=false, $nmFuncaoJS = "detalhar"){
	$retorno = "";
	$isConsultaHML = getInConsultarHTMLString();
	
	$retorno = "\nfunction $nmFuncaoJS(isExcluir) {\n
					var funcao ='';\n
					var chave ='';\n
					var lupa ='';\n
					if(isExcluir == null || !isExcluir){\n
						funcao = '".constantes::$CD_FUNCAO_DETALHAR. "';\n
					}else{\n
						funcao = '".constantes::$CD_FUNCAO_EXCLUIR."';\n
					}\n
			
					if (!isRadioButtonConsultaSelecionado('document.frm_principal.rdb_consulta'))\n
							return;\n
			
						chave = document.frm_principal.rdb_consulta.value;\n

						try{\n
							lupa = document.frm_principal.lupa.value;\n
						}catch(ex){\n
							lupa = 'N';\n
						}\n";
		$link= "'".$caminhoPagina . "detalhar.php?consultar=$isConsultaHML&funcao=' + funcao + '&chave=' + chave + '&lupa='+ lupa";
		//$retorno.= "location.href=$link;\n";
		$novaGuia="'_blank'";
		if(!$isNovaGuia){
			$novaGuia="'_self'";
		}
		$retorno.= "window.open($link, $novaGuia)\n;";
		$retorno.= "}";
	
	/*$retorno = "function detalhar(isExcluir) {\n
	 alert('funcionou');}";*/	
				
	return $retorno;	
}

function getTextoHTMLTagMouseOver($texto, $tag, $comImagemInterrogacao = true){
	
	if($comImagemInterrogacao){
		$imagem = getImagem("ajuda.gif", 15, $tag);
	}
	
	$retorno = "<abbr title='$tag'>$texto$imagem</abbr>";
	
	
	return $retorno;
}

function getImagem($nmImagem, $tam=15, $titulo=null){
	return "<img  title='$titulo' src='" . pasta_imagens . "$nmImagem' width='$tam' height='$tam'>";
}

function getTextoHTMLDestacado($texto, $cor="red", $sublinhado = true, $cordefundo=null){
	$retorno = $texto;
	if($sublinhado){
		$retorno = "<u>$retorno</u>";
	}
	$retorno = "<font color='$cor'>".getTextoHTMLNegrito($retorno)."</font>"; 
	
	if($cordefundo != null){
		$retorno = "<mark>$retorno</mark>";
	}
	
	return $retorno;
}
function getTextoHTMLSublinhado($texto){
	return "<u>$texto</u>";
}
function getTextoHTMLNegrito($texto){
	return "<b>$texto</b>";
}
function getTextoHTMLFonteParametros($texto, $size=12){
	return "<font size='$size'>$texto</font>";
}
function getDivHtmlExpansivel($nmDiv, $conteudoDiv=null, $visivel=false, $class = 'campoformulario', $corTexto="red"){	
	//biblioteca_funcoes_principal.js
	$scriptOnLoad = "esconderDiv(document.getElementById('$nmDiv'), null, true);";
	$scriptOnClick = "esconderDiv(document.getElementById('$nmDiv'), null, document.getElementById('$nmDiv').style.display=='');";
	$imagem = "<img  title='Entrar' src='" . pasta_imagens . "sinal_mais.gif' width='15' height='15'>";	
	$link = "<a href=\"javascript:$scriptOnClick\" >$imagem</a>";
	//$html = "$imagem<DIV class='$class' id='$nmDiv' name='$nmDiv' onLoad='ocultarElemento('$nmDiv')'>$conteudoDiv</DIV>";
	$html = "$link<DIV class='$class' id='$nmDiv' name='$nmDiv'>". getTextoHTMLDestacado($conteudoDiv, $corTexto, false)  . "</DIV>";
	$html .= getTagHtmlJavaScript($scriptOnLoad);
	return $html;
}

function getDivHtmlExpansivelArray($pArray){
	$nmDiv = $pArray[0];
	$conteudoDiv = $pArray[1];
	$titulo = $pArray[2];
	$visivel = $pArray[3];
	$class = $pArray[4];
	$corTexto = $pArray[5];
	
	if($class == null){
		$class = 'campoformulario';
	}
	
	if($corTexto == null){
		$corTexto="black";
	} 
					
	//biblioteca_funcoes_principal.js
	$scriptOnLoad = "esconderDiv(document.getElementById('$nmDiv'), null, true);";
	$scriptOnClick = "esconderDiv(document.getElementById('$nmDiv'), null, document.getElementById('$nmDiv').style.display=='');";
	$imagem = "<img  title='Entrar' src='" . pasta_imagens . "sinal_mais.gif' width='15' height='15'>";	
	$link = "<a href=\"javascript:$scriptOnClick\" >$imagem</a>";
	//$html = "$imagem<DIV class='$class' id='$nmDiv' name='$nmDiv' onLoad='ocultarElemento('$nmDiv')'>$conteudoDiv</DIV>";
	$html = "$titulo $link<DIV class='$class' id='$nmDiv' name='$nmDiv'>$conteudoDiv</DIV>";
	$html .= getTagHtmlJavaScript($scriptOnLoad);
	return $html;
}

/**
 * recupera um atributo com o valor a comparar entre aquele inserido pelo usuario e o que esta na base de dados
 * @param unknown $nmAtributo
 * @return unknown
 */
function getAtributoTelaACompararBanco($nmAtributo){
	$nmAtributo .= constantes::$DS_BANCO;
	return $_POST[$nmAtributo];
}

/**
 * retorna varios inputs hidden
 * @param unknown $pArray
 */
function getInputHiddenPorArrayNomeAlternativo($pArray){
	foreach ($pArray as $id => $valor){
		$id = voentidade::getNomeAtributoAlternativo($id);
		$retorno .= "\n".getInputHidden($id, $id, $valor);		
	}
	return $retorno;
}

/**
 * monta variavel hash pra ser usada no java script
 * @param unknown $pArray
 * @return string
 */
function montarHashNomeAlternativo($pArrayAtributos, $nmVariavelJS,$isChaveString=false, $isValorString=true){
	foreach ($pArrayAtributos as $atributo){
		$arrayCompleto[$atributo] = voentidade::getNomeAtributoAlternativo($atributo);
	}
	
	$retorno = getColecaoComoVariavelJS($arrayCompleto, $nmVariavelJS,$isChaveString,$isValorString);
	return $retorno;
}

function getInputCampoCheckResponsabilidade($pArrayCamposResponsabilidade, $nmVariavelJS="varJSCamposResponsabilidade"){
	$nmCheckBox = constantes::$ID_REQ_CHECK_RESPONSABILIDADE;

	$retorno = "\n<SCRIPT language='JavaScript' type='text/javascript'>\n";
	$retorno .= getColecaoComoVariavelJS($pArrayCamposResponsabilidade, $nmVariavelJS);
	$retorno .= "\n</SCRIPT>";
	$retorno .= "\n<INPUT type='checkbox' id='$nmCheckBox' name='$nmCheckBox' value='' onClick='validaFormRequiredCheckBox(this, $nmVariavelJS);'>". voMensageria::$DS_RESPONSABILIDADE_CAMPO_OBR;
	
	return $retorno;
}

function redirecionarConfirmar($vo){
	putObjetoSessao("vo", $vo);
	header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
}

function getFuncaoOperacaoHTML(){
	$funcao = @$_GET ["funcao"];
	return $funcao;
}

function getMsgPlaceHolder($msg){
	return "placeholder=\"$msg\"";
}

function getMsgPadraoInputText(){
	return "placeholder=\"Separar por ; para mais de um registro.\"";
}

function getTagHTMLAbreTabela(){
	return "    <TABLE id='table_filtro' class='filtro' cellpadding='0' cellspacing='0'>
        <TBODY>
				";
}
function getTagHTMLFechaTabela(){
	return "       </TBODY>
	  </TABLE>";
}