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

    /**
     * Función que obtiene una region por
     * identificador
     * @author Cheko
     * @return array $estado estado de la respuesta la obtener la region
     *
     */
     public function obtener_region($id=NULL)
     {
         $estado = array('success' => false, 'message' => 'No se obtuvo el listado', 'datos'=>[]);
         $regiones = [];
         $this->db->flush_cache();
         $this->db->reset_query();
         if($id != NULL)
         {
             try
             {
                 if($id == "Todos")
                 {
                     $estado['success'] = true;
                     $estado['message'] = "Se obtuvo la region con exito";
                 }
                 else
                 {
                     $resultado = $this->db->query("select * from catalogos.regiones where id_region={$id}");
                     $regiones = $resultado->result_array();
                     $estado['success'] = true;
                     $estado['message'] = "Se obtuvo la region con exito";
                     $estado['datos'] = count($regiones) > 0 ? $regiones[0] : $regiones;
                 }


             }catch(Exception $ex)
             {
                 $estado['datos'] = $ex;
             }
         }
         else
         {
            $estado['message'] = "Se necesita un identificador de la region";

         }
         return $estado;
     }

    /**
     * Función que obtiene todas las regiones existentes
     * del catálogo
     * @author Cheko
     * @return array $estado estado de la repuesta al obtener
     * el listado del as regiones
     */
     public function obtener_regiones()
     {
        $estado = array('success' => false, 'message' => 'No se obtuvo el listado', 'datos'=>[]);
        $regiones = [];
        $this->db->flush_cache();
        $this->db->reset_query();
        try
        {
            $resultado = $this->db->query("select id_region, nombre region from catalogos.regiones where activo=true");
            $regiones = $resultado->result_array();
            $estado['success'] = true;
            $estado['message'] = "Se obtuvo el listado delas regiones con exito";
            array_push($regiones, array('id_region'=>'Todos','region'=>'Todos'));
            $estado['datos'] = $regiones;
        }catch(Exception $ex)
        {
            $estado['datos'] = $ex;
        }
        return $estado;
     }



     /**
      * Función que obtiene una region por
      * identificador
      * @author Cheko
      * @return array $estado estado de la respuesta la obtener la region
      *
      */
      public function obtener_delegacion($id=NULL)
      {
          $estado = array('success' => false, 'message' => 'No se obtuvo el listado', 'datos'=>[]);
          $delegaciones = [];
          $this->db->flush_cache();
          $this->db->reset_query();
          if($id != NULL)
          {
              try
              {
                  if($id == "Todos")
                  {
                      $estado['success'] = true;
                      $estado['message'] = "Se obtuvo la delegacion con exito";
                  }
                  else
                  {
                      $resultado = $this->db->query("select * from catalogos.delegaciones where id_delegacion={$id}");
                      $delegaciones = $resultado->result_array();
                      $estado['success'] = true;
                      $estado['message'] = "Se obtuvo la delegacion con exito";
                      $estado['datos'] = $delegaciones[0];
                  }

              }catch(Exception $ex)
              {
                  $estado['datos'] = $ex;
              }

          }
          else
          {
             $estado['message'] = "Se necesita un identificador de la delegacion";

          }
          return $estado;
      }

     /**
      * Función que obtiene todas las delegaciones existentes
      * del catálogo por la region
      * @author Cheko
      * @param string $region la region que se a pedido
      * @return array $estado estado de la repuesta al obtener
      * el listado de las delegaciones
      */
      public function obtener_delegaciones($region)
      {
          $estado = array('success' => false, 'message' => 'No se obtuvo el listado', 'datos'=>[]);
          $delegaciones = [];
          $this->db->flush_cache();
          $this->db->reset_query();
          try
          {
             if($region == "Todos"){
                $resultado = $this->db->query("select id_delegacion, nombre delegacion from catalogos.delegaciones where activo = true");
             }
             else
             {
                $resultado = $this->db->query("select id_delegacion, nombre delegacion from catalogos.delegaciones where activo = true and id_region = {$region}");
             }
             $delegaciones = $resultado->result_array();
             $estado['success'] = true;
             $estado['message'] = "Se obtuvo el listado delas regiones con exito";
             array_push($delegaciones, array('id_delegacion'=>"Todos",'delegacion'=>'Todos'));
             $estado['datos'] = $delegaciones;

          }catch(Exception $ex)
          {
             $estado['datos'] = $ex;
          }
          return $estado;
      }

      /**
       * Función que obtiene todos los tipos de unidades
       * de un cierto tipo de nivel de atención
       * @author Cheko
       * @return array $estado estado de la repuesta al obtener
       * el listado de las unidadades
       */
       public function obtener_tipo_unidades($nivel)
       {
           $estado = array('success' => false, 'message' => 'No se obtuvo el listado de los tipos de unidad', 'datos'=>[]);
           $tipos_unidades = [];
           $this->db->flush_cache();
           $this->db->reset_query();
           try
           {
               if($nivel == "Todos"){
                  $resultado = $this->db->query("select id_tipo_unidad,nombre from catalogos.tipos_unidades TU where activo = true");
               }
               else
               {
                  $resultado = $this->db->query("select id_tipo_unidad,nombre from catalogos.tipos_unidades TU where TU.nivel={$nivel}");
               }

              $tipos_unidades = $resultado->result_array();
              $estado['success'] = true;
              $estado['message'] = "Se obtuvo el listado de los tipos de unidad";
              array_push($tipos_unidades, array('id_tipo_unidad'=>"Todos",'nombre'=>'Todos'));
              $estado['datos'] = $tipos_unidades;

           }catch(Exception $ex)
           {
              $estado['datos'] = $ex;
           }
           return $estado;
       }

       /**
        * Obtiene la información de un tipo de unidad en
        * especifico
        * @author Cheko
        * @param string $id el el identificador del tipo de unidad
        * @return array $estado estado de la repuesta al obtener
        * el listado de las delegaciones
        *
        */
       public function obtener_tipo_unidad($id)
       {
           $estado = array('success' => false, 'message' => 'No se obtuvo el tipo de unidad', 'datos'=>[]);
           $tipoUnidad = [];
           $this->db->flush_cache();
           $this->db->reset_query();
           if($id != NULL)
           {
               try
               {
                  if($id == "Todos")
                  {
                      $estado['success'] = true;
                      $estado['message'] = "Se obtuvo el tipo de unidad con exito";
                  }
                  else
                  {
                      $resultado = $this->db->query("select * from catalogos.tipos_unidades TU where TU.id_tipo_unidad = {$id}");
                      $tipoUnidad = $resultado->result_array();
                      $estado['success'] = true;
                      $estado['message'] = "Se obtuvo el tipo de unidad con exito";
                      $estado['datos'] = $tipoUnidad[0];
                  }
               }catch(Exception $ex)
               {
                   $estado['datos'] = $ex;
               }

           }
           else
           {
              $estado['message'] = "Se necesita un identificador de la delegacion";
           }
           return $estado;
       }

}
