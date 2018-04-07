<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    /**
     * @author Ale Quiroz
     * @return type array con las delegaciones existentes
     */
    public function lista_delegaciones()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array('id_delegacion', 'clave_delegacional', 'nombre'));
        $resultado_delegaciones = $this->db->get('catalogos.delegaciones')->result_array();
        return $resultado_delegaciones;
    }

    public function lista_nivel_atencion()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array('id_grupo', 'nombre'));
        $resultado_nivel_atencion = $this->db->get('sistema.grupos')->result_array();
        return $resultado_nivel_atencion;
    }

    /**
     * @author Ale Quiroz
     * @return type array con los niveles de atencion
     */
    public function lista_categoria($keyword = null)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array(
            'clave_categoria', 'concat(nombre, $$ [$$, clave_categoria, $$]$$) nombre'
        ));
        if ($keyword != null)
        {
            $this->db->like('lower(concat(clave_categoria,$$ $$, nombre))', $keyword);
            $this->db->limit(10);
        }
        $categoria = $this->db->get('catalogos.categorias')->result_array();
        return $categoria;
    }

    public function lista_unidad($keyword = null, $tipo_unidad = 0, $delegacion = '0', $periodo = "")
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if ($periodo == null || $periodo == "")
        {
            $periodo = date("Y");
        }
        $this->db->select(array(
            'id_unidad_instituto'
            , 'nombre'
            , 'clave_unidad'
                //,'concat(nombre, $$ TIPO: $$, id_tipo_unidad, $$, ESPERADO: '.$tipo_unidad.'$$) nombre'
        ));
        if ($keyword != null)
        {
            $this->db->like('lower(concat(clave_unidad,$$ $$, nombre))', $keyword);
            $this->db->limit(10);
        }
        if ($tipo_unidad > 0)
        {
            $this->db->where('id_tipo_unidad', $tipo_unidad);
        }
        if ($delegacion != '0')
        {
            if (is_int($delegacion))
            {
                $this->db->where('id_delegacion', $delegacion);
            } else
            {
                $this->db->where('grupo_delegacion', $delegacion);
            }
        }
        $this->db->where('anio', $periodo);
        $resultado_unidades = $this->db->get('catalogos.unidades_instituto')->result_array();
//        pr($this->db->last_query());
//        pr($delegacion);
        return $resultado_unidades;
    }

    public function localiza_unidad($clave)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $unidad = null;
        if (strlen($clave) > 7)
        {
            $busqueda = substr($clave, 0, 7);
            $this->db->like('clave_unidad', $clave, 'after');
            $resultado = $this->db->get('catalogos.unidades_instituto')->result_array();
            if ($resultado)
            {
                $unidad = $resultado[0];
            }
        }
        return $unidad;
    }

    public function registro_usuario(&$parametros = null)
    {
        $salida['msg'] = 'Error';
        $salida['result'] = false;
        $data = array(
            "reg_delegacion" => $parametros['delegacion'],
            "asp_matricula" => $parametros['matricula']
        );

        $token = $this->seguridad->folio_random(10, TRUE);
        $pass = $this->seguridad->encrypt_sha512($token . $parametros['password'] . $token);
        $usuario = $this->empleados_siap->buscar_usuario_siap($data)['empleado'];
        //pr($usuario);
        $usuario_db = $this->get_usuario($parametros['matricula']) == null;
        if ($usuario && $usuario_db)
        {
            $unidad_instituto = $this->get_unidad($usuario['adscripcion']);
            if ($unidad_instituto == null)
            {
                $unidad_instituto = $this->localiza_unidad($usuario['adscripcion']);
            }
            if ($unidad_instituto != null)
            {
                $data = array(
                    'nombre' => $usuario['nombre'] . ' ' . $usuario['paterno'] . ' ' . $usuario['materno'],
                    'password' => $pass,
                    'token' => $token,
                    'email' => $parametros['email'],
                    'matricula' => $parametros['matricula'],
                    'curp' => $usuario ['curp'],
                    'clave_delegacional' => $usuario ['delegacion'],
                    'clave_categoria' => $usuario ['emp_keypue'],
                    'id_unidad_instituto' => $unidad_instituto['id_unidad_instituto'],
                    'id_departamento_instituto' => $unidad_instituto['id_departamento_instituto']
                );
                //pr($data);
                $salida = $this->insert_guardar($data, $parametros['grupo']);
                $salida['siap'] = $data;
            } else
            {
                $salida['msg'] = 'Adscripción no localizada en la base de datos';
            }
        } else if (!$usuario_db)
        {
            $salida['msg'] = 'Usuario ya registrado';
        } else if (!$usuario)
        {
            $salida['msg'] = 'Usuario no registrado en SIAP';
        }
        return $salida;
    }

    private function insert_guardar(&$datos, $id_grupo)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción

        $this->db->insert('sistema.usuarios', $datos); //nombre de la tabla en donde se insertaran
        $id_usuario = $this->db->insert_id();
        $data = array(
            'id_grupo' => $id_grupo,
            'id_usuario' => $id_usuario
        );
        $this->db->insert('sistema.grupos_usuarios', $data);
        //pr($this->db->last_query());
        //pr($datos);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else
        {
            $this->db->trans_commit();
            $resultado['msg'] = 'Usuario almacenado con éxito';
            $resultado['result'] = TRUE;
        }
        return $resultado;
    }

    private function get_unidad($clave)
    {
        $unidad = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('clave_departamental', $clave);
        $resultado = $this->db->get('catalogos.departamentos_instituto')->result_array();
        if ($resultado)
        {
            $unidad = $resultado[0];
        }
        return $unidad;
    }

    private function get_usuario($matricula)
    {
        //pr($matricula);
        $usuario = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->where('matricula', $matricula);
        $resultado = $this->db->get('sistema.usuarios')->result_array();
        if ($resultado)
        {
            $usuario = $resultado[0];
        }
        //pr($usuario==null?'1':'0');
        return $usuario;
    }

    public function ver($filtros = null)
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
            $this->db->order_by('u.matricula', 'DESC');
        } else
        {
            $this->db->order_by('u.matricula', 'ASC');
        }

        $this->db->select(array(
            'u.id_usuario', 'u.nombre nombre', 'u.matricula', 'u.email'
            , 'u.clave_delegacional', 'd.nombre name_delegacion'
            , 'u.clave_categoria', 'c.nombre name_categoria'
            , 'u.id_unidad_instituto', 'ui.nombre name_unidad_ist'
            , 'd.nombre name_region'
            , 'u.email', 'ui.umae', 'u.activo'
        ));
        $this->db->join('catalogos.categorias c', 'c.clave_categoria = u.clave_categoria', 'left');
        $this->db->join('catalogos.delegaciones d', 'd.clave_delegacional = u.clave_delegacional', 'left');
        $this->db->join('catalogos.unidades_instituto ui', 'ui.id_unidad_instituto = u.id_unidad_instituto', 'left');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region', 'left');

        if (isset($filtros['type']) && isset($filtros['keyword']) &&
                !empty($filtros['keyword']) && in_array($filtros['type'], array('nombre', 'matricula', 'email')))
        {
            $this->db->like('u.' . $filtros['type'], $filtros['keyword']);
        }

        $this->db->order_by('u.id_usuario');
        $tabla = $this->db->get('sistema.usuarios u')->result_array();
        //pr($this->db->last_query());
        $this->db->reset_query();
        $resultado['tabla'] = $tabla;
        if (isset($filtros['type']) && isset($filtros['keyword']) &&
                !empty($filtros['keyword']) && in_array($filtros['type'], array('nombre', 'matricula', 'email')))
        {
            $this->db->like('u.' . $filtros['type'], $filtros['keyword']);
        }
        $this->db->select('count(*) cantidad');
        $resultado['total'] = $this->db->get('sistema.usuarios u')->result_array()[0]['cantidad'];

        return $resultado;
    }

    public function datos_usuario($id = null)
    {


        if (is_null($id))
        {
            return null;
        }

        $select = array(
            'u.id_usuario', 'u.nombre name_user', 'u.matricula', 'u.curp'
            , 'u.clave_delegacional', 'd.nombre name_delegacion'
            , 'u.clave_categoria', 'c.nombre name_categoria'
            , 'u.id_unidad_instituto', 'ui.nombre name_unidad_ist', 'ui.clave_unidad'
            , 'r.id_region', 'd.nombre name_region', 'u.password'
            , 'u.email', 'ui.umae'
            , 'g.id_grupo', 'g.nombre nombre_grupo', 'g.nivel'
            , 'u.token'
        );

        $this->db->select($select);
        $this->db->from('sistema.usuarios u');
        $this->db->join('sistema.grupos_usuarios gu', 'gu.id_usuario = u.id_usuario', 'left');
        $this->db->join('sistema.grupos g', 'g.id_grupo = gu.id_grupo', 'left');
        $this->db->join('catalogos.categorias c', 'c.clave_categoria = u.clave_categoria', 'left');
        $this->db->join('catalogos.delegaciones d', 'd.clave_delegacional = u.clave_delegacional', 'left');
        $this->db->join('catalogos.unidades_instituto ui', 'ui.id_unidad_instituto = u.id_unidad_instituto', 'left');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region', 'left');

        $this->db->where('u.id_usuario', $id);
        $query = $this->db->get();
        //        $result = $query->row();
        $result = $query->result_array();
        $return = array();
        //        pr($this->db->last_query());
        if (count($result) > 0)
        {
            $return = $result[0];
            $return['unidad_texto'] = $return['name_unidad_ist'] . ' [' . $return['clave_unidad'] . ']';
            $return['categoria_texto'] = $return['name_categoria'] . ' [' . $return['clave_categoria'] . ']';
        }

        $query->free_result();
        return $return;
    }

    public function actualiza_registro($data)
    {
        $salida = false;

        $this->db->select('B.clave_delegacional');
        $this->db->join('catalogos.delegaciones B', 'B.id_delegacion = A.id_delegacion', 'inner');
        $this->db->where('A.id_unidad_instituto', $data ['unidad']);
        $id_delegacion = $this->db->get('catalogos.unidades_instituto A')->result_array()[0]['clave_delegacional'];
        $this->db->reset_query();

        $this->db->where('id_usuario', $data ['id_usuario']);
        $this->db->set('email', $data ['email']);
        $this->db->set('clave_delegacional', $id_delegacion);
        $this->db->set('id_unidad_instituto', $data ['unidad']);
        $this->db->set('clave_categoria', $data ['categoria']);
        $obten_registro = $this->db->update('sistema.usuarios');
        //pr($this->db->last_query());
        $salida = true;
        return $salida;
    }

    public function set_status($id_usuario = 0, $status = false)
    {
        $salida = false;
        $this->db->flush_cache();
        $this->db->reset_query();
        try
        {
            $this->db->set('activo', $status);
            $this->db->where('id_usuario', $id_usuario);
            $this->db->update('sistema.usuarios');
            $salida = true;
            //pr($this->db->last_query());
        } catch (Exception $ex)
        {
            
        }
        return $salida;
    }

    public function update_password($datos = null)
    {
        $salida = false;
        try
        {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('token');
            $this->db->where('id_usuario', $datos['id_usuario']);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($datos);
            //pr($this->db->last_query());
            if ($resultado)
            {
                $this->load->library('seguridad');
                $token = $resultado[0]['token'];
                $this->db->reset_query();
                $password = $this->seguridad->encrypt_sha512($token . $datos['password'] . $token);
                $this->db->set('password', $password);
                $this->db->where('id_usuario', $datos['id_usuario']);
                $this->db->update('sistema.usuarios');
//                pr($this->db->last_query());
                $salida = true;
            } else
            {
                // pr('usuario no localizado');
            }
        } catch (Exception $ex)
        {
            //  pr($ex);
        }
        return $salida;
    }

    private function get_grupo($nombre_grupo)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $grupo = null;
        $this->db->where('nombre', $nombre_grupo);
        $resultado = $this->db->get('sistema.grupos')->result_array();
        if ($resultado)
        {
            $grupo = $resultado[0];
        }
        //pr($this->db->last_query());
        return $grupo;
    }

    private function get_delegacion($nombre)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $delegacion = null;
        $this->db->where('nombre', $nombre);
        $resultado = $this->db->get('catalogos.delegaciones')->result_array();
        if ($resultado)
        {
            $delegacion = $resultado[0];
        }
        //pr($this->db->last_query());
        return $delegacion;
    }

    public function carga_masiva(&$csv_array)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción
        $registros = [];
        $errores_presentes = false;
        foreach ($csv_array as $row)
        {
            if (isset($row['matricula']) && isset($row['delegacion']) && isset($row['grupo']) && isset($row['email']))
            {
                $clave_delegacional = $this->get_delegacion($row['delegacion'])['clave_delegacional'];
                $data = array(
                    "reg_delegacion" => $clave_delegacional,
                    "asp_matricula" => $row['matricula']
                );
                $usuario = $this->empleados_siap->buscar_usuario_siap($data);
                //pr($data);
                if ($usuario['tp_msj'] == En_tpmsg::SUCCESS)
                {
                    $usuario = $usuario['empleado'];
                    //pr($usuario);
                    $token = $this->seguridad->folio_random(10, TRUE);
                    $password = $this->seguridad->folio_random(10, TRUE);
                    $pass = $this->seguridad->encrypt_sha512($token . $password . $token);

                    $grupo = $this->get_grupo($row['grupo']);
                    //pr($row['grupo']);
                    //pr($grupo);
                    $usuario_registrado = $this->get_usuario($usuario['matricula'][0]);
                    //pr($usuario == null ? '1':'0'); pr($usuario);
                    //pr($usuario_registrado == null ? '1':'0'); pr($usuario_registrado);
                    //pr($grupo == null ? '1':'0'); pr($grupo);
                    if ($usuario && $usuario_registrado == null && $grupo != null)
                    {
                        //pr($usuario);
                        $unidad_instituto = $this->get_unidad($usuario['adscripcion']);

                        if ($unidad_instituto == null)
                        {
                            $unidad_instituto = $this->localiza_unidad($usuario['adscripcion']);
                        }
                        if ($unidad_instituto != null)
                        {

                            $data = array(
                                'nombre' => $usuario['nombre'] . ' ' . $usuario['paterno'] . ' ' . $usuario['materno'],
                                'password' => $pass,
                                'token' => $token,
                                'email' => $row['email'],
                                'matricula' => $row['matricula'],
                                'curp' => $usuario ['curp'],
                                'clave_delegacional' => $usuario ['delegacion'],
                                'clave_categoria' => $usuario ['emp_keypue'],
                                'id_unidad_instituto' => $unidad_instituto['id_unidad_instituto'],
                                'id_departamento_instituto' => $unidad_instituto['id_departamento_instituto']
                            );
                            $this->db->insert('sistema.usuarios', $data); //nombre de la tabla en donde se insertaran
                            //pr($this->db->last_query());
                            $id_usuario = $this->db->insert_id();
                            $data = array(
                                'id_grupo' => $grupo['id_grupo'],
                                'id_usuario' => $id_usuario
                            );
                            $this->db->insert('sistema.grupos_usuarios', $data);
                            //pr($this->db->last_query());
                            //pr($datos);
                            $row['errores'] = '';
                            $row['nueva password'] = $password;
                        } else
                        {
                            $row['errores'] = 'Adscripción no localizada';
                        }
                    } else
                    {
                        $errores_presentes = true;
                        $row['errores'] = 'Usuario no encontrado o ya registrado en el sistema';
                    }
                } else
                {
                    $errores_presentes = true;
                    $row['errores'] = 'Usuario no encontrado o ya registrado en el sistema';
                }
            } else
            {
                $errores_presentes = true;
                $row['errores'] = 'Datos de matricula, grupo y delegacion inválidos';
            }
            $registros[] = $row;
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $registros[] = 'Error en la transaccion';
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else
        {
            $this->db->trans_commit();
            $resultado['msg'] = 'Usuarios almacenado con éxito';
            if ($errores_presentes)
            {
                $resultado['msg'] = 'Se presentaron errores durante el registro';
            }
            $resultado['result'] = TRUE;
        }
        $resultado['data'] = $registros;
        //pr($resultado);
        return $resultado;
    }

}
