<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/drilldown.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>

<!-- <link href="<?php echo base_url(); ?>assets/third-party/fancytree/lib/prettify.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/prettify.js"></script> -->
<?php echo js('informacion_general.js'); ?>
<div class="row">
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <!-- <h4 class="col-lg-12 col-md-12 col-sm-12"><?php //echo $lenguaje['filtros']; ?></h4> -->
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
                <a href="#" data-toggle="collapse" data-target="#filtros_capa"><?php echo $lenguaje['filtros']; ?><i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
                </a>
            </div>
            <div id="filtros_capa" class="card-content">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- <div class="card">
                        <div class="card-header" data-background-color="blue" data-toggle="collapse" data-target="#periodo_tree_capa">
                            <?php echo $lenguaje['periodo']; ?><div class="material-icons pull-right">keyword_arrow_right</div>
                        </div>
                        <div id="periodo_tree_capa" class="card-content collapse collapse_element"> -->
                            <div>
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            //'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div>
                                <label class="control-label"><?php echo $lenguaje['periodo']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'periodo_seleccion',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['periodo'],
                                        'attributes'=>array('class'=>'form-control',
                                            //'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                        <!-- </div>
                    </div> -->
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!--<div class="card">
                        <div class="card-header" data-background-color="orange" data-toggle="collapse" data-target="#perfil_tree_capa">
                            <?php //echo $lenguaje['perfil']; ?><div class="material-icons pull-right">keyword_arrow_right</div>
                        </div>
                        <div id="perfil_tree_capa" class="card-content collapse collapse_element"> -->
                            <?php echo $lenguaje['perfil']; ?>
                            <div id="perfil_tree"></div>
                            <div><input type="hidden" id="perfil_seleccion" name="perfil_seleccion"></div>
                            <div><input type="hidden" id="perfil_seleccion_rootkey" name="perfil_seleccion_rootkey"></div>
                            <div><input type="hidden" id="perfil_seleccion_node" name="perfil_seleccion_node"></div>
                        <!-- </div>
                    </div> -->
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <!-- <div class="card">
                        <div class="card-header" data-background-color="green" data-toggle="collapse" data-target="#tipo_curso_tree_capa">
                            <?php //echo $lenguaje['tipo_curso']; ?><div class="material-icons pull-right">keyword_arrow_right</div>
                        </div>
                        <div id="tipo_curso_tree_capa" class="card-content collapse collapse_element"> -->
                            <?php echo $lenguaje['tipo_curso']; ?>
                            <div id="tipo_curso_tree"></div>
                            <div><input type="hidden" id="tipo_curso_seleccion" name="tipo_curso_seleccion"></div>
                            <div><input type="hidden" id="tipo_curso_seleccion_rootkey" name="tipo_curso_seleccion_rootkey"></div>
                            <div><input type="hidden" id="tipo_curso_seleccion_node" name="tipo_curso_seleccion_node"></div>
                        <!-- </div>
                    </div> -->
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <input type="button" id="btn_limpiar" name="btn_limpiar" class="btn btn-secondary pull-right" value="<?php echo $lenguaje['limpiar_filtros'];?>">
                    <input type="button" id="btn_buscar" name="btn_buscar" class="btn btn-primary pull-right" value="<?php echo $lenguaje['buscar'];?>">
                    <input type="hidden" id="temporal_tipo_busqueda" name="temporal_tipo_busqueda" value="perfil">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <!-- <div id="div_resultado" class="table-responsive" style="display:none;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center" data-background-color="purple"><?php echo $lenguaje['titulo_principal']; echo imprimir_elemento_html('#div_resultado'); ?></th>
                        </tr>
                        <tr>
                            <th class="text-center"><?php //echo $lenguaje['alumnos_inscritos']; ?></th>
                            <th class="text-center"><?php //echo $lenguaje['alumnos_aprobados']; ?></th>
                            <th class="text-center"><?php //echo $lenguaje['alumnos_no_aprobados']; ?></th>
                            <th class="text-center"><?php //echo $lenguaje['alumnos_no_acceso']; ?></th>
                            <th class="text-center"><?php //echo $lenguaje['eficiencia_terminal']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div id="total_alumnos_inscritos" class="text-center">-</div></td>
                            <td><div id="total_alumnos_aprobados" class="text-center">-</div></td>
                            <td><div id="total_alumnos_no_aprobados" class="text-center">-</div></td>
                            <td><div id="total_alumnos_no_acceso" class="text-center">-</div></td>
                            <td><div id="total_eficiencia_terminal" class="text-center">-</div></td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
            </div> -->
            <div id="container_perfil" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div id="tabla_perfil"></div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div id="tabla_tipo_curso"></div>
        </div>        
    </div>
</div>
<script type="text/javascript">
    <?php
    $sub = array();
    $html = 'var SOURCE = [';
    foreach ($catalogos['subcategorias'] as $key_sub => $subcategoria) {
        $html .= '{"title":"'.$subcategoria['subcategoria'].'", "key":"'.$key_sub.'", "expanded":"true", "selected": false, "children":[';
        if(isset($subcategoria['elementos'])){
            foreach ($subcategoria['elementos'] as $key_ele => $elemento) {
                $html .= '{"title":"'.$elemento.'", "selected": false, "key":'.$key_ele.'},';
            }
            $html = rtrim($html, ',');
        }
        $html .= ']},';
    }
    $html = rtrim($html, ',');
    echo $html.'];';
    $sub = array();
    $html1 = 'var SOURCE2 = [';
    foreach ($catalogos['tipos_cursos'] as $key_tip => $tipos) {
        $html1 .= '{"title":"'.$tipos.'", "key":'.$key_tip.', selected: false, "children":[]},';
    }
    $html1 = rtrim($html1, ',');
    echo $html1.'];';
    ?>
    /*function limpiar_filtros_listados(){
        var perfil_tree = $('#perfil_tree').fancytree('getTree');
        perfil_tree.reload(SOURCE);
        var tipo_curso_tree = $('#tipo_curso_tree').fancytree('getTree');
        tipo_curso_tree.reload(SOURCE2);
        $("#temporal_tipo_busqueda").val('');
        setTimeout(function() {   //calls click event after a certain time
           buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        }, 500);
    }*/
    function buscar_filtros_listados(path, form_recurso, recurso, destino) {
        if($("#temporal_tipo_busqueda").val()==""){ //Validamos que este vacío el campo para poder realizar el guardado temporal. Nos indica el sentido de la búsqueda
            $("#temporal_tipo_busqueda").val(recurso);
        }
        if($("#temporal_tipo_busqueda").val()==recurso){
            if($('#perfil_seleccion').val()==''){
                $('#perfil_seleccion').val('-1');
            }
            ///Eliminar datos de tipo_curso debido a que serán recalculados los datos
            $('#tipo_curso_seleccion').val('');
            $('#tipo_curso_seleccion_rootkey').val('');
            $('#tipo_curso_seleccion_node').val('');
            var dataSend = $(form_recurso).serialize();
            //console.log(dataSend);
            $.ajax({
                url: path,
                data: dataSend+'&destino='+destino,
                method: 'POST',
                dataType: 'json',
                beforeSend: function (xhr) {
                    mostrar_loader();
                    $('#no_existe_datos').remove();
                    $('#'+destino+'_tree').hide();
                    $('#div_resultado').hide('slow');
                    $('#container_perfil').html('');
                    $('#tabla_tipo_curso').html('');
                    $('#tabla_perfil').html('');
                }
            })
            .done(function (response) {
                $('#'+destino+'_seleccion').val('');
                $('#'+destino+'_seleccion_rootkey').val('');
                $('#'+destino+'_seleccion_node').val('');
                if(typeof(response.no_datos) != "undefined"){
                    //$('#'+destino+'_tree').html('<?php echo $lenguaje['no_existe_datos']; ?>');
                    $('#'+destino+'_tree').after('<div id="no_existe_datos"><?php echo $lenguaje['no_existe_datos']; ?></div>');
                    $('#'+destino+'_tree').hide();
                    ocultar_loader();
                } else {
                    var tree = $('#'+destino+'_tree').fancytree('getTree');
                    var t = [];
                    $.each(response, function(i, item) {
                        t.push(item)
                    });
                    tree.reload(t);

                    //////////////////////////////////////////////////////////////////
                    // Get a list of all selected nodes, and convert to a key array:
                    var selKeys = $.map(tree.getSelectedNodes(), function(node){
                        return node.key;
                    });
                    $('#'+destino+'_seleccion').val(selKeys.join(","));

                    // Get a list of all selected TOP nodes
                    var selRootNodes = tree.getSelectedNodes(true);
                    // ... and convert to a key array:
                    var selRootKeys = $.map(selRootNodes, function(node){
                        return node.key;
                    });
                    $('#'+destino+'_seleccion_rootkey').val(selRootKeys.join(","));
                    $('#'+destino+'_seleccion_node').val(selRootNodes.join(","));
                    //////////////////////////////////////////////////////////////////

                    $('#'+destino+'_tree').show('slow');
                    $(".collapse_element").collapse("show");
                    //console.log('nooo');
                    buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda', 'Información por perfil');
                }
            })
            .fail(function (jqXHR, textStatus) {
                //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
                ocultar_loader();
                console.log(jqXHR);
                console.log(textStatus);
            })
            .always(function () {
                //ocultar_loader();
            });
        }
    }
    $(function(){
        //buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda', 'Información por perfil');
        $('#btn_buscar').click(function() {
            buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda', 'Información por perfil');
        });
        $( "#btn_limpiar" ).click(function() {
            limpiar_filtros_listados();
        });
        /*$('#periodo_seleccion').change(function() {
            buscar_filtros_listados(site_url+'/informacion_general/buscar_filtros_listados', '#form_busqueda', 'perfil', 'tipo_curso');
        });
        $('#anio').change(function() {
            buscar_filtros_listados(site_url+'/informacion_general/buscar_filtros_listados', '#form_busqueda', 'perfil', 'tipo_curso');
        });*/
        $("#perfil_tree").fancytree({
            checkbox: true,
            selectMode: 3,
            source: SOURCE,
            icon: false,
            /*lazyLoad: function(event, ctx) {
                ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
            },
            loadChildren: function(event, ctx) {
                ctx.node.fixSelection3AfterClick();
            },*/
            select: function(event, data) {
                // Get a list of all selected nodes, and convert to a key array:
                var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
                    return node.key;
                });
                $("#perfil_seleccion").val(selKeys.join(","));

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#perfil_seleccion_rootkey").val(selRootKeys.join(","));
                $("#perfil_seleccion_node").val(selRootNodes.join(","));

                ////Código que permite cambiar las opciones del tree
                //buscar_filtros_listados(site_url+'/informacion_general/buscar_filtros_listados', '#form_busqueda', 'perfil', 'tipo_curso');
            },
            dblclick: function(event, data) {
                data.node.toggleSelected();
            },
            keydown: function(event, data) {
                if( event.which === 32 ) {
                    data.node.toggleSelected();
                    return false;
                }
            },
            init: function (event, data) {
                data.tree.getRootNode().visit(function (node) {
                    if (node.data.preselected) node.setSelected(true);
                });
            },
            // The following options are only required, if we have more than one tree on one page:
            // initId: "SOURCE",
            cookieId: "fancytree-Cb3",
            idPrefix: "fancytree-Cb3-"
        });
        $("#tipo_curso_tree").fancytree({
            checkbox: true,
            selectMode: 3,
            source: SOURCE2,
            /*lazyLoad: function(event, ctx) {
                ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
            },
            loadChildren: function(event, ctx) {
                ctx.node.fixSelection3AfterClick();
            },*/
            icon: false,
            select: function(event, data) {
                // Get a list of all selected nodes, and convert to a key array:
                var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
                    return node.key;
                });
                $("#tipo_curso_seleccion").val(selKeys.join(","));

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#tipo_curso_seleccion_rootkey").val(selRootKeys.join(","));
                $("#tipo_curso_seleccion_node").val(selRootNodes.join(","));

                if($("#tipo_curso_seleccion").val()==''){
                    $('#container_perfil').html('');
                    $('#tabla_tipo_curso').html('');
                    $('#tabla_perfil').html('');
                    //alert('Debe seleccionar algún perfil para mostrar datos.');
                } else {
                    //console.log('si');
                    //buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda', 'Información por perfil');
                }
            },
            dblclick: function(event, data) {
                data.node.toggleSelected();
            },
            keydown: function(event, data) {
                if( event.which === 32 ) {
                    data.node.toggleSelected();
                    return false;
                }
            },
            init: function (event, data) {
                data.tree.getRootNode().visit(function (node) {
                    if (node.data.preselected) node.setSelected(true);
                });
            },
            // The following options are only required, if we have more than one tree on one page:
            // initId: "SOURCE",
            cookieId: "fancytree-Cb2",
            idPrefix: "fancytree-Cb2-"
        });
    });
</script>