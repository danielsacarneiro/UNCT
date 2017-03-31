<?php
/**
 * ValidaCPFCNPJ valida e formata CPF e CNPJ
 *
 * Exemplo de uso:
 * $cpf_cnpj  = new ValidaCPFCNPJ('71569042000196');
 * $formatado = $cpf_cnpj->formata(); // 71.569.042/0001-96
 * $valida    = $cpf_cnpj->valida(); // True -> V�lido
 *
 * @package  valida-cpf-cnpj
 * @author   Luiz Ot�vio Miranda <contato@todoespacoonline.com/w>
 * @version  v1.4
 * @access   public
 * @see      http://www.todoespacoonline.com/w/
 */
class documentoPessoa
{
	static $tpDocCPF = "03";
	static $tpDocCNPJ = "02";
	static $tpDocRG = "01";
	
	/** 
	 * Configura o valor (Construtor)
	 * 
	 * Remove caracteres inv�lidos do CPF ou CNPJ
	 * 
	 * @param string $valor - O CPF ou CNPJ
	 */
	function __construct ( $valor = null ) {
		// Deixa apenas n�meros no valor
		$this->valor = preg_replace( '/[^0-9]/', '', $valor );
		
		// Garante que o valor � uma string
		$this->valor = (string)$this->valor;
	}
 
	function getNumDoc(){
		return $this->valor;
	}
	/**
	 * Verifica se � CPF ou CNPJ
	 * 
	 * Se for CPF tem 11 caracteres, CNPJ tem 14
	 * 
	 * @access protected
	 * @return string CPF, CNPJ ou false
	 */
	function verifica_cpf_cnpj () {
		// Verifica CPF
		if ( strlen( $this->valor ) === 11 ) {
			return self::$tpDocCPF;
		} 
		// Verifica CNPJ
		elseif ( strlen( $this->valor ) === 14 ) {
			return self::$tpDocCNPJ;
		} 
		// N�o retorna nada
		else {
			return false;
		}
	}
    
	/**
	 * Verifica se todos os n�meros s�o iguais
	 * 	 * 
	 * @access protected
	 * @return bool true para todos iguais, false para n�meros que podem ser v�lidos
	 */
    protected function verifica_igualdade() {
        // Todos os caracteres em um array
        $caracteres = str_split($this->valor );
        
        // Considera que todos os n�meros s�o iguais
        $todos_iguais = true;
        
        // Primeiro caractere
        $last_val = $caracteres[0];
        
        // Verifica todos os caracteres para detectar diferen�a
        foreach( $caracteres as $val ) {
            
            // Se o �ltimo valor for diferente do anterior, j� temos
            // um n�mero diferente no CPF ou CNPJ
            if ( $last_val != $val ) {
               $todos_iguais = false; 
            }
            
            // Grava o �ltimo n�mero checado
            $last_val = $val;
        }
        
        // Retorna true para todos os n�meros iguais
        // ou falso para todos os n�meros diferentes
        return $todos_iguais;
    }
 
	/**
	 * Multiplica d�gitos vezes posi��es
	 *
	 * @access protected
	 * @param  string    $digitos      Os digitos desejados
	 * @param  int       $posicoes     A posi��o que vai iniciar a regress�o
	 * @param  int       $soma_digitos A soma das multiplica��es entre posi��es e d�gitos
	 * @return int                     Os d�gitos enviados concatenados com o �ltimo d�gito
	 */
	protected function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
		// Faz a soma dos d�gitos com a posi��o
		// Ex. para 10 posi��es:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
			// Preenche a soma com o d�gito vezes a posi��o
			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
 
			// Subtrai 1 da posi��o
			$posicoes--;
 
			// Parte espec�fica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( $posicoes < 2 ) {
				// Retorno a posi��o para 9
				$posicoes = 9;
			}
		}
 
		// Captura o resto da divis�o entre $soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		$soma_digitos = $soma_digitos % 11;
 
		// Verifica se $soma_digitos � menor que 2
		if ( $soma_digitos < 2 ) {
			// $soma_digitos agora ser� zero
			$soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado � 11 menos $soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso d�gito procurado � 2
			$soma_digitos = 11 - $soma_digitos;
		}
 
		// Concatena mais um d�gito aos primeiro nove d�gitos
		// Ex.: 025462884 + 2 = 0254628842
		$cpf = $digitos . $soma_digitos;
 
		// Retorna
		return $cpf;
	}
 
	/**
	 * Valida CPF
	 *
	 * @author                Luiz Ot�vio Miranda <contato@todoespacoonline.com/w>
	 * @access protected
	 * @param  string    $cpf O CPF com ou sem pontos e tra�o
	 * @return bool           True para CPF correto - False para CPF incorreto
	 */
	protected function valida_cpf() {
		// Captura os 9 primeiros d�gitos do CPF
		// Ex.: 02546288423 = 025462884
		$digitos = substr($this->valor, 0, 9);
 
		// Faz o c�lculo dos 9 primeiros d�gitos do CPF para obter o primeiro d�gito
		$novo_cpf = $this->calc_digitos_posicoes( $digitos );
 
		// Faz o c�lculo dos 10 d�gitos do CPF para obter o �ltimo d�gito
		$novo_cpf = $this->calc_digitos_posicoes( $novo_cpf, 11 );
        
        // Verifica se todos os n�meros s�o iguais
        if ( $this->verifica_igualdade() ) {
            return false;
        }
 
		// Verifica se o novo CPF gerado � id�ntico ao CPF enviado
		if ( $novo_cpf === $this->valor ) {
			// CPF v�lido
			return true;
		} else {
			// CPF inv�lido
			return false;
		}
	}
 
	/**
	 * Valida CNPJ
	 *
	 * @author                  Luiz Ot�vio Miranda <contato@todoespacoonline.com/w>
	 * @access protected
	 * @param  string     $cnpj
	 * @return bool             true para CNPJ correto
	 */
	protected function valida_cnpj () {
		// O valor original
		$cnpj_original = $this->valor;
 
		// Captura os primeiros 12 n�meros do CNPJ
		$primeiros_numeros_cnpj = substr( $this->valor, 0, 12 );
 
		// Faz o primeiro c�lculo
		$primeiro_calculo = $this->calc_digitos_posicoes( $primeiros_numeros_cnpj, 5 );
 
		// O segundo c�lculo � a mesma coisa do primeiro, por�m, come�a na posi��o 6
		$segundo_calculo = $this->calc_digitos_posicoes( $primeiro_calculo, 6 );
 
		// Concatena o segundo d�gito ao CNPJ
		$cnpj = $segundo_calculo;
        
        // Verifica se todos os n�meros s�o iguais
        if ( $this->verifica_igualdade() ) {
            return false;
        }
 
		// Verifica se o CNPJ gerado � id�ntico ao enviado
		if ( $cnpj === $cnpj_original ) {
			return true;
		}
	}
 
	/**
	 * Valida
	 * 
	 * Valida o CPF ou CNPJ
	 * 
	 * @access public
	 * @return bool      True para v�lido, false para inv�lido
	 */
	public function valida () {
		// Valida CPF
		if ( $this->verifica_cpf_cnpj() === self::$tpDocCPF ) {
			// Retorna true para cpf v�lido
			return $this->valida_cpf();
		} 
		// Valida CNPJ
		elseif ( $this->verifica_cpf_cnpj() === self::$tpDocCNPJ ) {
			// Retorna true para CNPJ v�lido
			return $this->valida_cnpj();
		} 
		// N�o retorna nada
		else {
			return false;
		}
	}
 
	/**
	 * Formata CPF ou CNPJ
	 *
	 * @access public
	 * @return string  CPF ou CNPJ formatado
	 */
	public function formata() {
		// O valor formatado
		$formatado = false;
 
		// Valida CPF
		if ( $this->verifica_cpf_cnpj() === self::$tpDocCPF ) {
			// Verifica se o CPF � v�lido
			if ( $this->valida_cpf() ) {
				// Formata o CPF ###.###.###-##
				$formatado  = substr( $this->valor, 0, 3 ) . '.';
				$formatado .= substr( $this->valor, 3, 3 ) . '.';
				$formatado .= substr( $this->valor, 6, 3 ) . '-';
				$formatado .= substr( $this->valor, 9, 2 ) . '';
			}
			else {
				$formatado = $this->valor;
			}
		} 
		// Valida CNPJ
		elseif ( $this->verifica_cpf_cnpj() === self::$tpDocCNPJ) {
			// Verifica se o CPF � v�lido
			if ( $this->valida_cnpj() ) {
				// Formata o CNPJ ##.###.###/####-##
				$formatado  = substr( $this->valor,  0,  2 ) . '.';
				$formatado .= substr( $this->valor,  2,  3 ) . '.';
				$formatado .= substr( $this->valor,  5,  3 ) . '/';
				$formatado .= substr( $this->valor,  8,  4 ) . '-';
				$formatado .= substr( $this->valor, 12, 14 ) . '';
			}else {
				$formatado = $this->valor;
			}
		}
		else {
			$formatado = $this->valor;
		}
 
		// Retorna o valor 
		return $formatado;
	}
	
	static function getNumeroDocFormatado($num){
		$documento = new documentoPessoa($num);
		return $documento->formata(); 		
	}
	
	static function getNumeroDocSemMascara($num){
		$documento = new documentoPessoa($num);
		return $documento->getNumDoc();
	}
	
}