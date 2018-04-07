<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <?php
                    if (isset($status) && $status != null)
                    {
                        switch ($status)
                        {
                            case '2':
                                echo html_message('Datos inválidos', 'danger');
                                break;
                            case '3':
                                echo html_message('Archivo inválido', 'danger');
                                break;
                            case '4':
                                echo html_message('Acceso denegado', 'danger');
                                break;
                        }
                    }
                    ?>
                    <div class="form-group">

                        <div class="form-group">
                            <label class="col-md-4 control-label ">Sube: </label>
                            <div class="col-md-8 input-group">
                                <!-- <span class="label label-primary"><i class="glyphicon glyphicon-cloud-upload"></i></span> -->
                                <?php echo form_open_multipart('reportes_estaticos/upload'); ?>
                                <input type="file" name="userfile" class="">
                            </div>
                        </div> <br>

                        <div class="form-group">
                            <label class="col-md-4 control-label ">Nombre: </label>
                            <div class="col-md-8 input-group">
                                <?php
                                echo $this->form_complete->create_element(array('id' => 'nombre', 'type' => 'text'));
                                ?>

                            </div>
                        </div> <br>
                        <div class="form-group">
                            <label class="col-md-4 control-label ">Descripción: </label>
                            <div class="col-md-8 input-group">
                                <?php
                                echo $this->form_complete->create_element(array('id' => 'descripcion', 'type' => 'textarea', 'attributes' => array('rows' => '4')));
                                ?>
                            </div>
                        </div> <br>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <input type="submit" name="submit" value="Cargar reporte estático" class="btn btn-primary">
                            <?php echo form_close(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
