


function mostrar_tipo_unidades(nivel)
{
    var id = nivel.value;
    $('#tipos_unidades').find('option').remove().end();
    $.ajax({
        method: "GET",
        url: site_url + "/dec/tipos_unidades/"+id,
    }).done(function( tipos ) {
        console.log("Respuesta de Tipos de unidades: ", tipos);
        if(tipos.success)
        {
            $('#tipos_unidades').append($('<option>', { value: "", text: "Seleccione..."}));
            if(tipos.datos.length > 0)
            {
                tipos.datos.forEach(function(tipoU) {
                    $('#tipos_unidades').append($('<option>', { value: tipoU.id_tipo_unidad, text: tipoU.nombre}));
                });
            }
            else
            {
            }
        }
        else
        {
        }

    });
}

function mostrar_delegaciones(region)
{
    var region = region.value;
    console.log("ID region: ", region);
    $('#delegacion').find('option').remove().end();
    $.ajax({
        method: "GET",
        url: site_url + "/dec/obtener_delegaciones/"+region,
    }).done(function( delegacion ) {
        console.log("Respuesta de delegaciones: ", delegacion);
        if(delegacion.success)
        {
            $('#delegacion').append($('<option>', { value: "", text: "Seleccione..."}));
            if(delegacion.datos.length > 0)
            {
                delegacion.datos.forEach(function(itemDelegacion) {
                    $('#delegacion').append($('<option>', { value: itemDelegacion.id_delegacion, text: itemDelegacion.delegacion}));
                });
            }
            else
            {
              console.log(delegacion)
            }
        }
        else
        {
          console.log(delegacion)
        }

    });
}
