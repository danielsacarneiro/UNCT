<?php
//envia alertas dos editais
require ("alerta_edital.php");

require ("alerta_encaminhamento_SAD.php");
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste
require ("alerta_demanda_proposta_vencida.php");
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
require ("alerta_fim_prazo_NI_PA.php");
