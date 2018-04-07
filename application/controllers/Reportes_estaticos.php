<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para actualizar los hechos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Reportes_estaticos extends MY_Controller
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
        $this->load->model('Reportes_estaticos_model', 'reporte_model');
        $this->template->setTitle('Reportes estáticos');
    }

    public function index($status = null)
    {
        $reportes['data'] = $this->reporte_model->get_table();
        $reportes['status'] = $status;        
        $reportes['plan'] = $this->reporte_model->get_mark_file(Reportes_estaticos_model::PLAN_IMPLEMENTACIONES);        
        $main_content = $this->load->view('reportes_estaticos/tabla', $reportes, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Reportes estáticos');
        $this->template->getTemplate();
    }

    public function upload()
    {
        if ($this->input->post())
        {     // SI EXISTE UN ARCHIVO EN POST
            $config['allowed_types'] = 'pdf';           // CONFIGURAMOS EL TIPO DE ARCHIVO A CARGAR
            $config['upload_path'] = './uploads/';      // CONFIGURAMOS LA RUTA DE LA CARGA PARA LA LIBRERIA UPLOAD
            $config['max_size'] = '1000';               // CONFIGURAMOS EL PESO DEL ARCHIVO
            $this->load->library('upload', $config);    // CARGAMOS LA LIBRERIA UPLOAD
            if ($this->upload->do_upload())
            {
                $this->form_validation->set_rules('nombre', 'Nombre', 'required');
                if ($this->form_validation->run())
                { //Se ejecuta la validación de datos
                    $nombre = $this->input->post("nombre");
                    $descripcion = $this->input->post("descripcion");
                    $status = $this->reporte_model->upload($nombre, $descripcion);
                    if ($status)
                    {
                        redirect(site_url() . '/reportes_estaticos/index/1');
                    } else
                    {
                        redirect(site_url() . '/reportes_estaticos/index/2');
                    }
                } else
                {
                    redirect(site_url() . '/reportes_estaticos/draw_form/2');
                    //pr('datos no validos');
                }
            } else
            {
                //pr('fail upload');
                redirect(site_url() . '/reportes_estaticos/draw_form/3');
            }
        } else
        {
            redirect(site_url() . '/reportes_estaticos/draw_form/4');
        }
    }

    public function descarga($id = 0, $dentro_navegador = 0)
    {
        $reporte = $this->reporte_model->get_reporte($id);
        if ($reporte != null)
        {
//            pr($reporte);
            $size = filesize("./uploads/" . $reporte['filename']);
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $reporte['tipo']);
            if ($dentro_navegador != 1)
            {
                header('Content-Disposition: attachment; filename="' . $reporte['filename'] . '"');
            } else
            {
                header('Content-Disposition: inline; filename="' . $reporte['filename'] . '"');
            }
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);
            readfile("./uploads/" . $reporte['filename']);
        }
    }

    public function draw_form($status = null)
    {

        $output['status'] = $status;
        $main_content = $this->load->view('reportes_estaticos/formulario', $output, true);
        $this->template->setMainContent($main_content);
        $this->template->setSubTitle('Nuevo reporte estático');
        $this->template->getTemplate();
    }
    
    public function set_mark($mark = null, $id_file = null){
        if(!is_null($mark) && !is_null($id_file)){
            $this->reporte_model->set_mark_file($mark, $id_file);
            redirect('reportes_estaticos');
        }
    }

}
