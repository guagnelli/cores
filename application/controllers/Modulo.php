<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Modulo
 *
 * @author chrigarc
 */
class Modulo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->model('Modulo_model', 'modulo');
        $this->load->model('Grupos_usuarios_model', 'grupos');
        $this->load->helper('modulo_helper');
        $this->template->setTitle('Módulos');
    }

    public function index($full_view = 1)
    {
        $output['full_view'] = $full_view;
        $output['modulos'] = $this->modulo->get_modulos();
        $output['configuradores'] = dropdown_options($this->modulo->get_configuradores(), 'id_configurador', 'nombre');
        $output['modulos_dropdown'] = dropdown_options($this->modulo->get_modulos(0,false), 'id_modulo', 'nombre');
        
        if ($full_view == 1)
        {
            $view = $this->load->view('modulo/index', $output, true);
            $modal = $this->load->view('modulo/modal_custom_modulo', $output, true);
            $this->template->setCuerpoModal($modal);
            $this->template->setMainContent($view);
            $this->template->setSubTitle('Módulos');
            $this->template->getTemplate();
        }else{
            $this->load->view('modulo/index', $output);
        }
    }

    public function modulos_grupo()
    {

        $output['grupos'] = dropdown_options($this->grupos->get_grupos(), 'id_grupo', 'nombre');
        $view = $this->load->view('modulo/index_modulos_grupo', $output, true);
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Configuración de módulos por grupo');
        $this->template->getTemplate();
    }

    public function get_modulos_grupo($id_grupo = 0)
    {
        
        $output['grupo'] = $id_grupo;
        $output['modulos'] = $this->modulo->get_modulos_grupo($id_grupo, true, false);
        if ($this->input->post() && !empty($this->input->post('grupo', true)))
        {
            $this->modulo->upsert_modulos_grupo($id_grupo, $output['modulos'], $this->input->post());
        }
        $output['modulos'] = $this->modulo->get_modulos_grupo($id_grupo, true);
        $output['configuradores'] = dropdown_options($this->modulo->get_configuradores(), 'id_configurador', 'nombre');
        $this->load->view('modulo/modulos_grupo', $output);
    }

    public function get_modulo($id_modulo = 0)
    {
        if ($this->input->post())
        {
            $datos['nombre'] = $this->input->post('modulo', true);
            $datos['url'] = $this->input->post('url', true);
            $datos['tipo'] = $this->input->post('tipo', true);
            $datos['padre'] = $this->input->post('padre', true);
            $datos['orden'] = $this->input->post('orden', true);
            $datos['visible'] = ($this->input->post('visible', true) != null ? true : FALSE);
            $datos['icono'] = $this->input->post('icono', true);
            $salida['status'] = $this->modulo->update($id_modulo, $datos);
        }
        $salida['modulo'] = $this->modulo->get_modulos($id_modulo)[0];
        echo json_encode($salida);
    }

    public function new_modulo()
    {
        if ($this->input->post())
        {
            $datos['nombre'] = $this->input->post('modulo', true);
            $datos['url'] = $this->input->post('url', true);
            $datos['tipo'] = $this->input->post('tipo', true);
            $datos['padre'] = (empty($this->input->post('padre', true))?null : $this->input->post('padre', true));
            $datos['orden'] = $this->input->post('orden', true);
            $datos['visible'] = ($this->input->post('visible', true) != null ? true : FALSE);
            $datos['icono'] = $this->input->post('icono', true);
            $salida['status'] = $this->modulo->insert($datos);
        }
    }

}
