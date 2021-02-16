<?php

/**
 * substitui os caracteres especiais na importacao da planilha
 * @param unknown $param
 * @return mixed
 */
function getStringImportacaoCaracterEspecial($param, $removerEspaco= false){
	$param = str_replace('“', '"', $param);
	$param = str_replace('”', '"', $param);
	$param = str_replace('–', '-', $param);
	$param = str_replace('?', '-', $param);
	if($removerEspaco){
		$param = str_replace(' ', '', $param);
		$param = str_replace(',', '.', $param);
	}
	
	/*UPDATE contrato SET
	 ct_contratada = replace(replace(replace(replace(ct_contratada,'“','"'),'”','"'),'–','-'), '?','-'),
	 ct_objeto = replace(replace(replace(replace(ct_objeto,'“','"'),'”','"'),'–','-'), '?','-'),
	 ct_gestor = replace(replace(replace(replace(ct_gestor,'“','"'),'”','"'),'–','-'), '?','-'),
	 ct_processo_lic = replace(replace(replace(replace(ct_processo_lic,'“','"'),'”','"'),'–','-'), '?','-')*/
	
	return  $param;
}
?>