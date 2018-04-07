<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Autenticacion
 *
 * @author chrigarc
 */
class Autenticacion
{

    private static $libre_acceso = array(
        'welcome/index',
        'welcome/cerrar_sesion',
        'welcome/recuperar_password',
        'captcha/index',
    );

    function acceso()
    {
        $CI = & get_instance(); //Obtiene la insatancia del super objeto en codeigniter para su uso directo
//        echo $CI->load->view('template/sin_acceso', $datos_, true);
//        return json_encode($array_result);
        $CI->load->helper('url');
        $CI->load->library('session');

        $controlador = $CI->uri->rsegment(1);  //Controlador actual o dirección actual
        $accion = $CI->uri->rsegment(2);  //Función que se llama en el controlador

        $url = $controlador . '/' . $accion;

        if (!in_array($url, Autenticacion::$libre_acceso))
        {
            $usuario = $CI->session->userdata('usuario');
//            pr($usuario);
            if (isset($usuario['id_usuario']))
            {
                if (!$this->verifica_permiso($CI, $usuario))
                {                                        
                    redirect(site_url());
                }else{
                    $this->bitacora();
                }
            } else
            {
                redirect(site_url());
            }
        }
    }

    private function verifica_permiso($CI, $usuario)
    {
        $controlador = $CI->uri->rsegment(1);  //Controlador actual o dirección actual
        $accion = $CI->uri->rsegment(2);  //Función que se llama en el controlador
        $url = '/' . $controlador . '/' . $accion;
        $CI->load->model('Modulo_model', 'modulos');
        $modulo = $CI->modulos->check_acceso($url, $usuario['id_usuario']);        
        $is_index = null;
        if($accion == 'index'){
            $is_index = $CI->modulos->check_acceso('/'.$controlador, $usuario['id_usuario']);
        }
//        pr($url);
//        pr($modulo);
//        pr($is_index);
        return $modulo != null || $is_index != null || $url == '/welcome/dashboard';
//        return true;
    }

    private function bitacora()
    {
        $CI = & get_instance(); //Obtiene la insatancia del super objeto en codeigniter para su uso directo        
        $CI->load->library('Bitacora');
        $CI->bitacora->registra_actividad();
    }
}
