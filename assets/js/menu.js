$(function () {
    $('.tablero-menu-item').click(function (event) {
        set_menu_active($(this)[0].id);
    });
    if (typeof (Storage) !== "undefined") {
        console.log('colocando para: ' + sessionStorage.menu_active);
        if (sessionStorage.menu_active) {
            $('#'+sessionStorage.menu_active).parent().addClass('active');
            $('#'+sessionStorage.menu_active).parent().parent().addClass('in');
            $('#'+sessionStorage.menu_active).parent().parent().parent().parent().addClass('in');
        }
    } 
});

function set_menu_active(id_item) {    
    $('.tablero-menu-item').parent().removeClass('active');
    if (typeof (Storage) !== "undefined") {
        console.log('almacenando:' + id_item);
        sessionStorage.menu_active = id_item;
        $('#'+sessionStorage.menu_active).parent().addClass('active');
    } 
}