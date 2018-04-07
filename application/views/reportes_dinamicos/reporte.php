<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row" style="margin:5px;">
            <div class="panel" style="overflow: auto;">
                <h3><?php echo $reporte["informacion"][0]['nombre']; ?></h3>
                <table class="table table-bordered  table-striped">
                    <thead>
                        <tr>
                            <?php
                            foreach ($reporte['columnas'] as $row)
                            {
                                ?>
                                <th><?php echo $row['nombre']; ?></th>
                                <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//                        pr($reporte['contenido']);
                        foreach ($reporte['contenido'] as $row)
                        {
                            ?>
                            <tr>
                                <?php
                                foreach ($row as $item)
                                {
                                    ?>
                                    <td><?php echo $item; ?></td>
                                    <?php
                                }
                                ?>
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
