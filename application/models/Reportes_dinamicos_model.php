<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para agregar, modificar, eliminar hechos estaticos del tablero
 * @version 	: 1.0.0
 * @autor 		: Christian García
 */
class Reportes_dinamicos_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_json()
    {
        //reiniciamos el query y borramos cache por si las moscas
        $this->db->flush_cache();
        $this->db->reset_query();
        $contenido = $this->db->get('hechos.hechos_implementaciones_alumnos')->result_array();
        $columnas = array(
            'id_hecho_implementacion_alumno',
            'id_unidad_instituto',
            'id_implementacion',
            'id_categoria',
            'id_sexo',
            'cantidad_alumnos_inscritos',
            'cantidad_alumnos_certificados',
            'id_meta',
            'id_carga_informacion'
        );
        $json['nombre'] = 'alumnos por implementacion 2016';
        $json['descripcion'] = 'Sin descripcion';
        $json['fecha_inicio'] = '2016/01/01';
        $json['fecha_fin'] = '2016/12/31';
        $json['columnas'] = $columnas;
        $json['contenido'] = $contenido;
        $json = json_encode($json);
        return $json;
    }

    public function insert_data(&$json = null)
    {
        if ($json != null)
        {
            //reiniciamos el query y borramos cache por si las moscas
            $this->db->flush_cache();
            $this->db->reset_query();
            $datos = json_decode($json, true);
            $this->db->trans_begin(); //Definir inicio de transacción
            $data = array(
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin']
            );
            $this->db->insert('hechos.reportes_dinamicos', $data);
            $id_carga = $this->db->insert_id();
            foreach ($datos['columnas'] as $value)
            {
                $data = array(
                    'nombre' => $value,
                    'id_reporte_dinamico' => $id_carga
                );
                $this->db->insert('hechos.columnas_dinamicas', $data);
            }

            $indice_vertical = 0;
            foreach ($datos['contenido'] as $row)
            {
                $fila = '{';
                $i = 1;
                foreach ($row as $key => $value)
                {
                    if ($value != null)
                    {
                        $fila .= $value;
                    } else
                    {
                        $fila .= 'NULL';
                    }
                    if ($i < count($row))
                    {
                        $fila .= ',';
                    }
                    $i++;
                }
                $fila .= '}';
                //pr($fila);
                $data = array(
                    'indice_vertical' => $indice_vertical,
                    'id_reporte_dinamico' => $id_carga,
                    'contenido' => $fila
                );
                $this->db->insert('hechos.contenido_dinamico', $data);
                $indice_vertical++;
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
            pr($resultado);
            return $resultado;
        }
    }

    public function get_table($filtros = null)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $resultado = $this->db->get('hechos.reportes_dinamicos')->result_array();
        return $resultado;
    }

    public function get_reporte($id = 0, $filros = null)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('id_reporte_dinamico', $id);
        $columnas = $this->db->get('hechos.columnas_dinamicas')->result_array();
        $contenido = [];
        $this->db->reset_query();
        $this->db->where('id_reporte_dinamico', $id);
        $info = $this->db->get('hechos.reportes_dinamicos')->result_array();
        $this->db->reset_query();
        $index = 1;
        $array_select = [];
        //pr($columnas);
        if (count($columnas) > 0)
        {
            foreach ($columnas as $value)
            {
                $array_select[] = 'contenido[' . $index . '] as c' . $index++;
            }
            //pr($array_select);
            if (isset($filros['limit']))
            {
                $this->db->limit($filros['limit']);
            }
            $this->db->where('id_reporte_dinamico', $id);
            $this->db->select($array_select, false);
            $contenido = $this->db->get('hechos.contenido_dinamico')->result_array();
            //pr($this->db->last_query());
        }
//        pr($columnas);
        //pr($contenido);
        $resultado["columnas"] = $columnas;
        $resultado["contenido"] = $contenido;
        $resultado["informacion"] = $info;
        return $resultado;
    }

    function delete_reporte($id = 0, $status = false)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('id_reporte_dinamico', $id);
        $this->db->set('activo', $status);
        $this->db->update('hechos.contenido_dinamico');
    }

    function upload($nombre, $descripcion)
    {
        return true;
    }

    public function valida_reporte_dinamico(&$json_txt = null)
    {
        $status = false;
        if ($json_txt != null)
        {
            $json = json_decode($json_txt, true);
            $status = isset($json['nombre']) && isset($json['descripcion']) && isset($json['fecha_inicio']);
            //pr($status?'true':'false');
            //pr($json['nombre']);
            $status &= isset($json['fecha_fin']) && isset($json['columnas']) && isset($json['contenido']);
        }
        return $status;
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
        //pr('Untar');
        $p->extractTo(substr($file_path, 0, -3));
        $myfile = substr($file_path, 0, -4) . '/' . scandir(substr($file_path, 0, -4))[2]; // 0 : ., 1: .., 2 primer archivo
        //pr($myfile);
        $contenido = fopen("$myfile", "r") or die("Unable to open file!");
        $json = fread($contenido, filesize($myfile));
        fclose($contenido);
        return $json;
    }

}
