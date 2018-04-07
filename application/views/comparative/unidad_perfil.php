<?php
echo js('chart_options.js');
echo js('comparativa/unidad_perfil.js');
echo js('help.js');
echo form_open('comparativa/unidades', array('id' => 'form_comparativa_umae'));
?>
<input type="hidden" name="vista" value="2">
<input type="hidden" name="agrupamiento" value="<?php echo $agrupamiento?>">
<input type="hidden" name="tipo_comparativa" value="2">
<hr>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Año:</span>
            <?php
            $options_periodo = array('id' => 'periodo',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),                        
                        'options' => $periodos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region',
                            'onchange' => 'cmbox_comparativa()')
                    );
            if(isset($periodo)){
                $options_periodo['value'] = $periodo;
            }
               
            echo $this->form_complete->create_element(
                    $options_periodo
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
                        'options' => $grupos_categorias,
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
                        'value' => $usuario['nivel_atencion'],
                        'options' => $niveles,
                        'attributes' => $atributos_niveles
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('nivel'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">* Tipo de unidad:</span>
            <?php
            $tu = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'TIpo de unidad',
                'onchange' => 'cmbox_tipo_unidad()');
            if (is_nivel_operacional($usuario['grupos']))
            {
                $tu += array('disabled' => true);
            }
            echo $this->form_complete->create_element(array('id' => 'tipo_unidad',
                'type' => 'dropdown',
                'first' => array('' => 'Seleccione...'),
                'options' => $tipos_unidades,
                'value' => $tipo_unidad,
                'attributes' => $tu));
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="material-icons cores-helper" data-help="unidad_buscador">help</i> * Unidad:</span>            
            <?php
            echo $this->form_complete->create_element(array('id' => 'unidad1', 'type' => 'hidden', 'value' => $usuario['clave_unidad']));
            $atributos_unidad1 = array(
                'class' => 'form-control  form-control input-sm  unidad_texto',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'data-id' => 1,
                'autocomplete' => 'off',
                'placeholder' => 'Buscar unidad por nombre',
                'title' => 'Por favor escriba el nombre de la unidad');
            if (is_nivel_operacional($usuario['grupos']))
            {
                $atributos_unidad1 += array('disabled' => true);
            }
            echo $this->form_complete->create_element(
                    array('id' => 'unidad1_texto',
                        'type' => 'text',
                        'value' => $usuario['name_unidad_ist'],
                        'attributes' => $atributos_unidad1
                    )
            );
            ?>
            <ul class="ul-autocomplete" data-autocomplete-id="1" id="unidad1_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad1'); ?>
    </div>
    
</div>            
<hr>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon"><i class="material-icons cores-helper" data-help="unidad_buscador">help</i> * Comparar con :</span>            
            <?php
            echo $this->form_complete->create_element(array('id' => 'unidad2', 'type' => 'hidden'));
            echo $this->form_complete->create_element(
                    array('id' => 'unidad2_texto',
                        'type' => 'text',
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm  unidad_texto',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'data-id' => 2,
                            'autocomplete' => 'off',
                            'placeholder' => 'Buscar unidad por nombre',
                            'title' => 'Por favor escriba el nombre de la unidad')
                    )
            );
            ?>
            <ul class="ul-autocomplete" data-autocomplete-id="2" id="unidad2_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad2'); ?>
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
