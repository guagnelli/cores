<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Registro de usuario</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--form usuario completo-->
                    <?php
                    if (isset($status) && isset($formulario) && $formulario == 'datos_personales' && $status)
                    {
                        echo html_message('Usuario actualizado con éxito', 'success');
                    }else if(isset($status) && isset($formulario) && $formulario == 'datos_personales'){
                        echo html_message('Usuario no actualizado', 'danger');
                    }
                    ?>
                    <?php
                    echo form_open('perfil_usuario/index/', array('id' => 'form_actualizar', 'autocomplete'=>"off"));
                    ?>
                    <input type="hidden" name="formulario" value="datos_personales">
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
                        <?php echo form_error_format('email'); ?>
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
                    if (isset($status) && isset($formulario) && $formulario == 'update_password' && $status)
                    {
                        echo html_message('Contraseña actualizada con éxito', 'success');
                    } else if (isset($status) && isset($formulario) && $formulario == 'update_password')
                    {
                        echo html_message('Datos inválidos', 'danger');
                    }
                    ?>
                    <?php
                    echo form_open('perfil_usuario/index/', array('id' => 'form_actualizar_password', 'autocomplete'=>"off"));
                    ?>
                    <input type="hidden" name="formulario" value="update_password">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'pass', 'type' => 'password', 'value'=> '', 'attributes' => array('name' => 'pass', 'class' => 'form-control', 'autocomplete'=>'off')));
                            ?>
                        </div>
                        <?php echo form_error_format('pass'); ?>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Confirmar contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'repass', 'type' => 'password', 'value'=> '','attributes' => array('name' => 'repass', 'class' => 'form-control','autocomplete'=>'off')));
                            ?>
                        </div>
                        <?php echo form_error_format('repass'); ?>
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
