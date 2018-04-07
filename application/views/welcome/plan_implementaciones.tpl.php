<?php

/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$plan_implementacion = ($plan_cargado['id_reporte_estatico'] > 0) ? (site_url('reportes_estaticos/descarga/'.$plan_cargado['id_reporte_estatico'].'/1')) : $url_plan;
?>

<embed src="<?php echo $plan_implementacion;?>" width="100%" height="768px" />