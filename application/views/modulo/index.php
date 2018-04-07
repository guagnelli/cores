<?php
if ($full_view == 1)
{
    ?>
    <?php echo js('modulo/index.js'); ?>
    <button type="button" onclick="form_save()" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Nuevo
    </button>
    <div id="area_modulos"> 
    <?php } ?>
    <div ng-class="panelClass" class="row">
        <div class="col col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                    <?php echo render_modulo($modulos); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($full_view == 1)
    {
        ?>
    </div>
    <!-- Button trigger modal -->
    <button type="button" onclick="form_save()" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Nuevo
    </button>
<?php } ?>
