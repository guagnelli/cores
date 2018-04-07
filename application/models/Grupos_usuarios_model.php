<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Grupos_usuarios_model
 *
 * @author chrigarc
 */
class Grupos_usuarios_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_grupos()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $grupos = [];
        $select = array(
            'id_grupo', 'nombre', 'descripcion'
        );
        $this->db->select($select);
        $this->db->where('activo', true);
        $this->db->order_by('orden', 'asc');
        $grupos = $this->db->get('sistema.grupos')->result_array();
        return $grupos;
    }

    public function get_grupos_usuario($id_usuario = 0, $todos = false)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_grupo', 'A.nombre', 'A.descripcion', 'B.id_usuario'
        );
        $this->db->select($select);
        if (!$todos)
        {
            $this->db->from('sistema.grupos A');
            $this->db->join('sistema.grupos_usuarios B', 'B.id_grupo = A.id_grupo', 'inner');
            $this->db->where('A.activo', true);
            $this->db->where('B.activo', true);
            $this->db->where('B.id_usuario', $id_usuario);
        } else
        {
            $this->db->from('sistema.grupos A');
            $this->db->join('sistema.grupos_usuarios B', 'B.id_grupo = A.id_grupo and B.id_usuario = ' . $id_usuario . ' and B.activo', 'left');
            $this->db->where('A.activo', true);
        }
        $this->db->order_by('A.nombre');
        $grupos = $this->db->get()->result_array();
//        pr($this->db->last_query());
        return $grupos;
    }

    public function upsert($id_usuario, $opciones)
    {
        $grupos = $this->get_grupos();
        $this->db->trans_begin();
        foreach ($grupos as $grupo)
        {
            $id_grupo = $grupo['id_grupo'];
            $activo = (isset($opciones['activo' . $id_grupo])) ? true : false;
            $this->upsert_usuario_grupo($id_usuario, $id_grupo, $activo);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $status = false;
        } else
        {
            $this->db->trans_commit();
            $status = true;
        }
        return $status;
    }

    private function upsert_usuario_grupo($id_usuario, $id_grupo, $activo)
    {
        if ($id_grupo > 0 && $id_usuario > 0)
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('count(*) cantidad');
            $this->db->start_cache();
            $this->db->where('id_grupo', $id_grupo);
            $this->db->where('id_usuario', $id_usuario);
            $this->db->stop_cache();
            $existe = $this->db->get('sistema.grupos_usuarios')->result_array()[0]['cantidad'] != 0;
            if ($existe)
            {
                $this->db->set('activo', $activo);
                $this->db->update('sistema.grupos_usuarios');
            } else
            {
                $this->db->flush_cache();
                $insert = array(
                    'id_usuario' => $id_usuario,
                    'id_grupo' => $id_grupo,
                    'activo' => $activo
                );
                $this->db->insert('sistema.grupos_usuarios', $insert);
            }
        }
    }

}
