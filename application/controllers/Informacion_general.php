<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que genera la pantalla de información general, de los diferentes roles
 * @version : 1.0.0
 * @autor : JZDP
 */
class Informacion_general extends MY_Controller
//class Informacion_general extends CI_Controller
{
    var $anio_actual;
    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->load->library('Configuracion_grupos');
        $this->load->library('Catalogo_listado');
        $this->load->model('Informacion_general_model', 'inf_gen_model');
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->configuracion_grupos->set_periodo_actual();
    }
    
    public function index(){
        //pr($_SESSION['usuario']);
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $condicion_na = ($this->configuracion_grupos->obtener_is_umae()) ? '(nivel_atencion<>0 AND nivel_atencion=3)' : 'nivel_atencion<>0 AND nivel_atencion<>3'; ///Establecer condicionales para mostrar niveles de atención
        $nivel_atencion = $cat_list->obtener_catalogos(array(Catalogo_listado::UNIDADES_INSTITUTO=>array('llave'=>'DISTINCT(COALESCE(nivel_atencion,0))', 'valor'=>"case when nivel_atencion=1 then 'Primer nivel' when nivel_atencion=2 then 'Segundo nivel' when nivel_atencion=3 then 'Tercer nivel' else 'Nivel no disponible' end", 'orden'=>'llave', 'alias'=>'nivel_atencion', 'condicion'=>$condicion_na), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'anio', 'llave'=>'DISTINCT(anio)', 'orden'=>'llave DESC'))); //Obtener nivel de atención en otra llamada debido a que tiene el mismo indice que UMAE
        $configuracion = $this->configuracion_grupos->obtener_tipos_busqueda($datos['lenguaje']);
        $datos['catalogos'] = $cat_list->obtener_catalogos($configuracion['catalogos']); //Catalogo_listado::PERIODO        
        $datos['catalogos']+=$nivel_atencion;//Agregar arreglo de niveles de atención a los demás catálogos        
        $datos['catalogos']['tipos_busqueda'] = $configuracion['tipos_busqueda'];
        if(in_array($this->configuracion_grupos->obtener_grupo_actual(), array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN))) { //Agregar para superusuarios y administradores
            $datos['catalogos']['agrupamiento'] = dropdown_options($this->config->item('agrupamiento'), 'id', 'valor'); ///Agregar opciones para agrupamiento de delegaciones
            $datos['catalogos']['agrupamiento_umae'] = dropdown_options($this->config->item('agrupamiento'), 'id', 'valor'); ///Agregar opciones para agrupamiento de UMAEs
        }
        //pr($datos['catalogos']);

        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/index.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function inicio(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'anio', 'llave'=>'DISTINCT(anio)', 'orden'=>'llave DESC'),Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'))); //Obtener nivel de atención en otra llamada debido a que tiene el mismo indice que UMAE
        $datos['cargas'] = $this->inf_gen_model->obtener_cargas_informacion(array('fields'=>"to_char(MAX(fecha_carga), 'DD-MM-YYYY') as ultima_actualizacion",'conditions'=>'ci.activa=true'));
        $cursos = $this->inf_gen_model->obtener_listado_cursos(array('fields'=>'DISTINCT(cur.*)', 'conditions'=>'cur.activo=true', 'order'=>'cur.anio, cur.id_tipo_curso, cur.nombre'));
        foreach ($cursos as $key_c => $curso) {
            $datos['cursos'][$curso['anio']][$curso['id_tipo_curso']][] = $curso;
        }

        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_sistema']);
        //$this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/inicio.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_perfil(){
        $datos = array('catalogos'=>array('implementaciones'=>array(),'periodo'=>array()));
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];        
        if(!is_null($this->session->userdata('usuario'))){
            $this->load->library('Catalogo_listado'); //pr($this->config->item('periodo'));
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'anio', 'llave'=>'DISTINCT(anio)', 'orden'=>'llave DESC')));
            //pr($datos['catalogos']);
            $datos['catalogos']['periodo'] = dropdown_options($this->config->item('periodo'), 'id', 'valor');
            $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.descripcion as grupo_categoria', 'conditions'=>'sub.activa=true', 'order'=>'sub.order ASC, gc.order ASC'));
            //pr($listado_subcategorias);
            foreach ($listado_subcategorias as $key_ls => $listado) {
                $datos['catalogos']['subcategorias']['S_'.$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
                if(!empty($listado['grupo_categoria'])){
                    $datos['catalogos']['subcategorias']['S_'.$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
                }
            }
            //pr($datos);
            $this->template->setTitle($datos['lenguaje']['titulo_principal']);
            $this->template->setSubTitle($datos['lenguaje']['titulo_por_perfil'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
            $this->template->setDescripcion($this->mostrar_datos_generales());
        }
        $this->template->setMainContent($this->load->view('informacion_general/por_perfil.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_tipo_curso(){
        $datos = array('catalogos'=>array('implementaciones'=>array(),'periodo'=>array()));
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        if(!is_null($this->session->userdata('usuario'))){        
            $this->load->library('Catalogo_listado');
            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::TIPOS_CURSOS=>array('condicion'=>'activo=CAST(1 as boolean)'), Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'anio', 'llave'=>'DISTINCT(anio)', 'orden'=>'llave DESC')));
            $datos['catalogos']['periodo'] = dropdown_options($this->config->item('periodo'), 'id', 'valor');
            $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.descripcion as grupo_categoria', 'conditions'=>'sub.activa=true', 'order'=>'sub.order ASC, gc.order ASC'));
            foreach ($listado_subcategorias as $key_ls => $listado) {
                $datos['catalogos']['subcategorias']['S_'.$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
                if(!empty($listado['grupo_categoria'])){
                    $datos['catalogos']['subcategorias']['S_'.$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
                }
            }
            $this->template->setTitle($datos['lenguaje']['titulo_principal']);
            $this->template->setSubTitle($datos['lenguaje']['titulo_por_tipo_usuario'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
            $this->template->setDescripcion($this->mostrar_datos_generales());
        }
        $this->template->setMainContent($this->load->view('informacion_general/por_tipo_curso.tpl.php', $datos, true));
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function por_unidad(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::REGIONES, Catalogo_listado::IMPLEMENTACIONES=>array('valor'=>'anio', 'llave'=>'DISTINCT(anio)', 'orden'=>'llave DESC')));
        $tipos_busqueda = $this->config->item('tipo_busqueda');
        $tipo_grafica = $this->config->item('tipo_grafica');
        $datos['catalogos']['tipos_busqueda'] = array($tipos_busqueda['UMAE']['id']=>$tipos_busqueda['UMAE']['valor'], $tipos_busqueda['DELEGACION']['id']=>$tipos_busqueda['DELEGACION']['valor']);
        $datos['catalogos']['tipo_grafica'] = array($tipo_grafica['PERFIL']['id']=>$tipo_grafica['PERFIL']['valor'], $tipo_grafica['TIPO_CURSO']['id']=>$tipo_grafica['TIPO_CURSO']['valor']);
        if(in_array($this->configuracion_grupos->obtener_grupo_actual(), array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN))) { //Agregar para superusuarios y administradores
            $datos['catalogos']['agrupamiento'] = dropdown_options($this->config->item('agrupamiento'), 'id', 'valor'); ///Agregar opciones para agrupamiento de delegaciones
            $datos['catalogos']['agrupamiento_umae'] = dropdown_options($this->config->item('agrupamiento'), 'id', 'valor'); ///Agregar opciones para agrupamiento de UMAEs
        }
        //pr($datos['catalogos']);
        /*$listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }*/
        //pr($datos);
        $datos['grupo_actual'] = $this->configuracion_grupos->obtener_grupo_actual();
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_unidad'].'. '.$this->configuracion_grupos->index_obtener_subtitulo($datos['lenguaje']['titulo']));
        $this->template->setDescripcion($this->mostrar_datos_generales());
        $this->template->setMainContent($this->load->view('informacion_general/por_unidad.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }

    public function cargar_listado($tipo){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                $this->load->library('Catalogo_listado');
                $cat_list = new Catalogo_listado(); //Obtener catálogos
                $c_region = (isset($datos_busqueda['region']) AND !empty($datos_busqueda['region'])) ? " AND del.id_region=".$datos_busqueda['region'] : '';
                $c_delegacion = '';
                if(isset($datos_busqueda['delegacion']) AND !empty($datos_busqueda['delegacion'])) {
                    if(isset($datos_busqueda['agrupamiento']) && $datos_busqueda['agrupamiento']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                        $c_delegacion = ' AND del.id_delegacion='.$datos_busqueda['delegacion'];
                    } else {
                        $c_delegacion = " AND del.grupo_delegacion='".$datos_busqueda['delegacion']."'";
                    }
                }
                $c_nivel_atencion = (isset($datos_busqueda['nivel_atencion']) AND !empty($datos_busqueda['nivel_atencion'])) ? ' AND ins.nivel_atencion='.$datos_busqueda['nivel_atencion'] : '';
                $c_tipo_unidad = (isset($datos_busqueda['tipo_unidad']) AND !empty($datos_busqueda['tipo_unidad'])) ? ' AND ins.id_tipo_unidad='.$datos_busqueda['tipo_unidad'] : '';
                $resultado=array('resultado'=>false, 'datos'=>array(), 'mensaje'=>'');
                $lenguaje = $this->lang->line('interface')['informacion_general']+$this->lang->line('interface')['general'];
                $vista = 'listado.tpl.php';
                switch ($tipo) { ///Definimos 
                    case 'ud':
                        if($datos_busqueda['tipos_busqueda']=='umae'){
                            //$datos = $cat_list->obtener_catalogos(array(Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>'umae=true AND region=')));
                            //$dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion", 'conditions'=>'ins.umae=true '.$c_region.$c_tipo_unidad.' AND EXTRACT(YEAR FROM ins.fecha)='.$datos_busqueda['anio']));
                            if(isset($datos_busqueda['agrupamiento_umae']) && $datos_busqueda['agrupamiento_umae']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                                $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion", 'conditions'=>"ins.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."') ".$c_region.$c_tipo_unidad.' AND ins.anio='.$datos_busqueda['anio']));
                            } else {
                                $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"ins.unidad_principal as id_unidad_instituto, ins.nombre_unidad_principal as institucion", 'conditions'=>"ins.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."') ".$c_region.$c_tipo_unidad.' AND ins.anio='.$datos_busqueda['anio'], 'group'=>'ins.unidad_principal, ins.nombre_unidad_principal, ins.grupo_tipo_unidad'));
                            }
                            $resultado['form']['label'] = $lenguaje['umae'];
                            $resultado['form']['path'] = 'unidad';
                            $resultado['form']['evento'] = array('onchange'=>"javascript: calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');");
                            //$resultado['form']['destino'] = '#unidad_capa';
                            $resultado['datos'] = dropdown_options($dato_mod, 'id_unidad_instituto', 'institucion');
                            $resultado['resultado'] = true;
                            //$vista = 'listado_radio.tpl.php';
                            $tipo = 'umae';
                        } else {
                            $resultado['form']['label'] = $lenguaje['delegacion'];
                            $resultado['form']['path'] = 'nivel_atencion';
                            $resultado['form']['evento'] = array('onchange'=>"javascript: limpiar_capas(['tipo_unidad_capa', 'umae_capa', 'unidad_capa'], ['nivel_atencion']); data_ajax_listado(site_url+'/informacion_general/cargar_listado/".$resultado['form']['path']."', '#form_busqueda', '#".$resultado['form']['path']."_capa');");
                            //$resultado['form']['destino'] = '#tipo_unidad_capa';
                            $c_region = (isset($datos_busqueda['region']) AND !empty($datos_busqueda['region'])) ? " AND id_region=".$datos_busqueda['region'] : ''; ///Validación generada específicamente para la consulta
                            if(isset($datos_busqueda['agrupamiento']) && $datos_busqueda['agrupamiento']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                                $extra = array(Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion NOT IN ('.$this->config->item('DELEGACIONES')['SIN_DELEGACION']['id'].', '.$this->config->item('DELEGACIONES')['MANDO']['id'].') '.$c_region));
                            } else {
                                $extra = array(Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion NOT IN ('.$this->config->item('DELEGACIONES')['SIN_DELEGACION']['id'].', '.$this->config->item('DELEGACIONES')['MANDO']['id'].') '.$c_region, 'llave'=>'grupo_delegacion', 'valor'=>'nombre_grupo_delegacion',
                                    'group'=>'grupo_delegacion, nombre_grupo_delegacion', 'orden'=>'nombre_grupo_delegacion'));
                            }
                            $datos = $cat_list->obtener_catalogos($extra);
                            $resultado['datos'] = $datos['delegaciones'];
                            $resultado['resultado'] = true;
                            $tipo = 'delegacion';
                        }
                        break;
                    case 'nivel_atencion':
                        $resultado['form']['label'] = $lenguaje['nivel_atencion'];
                        $resultado['form']['path'] = 'tipo_unidad';
                        $resultado['form']['evento'] = array('onchange'=>"javascript: limpiar_capas(['umae_capa', 'unidad_capa'], ['tipo_unidad']); data_ajax_listado(site_url+'/informacion_general/cargar_listado/".$resultado['form']['path']."', '#form_busqueda', '#".$resultado['form']['path']."_capa');");
                        //$resultado['form']['destino'] = '#unidad_capa';
                        //$dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"DISTINCT(ins.nivel_atencion) as id_nivel_atencion, case when ins.nivel_atencion=1 then 'Primer nivel' when ins.nivel_atencion=2 then 'Segundo nivel' when ins.nivel_atencion=3 then 'Tercer nivel' else 'Nivel no disponible' end as nivel_atencion_nombre", 'conditions'=>'ins.umae=false '.$c_region.$c_delegacion.' AND EXTRACT(YEAR FROM ins.fecha)='.$datos_busqueda['anio'], 'order'=>'nivel_atencion_nombre'));
                        $condicion_na = ($this->configuracion_grupos->obtener_is_umae()) ? 'AND (nivel_atencion<>0 AND nivel_atencion=3)' : 'AND nivel_atencion<>0 AND nivel_atencion<>3'; ///Establecer condicionales para mostrar niveles de atención
                        $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>"DISTINCT(ins.nivel_atencion) as id_nivel_atencion, case when ins.nivel_atencion=1 then 'Primer nivel' when ins.nivel_atencion=2 then 'Segundo nivel' when ins.nivel_atencion=3 then 'Tercer nivel' else 'Nivel no disponible' end as nivel_atencion_nombre", 'conditions'=>"ins.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."') ".$condicion_na.$c_region.$c_delegacion.' AND ins.anio='.$datos_busqueda['anio'], 'order'=>'nivel_atencion_nombre'));
                        $resultado['datos'] = dropdown_options($dato_mod, 'id_nivel_atencion', 'nivel_atencion_nombre');
                        $resultado['resultado'] = true;
                        break;
                    case 'tipo_unidad':
                        $resultado['form']['label'] = $lenguaje['tipo_unidad'];
                        $resultado['form']['path'] = 'unidad';
                        if($datos_busqueda['tipos_busqueda']=='umae'){ ///Desplegar el tipo de unidad para UMAEs
                            $au = "'".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."'";
                            if(isset($datos_busqueda['agrupamiento_umae']) && $datos_busqueda['agrupamiento_umae']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                                $au = "'".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."'";
                            }
                            $resultado['form']['evento'] = array('onchange'=>"javascript: $('#capa_agrupamiento_umae').show(); limpiar_capas(['umae_capa', 'unidad_capa'], ['unidad', 'umae']); data_ajax_listado(site_url+'/informacion_general/cargar_listado/ud', '#form_busqueda', '#umae_capa');");
                            //$dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'DISTINCT(tipo_uni.id_tipo_unidad), tipo_uni.nombre as tipo_unidad', 'conditions'=>'ins.umae=true '.$c_region.' AND EXTRACT(YEAR FROM ins.fecha)='.$datos_busqueda['anio'], 'order'=>'tipo_unidad'));
                            $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'DISTINCT(tipo_uni.id_tipo_unidad), tipo_uni.nombre as tipo_unidad', 'conditions'=>"ins.grupo_tipo_unidad IN (".$au.") ".$c_region.' AND ins.anio='.$datos_busqueda['anio'], 'order'=>'tipo_unidad')); //Solo agregamos la clave de las UMAEs
                        } else {
                            $resultado['form']['evento'] = array('onchange'=>"javascript: limpiar_capas(['umae_capa', 'unidad_capa'], ['unidad', 'umae']); data_ajax_listado(site_url+'/informacion_general/cargar_listado/unidad', '#form_busqueda', '#".$resultado['form']['path']."_capa');");
                            //$dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'DISTINCT(tipo_uni.id_tipo_unidad), tipo_uni.nombre as tipo_unidad', 'conditions'=>'ins.umae=false '.$c_region.$c_delegacion.$c_nivel_atencion.' AND EXTRACT(YEAR FROM ins.fecha)='.$datos_busqueda['anio'], 'order'=>'tipo_unidad'));
                            $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'DISTINCT(tipo_uni.id_tipo_unidad), tipo_uni.nombre as tipo_unidad', 'conditions'=>"ins.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."') ".$c_region.$c_delegacion.$c_nivel_atencion.' AND ins.anio='.$datos_busqueda['anio'], 'order'=>'tipo_unidad'));
                        }
                        $resultado['datos'] = dropdown_options($dato_mod, 'id_tipo_unidad', 'tipo_unidad');
                        $resultado['resultado'] = true;
                        break;
                    case 'unidad':
                        $resultado['form']['label'] = $lenguaje['unidades'];
                        $resultado['form']['path'] = 'unidad';
                        $resultado['form']['evento'] = array('onchange'=>"javascript: calcular_totales_unidad(site_url+'/informacion_general/calcular_totales_unidad', '#form_busqueda');");
                        //$dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion', 'conditions'=>'ins.umae=false '.$c_region.$c_delegacion.$c_nivel_atencion.$c_tipo_unidad.' AND EXTRACT(YEAR FROM ins.fecha)='.$datos_busqueda['anio']));
                        $dato_mod = $this->inf_gen_model->obtener_listado_unidad_umae(array('fields'=>'ins.id_unidad_instituto, ins.clave_unidad, ins.nombre as institucion', 'conditions'=>"ins.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."') ".$c_region.$c_delegacion.$c_nivel_atencion.$c_tipo_unidad.' AND ins.anio='.$datos_busqueda['anio']));
                        $resultado['resultado'] = true;
                        $resultado['datos'] = dropdown_options($dato_mod, 'id_unidad_instituto', 'institucion');
                        //$vista = 'listado_radio.tpl.php';
                        break;
                }
                $resultado['form']['seleccione'] = $lenguaje['seleccione'];
                $resultado['tipo'] = $tipo;
                $resultado['busqueda'] = $datos_busqueda;
                echo $this->load->view('informacion_general/'.$vista, $resultado, true);
                //pr($datos);
                exit();
            }
        }
    }

    /*public function por_unidad(){
        $datos['lenguaje'] = $this->lang->line('interface')['informacion_general'];
        $this->load->library('Catalogo_listado');
        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $datos['catalogos'] = $cat_list->obtener_catalogos(array(Catalogo_listado::REGIONES, Catalogo_listado::DELEGACIONES=>array('orden'=>'id_periodo DESC'), ));
        //pr($datos['catalogos']);
        $listado_subcategorias = $this->inf_gen_model->obtener_listado_subcategorias(array('fields'=>'sub.id_subcategoria, sub.nombre as subcategoria, gc.id_grupo_categoria, gc.nombre as grupo_categoria'));
        foreach ($listado_subcategorias as $key_ls => $listado) {
            $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['subcategoria'] = $listado['subcategoria'];
            if(!empty($listado['grupo_categoria'])){
                $datos['catalogos']['subcategorias'][$listado['id_subcategoria']]['elementos'][$listado['id_grupo_categoria']] = $listado['grupo_categoria'];
            }
        }
        //pr($datos);
        $this->template->setTitle($datos['lenguaje']['titulo_principal']);
        $this->template->setSubTitle($datos['lenguaje']['titulo_por_unidad']);
        //$this->template->setDescripcion("Bienvenida a delegacional");
        $this->template->setMainContent($this->load->view('informacion_general/por_perfil.tpl.php', $datos, true));
        //$this->template->setBlank("tc_template/iiindex.tpl.php");    
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
    }*/
    private function obtener_grupos_categorias(&$datos_busqueda){
        $temp = array();
        foreach (explode(',', $datos_busqueda['perfil_seleccion']) as $key_a => $ps) { //Se valida que solo se filtren los grupos_categorias y no las subcategorías
            if(is_numeric($ps) OR !in_array($ps, explode(',', $datos_busqueda['perfil_seleccion_rootkey']))){
                $temp[] = $ps;
            }
        }
        return implode(',', $temp);
    }

    public function buscar_filtros_listados(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                if(isset($datos_busqueda['perfil_seleccion']) && !empty($datos_busqueda['perfil_seleccion'])){
                    $datos_busqueda['perfil_seleccion'] = $this->obtener_grupos_categorias($datos_busqueda);
                }
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //pr($datos_busqueda);
                //pr($datos['datos']);
                $res = array();
                if(!empty($datos['datos'])){
                    $resultado = array();
                    if(isset($datos_busqueda['destino'])) {
                        switch ($datos_busqueda['destino']) {
                            case 'tipo_curso':
                                foreach ($datos['datos'] as $key_tip => $tipos) {
                                    $resultado[$tipos['id_tipo_curso']]['principal']=$tipos['tipo_curso'];
                                }
                                break;
                            case 'perfil':
                                foreach ($datos['datos'] as $key_tip => $tipos) {
                                    if(!is_null($tipos['id_subcategoria'])){
                                        //pr($tipos);
                                        $resultado[$tipos['subcategoria_orden'].'_'.$tipos['id_subcategoria']]['principal']=$tipos['perfil'];
                                        if(isset($tipos['grupo_categoria']) AND !empty($tipos['grupo_categoria'])) {
                                            //pr($tipos['id_grupo_categoria'].'-'.$tipos['grupo_categoria']);
                                            $resultado[$tipos['subcategoria_orden'].'_'.$tipos['id_subcategoria']]['elementos'][$tipos['grupo_categoria_orden'].'_'.$tipos['id_grupo_categoria']] = $tipos['grupo_categoria'];
                                        }
                                    }
                                }
                                break;
                        }
                        //pr($resultado);
                    }
                    if(!empty($resultado)){
                        foreach ($resultado as $key_val => $valor) {
                            //echo '{"title":"'.$valor.'", "key":'.$key_val.', selected: true, "children":[]},';
                            $res[$key_val]['title']=$valor['principal'];
                            $res[$key_val]["key"]=$key_val;
                            $res[$key_val]['selected']=true;
                            $res[$key_val]['expanded']=true;
                            $res[$key_val]['icon']=false;
                            $children = array();
                            if(isset($valor['elementos']) AND !empty($valor['elementos'])){
                                foreach ($valor['elementos'] as $key => $value) {
                                    $children[$key]['title']=$value;
                                    $children[$key]["key"]=$key;
                                    $children[$key]['selected']=true;
                                    $children[$key]['icon']=false;
                                }
                            }
                            $res[$key_val]["children"]=$children;
                        } //pr($res);
                    } else {
                        $res = array('no_datos'=>'true');
                    }
                } else {
                    $res = array('no_datos'=>'true');
                    //echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
                echo json_encode($res);
                //pr($datos);                    
                exit();
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function crear_arreglo_por_tipo($resultado, &$dato){
        if(!isset($resultado['cantidad_alumnos_inscritos'])){
            $resultado['cantidad_alumnos_inscritos'] = 0;
        }
        if(!isset($resultado['cantidad_alumnos_certificados'])){
            $resultado['cantidad_alumnos_certificados'] = 0;
        }
        if(!isset($resultado['cantidad_no_aprobados'])){
            $resultado['cantidad_no_aprobados'] = 0;
        }
        if(!isset($resultado['cantidad_no_accesos'])){
            $resultado['cantidad_no_accesos'] = 0;
        }
        $resultado['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
        $resultado['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
        $resultado['cantidad_no_aprobados'] += $dato['cantidad_no_aprobados'];
        $resultado['cantidad_no_accesos'] += $dato['cantidad_no_accesos'];

        return $resultado;
    }
    public function obtener_condicionales(){
        $conditions = array();
        $grupo_actual = $this->configuracion_grupos->obtener_grupo_actual();
        switch ($grupo_actual) {
            case En_grupos::N1_DEIS: case En_grupos::N1_DM: case En_grupos::N1_JDES: case En_grupos::N2_DGU: 
                $conditions = array('conditions'=>"uni.grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
                break;
            case En_grupos::N1_CEIS: case En_grupos::N1_DH: case En_grupos::N1_DUMF: case En_grupos::N2_CPEI: case En_grupos::N2_CAME: case En_grupos::N3_JSPM:
                $conditions = array('conditions'=>"uni.grupo_tipo_unidad NOT IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')");
                break;
            case En_grupos::NIVEL_CENTRAL: case En_grupos::ADMIN: case En_grupos::SUPERADMIN:                
                break;
        }
        return $conditions;
    }

    public function calcular_totales_generales(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            //if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $resultado = array('total'=>array());

                if(!is_null($this->session->userdata('usuario'))){
                    $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                    
                    $conditions = $this->obtener_condicionales();

                    $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda+$conditions+array('fields'=>'SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados, SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados')); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                    //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                    //pr($datos['datos']);                
                    if(!empty($datos['datos'])){
                        foreach ($datos['datos'] as $key_d => $dato) {
                            //Total
                            $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                        }
                        //pr($datos);
                        echo json_encode($resultado);
                        exit();
                    } else {
                        echo json_encode($resultado); //Mostrar mensaje de datos no existentes
                    }
                } else {
                    echo json_encode($resultado); //Mostrar mensaje de datos no existentes
                }
            //}
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function calcular_totales_unidad(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                //pr($datos_busqueda);
                if(isset($datos_busqueda['agrupamiento_umae']) && $datos_busqueda['agrupamiento_umae']==$this->config->item('agrupamiento')['AGRUPAR']['id']){
                    if(isset($datos_busqueda['tipo_unidad']) AND !empty($datos_busqueda['tipo_unidad']) AND isset($datos_busqueda['umae']) AND empty($datos_busqueda['umae'])){
                        $datos_tipo = $this->inf_gen_model->calcular_totales($datos_busqueda+array('calcular_totales_unidad'=>true, 'fields'=>'DISTINCT(uni.unidad_principal) as unidad_principal', 'order_include'=>false));
                        $up = '';
                        foreach ($datos_tipo as $key_t => $tu) {
                            $up .= $tu['unidad_principal']."','";
                        }
                        //pr($datos_tipo);
                        //pr($up);
                        $datos_busqueda['umae'] = trim($up, "','");
                        //pr($datos_busqueda['umae']);
                        $datos_busqueda['tipo_unidad'] = '';
                    } elseif(isset($datos_busqueda['umae']) AND !empty($datos_busqueda['umae'])){
                        /*pr('***');
                        pr($datos_busqueda['tipo_unidad']);
                        pr($datos_busqueda['umae']);*/
                        $datos_busqueda['tipo_unidad'] = '';
                    }
                } /*else {
                    $datos_busqueda['tipo_unidad'] = '';
                }*/
                //pr($datos_tipo);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda+array('calcular_totales_unidad'=>true)); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                $resultado = array('perfil'=>array(),'tipo_curso'=>array());
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        if(!isset($resultado['perfil'][$dato['perfil']])){
                            $resultado['perfil'][$dato['perfil']] = array();
                        }
                        $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                            $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                    }
                    echo json_encode($resultado);
                } else {
                    echo json_encode(array('error'=>true,'msg'=>'No existen datos')); //Mostrar mensaje de datos no existentes
                }
                exit();
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }
    /*nction cmp($a, $b) {
        if ($a['orden'] == $b['orden']) {
            return 0;
        }
        return ($a['orden'] < $b['orden']) ? -1 : 1;
    }*/

    public function calcular_totales(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                $tipos_busqueda = $this->config->item('tipos_busqueda');

                $extra = array();
                switch ($datos_busqueda['tipos_busqueda']) {
                    case $tipos_busqueda['PERFIL']['id']:
                        $extra = array('fields'=>'"hia"."id_categoria", "gc"."id_grupo_categoria", "gc"."nombre" as "grupo_categoria", "gc"."order" as "grupo_categoria_orden", "sub"."id_subcategoria", "sub"."nombre" as "perfil", "sub"."order" as "perfil_orden",
                            SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                            SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                            'group'=>'"hia"."id_categoria", "gc"."id_grupo_categoria", "gc"."nombre", "gc"."order", "sub"."id_subcategoria", "sub"."nombre", "sub"."order"');
                        break;
                    case $tipos_busqueda['TIPO_CURSO']['id']:
                        $extra = array('fields'=>'"cur"."id_tipo_curso", "tc"."nombre" as "tipo_curso",
                            SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                            SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                            'group'=>'"cur"."id_tipo_curso", "tc"."nombre"');
                        break;
                    case $tipos_busqueda['NIVEL_ATENCION']['id']:
                        $extra = array('fields'=>'case when uni.nivel_atencion=1 then \'Primer nivel\' when uni.nivel_atencion=2 then \'Segundo nivel\' when uni.nivel_atencion=3 then \'Tercer nivel\' else \'Nivel no disponible\' end as nivel_atencion, 
                            SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                            SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                            'group'=>'uni.nivel_atencion',
                            'conditions'=>'uni.nivel_atencion is not null');
                        break;
                    /*case $tipos_busqueda['PERIODO']['id']:
                        $extra = array('fields'=>'imp.anio anio_fin,
                            SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                            SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                            'group'=>'imp.fecha_fin');
                        break;*/
                    case $tipos_busqueda['REGION']['id']:
                        $extra = array('fields'=>'"reg"."id_region", "reg"."nombre" as "region",
                            SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                            SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                            'group'=>'"reg"."id_region", "reg"."nombre"');
                        break;
                    case $tipos_busqueda['DELEGACION']['id']:
                        if($datos_busqueda['agrupamiento']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                            $extra = array('fields'=>'"del"."id_delegacion", "del"."nombre" as "delegacion", SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                                SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                                'group'=>'"del"."id_delegacion", "del"."nombre"', 'order'=>'delegacion');
                        } else {
                            $extra = array('fields'=>'"del"."grupo_delegacion", "del"."nombre_grupo_delegacion" as "delegacion", SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                                SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados',
                                'group'=>'"del"."grupo_delegacion", "del"."nombre_grupo_delegacion"', 'order'=>'delegacion');
                        }
                    break;
                    case $tipos_busqueda['UMAE']['id']:
                        if($datos_busqueda['agrupamiento_umae']==$this->config->item('agrupamiento')['DESAGRUPAR']['id']){
                            $extra = array('fields'=>'"uni"."id_unidad_instituto", "uni"."nombre" as "unidades_instituto", SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                                SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados, uni.grupo_tipo_unidad',
                                'group'=>'"uni"."id_unidad_instituto", "uni"."nombre", uni.grupo_tipo_unidad');
                        } else {
                            $extra = array('fields'=>'"uni"."unidad_principal", "uni"."nombre_unidad_principal" as "unidades_instituto", SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados,
                                SUM(COALESCE(hia.cantidad_no_accesos, 0)) as cantidad_no_accesos, (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-SUM(COALESCE(hia.cantidad_no_accesos, 0))) as cantidad_no_aprobados, uni.grupo_tipo_unidad',
                                'group'=>'"uni"."unidad_principal", "uni"."nombre_unidad_principal", uni.grupo_tipo_unidad');
                        }
                    break;
                }
                $conditions = $this->obtener_condicionales();

                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales(array_merge($datos_busqueda,$extra,$conditions)); ////Obtener listado de evaluaciones de acuerdo al año seleccionado
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']);
                
                $resultado = array('total'=>array(),'perfil'=>array(),'tipo_curso'=>array(),'periodo'=>array(),'region'=>array(),'delegacion'=>array(),'umae'=>array(),'nivel_atencion'=>array());
                if(!empty($datos['datos'])){
                    //pr($datos);
                    foreach ($datos['datos'] as $key_d => $dato) {
                        //Total
                        $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                        if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['PERFIL']['id']){
                            //Perfil
                            if(!isset($resultado['perfil'][$dato['perfil']])){
                                $resultado['perfil'][$dato['perfil']] = array();
                            }
                            $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                            $resultado['perfil'][$dato['perfil']]['orden'] = $dato['perfil_orden'];
                        }
                        //uasort($resultado['perfil'], 'cmp');
                        /*usort($resultado['perfil'][$dato['perfil']], function($a, $b) {
                            return $a['orden'] - $b['orden'];
                        });*/
                        if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['TIPO_CURSO']['id']){
                            //Tipo de curso
                            if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                                $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                            }
                            $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                        }
                        //ksort($resultado['tipo_curso']);
                        if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['NIVEL_ATENCION']['id']){
                            //Nivel atención
                            if(!isset($resultado['nivel_atencion'][$dato['nivel_atencion']])){
                                $resultado['nivel_atencion'][$dato['nivel_atencion']] = array();
                            }
                            $resultado['nivel_atencion'][$dato['nivel_atencion']] = $this->crear_arreglo_por_tipo($resultado['nivel_atencion'][$dato['nivel_atencion']], $dato);
                        }
                        //ksort($resultado['nivel_atencion']);
                        /*if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['PERIODO']['id']){
                            //Periodo
                            if(!isset($resultado['periodo'][$dato['anio_fin']])){
                                $resultado['periodo'][$dato['anio_fin']] = array();
                            }
                            $resultado['periodo'][$dato['anio_fin']] = $this->crear_arreglo_por_tipo($resultado['periodo'][$dato['anio_fin']], $dato);
                        }*/
                        if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['REGION']['id']){
                            //Región
                            if(!isset($resultado['region'][$dato['region']])){
                                $resultado['region'][$dato['region']] = array();
                            }
                            $resultado['region'][$dato['region']] = $this->crear_arreglo_por_tipo($resultado['region'][$dato['region']], $dato);
                        }
                        //ksort($resultado['region']);
                        if($datos_busqueda['tipos_busqueda']==$tipos_busqueda['DELEGACION']['id']){
                            //Delegación
                            if(!isset($resultado['delegacion'][$dato['delegacion']])){
                                $resultado['delegacion'][$dato['delegacion']] = array();
                            }
                            $resultado['delegacion'][$dato['delegacion']] = $this->crear_arreglo_por_tipo($resultado['delegacion'][$dato['delegacion']], $dato);
                        }
                        //ksort($resultado['delegacion']);
                        //UMAE
                        if(isset($dato['grupo_tipo_unidad']) AND in_array($dato['grupo_tipo_unidad'], array($this->config->item('grupo_tipo_unidad')['UMAE']['id'], $this->config->item('grupo_tipo_unidad')['CUMAE']['id']))){
                            /*if(!isset($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']])){
                                $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = array();
                            }
                            $resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']] = $this->crear_arreglo_por_tipo($resultado['umae'][$dato['clave_unidad'].'-'.$dato['unidades_instituto']], $dato);*/
                            if(!isset($resultado['umae'][$dato['unidades_instituto']])){
                                $resultado['umae'][$dato['unidades_instituto']] = array();
                            }
                            $resultado['umae'][$dato['unidades_instituto']] = $this->crear_arreglo_por_tipo($resultado['umae'][$dato['unidades_instituto']], $dato);
                            //ksort($resultado['umae']);
                        }
                    }
                    //pr($datos);
                    echo json_encode($resultado);
                } else {
                    echo json_encode(array('error'=>true,'msg'=>'No existen datos')); //Mostrar mensaje de datos no existentes
                }
                exit();
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function buscar_perfil(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                if(isset($datos_busqueda['perfil_seleccion']) && !empty($datos_busqueda['perfil_seleccion'])){
                    $datos_busqueda['perfil_seleccion'] = $this->obtener_grupos_categorias($datos_busqueda);
                }

                $conditions = $this->obtener_condicionales();
                //pr($datos_busqueda);
                $datos['datos'] = $this->inf_gen_model->calcular_totales($datos_busqueda+$conditions);/*+array('fields'=>'"imp"."fecha_fin", EXTRACT(MONTH FROM imp.fecha_fin) mes_fin, 
                    imp.anio anio_fin, "cur"."id_tipo_curso", 
                    "tc"."nombre" as "tipo_curso", "gc"."id_grupo_categoria", "gc"."nombre" as "grupo_categoria", 
                    "gc"."order" as "grupo_categoria_orden", "sub"."id_subcategoria", "sub"."nombre" as "perfil", "sub"."order" as "perfil_orden", 
                    SUM("hia"."cantidad_alumnos_inscritos") as cantidad_alumnos_inscritos, SUM("hia"."cantidad_alumnos_certificados") as cantidad_alumnos_certificados, 
                    "gc"."order" as "grupo_categoria_orden", COALESCE(SUM(hia.cantidad_no_accesos), 0) as cantidad_no_accesos, 
                    (SUM(hia.cantidad_alumnos_inscritos)-SUM(hia.cantidad_alumnos_certificados)-COALESCE(SUM(hia.cantidad_no_accesos), 0)) as cantidad_no_aprobados, 
                    EXTRACT(month FROM fecha_fin) as periodo_id, (CASE WHEN EXTRACT(month FROM fecha_fin) = 1 THEN \'Enero\'
                                    WHEN EXTRACT(month FROM fecha_fin) = 2 THEN \'Febrero\'
                                    WHEN EXTRACT(month FROM fecha_fin) = 3 THEN \'Marzo\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 4 THEN \'Abril\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 5 THEN \'Mayo\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 6 THEN \'Junio\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 7 THEN \'Julio\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 8 THEN \'Agosto\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 9 THEN \'Septiembre\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 10 THEN \'Octubre\' 
                                    WHEN EXTRACT(month FROM fecha_fin) = 11 THEN \'Noviembre\' 
                                    ELSE \'Diciembre\' END) as periodo', 'group'=>'"imp"."fecha_fin", EXTRACT(MONTH FROM imp.fecha_fin), 
                    imp.anio, "cur"."id_tipo_curso", "tc"."nombre", 
                    "gc"."id_grupo_categoria", "gc"."nombre", "gc"."order", 
                    "sub"."id_subcategoria", "sub"."nombre", "sub"."order"')*/ ////Obtener listado de evaluaciones de acuerdo al año seleccionado --"mes"."nombre" as "mes", "mes"."nombre", 
                //$datos['usuario']['string_values'] = array_merge($this->lang->line('interface_administracion')['usuario'], $this->lang->line('interface_administracion')['general']); //Cargar textos utilizados en vista
                //pr($datos['datos']); exit();
                $resultado = array('total'=>array(),'perfil'=>array(),'tipo_curso'=>array(),'periodo'=>array());
                $tablas = array('perfil'=>array(),'tipo_curso'=>'');
                if(!empty($datos['datos'])){
                    foreach ($datos['datos'] as $key_d => $dato) {
                        if(!isset($dato['periodo']) OR empty($dato['periodo'])) {
                            $dato['periodo'] = $dato['anio_fin'];
                        }                        
                        //Total
                        $resultado['total'] = $this->crear_arreglo_por_tipo($resultado['total'], $dato);
                        //Periodo
                        if(!isset($resultado['periodo'][$dato['periodo']])){
                            $resultado['periodo'][$dato['periodo']] = array();
                        }
                        $resultado['periodo'][$dato['periodo']] = $this->crear_arreglo_por_tipo($resultado['periodo'][$dato['periodo']], $dato);
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']])){
                            $resultado['perfil'][$dato['perfil']] = array();
                        }
                        $resultado['perfil'][$dato['perfil']] = $this->crear_arreglo_por_tipo($resultado['perfil'][$dato['perfil']], $dato);
                        //$tablas['perfil'][$dato['perfil_orden']][$dato['perfil']] = $dato['perfil'];
                        if(!isset($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']])){
                            $tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']] = array();
                        }
                        if(!isset($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos'][$dato['grupo_categoria_orden'].'_'.$dato['grupo_categoria']])){
                            $tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos'][$dato['grupo_categoria_orden'].'_'.$dato['grupo_categoria']] = array();
                        }
                        $tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']] = $this->crear_arreglo_por_tipo($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']], $dato);
                        $tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos'][$dato['grupo_categoria_orden'].'_'.$dato['grupo_categoria']] = $this->crear_arreglo_por_tipo($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos'][$dato['grupo_categoria_orden'].'_'.$dato['grupo_categoria']], $dato);
                        ksort($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos']);
                        ksort($tablas['perfil']);
                        //array_multisort($tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']], SORT_ASC, SORT_STRING, 
                            //$tablas['perfil'][$dato['perfil_orden'].'_'.$dato['perfil']]['elementos'][$dato['grupo_categoria_orden'].'_'.$dato['grupo_categoria']], SORT_NUMERIC, SORT_ASC);
                        //pr($dato);
                        //pr($tablas); exit();
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']])){
                            $resultado['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($resultado['tipo_curso'][$dato['tipo_curso']], $dato);
                        
                        /*if(!isset($tablas['tipo_curso'][$dato['tipo_curso']])){
                            $tablas['tipo_curso'][$dato['tipo_curso']] = array();
                        }
                        $tablas['tipo_curso'][$dato['tipo_curso']] = $this->crear_arreglo_por_tipo($tablas['tipo_curso'][$dato['grupo_categoria_orden'].'_'.$dato['tipo_curso']], $dato);*/
                        ksort($resultado['tipo_curso']);
                        
                        //pr($resultado);
                        //Periodo
                        /*if(!isset($resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'])){
                            $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'])){
                            $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['periodo'][$dato['periodo']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Tipo de curso
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'])){
                            $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['tipo_curso'][$dato['tipo_curso']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Perfil
                        if(!isset($resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'])){
                            $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'])){
                            $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['perfil'][$dato['perfil']][$dato['grupo_categoria']]['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];
                        //Total
                        if(!isset($resultado['total']['cantidad_alumnos_inscritos'])){
                            $resultado['total']['cantidad_alumnos_inscritos'] = 0;
                        }
                        if(!isset($resultado['total']['cantidad_alumnos_certificados'])){
                            $resultado['total']['cantidad_alumnos_certificados'] = 0;
                        }
                        $resultado['total']['cantidad_alumnos_inscritos'] += $dato['cantidad_alumnos_inscritos'];
                        $resultado['total']['cantidad_alumnos_certificados'] += $dato['cantidad_alumnos_certificados'];*/
                    }
                    $resultado['lenguaje'] = $this->lang->line('interface')['informacion_general'];
                    $etm = (isset($datos_busqueda['temporal_tipo_busqueda']) && $datos_busqueda['temporal_tipo_busqueda']=='tipo_curso') ? true : false;
                    //ksort($resultado['tipo_curso']);
                    $resultado['tabla_tipo_curso'] = $this->load->view('informacion_general/tabla.tpl.php', array('id'=>'tabla_tipo_curso', 'titulo'=>$resultado['lenguaje']['tipo_curso'], 'valores'=>$resultado['tipo_curso'], 'lenguaje'=>$resultado['lenguaje'], 'etm'=>$etm), true);
                    $resultado['tabla_perfil'] = $this->load->view('informacion_general/tabla.tpl.php', array('id'=>'tabla_perfil', 'titulo'=>$resultado['lenguaje']['perfil'], 'valores'=>$tablas['perfil'], 'lenguaje'=>$resultado['lenguaje'], 'etm'=>!$etm), true);
                    //pr($tablas);
                    echo json_encode($resultado);
                } else {
                    $resultado['total'] = 0;
                    echo json_encode($resultado);
                    //echo data_not_exist(); //Mostrar mensaje de datos no existentes
                }
                exit();
            }
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function obtener_umae(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta
                $c_region = (isset($datos_busqueda['region']) AND !empty($datos_busqueda['region'])) ? " AND id_region=".$datos_busqueda['region'] : '';
                $c_tipo_unidad = (isset($datos_busqueda['tipo_unidad']) AND !empty($datos_busqueda['tipo_unidad'])) ? ' AND id_tipo_unidad='.$datos_busqueda['tipo_unidad'] : '';
                $c_grupo = "grupo_tipo_unidad IN ('".$this->config->item('grupo_tipo_unidad')['UMAE']['id']."', '".$this->config->item('grupo_tipo_unidad')['CUMAE']['id']."')";
                $c_anio = " AND anio=".$datos_busqueda['anio'];

                $cat_list = new Catalogo_listado(); //Obtener catálogos
                if($datos_busqueda['agrupamiento_umae']==$this->config->item('agrupamiento')['AGRUPAR']['id']){
                    $agrupamiento = array(Catalogo_listado::UNIDADES_INSTITUTO=>array('valor'=>'nombre_unidad_principal', 'llave'=>'DISTINCT(unidad_principal)', 'orden'=>'nombre_unidad_principal','condicion'=>$c_grupo.$c_anio.$c_region.$c_tipo_unidad));
                } else {
                    $agrupamiento = array(Catalogo_listado::UNIDADES_INSTITUTO=>array('condicion'=>$c_grupo.$c_anio.$c_region." AND unidad_principal in (SELECT unidad_principal FROM catalogos.unidades_instituto WHERE ".$c_grupo.$c_anio.$c_region.$c_tipo_unidad.")", 'valor'=>"nombre"));
                }
                $catalogos = $cat_list->obtener_catalogos($agrupamiento); //Obtener nivel de atención en otra llamada debido a que tiene el mismo indice que UMAE
                
                echo json_encode($catalogos['unidades_instituto']);
            }
        }
    }

    public function obtener_delegacion(){
        if($this->input->is_ajax_request()){ //Solo se accede al método a través de una petición ajax
            if(!is_null($this->input->post())){ //Se verifica que se haya recibido información por método post
                $datos_busqueda = $this->input->post(null, true); //Datos del formulario se envían para generar la consulta

                $cat_list = new Catalogo_listado(); //Obtener catálogos
                if($datos_busqueda['agrupamiento']==$this->config->item('agrupamiento')['AGRUPAR']['id']){
                    $agrupamiento = array(Catalogo_listado::DELEGACIONES=>array('valor'=>'nombre_grupo_delegacion', 'llave'=>'DISTINCT(grupo_delegacion)', 'orden'=>'nombre_grupo_delegacion', 'condicion'=>'id_delegacion NOT IN ('.$this->config->item('DELEGACIONES')['SIN_DELEGACION']['id'].', '.$this->config->item('DELEGACIONES')['MANDO']['id'].')'));
                } else {
                    $agrupamiento = array(Catalogo_listado::DELEGACIONES=>array('condicion'=>'id_delegacion NOT IN ('.$this->config->item('DELEGACIONES')['SIN_DELEGACION']['id'].', '.$this->config->item('DELEGACIONES')['MANDO']['id'].')'));
                }
                $catalogos = $cat_list->obtener_catalogos($agrupamiento); //Obtener nivel de atención en otra llamada debido a que tiene el mismo indice que UMAE
                
                echo json_encode($catalogos['delegaciones']);
            }
        }
    }
}