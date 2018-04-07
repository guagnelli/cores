$(function () {
    console.log('activando paginacion');
    $("ul.pagination li a").click(function (event) {
        event.preventDefault();
        paginar_usuarios($(this));
    });
    
    $("ul.dropdown-menu li a.option-input-tablero").click(function(event){
        event.preventDefault();
        $('#btn-filtro-tablero').html($(this).text()+'<span class="caret"></span>');
        $('#filtro_texto').val($(this).attr('data-id'));
    });
});



function set_status_usuario(id_usuario) {
    var status = document.getElementById('usuario_chbox_' + id_usuario).checked;
    $.ajax({
        url: site_url + "/usuario/set_status/" + id_usuario
        , method: "post"
        , data: {status: status}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        ocultar_loader();
    });
}

function paginar_usuarios(obj) {
    var num_page = obj.attr('data-ci-pagination-page') -1;
    var limit = document.getElementById('usuarios_limit').value;
    if ($.isNumeric(num_page)) {
        document.getElementById('usuarios_current_page').value = num_page;      
        var action = $('#form_usuarios').attr('action');
        $('#form_usuarios').attr('action', action + ((num_page) * limit));
        $('#form_usuarios').submit();
    }
}