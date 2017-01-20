package br.gov.pe.sefaz.sfi.fin.util;

import java.sql.Date;

import br.gov.pe.sefaz.sfi.fin.gfu.util.ConstantesGFU;
import br.gov.pe.sefaz.sfi.util.BibliotecaFuncoesDataHora;
import br.gov.pe.sefaz.sfi.util.DAO_DB2;

/*
 * Este arquivo eh propriedade da Secretaria da Fazenda do Estado
 * de Pernambuco (Sefaz-PE). Nenhuma informacao nele contida pode ser
 * reproduzida, mostrada ou revelada sem permissao escrita da Sefaz-PE.
 */
public abstract class StatementConsultaVigencia_DB2 extends DAO_DB2 {
	//~ Metodos --------------------------------------------------------------------------------------------------------------------

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes   Autor: Josué , Alysson e Daniel
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtual(String pNmEntidade, String pNmColDtInicioVigencia, String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dtHoje = "'" + BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString() + "'";

		String sqlClausulaVigenciaAtual =
			"( ( "
				+ dtHoje
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ dtHoje
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		return sqlClausulaVigenciaAtual;
	}

	/**
	 * Retorna uma String formatada para realizar o GROUP BY na consulta do registros de vigencia Esta String deve conter os
	 * Atributos da Chave Primaria com excecao do NmAtributoSequencial responsável pela diferenciação no projeto de vigência
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 *
	 * @return
	 */
	public static String getAtributosChaveFormatadoSQL(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial) {
		int i = 0;
		String retorno = "";
		String nmAtributo = "";

		for (i = 0; i < pColecaoNmAtributosChavePrimaria.length; i++) {
			nmAtributo = pColecaoNmAtributosChavePrimaria[i];

			//retorna sem o atributo do sequencial
			//para fazer o GROUP BY
			if (!pNmColSequencial.equals(nmAtributo)) {
				retorno = retorno + pNmEntidade + "." + nmAtributo + ",";
			}
		}

		//retira a virgula final
		retorno = retorno.substring(0, retorno.length() - 1);

		return retorno;
	}


	private String getAtributosChaveFormatadoSQLSeChaveNula(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		Class[] pColecaoTpAtributosChavePrimaria,
		String pNmColSequencial) {
		int i = 0;
		String retorno = "";
		String nmAtributo = "";

		for (i = 0; i < pColecaoNmAtributosChavePrimaria.length; i++) {
			nmAtributo = pColecaoNmAtributosChavePrimaria[i];

			//retorna sem o atributo do sequencial
			//para fazer o GROUP BY
			if (!pNmColSequencial.equals(nmAtributo)) {
				if (pColecaoTpAtributosChavePrimaria == null || pColecaoTpAtributosChavePrimaria.length == 0) {

					retorno = retorno + "COALESCE(" + pNmEntidade + "." + nmAtributo + ",'NULO')" + ",";

				} else {
					if (pColecaoTpAtributosChavePrimaria[i].isAssignableFrom(String.class)) {

						retorno = retorno + "COALESCE(" + pNmEntidade + "." + nmAtributo + ",'NULO')" + ",";

					} else if ((pColecaoTpAtributosChavePrimaria[i].isAssignableFrom(java.util.Date.class))) {

						retorno = retorno + "COALESCE(" + pNmEntidade + "." + nmAtributo + ",'1800-01-01')" + ",";

					} else {

						retorno = retorno + "COALESCE(" + pNmEntidade + "." + nmAtributo + ",0)" + ",";
					}
				}
			}
		}

		//retira a virgula final
		retorno = retorno.substring(0, retorno.length() - 1);

		return retorno;
	}

	/**
	 * -
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributos
	 *
	 * @return
	 */
	private String getAtributosFormatadoSQL(String pNmEntidade, String[] pColecaoNmAtributos) {
		int i = 0;
		String retorno = "";
		String nmAtributo = "";

		for (i = 0; i < pColecaoNmAtributos.length; i++) {
			nmAtributo = pColecaoNmAtributos[i];

			//para fazer o GROUP BY
			retorno = retorno + pNmEntidade + "." + nmAtributo + ",";
		}

		//retira a virgula final
		retorno = retorno.substring(0, retorno.length() - 1);

		return retorno;
	}

	/**
	 * Retorna o SQL de datas para Vigencia numa data de comparacao
	 *
	 * @param pNmEntidade
	 * @param pNmColSequencial
	 * @param pChaveTuplaComparacaoSemSequencial
	 * @param pChaveGroupBy
	 * @param pDataComparacao
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected static String getSQLDataVigente(
		String pNmEntidade,
		String pNmColSequencial,
		String pChaveTuplaComparacaoSemSequencial,
		String pChaveGroupBy,
		String pDataComparacao,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		pDataComparacao = "'" + pDataComparacao + "'";

		String sqlClausulaVigenciaAtual =
			"( ( "
				+ pDataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ pDataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		String sqlFinal =
			"( ("
				+ pChaveTuplaComparacaoSemSequencial
				+ ", "
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")\n IN \n( SELECT "
				+ pChaveTuplaComparacaoSemSequencial
				+ ", MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")"
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlClausulaVigenciaAtual
				+ "\n GROUP BY "
				+ pChaveGroupBy
				+ ")\n OR " //para o caso do proprio registro retornar nulo em LEFT OUTER JOIN
				+ nmColDtInicioVigencia
				+ " IS NULL)";

		return sqlFinal;
	}

	protected static String getSQLDataVigenteSemDesativado(
			String pNmEntidade,
			String pNmColSequencial,
			String pChaveTuplaComparacaoSemSequencial,
			String pChaveGroupBy,
			String pDataComparacao,
			String pNmColDtInicioVigencia,
			String pNmColDtFimVigencia) {
			String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
			String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
			pDataComparacao = "'" + pDataComparacao + "'";

			String sqlClausulaVigenciaAtual =
				"( ( "
					+ pDataComparacao
					+ " BETWEEN "
					+ nmColDtInicioVigencia
					+ "\n AND "
					+ nmColDtFimVigencia
					+ "\n ) OR ( "
					+ nmColDtInicioVigencia
					+ " <= "
					+ pDataComparacao
					+ " "
					+ "\n AND "
					+ nmColDtFimVigencia
					+ " IS NULL"
					+ ") )";

			String sqlFinal =
				"("
					+ pChaveTuplaComparacaoSemSequencial
					+ ", "
					+ pNmEntidade
					+ "."
					+ pNmColSequencial
					+ ")\n IN \n( SELECT "
					+ pChaveTuplaComparacaoSemSequencial
					+ ", MAX("
					+ pNmEntidade
					+ "."
					+ pNmColSequencial
					+ ")"
					+ "\n FROM "
					+ pNmEntidade
					+ "\n WHERE "
					+ sqlClausulaVigenciaAtual
					+ "\n GROUP BY "
					+ pChaveGroupBy
					+ " )";

			String sqlSemDesativados =
				"("
					+ pChaveTuplaComparacaoSemSequencial
					+ ")\n NOT IN \n( SELECT "
					+ pChaveTuplaComparacaoSemSequencial
					+ "\n FROM "
					+ pNmEntidade
					+ "\n WHERE "
					+ nmColDtFimVigencia
					+ " = '1900-01-01'"
					+ "\n GROUP BY "
					+ pChaveGroupBy
					+ " )";

			sqlFinal = "("
				+ sqlFinal
				+ "\n AND "
				+ sqlSemDesativados
				+ ")";

			return sqlFinal;
		}

	/**
	 * -
	 *
	 * @param pNmEntidade
	 * @param pChave
	 * @param pValorChave
	 * @param pDataComparacao
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getSQLDataVigenteSemSobreposicao(
		String pNmEntidade,
		String pChave,
		Object pValorChave,
		String pDataComparacao,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		pDataComparacao = "'" + pDataComparacao + "'";

		String sqlClausulaVigenciaAtual =
			"( ( "
				+ pDataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ pDataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") ";

		if (pValorChave != null && pChave != null) {
			String pValor = "";

			if (pValorChave instanceof String) {
				pValor = "'" + pValorChave + "' ";
			} else {
				pValor = pValorChave + "";
			}

			sqlClausulaVigenciaAtual =
				sqlClausulaVigenciaAtual
					+ "\n AND "
					+ pNmEntidade
					+ "."
					+ pChave
					+ " = "
					+ pValor;
		}

		sqlClausulaVigenciaAtual = sqlClausulaVigenciaAtual + " ) ";

		return sqlClausulaVigenciaAtual;
	}

	/**
	 * Retorna o SQL de datas para Vigencia Passada numa data de comparacao
	 *
	 * @param pNmEntidade
	 * @param pNmColSequencial
	 * @param pChaveTuplaComparacaoSemSequencial
	 * @param pChaveGroupBy
	 * @param pDataComparacao
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getSQLDataAnterior(
		String pNmEntidade,
		String pNmColSequencial,
		String pChaveTuplaComparacaoSemSequencial,
		String pChaveGroupBy,
		String pDataComparacao,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		pDataComparacao = "'" + pDataComparacao + "'";

		//qualquer registro com data passada
		String sqlClausulaVigenciaPassada =
			"( " + nmColDtFimVigencia + " IS NOT NULL AND " + nmColDtFimVigencia + " < " + pDataComparacao + " )";

		//REGISTRO VIGENTE PRA FAZER O 'NOT'
		String sqlClausulaVigenciaAtual =
			"( ( "
				+ pDataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ pDataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		String sqlCondicaoVigenteMenorSequencialTotal =
			"("
				+ pChaveTuplaComparacaoSemSequencial
				+ ", "
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")\n NOT IN \n( SELECT "
				+ pChaveTuplaComparacaoSemSequencial
				+ ", MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")"
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlClausulaVigenciaAtual
				+ "\n GROUP BY "
				+ pChaveGroupBy
				+ " )";

		String sqlFinal = "(" + sqlClausulaVigenciaPassada + " \nOR " + sqlCondicaoVigenteMenorSequencialTotal + " )";

		return sqlFinal;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes, ou seja, 
	 * o maior sequencial na data atual 
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChave
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtual(
		String pNmEntidade,
		String[] pColecaoNmAtributosChave,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChave, pNmColSequencial);

		return this.getSQLDataVigente(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencial,
			chavePrimariaSemSequencial,
			dtHoje,
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes
	 * no caso em que os atributos da chave lógica podem ser nulos.
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChave - Chave lógica
	 * @param pColecaoTpAtributosChave - Tipos dos atributos da chave lógica
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtualSeChaveNula(
		String pNmEntidade,
		String[] pColecaoNmAtributosChave,
		Class[] pColecaoTpAtributosChave,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChave, pNmColSequencial);

		String chavePrimariaSemSequencialCoalesce =
				this.getAtributosChaveFormatadoSQLSeChaveNula(pNmEntidade, pColecaoNmAtributosChave,pColecaoTpAtributosChave, pNmColSequencial);

		return this.getSQLDataVigente(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencialCoalesce,
			chavePrimariaSemSequencial,
			dtHoje,
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes numa data específica
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pData
	 *
	 * @return
	 */
	protected static String getClausulaWhereVigenciaPorData(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pData) {

		String chavePrimariaSemSequencial =
			getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		return getSQLDataVigente(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencial,
			chavePrimariaSemSequencial,
			pData + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * -
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pData
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPorDataSeChaveNula(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		Class[] pColecaoTpAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pData) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		String chavePrimariaSemSequencialCoalesce =
			this.getAtributosChaveFormatadoSQLSeChaveNula(pNmEntidade, pColecaoNmAtributosChavePrimaria,pColecaoTpAtributosChavePrimaria, pNmColSequencial);

		return this.getSQLDataVigente(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencialCoalesce,
			chavePrimariaSemSequencial,
			pData + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes numa data específica Usado nos
	 * casos em que não há um atributo específico que defina a distinção entre os registros DEFAULT: Usar o atributo de DESCRIÇÃO
	 * pColecaoNmAtributosDistincao é a colecao de atributos que distinguem um registro nesse caso,  desconsiderando o SEQUENCIAL
	 * Ex.: VOClassificacaoIngresso
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosDistincao
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pDataVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaSemCodigoDistincao(
		String pNmEntidade,
		String[] pColecaoNmAtributosDistincao,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pDataVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		if (pDataVigencia == null) {
			pDataVigencia = BibliotecaFuncoesDataHora.getDataHoje();
		}

		String chaveGroupBy = this.getAtributosFormatadoSQL(pNmEntidade, pColecaoNmAtributosDistincao);

		return this.getSQLDataVigente(
			pNmEntidade,
			pNmColSequencial,
			chaveGroupBy,
			chaveGroupBy,
			pDataVigencia + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros de vigência passada
	 *
	 * @param pNmEntidade
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPassada(String pNmEntidade, String pNmColDtFimVigencia) {
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dtHoje = "'" + BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString() + "'";

		String sqlClausulaVigenciaPassada =
			"( " + nmColDtFimVigencia + " IS NOT NULL AND " + nmColDtFimVigencia + " < " + dtHoje + " )";

		return sqlClausulaVigenciaPassada;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros de vigência passada Depreciada por
	 * necessitar da DataInicioVigencia para a consulta
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 *
	 * @deprecated
	 */
	@Deprecated
	protected String getClausulaWhereVigenciaPassada(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtFimVigencia) {
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dtHoje = "'" + BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString() + "'";

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		//qualquer registro com data passada
		String sqlClausulaVigenciaPassada =
			"( " + nmColDtFimVigencia + " IS NOT NULL AND " + nmColDtFimVigencia + " < " + dtHoje + " )";

		//registro "vigente" , com MAIOR SEQUENCIAL
		String sqlCondicaoDataMaiorSequencial =
			"( " + nmColDtFimVigencia + " IS NULL OR " + nmColDtFimVigencia + " >= " + dtHoje + " )";

		//possui menor sequencial, mas com a dataFinalVigencia = HOJE
		String sqlCondicaoVigenteMenorSequencialTotal =
			"("
				+ chavePrimariaSemSequencial
				+ ", "
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")\n NOT IN \n( SELECT "
				+ chavePrimariaSemSequencial
				+ ", MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")"
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlCondicaoDataMaiorSequencial
				+ "\n GROUP BY "
				+ chavePrimariaSemSequencial
				+ " )";

		String sqlFinal = "(" + sqlClausulaVigenciaPassada + " \nOR " + sqlCondicaoVigenteMenorSequencialTotal + " )";

		return sqlFinal;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros de vigência passada
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPassada(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		return this.getSQLDataAnterior(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencial,
			chavePrimariaSemSequencial,
			dtHoje + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * -
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPassadaSeChaveNula(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		Class[] pColecaoTpAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		String chavePrimariaSemSequencialCoalesce =
			this.getAtributosChaveFormatadoSQLSeChaveNula(pNmEntidade, pColecaoNmAtributosChavePrimaria,pColecaoTpAtributosChavePrimaria, pNmColSequencial);

		return this.getSQLDataAnterior(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencialCoalesce,
			chavePrimariaSemSequencial,
			dtHoje + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}


	/**
	 * -
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pData
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPassadaPorDataSeChaveNula(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		Class[] pColecaoTpAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pData) {

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		String chavePrimariaSemSequencialCoalesce =
			this.getAtributosChaveFormatadoSQLSeChaveNula(pNmEntidade, pColecaoNmAtributosChavePrimaria,pColecaoTpAtributosChavePrimaria, pNmColSequencial);

		return this.getSQLDataAnterior(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencialCoalesce,
			chavePrimariaSemSequencial,
			pData + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros de vigência futura
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaFutura(String pNmEntidade, String pNmColDtInicioVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String dtHoje = "'" + BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString() + "'";

		String sqlClausulaVigenciaFutura = "( " + nmColDtInicioVigencia + " > " + dtHoje + " )";

		return sqlClausulaVigenciaFutura;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros não vigentes
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereNaoVigentes(String pNmEntidade, String pNmColDtInicioVigencia, String pNmColDtFimVigencia) {
		String sqlClausulaNaoVigentes =
			" ( \n"
				+ this.getClausulaWhereVigenciaPassada(pNmEntidade, pNmColDtFimVigencia)
				+ "\n  OR "
				+ this.getClausulaWhereVigenciaFutura(pNmEntidade, pNmColDtInicioVigencia)
				+ "\n )";

		return sqlClausulaNaoVigentes;
	}


	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros não vigentes
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereNaoVigentes(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String sqlClausulaNaoVigentes =
			" ( \n"
				+ this.getClausulaWhereVigenciaPassada(
					pNmEntidade,
					pColecaoNmAtributosChavePrimaria,
					pNmColSequencial,
					pNmColDtInicioVigencia,
					pNmColDtFimVigencia)
				+ "\n  OR "
				+ this.getClausulaWhereVigenciaFutura(pNmEntidade, pNmColDtInicioVigencia)
				+ "\n )";

		return sqlClausulaNaoVigentes;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes no intervalo de datas a ser
	 * preenchido
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pDtInicioVigencia
	 * @param pDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaIntervalo(
		String pNmEntidade,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pDtInicioVigencia,
		Date pDtFimVigencia) {
		String dtInicio = "";
		String dtFim = "";

		if (pDtInicioVigencia != null) {
			dtInicio = pDtInicioVigencia + "";
		}

		if (pDtFimVigencia != null) {
			dtFim = pDtFimVigencia + "";
		}

		return getClausulaWhereVigenciaIntervalo(
				pNmEntidade,
				pNmColDtInicioVigencia,
				pNmColDtFimVigencia,
				dtInicio,
				dtFim);
	}

	protected String getClausulaWhereVigenciaIntervalo(
			String pNmEntidade,
			String pNmColDtInicioVigencia,
			String pNmColDtFimVigencia,
			String pDtInicioVigencia,
			String pDtFimVigencia) {
			String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
			String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
			String sqlClausulaVigenciaIntervalo = "";
			String dtInicio = "";
			String dtFim = "";


			if (pDtInicioVigencia != null) {
				dtInicio = "'" + pDtInicioVigencia + "'";
			}

			if (pDtFimVigencia != null) {
				dtFim = "'" + pDtFimVigencia + "'";
			}

			if ((pDtInicioVigencia != null) && (pDtFimVigencia != null)) {
				sqlClausulaVigenciaIntervalo =
					"\n("
						+ "\n( ( "
						+ nmColDtInicioVigencia
						+ " BETWEEN "
						+ dtInicio
						+ " AND "
						+ dtFim
						+ "\n     ) OR ( "
						+ nmColDtFimVigencia
						+ " BETWEEN "
						+ dtInicio
						+ " AND "
						+ dtFim
						+ "\n) )"
						+ "\nOR"
						+ "\n( "
						+ "\n ( "
						+ "\n   ("
						+ dtInicio
						+ " BETWEEN "
						+ nmColDtInicioVigencia
						+ " AND "
						+ nmColDtFimVigencia
						+ "\n     ) OR ( "
						+ dtInicio
						+ " >= "
						+ nmColDtInicioVigencia
						+ " AND "
						+ nmColDtFimVigencia
						+ " IS NULL"
						+ "\n   ) "
						+ "\n ) OR ( "
						+ "\n   ("
						+ dtFim
						+ " BETWEEN "
						+ nmColDtInicioVigencia
						+ " AND "
						+ nmColDtFimVigencia
						+ "\n     ) OR ( "
						+ dtFim
						+ " >= "
						+ nmColDtInicioVigencia
						+ " AND "
						+ nmColDtFimVigencia
						+ " IS NULL"
						+ "\n   ) "
						+ "\n )"
						+ "\n)"
						+ "\n)";
			} else if (pDtInicioVigencia != null) {
				sqlClausulaVigenciaIntervalo =
					"( " + nmColDtFimVigencia + " >= " + dtInicio + " OR " + nmColDtFimVigencia + " IS NULL )";
			} else if (pDtFimVigencia != null) {
				sqlClausulaVigenciaIntervalo = nmColDtInicioVigencia + " <= " + dtFim;
			}

			return sqlClausulaVigenciaIntervalo;
		}

	protected String getClausulaWhereNaoVigenteIntervalo(
			String pNmEntidade,
			String pNmColDtInicioVigencia,
			String pNmColDtFimVigencia,
			String pNmColDesativada,
			String pDtInicioVigencia,
			String pDtFimVigencia) {
			String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
			String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
			String nmColDesativacao = pNmEntidade + "." + pNmColDesativada;
			String sqlClausulaVigenciaIntervalo = "";
			String dtInicio = "";
			String dtFim = "";

			if (pDtInicioVigencia != null) {
				dtInicio = "'" + pDtInicioVigencia + "'";
			}

			if (pDtFimVigencia != null) {
				dtFim = "'" + pDtFimVigencia + "'";
			}

			if ((pDtInicioVigencia != null) && (pDtFimVigencia != null)) {
				sqlClausulaVigenciaIntervalo =
					"\n(( "
						+ nmColDtFimVigencia
						+ " < "
						+ dtInicio
						+ ") OR ("
						+ nmColDtInicioVigencia
						+ " > "
						+ dtFim
						+ ") OR ("
						+ nmColDesativacao
						+ " IS NOT NULL))\n";


			} else if (pDtInicioVigencia != null) {
				sqlClausulaVigenciaIntervalo =
					"(( " + nmColDtFimVigencia + " < " + dtInicio + " AND " + nmColDtFimVigencia + " IS NOT NULL) OR (" + nmColDesativacao + " IS NOT NULL))";
			} else if (pDtFimVigencia != null) {
				sqlClausulaVigenciaIntervalo =
					"(( " + nmColDtInicioVigencia + " > " + dtFim + " AND " + nmColDtInicioVigencia + " IS NOT NULL)  OR (" + nmColDesativacao + " IS NOT NULL))";
			}

			return sqlClausulaVigenciaIntervalo;
		}

	protected String getClausulaWhereNaoVigentePorData(
			String pNmEntidade,
			String pNmColDtInicioVigencia,
			String pNmColDtFimVigencia,
			Date pData) {
			String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
			String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
			String dataComparacao = "'" + pData +  "'";

			String sqlClausulaNaoVigente =
				"(( " + nmColDtFimVigencia + " < " + dataComparacao + " AND " + nmColDtFimVigencia + " IS NOT NULL) " +
				"\n OR ( " + nmColDtInicioVigencia + " > " + dataComparacao + " AND " + nmColDtInicioVigencia + " IS NOT NULL ))";

			return sqlClausulaNaoVigente;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes no intervalo de datas a ser preenchido.
	 * Foi criado esse novo método, pois o anterior não esta retornando os valores corretos.
	 *
	 * MODIFICAÇÃO PARA:
	 * Metódo responsável por montar uma clausula onde o SQL retorna apenas os registros vigentes no intervalo de datas passado.
	 * Se passar a DtInicio e DtFim, retornará os registros vigentes dentro deste intervalo.
	 * Se passado apenas a DtInicio, retornará os vigentes a partir daquela data.
	 * Se passado apenas a DtFim, retornará os vigentes até aquela data
	 * Se não passar nenhuma das datas, retorna os vigentes até a data atual.
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pDtInicioVigencia
	 * @param pDtFimVigencia
	 *
	 * @return
	 */
	protected String getNovaClausulaWhereVigenciaIntervalo(
		String pNmEntidade,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pDtInicioVigencia,
		Date pDtFimVigencia) {

		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String dtInicio = "";
		String dtFim = "";
		String dtHoje = "'" + BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString() + "'";

		String sqlClausulaVigenciaIntervalo = "";

		if (pDtInicioVigencia != null) {
			dtInicio = "'" + pDtInicioVigencia + "'";
		}

		if (pDtFimVigencia != null) {
			dtFim = "'" + pDtFimVigencia + "'";
		}else{
			dtFim = dtHoje;
		}

		if (!dtInicio.equals("") && !dtFim.equals("")){
			sqlClausulaVigenciaIntervalo =
				"\n("
					+ "\n("
					+ dtFim
					+ " BETWEEN "
					+ nmColDtInicioVigencia
					+ " AND "
					+ nmColDtFimVigencia
					+ ")"
					+ "\n OR "
					+ "\n("
					+ "\n("
					+ dtInicio
					+ " >= "
					+ nmColDtInicioVigencia
					+ " AND "
					+ nmColDtFimVigencia
					+ " IS NULL)"
					+ "\n AND "
					+ "\n("
					+ dtFim
					+ " >= "
					+ nmColDtInicioVigencia
					+ " AND "
					+ nmColDtFimVigencia
					+ " IS NULL)"
					+ "\n) "
					+ "\n)";
		} else if (!dtInicio.equals("")) {
			sqlClausulaVigenciaIntervalo =
				"( " + nmColDtFimVigencia + " >= " + dtInicio + " OR " + nmColDtFimVigencia + " IS NULL )";
		} else if (!dtFim.equals("")) {
			sqlClausulaVigenciaIntervalo = nmColDtInicioVigencia + " <= " + dtFim;
		}

		return sqlClausulaVigenciaIntervalo;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes no intervalo de datas a ser
	 * preenchido
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 * @param pDtInicioVigencia
	 * @param pDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaIntervaloOuAtual(
		String pNmEntidade,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pDtInicioVigencia,
		Date pDtFimVigencia) {
		String sqlClausulaVigenciaIntervaloOuAtual = "";

		if ((pDtInicioVigencia != null) || (pDtFimVigencia != null)) {
			sqlClausulaVigenciaIntervaloOuAtual =
				this.getClausulaWhereVigenciaIntervalo(
					pNmEntidade,
					pNmColDtInicioVigencia,
					pNmColDtFimVigencia,
					pDtInicioVigencia,
					pDtFimVigencia);
		} else {
			//caso nenhuma data tenha sido preenchida, retorna apenas os registros VIGENTES
			sqlClausulaVigenciaIntervaloOuAtual =
				this.getClausulaWhereVigenciaAtual(pNmEntidade, pNmColDtInicioVigencia, pNmColDtFimVigencia);
		}

		return sqlClausulaVigenciaIntervaloOuAtual;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes
	 * passando uma chave primária composta (sequencial + código)
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChave
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtualSequencialComposto(
		String pNmEntidade,
		String[] pColecaoNmAtributosChaveLogica,
		String[] pNmColSequencialComposto,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChaveLogica, pNmColSequencialComposto);

		return this.getSQLDataVigenteSequencialComposto(
			pNmEntidade,
			pNmColSequencialComposto,
			chavePrimariaSemSequencial,
			chavePrimariaSemSequencial,
			dtHoje,
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia);

	}

	/**
	 * Retorna uma String formatada para realizar o GROUP BY na consulta do registros de vigencia
	 * Esta String deve conter os atributos do registro que será responsável pela diferenciação
	 * no projeto de vigência(Chave Lógica), com excecao do NmAtributoSequencial
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 *
	 * @return
	 */
	private String getAtributosChaveFormatadoSQL(
		String pNmEntidade,
		String[] pColecaoNmAtributosChaveLogica,
		String[] pNmColSequencialComposto) {
		int i = 0;
		String retorno = "";
		String nmAtributo = "";
		String nmColSequencial = "";
		boolean incluirNmSequencial = false;

		for (i = 0; i < pColecaoNmAtributosChaveLogica.length; i++) {
			nmAtributo = pColecaoNmAtributosChaveLogica[i];

			for (int j = 0; j < pNmColSequencialComposto.length; j++) {
				nmColSequencial = pNmColSequencialComposto[j];

				// Verifica se os atributos passados como chave lógica são diferentes
				// dos atributos passados como sequencial
				if (!nmColSequencial.equals(nmAtributo)) {
					incluirNmSequencial = true;
				}
			}

			if(incluirNmSequencial) {
				retorno = retorno + pNmEntidade + "." + nmAtributo + ",";
			}
		}

		//retira a virgula final
		retorno = retorno.substring(0, retorno.length() - 1);

		return retorno;
	}


	/**
	 * Retorna o SQL de datas para Vigência numa data de comparacao
	 *
	 * @param pNmEntidade
	 * @param pNmColSequencial
	 * @param pChaveTuplaComparacaoSemSequencial
	 * @param pChaveGroupBy
	 * @param pDataComparacao
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getSQLDataVigenteSequencialComposto(
		String pNmEntidade,
		String[] pNmColSequencialComposto,
		String pChaveTuplaComparacaoSemSequencial,
		String pChaveGroupBy,
		String pDataComparacao,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia) {

		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		pDataComparacao = "'" + pDataComparacao + "'";

		String sqlClausulaVigenciaAtual =
			"( ( "
				+ pDataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ pDataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		String nmColSequencial = "";
		String nmColMaiorSequencial = "";

		for (int i = 0; i < pNmColSequencialComposto.length; i++) {
			nmColSequencial =
				nmColSequencial
				+ pNmEntidade
				+ "."
				+ pNmColSequencialComposto[i]
				+ ",";

			//pega o maior valor do sequencial passado (sequencial composto)
			nmColMaiorSequencial =
				nmColMaiorSequencial
				+ " MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencialComposto[i]
				+ ")"
				+ ",";
		}
		//retira a virgula final
		nmColSequencial = nmColSequencial.substring(0, nmColSequencial.length() - 1);
		nmColMaiorSequencial = nmColMaiorSequencial.substring(0, nmColMaiorSequencial.length() - 1);

		String sqlFinal =
			"("
				+ pChaveTuplaComparacaoSemSequencial
				+ ", "
				+ nmColSequencial
				+ ")\n IN \n( SELECT "
				+ pChaveTuplaComparacaoSemSequencial
				+ ", "
				+ nmColMaiorSequencial
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlClausulaVigenciaAtual
				+ "\n GROUP BY "
				+ pChaveGroupBy
				+ " )";

		return sqlFinal;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes na data em que foi passada
	 *
	 * @param pNmEntidade
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPorData(
		String pNmEntidade,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		Date pData) {

		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dataComparacao = "'" + pData +  "'";

		String sqlClausulaVigenciaAtual =
			"( ( "
				+ dataComparacao +  ""
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ dataComparacao + ""
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		return sqlClausulaVigenciaAtual;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros vigentes a partir da data que foi passada
	 *
	 * @param pNmEntidade	Entidade
	 * @param pNmColData	Coluna de referência para a data
	 * @param pData			Data a partir do qual os registros serão retornados
	 *
	 * @return
	 */
	protected String getClausulaAPartirDeUmaData(
		String pNmEntidade,
		String pNmColData,
		Date pData){

		String nmColData = pNmEntidade + "." + pNmColData;
		String dataComparacao = "'" + pData +  "'";

		String sqlClausulaVigencia =
			"\n( "
			+ nmColData
			+ " >= "
			+ dataComparacao
			+ ")\n";

		return sqlClausulaVigencia;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros vigentes na data de início informada
	 *
	 * @param pNmEntidade	Entidade
	 * @param pNmColData	Coluna de referência para a data
	 * @param pData			Data a partir do qual os registros serão retornados
	 *
	 * @return
	 */
	protected String getClausulaWhereVigentesAPartirData(
		String pNmEntidade,
		String pNmColDataFim,
		Date pData) {

		String nmColDataFim = pNmEntidade + "." + pNmColDataFim;
		String dataComparacao = "'" + pData +  "'";

		String sqlClausulaVigencia =
			"\n("
			+ nmColDataFim
			+ " >= "
			+ dataComparacao
			+ " OR "
			+ nmColDataFim
			+ " IS NULL"
			+")\n";

		return sqlClausulaVigencia;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros vigentes na data de fim informada
	 *
	 * @param pNmEntidade	Entidade
	 * @param pNmColData	Coluna de referência para a data
	 * @param pData			Data a partir do qual os registros serão retornados
	 *
	 * @return
	 */
	protected String getClausulaWhereVigentesAteData(
		String pNmEntidade,
		String pNmColDataInicio,
		Date pData){

		String nmColDataInicio = pNmEntidade + "." + pNmColDataInicio;
		String dataComparacao = "'" + pData +  "'";

		String sqlClausulaVigencia =
			"\n("
			+ nmColDataInicio
			+ " <= "
			+ dataComparacao
			+ ")\n";

		return sqlClausulaVigencia;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros vigentes a partir da data ou timestamp que foi passada
	 *
	 * @param pNmEntidade	Entidade
	 * @param pNmColData	Coluna de referência para a data
	 * @param pData			Data a partir do qual os registros serão retornados
	 *
	 * @return
	 */
	protected String getClausulaRetroageMesesNaDataAtual(
		String pNmEntidade,
		String pNmColData,
		Short pQuantidadeMeses,
		boolean pIsColunaComparacaoTimestamp){

		String nmColData = pNmEntidade + "." + pNmColData;
		String indicadorTimestamp = "";

		if(pIsColunaComparacaoTimestamp){
			indicadorTimestamp = " CURRENT TIMESTAMP ";
		}else{
			indicadorTimestamp = " CURRENT DATE ";
		}

		String sqlClausulaVigencia =
			"\n "
			+ nmColData
			+ " >= "
			+ " ( " + indicadorTimestamp
			+ " - "
			+ pQuantidadeMeses
			+ " MONTHS"
			+ " )\n";

		return sqlClausulaVigencia;
	}

	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros desativados.
	 *
	 * @param pNmEntidade					Entidade
	 * @param pNmColDataInicioVigencia		Nome da coluna que faz referência a data de início de vigência da entidade
	 * @param pNmColDataFimVigencia			Nome da coluna que faz referência a data de fim de vigência da entidade
	 *
	 * @return
	 */
	protected String getClausulaWhereDesativacaoRegistros(
		String pNmEntidade,
		String pNmColDataInicioVigencia,
		String pNmColDataFimVigencia,
		boolean pRetornarRegistrosDesativados){

		String sqlClausulaRegDesativados = "";
		String nmColDtInicio = pNmEntidade + "." + pNmColDataInicioVigencia;
		String nmColDtFim = pNmEntidade + "." + pNmColDataFimVigencia;
		String dtDesativacao = "'1900-01-01'";

		if(pRetornarRegistrosDesativados){
			sqlClausulaRegDesativados =
				"\n( "
				+ nmColDtInicio
				+ " = "
				+ dtDesativacao
				+ " AND "
				+ nmColDtFim
				+ " = "
				+ dtDesativacao
				+ " )\n";
		}else{
			sqlClausulaRegDesativados =
				"\n( ("
				+ nmColDtInicio
				+ " <> "
				+ dtDesativacao
				+ "\nAND "
				+ nmColDtFim
				+ " <> "
				+ dtDesativacao
				+ ")"
				+ "\nOR "
				+ nmColDtFim
				+ " IS NULL "
				+ " )\n";
		}
		return sqlClausulaRegDesativados;
	}

//	************************ Mudanças por conta do indicador de desativação do registro **********************************
	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros vigentes
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChave
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtualSemDesativados(
		String pNmEntidade,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		String pNmColInDesativado) {

		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String sqlClausulaVigenciaAtual =
			"( ( '"
				+ dtHoje
				+ "' BETWEEN "
				+ pNmColDtInicioVigencia
				+ "\n AND "
				+ pNmColDtFimVigencia
				+ "\n ) OR ( "
				+ pNmColDtInicioVigencia
				+ " <= '"
				+ dtHoje
				+ "' "
				+ "\n AND "
				+ pNmColDtFimVigencia
				+ " IS NULL"
				+ "))";

		String sqlRemocaoRegistrosDesativados =
			"("
			+ pNmColInDesativado
			+ " IS NULL)\n";

		String sqlFinal =
			"("
				+ sqlClausulaVigenciaAtual
				+ "\n AND "
				+ sqlRemocaoRegistrosDesativados
				+ ")";

		return sqlFinal;
	}

	/**
	 * Retorno cláusula Where retirando registros desativados
	 */
	protected String getClausulaWhereSemDesativados(String pNmColunaDHDesativacao) {

		StringBuffer sql = new StringBuffer("");

		sql.append("(");
		sql.append(pNmColunaDHDesativacao);
		sql.append(" IS NULL)");

		return sql.toString();
	}
	
	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne todos os registros desativados.
	 *
	 * @param pNmEntidade					Entidade
	 * @param pNmColDataInicioVigencia		Nome da coluna que faz referência a data de início de vigência da entidade
	 * @param pNmColDataFimVigencia			Nome da coluna que faz referência a data de fim de vigência da entidade
	 *
	 * @return
	 */
	protected String getClausulaWhereIndicadorDesativacao(
		String pNmEntidade,
		String pNmColInDesativado,
		boolean pRetornarRegistrosDesativados){

		String sqlClausulaRegDesativados = "";
		String nmColInDesativado = pNmEntidade + "." + pNmColInDesativado;

		if(pRetornarRegistrosDesativados){
			sqlClausulaRegDesativados =
				"\n( "
				+ nmColInDesativado
				+ " = "
				+ "'"
				+ ConstantesGFU.CD_VERDADEIRO
				+ "'"
				+ " )\n";
		}else{
			sqlClausulaRegDesativados =
				"\n( "
				+ nmColInDesativado
				+ " = "
				+ "'"
				+ ConstantesGFU.CD_FALSO
				+ "'"
				+ " OR "
				+ nmColInDesativado
				+ " IS NULL"
				+ " )\n";
		}
		return sqlClausulaRegDesativados;
	}
	
	
	
	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros não vigentes e registros desativados
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereNaoVigentesDesativados(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		String pNmColDesativados) {
		String sqlClausulaNaoVigentes =
			" ( \n"
				+ this.getClausulaWhereVigenciaPassadaDesativados(
					pNmEntidade,
					pColecaoNmAtributosChavePrimaria,
					pNmColSequencial,
					pNmColDtInicioVigencia,
					pNmColDtFimVigencia, 
					pNmColDesativados)
				+ "\n  OR "
				+ this.getClausulaWhereVigenciaFutura(pNmEntidade, pNmColDtInicioVigencia)
				+ "\n )";

		return sqlClausulaNaoVigentes;
	}

	
	/**
	 * Metodo responsável por fazer com que a consulta SQL retorne apenas os registros de vigência passada
	 * e registros desativados
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChavePrimaria
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaPassadaDesativados(
		String pNmEntidade,
		String[] pColecaoNmAtributosChavePrimaria,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		String pNmColDesativados) {
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;

		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();

		String chavePrimariaSemSequencial =
			this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChavePrimaria, pNmColSequencial);

		return this.getSQLDataAnteriorDesativados(
			pNmEntidade,
			pNmColSequencial,
			chavePrimariaSemSequencial,
			chavePrimariaSemSequencial,
			dtHoje + "",
			pNmColDtInicioVigencia,
			pNmColDtFimVigencia,
			pNmColDesativados);
	}
	
	/**
	 * Retorna o SQL de datas para Vigencia Passada numa data de comparacao e registros desativados
	 *
	 * @param pNmEntidade
	 * @param pNmColSequencial
	 * @param pChaveTuplaComparacaoSemSequencial
	 * @param pChaveGroupBy
	 * @param pDataComparacao
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getSQLDataAnteriorDesativados(
		String pNmEntidade,
		String pNmColSequencial,
		String pChaveTuplaComparacaoSemSequencial,
		String pChaveGroupBy,
		String pDataComparacao,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		String pNmColDesativados) {
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		pDataComparacao = "'" + pDataComparacao + "'";

		//qualquer registro com data passada
		String sqlClausulaVigenciaPassada =
			"( " + nmColDtFimVigencia + " IS NOT NULL AND " + nmColDtFimVigencia + " < " + pDataComparacao + " )";

		//REGISTRO VIGENTE PRA FAZER O 'NOT'
		String sqlClausulaVigenciaAtual =
			"( ( "
				+ pDataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ pDataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		String sqlCondicaoVigenteMenorSequencialTotal =
			"("
				+ pChaveTuplaComparacaoSemSequencial
				+ ", "
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")\n NOT IN \n( SELECT "
				+ pChaveTuplaComparacaoSemSequencial
				+ ", MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")"
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlClausulaVigenciaAtual
				+ "\n GROUP BY "
				+ pChaveGroupBy
				+ " )";

		String sqlFinal = "(" + sqlClausulaVigenciaPassada + " \nOR " + sqlCondicaoVigenteMenorSequencialTotal + 
						  " OR " + pNmColDesativados + " IS NOT NULL " + " )";

		return sqlFinal;
	}
	
	/**
	 * Retorna registros vigentes de maior sequencial na data atual E permite passagem de filtro na clausula
	 * Melhor performance
	 *
	 * @param pNmEntidade
	 * @param pColecaoNmAtributosChave
	 * @param pNmColSequencial
	 * @param pNmColDtInicioVigencia
	 * @param pNmColDtFimVigencia
	 *
	 * @return
	 */
	protected String getClausulaWhereVigenciaAtualComFiltro(
		String pNmEntidade,
		String[] pColecaoNmAtributosChave,
		String pNmColSequencial,
		String pNmColDtInicioVigencia,
		String pNmColDtFimVigencia,
		String pFiltroAdicional) {
		
		String nmColDtInicioVigencia = pNmEntidade + "." + pNmColDtInicioVigencia;
		String nmColDtFimVigencia = pNmEntidade + "." + pNmColDtFimVigencia;
		String dtHoje = BibliotecaFuncoesDataHora.getDataHojeSemHoras().toString();
		String chavePrimariaSemSequencial = this.getAtributosChaveFormatadoSQL(pNmEntidade, pColecaoNmAtributosChave, pNmColSequencial);

		String dataComparacao = "'" + dtHoje + "'";
		String filtroAdicional = "";
		if(pFiltroAdicional != null){
			if(!pFiltroAdicional.equals(""))
				filtroAdicional =  "\n AND " + pFiltroAdicional;
		}	
		
		String sqlClausulaVigenciaAtual =
			"( ( "
				+ dataComparacao
				+ " BETWEEN "
				+ nmColDtInicioVigencia
				+ "\n AND "
				+ nmColDtFimVigencia
				+ "\n ) OR ( "
				+ nmColDtInicioVigencia
				+ " <= "
				+ dataComparacao
				+ " "
				+ "\n AND "
				+ nmColDtFimVigencia
				+ " IS NULL"
				+ ") )";

		String sqlFinal =
			"( ("
				+ chavePrimariaSemSequencial
				+ ", "
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")\n IN \n( SELECT "
				+ chavePrimariaSemSequencial
				+ ", MAX("
				+ pNmEntidade
				+ "."
				+ pNmColSequencial
				+ ")"
				+ "\n FROM "
				+ pNmEntidade
				+ "\n WHERE "
				+ sqlClausulaVigenciaAtual
				+ filtroAdicional
				+ "\n GROUP BY "
				+ chavePrimariaSemSequencial
				+ ")\n OR " //para o caso do proprio registro retornar nulo em LEFT OUTER JOIN
				+ nmColDtInicioVigencia
				+ " IS NULL)";

		return sqlFinal;
	}	
}