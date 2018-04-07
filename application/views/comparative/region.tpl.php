
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<!--titulo-->
<div class="col-md-12">
    <div class="card">
        <div class="card-header" data-background-color="">
            <h4 class="title"><?php echo isset($texts["title"]) ? $texts["title"] : "" ?></h4>
            <p class="category"><?php echo isset($texts["descripcion"]) ? $texts["descripcion"] : "" ?></p>
        </div>
        <div class="card-content">
            <!-- filtros-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-icon" data-background-color="green" >
                            <h4 class="card-title"><i class="fa fa-filter" aria-hidden="true"></i> Filtros<i class="material-icons cores-helper" data-help="comparativa_region">help</i></h4>
                        </div>
                        <div class="card-content" id="div-filters">
                            <div class="row">                                
                                <?php if(is_nivel_central($usuario['grupos'])){
                                    $tipo_display = 'block';
                                }else{
                                    $tipo_display = 'none';
                                }
                                ?>
                                <div class="col-md-2" style="display:<?php echo $tipo_display;?>">
                                    <?php                                    
                                    $cfg = array(
                                        "title" => "Tipo",
                                        'id' => "tipo",
                                    );
                                    echo dropdown($cfg,array(0=>'Delegacional', 1=>'UMAE'));
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $cfg = array(
                                        "title" => "Reporte",
                                        'id' => "tipo_reporte",
                                    );
                                    echo dropdown($cfg, $catalogos["reporte"]);
                                    ?>
                                </div>
                                <div class="col-md-2" id="div_p" style="display: none;">
                                    <?php
                                    $cfg = array(
                                        "title" => "Perfil",
                                        'id' => "perfil",
                                        "subseccion" => "descripcion",
                                        "id_sub" => "id_grupo_categoria",
                                    );
                                    echo dropdown($cfg, $catalogos["subcategorias"], $catalogos["perfil"]);
                                    ?>
                                </div>
                                <div class="col-md-2" id="div_tc" style="display: none;">
                                    <?php
                                    $cfg = array(
                                        "title" => "Tipo de curso",
                                        'id' => "tipo_curso",
                                    );
                                    echo dropdown($cfg, $catalogos["tipos_cursos"]);
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $cfg = array(
                                        "title" => "Año",
                                        'id' => "anio",
                                    );
                                    echo dropdown($cfg, $catalogos["implementaciones"]);
                                    ?>
                                </div>
                                <div class="col-md-3">
                                    <h4><b>Filtros seleccionados</b></h4>
                                    <b><span id="span_type">
                                            <?php echo isset($filters["type"]) ? $filters["type"] . ": " : ""; ?>  
                                        </span></b>
                                    <span id="span_num">
                                        <?php echo isset($filters["num"]) ? $filters["num"] : ""; ?>
                                    </span><br>
                                    <span id="span_anio">
                                        <?php echo isset($filters["year"]) ? "<b>Año: </b>" . $filters["year"] : ""; ?>
                                    </span><br>

                                    <button class="btn btn-primary btn-round" id="submit" data-id="region/analizar">
                                        <i class="material-icons">search</i>Realizar búsqueda
                                        <div class="ripple-container"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--fin filtros-->
            <?php
            $inscritos = "";
            $aprobados = "";
            $suspendidos = "";
            $etm = "";
            $chart_title = "Comparativa de regiones";
            $filtros = "";
            if (isset($comparativa) && is_array($comparativa) && !empty($comparativa))
            {
                foreach ($comparativa as $region)
                {
                    $class_region = '';
                    if($region['region'] == $usuario['name_region']){
                        $class_region = 'current_comparativa_region';
                        $region['region'] = '<strong>* '.$region['region'].'</strong>';
                    }
//                     pr($region['region'].' '.$usuario['name_region']);
                    $inscritos .= "<tr class='".$class_region."'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["inscritos"] . "</td>
                            </tr>";
                    $aprobados .= "<tr class='".$class_region."'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["aprobados"] . "</td>
                            </tr>";
                    $suspendidos .= "<tr class='".$class_region."'>
                              <th>" . $region["region"] . "</th>
                              <td>" . $region["suspendidos"] . "</td>
                            </tr>";
                    $etm .= "<tr class='".$class_region."'>
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
                                                <a href="#etm" data-toggle="tab">
                                                    Eficiencia Terminal Modificada
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
                        chart("chrt_inscritos", "table_inscritos", "", "Número de Alumnos Inscritos", ['#0095bc']);
                        chart("chrt_aprobados", "table_aprobados", "", "Número de Alumnos Aprobados", ['#98c56e']);
                        chart("chrt_suspendidos", "table_suspendidos", "", "Número de Alumnos suspendidos", ['#f05f50']);
                        chart("chrt_etm", "table_etm", "", "Porcentaje de Eficiencia Terminal Modificada", ['#f3b510']);
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
  