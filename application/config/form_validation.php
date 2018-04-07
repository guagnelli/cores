<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config = array(
    'formulario_nombre' => array(
        array(
            'field' => 'campo',
            'label' => 'Etiqueta visible',
            'rules' => 'required|regla2'
        ),
    ),
    'form_user_update_password' => array(
        array(
            'field' => 'pass',
            'label' => 'Contraseña',
            'rules' => 'required|min_length[8]'
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'Confirmar contraseña',
            'rules' => 'required|min_length[8]' //|callback_valid_pass
        ),
    ),
    'form_actualizar' => array(
        array(
            'field' => 'email',
            'label' => 'Correo electrónico',
            'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
        ),
        array(
            'field' => 'unidad',
            'label' => 'Unidad',
            'rules' => 'required' //|callback_valid_pass
        )
    ),
    'form_registro' => array(
        array(
            'field' => 'matricula',
            'label' => 'Matrícula',
            'rules' => 'required|max_length[18]|alpha_dash'
        ),
        array(
            'field' => 'delegacion',
            'label' => 'Delegación',
            'rules' => 'required' //|callback_valid_pass
        ),
        array(
            'field' => 'email',
            'label' => 'Correo electrónico',
            'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
        ),
        array(
            'field' => 'pass',
            'label' => 'Contraseña',
            'rules' => 'required' //|callback_valid_pass
        ),
        array(
            'field' => 'repass',
            'label' => 'Confirmación contraseña',
            'rules' => 'required|matches[pass]'
        ),
        array(
            'field' => 'niveles',
            'label' => 'Niveles de Atencion',
            'rules' => 'required'
        )
    ),
);

$config["login"] = array(
    array(
        'field' => 'usuario',
        'label' => 'Usuario',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    array(
        'field' => 'password',
        'label' => 'Contraseña',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    array(
        'field' => 'captcha',
        'label' => 'Imagen de seguridad',
        'rules' => 'required|check_captcha',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
            'check_captcha' => "El texto no coincide con la imagen, favor de verificarlo."
        ),
    ),
);

$config['form_actualizar_perfil'] = array(
    array(
        'field' => 'email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
);
$config['form_actualizar_perfil_password'] = array(
    array(
        'field' => 'pass',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'repass',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[pass]'
    ),
);

$config['filtros_comparativa_tipo_curso'] = array(
    array(
        'field' => 'periodo', 
        'label' => 'Año', 
        'rules' => 'required|is_numeric'
    ), 
    array(
        'field' => 'unidad1', 
        'label' => 'unidad1', 
        'rules' => 'required'
    ), 
    array(
        'field' => 'unidad2', 
        'label' => 'unidad2', 
        'rules' => 'required'
    ),     
    array(
        'field' => 'tipo_curso', 
        'label' => 'Año', 
        'rules' => 'required|is_numeric'
    )
);

$config['filtros_comparativa_perfil'] = array(
    array(
        'field' => 'periodo', 
        'label' => 'Año', 
        'rules' => 'required'
    ), 
    array(
        'field' => 'unidad1', 
        'label' => 'unidad1', 
        'rules' => 'required'
    ), 
    array(
        'field' => 'unidad2', 
        'label' => 'unidad2', 
        'rules' => 'required'
    ),     
    array(
        'field' => 'subperfil', 
        'label' => 'perfil', 
        'rules' => 'required|is_numeric'
    )
);

$config['form_departamento'] = array(
        array(
        'field' => 'nombre', 
        'label' => 'Nombre', 
        'rules' => 'required'
    ), 
    array(
        'field' => 'clave', 
        'label' => 'Clave', 
        'rules' => 'required'
    ), 
    array(
        'field' => 'unidad', 
        'label' => 'Unidad/UMAE', 
        'rules' => 'required|is_numeric'
    ),     
);