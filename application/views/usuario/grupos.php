<?php
    if(isset($status) && $status){
         echo html_message('Usuario actualizado con éxito', 'success');
    }else if(isset($status)){
         echo html_message('Falla al actualizar usuario', 'danger');
    }
?>
<input type="hidden" name="usuario" value="">
<table class="table table-bordered">
    <thead>
    <th>Nombre</th>        
    <th>Activo</th>
</thead>
<tbody>
    <?php
    foreach ($grupos_usuario as $row)
    {
        ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td>
                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'activo' . $row['id_grupo'],
                            'type' => 'checkbox',
                            'attributes' => array('name' => 'activo' . $row['id_grupo'],
                                'class' => 'form-control  form-control input-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'activo',
                                'checked' => (empty($row['id_usuario']) ? false : true))
                        )
                );
                ?>
            </td>
        </tr>
    <?php } ?>
</tbody>
</table>
<div class="col-md-4">
    <?php
    echo $this->form_complete->create_element(array(
        'id' => 'btn_submit',
        'type' => 'submit',
        'value' => 'Guardar',
        'attributes' => array(
            'class' => 'btn btn-primary',
        ),
    ));
    ?>
</div>
