<?php
/**
 * @author Christian Garcia
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('render_modulo'))
{

    function render_modulo($modulos = [], $padre = null)
    {
        $html = '';
        ob_start();
        ?>
        <ul class="<?php echo ($padre != null ? 'collapse' : ''); ?>" <?php echo ($padre != null ? 'id="' . $padre . '" style="margin-left: 20px;"' : ''); ?>>
            <?php
            foreach ($modulos as $row)
            {
                ?>            
                <li class="<?php echo (isset($row['childs']) ? '' : '') ?>" style="list-style-type: none;">
                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <a style="text-decoration: underline;cursor: pointer;" onclick="get_info_modulo(<?php echo $row['id_modulo']; ?>)" data-toggle="modal" data-target="#myModal"><?php echo $row['nombre']; ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <?php echo $row['url']; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <?php echo $row['configurador']; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($row['childs']))
                    {
                        ?>
                    <p data-toggle="collapse" data-target="#modulo<?php echo $row['id_modulo']; ?>"><a style="cursor:pointer;text-decoration: underline;" href="#">Ver más</a></p>
                        <?php
                        //pr($item['childs']);
                        echo render_modulo($row['childs'], 'modulo' . $row['id_modulo']);
                        ?>
                        
                        <?php
                    }
                    ?>
                </li>
                <hr>
            <?php } ?>
        </ul>
        <?php
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
    }

}

if (!function_exists('render_modulos_grupo'))
{

    function render_modulos_grupo($CI = null, $modulos = [], $padre = null)
    {
        $html = '';
        ob_start();
        ?>
        <ul class="<?php echo ($padre != null ? 'collapse' : ''); ?>" <?php echo ($padre != null ? 'id="' . $padre . '" style="margin-left: 20px;"' : ''); ?>>
            <?php
            foreach ($modulos as $row)
            {
                ?>            
                <li class="<?php echo (isset($row['childs']) ? '' : '') ?>" style="list-style-type: none;">
                    <div class="row form-group">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <?php echo $row['nombre']; ?><br><span style="text-decoration: underline; font-style: italic;"><?php echo $row['url']; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <?php
                                echo $CI->form_complete->create_element(
                                        array('id' => 'configurador' . $row['id_modulo'],
                                            'type' => 'text',
                                            'value' => $row['configurador_modulo'], 
                                            'attributes' => array('name' => 'configurador' . $row['id_modulo'],
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'placeholder' => 'Opción adicional',
                                                'title' => 'Opción adicional')
                                        )
                                );
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <?php
                                echo $CI->form_complete->create_element(
                                        array('id' => 'activo' . $row['id_modulo'],
                                            'type' => 'checkbox',
                                            'attributes' => array('name' => 'activo' . $row['id_modulo'],
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'activo',
                                                'checked' => (empty($row['id_grupo']) ? false : true))
                                        )
                                );
                                ?>

                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($row['childs']))
                    {
                        ?>
                    <p data-toggle="collapse" data-target="#modulo<?php echo $row['id_modulo']; ?>"><a href="#" style="cursor:pointer;text-decoration: underline;">Ver más</a></p>
                        <?php
                        //pr($item['childs']);
                        echo render_modulos_grupo($CI, $row['childs'], 'modulo' . $row['id_modulo']);
                        ?>
                      
                        <?php
                    }
                    ?>
                </li>
                  <hr>
            <?php } ?>
        </ul>
        <?php
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
    }

}