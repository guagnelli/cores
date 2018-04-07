<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona las sesiones
 * @version 	: 2.0
 * @author      : MAGG
 * */
class Welcome extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('User_model', 'usr');
        $this->lang->load('session', 'spanish');
    }

    function index()
    {
        //load idioma
        $data["texts"] = $this->lang->line('formulario'); //textos del formulario
        //validamos si hay datos
        if ($this->input->post())
        {
            $post = $this->input->post();

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('login'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE)
            {
                $valido = $this->usr->validar_user($post["usuario"], $post["password"]);
                $mensajes = $this->lang->line('mensajes');
                switch ($valido)
                {
                    case 1:
                        //redirect to home //load menu...etc etc
                        $usuario = $this->usr->get_datos_usuario($post['usuario']);
                        $this->session->set_userdata('usuario', $usuario);
                        //pr($usuario);
                        redirect(site_url() . '/welcome/dashboard');
                        break;
                    case 2:
                        $this->session->set_flashdata('flash_password', $mensajes[$valido]);
                        break;
                    case 3:
                        $this->session->set_flashdata('flash_usuario', $mensajes[$valido]);
                        break;
                }
            }
        }

        //cargamos plantilla
        $this->template->setTitle($data["texts"]["title"]);

        $this->template->setSubTitle($data["texts"]["subtitle"]);
        $this->template->setDescripcion($data["texts"]["descripcion"]);

        $this->template->setMainContent("tc_template/login.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/blank.tpl.php");
    }

    function recargar_captcha()
    {
        $this->load->library('captcha');
        $data['captcha'] = $this->captcha->main();
        $this->session->set_userdata('captchaWord', $data['captcha']);
    }

    function dashboard()
    {
        $this->load->model('Menu_model', 'menu');
        $usuario = $this->session->userdata('usuario');
        if (isset($usuario['id_usuario']))
        {
            $menu = $this->menu->get_menu_usuario($usuario['id_usuario']);
            $this->template->setNav($menu);
            $perfil = $this->load->view('tc_template/perfil.tpl.php', $usuario, true);
            $this->template->setPerfilUsuario($perfil);
            $output = array();
            $main_content = $this->load->view('welcome/dashboard', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }
    }

    function cerrar_sesion(){
        $this->session->sess_destroy();
        redirect(site_url());
    }
}
