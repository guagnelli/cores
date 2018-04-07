$(function () {
    $('#form_comparativa_umae').submit(function (event) {        
        $('.alert-comparativa').css('display', 'none');
        if (!valida_filtros('perfil')) {
            event.preventDefault();
            alert('Debe seleccionar los filtros y las UMAE deben ser diferentes, antes de realizar una comparación');
        }
    });
});

function procesa_datos(datos, index) {
    var salida = [];
    datos.unidad1.unidad = $("#unidad1 option:selected").text();
    datos.unidad2.unidad = $("#unidad2 option:selected").text();
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