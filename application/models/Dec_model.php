<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para generar los reportes de la dec
 *
 * @author Cheko
 */
class Dec_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    /**
     * Función que obtiene para obtener el reporte
     * dependiendo de la peticion
     * @author Cheko
     * @param array $usuario datos del usuario para generar el reporte
     * @param strig $nivel si es por delegacion o por unidad
     * @param array $peticion el arreglo de datos
     * @return array $estado estado de la repuesta al obtener
     * el reporte
     *
     */
    public function reporte($usuario, $nivel ,$peticion)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $estado = array('success' => false, 'message' => 'Peticion incorrecta', 'datos'=>[]);
        if(count($peticion) < 0)
        {
            return $estado;
        }
        else
        {
            switch ($nivel)
            {
              //NP si asitantes reales es 0 y no tiene programa educativo
              //query join left
                case 'delegacion':
                    if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                        	sum(hi.numerador) as numerador,
                        	sum(hi.denominador) as denominador,
                        round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                        from catalogos.unidades_instituto UI
                        	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                        	inner join catalogos.regiones R on R.id_region = D.id_region
                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                        where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']}
                        group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                        order by UI.nombre");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }
                    else
                    {
                      if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos")
                      {
                          $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                          	sum(hi.numerador) as numerador,
                          	sum(hi.denominador) as denominador,
                          round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                          from catalogos.unidades_instituto UI
                          	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                          	inner join catalogos.regiones R on R.id_region = D.id_region
                          	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                          	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                          where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                          group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                          order by UI.nombre");
                          $reporte = $resultado->result_array();
                          $estado['success'] = true;
                          $estado['message'] = "Se obtuvo el reporte con exito";
                          $estado['datos'] = $reporte;
                      }
                      else
                      {
                          if($peticion['region'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                          {
                              $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                              	sum(hi.numerador) as numerador,
                              	sum(hi.denominador) as denominador,
                              round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                              from catalogos.unidades_instituto UI
                              	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                              	inner join catalogos.regiones R on R.id_region = D.id_region
                              	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                              	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                              where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_delegacion = {$peticion['delegacion']}
                              group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                              order by UI.nombre");
                              $reporte = $resultado->result_array();
                              $estado['success'] = true;
                              $estado['message'] = "Se obtuvo el reporte con exito";
                              $estado['datos'] = $reporte;

                          }
                          else
                          {
                              if($peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                              {
                                  $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                  	sum(hi.numerador) as numerador,
                                  	sum(hi.denominador) as denominador,
                                  round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                  from catalogos.unidades_instituto UI
                                  	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                  	inner join catalogos.regiones R on R.id_region = D.id_region
                                  	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                  	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                  where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and and R.id_region = {$peticion['region']}
                                  group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                  order by UI.nombre");
                                  $reporte = $resultado->result_array();
                                  $estado['success'] = true;
                                  $estado['message'] = "Se obtuvo el reporte con exito";
                                  $estado['datos'] = $reporte;
                              }
                              else
                              {
                                if($peticion['delegacion'] == "Todos")
                                {
                                    $resultado = $this->db->query("
                                    select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                    	sum(hi.numerador) as numerador,
                                    	sum(hi.denominador) as denominador,
                                    round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                    from catalogos.unidades_instituto UI
                                    	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                    	inner join catalogos.regiones R on R.id_region = D.id_region
                                    	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                    	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                    where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and R.id_region = {$peticion['region']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                                    group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                    order by UI.nombre");
                                    $reporte = $resultado->result_array();
                                    $estado['success'] = true;
                                    $estado['message'] = "Se obtuvo el reporte con exito";
                                    $estado['datos'] = $reporte;
                                }
                                else
                                {
                                    if($peticion['tipos_unidades'] == "Todos")
                                    {
                                        $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                        	sum(hi.numerador) as numerador,
                                        	sum(hi.denominador) as denominador,
                                        round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                        from catalogos.unidades_instituto UI
                                        	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                        	inner join catalogos.regiones R on R.id_region = D.id_region
                                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                        where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and R.id_region = {$peticion['region']} and D.id_delegacion = {$peticion['delegacion']}
                                        group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                        order by UI.nombre");
                                        $reporte = $resultado->result_array();
                                        $estado['success'] = true;
                                        $estado['message'] = "Se obtuvo el reporte con exito";
                                        $estado['datos'] = $reporte;

                                    }
                                    else
                                    {
                                        if($peticion['region'] == "Todos")
                                        {
                                            $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                            	sum(hi.numerador) as numerador,
                                            	sum(hi.denominador) as denominador,
                                            round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                            from catalogos.unidades_instituto UI
                                            	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                            	inner join catalogos.regiones R on R.id_region = D.id_region
                                            	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                            	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                            where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and D.id_delegacion = {$peticion['delegacion']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                                            group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                            order by UI.nombre");
                                            $reporte = $resultado->result_array();
                                            $estado['success'] = true;
                                            $estado['message'] = "Se obtuvo el reporte con exito";
                                            $estado['datos'] = $reporte;
                                        }
                                        else
                                        {
                                            $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                                            	sum(hi.numerador) as numerador,
                                            	sum(hi.denominador) as denominador,
                                            round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                                            from catalogos.unidades_instituto UI
                                            	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                                            	inner join catalogos.regiones R on R.id_region = D.id_region
                                            	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                            	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                            where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and D.id_delegacion = {$peticion['delegacion']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']} and R.id_region = {$peticion['region']}
                                            group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                                            order by UI.nombre");
                                            $reporte = $resultado->result_array();
                                            $estado['success'] = true;
                                            $estado['message'] = "Se obtuvo el reporte con exito";
                                            $estado['datos'] = $reporte;
                                        }
                                    }
                                }

                              }
                          }
                      }

                    }
                    return $estado;
                    break;
                case 'umae':
                    //pr($peticion['tipos_unidades']);
                    if($peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                        	sum(hi.numerador) as numerador,
                        	sum(hi.denominador) as denominador,
                        round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                        from catalogos.unidades_instituto UI
                        	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                        	inner join catalogos.regiones R on R.id_region = D.id_region
                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                        where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = 3
                        group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                        order by UI.nombre");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;

                    }else{
                        $resultado = $this->db->query("select pp.nombre programa_educativo, D.clave_delegacional, D.nombre delegacion, UI.nombre unidad, pp.descripcion,
                        	sum(hi.numerador) as numerador,
                        	sum(hi.denominador) as denominador,
                        round((sum(hi.numerador::numeric) / nullif(sum(hi.denominador::numeric),0))*100,2) porcentaje
                        from catalogos.unidades_instituto UI
                        	inner join catalogos.delegaciones D on D.id_delegacion = UI.id_delegacion
                        	inner join catalogos.regiones R on R.id_region = D.id_region
                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                        where hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = 3 and id_tipo_unidad = {$peticion['tipos_unidades']}
                        group by pp.nombre, D.clave_delegacional, D.nombre , UI.nombre , pp.descripcion
                        order by UI.nombre");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                        //UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                    }
                    return $estado;
                    break;
                default:
                    return $estado;
                    break;
            }
        }
    }

    /**
     * Función que obtiene el reporte de unidades totales
     * dependiendo de la peticion
     * @author Cheko
     * @param array $usuario datos del usuario para generar el reporte
     * @param strig $nivel si es por delegacion o por unidad
     * @param array $peticion el arreglo de datos
     * @return array $estado estado de la repuesta al obtener
     * el reporte
     *
     */
    public function reporte_total_unidades($usuario, $nivel ,$peticion)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $estado = array('success' => false, 'message' => 'Peticion incorrecta', 'datos'=>[]);
        if(count($peticion) < 0)
        {
            return $estado;
        }
        else
        {
            switch ($nivel)
            {

                case 'delegacion':
                    if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select count(TU) total_unidades from
                        (select * from catalogos.unidades_instituto UI
                        where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']}) TU");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }
                    else
                    {
                      if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos")
                      {
                          $resultado = $this->db->query("select count(TU) total_unidades from
                          (select * from catalogos.unidades_instituto UI
                          where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}) TU");
                          $reporte = $resultado->result_array();
                          $estado['success'] = true;
                          $estado['message'] = "Se obtuvo el reporte con exito";
                          $estado['datos'] = $reporte;
                      }
                      else
                      {
                          if($peticion['region'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                          {
                              $resultado = $this->db->query("select count(TU) total_unidades from
                              (select * from catalogos.unidades_instituto UI
                              where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_delegacion = {$peticion['id_delegacion']}) TU");
                              $reporte = $resultado->result_array();
                              $estado['success'] = true;
                              $estado['message'] = "Se obtuvo el reporte con exito";
                              $estado['datos'] = $reporte;

                          }
                          else
                          {
                              if($peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                              {
                                  $resultado = $this->db->query("select count(TU) total_unidades from
                                  (select * from catalogos.unidades_instituto UI
                                  where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']})TU");
                                  $reporte = $resultado->result_array();
                                  $estado['success'] = true;
                                  $estado['message'] = "Se obtuvo el reporte con exito";
                                  $estado['datos'] = $reporte;
                              }
                              else
                              {
                                if($peticion['delegacion'] == "Todos")
                                {
                                    $resultado = $this->db->query("select count(TU) total_unidades from
                                    (select * from catalogos.unidades_instituto UI
                                    where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}) TU");
                                    $reporte = $resultado->result_array();
                                    $estado['success'] = true;
                                    $estado['message'] = "Se obtuvo el reporte con exito";
                                    $estado['datos'] = $reporte;
                                }
                                else
                                {
                                    if($peticion['tipos_unidades'] == "Todos")
                                    {
                                        $resultado = $this->db->query("select count(TU) total_unidades from
                                        (select * from catalogos.unidades_instituto UI
                                        where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_delegacion = {$peticion['delegacion']}) TU");
                                        $reporte = $resultado->result_array();
                                        $estado['success'] = true;
                                        $estado['message'] = "Se obtuvo el reporte con exito";
                                        $estado['datos'] = $reporte;
                                    }
                                    else
                                    {
                                        if($peticion['region'] == "Todos")
                                        {
                                            $resultado = $this->db->query("select count(TU) total_unidades from
                                            (select * from catalogos.unidades_instituto UI
                                            where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']} and UI.id_delegacion = {$peticion['delegacion']}) TU");
                                            $reporte = $resultado->result_array();
                                            $estado['success'] = true;
                                            $estado['message'] = "Se obtuvo el reporte con exito";
                                            $estado['datos'] = $reporte;
                                        }
                                        else
                                        {
                                          $resultado = $this->db->query("select count(TU) total_unidades from
                                          (select * from catalogos.unidades_instituto UI
                                          where UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']} and UI.id_delegacion = {$peticion['delegacion']}) TU");
                                          $reporte = $resultado->result_array();
                                          $estado['success'] = true;
                                          $estado['message'] = "Se obtuvo el reporte con exito";
                                          $estado['datos'] = $reporte;
                                        }
                                    }
                                }

                              }
                          }
                      }

                    }
                    return $estado;
                    break;
                case 'umae':
                    //pr($peticion);
                    if($peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select count(TU) total_unidades from (select * from catalogos.unidades_instituto UI
                        where UI.anio = 2017 and UI.nivel_atencion = 3) TU");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }else{
                        $resultado = $this->db->query("select count(TU) total_unidades from (select * from catalogos.unidades_instituto UI
                        where UI.anio = 2017 and UI.nivel_atencion = 3 and UI.id_tipo_unidad = {$peticion['tipos_unidades']}) TU");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }
                    return $estado;
                    break;
                default:
                    return $estado;
                    break;
            }
        }
    }

    /**
     * Función que obtiene el reporte de unidades totales
     * dependiendo de la peticion
     * @author Cheko
     * @param array $usuario datos del usuario para generar el reporte
     * @param strig $nivel si es por delegacion o por unidad
     * @param array $peticion el arreglo de datos
     * @return array $estado estado de la repuesta al obtener
     * el reporte
     *
     */
    public function reporte_total_unidades_programa($usuario, $nivel ,$peticion)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $estado = array('success' => false, 'message' => 'Peticion incorrecta', 'datos'=>[]);
        if(count($peticion) < 0)
        {
            return $estado;
        }
        else
        {
            switch ($nivel)
            {

                case 'delegacion':
                    if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                        from catalogos.unidades_instituto UI
                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                        where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']}
                        group by UI.id_delegacion
                        having sum(hi.numerador) > 0");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }
                    else
                    {
                      if($peticion['region'] == "Todos" && $peticion['delegacion'] == "Todos")
                      {
                          $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                          from catalogos.unidades_instituto UI
                          	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                          	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                          where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                          group by UI.id_delegacion
                          having sum(hi.numerador) > 0");
                          $reporte = $resultado->result_array();
                          $estado['success'] = true;
                          $estado['message'] = "Se obtuvo el reporte con exito";
                          $estado['datos'] = $reporte;
                      }
                      else
                      {
                          if($peticion['region'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                          {
                              $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                              from catalogos.unidades_instituto UI
                              	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                              	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                              where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_delegacion = {$peticion['id_delegacion']}
                              group by UI.id_delegacion
                              having sum(hi.numerador) > 0");
                              $reporte = $resultado->result_array();
                              $estado['success'] = true;
                              $estado['message'] = "Se obtuvo el reporte con exito";
                              $estado['datos'] = $reporte;

                          }
                          else
                          {
                              if($peticion['delegacion'] == "Todos" && $peticion['tipos_unidades'] == "Todos")
                              {
                                  $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                                  from catalogos.unidades_instituto UI
                                  	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                  	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                  where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['id_region']}
                                  group UI.id_delegacion
                                  having sum(hi.numerador) > 0");
                                  $reporte = $resultado->result_array();
                                  $estado['success'] = true;
                                  $estado['message'] = "Se obtuvo el reporte con exito";
                                  $estado['datos'] = $reporte;
                              }
                              else
                              {
                                if($peticion['delegacion'] == "Todos")
                                {
                                    $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                                    from catalogos.unidades_instituto UI
                                    	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                    	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                    where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                                    group by UI.id_delegacion
                                    having sum(hi.numerador) > 0");
                                    $reporte = $resultado->result_array();
                                    $estado['success'] = true;
                                    $estado['message'] = "Se obtuvo el reporte con exito";
                                    $estado['datos'] = $reporte;
                                }
                                else
                                {
                                    if($peticion['tipos_unidades'] == "Todos")
                                    {
                                        $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                                        from catalogos.unidades_instituto UI
                                        	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                        	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                        where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_delegacion = {$peticion['delegacion']}
                                        group by UI.id_delegacion
                                        having sum(hi.numerador) > 0");
                                        $reporte = $resultado->result_array();
                                        $estado['success'] = true;
                                        $estado['message'] = "Se obtuvo el reporte con exito";
                                        $estado['datos'] = $reporte;
                                        //
                                    }
                                    else
                                    {
                                        if($peticion['region'] == "Todos")
                                        {
                                            $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                                            from catalogos.unidades_instituto UI
                                            	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                            	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                            where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']} and UI.id_delegacion = {$peticion['delegacion']}
                                            group by UI.id_delegacion
                                            having sum(hi.numerador) > 0");
                                            $reporte = $resultado->result_array();
                                            $estado['success'] = true;
                                            $estado['message'] = "Se obtuvo el reporte con exito";
                                            $estado['datos'] = $reporte;
                                        }
                                        else
                                        {
                                            $resultado = $this->db->query("select UI.id_delegacion, count(distinct hi.cve_presupuestal) total_unidades
                                            from catalogos.unidades_instituto UI
                                            	left join dec.h_indicadores hi ON(hi.cve_presupuestal = UI.clave_presupuestal)
                                            	left join catalogos.programas_proyecto pp ON(pp.id_programa_proyecto = hi.id_programa_proyecto)
                                            where hi.numerador > 0 and hi.anio = 2017 and UI.anio = 2017 and UI.nivel_atencion = {$peticion['nivel']} and UI.id_region = {$peticion['region']} and UI.id_tipo_unidad = {$peticion['tipos_unidades']} and UI.id_delegacion = {$peticion['delegacion']}
                                            group by UI.id_delegacion
                                            having sum(hi.numerador) > 0");
                                            $reporte = $resultado->result_array();
                                            $estado['success'] = true;
                                            $estado['message'] = "Se obtuvo el reporte con exito";
                                            $estado['datos'] = $reporte;
                                        }
                                    }
                                }

                              }
                          }
                      }

                    }
                    return $estado;
                    break;
                case 'umae':
                    if($peticion['tipos_unidades'] == "Todos")
                    {
                        $resultado = $this->db->query("select count(distinct T.unidad) total_unidades from (select HI.id_programa_proyecto, UI.nombre unidad, HI.cve_presupuestal clave_unidad, sum(HI.numerador) numerador, sum(HI.denominador) denominador from dec.h_indicadores HI
                        inner join catalogos.unidades_instituto UI on UI.clave_presupuestal = HI.cve_presupuestal
                        where hi.numerador > 0 and hi.anio = 2017 and UI.nivel_atencion = 3 and UI.anio = 2017
                        group by HI.cve_presupuestal, UI.nombre, HI.id_programa_proyecto) T
                        where numerador > 0");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;

                    }else{
                        $resultado = $this->db->query("select count(distinct T.unidad) total_unidades from (select HI.id_programa_proyecto, UI.nombre unidad, HI.cve_presupuestal clave_unidad, sum(HI.numerador) numerador, sum(HI.denominador) denominador from dec.h_indicadores HI
                        inner join catalogos.unidades_instituto UI on UI.clave_presupuestal = HI.cve_presupuestal
                        where hi.numerador > 0 and hi.anio = 2017 and UI.nivel_atencion = 3 and UI.anio = 2017 and UI.id_tipo_unidad = {$peticion['tipos_unidades']}
                        group by HI.cve_presupuestal, UI.nombre, HI.id_programa_proyecto) T
                        where numerador > 0");
                        $reporte = $resultado->result_array();
                        $estado['success'] = true;
                        $estado['message'] = "Se obtuvo el reporte con exito";
                        $estado['datos'] = $reporte;
                    }
                    return $estado;
                    break;
                default:
                    return $estado;
                    break;
            }
        }
    }
}
