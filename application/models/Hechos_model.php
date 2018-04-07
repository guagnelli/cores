<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para agregar, modificar, eliminar hechos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Hechos_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    /**
     * El metodo carga, desempaqueta y descomprime el archivo subido
     * @author Christian Garcia
     * @return string - json leido de la carga de archivos
     */
    public function get_content_file()
    {
        $file_data = $this->upload->data();     //BUSCAMOS LA INFORMACIÓN DEL ARCHIVO CARGADO
        $file_path = './uploads/' . $file_data['file_name'];         // CARGAMOS LA URL DEL ARCHIVO
        $p = new PharData($file_path);  // devuelve "abcde");
        $p->decompress(); // creates files.tar
        pr('Untar');
        $p->extractTo(substr($file_path, 0, -3));
        $myfile = substr($file_path, 0, -4) . '/' . scandir(substr($file_path, 0, -4))[2]; // 0 : ., 1: .., 2 primer archivo
        $contenido = fopen("$myfile", "r") or die("Unable to open file!");
        $json = fread($contenido, filesize($myfile));
        fclose($contenido);
        return $json;
    }

    public function get_content_csv()
    {
        $file_data = $this->upload->data();     //BUSCAMOS LA INFORMACIÓN DEL ARCHIVO CARGADO
        $file_path = './uploads/' . $file_data['file_name'];         // CARGAMOS LA URL DEL ARCHIVO
        $csv_array = $this->csvimport->get_array($file_path);   //SI EXISTEN DATOS, LOS CARGAMOS EN LA VARIABLE
        return $csv_array;
    }

    public function get_lista()
    {
        //reiniciamos el query y borramos cache por si las moscas
        $this->db->flush_cache();
        $this->db->reset_query();
        $query = $this->db->get('sistema.cargas_informacion');  // Produces: SELECT * FROM mytable
        return $query->result_array();
    }

    public function valid_csv(&$contenido)
    {
        $salida['status'] = true;
        $headers = array(
            'id_unidad_instituto', 'id_implementacion', 'id_categoria',
            'id_sexo', 'cantidad_alumnos_inscritos', 'cantidad_alumnos_certificados',
            'grupo_tipo_unidad', 'grupo_delegacion', 'tipo_curso', 'cantidad_no_accesos'
        );
        foreach ($headers as $h)
        {
            if (!isset($contenido[0][$h]))
            {
                $salida['status'] = false;
            }
        }
        return $salida;
    }

    /**
     * verifica que el contenido del json sea un diccionario con los datos correctos
     * otra de la verificaciones que hace es sobre las fechas, no permite superponer informacion
     * @param type $contenido
     * @return type
     */
    public function valid_json(&$contenido)
    {
        $salida['status'] = false;
        try
        {
            $json = json_decode($contenido, true);
            $salida['status'] = empty($json) ? false : true;
            if ($salida)
            {
                $index = 0;
                $salida['errores'] = [];
                $salida['status'] = true;
                if (!(isset($contenido["fecha_inicio"]) && isset($contenido["fecha_fin"]) && $this->valida_fechas($contenido["fecha_fin"], $contenido["fecha_fin"])))
                {
                    $salida['errores'][] = 'Rango de fechas no valido';
                    $salida['status'] = false;
                }
            }
        } catch (Exception $ex)
        {
            
        }
        return $salida;
    }

    public function insert_data_csv(&$contenido)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $resultado['result'] = false;
        $resultado['data'] = [];
        $this->db->trans_begin(); //Definir inicio de transacción
        $data = array(
            'fecha_inicio' => date('Y-m-d'),
            'fecha_fin' => date('Y-m-d')
        );
        $this->db->insert('sistema.cargas_informacion', $data);
        $id_carga = $this->db->insert_id();
        foreach ($contenido as $row)
        {
            if ($this->valida_registro($row))
            {
                $data = $row;
                $registro = $this->get_registro($data);
                $data['id_carga_informacion'] = $id_carga;
                if (is_null($registro))
                {                    
                    $this->db->insert('hechos.hechos_implementaciones_alumnos', $data);
                } else
                {
                    unset($data['id_hecho_implementacion_alumno']);
                    unset($data['id_unidad_instituto']);
                    unset($data['id_implementacion']);
                    unset($data['id_categoria']);
                    unset($data['id_sexo']);
                    $this->db->where('id_hecho_implementacion_alumno', $registro['id_hecho_implementacion_alumno']);
                    $this->db->update('hechos.hechos_implementaciones_alumnos', $data);
                }
            } else
            {
                $row['errores'] = 'Información de catalogo no presente en la base de datos';                
            }
            $resultado['data'][] = $row;
            $this->db->reset_query();
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else
        {
            $this->db->trans_commit();
            $resultado['result'] = TRUE;
        }
        return $resultado;
    }
    
    private function valida_registro($row = []){
        $valido = false;
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        $this->db->where('id_unidad_instituto', $row['id_unidad_instituto']);
        $valido = $this->db->get('catalogos.unidades_instituto')->result_array()[0]['cantidad']>0;
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        $this->db->where('id_implementacion', $row['id_implementacion']);
        $valido = $valido && $this->db->get('catalogos.implementaciones')->result_array()[0]['cantidad']>0;       
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        $this->db->where('id_categoria', $row['id_categoria']);
        $valido = $valido && $this->db->get('catalogos.categorias')->result_array()[0]['cantidad']>0;
        $this->db->reset_query();             
        $row['valido'] = $valido?1:0;
        //pr($row);
        return $valido;
    }

    private function get_registro($row = [])
    {
        $registro = null;
        $this->db->reset_query();
        $this->db->where('id_unidad_instituto', $row['id_unidad_instituto']);
        $this->db->where('id_implementacion', $row['id_implementacion']);
        $this->db->where('id_categoria', $row['id_categoria']);
        $this->db->where('id_sexo', $row['id_sexo']);
        $resultset = $this->db->get('hechos.hechos_implementaciones_alumnos');
        if (!is_null($resultset) && count($resultset->result_array()) > 0)
        {
            $registro = $resultset->result_array()[0];
        }
        $this->db->reset_query();
        return $registro;
    }

    /**
     * @author Christian Garcia
     * @param type $contenido - contenido json con la información de hechos a cargar
     */
    public function insert_data(&$contenido)
    {
        //reiniciamos el query y borramos cache por si las moscas
        $this->db->flush_cache();
        $this->db->reset_query();
        $resultado = [];
        if (isset($contenido['hechos']['implementaciones_alumnos']))
        {
            $resultado[] = $this->insert_hechos('implementaciones_alumnos', $contenido);
        }
        if (isset($contenido['hechos']['implementaciones_docentes']))
        {
            $resultado[] = $this->insert_hechos('implementaciones_docentes', $contenido);
        }
        if (isset($contenido['hechos']['unidades_instituto']))
        {
            $resultado[] = $this->insert_hechos('unidades_instituto', $contenido);
        }
    }

    /**
     * @author Christian Garcia
     * @param type $id
     * @param type $status
     */
    public function update($id, $status)
    {
        //reiniciamos el query y borramos cache por si las moscas
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->set('activa', $status);
        $this->db->where('id_carga_informacion', $id);
        $this->db->update('sistema.cargas_informacion');
        pr($this->db->last_query());
        $this->db->reset_query();
        $this->db->select(array('fecha_inicio', 'fecha_fin'));
        $this->db->where('id_carga_informacion', $id);
        $resultado = $this->db->get('sistema.cargas_informacion')->result_array()[0];
        $inicio = $resultado['fecha_inicio'];
        $fin = $resultado['fecha_fin'];
//        pr($this->db->last_query());
        //pr($resultado);
        $this->db->reset_query();
        $this->db->set('activa', false);
        $this->db->where('fecha_inicio >= ', $inicio);
        $this->db->where('fecha_fin <= ', $fin);
        $this->db->where('id_carga_informacion != ', $id);
        $this->db->where('activa', true);
        $this->db->update('sistema.cargas_informacion');
//        pr($this->db->last_query());
        //pr('valido: '.($this->valida_fechas($inicio, $fin)?1:0));
    }

    /**
     * @author Christian Garcia
     * @param date $inicio
     * @param date $fin
     * @return salida - boolean, true en caso de que el rango de fechas valido
     */
    private function valida_fechas(&$inicio, &$fin)
    {
        //reiniciamos el query y borramos cache por si las moscas
        $salida = true;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        $this->db->where('fecha_inicio >=', $inicio);
        $this->db->where('fecha_fin >=', $fin);
        $this->db->where('activa', true);
        $resultado = $this->db->get('sistema.cargas_informacion')->result_array()[0]['cantidad'];
        $salida = $resultado = 0 ? true : false;
        return $salida;
    }

    function insert_hechos($tipo, &$contenido)
    {
        $this->db->trans_begin(); //Definir inicio de transacción

        $data = array(
            'fecha_inicio' => $contenido['fecha_inicio'],
            'fecha_fin' => $contenido['fecha_fin']
        );

        $this->db->insert('sistema.cargas_informacion', $data);
        $id_carga = $this->db->insert_id();

        switch ($tipo)
        {
            case 'implementaciones_alumnos':
                if (isset($contenido['hechos'][$tipo]))
                {
                    $datos = $contenido['hechos'][$tipo];
                    foreach ($datos as $value)
                    {
                        $data = array(
                            'id_unidad_instituto' => $value[0],
                            'id_implementacion' => $value[1],
                            'id_categoria' => $value[2],
                            'id_sexo' => $value[3],
                            'cantidad_alumnos_inscritos' => $value[4],
                            'cantidad_alumnos_certificados' => $value[5],
                            'id_carga_informacion' => $id_carga,
                            'grupo_tipo_unidad' => $value[6],
                            'grupo_delegacion' => $value[7],
                            'tipo_curso' => $value[8],
                            'cantidad_no_accesos' => $value[9]
                        );
                        $this->db->insert('hechos.hechos_implementaciones_alumnos', $data);
                    }
                }
                break;
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else
        {
            $this->db->trans_commit();
            $resultado['result'] = TRUE;
        }
        return $resultado;
    }

}
