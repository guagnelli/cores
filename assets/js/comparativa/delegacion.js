$(function(){
    $('btn_limpiar').click(function(){
            cmbox_comparativa();
    });
});

function chart(id_chart, tabla, titulo, ytext, color) {    
    Highcharts.chart(id_chart, {
        data: {
            table: tabla
        },
        chart: {
            type: 'column'
        },
        colors: color,
        title: {
            text: titulo
        },
        legend: {
            enabled: false
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: ytext
            }, 
            visible: false
        }                
    });
}

function cmbox_comparativa() {
    var id_destino = document.getElementById('comparativa').value;
    if (id_destino == "") {
        $('#form_delegacion_perfil').css('display', 'none');
        $('#form_delegacion_tipo_curso').css('display', 'none');
        $('#form_delegacion').css('display', 'none');
        $('#area_reportes').css('display', 'none');
    } else {
        var destino = site_url + '/comparativa/delegacion_v2/';
        var agrupamiento = 0;
        if (document.getElementById('agrupamiento') != null) {
            agrupamiento = document.getElementById('agrupamiento').value;
        }
        $.ajax({
            url: destino
            , method: "post"
            , data: {view: id_destino, agrupamiento: agrupamiento}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                $('#area_comparativa').html('');
                mostrar_loader();
            }
        }).done(function (response) {
            $('#area_comparativa').html(response);
            $('#area_graph').html('');
            $('#area_reportes').css('display', 'none');
            ocultar_loader();
        });
    }
}
function valida_filtros(formulario) {
    var valido = false
    switch (formulario) {
        case 1:
        case "1":
            valido = valida_filtros_aux(['tipo_curso', 'delegacion1', 'delegacion2', 'periodo']);
            break;
        case 2:
        case "2":
            valido = valida_filtros_aux(['subperfil', 'delegacion1', 'delegacion2', 'periodo']);
            break;
    }
    return valido;
}

function valida_filtros_aux(campos) {
    var valido = true;
    for (i = 0; i < campos.length; i++) {
        if (document.getElementById(campos[i]) != null) {
            var value = document.getElementById(campos[i]).value;
            if (value == null || value == "") {
                valido = false;
                console.log('elemento no encontrado: ' + campos[i]);
            }
        } else {
            console.log('elemento no encontrado: ' + campos[i]);
            valido = false;
        }
    }
    if(document.getElementById('delegacion1') != null && document.getElementById('delegacion2') != null){
        valido &= document.getElementById('delegacion1').value != document.getElementById('delegacion2').value;
    }
    return valido;
}

function cmbox_nivel() {
    var nivel = document.getElementById('nivel').value;
    if (nivel != null && nivel != "") {
        var datos = {nivel: nivel};
        if (document.getElementById('umae') && document.getElementById('umae').value == 1) {
            datos = {nivel: nivel, umae: 1};
        }
        $.ajax({
            url: site_url + "/buscador/get_tipo_unidad/"
            , method: "post"
            , data: datos
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                mostrar_loader();
            }
        }).done(function (response) {
            $('#tipo_unidad').empty()
            var opts = $.parseJSON(response);
            $('#tipo_unidad').append('<option value="">Todas</option>');
            // Use jQuery's each to iterate over the opts value
            $.each(opts, function (i, d) {
                $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
            });
            ocultar_loader();
        });
    }
}

function cmbox_perfil() {
    var subcategoria = document.getElementById('perfil').value;
    $.ajax({
        url: site_url + '/buscador/search_grupos_categorias'
        , method: "post"
        , data: {subcategoria: subcategoria}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#subperfil').empty()
        var opts = $.parseJSON(response);
        $('#subperfil').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#subperfil').append('<option value="' + d.id_grupo_categoria + '">' + d.nombre + '</option>');
        });
        ocultar_loader();
    });
}

function submit_delegacion(event) {
    if (!valida_filtros(document.getElementById('tipo_comparativa').value)) {        
        event.preventDefault();
        alert('Debe seleccionar los filtros y las delegaciones deben ser diferentes, antes de realizar una comparación');
    }
}

function procesa_datos(datos, index) {
    var salida = [];
    if (datos.delegacion1.delegacion == "") {
        datos.delegacion1.delegacion = $("#delegacion1 option:selected").text();
    }
    if (datos.delegacion2.delegacion == "") {
        datos.delegacion2.delegacion = $("#delegacion2 option:selected").text();
    }
    salida[0] = [datos.delegacion1.delegacion, datos.delegacion1.cantidad];
    salida[1] = [datos.delegacion2.delegacion, datos.delegacion2.cantidad];
    if (salida[0][1] == 0 && salida[1][1] == 0) {
        $('#area_graph' + index).css('display', 'none');
        $('#alert-comparativa' + index).css('display', 'block');
    } else {
        $('#area_graph' + index).css('display', 'block');
    }
    return salida;
}

function graficar(id, datos, titulo, texto, year, extra, colores) {
    Highcharts.chart('area_graph' + id, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        xAxis: {
            type: 'category',
            labels: {
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: texto
            },
            visible: false
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: texto + ' en ' + year + ' : <b>{point.y} ' + extra + '</b>'
        },
        colors: colores,
        series: [{
                name: texto,
                data: datos
                , dataLabels: {
                    enabled: true,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
    });
}

function get_descripcion_filtros(){
    var salida = '';
    
    /*if(document.getElementById('tipo_curso') != null){
        salida += ' de ' + $('#tipo_curso option:selected').text();
    }
    if(document.getElementById('perfil') != null){
        salida += ' de ' + $('#perfil option:selected').text();
    }
    if(document.getElementById('subperfil') != null){
        salida += ' ' + $('#subperfil option:selected').text();
    }
    if(document.getElementById('nivel') != null){
        salida += ' para el ' + $('#nivel option:selected').text();
    }
    if(document.getElementById('tipo_unidad') != null){
        salida += ' por el tipo ' + $('#tipo_unidad option:selected').text();
    }
    if(document.getElementById('periodo') != null){
        salida += ' en el año ' + $('#periodo option:selected').text();
    }*/
    if(document.getElementById('tipo_unidad') != null && $('#tipo_unidad').val()!=''){
        salida += ' de ' + $('#tipo_unidad option:selected').text();
    }
    
    if(document.getElementById('tipo_curso') != null && $('#tipo_curso').val()!=''){
        salida += ' en cursos de ' + $('#tipo_curso option:selected').text();
    }
    if(document.getElementById('perfil') != null && $('#perfil').val()!=''){
      //  salida += ' ' + $('#perfil option:selected').text();
    }
    
    if(document.getElementById('nivel') != null && $('#nivel').val()!=''){
       // salida += ' para el ' + $('#nivel option:selected').text()+' de atención';
    }
    
    if(document.getElementById('periodo') != null && $('#periodo').val()!=''){
        salida += ' en el año ' + $('#periodo option:selected').text();
    }
    
    if(document.getElementById('subperfil') != null && $('#subperfil').val()!=''){
        salida += ' (' + $('#subperfil option:selected').text() + ') ';
    }
    
    return salida;
}