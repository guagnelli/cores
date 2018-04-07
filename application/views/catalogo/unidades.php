<?php echo js('paginacion/general.js'); ?>
<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <?php
                    echo form_open('catalogo/unidad_instituto/', array('id' => 'form_paginacion'));
                    ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button id="btn-filtro-tablero" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Clave unidad<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="option-input-tablero" data-id="clave_unidad" href="#">Clave unidad</a></li>
                                        <li><a class="option-input-tablero" data-id="unidad" href="#">Unidad</a></li>
                                        <li><a class="option-input-tablero" data-id="clave_presupuestal" href="#">Clave presupuestal</a></li>
                                        <li><a class="option-input-tablero" data-id="delegacion" href="#">Delegación</a></li>
                                        <li><a class="option-input-tablero" data-id="tipo" href="#">Tipo</a></li>
                                    </ul>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control" aria-label="..." name="keyword">
                            </div><!-- /input-group -->
                            <input type="hidden" id="filtro_texto" name="filtro_texto" value="clave_unidad">
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                    
                    <?php
                    if (isset($unidades['current_row']))
                    {
                        ?>
                        <input id="pagination_current_page" type="hidden" name="current_row" value="<?php echo $unidades['current_row']; ?>" />
                        <input id="pagination_limit" type="hidden" name="pagination_limit" value="<?php echo $unidades['per_page']; ?>" />
                    <?php }
                    
                    ?>

                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Número de registros a mostrar:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'per_page',
                                            'type' => 'dropdown',
                                            'value' => $unidades['per_page'],
                                            'options' => array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100),
                                            'attributes' => array('name' => 'per_page',
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Número de registros a mostrar')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('per_page'); ?>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Tipo de orden:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'order',
                                            'type' => 'dropdown',
                                            'options' => array(1 => 'Ascendente', 2 => 'Descendente'),
                                            'attributes' => array('name' => 'order',
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Tipo de orden')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('order'); ?>
                        </div>

                        <div class="col-md-4">
                            <?php
                            echo $this->form_complete->create_element(array(
                                'id' => 'btn_submit',
                                'type' => 'submit',
                                'value' => 'Buscar',
                                'attributes' => array(
                                    'class' => 'btn btn-primary',
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!--tablas de usuarios-->

                    <br>
                    <p><?php echo 'Total: '.$unidades['total'];  
                       echo $paginacion['links'];?></p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Nombre</th>
                                <th>Cve. presupuestal</th>
                                <th>Delegación</th>
                                <th>Tipo</th>
                                <th>UMAE</th>
                                <th>Latitud</th>
                                <th>Logitud</th>
                                <th>Año</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($unidades['data'] as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['clave_unidad']; ?></td>
                                    <td><?php echo $row['unidad']; ?></td>
                                    <td><?php echo $row['clave_presupuestal']; ?></td>
                                    <td><?php echo $row['delegacion']; ?></td>
                                    <td><?php echo $row['tipo']; ?></td>
                                    <td><?php echo ($row['umae']?'Si':'No'); ?></td>
                                    <td><?php echo $row['latitud']; ?></td>
                                    <td><?php echo $row['longitud']; ?></td>
                                    <td><?php echo $row['anio']; ?></td>
                                    <!--<td>Editar</td>-->
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (isset($paginacion))
                    {
                        echo $paginacion['links'];
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
