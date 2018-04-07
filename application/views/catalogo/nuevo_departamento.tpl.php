<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$nombre_departamento = isset($params['nombre']) ? $params['nombre'] : '';
$clave_departamento = isset($params['clave']) ? $params['clave'] : '';
$nombre_unidad = isset($params['unidad_texto']) ? $params['unidad_texto'] : '';
$id_unidad_instituto = isset($params['unidad']) ? $params['unidad'] : '';
echo js('catalogo/departamento.js');
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Nuevo departamento</h4>
</div>
<div class="modal-body">
    <?php echo form_open('catalogo/nuevo_departamento', array('id' => 'form_departamento', 'autocomplete' => 'off')); ?>
    <div class="row">
        <div class="col col-md-12">
            <?php
            if (isset($status))
            {
                $tipo = $status['status'] ? 'success' : 'danger';
                echo html_message($status['msg'], $tipo);
            }
            ?>
        </div>
        <div class="col col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Nombre</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'nombre',
                            'type' => 'text',
                            'value' => $nombre_departamento,
                            'attributes' => array(
                                'class' => 'form-control  form-control input-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Departamento')
                        )
                );
                ?>
            </div>
            <?php echo form_error_format('nombre'); ?>
        </div>
        <div class="col col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Clave</span>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'clave',
                            'type' => 'text',
                            'value' => $clave_departamento,
                            'attributes' => array(
                                'class' => 'form-control  form-control input-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Clave departamento')
                        )
                );
                ?>
            </div>
            <?php echo form_error_format('clave'); ?>
        </div>
        <div class="col col-md-12">
            <div class="input-group input-group-sm">
                <span class="input-group-addon">Unidad/UMAE</span>
                <input type="hidden" name="unidad" id="unidad" value="<?php echo $id_unidad_instituto; ?>">
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'unidad_texto',
                            'type' => 'text',
                            'value' => $nombre_unidad,
                            'attributes' => array(
                                'class' => 'form-control  form-control input-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Unidad/UMAE')
                        )
                );
                ?>
                <ul class="ul-autocomplete" id="unidad_autocomplete" style="display:none;">

                </ul>
            </div>
            <?php echo form_error_format('unidad'); ?>
        </div>               
    </div>
    <br>
    <div class="row">
        <input type="submit" value="Guardar" class="btn btn-default">
    </div>
    <?php echo form_close(); ?>
</div>