<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body"><br><br>

                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Seleccione su CSV : </label>
                        <div class="col-md-6 input-group">
                            <?php echo form_open_multipart('usuario/carga_usuarios'); ?>
                            <input type="file" name="userfile"><br>
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <input type="submit" name="submit" value="Cargar archivo" class="btn btn-primary">
                        <?php echo form_close(); ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
