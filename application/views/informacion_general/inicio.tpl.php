<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php echo js('informacion_general.js'); ?>
<style type="text/css">
.table{
    margin: 0px 50px;
    width: 90%;
    color:#808788;
}
.nav-tabs > li > a {
    background-color: #7babab;
    color: #009b9b;
    padding: 30px;
}
.nav-tabs > li.active > a{
    /*background-color: #009b9b;*/
    background-color: #eceeee;
    color: #009b9b !important;
}
.nav-tabs > li > a:hover{
    background-color: #689090;
}
.table-striped>tbody>tr:nth-of-type(even){
   background-color:#cadddd;
}
.table-striped>tbody>tr:nth-of-type(odd){
   background-color:#FFF;
}
.ultima_actualizacion {
    font-size: 14px;
    color: #808788;
}
</style>
<div class="row">
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div>
                <label class="control-label"><?php echo $lenguaje['anio']; ?>: </label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'anio',
                        'type'=>'dropdown',
                        'options'=>$catalogos['implementaciones'],
                        'attributes'=>array('class'=>'form-control',
                            'onchange'=>"javascript:desplegar_cursos(this);"
                        )
                    )
                ); ?>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <div class="pull-right ultima_actualizacion"><?php echo $lenguaje['ultima_actualizacion'].': '.$cargas[0]['ultima_actualizacion'];?></div><br>
            <a href="<?php echo site_url('informacion_general'); ?>" class="btn btn-primary pull-right"><?php echo $lenguaje['ver_datos_generales'];?></a>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">as...</div>
            <div role="tabpanel" class="tab-pane" id="profile">..sd.</div>
            <div role="tabpanel" class="tab-pane" id="messages">.sdf..</div>
        </div>
        <br> -->
        <?php //pr($cursos);
        foreach ($catalogos['implementaciones'] as $key_a => $anio) { ?>
            <div id="anio_<?php echo $anio; ?>" class="card-nav-tabs" style="display:none;">
                <!-- <div class="card-header" style="background-color:#FFF;">
                    <div class="nav-tabs-navigation">
                        
                    </div>
                </div> -->
                <!-- <div class="card-content">
                    <div class="nav-tabs-wrapper"> -->
                <div style="padding:20px;">
                    <!-- <span class="nav-tabs-title">Comparativa de Alumnos:</span> -->
                    <ul class="nav nav-tabs" data-tabs="tabs" style="background-color:#eceeee;">
                        <?php
                        //pr($catalogos);
                        $inc=0;
                        $html_tab = '';
                        foreach ($catalogos['tipos_cursos'] as $tpk => $tipos_cursos) {
                            $class = (($inc==0) ? 'active' : '');
                            echo '<li class="'.$class.'">
                                    <a href="#tc_'.$anio.'_'.$tpk.'" data-toggle="tab">
                                        '.$tipos_cursos.'
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>';

                            $html_tab .= '<div class="tab-pane '.$class.'" id="tc_'.$anio.'_'.$tpk.'">
                                <table class="table table-striped">';
                                if(isset($cursos[$key_a][$tpk])){
                                    foreach ($cursos[$key_a][$tpk] as $key_c => $curso) {
                                        $html_tab .= '<tr><td>'.$curso['nombre'].'</td></tr>';
                                    }
                                } else {
                                    $html_tab .= '<tr><td>'.$lenguaje['no_existen_implementacion'].'</td></tr>';
                                }
                            $html_tab .= '</table>
                                </div>';

                            $inc++;
                        } ?>
                    </ul>                    
                    <div class="tab-content" style="background-color:#eceeee;">
                        <br>
                        <?php echo $html_tab; ?>
                        <br>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
    desplegar_cursos($('#anio'));
});
function desplegar_cursos(elemento){
    mostrar_loader();
    var anio = $(elemento).val();
    $.each($('.card-nav-tabs'), function( index, value ) {
        $(this).hide();
    });
    $('#anio_'+anio).show();
    ocultar_loader();
}
</script>