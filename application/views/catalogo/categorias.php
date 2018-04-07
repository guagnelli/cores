<?php echo js('paginacion/general.js'); ?>
<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <?php
                    echo form_open('catalogo/categoria/', array('id' => 'form_paginacion'));
                    ?>

                    <?php
                    if (isset($categorias['current_row']))
                    {
                        ?>
                        <input id="pagination_current_page" type="hidden" name="current_row" value="<?php echo $categorias['current_row']; ?>" />
                        <input id="pagination_limit" type="hidden" name="pagination_limit" value="<?php echo $categorias['per_page']; ?>" />
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
                                            'value' => $categorias['per_page'],
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
                    <p><?php echo 'Total: '.$categorias['total'];  
                       echo $paginacion['links'];?></p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Clave de categoría</th>
                                <th>Categoría</th>
                                <th>Grupo de categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($categorias['data'] as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['clave_categoria']; ?></td>
                                    <td><?php echo $row['categoria']; ?></td>
                                    <td><?php echo $row['grupo_categoria']; ?></td>
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
