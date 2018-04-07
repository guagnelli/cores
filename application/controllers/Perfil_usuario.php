<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perfil_usuario
 *
 * @author chrigarc
 */
class Perfil_usuario extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('seguridad');
        $this->load->model('Usuario_model', 'registro');
    }

    public function index()
    {
        $usuario = $this->session->userdata('usuario');
        if ($this->input->post())
        {
            $this->load->model('Perfil_usuario_model', 'perfil');
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            if ($this->input->post('formulario') && $this->input->post('formulario', true) == 'datos_personales')
            {
                $output['formulario'] = 'datos_personales';
                $validations = $this->config->item('form_actualizar_perfil'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations); //Añadir validaciones
                if ($this->form_validation->run() == TRUE)
                {
                    $output['status'] = $this->perfil->update($usuario, $this->input->post());
                } else
                {
                 //   pr(validation_errors());
                }
            } else if ($this->input->post('formulario'))
            {
                $output['formulario'] = 'update_password';
                $validations = $this->config->item('form_actualizar_perfil_password'); //Obtener validaciones de archivo general
                $this->form_validation->set_rules($validations); //Añadir validaciones
                if ($this->form_validation->run() == TRUE)
                {
                    $output['status'] = $this->perfil->update_password($usuario, $this->input->post());
                } else
                {
                 //   pr(validation_errors());
                }
            }
        }
        $output['usuarios'] = $this->registro->datos_usuario($usuario['id_usuario']);
        $main_content = $this->load->view('perfil_usuario/index', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->getTemplate();
    }

}
