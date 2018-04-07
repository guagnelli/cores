<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/drilldown.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<?php echo js('informacion_general.js');
//pr($_SESSION['usuario']);
if(isset($catalogos['agrupamiento']) AND !empty($catalogos['agrupamiento'])) {
    $id_delegacion = $this->session->userdata('usuario')['id_delegacion'];
    $nombre_delegacion = $this->session->userdata('usuario')['name_delegacion'];
} else {
    $id_delegacion = $this->session->userdata('usuario')['grupo_delegacion'];
    $nombre_delegacion = $this->session->userdata('usuario')['nombre_grupo_delegacion'];
} 
?>
<div class="row">
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <div id="filtros" class="col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header" data-background-color="green" data-toggle="collapse" data-target="#perfil_tree_capa">
                    <a href="#" data-toggle="collapse" data-target="#filtros_capa"><?php echo $lenguaje['filtros']; ?><i class="fa fa-arrow-right pull-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="perfil_tree_capa" class="card-content">
                    <?php switch ($grupo_actual) {
                        case En_grupos::NIVEL_CENTRAL: case En_grupos::ADMIN: case En_grupos::SUPERADMIN: ?>
                            <div id="tipos_busqueda_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipos_busqueda',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipos_busqueda'],
                                        'first' => array(''=>$lenguaje['seleccione']),
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: validar_tipos_busqueda('#tipos_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript:$('#capa_periodo_principal').html($('#anio').val()); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="tipo_grafica_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo_grafica']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipo_grafica',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipo_grafica'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="region_capa" class="col-lg-4 col-md-6 col-sm-12" style="display:none;">
                                <label class="control-label"><br><?php echo $lenguaje['region']; ?></label>
                                <?php
                                echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'region',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['regiones'],
                                        'first' => array(''=>$lenguaje['seleccione']),
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: limpiar_capas(['nivel_atencion_capa', 'tipo_unidad_capa', 'umae_capa', 'unidad_capa'], ['delegacion']); if($('#tipos_busqueda').val()=='umae') { data_ajax_listado(site_url+'/informacion_general/cargar_listado/tipo_unidad', '#form_busqueda', '#tipo_unidad_capa'); } else { data_ajax_listado(site_url+'/informacion_general/cargar_listado/ud', '#form_busqueda', '#'+$('#tipos_busqueda').val()+'_capa'); }"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="capa_agrupamiento_delegacion" class="col-lg-4 col-md-6 col-sm-12 agrupamiento" style="display:none;">
                                <?php if(isset($catalogos['agrupamiento']) AND !empty($catalogos['agrupamiento'])) { ?>
                                    <div>
                                        <div>
                                            <label class="control-label"><i class="material-icons md-18 cores-helper" data-help="agrupamiento">help</i><?php echo $lenguaje['agrupamiento']; ?></label>
                                            <?php echo $this->form_complete->create_element(
                                                array(
                                                    'id'=>'agrupamiento',
                                                    'type'=>'dropdown',
                                                    'options'=>$catalogos['agrupamiento'],
                                                    'attributes'=>array(
                                                        'class'=>'form-control',
                                                        'onchange'=>"javascript:obtener_umae(site_url+'/informacion_general/obtener_delegacion', '#form_busqueda', '#delegacion'); limpiar_capas(['nivel_atencion_capa', 'tipo_unidad_capa', 'umae_capa', 'unidad_capa'], []); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                                        //'onchange'=>"javascript:data_ajax_listado(site_url+'/informacion_general/cargar_listado/tipo_unidad', '#form_busqueda', '#tipo_unidad_capa'); limpiar_capas(['nivel_atencion_capa', 'tipo_unidad_capa', 'umae_capa', 'unidad_capa'], []); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                                        )
                                                    )
                                                );
                                            ?>
                                            <span class="material-input"></span>
                                        </div>
                                        <span class="material-input"></span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="delegacion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="nivel_atencion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="tipo_unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="capa_agrupamiento_umae" class="col-lg-4 col-md-6 col-sm-12 agrupamiento" style="display:none;">
                                <?php if(isset($catalogos['agrupamiento_umae']) AND !empty($catalogos['agrupamiento_umae'])) { ?>
                                    <div>
                                        <div>
                                            <label class="control-label"><i class="material-icons md-18 cores-helper" data-help="agrupamiento">help</i> <?php echo $lenguaje['agrupamiento']; ?></label>
                                            <?php echo $this->form_complete->create_element(
                                                array(
                                                    'id'=>'agrupamiento_umae',
                                                    'type'=>'dropdown',
                                                    'options'=>$catalogos['agrupamiento_umae'],
                                                    'attributes'=>array(
                                                        'class'=>'form-control',
                                                        //'onchange'=>"javascript:obtener_umae(site_url+'/informacion_general/obtener_umae', '#form_busqueda', '#umae'); limpiar_capas([], []); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                                        'onchange'=>"javascript:$('#tipo_unidad').val(''); data_ajax_listado(site_url+'/informacion_general/cargar_listado/tipo_unidad', '#form_busqueda', '#tipo_unidad_capa'); limpiar_capas(['nivel_atencion_capa', 'tipo_unidad_capa', 'umae_capa', 'unidad_capa'], []);"
                                                        )
                                                    )
                                                );
                                            ?>
                                            <span class="material-input"></span>
                                        </div>
                                        <span class="material-input"></span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="umae_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <script type="text/javascript">
                            function validar_tipos_busqueda(elemento){
                                mostrar_loader();
                                var valor = $(elemento).val();
                                if($(elemento).val()==''){
                                    $('#region_capa').hide();
                                } else {
                                    $('#region').val('');
                                    $('#region_capa').show();
                                }
                                $.each($('.agrupamiento'), function( key, value ) {
                                    //if($(this).attr('id')=='capa_agrupamiento_'+valor) {
                                    if('delegacion'==valor) {
                                        $('#capa_agrupamiento_'+valor).show();
                                        $('#capa_agrupamiento_umae').hide();
                                    } else {
                                        $('#'+$(this).attr('id')).hide();
                                    }
                                });
                                $('#umae_capa').html('');
                                $('#unidad_capa').html('');
                                $('#delegacion_capa').html('');
                                $('#nivel_atencion_capa').html('');
                                $('#tipo_unidad_capa').html('');
                                $('#comparativa_chrt').html('');
                                $('#comparativa_chrt2').html('');
                                calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
                            }
                            </script>
                        <?php break;
                        case En_grupos::N3_JSPM:
                            $tipos_busqueda = ($this->session->userdata('usuario')['umae']==true) ? 'umae' : 'delegacion';
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>'tipos_busqueda',
                                    'type'=>'hidden',
                                    'value'=>$tipos_busqueda
                                )
                            );?>
                            <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript:$('#capa_periodo_principal').html($('#anio').val()); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="tipo_grafica_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo_grafica']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipo_grafica',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipo_grafica'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: if(($('#unidad_capa').html().length > 0) || ($('#unidad').val()!='')){ calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');} else { alert('Debe seleccionar los otros filtros antes de cambiar el tipo de gráfica.'); }"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <?php echo $this->form_complete->create_element(
                                array(
                                    'id'=>'region',
                                    'type'=>'hidden',
                                    'value'=>$this->session->userdata('usuario')['id_region']
                                )
                            ); ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                                <div class="form-group form-group-sm">
                                    <?php echo $this->session->userdata('usuario')['name_region']; ?>
                                </div>
                                <span class="material-input"></span><br>
                            </div>
                            <!--<div id="delegacion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>-->
                            <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'delegacion',
                                        'type'=>'hidden',
                                        'value'=>$id_delegacion
                                    )
                                ); ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="control-label"><?php echo $lenguaje['delegacion']; ?></label>
                                    <div class="form-group form-group-sm">
                                        <?php echo $nombre_delegacion; ?>
                                    </div>
                                    <span class="material-input"></span>
                                </div>
                            <div id="nivel_atencion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="tipo_unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <script type="text/javascript">
                                $(function(){
                                    //data_ajax_listado(site_url+'/informacion_general/cargar_listado/ud', '#form_busqueda', '#delegacion_capa'); limpiar_capas([]);
                                    data_ajax_listado(site_url+'/informacion_general/cargar_listado/nivel_atencion', '#form_busqueda', '#nivel_atencion_capa'); $('#comparativa_chrt').html(''); $('#comparativa_chrt2').html('');
                                });
                                function validar_tipos_busqueda(elemento){
                                    mostrar_loader();
                                    $('#umae_capa').html('');
                                    $('#unidad_capa').html('');
                                    //$('#delegacion').val('');
                                    //$('#nivel_atencion_capa').html('');
                                    $('#nivel_atencion').val('');
                                    $('#tipo_unidad_capa').html('');
                                    $('#comparativa_chrt').html('');
                                    $('#comparativa_chrt2').html('');
                                    calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
                                }
                                </script>
                        <?php break;
                        case En_grupos::N2_CPEI: case En_grupos::N2_CAME:
                            $tipos_busqueda = ($this->session->userdata('usuario')['umae']==true) ? 'umae' : 'delegacion';
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>'tipos_busqueda',
                                    'type'=>'hidden',
                                    'value'=>$tipos_busqueda
                                )
                            );?>
                            <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript:$('#capa_periodo_principal').html($('#anio').val()); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="tipo_grafica_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo_grafica']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipo_grafica',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipo_grafica'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: if(($('#unidad_capa').html().length > 0) || ($('#umae_capa').html().length > 0) || ($('#unidad').val()!='')){ calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');} else { alert('Debe seleccionar los otros filtros antes de cambiar el tipo de gráfica.'); }"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <?php echo $this->form_complete->create_element(
                                array(
                                    'id'=>'region',
                                    'type'=>'hidden',
                                    'value'=>$this->session->userdata('usuario')['id_region']
                                )
                            ); ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                                <div class="form-group form-group-sm">
                                    <?php echo $this->session->userdata('usuario')['name_region']; ?>
                                </div>
                                <span class="material-input"></span><br>
                            </div>

                            <?php
                            echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'delegacion',
                                        'type'=>'hidden',
                                        'value'=>$id_delegacion
                                    )
                                ); ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="control-label"><?php echo $lenguaje['delegacion']; ?></label>
                                    <div class="form-group form-group-sm">
                                        <?php echo $nombre_delegacion; ?>
                                    </div>
                                    <span class="material-input"></span>
                                </div>
                                <div id="nivel_atencion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                                <div id="tipo_unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <?php /*echo $this->form_complete->create_element( ///Necesario para este tipo de rol(grupo_categoria)
                                array(
                                    'id'=>'tipo_unidad',
                                    'type'=>'hidden',
                                    'value'=>$this->session->userdata('usuario')['id_tipo_unidad']
                                )
                            );*/ ?>
                            <script type="text/javascript">
                            $(function(){
                                data_ajax_listado(site_url+'/informacion_general/cargar_listado/nivel_atencion', '#form_busqueda', '#nivel_atencion_capa'); $('#comparativa_chrt').html(''); $('#comparativa_chrt2').html('');
                            });
                            function validar_tipos_busqueda(elemento){
                                mostrar_loader();
                                $('#umae_capa').html('');
                                $('#unidad_capa').html('');
                                $('#nivel_atencion').val('');
                                $('#tipo_unidad_capa').html('');
                                $('#comparativa_chrt').html('');
                                $('#comparativa_chrt2').html('');
                                calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
                            }
                            </script>
                            <div id="unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>

                        <?php break;
                        case En_grupos::N2_DGU:
                            //if(in_array($grupo_actual, array(En_grupos::N1_DEIS, En_grupos::N1_DM, En_grupos::N1_JDES))) { ///En caso de ser UMAE se obtiene el termino de agrupamiento
                                echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'agrupamiento_umae',
                                        'type'=>'hidden',
                                        'value'=>$this->config->item('agrupamiento')['AGRUPAR']['id']
                                    )
                                );
                                /*echo $this->form_complete->create_element( ///Necesario para este tipo de rol(grupo_categoria)
                                    array(
                                        'id'=>'tipo_unidad',
                                        'type'=>'hidden',
                                        'value'=>$this->session->userdata('usuario')['id_tipo_unidad']
                                    )
                                );
                                $tipos_busqueda = 'umae';*/
                            //}
                            $tipos_busqueda = ($this->session->userdata('usuario')['umae']==true) ? 'umae' : 'delegacion';
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>'tipos_busqueda',
                                    'type'=>'hidden',
                                    'value'=>$tipos_busqueda
                                )
                            );?>
                            <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: $('#capa_periodo_principal').html($('#anio').val());  calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="tipo_grafica_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo_grafica']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipo_grafica',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipo_grafica'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <?php echo $this->form_complete->create_element(
                                array(
                                    'id'=>'region',
                                    'type'=>'hidden',
                                    'value'=>$this->session->userdata('usuario')['id_region']
                                )
                            ); ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                                <div class="form-group form-group-sm">
                                    <?php echo $this->session->userdata('usuario')['name_region']; ?>
                                </div>
                                <span class="material-input"></span><br>
                            </div>
                            <div id="tipo_unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="umae_capa" class="col-lg-4 col-md-6 col-sm-12"></div>

                            <script type="text/javascript">
                            $(function(){
                                data_ajax_listado(site_url+'/informacion_general/cargar_listado/tipo_unidad', '#form_busqueda', '#tipo_unidad_capa');
                            });
                            function validar_tipos_busqueda(elemento){
                                mostrar_loader();
                                $('#umae_capa').html('');
                                $('#unidad_capa').html('');
                                $('#tipo_unidad').val('');
                                $('#comparativa_chrt').html('');
                                $('#comparativa_chrt2').html('');
                                calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
                            }
                            </script>

                        <?php break;
                        case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES:
                            $tipos_busqueda = 'delegacion';
                            if(in_array($grupo_actual, array(En_grupos::N1_DEIS, En_grupos::N1_DM, En_grupos::N1_JDES))) { ///En caso de ser UMAE se obtiene el termino de agrupamiento
                                echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'agrupamiento_umae',
                                        'type'=>'hidden',
                                        'value'=>$this->config->item('agrupamiento')['AGRUPAR']['id']
                                    )
                                );
                                echo $this->form_complete->create_element( ///Necesario para este tipo de rol(grupo_categoria)
                                    array(
                                        'id'=>'tipo_unidad',
                                        'type'=>'hidden',
                                        'value'=>$this->session->userdata('usuario')['id_tipo_unidad']
                                    )
                                );
                                $tipos_busqueda = 'umae';
                            }
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>'tipos_busqueda',
                                    'type'=>'hidden',
                                    'value'=>$tipos_busqueda,
                                )
                            );?>
                            <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'anio',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['implementaciones'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript:$('#capa_periodo_principal').html($('#anio').val()); calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <div id="tipo_grafica_capa" class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['tipo_grafica']; ?></label>
                                <?php echo $this->form_complete->create_element(
                                    array(
                                        'id'=>'tipo_grafica',
                                        'type'=>'dropdown',
                                        'options'=>$catalogos['tipo_grafica'],
                                        'attributes'=>array('class'=>'form-control',
                                            'onchange'=>"javascript: calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');"
                                        )
                                    )
                                ); ?>
                                <span class="material-input"></span>
                            </div>
                            <?php echo $this->form_complete->create_element(
                                array(
                                    'id'=>'region',
                                    'type'=>'hidden',
                                    'value'=>$this->session->userdata('usuario')['id_region']
                                )
                            ); ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                                <div class="form-group form-group-sm">
                                    <?php echo $this->session->userdata('usuario')['name_region']; ?>
                                </div>
                                <span class="material-input"></span><br>
                            </div>
                            <?php 
                            if($this->session->userdata('usuario')['umae']==true) {
                                $titulo = 'umae';
                                $valor_unidad = $this->session->userdata('usuario')['unidad_principal'];
                            } else {
                                $titulo = 'unidad';
                                $valor_unidad = $this->session->userdata('usuario')['id_unidad_instituto'];
                                echo $this->form_complete->create_element(
                                        array(
                                            'id'=>'delegacion',
                                            'type'=>'hidden',
                                            'value'=>$id_delegacion
                                        )
                                    ); ?>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label class="control-label"><?php echo $lenguaje['delegacion']; ?></label>
                                        <div class="form-group form-group-sm">
                                            <?php echo $nombre_delegacion; ?>
                                        </div>
                                        <span class="material-input"></span>
                                    </div>
                            <?php }
                            echo $this->form_complete->create_element(
                                array(
                                    'id'=>$titulo,
                                    'type'=>'hidden',
                                    'value'=>$valor_unidad
                                )
                            ); ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="control-label"><?php echo $lenguaje[$titulo]; ?></label>
                                <div id="<?php echo $titulo.'_titulo'; ?>" class="form-group form-group-sm">
                                    <?php echo $this->session->userdata('usuario')['name_unidad_ist']; ?>
                                </div>
                                <span class="material-input"></span>
                            </div>
                            <script type="text/javascript">
                            $(function(){
                                calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
                            });
                            function validar_tipos_busqueda(elemento){
                                mostrar_loader();
                                $('#comparativa_chrt').html('');
                                $('#comparativa_chrt2').html('');
                                ocultar_loader();
                            }
                            </script>
                            <div id="unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                            <div id="umae_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                        <?php break;

                    } ?>                    
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <input type="button" id="btn_limpiar" name="btn_limpiar" class="btn btn-secondary pull-right" value="<?php echo $lenguaje['limpiar_filtros'];?>">
                        <input type="button" id="btn_buscar" name="btn_buscar" class="btn btn-primary pull-right" value="<?php echo $lenguaje['buscar'];?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div id="tabla_tipo_curso">
                    <div id="comparativa_chrt" style="min-width: 310px; margin: 0 auto">
                </div>
            </div><br><br>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div id="tabla_perfil">
                    <div id="comparativa_chrt2" style="min-width: 310px; margin: 0 auto">
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
    $(function(){
        $('#capa_periodo_principal').html($('#anio').val());
        $('#btn_limpiar').click(function() {
            validar_tipos_busqueda('#tipos_busqueda');
        });
        $('#btn_buscar').click(function() {
            //$('#tipos_busqueda').val('');
            //validar_tipos_busqueda('#tipos_busqueda');
            calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
        });
    });
</script>