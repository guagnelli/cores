<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la información de la DEC
 * @version 	: 1.0.0
 * @author      : Cheko
 * */
class Dec extends MY_Controller
{

  function __construct()
  {
      parent::__construct();
      $this->load->helper(array('form', 'general'));
      $this->load->library('form_complete');
      $this->load->library('Configuracion_grupos');
      $this->load->library('Catalogo_listado');
      $this->lang->load('interface'); //Cargar archivo de lenguaje
      $this->load->model('Informacion_general_model', 'inf_gen_model');
      $this->configuracion_grupos->set_periodo_actual();
      //catalogo
      $this->load->model('Catalogo_model', 'catalogo');
      //dec
      $this->load->model('Dec_model','dec');
  }

  /**
   * Función que muestra la información general de la dec por
   * UMAE o delegacional
   * @author Cheko
   * @param String $nivel nivel que se quiere mostrar (por_delegacion o por_umae)
   *
   */
  public function informacion_general($nivel=NULL)
  {
      switch ($nivel) {
          case 'por_delegacion':
              $this->muestra_por_delegacion();
              break;
          case 'por_umae':
              $this->muestra_por_UMAE();
              break;
          default:
              $this->muestra_por_delegacion();
              break;
      }
  }

  /**
   * Función que muestra la vista por delegacion
   * @author Cheko
   *
   */
  private function muestra_por_delegacion()
  {
      $output = array();
      $output['lenguaje'] = $this->lang->line('index');
      $output['usuario'] = $this->session->userdata('usuario');

      $grupo_actual = $this->configuracion_grupos->obtener_grupo_actual();
      $condicion = [];
      switch ($grupo_actual) {
          case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES: case En_grupos::N2_DGU:
              $conditions = array('conditions'=>"uni.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
              $output['usuario']['tactico'] = true;
              $output['usuario']['central'] = false;
              $output['usuario']['estrategico'] = false;
              break;
          case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N2_CPEI: case En_grupos::N2_CAME: case En_grupos::N3_JSPM:
              $conditions = array('conditions'=>"uni.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
              $output['usuario']['estrategico'] = true;
              $output['usuario']['central'] = false;
              $output['usuario']['tactico'] = false;
              $output['regiones'] = dropdown_options(array(0=>array('id_region'=>$output['usuario']['id_region'],'region'=>'defaul')), 'id_region', 'region');
              $delegaciones = $this->obtener_delegaciones($output['usuario']['id_region']);
              $output['delegaciones'] = dropdown_options($delegaciones, 'id_delegacion', 'delegacion');
              break;
          case En_grupos::NIVEL_CENTRAL: case En_grupos::ADMIN: case En_grupos::SUPERADMIN:
              if (is_nivel_central($output['usuario']['grupos']))
              {
                  $output['usuario']['central'] = true;
                  $output['usuario']['estrategico'] = false;
                  $output['usuario']['tactico'] = false;
                  if($this->catalogo->obtener_regiones()['success'])
                  {
                      $regiones = $this->catalogo->obtener_regiones()['datos'];
                      //pr($regiones);
                      $output['regiones'] = dropdown_options($regiones, 'id_region', 'region');
                  }
              }
              break;
      }

      //pr($output['usuario']);

      //pr($output);
      //$output['graficas'] = $this->ranking->get_tipos_reportes();

      if ($this->input->post())
      {

          $usuario = $this->session->userdata('usuario');
          $region = $this->catalogo->obtener_region($this->input->post('region'));
          $tipoUnidad = $this->catalogo->obtener_tipo_unidad($this->input->post('tipos_unidades'));
          $delegacion = $this->catalogo->obtener_delegacion($this->input->post('delegacion'));
          $output['filtros'] = $this->input->post();
          $output['region'] = count($region['datos']) > 0 ? $region['datos']['nombre'] : "Todos";
          $output['delegacion'] = count($delegacion['datos']) > 0 ? $delegacion['datos']['nombre']: "Todos";
          $output['tipo_unidad'] = count($tipoUnidad['datos']) > 0 ? $tipoUnidad['datos']['nombre'] : "Todos";

          $output['datos_totales_unidades'] = $this->dec->reporte_total_unidades($usuario,'delegacion',$this->input->post());
          $output['datos_totales_unidades_con_programa'] = $this->dec->reporte_total_unidades_programa($usuario,'delegacion',$this->input->post());
          if(count($output['datos_totales_unidades']['datos']) > 0 && count($output['datos_totales_unidades_con_programa']['datos'])){
              $t_unidades = floatval($output['datos_totales_unidades']['datos'][0]['total_unidades']);
              $tu_programa = floatval($output['datos_totales_unidades_con_programa']['datos'][0]['total_unidades']);
              if($tu_programa < 1){
                  $output['datos_totales_porcentaje'] = 0.0;
              }else{
                  $output['datos_totales_porcentaje'] = round(($tu_programa * 100.0)/$t_unidades,2);
              }

          }else{
            if(count($output['datos_totales_unidades_con_programa']['datos']) == 0){
              $output['datos_totales_porcentaje'] = 0.0;
            }
          }


          //para tabular
          $output['datos'] = $this->dec->reporte($usuario,'delegacion',$this->input->post());
          //pr($output['datos']);
          $output['reporte'] = 'delegacion';
          $output['tabla'] = $this->load->view('dec/informacion_general/tabla', $output, true);
      }
      $this->template->setTitle($output['lenguaje']['title']);
      $this->template->setSubTitle(render_subtitle($output['lenguaje']['subtitle'], 'Información general por delegación'));
      $this->template->setDescripcion($this->mostrar_datos_generales());
      $main_content = $this->load->view('dec/informacion_general/por_delegacion', $output, true);
      $this->template->setMainContent($main_content);
      $this->template->getTemplate();
  }

  /**
   * Funcion que realiza el resuelve la peticion de get tipos de unidades
   * para obtener todos los tipos de unidades por nivel
   * @author Cheko
   * @param string $nivel Nivel de atencion para obtener los tipos de unidades
   */
  public function tipos_unidades($nivel)
  {
      $tiposUnidades = $this->catalogo->obtener_tipo_unidades($nivel);
      $this->agregar_cabeceras();
      echo json_encode($tiposUnidades);
  }

  /**
   * Funcion que realiza el resuelve la peticion de get tipos de unidades
   * para obtener todos los tipos de unidades por nivel
   * @author Cheko
   * @param string $nivel Nivel de atencion para obtener los tipos de unidades
   */
  public function obtener_delegaciones($region)
  {
      $tiposUnidades = $this->catalogo->obtener_delegaciones($region);
      $this->agregar_cabeceras();
      echo json_encode($tiposUnidades);
  }

  /**
   * Funcion agregar las cabeceras a la peticion
   * @author Cheko
   *
   */
  private function agregar_cabeceras(){
      header('Content-Type: application/json; charset=utf-8;');
  }

  /**
   * Función que muestra la vista por UMAE
   * @author Cheko
   *
   */
  private function muestra_por_UMAE()
  {
    $output = array();
    $output['lenguaje'] = $this->lang->line('index');
    $output['usuario'] = $this->session->userdata('usuario');
//        pr($output['usuario']);
    if (is_nivel_central($output['usuario']['grupos']))
    {
        $output['usuario']['central'] = true;
    }
    if($this->catalogo->obtener_tipo_unidades('3')['success'])
    {
        $tipos_unidades = $this->catalogo->obtener_tipo_unidades('3')['datos'];
        $output['tipos_unidades'] = dropdown_options($tipos_unidades, 'id_tipo_unidad', 'nombre');
    }

    $grupo_actual = $this->configuracion_grupos->obtener_grupo_actual();
    $condicion = [];
    switch ($grupo_actual) {
        case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES: case En_grupos::N2_DGU:
            $conditions = array('conditions'=>"uni.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
            $output['usuario']['tactico'] = true;
            $output['usuario']['central'] = false;
            $output['usuario']['estrategico'] = false;
            break;
        case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N2_CPEI: case En_grupos::N2_CAME: case En_grupos::N3_JSPM:
            $conditions = array('conditions'=>"uni.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
            $output['usuario']['estrategico'] = true;
            $output['usuario']['central'] = false;
            $output['usuario']['tactico'] = false;
            $output['regiones'] = dropdown_options(array(0=>array('id_region'=>$output['usuario']['id_region'],'region'=>'defaul')), 'id_region', 'region');
            $delegaciones = $this->obtener_delegaciones($output['usuario']['id_region']);
            $output['delegaciones'] = dropdown_options($delegaciones, 'id_delegacion', 'delegacion');
            break;
        case En_grupos::NIVEL_CENTRAL: case En_grupos::ADMIN: case En_grupos::SUPERADMIN:
            if (is_nivel_central($output['usuario']['grupos']))
            {
                $output['usuario']['central'] = true;
                $output['usuario']['estrategico'] = false;
                $output['usuario']['tactico'] = false;
                if($this->catalogo->obtener_regiones()['success'])
                {
                    $regiones = $this->catalogo->obtener_regiones()['datos'];
                    //pr($regiones);
                    $output['regiones'] = dropdown_options($regiones, 'id_region', 'region');
                }
            }
            break;
    }

    if ($this->input->post())
    {
        $usuario = $this->session->userdata('usuario');

        $tipoUnidad = $this->catalogo->obtener_tipo_unidad($this->input->post('tipos_unidades'));
        $output['filtros'] = $this->input->post();
        $output['tipo_unidad'] = count($tipoUnidad['datos']) > 0 ? $tipoUnidad['datos']['nombre'] : "Todos";

        $output['datos_totales_unidades'] = $this->dec->reporte_total_unidades($usuario,'umae',$this->input->post());
        $output['datos_totales_unidades_con_programa'] = $this->dec->reporte_total_unidades_programa($usuario,'umae',$this->input->post());
        //pr($output['datos_totales_unidades']);
        //pr($output['datos_totales_unidades_con_programa']);
        if(count($output['datos_totales_unidades']['datos']) > 0 && count($output['datos_totales_unidades_con_programa']['datos'])){
            $t_unidades = floatval($output['datos_totales_unidades']['datos'][0]['total_unidades']);
            $tu_programa = floatval($output['datos_totales_unidades_con_programa']['datos'][0]['total_unidades']);
            if($tu_programa < 1){
                $output['datos_totales_porcentaje'] = 0.0;
            }else{
                $output['datos_totales_porcentaje'] = round(($tu_programa * 100.0)/$t_unidades,2);
            }
        }else{
          if(count($output['datos_totales_unidades_con_programa']['datos']) == 0){
            $output['datos_totales_porcentaje'] = 0.0;
          }
        }

        //para tabular
        $output['datos'] = $this->dec->reporte($usuario,'umae',$this->input->post());
        //pr($output['datos']);
        $output['reporte'] = 'umae';
        $output['tabla'] = $this->load->view('dec/informacion_general/tabla', $output, true);
    }
    $this->template->setTitle($output['lenguaje']['title']);
    $this->template->setSubTitle(render_subtitle($output['lenguaje']['subtitle'], 'Informacion general por UMAE')); //cambiar titulo
    $this->template->setDescripcion($this->mostrar_datos_generales());
    $main_content = $this->load->view('dec/informacion_general/por_unidad', $output, true);
    $this->template->setMainContent($main_content);
    $this->template->getTemplate();
  }
}
