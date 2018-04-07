<?php
/**
 * Archivo que contiene los textos del sistema
 * Contrucción del índice.
 * 	- Archivo fuente: interface_
 * 	- Modulo: login
 *  - Controlador: login
 *  - Identificador único del texto dentro del arreglo: texto_bienvenida
 * 		Ej:
 * 			$lang['interface_login']['login']['texto_bienvenida'] = 'Bienvenido al sistema SIPIMSS';
 * 			$lang['interface_login']['login']['texto_usuario'] = 'Usuario:';
 * 			$lang['interface_login']['login']['texto_contrasenia'] = 'Contraseña:';
 * 			$lang['interface_censo']['formacion']['texto_bienvenida'] = '...';
 * 			$lang['interface_censo']['actividad']['texto_bienvenida'] = '...';
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

//$lang['interface_'][''][''] = '';
//$lang['interface']['registro']['texto_bienvenida'] = 'Hola mundo';
$lang['interface'] = array(
    'informacion_general' => array(
        'titulo_sistema' => 'Consulta de Resultados de Educación en Salud',
        'ver_datos_generales' => 'Ver datos generales',
        'ultima_actualizacion' => 'Última actualización',
        'titulo_principal' => 'Información general',
        'texto_informativo' => 'Analizar por',
        'titulo_por_perfil' => 'Por perfil',
        'titulo_por_unidad' => 'Por unidad',
        'titulo_por_tipo_usuario' => 'Por tipo de curso',
        'titulo' => 'Resultados generales de cursos $tipo_curso $unidad $delegacion, durante el periodo <span id="capa_periodo_principal">$periodo</span>',
        'alumnos_inscritos' => 'Alumnos inscritos',
        'alumnos_aprobados' => 'Alumnos aprobados',
        'alumnos_no_aprobados' => 'Alumnos no aprobados',
        'alumnos_reprobados' => 'Alumnos reprobados',
        'alumnos_no_acceso' => 'Alumnos nunca entraron',
        'no_accesos' => 'No accesos',
        'eficiencia_terminal' => 'Eficiencia terminal modificada',
        'agrupar' => 'Agrupar',
        'agrupamiento' => 'Agrupamiento de Delegaciones y UMAE',
        'desagrupar' => 'Desagrupar',
        'periodo' => 'Periodo',
        'anio' => 'Año',
        'tipo_curso' => 'Tipo de curso',
        'tipo_unidad' => 'Tipo de unidad',
        'tipo_grafica' => 'Analizar por',
        'tipo' => 'Criterio de búsqueda',
        'perfil' => 'Perfil',
        'region' => 'Región',
        'delegacion' => 'Delegación',
        'unidades' => 'Unidades',
        'unidad' => 'Unidad',
        'umae' => 'UMAE',
        'nivel_atencion' => 'Nivel de atención',
        'nivel_central' => 'Nivel central',
        'direccion_normativa' => 'Dirección normativa',
        'filtros' => 'Filtros',
        'error_seleccion_tipo_busqueda' => 'Debe seleccionar el criterio de búsqueda'
    ),
    'datos_usuario' => array(
        'nombre' => 'Nombre',
        'matricula' => 'Matrícula',
        'categoria' => 'Categoría',
        'delegacion' => 'Delegación',
        'unidad' => 'Unidad',
        'umae' => 'UMAE'
    ),
    'general' => array(
        'acciones' => 'Acciones',
        'editar' => 'Editar',
        'eliminar' => 'Eliminar',
        'enviar' => 'Enviar',
        'cancelar' => 'Cancelar',
        'no_existe_datos' => 'No existen datos.',
        'no_existen_implementacion' => 'Cursos implementándose, aún sin resultados finales.',
        'seleccione' => 'Seleccione...',
        'buscar' => 'Buscar',
        'limpiar_filtros' => 'Limpiar filtros'
    ),
    'general_model' => array(
        'insercion' => 'Se ha insertado correctamente la información.',
        'actualizacion' => 'Se ha actualizado correctamente la información.',
        'eliminacion' => 'Se ha eliminado correctamente.',
        'error' => 'Ocurrió un error, por favor intentelo de nuevo más tarde.',
    ),
);