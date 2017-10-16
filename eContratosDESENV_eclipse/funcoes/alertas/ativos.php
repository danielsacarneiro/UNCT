<?php
//alerta 1
require ("alerta_encaminhamento_SAD.php");

//alerta 2
//envia alertas das demandas que devem ser analisadas pois ja tem suas proposta de precos vencida, tornando possivel o calculo do reajuste
require ("alerta_demanda_proposta_vencida.php");

//alerta 3
//envia alertas dos PAAPs cujas analises tiveram prazo vencido
require ("alerta_fim_prazo_NI_PA.php");
