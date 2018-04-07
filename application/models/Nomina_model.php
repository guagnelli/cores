<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Modelo con la información de la nomina
 *
 * @author Mr. Guag
 */
class Nomina_model extends MY_Model{

    function __construct(){
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    function get_region($where = null){

      if(!is_null($where)){
          $this->db->where(array($where));
      }
      $query = $this->db->get('catalogos.regiones');
      $resultado = $query->result_array();
      //pr($this->db->last_query());
      $query->free_result(); //Libera la memoria
      return $resultado;
    }

    function get_delegacion($id = null){

    }

    function get_unidad($id = null){

    }

    function get_tipo_perfil($where = null){
      if(!is_null($where)){
          $this->db->where(array($where));
      }
      $this->db->select("sc.id_subcategoria,sc.nombre as tipo_perfil");
      $this->db->order_by("sc.order", "asc");
      $query = $this->db->get('catalogos.subcategorias sc');
      $resultado = $query->result_array();
      //pr($this->db->last_query());
      $query->free_result(); //Libera la memoria
      return $resultado;
      //ALTER TABLE catalogos.subcategorias ADD "order" numeric(1) NULL ;
    }

    function get_perfil($where = null){
      if(!is_null($where)){
          $this->db->where(array($where));
      }
      $this->db->where('gc.activa=true');
      $this->db->select("sc.id_subcategoria,sc.nombre as tipo_perfil, gc.id_grupo_categoria,gc.nombre as perfil, gc.descripcion");
      $this->db->join("catalogos.subcategorias sc","gc.id_subcategoria=sc.id_subcategoria");
      $this->db->order_by("sc.id_subcategoria asc, gc.order asc");
      $query = $this->db->get('catalogos.grupos_categorias gc');
      $resultado = $query->result_array();
      //pr($this->db->last_query());
      $query->free_result(); //Libera la memoria
      return $resultado;
    }

    function get_dropdown_perfil(){
      
    }
}
