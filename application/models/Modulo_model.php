<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Modulo_model
 *
 * @author chrigarc
 */
class Modulo_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_modulos($id_modulo = 0, $agrupadas = true)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador', 'A.id_modulo_padre', 'A.visible', 'A.icon'
        );
        $this->db->select($select);
        $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
        $this->db->where('activo', true);
        if ($id_modulo > 0)
        {
            $this->db->where('A.id_modulo', $id_modulo);
        }
        $this->db->order_by('A.orden');
        $modulos = $this->db->get('sistema.modulos A')->result_array();
//pr($this->db->last_query());
        if ($id_modulo <= 0 && $agrupadas)
        {
            $modulos = $this->get_tree($modulos);
        }
        return $modulos;
    }

    public function get_modulos_usuario($id_usuario = 0)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador', 'A.id_modulo_padre', 'A.icon'
            ,'C.configurador configurador_modulo'
        );
        $this->db->select($select);
        $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
        $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo', 'inner');
        $this->db->join('sistema.grupos_usuarios D', 'D.id_grupo = C.id_grupo', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('C.activo', true);
        $this->db->where('D.activo', true);
        $this->db->where('C.id_grupo', $id_grupo);
        $this->db->order_by('A.orden');
        $modulos = $this->db->get('sistema.modulos A')->result_array();
    }

    public function get_modulos_grupo($id_grupo = 0, $todos = false, $agrupados = true)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_modulo', 'A.nombre', 'A.descripcion', 'A.url', 'A.visible', 'A.orden'
            , 'A.id_configurador', 'B.nombre configurador', 'C.id_grupo', 'A.id_modulo_padre', 'A.icon'
            ,'C.configurador configurador_modulo'
        );
        $this->db->select($select);
        if ($todos)
        {
            $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'left');
            $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo and C.id_grupo = ' . $id_grupo . ' AND C.activo', 'left');
            $this->db->where('A.activo', true);
        } else
        {
            $this->db->join('sistema.configuradores B', 'B.id_configurador = A.id_configurador', 'inner');
            $this->db->join('sistema.modulos_grupos C', ' C.id_modulo = A.id_modulo', 'inner');
            $this->db->where('A.activo', true);
            $this->db->where('C.activo', true);
            $this->db->where('C.id_grupo', $id_grupo);
        }
        $this->db->order_by('A.orden');
        $modulos = $this->db->get('sistema.modulos A')->result_array();
        if ($agrupados)
        {
            $modulos = $this->get_tree($modulos);
        }
        //pr($this->db->last_query());
        return $modulos;
    }

    public function get_configuradores()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'id_configurador', 'nombre', 'descripcion'
        );
        $this->db->select($select);
        $configuradores = $this->db->get('sistema.configuradores')->result_array();
        return $configuradores;
    }

    public function upsert_modulos_grupo($id_grupo = 0, $modulos = array(), $opciones = array())
    {
        $this->db->trans_begin();
        foreach ($modulos as $modulo)
        {
            $id_modulo = $modulo['id_modulo'];
            $configurador = (strlen($opciones['configurador' . $id_modulo])>5)?substr($opciones['configurador' . $id_modulo],0,5):$opciones['configurador' . $id_modulo];
            $activo = (isset($opciones['activo' . $id_modulo])) ? true : false;
            $this->upsert_modulo_grupo($id_grupo, $id_modulo, $configurador, $activo, $opciones);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
        }
    }

    private function upsert_modulo_grupo($id_grupo = 0, $id_modulo = 0, $configurador = '', $activo = false, $opciones = array())
    {
        //pr('[CH][modulo_model][upsert_modulo_grupo] id_grupo: '.$id_grupo.' id_modulo: '.$id_modulo.', conf: '.$configurador.', activo: '.($activo?'true':'false') );
        if ($id_grupo > 0 && $id_modulo > 0)
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('count(*) cantidad');
            $this->db->start_cache();
            $this->db->where('id_grupo', $id_grupo);
            $this->db->where('id_modulo', $id_modulo);
            $this->db->stop_cache();
            $existe = $this->db->get('sistema.modulos_grupos')->result_array()[0]['cantidad'] != 0;
            if ($existe)
            {
                $this->db->set('configurador', $configurador);
                $this->db->set('activo', $activo);
                $this->db->update('sistema.modulos_grupos');
            } else
            {
                $this->db->flush_cache();
                $insert = array(
                    'id_modulo' => $id_modulo,
                    'id_grupo' => $id_grupo,
                    'configurador' => $configurador,
                    'activo' => $activo
                );
                $this->db->insert('sistema.modulos_grupos', $insert);
            }
        }
    }

    public function update($id_modulo = 0, &$datos = array())
    {
        $status = false;
        if ($id_modulo > 0)
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->set('nombre', $datos['nombre']);
            $this->db->set('url', $datos['url']);
            $this->db->set('id_modulo_padre', (empty($datos['padre']))?null:$datos['padre']);
            $this->db->set('orden', $datos['orden']);
            $this->db->set('id_configurador', $datos['tipo']);
            $this->db->set('visible', $datos['visible']);
            $this->db->set('icon',(isset($datos['icono']))?$datos['icono']:null);
            
            $this->db->where('id_modulo', $id_modulo);
            $this->db->update('sistema.modulos');
            $status = true;
        }
        return $status;
    }

    public function insert(&$datos = array())
    {
        $status = false;
        $insert = array(
            'nombre' => $datos['nombre'],
            'url' => $datos['url'],
            'id_modulo_padre' => $datos['padre'],
            'orden' => ($datos['orden']!=''?$datos['orden']:1),
            'id_configurador' => $datos['tipo'],
            'visible' => $datos['visible'], 
            'icon' => (isset($datos['icono'])?$datos['icono']:NULL)
        );
        $this->db->insert('sistema.modulos', $insert);
        return $status;
    }

    private function get_tree($modulos = array())
    {
        $niveles_tree = 10;
        $pre_tree = [];
        for ($i = 0; $i < $niveles_tree + 1; $i++)
        {
            foreach ($modulos as $row)
            {
                if (!isset($pre_tree[$row['id_modulo']]))
                {
                    $pre_tree[$row['id_modulo']] = $row;
                }
                //pr($pre_tree[$row['id_modulo']]);
                if (isset($pre_tree[$row['id_modulo_padre']]) /* && !isset($pre_menu[$row['id_menu_padre']]['childs'][$row['id_menu']]) */)
                {
//                    pr($row['id_modulo']['id_modulo_padre']);
                    $pre_tree[$row['id_modulo_padre']]['childs'][$row['id_modulo']] = $pre_tree[$row['id_modulo']];
                } else
                {
                    //pr($row['id_modulo']['id_modulo_padre']);
                }
            }
        }
        $tree = [];
//        pr($pre_tree);

        foreach ($pre_tree as $row)
        {
            if (empty($row['id_modulo_padre']) && !isset($tree[$row['id_modulo']]))
            {
                $tree[$row['id_modulo']] = $row;
            }
        }
        //pr($tree);
        return $tree;
    }

    function check_acceso($url = null, $id_usuario = 0)
    {
        $salida = null;
        if ($id_usuario > 0 && $url != null && $url != "")
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $select = array(
                'A.id_modulo', 'A.nombre  modulo', 'A.url', 'B.configurador'
            );
            $this->db->select($select);
            $this->db->join('sistema.modulos_grupos B','B.id_modulo = A.id_modulo', 'inner');
            $this->db->join('sistema.grupos_usuarios C','C.id_grupo = B.id_grupo', 'inner');
            $this->db->where('C.id_usuario', $id_usuario);
            $this->db->where('A.activo', true);
            $this->db->where('B.activo', true);
            $this->db->where('C.activo', true);
            $url_d = $url.'/';
            $this->db->where("(A.url = '{$url}' or A.url = '{$url_d}')");            
            $result_set = $this->db->get('sistema.modulos A');
//            pr($this->db->last_query());
            if($result_set){
                $salida = $result_set->result_array();
            }
        }
        return $salida;
    }

}
