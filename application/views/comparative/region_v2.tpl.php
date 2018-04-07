
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<!--titulo-->
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
//pr($usuario);
                    if (isset($usuario['central']))
                    {
                        ?>                                      
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Nivel:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'umae',
                                            'type' => 'dropdown',
                                            'first' => array('' => 'Seleccione...'),
                                            'options' => array(0 => 'Delegacional', 1 => 'UMAE'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'UMAE')
                                        )
                                );
                                ?>
                            </div>
                        </div>                                                              
                        <?php
                    }
                    ?>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">* Analizar por:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'comparativa',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $comparativas,
                                        'value' => (isset($tcomparativa) ? $tcomparativa : ''),
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
                    if (isset($vista))
                    {
                        echo $vista;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">    
        <div class="card-content">
            <!-- filtros-->

            <!--fin filtros-->
            <?php
            $inscritos = "";
            $aprobados = "";
            $suspendidos = "";
            $no_acceso = "";
            $etm = "";
            $chart_title = "Comparativa de regiones";
            $filtros = "";
            if (isset($comparativa) && is_array($comparativa) && !empty($comparativa))
            {
                foreach ($comparativa as $region)
                {
                    $class_region = '';
                    if ($region['region'] == $usuario['name_region'])
                    {
                        $class_region = 'current_comparativa_region';
                        $region['region'] = '<strong>* ' . $region['region'] . '</strong>';
                    }
//                     pr($region['region'].' '.$usuario['name_region']);
                    $inscritos .= "<tr class='" . $class_region . "'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["inscritos"] . "</td>
                            </tr>";
                    $aprobados .= "<tr class='" . $class_region . "'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["aprobados"] . "</td>
                            </tr>";
                    $suspendidos .= "<tr class='" . $class_region . "'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["suspendidos"] . "</td>
                            </tr>";
                    $no_acceso .= "<tr class='" . $class_region . "'>
                              <th>" . $region["region"] . "</th>
                              <td>" . ($region["nunca_entraron"]) . "</td>
                            </tr>";
                    $etm .= "<tr class='" . $class_region . "'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["etm"] . "</td>
                            </tr>";
                    if (isset($region["tipo_curso"]))
                    {
                        $filtros = "Tipo de curso: " . $region["tipo_curso"];
                    } elseif (isset($region["perfil"]))
                    {
                        $filtros = "Perfil: " . $region["perfil"];
                    }
                }
                ?>
                <!--Reporte-->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card card-nav-tabs">
                            <div class="card-header" data-background-color="green">
                                <div class="nav-tabs-navigation">
                                    <div class="nav-tabs-wrapper">
                                        <span class="nav-tabs-title">Comparativa de Alumnos:</span>
                                        <ul class="nav nav-tabs" data-tabs="tabs">
                                            <li class="active">
                                                <a href="#inscritos" data-toggle="tab">
                                                    Inscritos
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#aprobados" data-toggle="tab">
                                                    Aprobados
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#suspendidos" data-toggle="tab">
                                                    No Aprobados
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#no_acceso" data-toggle="tab">
                                                    Nunca Entraron
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#etm" data-toggle="tab">
                                                    <i class="material-icons md-18 cores-helper" data-help="eficiencia_terminal_modificada">help</i>Eficiencia Terminal Modificada
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </ul>
                                        <p class="category">
                                            <?php echo $filtros; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="tab-content">
                                    <!--inscritos-->
                                    <div class="tab-pane active" id="inscritos">
                                        <div class="col-md-12">
                                            <div id="chrt_inscritos" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table id="table_inscritos" class="table">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Número de Alumnos inscritos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $inscritos; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--aprobados-->
                                    <div class="tab-pane" id="aprobados">
                                        <div class="col-md-12">
                                            <div id="chrt_aprobados" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table id="table_aprobados" class="table">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Número de Alumnos aprobados</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $aprobados; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--suspendodos-->
                                    <div class="tab-pane" id="suspendidos">
                                        <div class="col-md-12">
                                            <div id="chrt_suspendidos" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table id="table_suspendidos" class="table">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Número de Alumnos No Aprobados</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $suspendidos; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="no_acceso">
                                        <div class="col-md-12">
                                            <div id="chrt_no_acceso" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table id="table_no_acceso" class="table">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Número de Alumnos que Nunca entraron</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $no_acceso; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!--etm-->
                                    <div class="tab-pane" id="etm">
                                        <div class="col-md-12">
                                            <div id="chrt_etm" >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table id="table_etm" class="table">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Porcentaje de Eficiencia Terminal Modificada</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $etm; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--script src="<?php echo base_url(); ?>assets/hightcharts/highcharts.js" type="text/javascript"></script>
                <script src="<?php echo base_url(); ?>assets/hightcharts/exporting.js" type="text/javascript"></script-->
                <?php echo js("chart_options.js"); ?>
                <?php
                $titulo1 = 'Número de alumnos inscritos ';
                $titulo2 = 'Número de alumnos aprobados ';
                $titulo3 = 'Porcentaje de eficiencia terminal modificada ';
                $titulo4 = 'Número de alumnos no aprobados ';
                $titulo5 = 'Número de alumnos que nunca entraron ';
                ?>
                <script type="text/javascript">
                    function chart(id_chart, tabla, titulo, ytext, color) {
                        Highcharts.chart(id_chart, {
                            data: {
                                table: tabla
                            },
                            chart: {
                                type: 'column'
                            },
                            colors: color,
                            title: {
                                text: titulo
                            },
                            legend: {
                                enabled: false
                            },
                            yAxis: {
                                allowDecimals: false,
                                title: {
                                    text: ytext
                                }, visible: false
                            },
                            tooltip: {
                                formatter: function () {
                                    return '<b>Región:</b> ' + this.point.name + '<br/>' +
                                            this.series.name + ': ' + this.point.y;
                                }
                            }
                        });
                    }
                    $(document).ready(function () {
                        //chart

                        var titulo1 = '<?php echo $titulo1; ?>' + get_descripcion_filtros();
                        var titulo2 = '<?php echo $titulo2; ?>' + get_descripcion_filtros();
                        var titulo3 = '<?php echo $titulo3; ?>' + get_descripcion_filtros();
                        var titulo4 = '<?php echo $titulo4; ?>' + get_descripcion_filtros();
                        var titulo5 = '<?php echo $titulo5; ?>' + get_descripcion_filtros();

                        chart("chrt_inscritos", "table_inscritos", titulo1, "Número de Alumnos Inscritos", ['#0095bc']);
                        chart("chrt_aprobados", "table_aprobados", titulo2, "Número de Alumnos Aprobados", ['#98c56e']);
                        chart("chrt_suspendidos", "table_suspendidos", titulo4, "Número de Alumnos No Aprobados", ['#f05f50']);
                        chart("chrt_no_acceso", "table_no_acceso", titulo5, "Número de Alumnos que nunca entraron", ['#FC6220']);
                        chart("chrt_etm", "table_etm", titulo3, "Porcentaje de Eficiencia Terminal Modificada", ['#f3b510']);
                    });
                </script>
                <?php
            } elseif (isset($comparativa) && count($comparativa) == 0)
            {
                // var_dump($comparativa);
                ?>

                <div class="alert alert-warning">
                    <span>
                        No existen resultados para esa busqueda, intente con otros filtros por favor.
                    </span>
                </div>
                <?php
            }
            ?>
            <!--fin reporte-->

        </div>
    </div>
</div>
</div>
<?php
echo js("comparativa/region.js");
// pr($texts);
// pr($comparativa);
// pr($catalogos);
// pr($filters);
?>
  