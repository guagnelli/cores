$(function () {
    $('.unidad_texto').keyup(function () {
        search_unidad($(this));
    });
    $('#form_comparativa_umae').submit(function (event) {               
        if (!valida_filtros('perfil')) {            
            event.preventDefault();            
            alert('Debe seleccionar los filtros y las unidades deben ser diferentes, antes de realizar una comparación');
        }
    });
});

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

function procesa_datos(datos, index) {
    var salida = [];
    if (datos.unidad1.unidad == "") {
        datos.unidad1.unidad = $("#unidad1 option:selected").text();
    }
    if (datos.unidad2.unidad == "") {
        datos.unidad2.unidad = $("#unidad2 option:selected").text();
    }
    salida[0] = [datos.unidad1.unidad, datos.unidad1.cantidad];
    salida[1] = [datos.unidad2.unidad, datos.unidad2.cantidad];
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