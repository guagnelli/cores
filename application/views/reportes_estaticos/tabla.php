<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
          <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
              <!--<a class="btn btn-primary" href="<?php echo site_url(); ?>/reportes_estaticos/draw_form">Nuevo</a>-->
        <br />
        <div class="row" style="margin:5px;">
            <?php
            if (isset($status) && $status && $status == 1)
            {
                echo html_message('Reporte subido con éxito', 'success');
            } else if (isset($status) && $status && $status == 2)
            {
                echo html_message('Error al subir el reporte', 'danger');
            }
            ?>
            <div class="panel" style="overflow: auto;">
                <p><strong>Plan de implementaciones actual:</strong>  <?php echo $plan['nombre']; ?></p>
                <table class="table table-striped responsive">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Fecha de carga</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $row)
                        {
                            ?>
                            <tr>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><?php echo $row['tipo']; ?></td>
                                <td><?php echo $row['fecha_carga']; ?></td>
                                <td>
                                    <a href="<?php echo site_url() ?>/reportes_estaticos/descarga/<?php echo $row['id_reporte_estatico']; ?>">Descarga </a> | 
                                    <a href="<?php echo site_url() ?>/reportes_estaticos/set_mark/1/<?php echo $row['id_reporte_estatico']; ?>" >Plan implementación</a>                                    
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
