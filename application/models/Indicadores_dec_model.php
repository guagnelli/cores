<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Indicadores_dec_model extends CI_Model
{
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
    //$this->config->load('general');
    $this->load->database();
  }

  /*
  * @author AleSpock
  * @return
  */
  public function nuevo_dec($id_indicador,$cve_presupuestal,$numerador,$denominador,$trimestre,$porcentaje_aprobados,$anio, $id_programa_proyecto) {
    $this->db->flush_cache();
    $this->db->reset_query();
    $data = array(
      // 'id_indicador' => $id_indicador,
      'cve_presupuestal' => $cve_presupuestal,
      'numerador' => $numerador,
      'denominador' => $denominador,
      'trimestre' => $trimestre,
      'porcentaje_aprobados' => $porcentaje_aprobados,
      'anio' => $anio,
      'id_programa_proyecto' => $id_programa_proyecto
    );
    $this->db->insert('dec.h_indicadores',$data);
    return $data;

  }

  public function lista_indicadores_dec() {
    $this->db->flush_cache();
    $this->db->reset_query();
    $this->db->select(array('id_indicador', 'cve_presupuestal', 'numerador', 'denominador', 'trimestre', 'porcentaje_aprobados', 'anio', 'id_programa_proyecto'));
    $indicadores_dec = $this->db->get('dec.h_indicadores')->result_array();
    return $indicadores_dec;
    // $this->db->select(array('id_indicador', 'cve_presupuestal', 'numerador', 'denominador', 'trimestre', 'porcentaje_aprobados', 'anio', 'id_programa_proyecto'));
    // $this->db->from('');
  }

  public function update_registro_dec($id_indicador,$cve_presupuestal,$numerador,$denominador,$trimestre,$porcentaje_aprobados,$anio, $id_programa_proyecto) {
    $this->db->flush_cache();
    $this->db->reset_query();
    $data = array(

      'cve_presupuestal' => $cve_presupuestal,
      'numerador' => $numerador,
      'denominador' => $denominador,
      'trimestre' => $trimestre,
      'porcentaje_aprobados' => $porcentaje_aprobados,
      'anio' => $anio,
      'id_programa_proyecto' => $id_programa_proyecto
    );
    $this->db->where('id_indicador',$id_indicador);
    $this->db->update('dec.h_indicadores',$data);
    if ($this->db->trans_status() === FALSE){
      return -1;
    } else{
      return 0;
    }
  }

  public function delete($id_indicador) {
    $this->db->flush_cache();
    $this->db->reset_query();
    $this->db->where('id_indicador',$id_indicador);
    return $this->db->delete('dec.h_indicadores');

  }


  public function insert_reg($datos) {
    $salida = false;
    $this->db->flush_cache();
    $this->db->reset_query();
    $this->db->trans_begin();

    $this->db->insert('dec.h_indicadores', $datos);

    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();
    } else
    {
      $this->db->trans_commit();
      $salida =  $this->db->insert_id();
    }
    $this->db->flush_cache();
    $this->db->reset_query();
    return $salida;
  }

  // public function cat_programa_proyecto() {
  //   $this->db->flush_cache();
  //   $this->db->reset_query();
  //   $this->db->select(array('id_programa_proyecto', 'nombre'));
  //   $catalogo_prog_proy = $this->db->get('catalogos.programas_proyecto')->result_array();
  //   return $catalogo_prog_proy;
  //
  // }
  public function get_catalogos() {
    $this->db->flush_cache();
    $this->db->reset_query();
    $resutado = $this->db->get('catalogos.programas_proyecto');
    return $resutado->result_array();
  }
}
