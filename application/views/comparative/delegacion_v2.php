<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php
echo js('comparativa/delegacion.js');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
                <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
                </a>
            </div>

            <div id="filtros_capa" class="card-content collapse">
                <?php
                echo form_open('comparativa/umae', array('id' => 'form_comparativa'));
                ?>           
                <div class="row form-group">       
                    <?php
                    if (is_nivel_central($usuario['grupos']))
                    {
                        ?>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i class="material-icons cores-helper" data-help="agrupamiento">help</i> Agrupamiento:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'agrupamiento',
                                            'type' => 'dropdown',
                                            'options' => array(1 => 'Si', 0 => 'No'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Agrupamiento',
                                                'onchange' => 'cmbox_comparativa()'
                                            )
                                        )
                                );
                                ?>
                            </div>
                        </div>
                        
                    <?php } ?>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">* Analizar por:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'comparativa',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $comparativas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Tipo de comparativa',
                                            'onchange' => 'cmbox_comparativa()')
                                    )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
                <div id="area_comparativa">
                    <?php 
                    if(isset($vista)){
                        echo $vista;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php 
    if(isset($tabla)){ echo $tabla; } 
    ?>
    <?php 
    if(isset($grafica)){ echo $grafica; } 
    ?>
    <div id="area_graph"></div>
</div>     

<div id="area_reportes" class="row" style="display:none;">
    <div class="col-lg-12 col-md-12">
        <div class="card card-nav-tabs">
            <div class="card-header" data-background-color="green">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Comparativa de Alumnos:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="active">
                                <a href="#inscritos" data-toggle="tab" aria-expanded="true">
                                    Inscritos
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#aprobados" data-toggle="tab" aria-expanded="false">
                                    Aprobados
                                    <div class="ripple-container"></div>
                                </a>
                            </li>                            

                            <li class="">
                                <a href="#suspendidos" data-toggle="tab" aria-expanded="false">
                                    No Aprobabos
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#no_acceso" data-toggle="tab" aria-expanded="false">
                                    Nunca Entraron
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#etm" data-toggle="tab" aria-expanded="false">
                                    <i class="material-icons md-18 cores-helper" data-help="eficiencia_terminal_modificada">help</i>Eficiencia Terminal Modificada
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                        </ul>                        
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="tab-content">
                    <!--inscritos-->
                    <div class="tab-pane active" id="inscritos">
                        <div class="col-md-12">                            
                            <div id="area_graph0"></div>
                            <div id="alert-comparativa0" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--aprobados-->
                    <div class="tab-pane" id="aprobados">
                        <div class="col-md-12">                            
                            <div id="area_graph1"></div>
                            <div id="alert-comparativa1" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--etm-->
                    <div class="tab-pane" id="suspendidos">
                        <div class="col-md-12">                           
                            <div id="area_graph3"></div>
                            <div id="alert-comparativa3" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="no_acceso">
                        <div class="col-md-12">                           
                            <div id="area_graph4"></div>
                            <div id="alert-comparativa4" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--suspendodos-->
                    <div class="tab-pane" id="etm">
                        <div class="col-md-12">           
                            <div id="area_graph2"></div>
                            <div id="alert-comparativa2" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>