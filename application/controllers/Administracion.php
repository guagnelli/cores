<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administracion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->template->setTitle('Administración');
    }

    public function index()
    {
        echo "Error 404";
    }

    /**
     * Grocery crud de grupos registrados
     * @author Christian Garcia
     * @version 8 marzo 2017
     */
    public function grupos()
    {
        try
        {
            $this->db->schema = 'sistema';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('grupos');

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setSubTitle('Configuración de grupos');
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function bitacora($accion = null)
    {

        $output = [];

        //$output['registros'] = array('data'=>[], 'itemsCount'=>0);        
        if (is_null($accion))
        {
            $this->template->setSubTitle('Registro de actividades');
            $main_content = $this->load->view('administracion/bitacora.tpl.php', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } else
        {
            $this->load->library('Bitacora');
            $limit = !is_null($this->input->get('pageSize')) ? $this->input->get('pageSize') : Bitacora::PAGE_SIZE;
            $filtros = array(
                'limit' => $limit,
                'offset' => !is_null($this->input->get('pageIndex')) ? ($this->input->get('pageIndex') - 1) * $limit : 0
            );
            $opciones_filtro = array(
                'nombre', 'url', 'fecha', 'ip', 'matricula', 'categoria', 'delegacion', 'unidad'
            );
            $filtros['like'] = [];
            foreach ($this->input->get(null, true) as $key => $value)
            {
                if ($value != '' && in_array($key, $opciones_filtro))
                {
                    switch ($key)
                    {
                        case 'url':
                            $key = 'A.' . $key;
                            break;
                        case 'delegacion':
                            $key = 'DEL.nombre';
                            break;
                        case 'unidad':
                            $key = 'UNI.nombre';
                            break;
                        case 'categoria':
                            $key = 'CAT.nombre';
                            break;
                        case 'nombre':
                            $key = 'B.nombre';
                            break;
                    }
                    $filtros['like'][$key] = $value;
                }
            }
            $output['registros'] = $this->bitacora->get_registros($filtros);
//            $output['registros'] = count($output['registros']['data']);
            header('Content-Type: application/json; charset=utf-8;');
            echo json_encode($output['registros']);
        }
    }

}
