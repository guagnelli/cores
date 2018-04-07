<?php echo js('usuario/editar.js'); ?>
<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Datos básicos</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--form usuario completo-->
                    <?php
                    if (isset($status) && $status)
                    {
                        echo html_message('Usuario actualizado con éxito', 'success');
                    }
                    ?>
                    <?php
                    echo form_open('usuario/mod/' . $usuarios['id_usuario'], array('id' => 'form_actualizar', 'autocomplete'=>"off"));
                    ?>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Matrícula: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'matricula', 'type' => 'number', 'value' => $usuarios['matricula'], 'attributes' => array('name' => 'matricula', 'readonly' => ' ', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Correo electrónico: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'email', 'type' => 'email', 'value' => $usuarios['email'], 'attributes' => array('name' => 'email', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Categoría: </label>
                        <div class="col-md-6 input-group">
                            <input type="hidden" name="categoria" id="categoria" value="<?php echo $usuarios['clave_categoria']; ?>">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'categoria_texto', 
                                'type' => 'text', 
                                'value' => $usuarios['categoria_texto'],
                                'attributes' => array('name' => 'categoria_texto', 'class' => 'form-control', 'autocomplete'=>'off')));
                            ?>
                            <ul class="ul-autocomplete" id="categoria_autocomplete" style="display:none;">
                                
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Unidad: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <input type="hidden" name="unidad" id="unidad" value="<?php echo $usuarios['id_unidad_instituto']; ?>">
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'unidad_texto', 
                                'type' => 'text', 
                                'value' => $usuarios['unidad_texto'], 
                                'attributes' => array('name' => 'unidad_texto', 'class' => 'form-control', 'autocomplete'=>'off')));
                            ?>
                            <ul class="ul-autocomplete" id="unidad_autocomplete" style="display:none;">
                                
                            </ul>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                </div>




                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Constraseña de usuario</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--form usuario completo-->
                    <?php
                    if (isset($status_password) && $status_password && $status_password == 1)
                    {
                        echo html_message('Contraseña actualizada con éxito', 'success');
                    } else if (isset($status_password) && $status_password && $status_password == 2)
                    {
                        echo html_message('Datos inválidos', 'danger');
                    }
                    ?>
                    <?php
                    echo form_open('usuario/update_password/' . $usuarios['id_usuario'], array('id' => 'form_actualizar_password'));
                    ?>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'pass', 'type' => 'password','value'=> '', 'attributes' => array('name' => 'pass', 'class' => 'form-control', 'autocomplete'=>'off')));
                            ?>
                        </div>
                        <?php echo form_error_format('pass'); ?>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Confirmar contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'pass_confirm', 'type' => 'password', 'attributes' => array('name' => 'pass_confirm', 'class' => 'form-control')));
                            ?>
                        </div>
                        <?php echo form_error_format('pass_confirm'); ?>
                    </div>

                    <br>
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                    </div>
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Grupos</h3>
            </div> <br><br>
            <div class="panel-body">
                <?php
                echo form_open('usuario/upsert_grupos/'.$usuarios['id_usuario'], array('id' => 'form_usuario_grupo'));
                ?>
                <div id="area_grupos">

                    <?php echo $view_grupos_usuario; ?>

                </div>
                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>
