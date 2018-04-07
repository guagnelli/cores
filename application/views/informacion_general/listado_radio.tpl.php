<label class="control-label"><?php echo $form['label']; ?></label><div class="clearfix"></div>
<?php 
foreach ($datos as $key => $value) {
    echo '<label class="control-label">'.$this->form_complete->create_element(
        array(
            'id'=>$tipo,
            'type'=>'radio',
            'value' => $value['id_unidad_instituto'],
            'attributes'=>array('class'=>'',
                //'onchange'=>"javascript:data_ajax(site_url+'/informacion_general/cargar_listado/".$form['path']."', '#form_busqueda', '#".$form['path']."_capa')",
                'name'=>$tipo."[]"
            )
        )
    ).$value['institucion'].' ('.$value['clave_unidad'].')</label>';
} ?>
<span class="material-input"></span>  