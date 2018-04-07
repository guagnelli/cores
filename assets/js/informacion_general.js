$( document ).ready(function() {
    //$('[data-toggle="tooltip"]').tooltip(); //Llamada a tooltip
    Highcharts.setOptions({
        lang: {
            contextButtonTitle: "Menú contextual",
            downloadJPEG: "Descargar imagen JPEG",
            downloadPDF: "Descargar documento PDF",
            downloadPNG: "Descargar imagen PNG",
            downloadSVG: "Descargar imagen en vectores SVG",
            drillUpText: "Regresar a {series.name}",
            loading: "Cargando...",
            months: [ "Enero" , "Febrero" , "Marzo" , "Abril" , "Mayo" , "Junio" , "Julio" , "Agosto" , "Septiembre" , "Octubre" , "Noviembre" , "Diciembre"],
            noData: "No hay datos que mostrar",
            printChart: "Imprimir gráfica",
            resetZoom: "Restablecer zoom",
            resetZoomTitle: "Restablecer zoom nivel 1:1",
            shortMonths: [ "Ene" , "Feb" , "Mar" , "Abr" , "May" , "Jun" , "Jul" , "Ago" , "Sep" , "Oct" , "Nov" , "Dic"],
            weekdays: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        }
    });

    $("#filtros_capa").on("hide.bs.collapse", function(){
        $("#filtros_capa_header>i").addClass('fa-arrow-right');
        $("#filtros_capa_header>i").removeClass('fa-arrow-down');
    });
    $("#filtros_capa").on("show.bs.collapse", function(){
        //$("#filtros_capa_header>div").html('keyword_arrow_left');
        $("#filtros_capa_header>i").addClass('fa-arrow-down');
        $("#filtros_capa_header>i").removeClass('fa-arrow-right');
    });
});

function data_ajax_listado(path, form_recurso, elemento_resultado, callback) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        beforeSend: function (xhr) {
            //$(elemento_resultado).html(create_loader());
            mostrar_loader();
        }
        })
        .done(function (response) {
            if (typeof callback !== 'undefined' && typeof callback === 'function') {
                $(elemento_resultado).html(response).promise().done(callback());
            } else {
                $(elemento_resultado).html(response);
            }
            calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');
        })
        .fail(function (jqXHR, textStatus) {
            $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        })
        .always(function () {
            //remove_loader();
            //ocultar_loader();
        });
}

function limpiar_capas(arreglo, arreglo2) {
    for (var i = 0; i < arreglo.length; i++) { ///Eliminar elementos de las capas
        $('#'+arreglo[i]).html('');
    };
    for (var i = 0; i < arreglo2.length; i++) { ///Los listados dependientes se els eliminará la selección anterior.
        $('#'+arreglo2[i]).val('');
    };
    $('#comparativa_chrt').html(''); 
    $('#comparativa_chrt2').html('');
}
/**
 *	Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *	@return	string	Contenedor e imagen del cargador.
 */
function calcular_totales_generales(path) {
    $.ajax({
        url: path,
        method: 'POST',
        data: 'anio='+$('#anio').val(),
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
            $('#tipos_curso').val('');
            $('#nivel_atencion').val('');
            $('#region').val('');
            $('#delegacion').val('');
            $('#umae').val('');
        }
    })
    .done(function (response) {
        if(typeof(response.total) != "undefined"){
            $('#capa_periodo_principal').html($('#anio').val());
            if(response.total == null || response.total.length <= 0) {
                $('#container_perfil').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
                $('#container_perfil').show();
            } else {
                $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
                $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
                $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
                $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
                $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
                $('#container_perfil').html('');
            }
            $('#tipos_busqueda').val('');
            $('#container_tipo_curso').html('');
            $('#container_nivel_atencion').html('');
            $('#container_region').html('');
            $('#container_periodo').html('');
            $('#container_delegacion').html('');
            $('#container_umae').html('');
        }
    })
    .fail(function (jqXHR, textStatus) {
        //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function obtener_categoria_serie(datos){
    var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        inscritos.push(val.cantidad_alumnos_inscritos);
        certificados.push(val.cantidad_alumnos_certificados);
        no_acceso.push(val.cantidad_no_accesos);
        no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
    });
    series_datos = [{
            name: 'Inscritos',
            data: inscritos,
            stack: 'Inscritos'
        }, {
            name: 'Aprobados',
            data: certificados,
            stack: 'Aprobados'
        }, {
            name: 'No aprobados',
            data: no_aprobado,
            stack: 'No aprobado'
        }, {
            name: 'Nunca entraron',
            data: no_acceso,
            stack: 'No aprobado'
        }];
    return resultado = {'categorias':categorias, 'series':series_datos};
}

function obtener_categoria_serie_drilldown(datos){
    var categorias = [];
    var series_datos = [];
    var drilldown_datos = [];
    var inscritos = [];
    var certificados = [];
    //var no_acceso = [];
    var no_aprobado = [];
    //var reprobado = [];
    var insc = [];
    var apro = [];
    var no_apro = [];
    var comp = [];
    var inc = 1;
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        insc = {
            name: i,
            y: val.cantidad_alumnos_inscritos
        };
        inscritos.push(insc);
        apro = {
            name: i,
            y: val.cantidad_alumnos_certificados
        };
        certificados.push(apro);
        if(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados>0){
            no_apro = {
                name: i,
                y: val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados,
                drilldown: 'nom_'+inc
            };
            comp = {
                id: 'nom_'+inc,
                name: 'No aprobados',
                level:1,
                data: [
                    ['Nunca entraron', val.cantidad_no_accesos],
                    ['Reprobados', val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos]
                ],
                //color: ['#43A886','#EF5350'],
            };
            drilldown_datos.push(comp);
        } else {
            no_apro = {
                name: i,
                y: val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados
            };
        }
        no_aprobado.push(no_apro);
        inc++;
        //reprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
    });
    series_datos = [{
            name: 'Inscritos',
            level:0,
            data: inscritos,
            //stack: 'Inscritos'
        }, {
            name: 'Aprobados',
            level:0,
            data: certificados,
            //stack: 'Aprobados'
        }, {
            name: 'No aprobados',
            level:0,
            data: no_aprobado,
            //stack: 'No aprobado'
        }/*, {
            name: 'Nunca entraron',
            data: no_acceso,
            stack: 'No aprobado'
        }*/];
    var drilldown_datos1 = {
        series: 
            drilldown_datos
        };
    //console.log(series_datos);
    //console.log(drilldown_datos1);
    return resultado = {'categorias':categorias, 'series':series_datos, 'drilldown_datos':drilldown_datos1};
}

/*function obtener_categoria_serie_drill(datos){
    var series_datos;
    var drill_datos;
    series_datos = [{
        name: '2010',
        data: [{
            name: 'Republican',
            y: 5,
            drilldown: 'republican-2010'
        }, {
            name: 'Democrats',
            y: 2,
            drilldown: 'democrats-2010'
        }, {
            name: 'Other',
            y: 4,
            drilldown: 'other-2010'
        }]
    }, {
        name: '2014',
        data: [{
            name: 'Republican',
            y: 4,
            drilldown: 'republican-2014'
        }, {
            name: 'Democrats',
            y: 4,
            drilldown: 'democrats-2014'
        }, {
            name: 'Other',
            y: 4,
            drilldown: 'other-2014'
        }]
    }];

    drill_datos = {
        series: [{
            id: 'republican-2010',
            data: [
                ['East', 4],
                ['West', 2],
                ['North', 1],
                ['South', 4]
            ]
        }, {
            id: 'democrats-2010',
            data: [
                ['East', 6],
                ['West', 2],
                ['North', 2],
                ['South', 4]
            ]
        }, {
            id: 'other-2010',
            data: [
                ['East', 2],
                ['West', 7],
                ['North', 3],
                ['South', 2]
            ]
        }, {
            id: 'republican-2014',
            data: [
                ['East', 2],
                ['West', 4],
                ['North', 1],
                ['South', 7]
            ]
        }, {
            id: 'democrats-2014',
            data: [
                ['East', 4],
                ['West', 2],
                ['North', 5],
                ['South', 3]
            ]
        }, {
            id: 'other-2014',
            data: [
                ['East', 7],
                ['West', 8],
                ['North', 2],
                ['South', 2]
            ]
        }]
    };
    console.log(series_datos);
    console.log(drill_datos);
    
    return resultado = {'categorias':'--', 'series':series_datos, 'drilldown':drill_datos};
}*/

function calcular_totales(path, form_recurso) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        if(typeof(response.error) != "undefined"){
            $('#container_error').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
            $('#container_error').show();
            //$('#container_perfil').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
            //$('#container_perfil').show();
            $('#container_perfil').html('');
            $('#container_tipo_curso').html('');
            $('#container_nivel_atencion').html('');
            $('#container_region').html('');
            $('#container_periodo').html('');
            $('#container_delegacion').html('');
            $('#container_umae').html('');
        } else {
            $('#container_error').html(''); ///Ocultar capa en caso de que se haya activado para mostrar algún mensaje de error.
            $('#container_error').hide();
            if(typeof(response.total) != "undefined"){
                $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
                $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
                $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
                $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
                $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
            }
            /////////Perfiles
            /*var perfiles = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            var no_acceso = [];
            var no_aprobado = [];
            jQuery.each( response.perfil, function( i, val ) {
                perfiles.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
                no_acceso.push(val.cantidad_no_accesos);
                no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
            });
            series_datos = [{
                    name: 'Inscritos',
                    data: inscritos,
                    stack: 'inscritos'
                }, {
                    name: 'Aprobados',
                    data: certificados,
                    stack: 'aprobados'
                }, {
                    name: 'No acceso',
                    data: no_acceso,
                    stack: 'no_aprobado'
                }, {
                    name: 'No aprobado',
                    data: no_aprobado,
                    stack: 'no_aprobado'
                }];*/
            /*var perfil = obtener_categoria_serie(response.perfil);
            //var perfil_drill = obtener_categoria_serie_drill(response.perfil);
            crear_grafica_stacked_grouped('container_perfil', 'Información por perfil', perfil.categorias, 'Número de alumnos', perfil.series);*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='perfil'){
                var perfil = obtener_categoria_serie_drilldown(response.perfil);
                crear_grafica_drilldown('container_perfil', 'Información por perfil', perfil.categorias, 'Número de alumnos', perfil.series, perfil.drilldown_datos);
            }
            ////////Tipos de curso
            /*var tipos_curso = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            jQuery.each( response.tipo_curso, function( i, val ) {
                tipos_curso.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
            });
            series_datos = [{
                    name: 'Aprobados',
                    data: certificados
                }, {
                    name: 'Inscritos',
                    data: inscritos
                }];*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='tipo_curso'){
                var tipos_curso = obtener_categoria_serie_drilldown(response.tipo_curso);
                crear_grafica_drilldown('container_tipo_curso', 'Información por tipo de curso', tipos_curso.categorias, 'Número de alumnos', tipos_curso.series, tipos_curso.drilldown_datos);
            }
            ////////Región
            /*var region = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            jQuery.each( response.region, function( i, val ) {
                region.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
            });
            series_datos = [{
                    name: 'Aprobados',
                    data: certificados
                }, {
                    name: 'Inscritos',
                    data: inscritos
                }];*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='region'){
                var region = obtener_categoria_serie_drilldown(response.region);
                crear_grafica_drilldown('container_region', 'Información por región', region.categorias, 'Número de alumnos', region.series, region.drilldown_datos);
            }
            ////////Delegación
            /*var delegacion = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            jQuery.each( response.delegacion, function( i, val ) {
                delegacion.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
            });
            series_datos = [{
                    name: 'Aprobados',
                    data: certificados
                }, {
                    name: 'Inscritos',
                    data: inscritos
                }];*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='delegacion'){
                var delegacion = obtener_categoria_serie_drilldown(response.delegacion);
                crear_grafica_drilldown('container_delegacion', 'Información por delegación', delegacion.categorias, 'Número de alumnos', delegacion.series, delegacion.drilldown_datos);
            }
            //var delegacion = obtener_categoria_serie_drilldown(response.delegacion);
            //crear_grafica_drilldown('container_delegacion', 'Información por delegación', delegacion.categorias, 'Número de alumnos', delegacion.series, null);
            ////////UMAE
            /*var umae = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            jQuery.each( response.umae, function( i, val ) {
                umae.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
            });
            series_datos = [{
                    name: 'Aprobados',
                    data: certificados
                }, {
                    name: 'Inscritos',
                    data: inscritos
                }];*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='umae'){
                var umae = obtener_categoria_serie_drilldown(response.umae);
                crear_grafica_drilldown('container_umae', 'Información por UMAE', umae.categorias, 'Número de alumnos', umae.series, umae.drilldown_datos);
            }
            ////////Periodo
            /*var periodo = [];
            var series_datos = [];
            var inscritos = [];
            var certificados = [];
            jQuery.each( response.periodo, function( i, val ) {
                //jQuery.each( val, function( inc, value ) {
                    periodo.push(i);
                    inscritos.push(val.cantidad_alumnos_inscritos);
                    certificados.push(val.cantidad_alumnos_certificados);
                //});
            });
            series_datos = [{
                    name: 'Aprobados',
                    data: certificados
                }, {
                    name: 'Inscritos',
                    data: inscritos
                }];*/
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='periodo'){
                var periodo = obtener_categoria_serie_drilldown(response.periodo);
                crear_grafica_drilldown('container_periodo', 'Información por periodo', periodo.categorias, 'Número de alumnos', periodo.series, periodo.drilldown_datos);
            }
            if ($("#tipos_busqueda").length > 0 && $("#tipos_busqueda").val()=='nivel_atencion'){
                ////////Nivel de atención
                var nivel_atencion = obtener_categoria_serie_drilldown(response.nivel_atencion);
                crear_grafica_drilldown('container_nivel_atencion', 'Información por nivel de atención', nivel_atencion.categorias, 'Número de alumnos', nivel_atencion.series, nivel_atencion.drilldown_datos);
            }
        }
    })
    .fail(function (jqXHR, textStatus) {
        $('#container_error').html("<div class='alert alert-info text-center'><h5>Ocurrió un error durante el proceso, inténtelo más tarde.</h5></div>");
        //$('#container_error').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
        $('#container_error').show();
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function obtener_categoria_serie_unidad(datos){
    var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        inscritos.push(val.cantidad_alumnos_inscritos);
        certificados.push(val.cantidad_alumnos_certificados);
        no_acceso.push(val.cantidad_no_accesos);
        no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
    });
    series_datos = [{
            name: 'Inscritos',
            data: inscritos,
            stack: 'Inscritos'
        }, {
            name: 'Aprobados',
            data: certificados,
            stack: 'Aprobados'
        }, {
            name: 'No aprobados',
            data: no_aprobado,
            stack: 'No aprobado'
        }, {
            name: 'Nunca entraron',
            data: no_acceso,
            stack: 'No aprobado'
        }];
    return resultado = {'categorias':categorias, 'series':series_datos};
    /*var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        jQuery.each( val, function( i2, val2 ) {
            var t = [];
            var tt = '';
            jQuery.each( val2, function( i3, val3 ) {
                tt = i3;
                t.push(val3);
            });
            var tmp = {name: i2, data: t, stack: i};
            series_datos.push(tmp);
        });
    });
    return resultado = {'categorias':categorias, 'series':series_datos};*/
}

function calcular_totales_unidad(path, form_recurso) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        $('#capa_periodo_principal').html($('#anio').val());
        if(typeof(response.error) != "undefined"){
            $('#comparativa_chrt2').html("");
            $('#comparativa_chrt').html("");
            if(response.error == true){
                $('#comparativa_chrt').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
            }
        } else {
            var elemento = 'unidad';
            if($('#tipos_busqueda').val()=='umae'){
                elemento = 'umae';
            }
            if($('#'+elemento+' option:selected').text()!=''){
                texto = $('#'+elemento+' option:selected').text();
            } else {
                texto = $('#'+elemento+'_titulo').html();
            }
            if($('#tipo_grafica').val()=='perfil'){
                var perfil = obtener_categoria_serie_drilldown(response.perfil);
                crear_grafica_drilldown('comparativa_chrt', 'Información por perfil', perfil.categorias, 'Número de alumnos', perfil.series, perfil.drilldown_datos);
                /*var perfil = obtener_categoria_serie_unidad(response.perfil);
                crear_grafica_stacked_grouped('comparativa_chrt', '', perfil.categorias, 'Número de alumnos', perfil.series);*/
                $('#comparativa_chrt2').html('');
            }
            if($('#tipo_grafica').val()=='tipo_curso'){
                var tipos_curso = obtener_categoria_serie_drilldown(response.tipo_curso);
                crear_grafica_drilldown('comparativa_chrt2', 'Información por tipo de curso', tipos_curso.categorias, 'Número de alumnos', tipos_curso.series, tipos_curso.drilldown_datos);
                /*var tipos_curso = obtener_categoria_serie_unidad(response.tipo_curso);
                crear_grafica_drilldown('comparativa_chrt2', '', tipos_curso.categorias, 'Número de alumnos', tipos_curso.series, tipos_curso.drilldown_datos);*/
                $('#comparativa_chrt').html('');
            }
        }
    })
    .fail(function (jqXHR, textStatus) {
        //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        console.log(jqXHR);
        console.log(textStatus);
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

/**
 *  Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *  @return string  Contenedor e imagen del cargador.
 */
function buscar_perfil(path, form_recurso, titulo) {
    var dataSend = $(form_recurso).serialize();
    if($('#tipo_curso_seleccion').val()=='' || $('#perfil_seleccion').val()=='') {
        alert('Debe seleccionar al menos un perfil y un tipo de curso para continuar con la búsqueda. \nSeleccione por favor.');
    } else {
        $.ajax({
            url: path,
            data: dataSend,
            method: 'POST',
            dataType: 'json',
            beforeSend: function (xhr) {
                mostrar_loader();
            }
        })
        .done(function (response) {
            if(titulo==null || titulo==''){
                titulo = '.';
            }
            if(typeof(response.total) != "undefined"){
                $('#capa_periodo_principal').html($('#anio').val());
                if(response.total == 0){
                    $('#total_alumnos_inscritos').html('-');
                    $('#total_alumnos_aprobados').html('-');
                    $('#total_alumnos_no_aprobados').html('-');
                    $('#total_alumnos_no_acceso').html('-');
                    $('#total_eficiencia_terminal').html('-');
                    $("#div_resultado").hide();
                    $("#tabla_tipo_curso").html('');
                    $("#tabla_perfil").html('');
                    $('#container_perfil').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
                } else {
                    $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
                    $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
                    $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
                    $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
                    $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
                    $("#div_resultado").show();
                    $("#tabla_tipo_curso").html(response.tabla_tipo_curso);
                    $("#tabla_perfil").html(response.tabla_perfil);
                    //crear_grafica_area('container_perfil', '', periodos, 'Número de alumnos', series_datos);
                    var periodo = obtener_categoria_serie_drilldown(response.periodo);
                    //console.log(periodo);
                    crear_grafica_drilldown('container_perfil', titulo, periodo.categorias, 'Número de alumnos', periodo.series, periodo.drilldown_datos);
                }
                $(".collapse_element").collapse("hide");
            }
        })
        .fail(function (jqXHR, textStatus) {
            $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
            ocultar_loader();
        })
        .always(function () {
            ocultar_loader();
        });
    }
}

function limpiar_filtros_listados(){
    $("#anio").val($("#anio option:first").val()); ///Seleccionamos primera opción para el periodo
    $("#periodo_seleccion").val($("#periodo_seleccion option:first").val()); ///Seleccionamos primera opción para el año
    $("#tipo_curso_seleccion").val('');
    /*$("#tipo_curso_seleccion_rootkey").val('');
    $("#tipo_curso_seleccion_node").val('');*/
    $("#perfil_seleccion").val('');
    /*$("#perfil_seleccion_rootkey").val('');
    $("#perfil_seleccion_node").val('');*/    
    var perfil_tree = $('#perfil_tree').fancytree('getTree'); ///Se limpian la selección de perfil y tipo de curso en los treeview
    perfil_tree.reload(SOURCE);
    var tipo_curso_tree = $('#tipo_curso_tree').fancytree('getTree');
    tipo_curso_tree.reload(SOURCE2);
    $('#container_perfil').html('');
    $('#tabla_tipo_curso').html('');
    $('#tabla_perfil').html('');

    /*jQuery.each( ['perfil', 'tipo_curso'], function( i, destino ) {
        //////////////////////////////////////////////////////////////////
        var tree = $('#'+destino+'_tree').fancytree('getTree');
        // Get a list of all selected nodes, and convert to a key array:
        var selKeys = $.map(tree.getSelectedNodes(), function(node){
            return node.key;
        });
        $('#'+destino+'_seleccion').val(selKeys.join(","));

        // Get a list of all selected TOP nodes
        var selRootNodes = tree.getSelectedNodes(true);
        // ... and convert to a key array:
        var selRootKeys = $.map(selRootNodes, function(node){
            return node.key;
        });
        $('#'+destino+'_seleccion_rootkey').val(selRootKeys.join(","));
        $('#'+destino+'_seleccion_node').val(selRootNodes.join(","));
        //////////////////////////////////////////////////////////////////
    });
    $("#temporal_tipo_busqueda").val('');
    setTimeout(function() {   //calls click event after a certain time
       buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda', 'Información');
    }, 500);*/
}

function mostrar_tipo_grafica(elemento){
    $(elemento+" > option").each(function() {
        if($('#capa_'+this.value).length>0){
            $('#'+this.value).val('');
            if(this.selected==true){
                if($('#tipos_busqueda').val()=='delegacion'){ ///Realizar carga de catálogo de Delegaciones
                    obtener_umae(site_url+'/informacion_general/obtener_delegacion', '#form_busqueda', '#delegacion');
                    $('#capa_agrupamiento_'+this.value).show();
                }
                if($('#tipos_busqueda').val()=='umae'){ ///Realizar carga de catálogo de UMAEs
                    obtener_umae(site_url+'/informacion_general/obtener_umae', '#form_busqueda', '#umae')
                    $('#capa_agrupamiento_'+this.value).show();
                }
                $('#capa_'+this.value).show();
                $('#container_'+this.value).show();
                calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');
            } else {
                $('#capa_'+this.value).hide();
                $('#container_'+this.value).hide();
                if($('#capa_agrupamiento_'+this.value).length>0){
                    $('#capa_agrupamiento_'+this.value).hide();
                }
            }
        }
    });
}

function obtener_umae(path, form_recurso, elemento_resultado) {
    $(elemento_resultado).val(''); ///Seleccionar por default
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        dataType: 'JSON',
        beforeSend: function (xhr) {
            //$(elemento_resultado).html(create_loader());
            //mostrar_loader();
        }
    })
    .done(function (response) {
        //var $el = $(elemento_resultado);
        $(elemento_resultado+' option:gt(0)').remove();
        //$el.empty(); // remove old options
        $.each(response, function(key,value) {
            console.log(key+' : '+value);
            $(elemento_resultado).append($("<option></option>").attr("value", key).text(value));
        });
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
    })
    .always(function () {
        //remove_loader();
        //ocultar_loader();
    });
}

function crear_grafica_stacked(elemento, titulo, categorias, texto_y, series_datos){
    var rotar = {};
    if(elemento=='container_umae' || elemento=='container_delegacion'){
        rotar = {
                rotation: -90,
                y:40                
            };
    }
    /*console.log('-----------------------------');
    console.log(elemento);
    console.log(titulo);
    console.log(categorias);
    console.log(texto_y);
    console.log(series_datos);
    console.log('++++++++++++++++++++++++++++++');*/
    Highcharts.chart(elemento, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
        xAxis: {
            labels: rotar,
            categories: categorias
        },
        yAxis: {
            visible: false,
            tickInterval: 1,
            allowDecimals: false,
            min: 0,
            title: {
                text: texto_y
            },
            stackLabels: {
                //enabled: false
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            //headerFormat: '<b>{point.x}</b><br/>',
            /*pointFormat: function () {
                return '{series.name}: {point.y}<br/>{series.stackKey}:: {point.stackTotal}';
            }*/
            //pointFormat: '{series.name}: {point.y}<br/>{series.stackKey}: {point.stackTotal}',
            formatter: function () {
                //console.log(this.point);
                //console.log(series);
                //var stackK = this.series.stackKey;
                return '<b>'+this.point.category+'</b><br/>'+this.series.name+': '+this.point.y+'<br/>'+this.series.options.stack+': '+this.point.stackTotal+'';
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    //enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: series_datos
    });
}

function crear_grafica_area(elemento, titulo, categorias, texto_y, series_datos){
    Highcharts.chart(elemento, {
        chart: {
            type: 'area'
        },
        title: {
            text: titulo
        },
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
        xAxis: {
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            },
            categories: categorias
        },
        yAxis: {
            visible: false,
            tickInterval: 1,
            allowDecimals: false,
            title: {
                text: texto_y
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        tooltip: {
            split: true,
            valueSuffix: ' alumnos'
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: series_datos
    });
}

function crear_grafica_stacked_grouped(elemento, titulo, categorias, texto_y, series_datos){
    Highcharts.chart(elemento, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
        xAxis: {
            categories: categorias
        },
        yAxis: {
            visible: false,
            tickInterval: 1,
            allowDecimals: false,
            min: 0,
            title: {
                text: texto_y
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>'+this.point.category+'</b><br/>'+this.series.name+': '+this.point.y+'<br/>'+this.series.options.stack+': '+this.point.stackTotal+'';
            }
            /*formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }*/
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    //enabled: true,
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: series_datos
    });
}

function crear_grafica_drilldown(elemento, titulo, categorias, texto_y, series_datos, drilldown_datos){
    /*console.log('elemento');
    console.log(elemento);
    console.log('titulo');
    console.log(titulo);
    console.log('categorias');
    console.log(categorias);
    console.log('texto_y');
    console.log(texto_y);
    console.log('series_datos');
    console.log(series_datos);
    console.log('drilldown_datos');
    console.log(drilldown_datos);*/
    Highcharts.Tick.prototype.drillable = function () {};

    /*setTimeout(function(){
        console.log(elemento);
        $(elemento).append('<br><p>* Nota. Puede visualizar el desglose de alumnos no aprobados, haciendo clic sobre la barra correspondiente. </p>');
    }, 2000); */

    var chart = Highcharts.chart(elemento, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
        xAxis: {
            type: 'category'
        },
        yAxis: {
            visible: false,
            tickInterval: 1,
            allowDecimals: false,
            min: 0,
            title: {
                text: texto_y
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function() {
                if(this.series.options.level==0){
                    return  this.series.name+': '+this.y;
                } else {
                    return  this.point.name+': '+this.y;
                }
            }
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: series_datos /*[{
            name: 'Inscritos',
            level:0,
            data: [{
                name: 'Médico',
                y: 21280
            }, {
                name: 'Enfermería',
                y: 2935
            }, {
                name: 'Otros',
                y: 101
            }]
        }, {
            name: 'Aprobados',
            level:0,
            data: [{
                name: 'Médico',
                y: 10638
            }, {
                name: 'Enfermería',
                y: 957
            }, {
                name: 'Otros',
                y: 42
            }]
        }, {
            name: 'No aprobados',
            level:0,
            data: [{
                name: 'Médico',
                y: 10642,
                drilldown: 'medico'
            }, {
                name: 'Enfermería',
                y: 1978,
                drilldown: 'enfermeria'
            }, {
                name: 'Otros',
                y: 59,
                drilldown: 'otros'
            }]
        }]*/,
        drilldown: drilldown_datos /*{
            series: [ {
                id: 'medico',
                name: 'No aprobados',
                level:1,
                data: [
                    ['Nunca entraron', 4321],
                    ['Reprobados', 6321]
                ]
            }, {
                id: 'enfermeria',
                name: 'No aprobados',
                level:1,
                data: [
                    ['Nunca entraron', 1005],
                    ['Reprobados', 973]
                ]
            }, {
                id: 'otros',
                name: 'No aprobados',
                level:1,
                data: [
                    ['Nunca entraron', 27],
                    ['Reprobados', 32]
                ]
            }]
        }*/
    }, function(chart) { // on complete
        chart.renderer.text("<br><p>* Nota. Dar clic para desplegar el desglose de alumnos 'No aprobados'.</p><br>", 10, 396)
            .css({
                color: '#4572A7',
                fontSize: '12px'
            })
            .add();
    });
}