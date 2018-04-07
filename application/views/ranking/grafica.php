<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$titulo = ($filtros['tipo'] == 1 || $filtros['tipo'] == '' ? 'Número de Alumnos Aprobados' : 'Eficiencia terminal modificada');
$color = ($filtros['tipo'] == 1 || $filtros['tipo'] == '' ? '#98c56e' : '#f3b510');
?>
<?php
if (empty($datos))
{
    ?>
    <script type="text/javascript">
        $(document).ready(function () {            
            $('#alert-ranking').css('display', 'block');            
        });
    </script>    
    <?php
} else
{
    ?>
    <script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
    <script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
    <div id="area_graph"></div>


    <?php echo js("chart_options.js"); ?>
    <script type="text/javascript">
        var titulo = "<?php echo $titulo; ?>" + get_descripcion_filtros();
        $(document).ready(function () {
            //chart
            sortTable("table_ranking");
            chart("area_graph", "table_ranking", titulo, titulo, ['<?php echo $color; ?>']);
        });
    </script>
    <?php
}
?>