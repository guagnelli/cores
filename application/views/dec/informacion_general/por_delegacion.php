<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
              <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
              </a>
            </div>
            <?php
            echo js('dec/informacion_general.js');
            echo form_open('dec/informacion_general/por_delegacion', array('id' => 'fig'));
            ?>
            <div id="filtros_capa" class="card-content">
                <?php
                if (isset($usuario['central']) && isset($usuario['estrategico']) && isset($usuario['tactico']))
                {
                    ?>
                    <div class="row form-group">
                        <?php
                          if($usuario['central']){ ?>
                            <div class="col-md-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">*Region:</span>
                                    <?php
                                      echo $this->form_complete->create_element(
                                              array('id' => 'region',
                                                  'type' => 'dropdown',
                                                  'first' => array('' => 'Seleccione...'),
                                                  'options' => $regiones,
                                                  'attributes' => array(
                                                      'class' => 'form-control  form-control input-sm',
                                                      'data-toggle' => 'tooltip',
                                                      'data-placement' => 'top',
                                                      'onchange' => 'mostrar_delegaciones(this)',
                                                      'required' => null,
                                                      'title' => 'Regiones')
                                              )
                                      );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3" id="del">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">*Delegaciones:</span>
                                    <?php
                                    echo $this->form_complete->create_element(
                                            array('id' => 'delegacion',
                                                'type' => 'dropdown',
                                                'first' => array('' => 'Seleccione...'),
                                                'attributes' => array(
                                                    'class' => 'form-control  form-control input-sm',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'required' => null,
                                                    'title' => 'Delegación',
                                                )
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">*Nivel de atención:</span>
                                    <?php
                                    echo $this->form_complete->create_element(
                                            array('id' => 'nivel',
                                                'type' => 'dropdown',
                                                'first' => array('' => 'Seleccione...'),
                                                'options' => array(1=>'Primer nivel', 2 =>'Segundo nivel'),
                                                'attributes' => array(
                                                    'class' => 'form-control  form-control input-sm',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'onchange' => 'mostrar_tipo_unidades(this)',
                                                    'required' => null,
                                                    'title' => 'Regiones')
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3" id="tu">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">*Tipo de unidad:</span>
                                    <?php
                                    echo $this->form_complete->create_element(
                                            array('id' => 'tipos_unidades',
                                                'type' => 'dropdown',
                                                'first' => array('' => 'Seleccione...'),
                                                'attributes' => array(
                                                    'class' => 'form-control  form-control input-sm',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'required' => null,
                                                    'title' => 'tipos_unidades')
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                        <?php
                      }else{
                        if($usuario['estrategico']){ ?>
                          <div class="col-md-2" style="display:none;">
                            <input style="display:none;" name="region" value="<?php$usuario["id_region"]?>"/>
                          </div>
                          <div class="col-md-3" id="del">
                              <div class="input-group input-group-sm">
                                  <span class="input-group-addon">*Delegaciones:</span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'delegacion',
                                              'type' => 'dropdown',
                                              'first' => array('' => 'Seleccione...'),
                                              'options' => $delegaciones,
                                              'attributes' => array(
                                                  'class' => 'form-control  form-control input-sm',
                                                  'data-toggle' => 'tooltip',
                                                  'data-placement' => 'top',
                                                  'required' => null,
                                                  'title' => 'Delegación',
                                              )
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="input-group input-group-sm">
                                  <span class="input-group-addon">*Nivel de atención:</span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'nivel',
                                              'type' => 'dropdown',
                                              'first' => array('' => 'Seleccione...'),
                                              'options' => array(1=>'Primer nivel', 2 =>'Segundo nivel'),
                                              'attributes' => array(
                                                  'class' => 'form-control  form-control input-sm',
                                                  'data-toggle' => 'tooltip',
                                                  'data-placement' => 'top',
                                                  'onchange' => 'mostrar_tipo_unidades(this)',
                                                  'required' => null,
                                                  'title' => 'Regiones')
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                          <div class="col-md-3" id="tu">
                              <div class="input-group input-group-sm">
                                  <span class="input-group-addon">*Tipo de unidad:</span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'tipos_unidades',
                                              'type' => 'dropdown',
                                              'first' => array('' => 'Seleccione...'),
                                              'attributes' => array(
                                                  'class' => 'form-control  form-control input-sm',
                                                  'data-toggle' => 'tooltip',
                                                  'data-placement' => 'top',
                                                  'required' => null,
                                                  'title' => 'tipos_unidades')
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                      <?php
                        }else{?>
                          <div class="col-md-2" style="display:none;">
                            <input style="display:none;" name="region" value="<?php$usuario["id_region"]?>"/>
                          </div>
                          <div class="col-md-3" id="del" style="display:none;">
                            <input style="display:none;" name="delegacion" value="<?php$usuario["id_delegacion"]?>"/>
                          </div>
                          <div class="col-md-3">
                              <div class="input-group input-group-sm">
                                  <span class="input-group-addon">*Nivel de atención:</span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'nivel',
                                              'type' => 'dropdown',
                                              'first' => array('' => 'Seleccione...'),
                                              'options' => array(1=>'Primer nivel', 2 =>'Segundo nivel'),
                                              'attributes' => array(
                                                  'class' => 'form-control  form-control input-sm',
                                                  'data-toggle' => 'tooltip',
                                                  'data-placement' => 'top',
                                                  'onchange' => 'mostrar_tipo_unidades(this)',
                                                  'required' => null,
                                                  'title' => 'Regiones')
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                          <div class="col-md-3" id="tu">
                              <div class="input-group input-group-sm">
                                  <span class="input-group-addon">*Tipo de unidad:</span>
                                  <?php
                                  echo $this->form_complete->create_element(
                                          array('id' => 'tipos_unidades',
                                              'type' => 'dropdown',
                                              'first' => array('' => 'Seleccione...'),
                                              'attributes' => array(
                                                  'class' => 'form-control  form-control input-sm',
                                                  'data-toggle' => 'tooltip',
                                                  'data-placement' => 'top',
                                                  'required' => null,
                                                  'title' => 'tipos_unidades')
                                          )
                                  );
                                  ?>
                              </div>
                          </div>
                        <?php
                        }
                      }
                        ?>
                    </div>
                    <?php
                }
                ?>
                <br>
                <div class="row">
                    <input style="float: right;margin-right: 50px;" type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
<div class="row card">
    <?php
    if(isset($region) && isset($delegacion) && isset($tipo_unidad)){
    ?>
      <div class="row">
        <div class="col-lg-1 col-md-0 col-sm-0"></div>
        <div class="col-lg-1 col-md-0 col-sm-0"></div>
        <div class="col-lg-1 col-md-0 col-sm-0"></div>
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="blue">
                    <i class="fa fa-user"></i>
                </div>
                <div class="card-content">
                    <p class="category pull-right" style="height:40px; width: 110px;"><?php echo "Unidades totales";?></p>
                    <h4 class="title" id="total_alumnos_inscritos"><?php if(count($datos_totales_unidades['datos']) > 0) {echo $datos_totales_unidades['datos'][0]['total_unidades'];}else{ echo 0;}?></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="green">
                    <i class="fa fa-user"></i>
                </div>
                <div class="card-content">
                    <p class="category pull-right" style="height:40px; width: 120px;"><?php echo "Unidades con programas educativos";?></p>
                    <h4 class="title" id="total_alumnos_aprobados"><?php if(count($datos_totales_unidades_con_programa['datos']) > 0) {echo $datos_totales_unidades_con_programa['datos'][0]['total_unidades'];}else{echo 0;} ?></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header" data-background-color="red">
                    <i class="fa fa-percent"></i>
                </div>
                <div class="card-content">
                    <p class="category pull-right" style="height:40px; width: 120px;"><?php echo "Educación continua"; ?></p>
                    <h4 class="title" id="total_alumnos_no_aprobados"><?php echo $datos_totales_porcentaje; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-1 col-md-0 col-sm-0"></div>
      </div>
      <p style="margin-top:20px;">Resultados</p>
      <div id="secInfoGnral" style="width:100%; height:auto; display:flex; flex-direction:row; margin-top:20px;">
        <section id="sec1" style="width:33%;">
          <p><?php echo "Región: ".$region; ?></p>
          <p><?php echo "Delegación: ".$delegacion; ?></p>
          <p><?php echo "Tipo de unidad: ".$tipo_unidad; ?></p>
        </section>
        <section id="sec2" style="width:33%; display:flex; justify-content: center;">
          <p style="align-self: last baseline;"><?php echo "Interpretacion: 2017"; ?></p>
        </section>
        <section id="sec3" style="width:33%;">
        </section>
      </div>
      <hr>
    <?php
    }
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
<script>
$(function(){
    var filtros = <?php echo json_encode($filtros)?>;
    var delegacion_id = filtros.delegacion;
    var tipos_unidades = filtros.tipos_unidades;
    console.log("Delegacion: ", delegacion);
    if($('#region')[0].value != "" && $('#delegacion')[0].value == "")
    {
        var region = $('#region')[0].value;
        $.ajax({
            method: "GET",
            url: site_url + "/dec/obtener_delegaciones/"+region,
        }).done(function( delegacion ) {
            console.log("Respuesta de delegaciones: ", delegacion);
            if(delegacion.success)
            {
                if(delegacion.datos.length > 0)
                {
                    delegacion.datos.forEach(function(itemDelegacion) {
                        $('#delegacion').append($('<option>', { value: itemDelegacion.id_delegacion, text: itemDelegacion.delegacion}));
                    });
                    $('#delegacion').val(delegacion_id);
                }
                else
                {
                  console.log(delegacion)
                }
            }
            else
            {
              console.log(delegacion)
            }

        });
    }
    if($('#nivel')[0].value != "" && $('#tipos_unidades')[0].value == "")
    {
      var nivel = $('#nivel')[0].value;
      $.ajax({
          method: "GET",
          url: site_url + "/dec/tipos_unidades/"+nivel,
      }).done(function( tipos ) {
          console.log("Respuesta de Tipos de unidades: ", tipos);
          if(tipos.success)
          {
              if(tipos.datos.length > 0)
              {
                  tipos.datos.forEach(function(tipoU) {
                      $('#tipos_unidades').append($('<option>', { value: tipoU.id_tipo_unidad, text: tipoU.nombre}));
                  });
                  $('#tipos_unidades').val(tipos_unidades);
              }
              else
              {
              }
          }
          else
          {
          }

      });
    }
})
</script>
