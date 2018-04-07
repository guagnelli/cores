<?php echo js('usuario/validaciones.js')
?>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Registrar Usuarios</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--formulario de registro-->
                    <?php
                    if (isset($registro_valido['result']))
                    {
                        //pr($registro_valido);
                        $tipo_msg = $registro_valido['result']? 'success' : 'danger';
                        echo html_message($registro_valido['msg'], $tipo_msg);
                    }
                    ?>

                    <?php
                    echo form_open('usuario/agregar_usuario', array('id' => 'form_registro'));
                    ?>
                    <!--Matriculas-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Matrícula: </label>
                        <!-- <div class="col-md-4 inputGroupContainer"> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <input id="matricula" name="matricula" placeholder="Escriba su matrícula" class="form-control"  type="number" required>
                        </div>
                        <?php echo form_error_format('matricula'); ?>
                    </div>
                    <!-- </div> -->
                    <!--email -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Correo electrónico: </label>
                        <!-- <div class="col-md-4 inputGroupContainer"> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <input id="email" name="email" placeholder="correo@imss.com" class="form-control"  type="email" required>
                        </div>
                        <?php echo form_error_format('email'); ?>
                    </div>
                    <!-- </div> -->
                    <!--Password-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Contraseña: </label>
                        <!-- <div class="col-md-4 inputGroupContainer"> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <input id="pass" name="pass" placeholder="Escribe tu contraseña" class="form-control" type="password" required>
                        </div>
                        <?php echo form_error_format('pass'); ?>
                    </div>
                    <!-- </div> -->
                    <!--Re-Password-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Confirmar contraseña: </label>
                        <!-- <div class="col-md-4 inputGroupContainer"> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <input id="repass" name="repass" placeholder="Repite tu contraseña" class="form-control" type="password" required >
                        </div>
                        <?php echo form_error_format('repass'); ?>
                    </div>
                    <!-- </div> -->
                    <!--Delegación-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Delegación: </label>
                        <!-- <div class="col-md-4 "> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'delegacion', 'type' => 'dropdown', 'options' => $delegaciones, 'first' => array('' => 'Seleccione una opción'), 'attributes' => array('name' => 'delegacion', 'class' => 'form-control')));
                            ?>
                        </div>
                        <?php echo form_error_format('delegacion'); ?>
                    </div>
                    <!-- </div> -->
                    <!--Categoria / Rol-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">Nivel de atención: </label>
                        <!-- <div class="col-md-4 selectContainer"> -->
                        <div class="col-md-4 input-group">
                            <span class="input-group-addon"></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'niveles', 'type' => 'dropdown', 'options' => $nivel_atencion, 'first' => array('' => 'Seleccione una opción'), 'attributes' => array('name' => 'niveles', 'class' => 'form-control')));
                            ?>
                        </div>
                        <?php echo form_error_format('niveles'); ?>
                    </div><br>

                    <!-- </div> -->
                    <!-- Guardar -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <!-- <div class="col-md-4"> -->
                        <button id="submit" name="submit" type="submit" class="btn btn-primary" data-idmodal="#divModal" ><a class="btn-primary">Registrar</a>     <span class="glyphicon glyphicon-send"></span></button>
                    </div>
                    <!-- </div> -->
                    <?php echo form_close(); ?>

                </div><!-- /.container -->

            </div>



        </div>
    </div>
</div>
