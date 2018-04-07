/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

$(function () {
    var grid = $("#jsGrid").jsGrid({
        height: "400px",
        width: "100%",
        deleteButton: false,
        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        selecting: false,
        paging: true,
        pageLoading: true,
        autoload: true,
        pageSize: 5,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No existe ningún registro",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: site_url + "/administracion/bitacora/registros/",
                    data: filter
                });
            }
        },
        fields: [
            {name: "id_bitacora", title: "id_bitacora", type: "number", align: "center", visible:false},
            {name: "id_usuario", title: "Matrícula", type: "number", align: "center", visible:false},
            {name: "delegacion", title: "Delegación", type: "text", align: "center"},            
            {name: "unidad", title: "Unidad/UMAE", type: "text", align: "center", width:'200px'},            
            {name: "matricula", title: "Matrícula", type: "number", align: "center"},
            {name: "nombre", title: "Nombre", type: "text", align: "center"},                                  
            {name: "categoria", title: "Categoría", type: "text", align: "center"},                                  
            {name: "url", title: "URL", type: "text", width:'200px'},            
            {name: "fecha", title: "Fecha", type: "date", align: "center",  filtering: true},
            {name: "ip", title: "IP", type: "text", align: "center"},            
            {type: "control",     editButton: false, deleteButton: false}
        ]
    });
    $("#jsGrid").jsGrid("option", "filtering", false);
});