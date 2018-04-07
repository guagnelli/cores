<?php
echo form_open('comparativa/delegacion_v2', array('id' => 'form_delegacion'));
?>
<hr>
<input type="hidden" id="tipo_comparativa" name="tipo_comparativa" value="2">
<input type="hidden" name="view" value="2">
<input type="hidden" name="agrupamiento" value="<?php echo $agrupamiento ?>">
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Tipo de perfil:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'perfil',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $subcategorias,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'perfil',
                            'onchange' => 'cmbox_perfil()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('perfil'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Perfil:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'subperfil',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $subperfiles,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'subperfil',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('subperfil'); ?>
    </div>

    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Nivel de atención:</span>
            <?php
            $atributos_niveles = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Nivel de atención',
                'onchange' => 'cmbox_nivel()');
            if (is_nivel_operacional($usuario['grupos']))
            {
                $atributos_niveles += array('disabled' => true);
            }
            echo $this->form_complete->create_element(
                    array('id' => 'nivel',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $niveles,
                        'attributes' => $atributos_niveles
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('nivel'); ?>
    </div>    

</div>
<hr>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo de unidad:</span>
            <?php
            $tu = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'TIpo de unidad',
            );
            echo $this->form_complete->create_element(array('id' => 'tipo_unidad',
                'type' => 'dropdown',
                'first' => array('' => 'Todas'),
                'options' => $tipos_unidades,
                'value' => $tipo_unidad,
                'attributes' => $tu));
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Delegación:</span>
            <?php
            $atributos_del = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Delegación',
                'onchange' => '');
            if (is_nivel_tactico($usuario['grupos']))
            {
                $atributos_del['disabled'] = true;
            }
            echo $this->form_complete->create_element(
                    array('id' => 'delegacion1',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'value' => $usuario['grupo_delegacion'],
                        'options' => $delegaciones,
                        'attributes' => $atributos_del
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('unidad1'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Comparar con :</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'delegacion2',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => (array('PROMEDIO' => 'PROMEDIO')) + $delegaciones,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Delegación 2',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('unidad2'); ?>
    </div>
</div>            
<hr>
<div class="row form-group">    
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Año:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'periodo',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $periodos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('periodo'); ?>
    </div>

</div>            
<hr>
<div class="row form-group">

    <div class="col-md-6">
        <div class="input-group input-group-sm">
            <input type="submit" name="submit" value="Comparar" class="btn btn-primary">
            <input id="btn_limpiar" name="btn_limpiar" class="btn btn-secondary pull-right" value="Limpiar filtros" type="button" onclick="cmbox_comparativa()">
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    $(function () {    
        $('#form_delegacion').submit(function (event) {            
            submit_delegacion(event);
        });
    });
</script>