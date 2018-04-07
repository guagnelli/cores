/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

function departamento_modal() {
    var destino = site_url + '/catalogo/nuevo_departamento';
    data_ajax(destino, null, '#cores-modal');
}

function set_value_unidad(item) {
    var id_unidad = item.getAttribute("data-unidad-id");
    var unidad = item.getAttribute("data-unidad-nombre");
    document.getElementById('unidad').value = id_unidad;
    document.getElementById('unidad_texto').value = unidad;
    $('#unidad_autocomplete').css('display', 'none');
    $('#unidad_autocomplete').html('');
}

$(function () {
    $('#unidad_texto').keyup(function () {
        keyword = document.getElementById('unidad_texto').value;
        console.log('buscando:' + keyword);
        $.ajax({
            url: site_url + '/buscador/search_unidad_instituto'
            , method: "post"
            , timeout: 200
            , data: {keyword: keyword}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
        }).done(function (response) {
            $('#unidad_autocomplete').css('display', 'block');
            $('#unidad_autocomplete').html(response);
        });
    });

    $('#form_departamento').submit(function (event) {
        event.preventDefault();
        var destino = site_url + '/catalogo/nuevo_departamento';
        data_ajax(destino, '#form_departamento', '#cores-modal');
    });
});