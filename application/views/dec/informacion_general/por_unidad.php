<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
              <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
              </a>
            </div>
            <?php
            echo js('dec/informacion_general.js');
            echo form_open('dec/informacion_general/por_umae');
            ?>
            <div id="filtros_capa" class="card-content">
                <?php
//pr($usuario);
                if (isset($usuario['central']) && isset($usuario['estrategico']))
                {
                    if($usuario['central']){
                    ?>
                    <div class="col-md-3" id="tu">
                        <div class="input-group input-group-sm">
                            <?php
                              if($usuario['estrategico']){?>
                                  <input style="display:none;" name="region" value="<?php$usuario["id_region"]?>"/>
                            <?php
                              }
                            ?>
                            <span class="input-group-addon">Tipo de unidad:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'tipos_unidades',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $tipos_unidades,
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
                  }?>
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
    if(isset($tipo_unidad)){
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
