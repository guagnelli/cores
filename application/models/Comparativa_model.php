<?php

/**
 * Description of Comparativa_model
 *
 * @author chrigarc, mr. guag
 */
class Comparativa_model extends MY_Model
{

    const
            PERFIL = 'p',
            TIPO_CURSO = 'tc';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        $this->load->database();
    }

    public function get_tipos_comparativas()
    {
        return array(1 => 'Tipo de curso', 2 => 'Perfil');
    }

    public function get_tipos_reportes()
    {
        return array(1 => 'Inscritos', 2 => 'Aprobados', 3 => 'Eficiencia terminal',
            5 => 'No aprobados');
    }

    public function get_niveles($umae = false)
    {
        /*
          $this->db->flush_cache();
          $this->db->reset_query();
          $this->db->distinct();
          $this->db->select('nivel_atencion');
          $this->db->where('nivel_atencion is not null');
          $this->db->order_by('nivel_atencion');
          $niveles = $this->db->get('catalogos.unidades_instituto')->result_array();
         */
        if ($umae)
        {
            $niveles = array(
                3 => 'Tercer nivel',
            );
        } else
        {
            $niveles = array(
                1 => 'Primer nivel',
                2 => 'Segundo nivel',
            );
        }
        return $niveles;
    }

    public function get_tipos_unidades($umae = false, $delegacion = '0', $nivel = "")
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $select = array('A.id_tipo_unidad', 'A.nombre');
        $this->db->select($select);
        $this->db->join('catalogos.unidades_instituto B', 'B.id_tipo_unidad = A.id_tipo_unidad', 'inner');
        $this->db->where('B.umae', $umae);
        if ($delegacion != '0')
        {
            $this->db->where('B.grupo_delegacion', $delegacion);
        }
        if ($nivel != "")
        {
            $this->db->where('B.nivel_atencion', $nivel);
        }
        $tipos = $this->db->get('catalogos.tipos_unidades A')->result_array();
        //pr($this->db->last_query());
        return $tipos;
    }

    public function get_comparar_delegacion($filtros = [])
    {
        $datos = [];
        $datos['dato1'] = $this->get_data_delegacion($filtros['delegacion1'], $filtros);
        $datos['dato2'] = $this->get_data_delegacion($filtros['delegacion2'], $filtros);
        return $datos;
    }

    public function get_data_delegacion($delegacion = 0, &$filtros = array())
    {
        $this->db->flush_cache();
        $this->db->reset_query();

        $participantes = $this->get_numero_participantes_delegacion($filtros);

        $this->db->start_cache();
        $this->db->join('catalogos.unidades_instituto B', "A.id_delegacion = B.id_delegacion and B.anio = {$filtros['periodo']}", 'inner');
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', ' C.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'inner');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'inner');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'inner');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=E.id_tipo_curso AND tc.activo');
        $this->db->join('catalogos.categorias I', 'I.id_categoria = C.id_categoria', 'left');
        $this->db->join('catalogos.grupos_categorias H', 'H.id_grupo_categoria = I.id_grupo_categoria', 'left');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=H.id_subcategoria AND sub.activa', 'inner');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'inner');
        $this->db->where("(t.grupo_tipo != 'UMAE' and t.grupo_tipo != 'CUMAE')");
        $this->db->where('CI.activa', true);
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $inicio = $filtros['periodo'] . '/01/01';
            $fin = $filtros['periodo'] . '/12/31';
            $this->db->where('E.anio', $filtros['periodo']);
        }

        if (isset($filtros['subperfil']) && !empty($filtros['subperfil']))
        {
            $this->db->where('H.id_grupo_categoria', $filtros['subperfil']);
        }
        if (isset($filtros['nivel']) && !empty($filtros['nivel']))
        {
            $this->db->where('B.nivel_atencion', $filtros['nivel']);
        }
        if (isset($filtros['tipo_curso']) && !empty($filtros['tipo_curso']))
        {
            $this->db->where('E.id_tipo_curso', $filtros['tipo_curso']);
        }
        if (isset($filtros['tipo_unidad']) && !empty($filtros['tipo_unidad']))
        {
            $this->db->where('B.id_tipo_unidad', $filtros['tipo_unidad']);
        }

        $this->db->order_by(1);
        $this->db->stop_cache();
        $datos = [];

        if ($delegacion != 'PROMEDIO')
        {
            if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 0)
            {
                $select = array('A.nombre  nombre',
                    'sum("C".cantidad_alumnos_certificados) aprobados',
                    'sum("C".cantidad_alumnos_inscritos) inscritos',
                    'sum("C".cantidad_no_accesos) no_acceso');

                $this->db->where('A.id_delegacion', $delegacion);
                $this->db->group_by('A.nombre');
            } else
            {
                $select = array('A.nombre_grupo_delegacion nombre',
                    'sum("C".cantidad_alumnos_certificados) aprobados',
                    'sum("C".cantidad_alumnos_inscritos) inscritos',
                    'sum("C".cantidad_no_accesos) no_acceso');

                $this->db->where('A.grupo_delegacion', $delegacion);
                $this->db->group_by('A.nombre_grupo_delegacion');
            }
        } else
        {
            $select = array("'Promedio' nombre",
                'sum("C".cantidad_alumnos_certificados)/' . $participantes . ' aprobados',
                'sum("C".cantidad_alumnos_inscritos)/' . $participantes . ' inscritos',
                'sum("C".cantidad_no_accesos)/' . $participantes . ' no_acceso');
        }
        $this->db->select($select);
        $datos = $this->db->get('catalogos.delegaciones A')->result_array();
//                pr($this->db->last_query());
        $this->db->reset_query();
        $this->db->flush_cache();
        if (count($datos) == 0)
        {
            if (is_numeric($delegacion))
            {
                $opciones = array(
                    'llave' => 'id_delegacion',
                    'valor' => 'nombre',
                    'condicion' => array('id_delegacion' => $delegacion)
                );
            } else
            {
                $opciones = array(
                    'llave' => 'grupo_delegacion',
                    'valor' => 'nombre_grupo_delegacion',
                    'condicion' => array('grupo_delegacion' => $delegacion)
                );
            }
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $catalogo = $cat_list->obtener_catalogos(array(
                Catalogo_listado::DELEGACIONES => $opciones
            ));
//            pr($this->db->last_query());
//            pr($catalogo);
            $datos['nombre'] = isset($catalogo['delegaciones'][$delegacion])?$catalogo['delegaciones'][$delegacion]:'Falla en el filtro';
            $datos['aprobados'] = 0;
            $datos['inscritos'] = 0;
            $datos['no_acceso'] = 0;
        } else
        {
            $datos = $datos[0];
        }

        $this->db->reset_query();
        $this->db->flush_cache();
//        pr($datos);
        return $datos;
    }

    public function get_comparar_unidad($filtros = [])
    {
        $datos = [];
        $datos['dato1'] = $this->get_data_unidad($filtros['unidad1'], $filtros);
        $datos['dato2'] = $this->get_data_unidad($filtros['unidad2'], $filtros);
        return $datos;
    }

    public function get_data_unidad($unidad = 0, $filtros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $participantes = $this->get_numero_participantes_unidad($filtros);
        $this->db->start_cache();
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', "C.id_unidad_instituto = B.id_unidad_instituto and B.anio = {$filtros['periodo']}", 'inner');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'inner');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'inner');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'inner');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=E.id_tipo_curso AND tc.activo');
        $this->db->join('catalogos.categorias I', 'I.id_categoria = C.id_categoria', 'LEFT');
        $this->db->join('catalogos.grupos_categorias H', 'H.id_grupo_categoria = I.id_grupo_categoria', 'inner');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=H.id_subcategoria AND sub.activa', 'inner');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'inner');
        $this->db->where("(t.grupo_tipo != 'UMAE' AND t.grupo_tipo != 'CUMAE')");
        $this->db->where('CI.activa', true);
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $inicio = $filtros['periodo'] . '/01/01';
            $fin = $filtros['periodo'] . '/12/31';
            $this->db->where('E.anio', $filtros['periodo']);
        }

        if (isset($filtros['subperfil']) && !empty($filtros['subperfil']))
        {
            $this->db->where('H.id_grupo_categoria', $filtros['subperfil']);
        }
        if (isset($filtros['nivel']) && !empty($filtros['nivel']))
        {
            $this->db->where('B.nivel_atencion', $filtros['nivel']);
        }
        if (isset($filtros['tipo_curso']) && !empty($filtros['tipo_curso']))
        {
            $this->db->where('E.id_tipo_curso', $filtros['tipo_curso']);
        }
        if (isset($filtros['tipo_unidad']) && !empty($filtros['tipo_unidad']))
        {
            $this->db->where('B.id_tipo_unidad', $filtros['tipo_unidad']);
        }

        $this->db->order_by(1);
        $this->db->stop_cache();
        $datos = [];

        if ($unidad != '0')
        {
            $select = array('B.nombre  nombre',
                'sum("C".cantidad_alumnos_certificados) aprobados',
                'sum("C".cantidad_alumnos_inscritos) inscritos',
                'sum("C".cantidad_no_accesos) no_acceso');
            $this->db->where('B.clave_unidad', $unidad);
            $this->db->group_by('B.nombre');
        } else
        {            
            $select = array("'Promedio'  nombre",
                'sum("C".cantidad_alumnos_certificados)/' . $participantes . ' aprobados',
                'sum("C".cantidad_alumnos_inscritos)/' . $participantes . ' inscritos',
                'sum("C".cantidad_no_accesos)/' . $participantes . ' no_acceso');
        }

        $this->db->select($select);
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
//        pr($this->db->last_query());
        $this->db->reset_query();
        $this->db->flush_cache();
        if (count($datos) == 0)
        {

            $opciones = array(
                'llave' => 'clave_unidad',
                'valor' => 'nombre',
                'condicion' => "(grupo_tipo_unidad != 'UMAE' and grupo_tipo_unidad != 'CUMAE') and anio = {$filtros['periodo']} and clave_unidad = '{$unidad}'",
                'group' => array('id_unidad_instituto', 'nombre')
            );

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $catalogo = $cat_list->obtener_catalogos(array(
                Catalogo_listado::UNIDADES_INSTITUTO => $opciones
            ));
//            pr($this->db->last_query());
//            pr($catalogo);
            $datos['nombre'] = isset($catalogo['unidades_instituto'][$unidad]) ? $catalogo['unidades_instituto'][$unidad] : '';
            $datos['aprobados'] = 0;
            $datos['inscritos'] = 0;
            $datos['no_acceso'] = 0;
        } else
        {
            $datos = $datos[0];
        }

        $this->db->reset_query();
        $this->db->flush_cache();
//        pr($datos);
        return $datos;
    }

    public function get_comparar_umae($filtros = [])
    {
        $datos = [];
        $datos['dato1'] = $this->get_data_umae($filtros['unidad1'], $filtros);
        $datos['dato2'] = $this->get_data_umae($filtros['unidad2'], $filtros);
        return $datos;
    }

    public function get_data_umae($umae = 0, $filtros = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $participantes = $this->get_numero_participantes_umae($filtros);
        $this->db->start_cache();
        $this->db->join('hechos.hechos_implementaciones_alumnos C ', "C.id_unidad_instituto = B.id_unidad_instituto and B.anio = {$filtros['periodo']}", 'inner');
        $this->db->join('sistema.cargas_informacion CI', 'CI.id_carga_informacion = C.id_carga_informacion', 'inner');
        $this->db->join('catalogos.implementaciones D', 'D.id_implementacion = C.id_implementacion', 'inner');
        $this->db->join('catalogos.cursos E ', ' E.id_curso = D.id_curso', 'inner');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=E.id_tipo_curso AND tc.activo');
        $this->db->join('catalogos.categorias I', 'I.id_categoria = C.id_categoria', 'left');
        $this->db->join('catalogos.grupos_categorias H', 'H.id_grupo_categoria = I.id_grupo_categoria', 'inner');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=H.id_subcategoria AND sub.activa', 'inner');
        $this->db->join('catalogos.tipos_unidades t', 'B.id_tipo_unidad = t.id_tipo_unidad', 'inner');
        $this->db->where("(t.grupo_tipo = 'UMAE' OR t.grupo_tipo = 'CUMAE')");
        $this->db->where('CI.activa', true);
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $inicio = $filtros['periodo'] . '/01/01';
            $fin = $filtros['periodo'] . '/12/31';
            $this->db->where('E.anio', $filtros['periodo']);
        }

        if (isset($filtros['subperfil']) && !empty($filtros['subperfil']))
        {
            $this->db->where('H.id_grupo_categoria', $filtros['subperfil']);
        }
        if (isset($filtros['nivel']) && !empty($filtros['nivel']))
        {
            $this->db->where('B.nivel_atencion', $filtros['nivel']);
        }
        if (isset($filtros['tipo_curso']) && !empty($filtros['tipo_curso']))
        {
            $this->db->where('E.id_tipo_curso', $filtros['tipo_curso']);
        }
        if (isset($filtros['tipo_unidad']) && !empty($filtros['tipo_unidad']))
        {
            //$this->db->where('B.id_tipo_unidad', $filtros['tipo_unidad']);
        }

        $this->db->order_by(1);
        $this->db->stop_cache();
        $datos = [];

        if ($umae != 'PROMEDIO')
        {
            if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 0)
            {
                $select = array('B.nombre  nombre',
                    'sum("C".cantidad_alumnos_certificados) aprobados',
                    'sum("C".cantidad_alumnos_inscritos) inscritos',
                    'sum("C".cantidad_no_accesos) no_acceso');
                $this->db->where('B.id_unidad_instituto', $umae);
                $this->db->group_by('B.nombre');
            } else
            {
                $select = array('B.nombre_unidad_principal nombre',
                    'sum("C".cantidad_alumnos_certificados) aprobados',
                    'sum("C".cantidad_alumnos_inscritos) inscritos',
                    'sum("C".cantidad_no_accesos) no_acceso');

                $this->db->where('B.nombre_unidad_principal', $umae);
                $this->db->group_by('B.nombre_unidad_principal');
            }
        } else
        {
            $select = array("'Promedio' nombre",
                'sum("C".cantidad_alumnos_certificados)/' . $participantes . ' aprobados',
                'sum("C".cantidad_alumnos_inscritos)/' . $participantes . ' inscritos',
                'sum("C".cantidad_no_accesos)/' . $participantes . ' no_acceso');
        }
        $this->db->select($select);
        $datos = $this->db->get('catalogos.unidades_instituto B')->result_array();
//                pr($this->db->last_query());
        $this->db->reset_query();
        $this->db->flush_cache();
        if (count($datos) == 0)
        {
            if ($filtros['agrupamiento'] == 0)
            {
                $opciones = array(
                    'llave' => 'id_unidad_instituto',
                    'valor' => 'nombre',
                    'condicion' => "(grupo_tipo_unidad = 'UMAE' or grupo_tipo_unidad = 'CUMAE') and anio = {$filtros['periodo']} and id_unidad_instituto = {$umae}",
                    'group' => array('id_unidad_instituto', 'nombre')
                );
            } else
            {
                $opciones = array(
                    'llave' => 'nombre_unidad_principal',
                    'valor' => 'nombre_unidad_principal',
                    'condicion' => "grupo_tipo_unidad = 'UMAE' and anio = {$filtros['periodo']} and nombre_unidad_principal = '{$umae}'",
                    'group' => array('nombre_unidad_principal'),
                    'orden' => 1
                );
            }
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $catalogo = $cat_list->obtener_catalogos(array(
                Catalogo_listado::UNIDADES_INSTITUTO => $opciones
            ));
//            pr($this->db->last_query());
//            pr($catalogo);
            $datos['nombre'] = isset($catalogo['unidades_instituto'][$umae])? $catalogo['unidades_instituto'][$umae]: 'Filtro inválido';
            $datos['aprobados'] = 0;
            $datos['inscritos'] = 0;
            $datos['no_acceso'] = 0;
        } else
        {
            $datos = $datos[0];
        }

        $this->db->reset_query();
        $this->db->flush_cache();
//        pr($datos);
        return $datos;
    }

    /*
     * @Author: Mr. Guag
     * @Version: 1.0
     * @Description: Esta función realiza una comparativa entre regiones, dependiendo de los parametros asignados
     * @param {int} tipo_reporte - Recibe como parámetro una de las constantes TIPO_CURSO o PERFIL(por default)
     * @param {int} anio - Año de la comparativa
     * @param {int} id - clave del perfil o tipo de curso, según aplique
     * @return: {array} Comparativa de regiones
     */

    function get_comparativa_region($id = null, $anio = 2016, $tipo_reporte = Self::PERFIL, $umae = 0)
    {
        if (is_null($id))
        {
            return false;
        }
        if ($tipo_reporte == Self::TIPO_CURSO)
        {
            $select = ",ct.id_tipo_curso,ct.tipo_curso";
            $where = "WHERE cur.id_tipo_curso = $id";
            $group = ",ct.id_tipo_curso, ct.tipo_curso";
        } else
        {
            $select = ",per.id_grupo_categoria id_perfil,per.descripcion perfil";
            $where = " WHERE per.id_grupo_categoria = $id";
            $group = ",per.id_grupo_categoria, per.nombre";
        }
        if ($umae == 1)
        {
            $where .= " and (t.grupo_tipo = 'UMAE' OR t.grupo_tipo = 'CUMAE')";
        } else
        {
            $where .= " and (t.grupo_tipo != 'UMAE' AND t.grupo_tipo != 'CUMAE')";
        }
        $where .= ' and del.id_region is not null and imp.fecha_inicio >= $$' . $anio . '/01/01$$ and imp.fecha_fin <= $$' . $anio . '/12/31$$  AND cur.anio = ' . $anio;
        $where .= ' and CI.activa ';
        $query = "select
        sum(himp.cantidad_alumnos_inscritos) inscritos,
        sum(himp.cantidad_alumnos_certificados) aprobados,
        sum(himp.cantidad_alumnos_inscritos) - sum(himp.cantidad_alumnos_certificados) suspendidos,        
        sum(himp.cantidad_no_accesos) nunca_entraron,        
        trunc(sum(himp.cantidad_alumnos_certificados)/(sum(himp.cantidad_alumnos_inscritos)-sum(himp.cantidad_no_accesos))::float*100) etm,
        del.id_region,reg.nombre region                
        from hechos.hechos_implementaciones_alumnos himp
        inner join sistema.cargas_informacion CI on CI.id_carga_informacion = himp.id_carga_informacion
         left join catalogos.implementaciones imp ON(imp.id_implementacion = himp.id_implementacion)
         left join catalogos.cursos cur ON(cur.id_curso = imp.id_curso)         
         left join catalogos.unidades_instituto unit ON(unit.id_unidad_instituto = himp.id_unidad_instituto)
         left join catalogos.delegaciones del ON(del.id_delegacion = unit.id_delegacion)
         left join catalogos.regiones reg ON(reg.id_region = del.id_region)
         left join catalogos.categorias cat ON(cat.id_categoria = himp.id_categoria)
         left join catalogos.grupos_categorias per ON(per.id_grupo_categoria = cat.id_grupo_categoria)
         inner join catalogos.tipos_unidades t on unit.id_tipo_unidad = t.id_tipo_unidad
          $where
        group by del.id_region, region
        order by 1,3 asc";

        $result = $this->db->query($query);
        $regiones = $result->result_array();
//        pr($this->db->last_query());
        unset($result);
        $this->db->start_cache();
        $this->db->stop_cache();
        $this->db->flush_cache();
        return $regiones;
    }

    /*
     * @Author: Mr. Guag
     * @Version: 1.0
     * @Description: Esta función realiza una comparativa entre regiones, dependiendo de los parametros asignados
     * @param {int} tipo_reporte - Recibe como parámetro una de las constantes TIPO_CURSO o PERFIL(por default)
     * @param {int} anio - Año de la comparativa
     * @param {int} id - clave del perfil o tipo de curso, según aplique
     * @param {int} region - clave de region 
     * @return: Comparativa de delegaciones
     */

    function get_comparativa_delegacion($id = null, $anio = 2016, $tipo_reporte = Self::PERFIL, $region = 0, $umae = null)
    {
        if (is_null($id))
        {
            return false;
        }

        //filtros

        $where = " WHERE  unit.nivel_atencion in (1,2)";
        if ($tipo_reporte == Self::TIPO_CURSO)
        {
            $select = ",ct.id_tipo_curso,ct.tipo_curso";
            $where .= " AND cur.id_tipo_curso = $id";
            $group = ",ct.id_tipo_curso, ct.tipo_curso";
        } else
        {
            $select = ",per.id_grupo_categoria id_perfil,per.descripcion perfil";
            $where .= " AND per.id_grupo_categoria = $id";
            $group = ",per.id_grupo_categoria, per.nombre";
        }
        $where .= $region > 0 ? " AND del.id_region = $region" : "";
        if ($umae != null)
        {
            $where .= ' and unit.umae = ' . $umae . ' ';
        }

        $query = "select
        sum(himp.cantidad_alumnos_inscritos) inscritos,
        sum(himp.cantidad_alumnos_certificados) aprobados,
        sum(himp.cantidad_alumnos_inscritos) - sum(himp.cantidad_alumnos_certificados) suspendidos,
        sum(himp.cantidad_alumnos_inscritos) - sum(himp.cantidad_alumnos_certificados) -  sum(himp.cantidad_no_accesos) no_aprobados,
        sum(himp.cantidad_no_accesos) nunca_entraron,
        trunc(sum(himp.cantidad_alumnos_certificados)/(sum(himp.cantidad_alumnos_inscritos)-sum(himp.cantidad_no_accesos))::float*100) etm,
        del.clave_delegacional id_del, del.nombre delegacion
        $select
        ,EXTRACT(year FROM imp.fecha_inicio) anio
        from hechos.hechos_implementaciones_alumnos himp
         left join catalogos.implementaciones imp ON(imp.id_implementacion = himp.id_implementacion and EXTRACT(year FROM imp.fecha_inicio)  = $anio)
         left join catalogos.cursos cur ON(cur.id_curso = imp.id_curso)
         left join catalogos.curso_tipo ct ON(ct.id_tipo_curso = cur.id_tipo_curso)
         left join catalogos.unidades_instituto unit ON(
            unit.id_unidad_instituto = himp.id_unidad_instituto)
         left join catalogos.delegaciones del ON(del.id_delegacion = unit.id_delegacion)
         left join catalogos.categorias cat ON(cat.id_categoria = himp.id_categoria)
         left join catalogos.grupos_categorias per ON(per.id_grupo_categoria = cat.id_grupo_categoria)        
          $where
        group by id_del, delegacion $group , anio
        order by id_del,3 asc";

        $result = $this->db->query($query);
        $delegaciones = $result->result_array();
        unset($result);
        $this->db->start_cache();
        $this->db->stop_cache();
        $this->db->flush_cache();
        return $delegaciones;
    }

    private function get_numero_participantes_delegacion($filtros)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 0)
        {
            $this->db->select('count(*) cantidad');
        } else
        {
            $this->db->select('count(distinct grupo_delegacion) cantidad');
        }
        $participantes = $this->db->get('catalogos.delegaciones')->result_array()[0]['cantidad'];
        $this->db->flush_cache();
        $this->db->reset_query();
        return $participantes;
    }

    private function get_numero_participantes_umae($filtros)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        if (isset($filtros['agrupamiento']) && $filtros['agrupamiento'] == 0)
        {
            $this->db->where("(grupo_tipo_unidad = 'UMAE' OR grupo_tipo_unidad = 'CUMAE')");
        } else
        {
            $this->db->where("(grupo_tipo_unidad = 'UMAE')");
        }
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $inicio = $filtros['periodo'] . '/01/01';
            $fin = $filtros['periodo'] . '/12/31';
            $this->db->where('anio', $filtros['periodo']);
        }
        $participantes = $this->db->get('catalogos.unidades_instituto')->result_array()[0]['cantidad'];
        $this->db->flush_cache();
        $this->db->reset_query();
        return $participantes;
    }

    private function get_numero_participantes_unidad($filtros)
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('count(*) cantidad');
        $this->db->where("(grupo_tipo_unidad != 'UMAE' AND grupo_tipo_unidad != 'CUMAE')");        
        if (isset($filtros['periodo']) && !empty($filtros['periodo']))
        {
            $inicio = $filtros['periodo'] . '/01/01';
            $fin = $filtros['periodo'] . '/12/31';
            $this->db->where('B.anio', $filtros['periodo']);
        }
       
        if (isset($filtros['nivel']) && !empty($filtros['nivel']))
        {
            $this->db->where('B.nivel_atencion', $filtros['nivel']);
        }
        
        if (isset($filtros['tipo_unidad']) && !empty($filtros['tipo_unidad']))
        {
            $this->db->where('B.id_tipo_unidad', $filtros['tipo_unidad']);
        }
        $participantes = $this->db->get('catalogos.unidades_instituto B')->result_array()[0]['cantidad'];
        $this->db->flush_cache();
        $this->db->reset_query();
        return $participantes;
    }

}
