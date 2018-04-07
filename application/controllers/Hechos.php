<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para actualizar los hechos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Hechos extends MY_Controller
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
        $this->load->library('csvimport');
        $this->load->model('Hechos_model', 'hechos');
        $this->template->setSubTitle('Hechos');
    }

    /**
     * Acceso principal del controlador.
     * @autor 		: Christian García
     * @modified 	:
     * @access 		: public
     */
    public function index()
    {
        redirect(site_url() . '/hechos/get_lista/');
    }

    public function upload()
    {
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['allowed_types'] = 'csv';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            if ($this->upload->do_upload())
            {
                $csv = $this->hechos->get_content_csv();
                if ($this->hechos->valid_csv($csv)['status'])
                {
                    $resultado = $this->hechos->insert_data_csv($csv);
                    if ($resultado['result'])
                    {
                        //redirect(site_url() . '/hechos/get_lista/1');
                        $this->reporte_registro($resultado);
                    } else
                    {
                        redirect(site_url() . '/hechos/draw_form/3');
                    }
                } else
                {
                    redirect(site_url() . '/hechos/draw_form/3');
                }
            } else
            {
                redirect(site_url() . '/hechos/draw_form/2');
            }
        } else
        {
            redirect(site_url() . '/hechos/draw_form');
        }
    }
    
    private function reporte_registro(&$datos)
    {
        $filename = "Registro_Hechos" . date("d-m-Y_H-i-s") . ".xls";
        //header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
        header("Content-type: application/x-msexcel;charset=UTF-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $this->load->view('hechos/reporte_registro', $datos, TRUE);
        exit();
    }
    
    public function update_carga($id = 0, $activo = 0)
    {
        pr($activo);
        if ($id > 0)
        {
            $this->hechos->update($id, $activo);
        }
    }

    public function get_lista($status = null)
    {
        $data['status'] = $status;
        $data['lista'] = $this->hechos->get_lista();
        $main_content = $this->load->view('hechos/lista', $data, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Hechos');
        $this->template->getTemplate();
    }

    public function draw_form($status = null)
    {
        $output['status'] = $status;
        $main_content = $this->load->view('hechos/formulario', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Nuevo hecho');
        $this->template->getTemplate();
    }

}
