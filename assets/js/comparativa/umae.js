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
        var destino = site_url + '/comparativa/umae/';
        var agrupamiento = 0;
        if (document.getElementById('agrupamiento') != null) {
            agrupamiento = document.getElementById('agrupamiento').value;
        }
        $.ajax({
            url: destino
            , method: "post"
            , data: {vista: id_destino, agrupamiento: agrupamiento}
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
                $('#unidad1').empty();
                $('#unidad1').append('<option value="">Seleccionar...</option>');
                $('#unidad2').empty();
                $('#unidad2').append('<option value="">Seleccionar...</option>');
                ocultar_loader();
            });
        }
    }
}

function cmbox_tipo_unidad() {
    var tipo_unidad = document.getElementById('tipo_unidad').value;
    var umae = 1;
    var agrupamiento = 0;
    if (document.getElementById('agrupamiento') != null) {
        agrupamiento = document.getElementById('agrupamiento').value;
    }
    var datos = {tipo_unidad: tipo_unidad, agrupamiento: agrupamiento};
    if(document.getElementById('periodo')!= null){
        datos['periodo'] = document.getElementById('periodo').value;
    }
    $.ajax({
        url: site_url + "/buscador/get_unidades/" + umae
        , method: "post"
        , data: datos
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#unidad1').empty()
        $('#unidad2').empty()
        var opts = $.parseJSON(response);
        $('#unidad1').append('<option value="">Seleccionar...</option>');
        $('#unidad2').append('<option value="">Seleccionar...</option>');
        $('#unidad2').append('<option value="PROMEDIO">PROMEDIO</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#unidad1').append('<option value="' + d.id_unidad_instituto + '">' + d.nombre + '</option>');
            $('#unidad2').append('<option value="' + d.id_unidad_instituto + '">' + d.nombre + '</option>');
        });
        ocultar_loader();
    });
}