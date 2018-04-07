<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Menu_model
 *
 * @author chrigarc
 */
class Menu_model extends CI_Model
{

    //put your code here
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_menu_usuario($id_usuario = 0)
    {
        $niveles_menu = 10;
       
        $select = array(
            'A.id_modulo id_menu', 'A.nombre label', 'A.url enlace', 'A.id_modulo_padre id_menu_padre', 'A.icon', 
            'A.orden', 'D.orden'
        );

       // $this->db->order_by('A.orden', 'asc');
        $this->db->order_by('"D".orden asc nulls first, "A".orden asc', null, false);
        $this->db->select($select);
        $this->db->from('sistema.modulos A');
        $this->db->join('sistema.modulos D','D.id_modulo = "A".id_modulo_padre', 'left');
        $this->db->join('sistema.modulos_grupos B','B.id_modulo = "A".id_modulo', 'inner');
        $this->db->join('sistema.grupos_usuarios C','C.id_grupo = B.id_grupo', 'inner');
        $this->db->where('A.activo', true);
        $this->db->where('B.activo', true);
        $this->db->where('C.activo', true);
        $this->db->where('A.id_configurador', 1); //elemento menu
        $this->db->where('C.id_usuario', $id_usuario);        

        $query = $this->db->get();
        $result = $query->result_array();
//        pr($this->db->last_query());
        $query->free_result();
        //procesamos el arreglo para limpiarlo
        $pre_menu = [];
        for ($i = 0; $i < $niveles_menu + 1; $i++)
        {
            foreach ($result as $row)
            {
                if (!isset($pre_menu[$row['id_menu']]))
                {
                    $pre_menu[$row['id_menu']]['id_menu_padre'] = $row['id_menu_padre'];
                    $pre_menu[$row['id_menu']]['titulo'] = $row['label'];
                    $pre_menu[$row['id_menu']]['id_menu'] = $row['id_menu'];
                    $pre_menu[$row['id_menu']]['link'] = $row['enlace'];
                    $pre_menu[$row['id_menu']]['icon'] = $row['icon'];
                    $pre_menu[$row['id_menu']]['orden'] = $row['orden'];
                }
                if (isset($pre_menu[$row['id_menu_padre']]) /* && !isset($pre_menu[$row['id_menu_padre']]['childs'][$row['id_menu']]) */)
                {
                    $pre_menu[$row['id_menu_padre']]['childs'][$row['id_menu']] = $pre_menu[$row['id_menu']];
                }
            }
        }
        $menu = [];


        foreach ($pre_menu as $row)
        {
            if (empty($row['id_menu_padre']) && !isset($menu[$row['id_menu']]))
            {
                $menu[$row['id_menu']] = $row;
            }
        }
//        pr($pre_menu);
        return $menu;
    }

}
