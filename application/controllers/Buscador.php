<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Buscador
 *
 * @author chrigarc
 */
class Buscador extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('Catalogo_listado');
    }

    public function get_delegaciones($id_region = 0)
    {
        $this->load->model('Buscador_model', 'buscador');
        $delegaciones = $this->buscador->get_delegaciones($id_region);
        echo json_encode($delegaciones);
    }

    public function search_unidad_instituto()
    {
        if ($this->input->post())
        {
            //$keyword = 'COO';
            $this->load->model('Usuario_model', 'usuario');
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $tipo_unidad = 0;
            $delegacion = 0;
            if ($keyword != null)
            {
                $usuario = $this->session->userdata('usuario');
                if (is_nivel_operacional($usuario['grupos']) || is_nivel_tactico($usuario['grupos']))
                {
                    $delegacion = $usuario['grupo_delegacion'];
                    $tipo_unidad = $usuario['id_tipo_unidad'];
                }
                if (is_nivel_central($usuario['grupos']) || is_nivel_tactico($usuario['grupos']) || is_nivel_estrategico($usuario['grupos']))
                {
                    if ($this->input->post('tipo_unidad', true))
                    {
                        $tipo_unidad = $this->input->post('tipo_unidad', true);
                    }
                }
                if (is_nivel_central($usuario['grupos']) || is_nivel_estrategico($usuario['grupos']))
                {
                    if ($this->input->post('delegacion', true))
                    {
                        $delegacion = $this->input->post('delegacion', true);
                    }
                }
                $periodo = $this->input->post('periodo', true);
                $output['unidades'] = $this->usuario->lista_unidad($keyword, $tipo_unidad, $delegacion, $periodo);
                echo $this->load->view('buscador/unidades_instituto', $output, true);
            }
        }
    }

    public function search_categoria()
    {
        if ($this->input->post())
        {
            //$keyword = 'COO';
            $this->load->model('Usuario_model', 'usuario');
            $keyword = $this->input->post('keyword', true);
            $keyword = strtolower($keyword);
            $output['categorias'] = $this->usuario->lista_categoria($keyword);
            echo $this->load->view('buscador/categorias', $output, true);
        }
    }

    public function search_grupos_categorias()
    {
        if ($this->input->post())
        {
            $this->load->model('Buscador_model', 'buscador');
            $grupos_categorias = $this->buscador->get_grupos_categorias($this->input->post());
            echo json_encode($grupos_categorias);
        }
    }

    public function get_tipo_unidad()
    {
        if ($this->input->post())
        {
            $this->load->model('Comparativa_model', 'comparativa');
            $usuario = $this->session->userdata('usuario');
            $delegacion = 0;
            if (is_nivel_operacional($usuario['grupos']) || is_nivel_tactico($usuario['grupos']))
            {
                $delegacion = $usuario['grupo_delegacion'];
            }
            $nivel = $this->input->post('nivel', true);
            $umae = $usuario['umae'];
            if (is_nivel_central($usuario['grupos']) && $this->input->post('umae') && $this->input->post('umae') == 1)
            {
                $umae = true;
            }
            $tipos_unidades = $this->comparativa->get_tipos_unidades($umae, $delegacion, $nivel);
            echo json_encode($tipos_unidades);
        }
    }

    public function get_unidades($umae = 0)
    {
        if ($this->input->post())
        {
            $this->load->model('Buscador_model', 'buscador');
            $usuario = $this->session->userdata('usuario');
            $id_tipo_unidad = $this->input->post('tipo_unidad', true);
            $umae = $umae == 1 ? true : false;
            $delegacion = 0;
            $condiciones = array('umae' => $umae,
                'id_tipo_unidad' => $id_tipo_unidad, 'agrupamiento' => 1);
            if($this->input->post('periodo') != null){
                $condiciones['periodo'] = $this->input->post('periodo', true);
            }
            if (is_nivel_operacional($usuario['grupos']) || is_nivel_tactico($usuario['grupos']))
            {
                $delegacion = $usuario['grupo_delegacion'];
                $condiciones += array('id_delegacion' => $delegacion);
            }            
            //pr($this->input->post('agrupamiento', true));
            if(is_nivel_central($usuario['grupos']) && $this->input->post('agrupamiento') != null &&  $this->input->post('agrupamiento') == 0){
                $condiciones['agrupamiento'] = 0;
            }
            //pr($condiciones);
            $output = $this->buscador->get_unidades($condiciones);
            echo json_encode($output);
        }
    }

}
