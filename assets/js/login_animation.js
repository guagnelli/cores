var cores_colores = ['#0095bc', '#f05f50', '#f3b510', '#98c56e'];
var cores_textos = ['Decisiones basadas en información', '¿Qué está pasando en materia educativa?', 'Nuestra visión, enfocarnos en los resultados', 'Claridad y significado en los datos'];
var cores_cantidad_bloques = 6;
var cores_time_ms = 5000;
var cores_index_banner = 0;
var cores_image_w = 1942;
var cores_nucleo_centro_w = 830;
var cores_nucleo_centro_h = 480;

$(function () {    
    cores_render_points2();
    cores_banner();
});

function cores_banner(){
    if(cores_index_banner>cores_textos.length){
        cores_index_banner = 0;
    }
    $('#cores-banner').fadeOut( "slow" );
    setTimeout(function (){
        $('#cores-banner').text(cores_textos[cores_index_banner++]).fadeIn('slow');
    }, 1000);            
    setTimeout(cores_banner, cores_time_ms);
}

function cores_render_points() {
    $('#cores-area-animation').html('');
    console.log('generando animacion');
    for (i = 0; i < cores_cantidad_bloques; i++) {
        for (j = 0; j < cores_colores.length; j++) {
            var size_limite = $('.cores-background').height() > $('#cores-area-animation').width() ? $('#cores-area-animation').width() : $('.cores-background').height();
            //var size_limite = $('.cores-background').height() > $('#cores-area-principal').width() ? $('#cores-area-principal').width() : $('.cores-background').height();
            var size = cores_get_size((size_limite * .75), i);
            var centro_h = ($('.cores-background').height() / 2) - (size / 2);
            var centro_w = ($('#cores-area-animation').width() / 2) - (size / 2);
            //var centro_w = ($('#cores-area-principal').width() / 2) - (size / 2);
            var area1 = $('<div>')
                    .css({width: size, height: size, top: centro_h, left: centro_w, position: 'absolute'});                       
            cores_agrega_punto(area1, size, j);
            $('#cores-area-animation').append(area1);
        }
    }
}

function cores_agrega_punto(area1, size, j) {
    var css = cores_get_css(size/2, j);
    var circle = $('<div>')
            .css(css);
    area1.append(circle);
    area1.addClass('cores-orbit');
}

function cores_get_size(max_size, index) {
    var tmp = Math.floor(Math.random() * max_size);
    while (tmp > max_size) {
        tmp = Math.floor(Math.random() * max_size);
    }
    return tmp;
}

function cores_get_css(size, index) {
    var size_circle = Math.floor(Math.random() * 15);
    var pos = Math.floor(Math.random() * (size));
    //pos = 0;
    var a = {};
    switch (index) {
        case 0:
            a = {left: pos, position: 'absolute', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%'};
            break;
        case 1:
            a = {top: pos, position: 'relative', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', float: 'right'};
            break;
        case 2:
            a = {position: 'absolute', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', bottom: '0px'};
            break;
        case 3:
            a = {right: pos, position: 'relative', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', top: '100%', float: 'right'}
            break;
    }
    return a;
}

function cores_render_points2(){
    $('#cores-area-animation').html('');
//    var size_limite = $('.cores-background').height() > $('#cores-area-animation').width() ? $('#cores-area-animation').width() : $('.cores-background').height();
    var size_limite = $('.cores-background').height() > $('#cores-area-principal').width() ? $('#cores-area-principal').width() : $('.cores-background').height();
    var size = size_limite * .75;
//    var centro_h = ($('.cores-background').height() / 2) - (size / 2);
//    var centro_w = ($('#cores-area-animation').width() / 2) - (size / 2);
//    var centro_w = ($('#cores-area-principal').width() / 2) - (size / 2) + 30;
    var s_w = $('#cores-area-principal').width() + 30; //30 son de los margenes
//    console.log(s_w);
    s_w = s_w / cores_image_w ; //escala a trabajar
//    console.log(s_w);
    var centro_h = 0 - (size / 2) + (cores_nucleo_centro_h * s_w);
    var centro_w = 0 - (size / 2) + (cores_nucleo_centro_w * s_w);
    var area1 = $('<div>')
                    .css({width: size, height: size, top: centro_h, left: centro_w, position: 'absolute'});                       
	for(i=0;i<6;i++){
		cores_agrega_punto2(area1, size, i, 0);			
		cores_agrega_punto2(area1, size, i, 1);			
		cores_agrega_punto2(area1, size, i, 2);			
		cores_agrega_punto2(area1, size, i, 3);			
	}
    area1.addClass('cores-orbit');
	$('#cores-area-animation').append(area1);
}

function cores_agrega_punto2(area1, size, i, sector) {
	var s = 5 + i;
	var step = (size) / (6*2);
	var time = i;
	var x = 0;
	var y = 0;
//	console.log(sector);
	switch(sector){
		case 0: 
			x = step*time;
			y = step*time;				 		
			break;
		case 1:
			x = (size/2) + step*time;
			y = step*time;
			break;
		case 2:
			x = (size/2) + step*time;
			y = (size/2) + step*time;
			break;
		case 3:
			x = step*time;
			y = (size/2) + step*time; 		 		
			break;
	}
	var random_x = Math.floor(Math.random() * (size/4)); 
	random_x = (Math.floor(Math.random() * (2)))==0? random_x : -1*random_x; 
	x += random_x;

	var css = {top: y, left: x, width: s, height: s, background: cores_colores[sector], position: 'absolute', 'border-radius': '50%'};
	
    var circle = $('<div>')
            .css(css)
            .addClass('cores-circle')
            .css('animation-duration', (5+i)+'s');
    area1.append(circle);    
}
