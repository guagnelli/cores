<?php
/*
* @author AleSpock
* @return
*/
defined('BASEPATH') OR exit('No direct script access allowed');


class Indicadores_dec extends MY_Controller
{
  // const LISTA = 'lista', NUEVA = 'agregar', EDITAR = 'editar', CREAR = 'crear', LEER= 'leer', ACTUALIZAR = 'actualizar', ELIMINAR = 'eliminar';

  function __construct()
  {
    parent::__construct();
    //$this->load->model('User_model', 'usr');
    //$this->load->model('Usuario_model', 'usuario');
      //$view = $this->load->view('administracion/indicadores_dec.php', true);
      $this->load->model('Indicadores_dec_model', 'indicadores_dec');
     // $this->lang->load('session', 'spanish');

  }


  public function index()
    {

      //$view = $this->load->view('administracion/indicadores_dec');
      $output = [];
      $view = $this->load->view('administracion/indicadores_dec.php', $output, true);
      //  $data['registros_dec'] = $this->indicadores_dec->lista_indicadores_dec();
      // $this->load->view('administracion/indicadores_dec.php',$data);
      $this->template->setDescripcion($this->mostrar_datos_generales());
      $this->template->setMainContent($view);
      $this->template->setSubTitle('Indicadores DEC');
      $this->template->getTemplate();


    }

  public function mostrar_dec() {
    $array_data = $this->indicadores_dec->lista_indicadores_dec();
    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($array_data);

  }

  public function eliminar_dec()
    {

        //comprobamos si es una petición ajax y existe la variable post id
        if($this->input->is_ajax_request() && $this->input->post('id_indicador'))
        {

        	$id_indicador = $this->input->post('id_indicador');

         $this->indicadores_dec->delete($id_indicador);

        }

    }

  public function actualizar_dec() {
    // header('Content-Type: application/json; charset=utf-8;');
    // $input_data = $this->input->post(null,TRUE);
    // echo json_encode($input_data);
    // exit;
    $id_indicador = $this->input->post('id_indicador');
    // pr ($id_indicador);
    $cve_presupuestal = $this->input->post('cve_presupuestal');
    $numerador = $this->input->post('numerador');
    $denominador = $this->input->post('denominador');
    $trimestre = $this->input->post('trimestre');
    $porcentaje_aprobados = $this->input->post('porcentaje_aprobados');
    $anio = $this->input->post('anio');
    $id_programa_proyecto = $this->input->post('id_programa_proyecto');
    $actualizar = $this->indicadores_dec->update_registro_dec($id_indicador,$cve_presupuestal,$numerador,$denominador,$trimestre,$porcentaje_aprobados,$anio, $id_programa_proyecto);
    //si la actualización ha sido correcta creamos una sesión flashdata para decirlo
    // if($actualizar)
    // {
    //   $this->session->set_flashdata('actualizado', 'El mensaje se actualizó correctamente');
    //   //redirect('', 'refresh');
    // }
    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($actualizar);


}
  public function nuevo_dec(){
    // $id_indicador = $this->input->post('id_indicador');
    $cve_presupuestal = $this->input->post('cve_presupuestal');
    $numerador = $this->input->post('numerador');
    $denominador = $this->input->post('denominador');
    $trimestre = $this->input->post('trimestre');
    $porcentaje_aprobados = $this->input->post('porcentaje_aprobados');
    $anio = $this->input->post('anio');
    $id_programa_proyecto = $this->input->post('id_programa_proyecto');
    $nuevo_dec = $this->indicadores_dec->nuevo_dec($cve_presupuestal,$numerador,$denominador,$trimestre,$porcentaje_aprobados,$anio,$id_programa_proyecto);
    //si la actualización ha sido correcta creamos una sesión flashdata para decirlo
    // if($actualizar)
    // {
    //   $this->session->set_flashdata('actualizado', 'El mensaje se actualizó correctamente');
    //   //redirect('', 'refresh');
    // }

    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($nuevo_dec);

  }

  public function mostrar_catalogos_proy() {
    $data ['datos_cat'] = $this->indicadores_dec->get_catalogos();
    header('Content-Type: application/json; charset=utf-8;');
    echo json_encode($data);
    // $catalogos = $this->indicadores_dec->get_catalogos('catalogos.programas_proyecto', $c_query);
    //           array_unshift($catalogos,  array('id_programa_proyecto' => '', 'nombre' => 'Seleccionar'));
    //           $output['var_js'] = array(
    //               array(
    //                   "nombre" => 'json_catalogos',
    //                   'value' => json_encode($catalogos)
    //               )
    //           );
  }

}
