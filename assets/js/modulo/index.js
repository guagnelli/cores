$(function () {
    $('#form_custom_modulo').submit(function (event) {
        event.preventDefault();
        console.log($(this).attr('action'));
        $.ajax({
            url: $(this).attr('action')
            , method: "post"
            , data: $(this).serialize()
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                mostrar_loader();
            }
        }).done(function (response) {
            console.log(response);
            refresh_page();
            ocultar_loader();
        });
    });
});

function get_info_modulo(modulo) {
    $.getJSON(site_url + "/modulo/get_modulo/" + modulo, function (data) {
        //$('#myModalLabel').text(data.modulo.nombre);
        $('#modulo').val(data.modulo.nombre);
        $('#url').val(data.modulo.url);
        $('#tipo').val(data.modulo.id_configurador);
        $('#padre').val(data.modulo.id_modulo_padre);
        $('#orden').val(data.modulo.orden);
        $('#icono').val(data.modulo.icon);
        $('#visible').checked = data.modulo.visible;
        $('#form_custom_modulo').attr('action', site_url + "/modulo/get_modulo/" + modulo);
    });
}

function form_save() {
    $('#modulo').val('');
    $('#url').val('');
    $('#tipo').val('');
    $('#padre').val('');
    $('#orden').val('');
    $('#visible').checked = false;
    $('#form_custom_modulo').attr('action', site_url + "/modulo/new_modulo/");
}

function refresh_page() {
    $.ajax({
        url: site_url + "/modulo/index/0"
        , method: "post"
        , data: $(this).serialize()
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#area_modulos').html(response);
    });
}