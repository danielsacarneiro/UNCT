<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroConsultarDemandaUsuario extends filtroManter{

	public static $nmFiltro = "filtroManterGestor";
	
	static $NmColNumTotalDemandas = "NmColNumTotalDemandas";
	static $NmColNumDemandasSetor = "NmColNumDemandasSetor";
	static $NmColFatorTrabalho = "NmColFatorTrabalho";
	 
	var $ano = "";
	var $situacao  = "";

	// ...............................................................
	function getFiltroFormulario() {
		$this->ano = @$_POST[voDemanda::$nmAtrAno];
		$this->situacao  = @$_POST[voDemanda::$nmAtrSituacao];
	}

	function getFiltroConsultaSQL($comAtributoOrdenacao = null) {

		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico;
		$nmTabelaDemanda = voDemanda::getNmTabelaStatic ( false );
		$nmTabelaWPUsers = vousuario::getNmTabela();

		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}

		if(isAtributoValido($this->ano)){
			$filtro = $filtro . $conector
			. "$nmTabelaDemanda." . voDemanda::$nmAtrAno
			. " = " . getVarComoNumero($this->ano);
						
					$conector  = "\n AND ";

		}
		
		if ($this->situacao != null
				&& (!is_array($this->situacao) || (is_array($this->situacao) && !$this->isAtributoArrayVazio($this->situacao)))) {
		
					$comparar = " = '" . $this->situacao . "'";
					$colecaoAComparar = $this->situacao;
						
					if(is_array($colecaoAComparar)){
						//acrescenta os itens que compoem o A FAZER
						if(in_array(dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER, $colecaoAComparar)){
							$colecaoAComparar = array_merge($colecaoAComparar, array_keys(dominioSituacaoDemanda::getColecaoAFazer()));
							$colecaoAComparar = removeElementoArray($colecaoAComparar, dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER);
						}
						$comparar = " IN (" . getSQLStringFormatadaColecaoIN($colecaoAComparar, true) . ")";
					}
		
					$filtro = $filtro . $conector . $nmTabelaDemanda . "." . voDemanda::$nmAtrSituacao . $comparar;
		
					$conector = "\n AND ";
				}
		
		$conector = "\n AND ";
		$filtro = $filtro . $conector . "$nmTabelaDemanda." . voDemanda::$nmAtrCdPessoaRespUNCT . " IS NOT NULL ";
		//remove a exibicao do chefe da unct
		if(!isUsuarioAdmin()){
			$filtro = $filtro . $conector . "$nmTabelaDemanda." . voDemanda::$nmAtrCdPessoaRespUNCT . " <> 1 ";
		}
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);		
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}

}

?>