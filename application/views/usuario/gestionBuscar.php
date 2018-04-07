<?php echo js('usuario/lista_usuarios.js'); ?>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <?php
                    echo form_open('usuario/lista_usuarios/', array('id' => 'form_usuarios'));
                    ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button id="btn-filtro-tablero" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Matrícula<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="option-input-tablero" data-id="nombre" href="#">Nombre</a></li>
                                        <li><a class="option-input-tablero" data-id="matricula" href="#">Matrícula</a></li>
                                        <li><a class="option-input-tablero" data-id="email" href="#">Correo electrónico</a></li>
                                    </ul>
                                </div><!-- /btn-group -->
                                <input type="text" class="form-control" aria-label="..." name="keyword">
                            </div><!-- /input-group -->
                            <input type="hidden" id="filtro_texto" name="filtro_texto" value="matricula">
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->

                    <?php
                    if (isset($current_row))
                    {
                        ?>
                        <input id="usuarios_current_page" type="hidden" name="current_row" value="<?php echo $current_row; ?>" />
                        <input id="usuarios_limit" type="hidden" name="usuarios_limit" value="<?php echo $per_page; ?>" />
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
                                            'value' => $per_page,
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
                    <p><?php
                        echo 'Total: ' . $usuarios['total'];
                        echo $paginacion['links'];
                        ?></p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Delegación</th>
                                <th>Unidad</th>
                                <th>Activo</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($usuarios['tabla'] as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['matricula']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['name_delegacion']; ?></td>
                                    <td><?php echo $row['name_unidad_ist']; ?></td>
                                    <!-- <td><a href="<?php echo site_url() ?>/usuario/ver_usuario_tabla/<?php echo $row['id_usuario']; ?>">Ver</a></td> -->
                                    <td>
                                        <input id="usuario_chbox_<?php echo $row['id_usuario']; ?>" type="checkbox" <?php echo ($row['activo'] == 1 ? 'checked' : '') ?> onchange="set_status_usuario(<?php echo $row['id_usuario']; ?>)" />
                                    </td>
                                    <td><a href="<?php echo site_url() ?>/usuario/mod/<?php echo $row['id_usuario']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                                    </td>
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
