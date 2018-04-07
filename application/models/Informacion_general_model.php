<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para obtener datos a mostrar en la sección de información general
 * @version 	: 1.0.0
 * @autor 		: JZDP
 */
class Informacion_general_model extends CI_Model
{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->config('general');
    }

    /**
     * El metodo obtiene el total de alumnos inscritos
     * @author JZDP
     * @param  array $params Se forma de los campos que se desea obtener, las condicionales en la búsqueda y el tipo y campo utilziados para el ordenamiento
     * @return array $resultado Contiene arreglo con los datos obtenidos de la base
     */
    public function calcular_totales($params = array()){
        $resultado = array();
        //Condiciones utilizadas en informacion_general/index
        if(isset($params['perfil']) AND !empty($params['perfil'])){
            $this->db->where('sub.id_subcategoria='.$params['perfil']);
        }        
        if(isset($params['tipo_curso']) AND !empty($params['tipo_curso'])){
            $this->db->where('tc.id_tipo_curso='.$params['tipo_curso']);
        }
        if(isset($params['tipo_unidad']) AND !empty($params['tipo_unidad'])){
            $this->db->where('uni.id_tipo_unidad='.$params['tipo_unidad']);
        }        
        if(isset($params['periodo']) AND !empty($params['periodo'])){
            $this->db->where('imp.anio='.$params['periodo']);
        }
        if(isset($params['nivel_atencion']) AND is_numeric($params['nivel_atencion'])){
            if($params['nivel_atencion']==0){//Agregar condicional para nivel de atención no asignado
                $this->db->where('uni.nivel_atencion IS NULL');
            } else {
                $this->db->where('uni.nivel_atencion='.$params['nivel_atencion']);
            }
        }
        if(isset($params['region']) AND !empty($params['region'])){
            $this->db->where('reg.id_region='.$params['region']);
        }
        $tb = $this->config->item('tipos_busqueda');
        if(isset($params['tipos_busqueda']) AND !empty($params['tipos_busqueda'])){
            switch ($params['tipos_busqueda']) {
                case $tb['DELEGACION']['id']:
                    //$this->db->where('uni.umae=false');
                    $this->db->where("uni.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
                    if(isset($params['delegacion']) AND !empty($params['delegacion'])){
                        if(isset($params['agrupamiento']) AND $params['agrupamiento']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                            $this->db->where("del.id_delegacion='".$params['delegacion']."'");
                        } else {
                            $this->db->where("del.grupo_delegacion='".$params['delegacion']."'");
                        }
                    }
                    if(isset($params['unidad']) AND !empty($params['unidad'])){
                        $this->db->where('uni.id_unidad_instituto='.$params['unidad']);
                    }
                    if(isset($params['tipo_grafica']) AND $params['tipo_grafica']==$tb['PERFIL']['id']) {
                        $this->db->order_by('subcategoria_orden, grupo_categoria_orden');
                    } elseif(isset($params['tipo_grafica']) AND $params['tipo_grafica']==$tb['TIPO_CURSO']['id']) {
                        $this->db->order_by('tipo_curso');
                    } else {
                        if(isset($params['agrupamiento']) AND $params['agrupamiento']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                            $this->db->order_by('del.nombre');
                            $this->db->order_by('del.nombre_grupo_delegacion');
                        } else {
                        }
                    }
                    break;
                case $tb['NIVEL_ATENCION']['id']:
                    $this->db->order_by('nivel_atencion');
                    break;
                case $tb['PERFIL']['id']:
                    $this->db->order_by('sub.order, gc.order');
                    break;
                /*case $tb['PERIODO']['id']:
                    
                    break;*/
                case $tb['REGION']['id']:
                    $this->db->order_by('reg.nombre');
                    break;
                case $tb['TIPO_CURSO']['id']:
                    $this->db->order_by('tc.nombre');
                    break;
                case $tb['UMAE']['id']:
                    //$this->db->where('uni.umae=true');
                    $this->db->where("uni.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
                    if(isset($params['umae']) AND !empty($params['umae'])){
                        if(isset($params['agrupamiento_umae']) AND $params['agrupamiento_umae']==$this->config->item('agrupamiento')['AGRUPAR']['id']){
                            $this->db->where("uni.unidad_principal IN ('".$params['umae']."')");
                        } else {
                            $this->db->where('uni.id_unidad_instituto='.$params['umae']);
                        }
                    }
                    if(isset($params['order_include']) AND $params['order_include']===false){
                        ///$this->db->order_by('1 asc');
                    } else {
                        if(isset($params['tipo_grafica']) AND $params['tipo_grafica']==$tb['PERFIL']['id']) {
                            $this->db->order_by('subcategoria_orden, grupo_categoria_orden');
                        } elseif(isset($params['tipo_grafica']) AND $params['tipo_grafica']==$tb['TIPO_CURSO']['id']) {
                            $this->db->order_by('tipo_curso');
                        } else {
                            if(isset($params['agrupamiento_umae']) AND $params['agrupamiento_umae']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                                $this->db->order_by('uni.nombre');
                            } else {                            
                                $this->db->order_by('uni.nombre_unidad_principal');
                            }
                        }
                    }
                    break;
            }
        }
        //Condiciones utilizadas en informacion_general/perfil
        if(isset($params['anio']) AND !empty($params['anio'])){
            $this->db->where('imp.anio='.$params['anio']);
        }
        //pr($params);
        if(isset($params['perfil_seleccion']) AND !empty($params['perfil_seleccion']) AND $params['perfil_seleccion']>0){
            $this->db->where('gc.id_grupo_categoria IN ('.$params['perfil_seleccion'].')');
        }
        if(isset($params['tipo_curso_seleccion']) AND !empty($params['tipo_curso_seleccion']) AND $params['tipo_curso_seleccion']>0){
            $this->db->where('tc.id_tipo_curso IN ('.$params['tipo_curso_seleccion'].')');
        }

        //Periodo
        $periodo = '';
        if(isset($params['periodo_seleccion']) AND !empty($params['periodo_seleccion']) AND !isset($params['destino'])){
            $per = $this->config->item('periodo');
            //pr($per);
            //echo $params['periodo_seleccion']." - ".$per['SEMESTRAL']['id'];
            if($params['periodo_seleccion']==$per['SEMESTRAL']['id']){
               $periodo = ", (CASE WHEN date_part('month', fecha_fin) <= 6 THEN 'Enero-Junio' ELSE 'Julio-Diciembre' END) as periodo, (CASE WHEN date_part('month', fecha_fin) <= 6 THEN 1 ELSE 2 END) as periodo_id";
            } elseif($params['periodo_seleccion']==$per['TRIMESTRAL']['id']){
                //pr('entro');
                $periodo = ", EXTRACT(quarter FROM fecha_fin) as periodo_id, 
                    (CASE WHEN EXTRACT(quarter FROM fecha_fin) = 1 THEN 'Enero-Marzo'
                    WHEN EXTRACT(quarter FROM fecha_fin) = 2 THEN 'Abril-Junio'
                    WHEN EXTRACT(quarter FROM fecha_fin) = 3 THEN 'Julio-Septiembre' ELSE 'Octubre-Diciembre' END) as periodo";
            } elseif($params['periodo_seleccion']==$per['BIMESTRAL']['id']){
                $periodo = ", (EXTRACT(month FROM fecha_fin)/2+.1):: Integer as periodo_id, 
                    (CASE WHEN (EXTRACT(month FROM fecha_fin)/2+.1):: Integer = 1 THEN 'Enero-Febrero'
                    WHEN (EXTRACT(month FROM fecha_fin)/2+.1):: Integer = 2 THEN 'Marzo-Abril'
                    WHEN (EXTRACT(month FROM fecha_fin)/2+.1):: Integer = 3 THEN 'Mayo-Junio'
                    WHEN (EXTRACT(month FROM fecha_fin)/2+.1):: Integer = 4 THEN 'Julio-Agosto'
                    WHEN (EXTRACT(month FROM fecha_fin)/2+.1):: Integer = 5 THEN 'Septiembre-Octubre' ELSE 'Noviembre-Diciembre' END) as periodo";
            } elseif($params['periodo_seleccion']==$per['MENSUAL']['id']){
                $periodo = ", EXTRACT(month FROM fecha_fin) as periodo_id,
                    (CASE WHEN EXTRACT(month FROM fecha_fin) = 1 THEN 'Enero'
                    WHEN EXTRACT(month FROM fecha_fin) = 2 THEN 'Febrero'
                    WHEN EXTRACT(month FROM fecha_fin) = 3 THEN 'Marzo' 
                    WHEN EXTRACT(month FROM fecha_fin) = 4 THEN 'Abril' 
                    WHEN EXTRACT(month FROM fecha_fin) = 5 THEN 'Mayo' 
                    WHEN EXTRACT(month FROM fecha_fin) = 6 THEN 'Junio' 
                    WHEN EXTRACT(month FROM fecha_fin) = 7 THEN 'Julio' 
                    WHEN EXTRACT(month FROM fecha_fin) = 8 THEN 'Agosto' 
                    WHEN EXTRACT(month FROM fecha_fin) = 9 THEN 'Septiembre' 
                    WHEN EXTRACT(month FROM fecha_fin) = 10 THEN 'Octubre' 
                    WHEN EXTRACT(month FROM fecha_fin) = 11 THEN 'Noviembre' 
                    ELSE 'Diciembre' END) as periodo";
            } /* else {
                $this->db->where('EXTRACT(YEAR FROM imp.fecha_fin)='.$params['periodo_seleccion'].' as periodo');
            }*/
            $this->db->order_by('periodo_id');
        }
        if(!isset($params['calcular_totales_unidad']) || (isset($params['calcular_totales_unidad']) && $params['calcular_totales_unidad']!=true)){
            $this->load->library('Configuracion_grupos');
            $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
            $configuracion = $this->configuracion_grupos->obtener_tipos_busqueda($datos['lenguaje']);
            if(!empty($configuracion['condicion_calcular_totales'])){
                $this->db->where($configuracion['condicion_calcular_totales']);
            }
        }
        //pr($periodo);
        //$this->db->limit('500');
        if(isset($params['fields']) AND !empty($params['fields'])) { //En caso de que se envien valores a través desde el controlador
            if (array_key_exists('fields', $params)) {
                $this->db->select($params['fields']);
            }
        } else {
            if(isset($params['destino']) AND !empty($params['destino'])){ ///Se utiliza para construir los listados (tree) de vista por_perfil y por_tipo_curso
                if($params['destino']==$tb['PERFIL']['id']){
                    $this->db->order_by('subcategoria_orden, grupo_categoria_orden');
                } else {
                    $this->db->order_by($params['destino']);
                }
                $this->db->select('gc.id_grupo_categoria, gc.descripcion as grupo_categoria, sub.id_subcategoria, sub.nombre as perfil, cur.id_tipo_curso, tc.nombre as tipo_curso, sub.order as subcategoria_orden, gc.order as grupo_categoria_orden');
                $this->db->group_by('gc.id_grupo_categoria, gc.descripcion, sub.id_subcategoria, sub.nombre, cur.id_tipo_curso, tc.nombre');
            } else {
                $this->db->select('imp.id_curso, imp.fecha_fin,EXTRACT(MONTH FROM imp.fecha_fin) mes_fin, 
                    imp.anio anio_fin, reg.id_region, reg.nombre as region, cur.id_tipo_curso, tc.nombre as tipo_curso,
                    del.id_delegacion, del.nombre as delegacion, uni.id_unidad_instituto, uni.clave_unidad, uni.nombre as unidades_instituto, uni.umae, uni.grupo_tipo_unidad,
                    hia.id_categoria, gc.id_grupo_categoria, gc.descripcion as grupo_categoria, gc.order as grupo_categoria_orden, sub.id_subcategoria, sub.nombre as perfil, sub.order as perfil_orden,
                    hia.id_unidad_instituto, hia.id_implementacion, hia.cantidad_alumnos_inscritos, hia.cantidad_alumnos_certificados, 
                    case when uni.nivel_atencion=1 then \'Primer nivel\' when uni.nivel_atencion=2 then \'Segundo nivel\' when uni.nivel_atencion=3 then \'Tercer nivel\' else \'Nivel no disponible\' end as nivel_atencion,
                    sub.order as subcategoria_orden, gc.order as grupo_categoria_orden,
                    COALESCE(hia.cantidad_no_accesos, 0) as cantidad_no_accesos,
                    (hia.cantidad_alumnos_inscritos-hia.cantidad_alumnos_certificados-COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_aprobados'.$periodo);
            } //mes.nombre as mes,
        }
        if (array_key_exists('group', $params)) {
            $this->db->group_by($params['group']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']);
        }

        //$this->db->join('hechos.accesos_implemetaciones no_acc', 'no_acc.id_unidad_instituto=hia.id_unidad_instituto AND no_acc.id_implementacion=hia.id_implementacion AND no_acc.id_categoria=hia.id_categoria AND no_acc.id_sexo=hia.id_sexo', 'left');
        $this->db->join('catalogos.implementaciones imp', 'imp.id_implementacion=hia.id_implementacion');
        //$this->db->join('catalogos.meses mes', 'mes.id_mes=EXTRACT(MONTH FROM imp.fecha_fin)');
        $this->db->join('catalogos.cursos cur', 'cur.id_curso=imp.id_curso');
        $this->db->join('catalogos.tipos_cursos tc', 'tc.id_tipo_curso=cur.id_tipo_curso AND tc.activo=CAST(1 as boolean)');
        $this->db->join('catalogos.unidades_instituto uni', 'uni.id_unidad_instituto=hia.id_unidad_instituto AND uni.anio='.$params['anio'], 'left');
        $this->db->join('catalogos.delegaciones del', 'del.id_delegacion=uni.id_delegacion', 'left');
        //$this->db->join('catalogos.regiones reg', 'reg.id_region=del.id_region', 'left');
        $this->db->join('catalogos.regiones reg', 'reg.id_region=del.id_region');
        $this->db->join('catalogos.categorias cat', 'cat.id_categoria=hia.id_categoria', 'left');
        $this->db->join('catalogos.grupos_categorias gc', 'gc.id_grupo_categoria=cat.id_grupo_categoria AND gc.activa=CAST(1 as boolean)', 'left');
        //$this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=gc.id_subcategoria AND sub.activa=CAST(1 as boolean)', 'left');
        $this->db->join('catalogos.subcategorias sub', 'sub.id_subcategoria=gc.id_subcategoria AND sub.activa=CAST(1 as boolean)');
        //$this->db->limit(50);
        $query = $this->db->get('hechos.hechos_implementaciones_alumnos hia'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query()); pr($params); //exit();
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function obtener_listado_subcategorias($params = array()){
        $resultado = array();
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']);
        }
        $this->db->join('grupos_categorias gc', 'gc.id_subcategoria=sub.id_subcategoria AND gc.activa=CAST(1 as boolean)', 'left');
        $query = $this->db->get('subcategorias sub'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function obtener_listado_unidad_umae($params = array()){
        $resultado = array();
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('group', $params)) {
            $this->db->group_by($params['group']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']);
        }
        $this->db->join('catalogos.delegaciones del', 'del.id_delegacion=ins.id_delegacion');
        $this->db->join('catalogos.tipos_unidades tipo_uni', 'tipo_uni.id_tipo_unidad=ins.id_tipo_unidad');
        $query = $this->db->get('catalogos.unidades_instituto ins'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function obtener_listado_cursos($params = array()){
        $resultado = array();
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('group', $params)) {
            $this->db->group_by($params['group']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']);
        }
        $this->db->join('catalogos.implementaciones imp', 'imp.id_curso=cur.id_curso');
        
        $query = $this->db->get('catalogos.cursos cur'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }

    public function obtener_cargas_informacion($params = array()){
        $resultado = array();
        if (array_key_exists('fields', $params)) {
            $this->db->select($params['fields']);
        }
        if (array_key_exists('conditions', $params)) {
            $this->db->where($params['conditions']);
        }
        if (array_key_exists('group', $params)) {
            $this->db->group_by($params['group']);
        }
        if (array_key_exists('order', $params)) {
            $this->db->order_by($params['order']);
        }
        
        $query = $this->db->get('sistema.cargas_informacion ci'); //Obtener conjunto de registros
        $resultado = $query->result_array();
        //pr($this->db->last_query());
        $query->free_result(); //Libera la memoria

        return $resultado;
    }
}
