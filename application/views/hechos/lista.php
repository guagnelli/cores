<?php echo js('hechos/get_lista.js'); ?>
<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body"><br><br>

                <div class="row">
                    <?php
                    if (isset($status) && $status && $status == 1)
                    {
                        echo html_message('Información almacenada con éxito', 'success');
                    }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row" style="margin:5px; overflow: auto;">
                            <div class="container">
                                <table class="table table-striped responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Periodo</th>
                                            <th>Fecha de carga</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($lista as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id_carga_informacion']; ?></td>
                                                <td><?php echo $row['fecha_inicio'] . ' - ' . $row['fecha_fin']; ?></td>
                                                <td><?php echo $row['fecha_carga']; ?></td>
                                                <td>
                                                    <input id="hechos_chbox_<?php echo $row['id_carga_informacion']; ?>" type="checkbox" <?php echo ($row['activa'] == 1 ? 'checked' : '') ?> onchange="set_status_hechos(<?php echo $row['id_carga_informacion']; ?>)" />
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
