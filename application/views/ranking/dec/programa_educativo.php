<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
                <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
                </a>
            </div>
            <?php
            echo js('ranking/index.js');
            echo form_open('ranking/', array('id' => 'form_ranking'));
            ?>
            <div id="filtros_capa" class="card-content">
                <?php
                if (isset($usuario['central']))
                {
                    ?>
                    <div class="row form-group">                       
                        <div class="col-md-4">
                            <label class="control-label">* Criterio de b&uacute;squeda:</label>
                            <div class="form-group form-group-sm is-empty">
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'umae',
                                            'type' => 'dropdown',
                                            'first' => array('' => 'Seleccione...'),
                                            'options' => array(1 => 'Delegacional', 2 => 'UMAE'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'UMAE',
                                                'onchange'=>'mostrar_nivel(this)')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('umae'); ?>
                        </div>
                        <div class="col-md-4">                            
                            <label class="control-label">* Año:</label>
                            <div class="form-group form-group-sm is-empty">
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'periodo',
                                            'type' => 'dropdown',
                                            'first' => array('' => 'Seleccione...'),
                                            'options' => $periodos,
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Periodo')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('periodo'); ?>
                        </div>
                        <div id="div_niveles" class="col-md-4">
                            <label class="control-label">* Niveles:</label>
                            <div class="form-group form-group-sm is-empty">
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'niveles',
                                            'type' => 'dropdown',
                                            'first' => array('' => 'Seleccione...'),
                                            'options' => $niveles[1],
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Niveles')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('niveles'); ?>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i class="material-icons cores-helper" data-help="agrupamiento">help</i> Agrupamiento:</span>
                                <php
                                echo $this->form_complete->create_element(
                                        array('id' => 'agrupamiento',
                                            'type' => 'dropdown',
                                            'options' => array(1 => 'Si', 0 => 'No'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Agrupamiento',
                                            )
                                        )
                                );
                                ?>
                            </div>
                        </div> -->
                    </div>            
                    <?php
                }
                ?>
                
                <div class="row form-group">                    
                    <!-- <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon"> Programa educativo:<br>                                                                
                                    <a href="#cores-modal" id="cores-programa-ayuda" onclick="lista_cursos()" style="display:<php echo (isset($lista_cursos)? 'block':'none')?>;" data-toggle="modal" data-target="#myModal">Ver lista de cursos</a>
                            </span>
                            <php
                            echo $this->form_complete->create_element(
                                    array('id' => 'programa',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $programas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Programa',
                                            'onchange' => 'cmbox_programa(this)')
                                    )
                            );
                            ?>                                                       
                        </div>
                        <php echo form_error_format('programa'); ?>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Reporte:</span>
                            <php
                            echo $this->form_complete->create_element(
                                    array('id' => 'tipo',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $graficas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Tipo')
                                    )
                            );
                            ?>
                        </div>
                        <php echo form_error_format('tipo'); ?>
                    </div> -->
                </div>
                <br>
                <div class="row">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<div id="area_cursos" class="row" style="display: none">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="capa_lista_cursos" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#lista_cursos">
                <a href="#" data-toggle="collapse" data-target="#lista_cursos">Lista de cursos<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i>
                </a>
            </div>
            <div id="lista_cursos" class="card-content collapse">
                <?php
                if (isset($lista_cursos))
                {
                    echo $lista_cursos;
                }
                ?>
            </div>
            <br>
        </div>
    </div>
</div>

<div class="row">

    <?php
    if (isset($grafica))
    {
        echo $grafica;
    }
    ?>    
    <div id="area_table">
        <?php
        if (isset($tabla))
        {
            echo $tabla;
        }
        ?>
    </div>
</div>

<div id="alert-ranking" class="alert alert-warning alert-comparativa" style="display: none">
    <span>
        No existen resultados para esa busqueda, intente con otros filtros por favor.
    </span>
</div>
<script type="text/javascript">
function mostrar_nivel(elemento){
    if($(elemento).val()==1){
        $('#div_niveles').show();
    } else {
        $('#div_niveles').hide();
        $('#niveles').val('');
    }
}
</script>