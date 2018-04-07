<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Buscador_model
 *
 * @author chrigarc
 */
class Buscador_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_grupos_categorias($filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_grupo_categoria', 'descripcion as nombre'
        );
        $this->db->select($select);
        if (isset($filtros['subcategoria']))
        {
            $this->db->where('id_subcategoria', $filtros['subcategoria']);
            $this->db->where('activa', 'true');
        }        
        $this->db->order_by('order', 'asc');
        $subcategorias = $this->db->get('catalogos.grupos_categorias')->result_array();
        return $subcategorias;
    }

    public function get_delegaciones($id_region = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_delegacion', 'A.nombre'
        );
        $this->db->select($select);
        $this->db->join('catalogos.regiones B', ' B.id_region = A.id_region', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('B.activo', true);
        $this->db->where('A.id_region', $id_region);
        $delegaciones = $this->db->get('catalogos.delegaciones A')->result_array();
        return $delegaciones;
    }

    public function get_unidades($filtros = [])
    {        
        $this->db->flush_cache();
        $this->db->reset_query();
        if (!isset($filtros['periodo']) || $filtros['periodo'] == "")
        {
            $periodo = date("Y");
        } else
        {
            $periodo = $filtros['periodo'];
        }
        $grupo_principal[0] = 'A.id_unidad_instituto';
        $grupo_principal[1] = 'A.nombre';
        if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 1 && $filtros['umae'])
        {
            $grupo_principal[0] = 'A.nombre_unidad_principal';
            $grupo_principal[1] = 'A.nombre_unidad_principal';
        }
        $select = array($grupo_principal[0] . ' id_unidad_instituto', $grupo_principal[1] . ' nombre');
        $this->db->select($select);
        $this->db->join('catalogos.tipos_unidades t', 'A.id_tipo_unidad = t.id_tipo_unidad', 'left');
        if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 1)
        {
            $this->db->join('catalogos.delegaciones d', 'd.grupo_delegacion = A.grupo_delegacion', 'left');
        }
        if (isset($filtros['umae']) && $filtros['umae'] != null && $filtros['umae'])
        {
            $this->db->where("(t.grupo_tipo = 'UMAE' OR t.grupo_tipo = 'CUMAE')");
        } else
        {
            $this->db->where("(t.grupo_tipo != 'UMAE' OR t.grupo_tipo != 'CUMAE')");
        }
        $this->db->where('A.anio', $periodo);
        if (isset($filtros['id_delegacion']))
        {
            if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 1)
            {                
                $this->db->where('d.grupo_delegacion', $filtros['id_delegacion']);
            } else
            {
                $this->db->where('id_delegacion', $filtros['id_delegacion']);
            }
        }
        
        if(isset($filtros['id_tipo_unidad']) && !empty($filtros['id_tipo_unidad'])){
            $this->db->where('A.id_tipo_unidad', $filtros['id_tipo_unidad']);
        }

        $this->db->group_by($grupo_principal);
        $this->db->order_by($grupo_principal[1], 'asc');
        $unidades = $this->db->get('catalogos.unidades_instituto A')->result_array();
//        pr($this->db->last_query());
        $this->db->reset_query();
        return $unidades;
    }

}
