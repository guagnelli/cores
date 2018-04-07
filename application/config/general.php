<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['alert_msg'] = array(
    'SUCCESS' => array('id_msg' => 1, 'class' => 'success'),
    'DANGER' => array('id_msg' => 2, 'class' => 'danger'),
    'WARNING' => array('id_msg' => 3, 'class' => 'warning'),
    'INFO' => array('id_msg' => 4, 'class' => 'info')
);

$config['periodo'] = array(
	//'ANUAL' => array('id'=>1),
	'MENSUAL' => array('id'=>5, 'valor'=>'Mensual'),
	'BIMESTRAL' => array('id'=>4, 'valor'=>'Bimestral'),
	'TRIMESTRAL' => array('id'=>3, 'valor'=>'Trimestral'),
	'SEMESTRAL' => array('id'=>2, 'valor'=>'Semestral'),
);

$config['tipo_busqueda'] = array(
	'UMAE' => array('id'=>'umae', 'valor'=>'UMAE'),
	'DELEGACION' => array('id'=>'delegacion', 'valor'=>'Delegación')
);

$config['tipo_grafica'] = array(
	'PERFIL' => array('id'=>'perfil', 'valor'=>'Perfil'),
	'TIPO_CURSO' => array('id'=>'tipo_curso', 'valor'=>'Tipo de curso')
);

$config['tipos_busqueda'] = array(
	'DELEGACION' => array('id'=>'delegacion', 'valor'=>'Delegación'),
	'NIVEL_ATENCION' => array('id'=>'nivel_atencion', 'valor'=>'Nivel de atención'),
	'PERFIL' => array('id'=>'perfil', 'valor'=>'Perfil'),
	//'PERIODO' => array('id'=>'periodo', 'valor'=>'Periodo'),
	'REGION' => array('id'=>'region', 'valor'=>'Región'),
	'TIPO_CURSO' => array('id'=>'tipo_curso', 'valor'=>'Tipo de curso'),
	'UMAE' => array('id'=>'umae', 'valor'=>'UMAE')
);

$config['agrupamiento'] = array(
	'AGRUPAR' => array('id'=>'agrupar', 'valor'=>'Sí'),
	'DESAGRUPAR' => array('id'=>'desagrupar', 'valor'=>'No'),
);

$config['grupo_tipo_unidad'] = array(
	'UMAE' => array('id'=>'UMAE', 'valor'=>'UMAE'),
	'CUMAE' => array('id'=>'CUMAE', 'valor'=>'CUMAE')
);

$config['DELEGACIONES'] = array(
	'SIN_DELEGACION' => array('id'=>'1', 'valor'=>'SIN DELEGACION'),
	'MANDO' => array('id'=>'40', 'valor'=>'MANDO')
);

$config['plan_implementaciones_url'] = base_url().'assets/files/file.pdf';