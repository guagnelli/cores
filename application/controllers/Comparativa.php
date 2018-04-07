<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que genera reporte dashboard de nivel central y de delegacionales
 * @version : 1.0.0
 * @autor : Miguel Guagnelli
 */
class Comparativa extends MY_Controller
{

    /**
     * Carga de clases para el acceso a base de datos y obtencion de las variables de session
     * @access 		: public
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'general'));
        $this->load->library('form_complete');
        $this->lang->load('comparativa', 'spanish');
        $this->load->model("Nomina_model", "nom");
        $this->lang->load('interface'); //Cargar archivo de lenguaje
        $this->load->model('Comparativa_model', 'comparativa');
        $this->load->library('form_validation');
        $this->load->library('Catalogo_listado');
    }

    public function index()
    {
        /*
          1. generar plantilla con gRAFICO
          2. generar consulta
          3. generar gráfica con query
          4. generar filtros
          5.integrar filtros
          6. integrar sesión con filtro
         */
        $data["texts"] = $this->lang->line('formulario'); //Mensajes de respuesta
        //pr($data);

        $this->template->setTitle($data["texts"]["title"]);

        $this->template->setSubTitle($data["texts"]["subtitle"]);
        $this->template->setDescripcion($data["texts"]["descripcion"]);

        $this->template->setBlank("comparative/index.tpl.php", $data, FALSE);
        //$this->template->setBlank("tc_template/index.tpl.php");

        $this->template->getTemplate(null, "tc_template/index.tpl.php");
    }

    public function unidades()
    {
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();

        $output['usuario'] = $this->session->userdata('usuario');
//        pr($output['usuario']);
        if ($this->input->post('vista'))
        {
            $filtros = $this->input->post();
            if (!isset($filtros['periodo']) || $filtros['periodo'] == "")
            {
                $periodo = date("Y");
            } else
            {
                $periodo = $filtros['periodo'];
            }
            $output['periodo'] = $periodo;
            $filtros['agrupamiento'] = 1; //activamos el agrupamiento            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central                
            }

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS => array('condicion' => 'id_subcategoria > 1'),
                Catalogo_listado::TIPOS_CURSOS => array('condicion' => 'activo = true'))
            );
            if ($this->input->post('nivel') != null)
            {
                $nivel = $this->input->post('nivel', true);
                $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, '0', $nivel), 'id_tipo_unidad', 'nombre');
            } else
            {
                $output['tipos_unidades'] = [];
                if (isset($output['usuario']['nivel_atencion']) && $output['usuario']['nivel_atencion'] != null)
                {
                    $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, '0', $output['usuario']['nivel_atencion']), 'id_tipo_unidad', 'nombre');
                }
            }
            if ($this->input->post('perfil') != null)
            {
                $this->load->model('Buscador_model', 'buscador');
                $filtros_['subcategoria'] = $this->input->post('perfil', true);
                $output['grupos_categorias'] = dropdown_options($this->buscador->get_grupos_categorias($filtros_), 'id_grupo_categoria', 'nombre');
            } else
            {
                $output['grupos_categorias'] = [];
            }
            $output['agrupamiento'] = $filtros['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('vista', true))
            {
                case 1:
                    $vista = 'unidad_tipo_curso';
                    break;
                default:
                    $vista = 'unidad_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $output['datos'] = $datos = $this->comparativa->get_comparar_unidad($filtros);
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        }

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {

            $view = $this->load->view('comparative/unidades', $output, true);
            $this->template->setDescripcion($this->mostrar_datos_generales());
            $this->template->setMainContent($view);
            $this->template->setSubTitle(render_subtitle('Comparativa por Unidades Instituto', 'comparativa_unidades'));
            $this->template->getTemplate();
        }
    }

    public function umae()
    {
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
        $output['comparativas'] = $this->comparativa->get_tipos_comparativas();

        $output['usuario'] = $this->session->userdata('usuario');
        if ($this->input->post('vista'))
        {
            $filtros = $this->input->post();
            if (!isset($filtros['periodo']) || $filtros['periodo'] == "")
            {
                $periodo = date("Y");
            } else
            {
                $periodo = $filtros['periodo'];
            }
            $filtros['agrupamiento'] = 1; //activamos el agrupamiento            
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
                $opciones_umae = array(
                    'llave' => 'id_unidad_instituto',
                    'valor' => 'nombre',
                    'condicion' => "(grupo_tipo_unidad = 'UMAE' or grupo_tipo_unidad = 'CUMAE') and anio = {$periodo}",
                    'group' => array('id_unidad_instituto', 'nombre'),
                    'orden' => 'nombre'
                );
            } else
            {
                $opciones_umae = array(
                    'llave' => 'nombre_unidad_principal',
                    'valor' => 'nombre_unidad_principal',
                    'condicion' => "grupo_tipo_unidad = 'UMAE' and anio = {$periodo}",
                    'group' => array('nombre_unidad_principal'),
                    'orden' => 'nombre_unidad_principal'
                );
            }

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS => array('condicion' => 'id_subcategoria > 1'),
                Catalogo_listado::TIPOS_CURSOS => array('condicion' => 'activo = true'),
                Catalogo_listado::UNIDADES_INSTITUTO => $opciones_umae,
                Catalogo_listado::TIPOS_UNIDADES => array(
                    'llave' => 'id_tipo_unidad',
                    'nombre' => 'nombre',
                    'condicion' => 'nivel = 3'
                ))
            );
            if ($this->input->post('perfil') != null)
            {
                $this->load->model('Buscador_model', 'buscador');
                $filtros_['subcategoria'] = $this->input->post('perfil', true);
                $output['subperfiles'] = dropdown_options($this->buscador->get_grupos_categorias($filtros_), 'id_grupo_categoria', 'nombre');
            } else
            {
                $output['subperfiles'] = [];
            }
            $output['agrupamiento'] = $filtros['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles(true);
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            //$output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false), 'id_tipo_unidad', 'nombre');
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('vista', true))
            {
                case 1:
                    $vista = 'umae_tipo_curso';
                    break;
                default:
                    $vista = 'umae_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $output['datos'] = $datos = $this->comparativa->get_comparar_umae($filtros);
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        }

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {

            $view = $this->load->view('comparative/umae', $output, true);
            $this->template->setDescripcion($this->mostrar_datos_generales());
            $this->template->setMainContent($view);
            $this->template->setSubTitle(render_subtitle('Comparativa por UMAE', 'comparativa_umae'));
            $this->template->getTemplate();
        }
    }

    public function region($num = null, $year = null, $type = null, $umae = null, $niv = null, $tunidad = null, $sc = null)
    {
        $this->load->model('Buscador_model', 'buscador');
        $this->load->model('Ranking_model', 'ranking');
        $usuario = $this->session->userdata('usuario');
        $data['usuario'] = $usuario;
        if ($usuario['umae'])
        {
            $data['umae'] = true;
        } else
        {
            $data['umae'] = false;
        }
        if (is_nivel_central($data['usuario']['grupos']))
        {
            $data['usuario']['central'] = true;
            if ($this->input->post('umae') != null)
            {
//                pr($this->input->post());
                switch ($this->input->post('umae', true))
                {
                    case 0:
                    case '0':
                        $data['umae'] = false;
                        break;
                    default :
                        $data['umae'] = true;
                        break;
                }
            }
        }
        $accion = 'analizar';
        //1. modificar plantilla con campos y gráfica estática
        //2. generar querys para reporte
        //3. generar json dinamico
        //4. obtener datos para campos y campos relacionados
        //5. aplicar filtros

        if ($this->input->post('view') != null)
        {
            switch ($this->input->post('view', true))
            {
                case 1:
                    $accion = 'tipo_curso';
                    break;
                case 2:
                    $accion = 'perfil';
                    break;
            }
        }

        $data["texts"] = $this->lang->line('region'); //Mensajes
        $this->template->setTitle($data["texts"]["title"]);

        $this->template->setSubTitle(render_subtitle($data["texts"]["subtitle"], 'comparativa_regiones'));
        $this->template->setDescripcion($data["texts"]["descripcion"]);
        $data['comparativas'] = $this->comparativa->get_tipos_comparativas();

        $cat_list = new Catalogo_listado(); //Obtener catálogos
        $data += $cat_list->obtener_catalogos(array(
            Catalogo_listado::TIPOS_CURSOS => array('condicion' => 'id_tipo_curso<>1'),
            Catalogo_listado::IMPLEMENTACIONES => array(
                'valor' => 'EXTRACT(year FROM fecha_fin)',
                'llave' => 'DISTINCT(EXTRACT(year FROM fecha_fin))',
                'orden' => '1 DESC'),
            Catalogo_listado::SUBCATEGORIAS => array('condicion' => 'id_subcategoria > 1')
        ));
//        pr($data['umae']?'true':'false');
        $data['niveles'] = $this->comparativa->get_niveles($data['umae']);
        $data['tipo_unidad'] = $data['usuario']['id_tipo_unidad'];
        if ($this->input->post('nivel') != null)
        {
            $nivel = $this->input->post('nivel', true);
            $data['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, '0', $nivel), 'id_tipo_unidad', 'nombre');
        } else
        {

            $data['tipos_unidades'] = [];
        }
        if ($this->input->post('perfil') != null)
        {            
            $filtros_['subcategoria'] = $this->input->post('perfil', true);
            $data['subperfiles'] = dropdown_options($this->buscador->get_grupos_categorias($filtros_), 'id_grupo_categoria', 'nombre');
        } else
        {
            $data['subperfiles'] = [];
        }
        $data['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');


        if (is_null($umae) && !is_nivel_central($usuario['grupos']))
        {
            $umae = $usuario['umae'] ? 1 : 0;
        }

        if (!is_null($num) && !is_null($year) && !is_null($type))
        {
            //$data["filters"]["num"] = $data["filters"]["type"] == 'tc' ? ;
            if ($accion != null && $accion == 'analizar')
            {
                $data["comparativa"] = $this->comparativa->get_comparativa_region($num, $year, $type, $umae);
                $data['periodo'] = $year;
                $data['subperfil'] = $num;
                $data['curso'] = $num;
                $data['nivel'] = $niv;
                $data['subcategoria'] = $sc;                
                if(!is_null($tunidad) && $tunidad != null){
                    $data['tipo_unidad'] = $tunidad;
                }
                if (!is_null($sc) && $sc != null)
                {
                    $filtros_ = [];
                    $filtros_['subcategoria'] = $sc;
                    $data['subperfiles'] = dropdown_options($this->buscador->get_grupos_categorias($filtros_), 'id_grupo_categoria', 'nombre');
                }
                $data['tcomparativa'] = $type == Comparativa_model::TIPO_CURSO ? 1 : 2;
                if ($niv != null)
                {
                    $data['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, '0', $niv), 'id_tipo_unidad', 'nombre');
                }
                switch ($type)
                {
                    case 'tc':
                        $data['vista'] = $this->load->view('comparative/region_tipo_curso', $data, true);
                        break;
                    default :
                        $data['vista'] = $this->load->view('comparative/region_perfil', $data, true);
                        break;
                }
            }
//            pr($data['comparativa']);
//            pr($usuario);
        }

        switch ($accion)
        {
            case 'perfil':
                $this->load->view('comparative/region_perfil', $data);
                break;
            case 'tipo_curso':
                $this->load->view('comparative/region_tipo_curso', $data);
                break;
            case 'analizar':
            default :
                if (!$this->input->is_ajax_request())
                {
                    $view = $this->load->view("comparative/region_v2.tpl.php", $data, TRUE);
                    $this->template->setDescripcion($this->mostrar_datos_generales());
                    $this->template->setSubTitle(render_subtitle('Región', 'comparativa_region'));
                    $this->template->setMainContent($view);
                    $this->template->getTemplate();
                }
                break;
        }
//        $this->output->enable_profiler(true);
    }

    public function delegacion_v2()
    {
        $this->load->model('Ranking_model', 'ranking');
        $output['usuario'] = $this->session->userdata('usuario');
//        pr($output['usuario']);
        $output["texts"] = $this->lang->line('delegacion'); //Mensajes
        if ($this->input->post('view'))
        {
            $filtros_delegacion = array();
            $filtros_delegacion['agrupamiento'] = 1; //activamos el agrupamiento            
//            pr($this->input->post('agrupamiento'));
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento') == 0)
            {
                $filtros_delegacion['agrupamiento'] = 0; // desactivamos el agrupamiento solo si somos nivel central
            }

//            pr($output['usuario']['id_region']);
            if ($filtros_delegacion['agrupamiento'] == 1)
            {
                $opciones_delegaciones = array(
                    'llave' => 'grupo_delegacion',
                    'valor' => 'nombre_grupo_delegacion',
                    'group' => array('grupo_delegacion', 'nombre_grupo_delegacion'),
                    'orden' => 'nombre_grupo_delegacion'
                );
            } else
            {
                $opciones_delegaciones = array(
                    'llave' => 'id_delegacion',
                    'valor' => 'nombre',
                    'orden' => 'nombre'
                );
            }
            if (is_nivel_tactico($output['usuario']['grupos']) || is_nivel_estrategico($output['usuario']['grupos']))
            {
                $opciones_delegaciones['condicion'] = 'id_region = ' . $output['usuario']['id_region'] . ' AND ' . "(not (clave_delegacional =  any ('{09,00,39}'::varchar(2)[])))";
            }
//            pr($opciones_delegaciones);

            $cat_list = new Catalogo_listado(); //Obtener catálogos
            $output += $cat_list->obtener_catalogos(array(
                Catalogo_listado::SUBCATEGORIAS => array('condicion' => 'id_subcategoria > 1'),
                Catalogo_listado::TIPOS_CURSOS => array('condicion' => 'activo = true'),
                Catalogo_listado::DELEGACIONES => $opciones_delegaciones,
                Catalogo_listado::GRUPOS_CATEGORIAS)
            );
            $output['agrupamiento'] = $filtros_delegacion['agrupamiento'];
            $output['niveles'] = $this->comparativa->get_niveles();
            $output['tipo_unidad'] = $output['usuario']['id_tipo_unidad'];
            if ($this->input->post('nivel') != null)
            {
                $nivel = $this->input->post('nivel', true);
                $output['tipos_unidades'] = dropdown_options($this->comparativa->get_tipos_unidades(false, '0', $nivel), 'id_tipo_unidad', 'nombre');
            } else
            {

                $output['tipos_unidades'] = [];
            }
            if ($this->input->post('perfil') != null)
            {
                $this->load->model('Buscador_model', 'buscador');
                $filtros_['subcategoria'] = $this->input->post('perfil', true);
                $output['subperfiles'] = dropdown_options($this->buscador->get_grupos_categorias($filtros_), 'id_grupo_categoria', 'nombre');
            } else
            {
                $output['subperfiles'] = [];
            }
            $output['periodos'] = dropdown_options($this->ranking->get_periodos(), 'periodo', 'periodo');
            $output['reportes'] = $this->comparativa->get_tipos_reportes();
            switch ($this->input->post('view', true))
            {
                case 1:
                    $vista = 'delegacion_tipo_curso';
                    break;
                default:
                    $vista = 'delegacion_perfil';
                    break;
            }
            $output['vista'] = $this->load->view('comparative/' . $vista, $output, true);
        }
        if ($this->input->post('tipo_comparativa'))
        {
            $filtros = $this->input->post();
            if (is_nivel_operacional($output['usuario']['grupos']) || is_nivel_tactico($output['usuario']['grupos']))
            {
                $filtros['region'] = $output['usuario']['id_region'];
                $filtros['delegacion1'] = $output['usuario']['grupo_delegacion'];
            }
            $filtros['agrupamiento'] = 1;
            if (is_nivel_central($output['usuario']['grupos']) && $this->input->post('agrupamiento') != null && $this->input->post('agrupamiento', true) == 0)
            {
                $filtros['agrupamiento'] = 0;
            }
            $output['datos'] = $datos = $this->comparativa->get_comparar_delegacion($filtros);
            $output['tabla'] = $this->load->view('comparative/tabla.tpl.php', $output, true);
            $output['grafica'] = $this->load->view('comparative/grafica.tpl.php', $output, true);
        }

        if ($this->input->is_ajax_request())
        {
            echo $output['vista'];
        } else
        {
            $output['comparativas'] = $this->comparativa->get_tipos_comparativas();
            $this->template->setTitle($output["texts"]["title"]);
            $this->template->setSubTitle(render_subtitle($output["texts"]["subtitle"], 'comparativa_delegaciones'));
            $this->template->setDescripcion($this->mostrar_datos_generales());
            $view = $this->load->view('comparative/delegacion_v2', $output, true);
            $this->template->setMainContent($view);
            $this->template->getTemplate();
        }
//        $this->output->enable_profiler();
    }

}
