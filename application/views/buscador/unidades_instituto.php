
<?php foreach($unidades as $unidad){
    ?>
<li class="autocomplete_unidad li-autocomplete" data-unidad-clave="<?php echo $unidad['clave_unidad'];?>" data-unidad-nombre="<?php echo $unidad['nombre']; ?>" data-unidad-id="<?php echo $unidad['id_unidad_instituto']; ?>" onclick="set_value_unidad(this)" ><?php echo $unidad['nombre']; ?></li>
<?php
}
