<?php

/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

?>
<link href="<?php echo base_url(); ?>assets/jsgrid/css/jsgrid.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/jsgrid/css/jsgrid-theme.min.css" rel="stylesheet" />
<!-- Grid plugin -->
<script src="<?php echo base_url(); ?>assets/jsgrid/js/jsgrid.min.js"></script>

<?php
echo js('administracion/bitacora.js');
?>

<div class="">    
    <div id="jsGrid"></div>
</div>