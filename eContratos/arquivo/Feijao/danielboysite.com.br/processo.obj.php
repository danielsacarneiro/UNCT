<?php
// Classe config
include "lib/config.obj.php";

// Classe db
include "lib/db.obj.php";

class processo{
	
	var $cDb;
    var $cConfig;

	// ...............................................................
	// construtor
	function processo() {
		$this->cConfig = new config();
		$this->cDb = new db();
		$this->cDb->abrirConexao($this->cConfig->db, $this->cConfig->login, $this->cConfig->senha,$this->cConfig->odbc,$this->cConfig->driver,$this->cConfig->servidor);
	}
	
	function getDadosProcesso($CodProcesso){
		$query = "SELECT * FROM tab_processo 
				   WHERE CodProcesso = '$CodProcesso'";
		$retorno = $this->cDb->consultar($query);
	    return $retorno;
	}
	
	
	function listarProcessoo() {
		$query = "SELECT * FROM tab_processo WHERE Ativo = 1 ORDER BY CodProcesso";
		$retorno = $this->cDb->consultar($query);
	    return $retorno;
	}

 	//cadastra o usuário
	function cadastraUsuario($novoCodigo,$nome,$login,$senha,$lembrete,$CodLoja) {
		$query = "INSERT INTO tab_usuario (CodUsuario,Nome,Login,Senha,Lembrete,CodEntidade,Conselho,AtivoUsuario) 
					   VALUES('$novoCodigo','$nome','$login','$senha','$lembrete','$CodLoja',0,1)";
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;
	}
	
	//função para confirmar os dados loja
	function ConfirmaDados($CodArquiteto,$endereco,$bairro,$cidade,$estado,$cep,$telefones,$celular,$email,$site,$profissao,$crea,$escritorio,$socio,$facebook,$twitter,$aniversario,$dataAlteracao) {
		$query = "UPDATE tab_entidade
                             SET Endereco = '$endereco',
                                 Bairro = '$bairro',
                                 Cidade = '$cidade',
                                 Estado = '$estado',
                                 Cep = '$cep',
                                 Telefones = '$telefones',
                                 Celular = '$celular',
                                 EMail = '$email',
                                 Site = '$site',
                                 Profissao = '$profissao',
                                 Crea = '$crea',
                                 Escritorio = '$escritorio',
                                 Socio = '$socio',
                                 Facebook = '$facebook',
                                 Twitter = '$twitter',
                                 Aniversario = '$aniversario',
                                 DataAlteracao = '$dataAlteracao'
                           WHERE CodEntidade = '$CodArquiteto'";
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;
	}

	function excluirProduto($CodProduto,$CodEntidade) {
		$query = "DELETE FROM tab_produto 
		           WHERE CodProduto = '$CodProduto'
		             AND CodEntidade = '$CodEntidade'";
		$retorno = $this->cDb->atualizar($query);
	    return $retorno;
	}
	
	function limpaResultado(){
		$this->cDb->limpaResultado();	   
	}
	
	Function finalize() {
		$this->cDb->fecharConexao();
	}

	Function fechar() {
		$this->cDb->close();
	}
}	