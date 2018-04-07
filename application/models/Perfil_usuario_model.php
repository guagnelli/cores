<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perfil_usuario_model
 *
 * @author chrigarc
 */
class Perfil_usuario_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function update($usuario = null, $datos = null)
    {
        $status = false;
        $this->db->flush_cache();
        $this->db->reset_query();
        try
        {
            $this->db->set('email', $datos['email']);
            $this->db->where('id_usuario', $usuario['id_usuario']);
            $this->db->update('sistema.usuarios');
            $status = true;
        } catch (Exception $ex)
        {
            $status = FALSE;
        }
        return $status;
    }

    public function update_password($usuario = null, $datos = null)
    {
        $status = false;
        $this->db->flush_cache();
        $this->db->reset_query();
        try
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('token');
            $this->db->where('id_usuario', $usuario['id_usuario']);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($datos);
            //pr($this->db->last_query());
            if ($resultado)
            {
                $this->load->library('seguridad');
                $token = $resultado[0]['token'];
                $this->db->reset_query();
                $password = $this->seguridad->encrypt_sha512($token . $datos['pass'] . $token);
                $this->db->set('password', $password);
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->update('sistema.usuarios');
//                pr($this->db->last_query());
                $status = true;
            } else
            {
                // pr('usuario no localizado');
            }
        } catch (Exception $ex)
        {
            $status = FALSE;
        }
        return $status;
    }

}
