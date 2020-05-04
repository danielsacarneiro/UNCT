<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");
require_once (caminho_funcoes . vocontrato::getNmTabela() . "/dominioAutorizacao.php");

class filtroConsultarDemandaRendimento extends filtroConsultarDemandaGestao {
	
	public $nmFiltro = "filtroConsultarDemandaRendimento";
	static $NmTabelaRendimento= "NmTabelaRendimento";
	static $CD_CAMPO_SUBSTITUICAO_PRINCIPAL = "CD_CAMPO_SUBSTITUICAO_PRINCIPAL";
	
	//colunas
	static $NmColNuEntradas = "NmColNuEntradas";
	static $NmColNuSaidas = "NmColNuSaidas";
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$nmTabela = voDemandaTramitacao::getNmTabelaStatic(false);
					
		//seta os filtros obrigatorios
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
			//echo "setou o ano defaul";
			;
		}

		if($this->vodemanda->ano != null){
				
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaTramitacao::$nmAtrAno
			. " = "
					. $this->vodemanda->ano
					;
		
					$conector  = "\n AND ";
		
		}
		
		if($this->vodemanda->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemandaTramitacao::$nmAtrCd
			. " = "
					. $this->vodemanda->cd
					;
		
					$conector  = "\n AND ";		
		}
				
		if ($this->vodemanda->tipo != null 
				&& $this->vodemanda->tipo != "" 
				&& !$this->isAtributoArrayVazio($this->vodemanda->tipo)) {
					
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrTipo;
			
			$tipoDem = $this->vodemanda->tipo;
			
			/*if($tipoDem == dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO){			
				$tipoDem = array_keys(dominioTipoDemanda::getColecaoTipoDemandaContrato());
			}*/
			
			if(is_array($tipoDem)){
				$filtro .= 	" IN (" . getSQLStringFormatadaColecaoIN($tipoDem, false) . ") ";
				
			}else{
				$filtro .= 	" = " . $tipoDem;				
			}				
			
			$conector  = "\n AND ";
		}
		
		$tpDemandaContrato = $this->vodemanda->tpDemandaContrato;
		if ($tpDemandaContrato != null
				&& $tpDemandaContrato != ""
				&& !$this->isAtributoArrayVazio($tpDemandaContrato)) {
				
				$strFiltroTpDemanda = getSQLBuscarStringCampoSeparador($tpDemandaContrato, voDemanda::$nmAtrTpDemandaContrato, constantes::$CD_OPCAO_OR);
				//echo $strFiltroTpDemanda;
				$filtro = $filtro . $conector . $strFiltroTpDemanda;
				$conector  = "\n AND ";
		}
		
		if($this->vodemanda->inTpDemandaReajusteComMontanteA != null){
			$reajuste = $this->vodemanda->inTpDemandaReajusteComMontanteA;
			$clausulaReajuste = " $nmTabela." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA . " = " . getVarComoString($this->vodemanda->inTpDemandaReajusteComMontanteA);
			
			if($reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_A
					|| $reajuste == dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_B){
				
						$clausulaReajuste .= " OR $nmTabela." .voDemanda::$nmAtrInTpDemandaReajusteComMontanteA
									. " = "
									. getVarComoString(dominioTipoReajuste::$CD_REAJUSTE_AMBOS)
						;				
			}
			$filtro = $filtro . $conector . "($clausulaReajuste)";
					
			$conector  = "\n AND ";
		}
		
		/*if($this->vodemanda->cdSetor != null){
			$filtro = $filtro . $conector
			. voDemanda::$nmAtrCdSetor
			. " = "
					. $this->vodemanda->cdSetor
					;
		
					$conector  = "\n AND ";
		}*/
		
		if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. " (("
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
			. " IS NULL AND "
			.$nmTabela. "." .voDemanda::$nmAtrCdSetor
			. " = "
			. $this->vodemanda->cdSetorDestino			
			. " ) OR ("
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
			. " IS NOT NULL AND "
			. $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrCdSetorDestino
			. " = "
			. $this->vodemanda->cdSetorDestino
			. "))";
										
			$conector  = "\n AND ";
		}
		
		if($this->cdSetorPassagem != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND (" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorOrigem. "=" . $this->cdSetorPassagem
					. " OR "
					. $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCdSetorDestino . "=" . $this->cdSetorPassagem							
					. "))\n ";
		
							$conector  = "\n AND ";
		}
		
		
		if($this->vodemanda->prioridade != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrPrioridade
			. " = "
					. $this->vodemanda->prioridade
					;
		
					$conector  = "\n AND ";
		}
		
		/*if($this->vodemanda->situacao != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voDemanda::$nmAtrSituacao
			. " = "
					. $this->vodemanda->situacao
					;
		
					$conector  = "\n AND ";
		}*/
		
		if ($this->vodemanda->situacao != null 
				&& (!is_array($this->vodemanda->situacao) || (is_array($this->vodemanda->situacao) && !$this->isAtributoArrayVazio($this->vodemanda->situacao)))) {
						
			$comparar = " = '" . $this->vodemanda->situacao . "'";
			if(is_array($this->vodemanda->situacao)){
							
				if(count($this->vodemanda->situacao) == 1 && $this->vodemanda->situacao[0] == dominioSituacaoDemanda::$CD_SITUACAO_DEMANDA_A_FAZER){
					$comparar = " IN (" . getSQLStringFormatadaColecaoIN(array_keys(dominioSituacaoDemanda::getColecaoAFazer()), true) . ")";
				}else{
					$comparar = " IN (" . getSQLStringFormatadaColecaoIN($this->vodemanda->situacao, true) . ")";
				}
			}
				
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrSituacao . $comparar;
				
			$conector = "\n AND ";
		}		
		
		if ($this->tipoExcludente != null && $this->tipoExcludente != "" && !$this->isAtributoArrayVazio($this->tipoExcludente)) {
			$comparar = " <> '" . $this->tipoExcludente. "'";
			if(is_array($this->tipoExcludente)){		
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->tipoExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrTipo . $comparar;
		
			$conector = "\n AND ";
		}
		
		if($this->inComPAAPInstaurado != null){
			$comparacao = " IS NOT NULL ";
			if(!getAtributoComoBooleano($this->inComPAAPInstaurado)){
				$comparacao = " IS NULL ";
			}
			
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrCdPA
			. " $comparacao "
					;
		
					$conector  = "\n AND ";
		}		
		
		if($this->inSEI != null){			
			$numCaracteres = constantes::$TAMANHO_CARACTERES_PRT;
			if(getAtributoComoBooleano($this->inSEI)){
				$numCaracteres = constantes::$TAMANHO_CARACTERES_SEI;
			}
				
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE "
					. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
					. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
					. " AND LENGTH($nmTabelaTramitacao." . voDemandaTramitacao::$nmAtrProtocolo
					. ") =  $numCaracteres)\n";
						
			$conector  = "\n AND ";
		}
		
		if ($this->prioridadeExcludente != null && !$this->isAtributoArrayVazio($this->prioridadeExcludente)) {
			$comparar = " <> '" . $this->prioridadeExcludente. "'";
			if(is_array($this->prioridadeExcludente)){
				$comparar = " NOT IN (" . getSQLStringFormatadaColecaoIN($this->prioridadeExcludente, true) . ")";
			}
		
			$filtro = $filtro . $conector . $nmTabela . "." . voDemanda::$nmAtrPrioridade . $comparar;
		
			$conector = "\n AND ";
		}
		
		if($this->dtUltMovimentacaoInicial != null){
			/*$colDemandaTram = $nmTabelaTramitacao. "." .voDemandaTramitacao::$nmAtrDhInclusao;
			$colDemanda = $nmTabela. "." .voDemanda::$nmAtrDhUltAlteracao;
			$dtUltMovimentacao = getVarComoDataSQL($this->dtUltMovimentacaoInicial); 
			
			$filtro = $filtro . $conector
			. " ((". $colDemandaTram 
			. " IS NOT NULL AND DATE(". $colDemandaTram
			. ") >= $dtUltMovimentacao "
			. ") OR "
			. "(". $colDemanda
			. " IS NOT NULL AND DATE(". $colDemanda
			. ") >= "
			. $dtUltMovimentacao					
			. ")) "
			;*/
			
			$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao($this->dtUltMovimentacaoInicial, ">=");
		
			$conector  = "\n AND ";
		}
		
		if($this->dtUltMovimentacaoFinal != null){
			$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao($this->dtUltMovimentacaoFinal, "<=");		
			$conector  = "\n AND ";
		}
		
		if($this->cdSetorImplementacaoEconti != null){
			/*$filtro = $filtro . $conector
			. " DATE($nmTabelaTramitacao." .voDemandaTramitacao::$nmAtrDhInclusao
			. ") >= ";*/
			
			if($this->cdSetorImplementacaoEconti == dominioSetor::$CD_SETOR_UNCT){
				$filtro = $filtro . $conector . static::getSQLDataDemandaMovimentacao("01/02/2019", ">=");
			}
			
			$conector  = "\n AND ";
		}
		
		if($this->vodemanda->prt != null){
			$filtro = $filtro . $conector
			. " EXISTS (SELECT 'X' FROM " . $nmTabelaTramitacao
			. " WHERE " 
			. $nmTabela . "." . voDemanda::$nmAtrAno . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrAno
			. " AND " . $nmTabela . "." . voDemanda::$nmAtrCd . "=" . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrCd
			. " AND " . $nmTabelaTramitacao. "." . voDemandaTramitacao::$nmAtrProtocolo 
			. " LIKE '%"			
			. voDemandaTramitacao::getNumeroPRTSemMascara($this->vodemanda->prt,false)
			. "%')\n";
		
			$conector  = "\n AND ";
		}
		
		if($this->vocontrato->anoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrAnoContrato
			. " = "
					. $this->vocontrato->anoContrato 
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->cdContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrCdContrato
			. " = "
					. $this->vocontrato->cdContrato
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->tipo != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrTipoContrato
			. " = "
					. getVarComoString($this->vocontrato->tipo)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->cdEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrCdEspecieContrato
			. " = "
					. getVarComoString($this->vocontrato->cdEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->vocontrato->sqEspecie != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaContrato. "." .voDemandaContrato::$nmAtrSqEspecieContrato
			. " = "
					. getVarComoNumero($this->vocontrato->sqEspecie)
					;
		
					$conector  = "\n AND ";
		}
		
		//echo $this->vocontrato->cdAutorizacao; 
		if($this->vocontrato->cdAutorizacao != null){
			$strComparacao = "COALESCE (" . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrCdAutorizacaoContrato 
							. "," 
							. $nmTabelaContrato . "." . voContrato::$nmAtrCdAutorizacaoContrato . ")";
			
			if(!is_array($this->vocontrato->cdAutorizacao)){
				$filtro = $filtro . $conector
				//. $nmTabelaContrato. "." .vocontrato::$nmAtrCdAutorizacaoContrato
				. $strComparacao
				. " = "
						. $this->vocontrato->cdAutorizacao
						;				
			}else{
				
				$colecaoAutorizacao = $this->vocontrato->cdAutorizacao;				
				$filtro = $filtro . $conector . $strComparacao . voContratoInfo::getOperacaoFiltroCdAutorizacaoOR_AND($colecaoAutorizacao, $this->inOR_AND);
				
			}			
			
			$conector  = "\n AND ";
		}
		
		if($this->nmContratada != null){
			$filtro = $filtro . $conector
			//. "($nmTabelaPessoaContrato." .vopessoa::$nmAtrNome
			. "(". getSQLNmContratada(false)
			. " LIKE '%"
			. $this->nmContratada
			. "%'"
			. " OR $nmTabela." .voDemanda::$nmAtrTexto
			. " LIKE '%"
			. $this->nmContratada
			. "%')"
			;		
			$conector  = "\n AND ";
		
		}
		
		if($this->docContratada != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaContrato. "." .vopessoa::$nmAtrDoc
			. " = '"
					. documentoPessoa::getNumeroDocSemMascara($this->docContratada)
					. "'"
							;
							$conector  = "\n AND ";
		
		}
		
		if($this->cdDemandaInicial != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrCd
			. " >= "
					. $this->cdDemandaInicial;
					$conector  = "\n AND ";
		
		}
		
		if($this->cdDemandaFinal != null){
			$filtro = $filtro . $conector
			. $nmTabela . "." .voDemanda::$nmAtrCd
			. " <= "
					. $this->cdDemandaFinal;
					$conector  = "\n AND ";
		
		}
		
		if($this->vlGlobalInicial != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato . "." .vocontrato::$nmAtrVlGlobalContrato
			. " >= "
					. getVarComoDecimal($this->vlGlobalInicial);
					$conector  = "\n AND ";
		
		}
		
		if($this->vlGlobalFinal != null){
			$filtro = $filtro . $conector
			. $nmTabelaContrato . "." .vocontrato::$nmAtrVlGlobalContrato
			. " >= "
					. getVarComoDecimal($this->vlGlobalFinal);
					$conector  = "\n AND ";
		
		}
		
		if($this->cdClassificacaoContrato != null){
			$filtro = $filtro . $conector
			. $nmTabelaContratoInfo . "." .voContratoInfo::$nmAtrCdClassificacao
			. " = "
			. getVarComoNumero($this->cdClassificacaoContrato);
			$conector  = "\n AND ";
		
		}
		
		if ($this->inMaoDeObra != null) {
		
			$filtro = $filtro . $conector . $nmTabelaContratoInfo . "." . voContratoInfo::$nmAtrInMaoDeObra . " = " . getVarComoString ( $this->inMaoDeObra);
		
			$conector = "\n AND ";
		}
		
		if($this->inContratoComDtPropostaVencida != null){
			/*if($this->vocontrato->dtProposta == null){
				throw new excecaoGenerica("Consulta data proposta futura: campo obrigatório: vocontrato->dtproposta.");
			}*/

			//a data da comparacao eh a data de hoje
			$dtReferencia = getVarComoDataSQL(getDataHoje());
			//$dtReferencia = getVarComoDataSQL($this->vocontrato->dtProposta);
			$atributoDataReajuste = "COALESCE($nmTabelaContratoInfo" . "." .voContratoInfo::$nmAtrDtBaseReajuste 
						. ",$nmTabelaContratoInfo." . voContratoInfo::$nmAtrDtProposta 
						. ",$nmTabelaContrato ." . vocontrato::$nmAtrDtAssinaturaContrato
						.")";
			//$nmAtributoDataProposta = $nmTabelaContratoInfo . "." .voContratoInfo::$nmAtrDtProposta;
			$dtPropostaPAram = $atributoDataReajuste;
			/*CONSIDERAVA 1 ANO ANTES DO ATUAL PARA FAZER A DIFERENCA DE 1 ANO PARA A CONCESSAO DE REAJUSTE
			$ano = "YEAR($dtReferencia)-1";*/			
			//ANTES ERA ANO-1, agora ficou somente ANO... nao lembro o motivo da subtracao de 1
			$ano = "YEAR($dtReferencia)";
			$mes = "MONTH($dtPropostaPAram)";
			//considera o dia 15 do mes como dia limite para obtencao do indice de reajuste exigido por lei
			//ver data da liberacao dos indices em https://www.indiceseindicadores.com.br/inpc/
			//dai que foi usado o dia 15 como media
			$dia = "15";			
			//$dia = "DAY($dtPropostaPAram)";
			$dtPropostaPAram = getDataSQLFormatada($ano,$mes, $dia);
			
			//echo "$dtReferencia";
						
			//se a diferenca de anos for zero, quer dizer que nao ha diferenca de 1 ano
			//nesse caso, o vencimento da data da proposta nao ocorreu, nao podendo ser a demanda analisada para fins de reajuste
			if(getAtributoComoBooleano($this->inContratoComDtPropostaVencida)){
				//desejam-se as demandas com propostas vencidas
				$operacao = " > 0 ";
			}else{
				//desejam-se as demandas com propostas a vencer
				$operacao = " = 0 ";
			}			
			
			//se a data da proposta for nula, exibe o alerta de todo o jeito, ate que ela seja preenchida
			//ainda verifica se tem ou nao montanteA, caso tenha, traz a demanda pois ela sera analisada de imediato
			//se nao tiver montanteA, trarah apenas em caso positivo de aniversario da data da proposta
			$conjuntoSQLMontanteA = "'".dominioTipoReajuste::$CD_REAJUSTE_AMBOS."','" . dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_A . "'"; 
			$conjuntoSQLMontanteB = "'".dominioTipoReajuste::$CD_REAJUSTE_OUTROS 
									."','" . dominioTipoReajuste::$CD_REAJUSTE_AMBOS
									."','" . dominioTipoReajuste::$CD_REAJUSTE_MONTANTE_B 
									. "'";
			
			$nmAtributoInTpDemandaReajusteComMontanteA = voDemanda::$nmAtrInTpDemandaReajusteComMontanteA; 
			$sqlTrazerTipoReajusteComMontanteA =  " $nmAtributoInTpDemandaReajusteComMontanteA IN ($conjuntoSQLMontanteA) ";
			$sqlTrazerTipoReajusteComMontanteB = " $nmAtributoInTpDemandaReajusteComMontanteA IN ($conjuntoSQLMontanteB) ";
			
			/*$sqlIsDemandaSAD =
					"($nmTabela." . voDemanda::$nmAtrTpDemandaContrato . "=".dominioTipoDemandaContrato::$CD_TIPO_REAJUSTE
					. " AND "
					. "$nmTabelaContratoInfo." . voContratoInfo::$nmAtrCdClassificacao . "<>".dominioClassificacaoContrato::$CD_LOCACAO_IMOVEL
					. " AND "
					. $this->getSQLInternoIsDemandaSAD($nmTabelaContratoInfo, $nmTabelaContrato)
					. ")";*/
			
			//para demandas SAD nao ha preocupacao de listar aqui
			//pois elas aparecerao no lugar especifico de DEMANDAS SAD PRIORIZADAS
			$filtro = $filtro . $conector
			. " (
				$atributoDataReajuste IS NULL 
				OR 
				$nmAtributoInTpDemandaReajusteComMontanteA IS NULL 
				OR 
				$sqlTrazerTipoReajusteComMontanteA 
				OR 
				($sqlTrazerTipoReajusteComMontanteB AND "
				//basta comparar se o mes da data de referencia (hoje) eh maior ou igual ao mes da data de comparacao
				//se for, significa que o tempo necessario para se ter o calculo do indice, que eh de 1 ano, ja passou
				//. " MONTH($dtReferencia) >= MONTH($dtPropostaPAram) "
				// verifica se transcorreu 1 ano da data base de reajuste
				. getDataSQLDiferencaAnos($dtPropostaPAram, $dtReferencia)
				// . $operacao
				. ")) ";
			
			$conector  = "\n AND ";
		}
						
		if($this->inRetornarReajusteSeLocacaoImovel != null && !getAtributoComoBooleano($this->inRetornarReajusteSeLocacaoImovel)){
			$filtro = $filtro . $conector
			. " NOT ($nmTabela." .voDemanda::$nmAtrTipo 
			. " = "
			. dominioTipoDemanda::$CD_TIPO_DEMANDA_CONTRATO_REAJUSTE
			. " AND $nmTabelaContratoInfo." .voContratoInfo::$nmAtrCdClassificacao
			. " IS NOT NULL "
			. " AND $nmTabelaContratoInfo." .voContratoInfo::$nmAtrCdClassificacao
			. " = "
			. dominioClassificacaoContrato::$CD_LOCACAO_IMOVEL
			. ")";
					$conector  = "\n AND ";
		
		}
		
		if($this->voproclic->cd != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrCdProcLic
			. " = "
					. $this->voproclic->cd
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->ano != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrAnoProcLic
			. " = "
					. $this->voproclic->ano
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voproclic->cdModalidade != null){
			$filtro = $filtro . $conector
			. $nmTabelaDemandaPL . "." .voDemandaPL::$nmAtrCdModalidadeProcLic
			. " = "
					. getVarComoString($this->voproclic->cdModalidade)
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voPA->cdPA != null){
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrCdPA
			. " = "
					. $this->voPA->cdPA
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->voPA->anoPA != null){
			$filtro = $filtro . $conector
			. $nmTabelaPA . "." .voPA::$nmAtrAnoPA
			. " = "
					. $this->voPA->anoPA
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->nuTempoVidaMinimo != null){
			$filtro = $filtro . $conector
			. static::getSQLNuTempoVida($nmTabela)
			. " >= "
					. $this->nuTempoVidaMinimo
					;
		
					$conector  = "\n AND ";
		}
		
		if($this->nuTempoVidaMinimoUltimaTram != null){
		//if($this->vodemanda->cdSetorDestino != null){
			$filtro = $filtro . $conector
			. static::getSQLNuTempoUltimaTram($nmTabelaTramitacao, $nmTabela)
			. " >= "
					. $this->nuTempoVidaMinimoUltimaTram
					;
						
					$conector  = "\n AND ";
		}
		
		
		$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);

		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributoOrdenacaoAnteriorDefault(){
		//$nmTabelaDemanda = voDemanda::getNmTabelaStatic($this->isHistorico);
		//$retorno = $nmTabelaDemanda . "." . voDemanda::$nmAtrAno . " " . $this->cdOrdenacao;
		return $retorno;
	}
	
	function getAtributoOrdenacaoDefault(){
		//$retorno = filtroConsultarDemandaGestao::$NmColNumTotalDemandas . " " . constantes::$CD_ORDEM_DECRESCENTE;
		return $retorno;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				filtroConsultarDemandaRendimento::$NmColNuSaidas => "Saídas",
				filtroConsultarDemandaRendimento::$NmColNuEntradas => "Entradas",				
		);
		
		return $varAtributos;
	}	

}

?>