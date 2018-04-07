<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Catalogo_model
 *
 * @author chrigarc
 */
class Catalogo_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_categorias($filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        if (isset($filtros['per_page']) && $filtros['current_row'])
        {
            $this->db->limit($filtros['per_page'], $filtros['current_row'] * $filtros['per_page']);
        } else if (isset($filtros['per_page']))
        {
            $this->db->limit($filtros['per_page']);
        }

        if (isset($filtros['order']) && $filtros['order'] == 2)
        {
            $this->db->order_by('A.clave_categoria', 'DESC');
        } else
        {
            $this->db->order_by('A.clave_categoria', 'ASC');
        }

        $select = array(
            'A.id_categoria', 'A.nombre categoria', 'A.id_grupo_categoria',
            'B.nombre grupo_categoria', 'A.clave_categoria'
        );
        $this->db->select($select);
        $this->db->join('catalogos.grupos_categorias B', ' B.id_grupo_categoria = A.id_grupo_categoria', 'left');
        $this->db->where('A.activa', true);
        $categorias['data'] = $this->db->get('catalogos.categorias A')->result_array();
        $this->db->reset_query();
        $this->db->select('count(*) total');
        $categorias['total'] = $this->db->get('catalogos.categorias A')->result_array()[0]['total'];
        $categorias['per_page'] = $filtros['per_page'];
        $categorias['current_row'] = $filtros['current_row'];
        return $categorias;
    }

    public function get_departamentos($filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        if (isset($filtros['per_page']) && $filtros['current_row'])
        {
            $this->db->limit($filtros['per_page'], $filtros['current_row'] * $filtros['per_page']);
        } else if (isset($filtros['per_page']))
        {
            $this->db->limit($filtros['per_page']);
        }

        if (isset($filtros['order']) && $filtros['order'] == 2)
        {
            $this->db->order_by('A.clave_departamental', 'DESC');
        } else
        {
            $this->db->order_by('A.clave_departamental', 'ASC');
        }

        $select = array(
            'A.id_departamento_instituto', 'A.nombre departamento',
            'A.clave_departamental', 'A.id_unidad_instituto', 'B.nombre unidad',
            'B.clave_unidad'
        );
        $this->db->select($select);
        $this->db->start_cache();
        $this->db->join('catalogos.unidades_instituto B', 'B.id_unidad_instituto = A.id_unidad_instituto', 'inner');
        $this->db->where('A.activa', true);
        $this->db->where('B.activa', true);
        if (isset($filtros['type']) && isset($filtros['keyword']) &&
                !empty($filtros['keyword']) && in_array($filtros['type'], array('clave_departamental', 'departamento', 'unidad', 'clave_unidad')))
        {
            $type = $filtros['type'];
            switch ($filtros['type'])
            {
                case 'departamento': $type = 'A.nombre';
                    break;
                case 'unidad': $type = 'B.nombre';
                    break;
            }

            $this->db->like($type, $filtros['keyword']);
        }
        $this->db->stop_cache();
        $departamentos['data'] = $this->db->get('catalogos.departamentos_instituto A')->result_array();
        $this->db->reset_query();
        $this->db->select('count(*) total');
        $departamentos['total'] = $this->db->get('catalogos.departamentos_instituto A')->result_array()[0]['total'];
        $departamentos['per_page'] = $filtros['per_page'];
        $departamentos['current_row'] = $filtros['current_row'];
        $this->db->flush_cache();
        $this->db->reset_query();
        return $departamentos;
    }

    public function insert_departamento(&$parametros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $salida = array('status' => false, 'msg' => 'Se presentó un error al conectar con la base de datos');
        $insert = array(
            'nombre' => $parametros['nombre'],
            'clave_departamental' => $parametros['clave'],
            'id_unidad_instituto' => $parametros['unidad'],            
        );

        $this->db->insert('catalogos.departamentos_instituto', $insert);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
            $salida['status'] = true;
            $salida['msg'] = 'Departamento agregado con éxito';
        }

        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    public function get_unidades($filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        if (isset($filtros['per_page']) && $filtros['current_row'])
        {
            $this->db->limit($filtros['per_page'], $filtros['current_row'] * $filtros['per_page']);
        } else if (isset($filtros['per_page']))
        {
            $this->db->limit($filtros['per_page']);
        }

        if (isset($filtros['order']) && $filtros['order'] == 2)
        {
            $this->db->order_by('A.clave_unidad', 'DESC');
        } else
        {
            $this->db->order_by('A.clave_unidad', 'ASC');
        }

        $select = array(
            'A.id_unidad_instituto', 'A.nombre unidad', 'A.clave_unidad',
            'A.clave_presupuestal', 'A.id_delegacion', 'B.nombre delegacion',
            'A.id_tipo_unidad', 'C.nombre tipo', 'A.umae', 'A.latitud', 'A.longitud', 'A.anio'
        );
        $this->db->select($select);
        $this->db->start_cache();
        $this->db->join('catalogos.delegaciones B', 'B.id_delegacion = A.id_delegacion', 'inner');
        $this->db->join('catalogos.tipos_unidades C', 'C.id_tipo_unidad = A.id_tipo_unidad', 'left');
        $this->db->where('A.activa', true);
        if (isset($filtros['type']) && isset($filtros['keyword']) &&
                !empty($filtros['keyword']) && in_array($filtros['type'], array('clave_unidad', 'unidad', 'clave_presupuestal', 'delegacion', 'tipo')))
        {
            $type = $filtros['type'];
            switch ($filtros['type'])
            {
                case 'delegacion': $type = 'B.nombre';
                    break;
                case 'unidad': $type = 'A.nombre';
                    break;
                case 'tipo': $type = 'C.nombre';
            }

            $this->db->like($type, $filtros['keyword']);
        }
        $this->db->stop_cache();
        $unidades['data'] = $this->db->get('catalogos.unidades_instituto A')->result_array();
        $this->db->reset_query();
        $this->db->select('count(*) total');
        $unidades['total'] = $this->db->get('catalogos.unidades_instituto A')->result_array()[0]['total'];
        $unidades['per_page'] = $filtros['per_page'];
        $unidades['current_row'] = $filtros['current_row'];
        $this->db->flush_cache();
        $this->db->reset_query();
        return $unidades;
    }

}
