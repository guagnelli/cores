<?php
echo form_open('ranking/index/', array('id' => 'form_ranking'));
?>
<input type="hidden" name="umae" value="0">
<div class="form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Region:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'region',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $regiones,
                        'value' => $usuario['id_region'], 
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region', 
                            'onchange' => 'cmbox_region()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('region'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Delegación:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'delegacion',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $delegaciones,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Delegación', 
                            'onchange' => 'cmbox_delegacion()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('delegacion'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'tipo_unidad',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $tipos_unidades,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Tipo', 
                            'onchange' => 'cmbox_tipo_unidad()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('tipo_unidad'); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Curso:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'curso',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $cursos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Curso', 
                            'onchange' => 'cmbox_curso()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('curso'); ?>
    </div>
</div>

<?php echo form_close(); ?>