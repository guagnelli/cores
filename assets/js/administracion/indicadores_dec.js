/*
 * @author AleSpock
 * @return
 */
  // var catalogos_dec = {
  //
  //
  //
  // }
$(function () {
    var grid = $("#jsGrid_dec").jsGrid({

      // var id_value = null;
      // var matricula_value = '';
      // var nombre_value = "";
      // var curp_value = null;
      // var delegacion_value = "";
        height: "400px",
        width: "100%",
        deleteButton: true,
        // confirmDeleting: true,
        // deleteConfirm: "¿Estás seguro de borrar este registro?",
        // deleteButtonTooltip: "Borrar registro",
        filtering: true,
        inserting: true,
        editing: true,
        sorting: true,
        selecting: true,
        paging: true,
        autoload: true,
        pageSize: 15,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last} {pageIndex} de {pageCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No existe ningún registro",
        controller: {
            loadData: function(filter) {
                var d = $.Deferred();

                $.ajax({
                    type: "GET",
                    url: site_url + "/indicadores_dec/mostrar_dec",
                    data:filter,
                    datatype: "json"
                }).done(function(data){
                      console.log(data);
            					var res = $.grep(data, function (item) {
            						  return (!filter.cve_presupuestal || (item.cve_presupuestal !== null && filter.cve_presupuestal === item.cve_presupuestal))
            							&& (!filter.numerador || (item.numerador !== null && filter.numerador === item.numerador))
                          && (!filter.denominador || (item.denominador !== null && filter.denominador === item.denominador))
                          && (!filter.trimestre || (item.trimestre !== null && filter.trimestre === item.trimestre))
                          && (!filter.porcentaje_aprobados || (item.porcentaje_aprobados !== null && filter.porcentaje_aprobados === item.porcentaje_aprobados))
                          && (!filter.anio || (item.anio !== null && filter.anio === item.anio));
            					});
                      d.resolve(res);
                  });
                  return d.promise();
            },

            updateItem: function(item){
            var de = $.Deferred();
             $.ajax({
                 type: "POST",
                 url: site_url + "/indicadores_dec/actualizar_dec",
                 data: item,
                 datatype: "json"
             }).done(function (json) {
                 console.log('success');
                 console.log (json);
                 //alert(json.message);
                 if (json.success) {
                     de.resolve(json.data);
                 } else {
                     de.resolve(grid._lastPrevItemUpdate);
                 }
             }).fail(function (jqXHR, error, errorThrown) {
                 console.log("error");
                 console.log(jqXHR);
                 console.log(error);
                 console.log(errorThrown);
             });
             return de.promise();
           },

           deleteItem: function(item){
              var del = $.Deferred();
               $.ajax({
                   type: "POST",
                   url: site_url + "/indicadores_dec/eliminar_dec",
                   data: item
               }).done(function (json) {
                   console.log('success');
                   //alert(json.message);
                   del.resolve(json.data);
               }).fail(function (jqXHR, error, errorThrown) {
                   console.log("error");
                   console.log(jqXHR);
                   console.log(error);
                   console.log(errorThrown);
               });
               return del.promise();
          },
          insertItem: function (item) {

                var dei = $.Deferred();
                console.log(item);
                $.ajax({
                    type: "POST",
                    url: site_url + "/indicadores_dec/nuevo_dec",
                    data: item
                })
                        .done(function (json) {
                          //  alert(json['message']);
                            console.log(json);
                            grid.insertSuccess = json['success'];
                            dei.resolve(json['data']);
                        })
                        .fail(function (jqXHR, error, errorThrown) {
                            console.log("error");
                            console.log(jqXHR);
                            console.log(error);
                            console.log(errorThrown);
//                            $(this).jsGrid("destroy");
                        });
                return dei.promise();
            }


        },

        fields: [
            // {name: "id_indicador", title: "id_indicador", type: "number", align: "center", visible:false},
            {name: "cve_presupuestal", title: "Clave presupuestal", type: "text", align: "center"},
            {name: "numerador", title: "Numerador", type: "number", align: "center"},
            {name: "denominador", title: "Denominador", type: "number", align: "center", width:'200px'},
            {name: "trimestre", title: "Trimestre", type: "number", align: "center"},
            {name: "porcentaje_aprobados", title: "Porcentaje aprobados", type: "number", align: "center"},
            {name: "anio", title: "Año", type: "number", align: "center"},
            {name: "id_programa_proyecto", title: "Programa proyecto", type: "number",

             // items: json_catalogos, valueField: "id_programa_proyecto", textField: "nombre"
           },
            {type: "control",editButton: true, deleteButton: true}
        ]
    });
    //$("#jsGrid_dec").jsGrid("option", "filtering", true);
});
// function obtener_catalogos_proyectos() {
//     var arr_header = {
//         clave_implementacion: 'Nombre corto',
//         clave_curso: 'Clave de curso',
//         nombre_curso: 'Nombre del curso',
//         //profesor_titular: 'Profesor titular',
//         ciefd: 'CIEFD',
//         anio: 'Año',
//         estado_implementacion: 'Estado',
//         acciones: 'Acciones',
//     }
//
//     return arr_header;
// }
