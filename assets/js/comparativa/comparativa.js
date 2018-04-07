function valida_filtros(formulario) {
    var valido = false
    switch (formulario) {
        case 'tipo_curso':
            valido = valida_filtros_aux(['tipo_curso', 'tipo_unidad', 'unidad1', 'unidad2', 'periodo']);
            break;
        case 'perfil':
            valido = valida_filtros_aux(['subperfil', 'tipo_unidad', 'unidad1', 'unidad2', 'periodo']);
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
        if (document.getElementById('umae')) {
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
            $('#unidad1').val("");
            $('#unidad2').val("");
            $('#unidad1_texto').val("");
            $('#unidad2_texto').val("");

            ocultar_loader();
        });
    }
}

function cmbox_delegacion() {

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