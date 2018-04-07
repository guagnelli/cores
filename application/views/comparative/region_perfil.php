<?php
echo form_open('comparativa/region', array('id' => 'form_region'));
?>
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
                        'value' => (isset($periodo)?$periodo:''),
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
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Tipo de perfil:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'perfil',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $subcategorias,
                        'value' => (isset($subcategoria)?$subcategoria:''),
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
                        'value' => (isset($subperfil)?$subperfil:''),
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

    

</div>
<hr>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Nivel de atención:</span>
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
                        'value' => (isset($nivel)?$nivel:''),
                        'attributes' => $atributos_niveles
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('nivel'); ?>
    </div>    
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">*Tipo de unidad:</span>
            <?php
            $tu = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'TIpo de unidad',
            );
            echo $this->form_complete->create_element(array('id' => 'tipo_unidad',
                'type' => 'dropdown',
                'first' => array('' => 'Seleccione...'),
                'options' => $tipos_unidades,
                'value' => $tipo_unidad,
                'attributes' => $tu));
            ?>
        </div>
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
        $('#form_region').submit(function (event) {            
            event.preventDefault();
            if(valida_filtros('perfil')){
                submit_region();
            }else{
                alert('Debe seleccionar los filtros, antes de realizar una comparación');
            }
        });
    });
</script>