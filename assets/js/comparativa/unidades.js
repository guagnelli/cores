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
        $('#area_comparativa').html('');
        $('#area_reportes').css('display', 'none');
    } else {
        var destino = site_url + '/comparativa/unidades';        
        var datos = {vista: id_destino};
        if(document.getElementById('periodo') != null){
            datos['periodo'] = document.getElementById('periodo').value;
        }
        $.ajax({
            url: destino
            , method: "post"
            , data: datos
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

function cmbox_region() {
    var id_region = document.getElementById('region').value;
    $.ajax({
        url: site_url + "/buscador/get_delegaciones/" + id_region
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
            ocultar_loader();
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#delegacion').empty()
        var opts = $.parseJSON(response);
        $('#delegacion').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#delegacion').append('<option value="' + d.id_delegacion + '">' + d.nombre + '</option>');
        });
        ocultar_loader();
    });
}

function search_unidad(elemento) {
    var index = elemento[0].getAttribute('data-id');
    var keyword = document.getElementById('unidad' + index + '_texto').value;
    var tipo_unidad = document.getElementById('tipo_unidad').value;
    var periodo = document.getElementById('periodo').value;;
    
    console.log('buscando:' + keyword);
    $.ajax({
        url: site_url + '/buscador/search_unidad_instituto'
        , method: "post"
        , timeout: 200
        , data: {keyword: keyword, tipo_unidad: tipo_unidad, periodo: periodo}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        if (index > 1 && response != null && response != "") {
            response = '<li class="autocomplete_unidad" data-unidad-nombre="PROMEDIO" data-unidad-clave="0" data-unidad-id="0" onclick="set_value_unidad(this)" >PROMEDIO</li>' + response;
        }
        $('#unidad' + index + '_autocomplete').css('display', 'block');
        $('#unidad' + index + '_autocomplete').html(response);
    });
}

function set_value_unidad(item) {
    var id_unidad = item.getAttribute("data-unidad-clave");
    var unidad = item.getAttribute("data-unidad-nombre");
    var index = item.parentElement.getAttribute('data-autocomplete-id');
    console.log(index);
    document.getElementById('unidad' + index).value = id_unidad;
    document.getElementById('unidad' + index + '_texto').value = unidad;
    $('#unidad' + index + '_autocomplete').css('display', 'none');
    $('#unidad' + index + '_autocomplete').html('');
}

function cmbox_delegacion() {
    if (document.getElementById('nivel') != null) {
        var nivel = document.getElementById('nivel').value;
        var delegacion = document.getElementById('delegacion').value;
        if (delegacion != null && delegacion != "") {
            var datos = {delegacion: delegacion, nivel: nivel};
            if (document.getElementById('umae')) {
                datos = {delegacion: delegacion, nivel: nivel, umae: 1};
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
                $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
                // Use jQuery's each to iterate over the opts value
                $.each(opts, function (i, d) {
                    $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
                });
                $('#unidad1').val("");
                $('#unidad1_texto').val("");
                $('#unidad2').val("");
                $('#unidad2_texto').val("");
                ocultar_loader();
            });
        }
    }
}

function cmbox_tipo_unidad() {
    $('#unidad1').val("");
    $('#unidad1_texto').val("");
    $('#unidad2').val("");
    $('#unidad2_texto').val("");
}