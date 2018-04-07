<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catálogos
 * @version 	: 1.0.0
 * @author      : JZDP
 * */
class Catalogo extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->model('Catalogo_model', 'catalogo');
        $this->load->library('grocery_CRUD');
        $this->template->setTitle('Catálogos');
    }

    public function index()
    {
        redirect(site_url());
    }

    public function alineacion_estrategica()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado
            /* $this->load->library('Catalogo_listado');
              $cl = new Catalogo_listado();
              $cl->obtener_catalogos(array(Catalogo_listado::LINEAS_ESTRATEGICAS, Catalogo_listado::DELEGACIONES
              //Catalogo_listado::DELEGACIONES => array('llave'=>'clave_delegacional', 'valor'=>"CONCAT(nombre,'(',clave_delegacional,')')", 'orden'=>'clave_delegacional DESC', 'condicion'=>"clave_delegacional>'05'")
              ));
              echo '<button id="print" onclick="imprimir_contenido(\'.card-header .card-chart\');" >Imprimir</button>'; */

            $crud = $this->new_crud();
            $crud->set_table('alineaciones_estrategicas');
            $crud->set_subject('Alineación estratégica');
            $crud->display_as('id_alineacion_estrategica', 'ID');
            $crud->display_as('id_curso', 'Curso');
            $crud->display_as('id_tema_prioritario', 'Tema prioritario');
            $crud->display_as('id_linea_accion', 'Línea de acción');
            $crud->display_as('id_linea_estrategica', 'Línea estratégica');
            $crud->display_as('id_programa_proyecto', 'Programa / proyecto');
            $crud->display_as('descripcion', 'Descripción');

            $crud->set_primary_key('id_alineacion_estrategica', 'alineaciones_estrategicas'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_curso', 'cursos');
            $crud->set_primary_key('id_tema_prioritario', 'temas_prioritarios');
            $crud->set_primary_key('id_linea_accion', 'lineas_accion');
            $crud->set_primary_key('id_linea_estrategica', 'lineas_estrategicas');
            $crud->set_primary_key('id_programa_proyecto', 'programas_proyecto');

            //$crud->columns('id_unidad_instituto','clave_unidad','nombre','id_delegacion','clave_presupuestal','id_tipo_unidad','umae'); //Definir columnas a mostrar en el listado y su orden
            //$crud->add_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a agregar y su orden
            //$crud->edit_fields('clave_unidad','nombre','id_delegacion','clave_presupuestal','fecha','nivel_atencion','id_tipo_unidad','umae','latitud','longitud','activa'); //Definir campos que se van a editar y su orden

            $crud->set_relation('id_curso', 'cursos', '{nombre} ({clave})', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('id_tema_prioritario', 'temas_prioritarios', '{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_linea_accion', 'lineas_accion', '{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_linea_estrategica', 'lineas_estrategicas', '{nombre} ({clave})', null, 'nombre');
            $crud->set_relation('id_programa_proyecto', 'programas_proyecto', '{nombre} ({clave})', null, 'nombre');

            $crud->columns('id_alineacion_estrategica', 'id_tema_prioritario', 'id_linea_estrategica', 'id_linea_accion', 'id_programa_proyecto', 'id_curso', 'descripcion'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('id_tema_prioritario', 'id_linea_estrategica', 'id_linea_accion', 'id_programa_proyecto', 'id_curso', 'descripcion'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('id_tema_prioritario', 'id_linea_estrategica', 'id_linea_accion', 'id_programa_proyecto', 'id_curso', 'descripcion'); //Definir campos que se van a editar y su orden

            $crud->set_rules('id_curso', 'Curso', 'required');
            $crud->set_rules('id_tema_prioritario', 'Tema prioritario', 'required');
            $crud->set_rules('id_linea_accion', 'Línea de acción', 'required');
            $crud->set_rules('id_linea_estrategica', 'Línea estratégica', 'required');
            $crud->set_rules('id_programa_proyecto', 'Programa / proyecto', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setSubTitle('Alineaciones estrategicas');
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function categoria()
    {
        $filtros['per_page'] = 10;
        $filtros['current_row'] = 0;
        if ($this->input->post())
        {
            $filtros['order'] = $this->input->post('order', true);
            $filtros['per_page'] = $this->input->post('per_page', true);

            if ($this->input->post('current_row') && is_numeric($this->input->post('current_row', true)))
            {
                $filtros['current_row'] = $this->input->post('current_row', true);
            }
            $output['current_row'] = $filtros['current_row'];
        }
        $output['categorias'] = $this->catalogo->get_categorias($filtros);
        $paginacion = $this->template->pagination_data($output['categorias']);
        $output['paginacion'] = $paginacion;
        $view = $this->load->view('catalogo/categorias', $output, true);
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Categorías');
        $this->template->getTemplate();
    }

    public function curso()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('cursos');

            $crud->set_subject('Curso');
            $crud->display_as('id_curso', 'ID');
            $crud->display_as('clave', 'Clave de curso');
            $crud->display_as('nombre', 'Nombre de curso');
            $crud->display_as('id_tipo_curso', 'Tipo de curso');
            $crud->display_as('id_modalidad', 'Modalidad');
            $crud->display_as('tutorizado', 'Tutorizado');
            $crud->display_as('id_programa_proyecto', 'Programa');
            $crud->display_as('anio', 'Año');            
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_tipo_curso', 'tipos_cursos'); //Definir llaves primarias, asegurar correcta relación

            $crud->set_relation('id_tipo_curso', 'tipos_cursos', 'nombre', null, 'nombre'); //Establecer relaciones
            $crud->set_relation('id_programa_proyecto', 'programas_proyecto', 'nombre'); //Establecer relaciones

            $crud->columns('id_curso', 'clave', 'nombre', 'id_tipo_curso', 'id_modalidad', 'tutorizado', 'anio', 'id_programa_proyecto', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'id_tipo_curso', 'id_modalidad', 'tutorizado', 'anio', 'id_programa_proyecto',  'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'id_tipo_curso', 'id_modalidad', 'tutorizado','anio', 'id_programa_proyecto', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('tutorizado', 'true_false', array(0 => 'No tutorizado', 1 => 'Tutorizado'));
            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));
                        
            $crud->set_rules('clave', 'Clave de categoría', 'trim|required');
            $crud->set_rules('nombre', 'Nombre de categoría', 'trim|required');
            $crud->set_rules('id_tipo_curso', 'Tipo de curso', 'required');
            $crud->set_rules('id_modalidad', 'Modalidad', 'required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setSubTitle('Cursos');
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function delegacion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('delegaciones');

            $crud->set_subject('Delegación');
            $crud->display_as('clave_delegacional', 'Clave');
            $crud->display_as('nombre', 'Nombre de la delegación');
            $crud->display_as('id_region', 'Región');
            $crud->display_as('configuraciones', 'Configuraciones');
            $crud->display_as('latitud', 'Latitud');
            $crud->display_as('longitud', 'Longitud');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');


            $crud->set_primary_key('id_region', 'regiones'); //Definir llaves primarias, asegurar correcta relación
            $crud->set_primary_key('id_delegacion', 'delegaciones');

            $crud->set_relation('id_region', 'regiones', 'nombre', null, 'nombre'); //Establecer relaciones

            $crud->columns('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'configuraciones', 'fecha', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_delegacional', 'nombre', 'id_region', 'latitud', 'longitud', 'configuraciones', 'fecha', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de categoría', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setSubTitle('Delegaciones');
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function departamento()
    {
        $filtros['per_page'] = 10;
        $filtros['current_row'] = 0;
        if ($this->input->post())
        {
            $filtros['order'] = $this->input->post('order', true);
            $filtros['per_page'] = $this->input->post('per_page', true);
            $filtros['keyword'] = $this->input->post('keyword', true);
            $filtros['type'] = $this->input->post('filtro_texto', true);
            if ($this->input->post('current_row') && is_numeric($this->input->post('current_row', true)))
            {
                $filtros['current_row'] = $this->input->post('current_row', true);
            }
            $output['current_row'] = $filtros['current_row'];
        }
        $output['departamentos'] = $this->catalogo->get_departamentos($filtros);
        $paginacion = $this->template->pagination_data($output['departamentos']);
        $output['paginacion'] = $paginacion;
        $view = $this->load->view('catalogo/departamentos', $output, true);
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Departamentos');
        $this->template->getTemplate();
    }

    public function grupo_categoria()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('grupos_categorias');

            $crud->set_subject('Grupo categoría');
            $crud->display_as('id_grupo_categoria', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre del grupo-categoría');
            $crud->display_as('descripcion', 'Descripción');

            $crud->set_primary_key('id_grupo_categoria', 'grupos_categorias'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_grupo_categoria', 'clave', 'nombre'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion'); //Definir campos que se van a editar y su orden

            $crud->set_rules('nombre', 'Nombre del grupo-categoría', 'trim|required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Grupos de categorías');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }


    public function grupo()
    {
        try
        {
            $this->db->schema = 'sistema';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('grupos');

            $crud->set_subject('Grupos - Roles');
            $crud->display_as('id_grupo', 'ID');
            $crud->display_as('nombre', 'Nombre del grupo');
            $crud->display_as('descripcion', 'Descripción del grupo');
            $crud->display_as('activo', 'Activo');
            $crud->display_as('nivel', 'Nivel');
            $crud->display_as('orden', 'Orden');

            $crud->set_primary_key('id_grupo', 'grupos'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_grupo', 'nombre', 'descripcion', 'activo', 'nivel', 'orden'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('nombre', 'descripcion', 'activo', 'nivel', 'orden'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('nombre', 'descripcion', 'activo', 'nivel', 'orden'); //Definir campos que se van a editar y su orden

            $crud->set_rules('nombre', 'Nombre del grupo', 'trim|required');
            $crud->set_rules('nivel', 'Nivel', 'trim|required|integer');
            $crud->set_rules('orden', 'Orden', 'trim|required|integer');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Grupos - Roles');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function implementacion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('implementaciones');

            $crud->set_subject('Implementación');
            $crud->display_as('id_implementacion', 'ID');
            $crud->display_as('id_curso', 'Curso');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre de la implementación');
            $crud->display_as('id_remoto', 'ID remoto');
            $crud->display_as('fecha_inicio', 'Fecha inicial');
            $crud->display_as('fecha_fin', 'Fecha final');
            $crud->display_as('anio', 'Año');
            $crud->display_as('clave_curso', 'Clave del curso');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_implementacion', 'implementaciones'); //Definir llaves primarias, asegurar correcta relación

            $crud->set_relation('id_curso', 'cursos', "{nombre} ({clave} {anio})", null, 'clave'); //Establecer relaciones            
            
            $crud->columns('id_implementacion', 'id_curso', 'clave', 'nombre', 'fecha_inicio', 'fecha_fin', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('id_curso', 'clave', 'nombre', 'clave_curso', 'id_remoto', 'fecha_inicio', 'fecha_fin', 'anio','activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('id_curso', 'clave', 'nombre', 'clave_curso', 'id_remoto', 'fecha_inicio', 'fecha_fin', 'anio', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));
            
            $crud->change_field_type('fecha_inicio', 'date');
            $crud->change_field_type('fecha_fin', 'date');
            $crud->change_field_type('anio', 'integer');

            $crud->set_rules('id_curso', 'Curso', 'required');
            $crud->set_rules('clave', 'Clave', 'trim|required');
            $crud->set_rules('nombre', 'Nombre de departamento', 'trim|required');
            $crud->set_rules('id_remoto', 'ID remoto', 'trim|required|integer');
            $crud->set_rules('fecha_inicio', 'Fecha inicial', 'trim|required');
            $crud->set_rules('fecha_fin', 'Fecha final', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Implementaciones');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function linea_accion()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('lineas_accion');

            $crud->set_subject('Línea de acción');
            $crud->display_as('id_linea_accion', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre de la línea de acción');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_linea_accion', 'lineas_accion'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_linea_accion', 'clave', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'fecha', 'descripcion', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'fecha', 'descripcion', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de departamento', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Líneas de acción');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function linea_estrategica()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('lineas_estrategicas');

            $crud->set_subject('Línea estratégica');
            $crud->display_as('id_linea_estrategica', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre de la línea estratégica');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_linea_estrategica', 'lineas_estrategicas'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_linea_estrategica', 'clave', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'fecha', 'descripcion', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'fecha', 'descripcion', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de departamento', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Líneas estratégicas');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function perfiles_alumno()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('perfiles_alumnos');

            $crud->set_subject('Perfil de alumno');
            $crud->display_as('id_perfil_alumno', 'ID');
            $crud->display_as('clave_perfil', 'Clave');
            $crud->display_as('nombre', 'Nombre del perfil del alumno');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');

            $crud->set_primary_key('id_perfil_alumno', 'perfiles_alumno'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_perfil_alumno', 'clave_perfil', 'nombre', 'fecha'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_perfil', 'nombre', 'descripcion', 'fecha'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_perfil', 'nombre', 'descripcion', 'fecha'); //Definir campos que se van a editar y su orden

            $crud->set_rules('nombre', 'Nombre de departamento', 'trim|required');
            $crud->set_rules('clave_perfil', 'Clave', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function programa_proyecto()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('programas_proyecto');

            $crud->set_subject('Programa - proyecto');
            $crud->display_as('id_programa_proyecto', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre del programa - proyecto');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_programa_proyecto', 'programas_proyecto'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_programa_proyecto', 'clave', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de departamento', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Programas');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function region()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('regiones');

            $crud->set_subject('Región');
            $crud->display_as('id_region', 'ID');
            $crud->display_as('clave_regional', 'Clave');
            $crud->display_as('nombre', 'Nombre de la región');
            $crud->display_as('configuraciones', 'Configuraciones');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_region', 'regiones'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_region', 'clave_regional', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave_regional', 'nombre', 'fecha', 'configuraciones', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave_regional', 'nombre', 'fecha', 'configuraciones', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre de la región', 'trim|required');
            $crud->set_rules('clave_regional', 'Clave', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Regiones');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tema_prioritario()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('temas_prioritarios');

            $crud->set_subject('Tema prioritario');
            $crud->display_as('id_tema_prioritario', 'ID');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('nombre', 'Nombre del tema prioritario');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_tema_prioritario', 'temas_prioritarios'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_tema_prioritario', 'clave', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre del tema prioritario', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Temas prioritarios');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_curso()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('tipos_cursos');

            $crud->set_subject('Tipo de curso');
            $crud->display_as('id_tipo_curso', 'ID');
            $crud->display_as('nombre', 'Nombre del tipo de curso');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('fecha', 'Fecha');
            $crud->display_as('activo', 'Activo');

            $crud->set_primary_key('id_tipo_curso', 'tipos_cursos'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_tipo_curso', 'nombre', 'fecha', 'activo'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('nombre', 'descripcion', 'fecha', 'activo'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre del tipo de curso', 'trim|required');
            $crud->set_rules('fecha', 'Fecha', 'trim|required');
            $crud->set_rules('activo', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Tipos de curso');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function tipo_unidad()
    {
        try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('tipos_unidades');

            $crud->set_subject('Tipo de unidad');
            $crud->display_as('id_tipo_unidad', 'ID');
            $crud->display_as('nombre', 'Nombre del tipo de unidad');
            $crud->display_as('clave', 'Clave');
            $crud->display_as('descripcion', 'Descripción');
            $crud->display_as('activa', 'Activo');

            $crud->set_primary_key('id_tipo_unidad', 'tipos_unidades'); //Definir llaves primarias, asegurar correcta relación

            $crud->columns('id_tipo_unidad', 'nombre', 'clave', 'activa'); //Definir columnas a mostrar en el listado y su orden
            $crud->add_fields('clave', 'nombre', 'descripcion', 'activa'); //Definir campos que se van a agregar y su orden
            $crud->edit_fields('clave', 'nombre', 'descripcion', 'activa'); //Definir campos que se van a editar y su orden

            $crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));

            $crud->set_rules('nombre', 'Nombre del tipo de unidad', 'trim|required');
            $crud->set_rules('activa', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Tipos de unidades');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function nuevo_departamento()
    {
        $output = [];
        if ($this->input->post())
        {            
            $params = $this->input->post(NULL, true);
            $output['params'] = $params;
            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('form_departamento'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations); //Añadir validaciones
            if ($this->form_validation->run() == TRUE){                
                $output['status'] = $this->catalogo->insert_departamento($params);
            }else{
//                pr(validation_errors());
            }
        }
        $this->load->view('catalogo/nuevo_departamento.tpl.php', $output);
    }

    public function unidad_instituto($admin = '')
    {
        switch ($admin){            
            case 'crud':
                $this->admin_unidades_instituto();
                break;
            default :
                $this->ver_unidades_instituto();
                break;
        }        
    }
    
    private function admin_unidades_instituto(){
         try
        {
            $this->db->schema = 'catalogos';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('unidades_instituto');

            $crud->set_subject('Unidades del instituto');

            //$crud->set_primary_key('id_unidad_instituto', 'unidades_instituto'); //Definir llaves primarias, asegurar correcta relación
            

            //$crud->change_field_type('activa', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));
            
            //$crud->set_rules('activa', 'Activo', 'required');

            $crud->unset_delete();
            $output = $crud->render();

            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->setSubTitle('Administración de las unidades del instituto');
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    private function ver_unidades_instituto(){
        $filtros['per_page'] = 10;
        $filtros['current_row'] = 0;
        if ($this->input->post())
        {
            $filtros['order'] = $this->input->post('order', true);
            $filtros['per_page'] = $this->input->post('per_page', true);
            $filtros['keyword'] = $this->input->post('keyword', true);
            $filtros['type'] = $this->input->post('filtro_texto', true);
            if ($this->input->post('current_row') && is_numeric($this->input->post('current_row', true)))
            {
                $filtros['current_row'] = $this->input->post('current_row', true);
            }
            $output['current_row'] = $filtros['current_row'];
        }
        $output['unidades'] = $this->catalogo->get_unidades($filtros);
        $paginacion = $this->template->pagination_data($output['unidades']);
        $output['paginacion'] = $paginacion;
        $view = $this->load->view('catalogo/unidades', $output, true);
        $this->template->setMainContent($view);
        $this->template->setSubTitle('Unidades instituto');
        $this->template->getTemplate();
    }

}
