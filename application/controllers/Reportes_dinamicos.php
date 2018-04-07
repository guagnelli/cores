<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para actualizar los hechos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Reportes_dinamicos extends MY_Controller
{

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Reportes_dinamicos_model', 'reporte_model');
        $this->template->setTitle('Reportes dinámicos');
    }

    public function index()
    {
        $reportes['data'] = $this->reporte_model->get_table();
        $main_content = $this->load->view('reportes_dinamicos/tabla', $reportes, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Reportes dinámicos');
        $this->template->getTemplate();
    }

    public function view($id = 0)
    {
        $filtros['limit'] = $this->input->post('limit') ? $this->input->post('limit') : 10;
        $info['reporte'] = $this->reporte_model->get_reporte($id, $filtros);
        $main_content = $this->load->view('reportes_dinamicos/reporte', $info, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Detalle del reporte');
        $this->template->getTemplate();
    }

    public function upload()
    {
        $output = [];
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['allowed_types'] = 'gz';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            if ($this->upload->do_upload())
            { //Se ejecuta la validación de datos
                $json = $this->reporte_model->get_content_file();
                //pr($json);
                $status = $this->reporte_model->valida_reporte_dinamico($json);
                if ($status)
                {
                    $status = $this->reporte_model->insert_data($json)['result'];
                }
            } else
            {
                $status = false;
            }
            $output['status'] = $status;       
        }
        $main_content = $this->load->view('reportes_dinamicos/formulario', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Nuevo reporte dinámico');
        $this->template->getTemplate();
    }

    public function columnas()
    {
        try
        {
            $this->db->schema = 'hechos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('columnas_dinamicas');
            $crud->unset_delete();
            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Configuración de columnas');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    /* solo para pruebas */

    private function test()
    {
        pr('hola');
        $json = $this->reporte_model->get_json();
        pr($json);
        //$resultado = $this->reporte_model->insert_data($json);
        pr('fin');
        pr($resultado);
    }

}
