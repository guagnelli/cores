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

              <b>Región: Centro</b>

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
            <br>Delegaciones
            <div id="regiones_tree"></div>
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
          Comparativa de Delegaciones (Nivel Táctico)
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
      </div>
    </div>
  </div>
</div>
<!--script src="<?php echo base_url(); ?>assets/hightcharts/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/hightcharts/exporting.js" type="text/javascript"></script-->
<script type="text/javascript">
 $(document).ready(function(){
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
          return '<b>Delegación: </b>' + columna + '<br/><b>' +
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
           stack: 'Chiapas'
       }, {
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Chiapas'
       },{
           name: 'Capacitación',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Chiapas'
       }, {
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'DF Sur'
       },{
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'DF Sur'
       }, {
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'DF Sur'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },
     ],

   });

   Highcharts.chart('comparativa_chrt2', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Tipos de curso por delegación'
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
          return '<b>Delegación: </b>' + columna + '<br/><b>' +
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
           stack: 'Chiapas'
       }, {
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Chiapas'
       },{
           name: 'ENF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Chiapas'
       }, {
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'DF sur'
       },{
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'DF sur'
       }, {
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'DF sur'
       },{
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'MNF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Morelos'
       },{
           name: 'MF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },{
           name: 'MNF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },{
           name: 'ENF',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Veracruz sur'
       },
     ],

   });
//tree
var SOURCE = [
   {title: "Tipo de curso", selected: true, tooltip: "Tipo de curso" },
   {title: "Perfil", selected: true,tooltip: "Perfil" },
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
       {title: "Chiapas", selected: true },
       {title: "DF sur", selected: true},
       {title: "Guerrero", selected: true},
       {title: "Morelos", selected: true },
       {title: "Oaxaca", selected: true },
       {title: "Puebla", selected: true },
       {title: "Queretaro", selected: true },
       {title: "Tabasco", selected: true },
       {title: "Tlaxcala", selected: true },
       {title: "Veracruz sur", selected: true },
       {title: "Veracruz nte", selected: true },
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
        $("#regiones_tree").fancytree(CFG);

 });
</script>
