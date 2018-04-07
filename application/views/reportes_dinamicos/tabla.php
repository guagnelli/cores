<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <!--<a class="btn btn-primary" href="<?php echo site_url(); ?>/reportes_dinamicos/draw_form">Nuevo</a>-->
                        <br />
                        <div class="row" style="margin:5px;">
                            <div class="panel" style="overflow: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Periodo</th>
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
                                                <td><?php echo $row['fecha_inicio'] . ' - ' . $row['fecha_fin']; ?></td>
                                                <td><?php echo $row['fecha_carga']; ?></td>
                                                <td><a href="<?php echo site_url() ?>/reportes_dinamicos/view/<?php echo $row['id_reporte_dinamico']; ?>">Ver</a></td>
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
