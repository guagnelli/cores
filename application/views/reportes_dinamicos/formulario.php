<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <?php
                    if (isset($status))
                    {
                        $msg = $status ? 'Reporte cargado con éxito' : 'Datos inválidos';
                        $tipo_m = $status ? 'success' : 'danger';
                        echo html_message($msg, $tipo_m);
                    }
                    ?>
                    <div class="form-group">

                        <div class="form-group">
                            <label class="col-md-4 control-label ">Sube: </label>
                            <div class="col-md-8 input-group">
                                <!-- <span class="label label-primary"><i class="glyphicon glyphicon-cloud-upload"></i></span> -->
                                <?php echo form_open_multipart('reportes_dinamicos/upload'); ?>
                                <input type="file" name="userfile" class="">
                            </div>
                            <?php echo form_error_format('userfile'); ?>
                        </div> <br>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <input type="submit" name="submit" value="Cargar" class="btn btn-primary">
                            <?php echo form_close(); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
