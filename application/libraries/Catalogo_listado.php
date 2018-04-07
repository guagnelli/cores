<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase que contiene los atributos y métodos necesarios para obtener datos de catálogos y formar listados desplegables
 * @version 	: 1.0.0
 * @author      : JZDP
 */
class Catalogo_listado {
	/**
	 * Listado de tablas de las cuales se obtienen listados desplegables. Se crean constantes.
	 */
	const ALINEACIONES_ESTRATEGICAS = 'alineaciones_estrategicas',
		CATEGORIAS = 'categorias',
		CURSOS = 'cursos',
		DELEGACIONES = 'delegaciones',
		DEPARTAMENTOS_INSTITUTO = 'departamentos_instituto',
		GRUPOS_CATEGORIAS = 'grupos_categorias',
		IMPLEMENTACIONES = 'implementaciones',
		LINEAS_ACCION = 'lineas_accion',
		LINEAS_ESTRATEGICAS = 'lineas_estrategicas',
		PERFILES_ALUMNOS = 'perfiles_alumnos',
		PERIODO = 'periodo',
		PROGRAMAS_PROYECTO = 'programas_proyecto',
		REGIONES = 'regiones',
		ROLES_ACADEMICOS = 'roles_academicos',
		SEXOS = 'sexos',
		SUBCATEGORIAS = 'subcategorias',
		TEMAS_PRIORITARIOS = 'temas_prioritarios',
		TIPOS_CURSOS = 'tipos_cursos',
		TIPOS_UNIDADES = 'tipos_unidades',
		UNIDADES_INSTITUTO = 'unidades_instituto';
	// Warning: array can be changed lateron, so this is not a real constant value:
	
	//Constants::$array[] = 'newValue';
	/**
	 * Arreglo que contiene nombre de la tabla, del campo llave, del campo que se muestra al usuario(valor), campo utilizado para el ordenamiento
	*/
	private $catalogos = array(self::ALINEACIONES_ESTRATEGICAS => array('table'=>self::ALINEACIONES_ESTRATEGICAS, 'llave'=>'id_alineacion_estrategica', 'valor'=>'descripcion', 'orden'=>'descripcion'),
		self::CATEGORIAS => array('table'=>self::CATEGORIAS, 'llave'=>'id_categoria', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::CURSOS => array('table'=>self::CURSOS, 'llave'=>'id_curso', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::DELEGACIONES => array('table'=>self::DELEGACIONES, 'llave'=>'id_delegacion', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::DEPARTAMENTOS_INSTITUTO => array('table'=>self::DEPARTAMENTOS_INSTITUTO, 'llave'=>'id_departamento_instituto', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::GRUPOS_CATEGORIAS => array('table'=>self::GRUPOS_CATEGORIAS, 'llave'=>'id_grupo_categoria', 'valor'=>'nombre', 'orden'=>'order'),
		self::IMPLEMENTACIONES => array('table'=>self::IMPLEMENTACIONES, 'llave'=>'id_implementacion', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::LINEAS_ACCION => array('table'=>self::LINEAS_ACCION, 'llave'=>'id_linea_accion', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::LINEAS_ESTRATEGICAS => array('table'=>self::LINEAS_ESTRATEGICAS, 'llave'=>'id_linea_estrategica', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::PERFILES_ALUMNOS => array('table'=>self::PERFILES_ALUMNOS, 'llave'=>'id_perfil_alumno', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::PERIODO => array('table'=>self::PERIODO, 'llave'=>'id_periodo', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::PROGRAMAS_PROYECTO => array('table'=>self::PROGRAMAS_PROYECTO, 'llave'=>'id_programa_proyecto', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::REGIONES => array('table'=>self::REGIONES, 'llave'=>'id_region', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::ROLES_ACADEMICOS => array('table'=>self::ROLES_ACADEMICOS, 'llave'=>'id_rol_academico', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::SEXOS => array('table'=>self::SEXOS, 'llave'=>'id_sexo', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::SUBCATEGORIAS => array('table'=>self::SUBCATEGORIAS, 'llave'=>'id_subcategoria', 'valor'=>'nombre', 'orden'=>'order'),
		self::TEMAS_PRIORITARIOS => array('table'=>self::TEMAS_PRIORITARIOS, 'llave'=>'id_tema_prioritario', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::TIPOS_CURSOS => array('table'=>self::TIPOS_CURSOS, 'llave'=>'id_tipo_curso', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::TIPOS_UNIDADES => array('table'=>self::TIPOS_UNIDADES, 'llave'=>'id_tipo_unidad', 'valor'=>'nombre', 'orden'=>'nombre'),
		self::UNIDADES_INSTITUTO => array('table'=>self::UNIDADES_INSTITUTO, 'llave'=>'id_unidad_instituto', 'valor'=>'nombre', 'orden'=>'nombre')
	);
	
    public function __construct() {
        $this->CI = & get_instance();
        //parent::__construct(); //Inicializa la clase padre
    }

    /**
     * Obtiene datos de la BD y forma listados desplegables
     * @param $catalogos array table(Nombre de la tabla), llave(campo utilizado como identificador en el listado), 
     * 	valor(campo(s) que visualiza el usuario, asociado a la llave), orden(campo utilizado para el ordenamiento de los datos)
     * 	condicion(condicion(es) que limitan la búsqueda), alias(identificador del arreglo)
     **/
    public function obtener_catalogos($catalogos=null){
    	$datos = array();
    	if(is_array($catalogos) AND !is_null($catalogos)){
    		foreach ($catalogos as $key_cat => $catalogo) {
    			$arreglo_datos = (is_array($catalogo) AND !empty($catalogo)) ? $this->crear_catalogo_arreglo($key_cat, $catalogo) : ((isset($this->catalogos[$catalogo])) ? $this->catalogos[$catalogo] : array()); //Obtener datos que tenemos por default
    			if(isset($arreglo_datos) AND !empty($arreglo_datos)) {
    				if(is_array($catalogo)) { //Definir el nombre del arreglo, como podrá ser identificado
    					if(isset($catalogo['alias']) && !empty($catalogo['alias'])){
    						$indice = $catalogo['alias'];
    					} else {
    						$indice = $key_cat;
    					}
    				} else {
    					$indice = $catalogo;
    				}
    				$datos[$indice] = $this->obtener_catalogo_datos($arreglo_datos); //Se obtienen datos de la base de datos
    			} else {
    				$datos[$catalogo] = $arreglo_datos;
    			}
    		}
    	} else {
    		//Manejo de errores
    	}
    	//pr($datos);
    	return $datos; //$indice !== false ? self::$catalogos[$indice] : self::$catalogos;
    }

    private function crear_catalogo_arreglo($cat='', $arreglo = array()){
    	$arreglo_datos = array(); //'llave'=>'', 'valor'=>'', 'orden'=>'', 'condicion'
    	$arreglo_datos['table'] = $this->catalogos[$cat]['table'];
    	$arreglo_datos['llave'] = (isset($arreglo['llave']) AND !empty($arreglo['llave'])) ? $arreglo['llave'] : $this->catalogos[$cat]['llave'];
    	$arreglo_datos['valor'] = (isset($arreglo['valor']) AND !empty($arreglo['valor'])) ? $arreglo['valor'] : $this->catalogos[$cat]['valor'];
    	$arreglo_datos['orden'] = (isset($arreglo['orden']) AND !empty($arreglo['orden'])) ? $arreglo['orden'] : $this->catalogos[$cat]['orden'];
    	$arreglo_datos['condicion'] = (isset($arreglo['condicion']) AND !empty($arreglo['condicion'])) ? $arreglo['condicion'] : '';
    	$arreglo_datos['group'] = (isset($arreglo['group']) AND !empty($arreglo['group'])) ? $arreglo['group'] : '';

    	return $arreglo_datos;
    }

    /**
     * Método que obtiene los datos de la base de datos y retorna un arreglo llave=>valor
     **/
    private function obtener_catalogo_datos($datos = array()){
    	$resultado = array();
    	if(!empty($datos)){
		   	$this->CI->load->database(); ////Obtener información de BD
		   	if(!empty($datos['condicion'])){
		   		$this->CI->db->where($datos['condicion']);
		   	}
		   	if(!empty($datos['group'])){
		   		$this->CI->db->group_by($datos['group']);
		   	}
		   	$this->CI->db->select($datos['llave'].' as llave, '.$datos['valor'].' as valor');
		   	$this->CI->db->order_by($datos['orden']);
		   	
		   	$query = $this->CI->db->get($datos['table']);
		   	//echo $this->CI->db->last_query();
		   	$registro = $query->result_array();
		   	foreach ($registro as $key => $value) {
		   		//pr($registro);
		   		$resultado[$value['llave']] = $value['valor'];
		   	}

		   	$query->free_result(); //Libera la memoria
	    }
        return $resultado;
    }
}