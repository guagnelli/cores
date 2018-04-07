// // $num = '3',$year='2016', $type=Comparativa_model::PERFIL
// PERFIL = 1,
// TIPO_CURSO = 2;
function comparativa(num, year, type, tipo, nivel, tunidad, extra) {
    var region_url = site_url + "/comparativa/region/" + num + "/" + year + "/" + type + "/" + tipo + "/" + nivel + '/' + tunidad + '/' + extra;    
    //alert(region_url)
    window.location.replace(region_url);
}
function submit_region() {
    var reporte = document.getElementById('comparativa').value == 1 ? 'tc': 'p';
    var year = document.getElementById('periodo') == null ? new Date().getFullYear() - 1 : document.getElementById('periodo').value;
    var num = reporte == 'tc' ? document.getElementById('tipo_curso').value : document.getElementById('subperfil').value;
    var tipo = document.getElementById('umae') != null ? document.getElementById('umae').value : '';    
    var extra = document.getElementById('perfil') != null ? document.getElementById('perfil').value : '';    
    var nivel = document.getElementById('nivel') != null ? document.getElementById('nivel').value : '';    
    var tunidad = document.getElementById('tipo_unidad') != null ? document.getElementById('tipo_unidad').value : '';    
    comparativa(num, year, reporte, tipo, nivel, tunidad, extra);
}


function cmbox_comparativa() {
    var id_destino = document.getElementById('comparativa').value;
    if (id_destino == "") {
        $('#form_region_perfil').css('display', 'none');
        $('#form_region_tipo_curso').css('display', 'none');
        $('#area_reportes').css('display', 'none');
    } else {
        var destino = site_url + '/comparativa/region//';
        var datos = {view: id_destino};
        if (document.getElementById('umae') != null) {
            datos['umae'] = document.getElementById('umae').value;
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

function valida_filtros(formulario) {
    var valido = false
    switch (formulario) {
        case 'tipo_curso':
            valido = valida_filtros_aux(['tipo_curso', 'tipo_unidad', 'periodo']);
            break;
        case 'perfil':
            valido = valida_filtros_aux(['subperfil', 'tipo_unidad', 'periodo']);
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
            }
        } else {
            console.log('elemento no encontrado: ' + campos[i]);
            valido = false;
        }
    }
    if (document.getElementById('unidad1') != null && document.getElementById('unidad2') != null) {
        valido &= document.getElementById('unidad1').value != document.getElementById('unidad2').value;
    }
    return valido;
}

function cmbox_nivel() {
    var nivel = document.getElementById('nivel').value;
    if (nivel != null && nivel != "") {
        var datos = {nivel: nivel};
        if (document.getElementById('umae')!=null && document.getElementById('umae').value==1) {
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
            $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
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

function get_descripcion_filtros() {
    var salida = '';

    if (document.getElementById('tipo_unidad') != null && $('#tipo_unidad').val() != '') {
        salida += ' de ' + $('#tipo_unidad option:selected').text();
    }

    if (document.getElementById('tipo_curso') != null && $('#tipo_curso').val() != '') {
        salida += ' en cursos de ' + $('#tipo_curso option:selected').text();
    }
    if (document.getElementById('perfil') != null && $('#perfil').val() != '') {
       // salida += ' con tipo perfil ' + $('#perfil option:selected').text();
    }
    
    if (document.getElementById('nivel') != null && $('#nivel').val() != '') {
        //  salida += ' para el ' + $('#nivel option:selected').text() + ' de atención';
    }

    if (document.getElementById('periodo') != null && $('#periodo').val() != '') {
        salida += ' en el año ' + $('#periodo option:selected').text();
    }
    
    if (document.getElementById('subperfil') != null && $('#subperfil').val() != '') {
        salida += ' (' + $('#subperfil option:selected').text() + ') ';
    }

    return salida;
}