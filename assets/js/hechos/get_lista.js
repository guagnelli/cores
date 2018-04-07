function set_status_hechos(id_hecho){
    var status = document.getElementById('hechos_chbox_' +id_hecho).checked?1:0;
    $.ajax({
        url: site_url + "/hechos/update_carga/" + id_hecho + "/" + status
        , method: "post"
        , success: function (response) {
            
        }
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    });
}

