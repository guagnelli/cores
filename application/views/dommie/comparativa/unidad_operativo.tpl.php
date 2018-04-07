<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-header" data-background-color="green">
        Filtros
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-md-12">
              <b>Región:</b> Centro<br>
              <b>Delegación:</b> DF Sur<br>
              <b>Tipo de unidad:</b> UMF<br>
              <b>Unidad: </b>UMF 31<br>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group form-group-sm">
              <label class="control-label">Año</label>
              <select class="form-control">
                <option></option>
                <option>2016</option>
                <option>2017</option>
              </select>
              <span class="material-input"></span>
            </div>
          </div>
          <div class="col-md-12">
            <br>Unidades
            <div id="unidades_tree"></div>
          </div>
          <div class="col-md-12">
              <br>Reportes
              <div id="reporte_tree"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card">
      <div class="card-header" data-background-color="purple">
        <h4 class="title">
          Comparativa de unidades (Nivel operativo)
        </h4>
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-md-12">
            <div id="comparativa_chrt" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><br><br><br><br>
            <div id="comparativa_chrt2" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><br><br><br><br>
            <div id="promedio_chrt" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--script src="<?php echo base_url(); ?>assets/hightcharts/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/hightcharts/exporting.js" type="text/javascript"></script-->
<script type="text/javascript">
 $(document).ready(function(){

    // Build the chart
    Highcharts.chart('promedio_chrt', {
          chart: {
              plotBackgroundColor: null,
              plotBorderWidth: null,
              plotShadow: false,
              type: 'pie'
          },
          title: {
              text: 'Promedio de delegacional'
          },
          tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: false
                  },
                  showInLegend: true
              }
          },
          series: [{
              name: 'Promedio',
              colorByPoint: true,
              data: [{
                  name: 'UMF 31 Iztapalapa',
                  y: 12.77
              }, {
                  name: 'Promedio delegacional',
                  y: 87.23,
                  sliced: true,
                  selected: true
              }]
          }]
      });


   Highcharts.chart('comparativa_chrt', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Tipos de curso por delegación '
       },
       subtitle: {
           text: ''
       },
       legend: {
          enabled: false
      },
       xAxis: {
           categories: [
               'Aprobados',
               'No accesos',
               'Inscritos',
               'ETM'
           ],
       },
       yAxis: {
           min: 0,
           title: {
               text: 'Alumnos'
           }
       },
       tooltip: {
         formatter: function () {
           //console.log(this.point);
           var columna = this.series.stackKey.replace('column','');
          return '<b>Unidad: </b>' + columna + '<br/><b>' +
              this.series.name + '</b>: ' + this.y + '<br/>' +
              '<b>Total: </b>' + this.point.stackTotal;

              //
            }
       },
       plotOptions: {
           column: {
               pointPadding: 0.2,
               borderWidth: 0,
               stacking: 'normal',
           }
       },
       series: [
       {
           name: 'Formación',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'UMF 31 Iztapalapa'
       }, {
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 31 Iztapalapa'
       },{
           name: 'Capacitación',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 31 Iztapalapa'
       }, {
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 1 Col. Roma'
       },{
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 1 Col. Roma'
       }, {
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 1 Col. Roma'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },
     ],

   });
   Highcharts.chart('comparativa_chrt2', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Tipos de curso por ión '
       },
       subtitle: {
           text: ''
       },
       legend: {
          enabled: false
      },
       xAxis: {
           categories: [
               'Aprobados',
               'No accesos',
               'Inscritos',
               'ETM'
           ],
       },
       yAxis: {
           min: 0,
           title: {
               text: 'Alumnos'
           }
       },
       tooltip: {
         formatter: function () {
           //console.log(this.point);
           var columna = this.series.stackKey.replace('column','');
          return '<b>Unidad: </b>' + columna + '<br/><b>' +
              this.series.name + '</b>: ' + this.y + '<br/>' +
              '<b>Total: </b>' + this.point.stackTotal;

              //
            }
       },
       plotOptions: {
           column: {
               pointPadding: 0.2,
               borderWidth: 0,
               stacking: 'normal',
           }
       },
       series: [
       {
           name: 'MF',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'UMF 31 Iztapalapa'
       }, {
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 31 Iztapalapa'
       },{
           name: 'ENF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 31 Iztapalapa'
       }, {
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 1 Col. Roma'
       },{
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'UMF 1 Col. Roma'
       }, {
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 1 Col. Roma'
       },{
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'MNF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 4 Doctores'
       },{
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },{
           name: 'MNF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },{
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'UMF 18 Contreras'
       },
     ],

   });
//tree
var SOURCE = [
   {title: "Tipo de curso", selected: true },
   {title: "Perfil", selected: true },
   {title: "Promedio", selected: true },
 ];
 var CFG = {
      checkbox: true,
      selectMode: 3,
      source: SOURCE,
      lazyLoad: function(event, ctx) {
        ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
      },
      loadChildren: function(event, ctx) {
        ctx.node.fixSelection3AfterClick();
      },
      select: function(event, data) {
        // Get a list of all selected nodes, and convert to a key array:
        var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
          return node.key;
        });
        $("#echoSelection3").text(selKeys.join(", "));

        // Get a list of all selected TOP nodes
        var selRootNodes = data.tree.getSelectedNodes(true);
        // ... and convert to a key array:
        var selRootKeys = $.map(selRootNodes, function(node){
          return node.key;
        });
        $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
        $("#echoSelectionRoots3").text(selRootNodes.join(", "));
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
      // The following options are only required, if we have more than one tree on one page:
//        initId: "SOURCE",
      cookieId: "fancytree-Cb3",
      idPrefix: "fancytree-Cb3-"
    };
    $("#reporte_tree").fancytree(CFG);

    var SOURCE = [
       {title: "UMF 1 Col. Roma", selected: true },
       {title: "UMF 4 Doctores", selected: true},
       {title: "UMF 9 San Pedro Pinos", selected: true},
       {title: "UMF 18 Contreras", selected: true },
       {title: "UMF 19 Coyoacán", selected: true },
       {title: "UMF 28 Del Valle", selected: true },
       {title: "UMF 38 CFE. Parque España", selected: true },
       {title: "UMF 39 CFE. Xola", selected: true },
       {title: "UMF 140 La Teja", selected: true },
       {title: "UMF 22 Independecia", selected: true },
       {title: "UMF 12 Santa Fe", selected: true },
       {title: "UMF 7 Calz. Tlalpan", selected: true },
       {title: "UMF 12 Santa Fe", selected: true },
       {title: "UMF 15 Ermita Iztapalapa", selected: true },
       {title: "UMF 46 Soriano", selected: true },
       {title: "UMF 21 Fco. del Paso", selected: true },
       {title: "UMF 31 Iztapalapa", selected: true },
       {title: "UMF 160 El Vergel", selected: true },
       {title: "UMF 43 Rojo Gómez", selected: true },
       {title: "UMF 45 Iztacalco", selected: true },
     ];
     var CFG = {
          checkbox: true,
          selectMode: 3,
          source: SOURCE,
          lazyLoad: function(event, ctx) {
            ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
          },
          loadChildren: function(event, ctx) {
            ctx.node.fixSelection3AfterClick();
          },
          select: function(event, data) {
            // Get a list of all selected nodes, and convert to a key array:
            var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
              return node.key;
            });
            $("#echoSelection3").text(selKeys.join(", "));

            // Get a list of all selected TOP nodes
            var selRootNodes = data.tree.getSelectedNodes(true);
            // ... and convert to a key array:
            var selRootKeys = $.map(selRootNodes, function(node){
              return node.key;
            });
            $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
            $("#echoSelectionRoots3").text(selRootNodes.join(", "));
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
          // The following options are only required, if we have more than one tree on one page:
    //        initId: "SOURCE",
          cookieId: "fancytree-Cb3",
          idPrefix: "fancytree-Cb3-"
        };
        $("#unidades_tree").fancytree(CFG);

 });
</script>
