$(function () {
    $('#form_ranking').submit(function (event) {
        if (!valida_filtros()) {
            event.preventDefault();
            alert('Seleccione los campos requeridos');
        }
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
        }, xAxis: {     
            labels: {
                rotation: -90
            }
        },
    });
}

function valida_filtros() {
    var campos = ['periodo'];
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
    return valido;
}

function sortTable(name_table) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById(name_table);
    switching = true;
    /*Make a loop that will continue until
     no switching has been done:*/
    console.log('ordenando');
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("TR");
        /*Loop through all table rows (except the
         first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
             one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[0];
            y = rows[i + 1].getElementsByTagName("TD")[0];
            //check if the two rows should switch place:
            if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                //if so, mark as a switch and break the loop:                
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            /*If a switch has been marked, make the switch
             and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
    console.log('fin');
}

function get_descripcion_filtros() {
    var salida = '';
    if (document.getElementById('programa') != null && $('#programa').val() != '') {
        salida += ' en el Programa ' + $('#programa option:selected').text();
    }
    if (document.getElementById('periodo') != null && $('#periodo').val() != '') {
        salida += ' en el año ' + $('#periodo option:selected').text();
    }
    return salida;
}

function cmbox_programa(item) {
    var id = item.value;
    if (id != '') {
        $('#cores-programa-ayuda').css('display', 'block');
    } else {
        $('#cores-programa-ayuda').css('display', 'none');
    }
}

function lista_cursos() {
    var id = document.getElementById('programa').value != '' ? document.getElementById('programa').value : '';
    var periodo = document.getElementById('periodo').value != '' ? document.getElementById('periodo').value : '';
    if (id != '') {
        var destino = site_url + '/ranking/cursos/' + id + '/' + periodo;
        data_ajax(destino, null, '#cores-modal');
    }
}