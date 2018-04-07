<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que contiene la gestion de catalogos
 * @version 	: 1.0.0
 * @author      : LEAS
 * */
class Login_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->config->load('general');
    }

    public function recuperar_password($username) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select(array(
            'id_usuario', 'nombre', 'email', 'recovery_code'
        ));
        $this->db->where('matricula', $username);
        $this->db->or_where('email', $username);
        $this->db->limit(1);
        $resultado = $this->db->get('sistema.usuarios')->result_array();

        if ($resultado) {
            $usuario = $resultado[0];
            if (empty($usuario['recovery_code'])) {
                $this->load->library('seguridad');
                $usuario['recovery_code'] = $this->seguridad->crear_token();
                $this->db->reset_query();
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('recovery_code', $usuario['recovery_code']);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
            }
            $this->send_recovery_mail($usuario);
        }
    }

    public function update_password($code = null, $new_password = null) {
        $salida = false;
        if ($code != null && $new_password != null) {
            $this->db->flush_cache();
            $this->db->reset_query();

            $this->db->select(array(
                'id_usuario', 'token'
            ));
            $this->db->where('recovery_code', $code);
            $this->db->limit(1);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($resultado);
            if ($resultado) {
                $this->load->library('seguridad');
                $usuario = $resultado[0];
                $this->db->reset_query();
                $pass = $this->seguridad->encrypt_sha512($usuario['token'] . $new_password . $usuario['token']);
                $this->db->where('id_usuario', $usuario['id_usuario']);
                $this->db->set('password', $pass);
                $this->db->set('recovery_code', null);
                $this->db->update('sistema.usuarios');
                //pr($this->db->last_query());
                $salida = true;
            }
        }
        return $salida;
    }

    private function send_recovery_mail($usuario) {
        $this->load->config('email');
        $this->load->library('My_phpmailer');
        $mailStatus = $this->my_phpmailer->phpmailerclass();
        $emailStatus = $this->load->view('sesion/mail_recovery_password', $usuario, true);
//        $mailStatus->addAddress('zurgcom@gmail.com'); //pruebas chris
        $mailStatus->addAddress($usuario['email']);
        $mailStatus->Subject = 'Recuperación de contraseña para el Tablero';
        $mailStatus->msgHTML(utf8_decode($emailStatus));
        $mailStatus->send();
    }

    /**
     * 
     * @autor       : LEAS.
     * @Fecha       : 13052016.
     * @param String $cve_usuario
     * @return array con ROL_CVE, MODULO_CVE, ROL_NOMBRE, MOD_NOMBRE
     * 
     * 
     */
    public function get_permisos_usuario($matricula = null) {
        if (is_null($matricula)) {
            return null;
        }
        $select = array('usu.id_usuario', 'g.id_grupo', 'g.nombre', 'sg.configurador conf_busqueda'
            , 'sr.nombre', 'sr.servicio', 'sr.configuraciones cong_prop_servicio', 'sr.descripcion'
            , 'sr.id_tipo_servicio tipo_servicio', 'ts.nombre nombre_tipo_servicio'
        );

        $this->db->select($select);
        $this->db->from('sistema.servicios_grupos sg');
        $this->db->join('sistema.grupos g', 'g.id_grupo = sg.id_grupo and sg.activo');
        $this->db->join('sistema.grupos_usuarios gu', 'gu.id_grupo = g.id_grupo and g.activo');
        $this->db->join('sistema.usuarios usu', 'usu.id_usuario = gu.id_usuario');
        $this->db->join('sistema.servicios_rest sr', 'sr.id_servicio_rest = sg.id_servicio_rest and sg.activo');
        $this->db->join('sistema.tipos_servicios ts', 'ts.id_tipo_permiso = sr.id_tipo_servicio');

        $this->db->where('usu.matricula', $matricula);
        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();

        $query->free_result();
        return $result;
    }

    public function get_servicios_rest($parametros = null) {
        if (!is_null($parametros)) {//Agrega condiciones a la consulta
            foreach ($parametros as $llave => $valor) {
                $this->db->where($llave, $valor);
            }
        }
        $query = $this->db->get('sistema.servicios_rest');
        $result = $query->result_array();
        $query->free_result();
//        pr($this->db->last_query());
        return $result;
    }

    /**
     * 
     * @autor       : LEAS.
     * @Fecha       : 07032017.
     * @param String : $matricula
     * @return todos los servicios relacionados con el usuario
     * 
     */
    public function get_servicios_usuario($matricula = null) {
        if (is_null($matricula)) {
            return null;
        }
        $select = array(
            'g.id_grupo', 'g.nombre', 'u.id_usuario'
            , 'u.nombre', 'sr.id_servicio_rest', 'sr.servicio'
            , 'sr.id_tipo_servicio', 'ts.nombre name_tipo'
            , 'sr.configuraciones', 'sr.menu', 'sg.configurador conf_servicio_acceso'
        );

        $this->db->select($select);
        $this->db->from('sistema.grupos_usuarios gu');
        $this->db->join('sistema.usuarios u', 'u.id_usuario = gu.id_usuario');
        $this->db->join('sistema.grupos g', 'g.id_grupo = gu.id_grupo');
        $this->db->join('sistema.servicios_grupos sg', 'sg.id_grupo = g.id_grupo');
        $this->db->join('sistema.servicios_rest sr', 'sr.id_servicio_rest = sg.id_servicio_rest');
        $this->db->join('sistema.tipos_servicios ts', 'ts.id_tipo_permiso = sr.id_tipo_servicio');
        $this->db->where('sr.activo', true);
        $this->db->where('g.activo', true);
        $this->db->where('ts.activo', true);

        $this->db->where('u.matricula', $matricula);
        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();

        $query->free_result();
        return $result;
    }

    /**
     * @autor       : LEAS.
     * @Fecha       : 07/03/2017.
     * @param type $array_servicios
     * @return type
     */
    public function get_ui_usuario($array_servicios = null) {
        if (is_null($array_servicios) || empty($array_servicios)) {
            return null;
        }

        $in = ' and srm.id_servicio_rest in(';
        $separador = '';
        foreach ($array_servicios as $id_servicio) {
            $in .= $separador . $id_servicio;
            $separador = ',';
        }
        $in .= ')';

        $select = array(
            'm.id_menu', 'm.nombre', 'm.label', 'm.enlace', 'm.configuracion conf_menu',
            'srm.id_servicio_rest', 'ar.id_angular_route', 'ar.nombre',
            'ar.ruta_js', 'ar.templete', 'ar.templete', 'ar.link', 'ar.delay', 'ar.main', 'ar.descripcion',
            '(select mp.nombre from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_name_menu',
            '(select mp.label from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_padre_label',
            '(select mp.enlace from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_name_enlace'
            , 'ts.nombre name_tipo_servicio',
            "case  
                when (m.id_menu_padre is null) then m.orden
             else
                (select mp.orden from ui.menus mp where mp.id_menu = m.id_menu_padre)
             end as order_menu_padre"
        );

        $this->db->order_by('order_menu_padre');
        $this->db->order_by('m.label');
        $this->db->select($select);
        $this->db->from('ui.menus m');
        $this->db->join('ui.servicios_routes_menus srm', 'srm.id_menu = m.id_menu' . $in);
        $this->db->join('sistema.servicios_rest sr', 'sr.id_servicio_rest = srm.id_servicio_rest');
        $this->db->join('sistema.tipos_servicios ts', 'ts.id_tipo_permiso = sr.id_tipo_servicio');
        $this->db->join('ui.angular_routes ar', 'ar.id_angular_route = srm.id_angular_route');
        $this->db->where('ar.activo', true);
        $this->db->where('m.activo', true);
        $this->db->where('srm.activo', true);
        $this->db->where('sr.menu', true);

        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();
//        pr($this->db->last_query());
        $query->free_result();
        return $result;
    }

    /**
     * @autor       : LEAS.
     * @Fecha       : 07/03/2017.
     * @param type $array_servicios
     * @return type
     */
    public function get_ui_padres($array_servicios = null) {
        if (is_null($array_servicios) || empty($array_servicios)) {
            return null;
        }

        $in = ' and srm.id_servicio_rest in(';
        $separador = '';
        foreach ($array_servicios as $id_servicio) {
            $in .= $separador . $id_servicio;
            $separador = ',';
        }
        $in .= ')';

        $select = array(
            'm.id_menu', 'm.nombre', 'm.label', 'm.enlace', 'm.configuracion conf_menu',
            'srm.id_servicio_rest', 'ar.id_angular_route', 'ar.nombre',
            'ar.ruta_js', 'ar.templete', 'ar.templete', 'ar.link', 'ar.delay', 'ar.main', 'ar.descripcion',
            '(select mp.nombre from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_name_menu',
            '(select mp.label from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_padre_label',
            '(select mp.enlace from ui.menus mp where mp.id_menu = m.id_menu_padre) as padre_name_enlace'
            , 'ts.nombre name_tipo_servicio',
            "case  
                when (m.id_menu_padre is null) then m.orden
             else
                (select mp.orden from ui.menus mp where mp.id_menu = m.id_menu_padre)
             end as order_menu_padre"
        );

        $this->db->order_by('order_menu_padre');
        $this->db->order_by('m.label');
        $this->db->select($select);
        $this->db->from('ui.menus m');
        $this->db->join('ui.servicios_routes_menus srm', 'srm.id_menu = m.id_menu' . $in);
        $this->db->join('sistema.servicios_rest sr', 'sr.id_servicio_rest = srm.id_servicio_rest');
        $this->db->join('sistema.tipos_servicios ts', 'ts.id_tipo_permiso = sr.id_tipo_servicio');
        $this->db->join('ui.angular_routes ar', 'ar.id_angular_route = srm.id_angular_route');
        $this->db->where('ar.activo', true);
        $this->db->where('m.activo', true);
        $this->db->where('srm.activo', true);
        $this->db->where('sr.menu', true);

        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();
//        pr($this->db->last_query());
        $query->free_result();
        return $result;
    }

    public function get_datos_usuario($matricula = null, $password = null) {
        if (is_null($matricula) and is_null($password)) {
            return null;
        }

        $select = array(
            'u.id_usuario', 'u.nombre name_user', 'u.matricula', 'u.curp'
            , 'u.clave_delegacional', 'd.nombre name_delegacion'
            , 'u.clave_categoria', 'c.nombre name_categoria'
            , 'u.id_unidad_instituto', 'ui.nombre name_unidad_ist'
            , 'r.id_region', 'd.nombre name_region', 'r.clave_regional', 'u.token', 'u.password'
            , 'u.email', 'ui.umae', 'ui.id_tipo_unidad'
            // , "case u.password when md5(concat(u.token, '$password')) then 1 else 0 end as valido_password "
            , 'g.id_grupo', 'g.nombre nombre_grupo', 'g.nivel'
        );

        $this->db->select($select);
        $this->db->from('sistema.usuarios u');
        $this->db->join('sistema.grupos_usuarios gu', 'gu.id_usuario = u.id_usuario', 'left');
        $this->db->join('sistema.grupos g', 'g.id_grupo = gu.id_grupo', 'left');
        $this->db->join('catalogos.categorias c', 'c.clave_categoria = u.clave_categoria', 'left');
        $this->db->join('catalogos.delegaciones d', 'd.clave_delegacional = u.clave_delegacional', 'left');
        $this->db->join('catalogos.unidades_instituto ui', 'ui.id_unidad_instituto = u.id_unidad_instituto', 'left');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region', 'left');

        $this->db->where('u.matricula', $matricula);
        $query = $this->db->get();
//        $result = $query->row();
        $result = $query->result_array();
        $return = array();
//        pr($this->db->last_query());
        if (count($result) > 0) {
            $this->load->library('seguridad');
            $cadena = $result[0]['token'] . $password . $result[0]['token'];
            $clave = $this->seguridad->encrypt_sha512($cadena);
//            pr($clave);
//            pr($result[0]['password']);
//            
//            $return['valido'] = $result[0]['valido_password'];
            $return['valido'] = ($result[0]['password'] == $clave) ? 1 : 0;
            $return['datos_user'] = $result[0];
        }

        $query->free_result();
        return $return;
    }

    public function get_delegaciones($param = null) {
        if (isset($param['clave_delegacional'])) {
            $this->db->where('d.clave_delegacional', $param['clave_delegacional']);
        }

        if (isset($param['clave_regional'])) {
            $this->db->where('r.clave_regional', $param['clave_regional']);
        }

        $this->db->where('d.activo', TRUE);
        $this->db->where('d.id_region is not null');
        $select = array(
            'd.id_delegacion', 'd.nombre', 'clave_delegacional', 'r.clave_regional', 'd.configuraciones'
        );

        $this->db->select($select);
        $this->db->from('catalogos.delegaciones d');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region');

        $query = $this->db->get();
//        $result = $query->row();

        return $query->result_array();
    }

    /**
     * @LEAS 
     * @param type $param
     * @return type
     */
    public function get_regiones($param = null) {
        if (!is_null($param)) {
            foreach ($param as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        $this->db->where('r.activo', true);
        $select = array(
            'r.id_region', 'r.nombre', 'r.clave_regional', 'r.configuraciones'
        );

        $this->db->select($select);
        $this->db->from('catalogos.regiones r');

        $query = $this->db->get();
//        $result = $query->row();

        return $query->result_array();
    }

    public function get_unidades_and_umaes($param = null) {

        if (!is_null($param)) {
            foreach ($param as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        $this->db->where('u.activa', true);
        $select = array(
            'u.id_unidad_instituto', 'u.clave_unidad', 'u.nombre', 'u.id_delegacion',
            'u.umae', 'u.id_tipo_unidad', 'u.nivel_atencion', 'r.clave_regional'
        );

        $this->db->select($select);
        $this->db->from('catalogos.unidades_instituto u');
        $this->db->join('catalogos.delegaciones d', 'd.id_delegacion = u.id_delegacion');
        $this->db->join('catalogos.regiones r', 'r.id_region = d.id_region');

        $query = $this->db->get();
//        pr($this->db->last_query());

        return $query->result_array();
    }

    /**
     * @fecha 24/03/2017
     * @autor LEAS
     * @description Obtiene los años implicados en las implementaciones
     */
    public function get_anios_implementacion() {
        $select = array('(TRUNC(EXTRACT(YEAR FROM "implementaciones"."fecha_inicio")))::text as anio');
        $this->db->select($select);
        $this->db->group_by('anio');
        $this->db->order_by('anio', 'asc');

        $query = $this->db->get('catalogos.implementaciones implementaciones');
        $res = $query->result_array();
        return $res;
    }

    function get_bloque_permisos_usuario_inicio($matricula = null) {
        $permisos = new mapa_permisos();
        $result = $permisos->get_permisos_usuario($this, $matricula);
        return $result;
    }

    /**
     * @author LEAS
     * @fecha 01032017
     * @param type $matricula
     * @return type
     */
    function validar_ruta_acceso($matricula = null) {
        $accesos_user = $this->get_permisos_usuario($matricula); //Obtiene permisos
//        pr($accesos_user);
        return $result;
    }

    /**
     * @author LEAS
     * @fecha 01032017
     * @param type $matricula
     * @return type
     */
    function get_genera_info_usuario($matricula = null) {
        $tmpservicios = $this->get_servicios_usuario($matricula); //Obtiene permisos
        $class_estructura = new estructura_permisos();

        //Limpia servicios de menu Obtiene todos los servicios que corresponden a un menu
        $array_servicios = $class_estructura->get_id_servicios_menu($tmpservicios);
        //El servicio lo conviete en llave de los servicios
        foreach ($tmpservicios as $value) {
            $servicios_user['servicios'][$value['servicio']] = $value;
        }

        //Obtiene rutas de la interfaz de usuario
        $ui_user = $this->get_ui_usuario($array_servicios); //Obtiene permisos
//        pr($ui_user);
        $servicios_user['ui'] = $class_estructura->get_genera_menus($ui_user); //Obtiene propiedades de todos los servicios
        //****Obtiene años de implementación validos
        $anios_implementacion = $this->get_anios_implementacion(); //Años de implementación
        $servicios_user['anios_implementacion'] = array();
        foreach ($anios_implementacion as $value) {
            $servicios_user['anios_implementacion'][] = $value['anio']; //Años de implementación
        }
        //Fin de años de implementacion
//        pr($servicios_user);
//        pr($menu_formato);
        return $servicios_user;
    }

}

class estructura_permisos {

    /**
     * 
     * @param type $servicios array de todos los servicios relacionados con el usuario
     * @return type
     */
    function get_id_servicios_menu($servicios) {
        $result_array = array();
        foreach ($servicios as $value) {
            if ($value['menu']) {
                $result_array[] = $value['id_servicio_rest'];
            }
        }
        return $result_array;
    }

    function get_recursion_menu() {
        
    }

    function get_genera_menus($menu) {
        $array_resultado = array();
//        pr($menu);
//        pr('sasmbsd');
        foreach ($menu as $value) {
//            $conf_array = (array) json_decode($value['configuraciones']);
//            pr($conf_array);
            if (empty($value['padre_name_menu'])) {//si es vacio, es un padré, por lo que se cataloga como 
//                and !isset($array_resultado['menu'][$value['padre_name_menu']])
                $array_resultado['menu'][$value['name_tipo_servicio']] = array(
                    'label' => $value['label'],
                    'url' => $value['enlace'],
                );
            } else {//Se cataloga como un hijo que se encuentra contenido dentro de un padré
                if (!isset($array_resultado['menu'][$value['name_tipo_servicio']])) {//Crea al padre si no existre
//                    pr($value['name_tipo_servicio'] . ' ' . $value['padre_padre_label']);
//                pr($array_resultado['menu'][$value['name_tipo_servicio']]);
                    $array_resultado['menu'][$value['name_tipo_servicio']] = array(
                        'label' => $value['padre_padre_label'],
                        'url' => null,
                        'childs' => array(),
                    );
                }
                $array_resultado['menu'][$value['name_tipo_servicio']]['childs'][$value['enlace']] = array(
                    'label' => $value['label'],
                    'url' => $value['enlace'],
                );
            }


//            $array_resultado['menu'][$value['name_tipo_servicio']] = array(
//                'label' => $value[''],
//                'url' => $value['servicio'],
//            );
            $array_resultado['config'][] = array(
                'controller' => $value['nombre'],
                'url_controller' => $value['ruta_js'],
                'url_template' => $value['templete'],
                'url_link' => $value['link'],
                'delay' => $value['delay'],
                'main' => $value['main'],
                'tipo' => $value['name_tipo_servicio'],
            );
        }
//         pr($array_resultado['menu']);
        return $array_resultado;
    }

}

class mapa_permisos {

    private $class_consultas;

    const UNIDAD = 2;
    const DELEGACION = 1;
    const REGION = 3;
    const UMAE = 4;

    function get_permisos_usuario($class_login, $matricula = null) {
        $this->class_consultas = $class_login;
        $data_tmp = $class_login->get_servicios_usuario($matricula);
//        pr($data_tmp);
        $array_cat = array();
        foreach ($data_tmp as $access) {
            $tipo_permiso = $this->get_tipo_ser_dimencional($access); //Tipo de permiso
            if ($tipo_permiso['is_tipo_dimencional'] == 1) {
                $array_cat['dimencional'][$tipo_permiso['name_tipo']] = $tipo_permiso['mapa'];
            } else {
                $array_cat['tipo_normal'][] = $access;
            }
        }

        $result = $this->get_permisos_no_dimencionales($array_cat['tipo_normal']);
        if (isset($array_cat['dimencional'])) {

            $result['permisos']['mapa'] = $array_cat['dimencional'];
        }

        $result['anios_implementacion'] = $anios_implementacion;
//        $result['permisos'] = $accesso;
//        pr($result);
        return $result;
    }

    function get_tipo_ser_dimencional($configurador) {
        switch ($configurador['tipo_servicio']) {
            case mapa_permisos::DELEGACION://Delegación
                $respuesta['mapa'] = $this->get_delegaciones($configurador);
                $respuesta['name_tipo'] = 'delegaciones';
                $respuesta['is_tipo_dimencional'] = 1;
                return $respuesta;
            case mapa_permisos::UNIDAD://Unidades
                $respuesta['mapa'] = $this->get_unidades($configurador);
                $respuesta['name_tipo'] = 'unidades';
                $respuesta['is_tipo_dimencional'] = 1;
                return $respuesta;
            case mapa_permisos::REGION://Region
                $respuesta['mapa'] = $this->get_regiones($configurador);
                $respuesta['name_tipo'] = 'regiones';
                $respuesta['is_tipo_dimencional'] = 1;
                return $respuesta;
            case mapa_permisos::UMAE://Umaes
                $respuesta['mapa'] = $this->get_umaes($configurador);
                $respuesta['name_tipo'] = 'umaes';
                $respuesta['is_tipo_dimencional'] = 1;
                return $respuesta;
            default :
        }
        $respuesta['is_tipo_dimencional'] = 0;
        return $respuesta;
    }

    function get_permisos_no_dimencionales($array_permisos) {
        $array_resp = array();
        foreach ($array_permisos as $value) {

            if (!empty($value['cong_prop_servicio'])) {
//                pr($value['cong_prop_servicio']);
//                $tmp_decode = json_decode($value['cong_prop_servicio']);
//                $result_config = (array) $tmp_decode;
                $result_config['acceso'] = TRUE;
                $array_resp['permisos']['menu'][$value['servicio']] = $result_config;
            }
        }
//        pr($array_resp);
        return $array_resp;
    }

    function get_unidades($expresion_conf) {
//        pr($expresion_conf['cong_prop_servicio']);
//            'id_usuario', 'id_grupo', 'nombre', 'conf_busqueda', 'nombre', 'servicio', 'cong_prop_servicio', 
//            'descripcion', 'tipo_servicio', 'nombre_tipo_servicio'
        switch ($expresion_conf['conf_busqueda']) {
            case '*':
                $unidades = $this->class_consultas->get_unidades_and_umaes();
                break;
            case '+':
                $param[''] = $expresion_conf[''];
                break;
            case 't':
                break;
        }
//        "1" => array( "label" => "Región Nor-occidente", "acceso"=>2, "estilos" => array( "fill" => "#00A99D" ) )
//           , "2" => array( "label" => "Región Centro", "acceso"=>2, "estilos" => array( "fill" => "#408DD3" ) )
//           , "3" => array( "label" => "Región Centro Sureste", "acceso"=>2, "estilos" => array( "fill" => "#FBB03B" ) )
//           , "4" => array( "label" => "Región Noreste", "acceso"=>2, "estilos" => array( "fill" => "#834DB2" ) ) 
    }

    function get_regiones($expresion_conf) {
//            'id_usuario', 'id_grupo', 'nombre', 'conf_busqueda', 'nombre', 'servicio', 'cong_prop_servicio', 
//            'descripcion', 'tipo_servicio', 'nombre_tipo_servicio'
        $posicion = strpos($expresion_conf['servicio'], 'detalle');
        $acceso = 0;
        if ($posicion >= 0) {
            $acceso = 1;
        } else {
            $posicion = strpos($expresion_conf['servicio'], 'resumen');
            if ($$posicion >= 0) {
                $acceso = 2;
            }
        }

        if ($acceso == 1 || $acceso = 2) {
//        $acceso = strpos('detalle', $expresion_conf['servicio']);
            switch ($expresion_conf['conf_busqueda']) {
                case '*':
                    $regiones = $this->class_consultas->get_regiones();
//                pr($regiones);
                    $r_datos = array();
                    foreach ($regiones as $value) {
                        $json_decode = (array) json_decode($value['configuraciones']);
                        $r_datos[$value['clave_regional']] = array('label' => $value['nombre'], 'acceso' => $acceso, 'estilos' => $json_decode);
                    }
                    return $r_datos;
                case '+':
                    $param = array('r.clave_regional' => 2); //Clave regional 
                    $regiones = $this->class_consultas->get_regiones($param);
                    //                pr($regiones);
                    $r_datos = array();
                    foreach ($regiones as $value) {
                        $json_decode = (array) json_decode($value['configuraciones']);
                        $r_datos[$value['clave_regional']] = array('label' => $value['nombre'], 'acceso' => $acceso, 'estilos' => $json_decode);
                    }
                    return $r_datos;
                    break;
                case 't':

                    break;
            }
        }
    }

    function get_umaes($expresion_conf) {
//            'id_usuario', 'id_grupo', 'nombre', 'conf_busqueda', 'nombre', 'servicio', 'cong_prop_servicio', 
//            'descripcion', 'tipo_servicio', 'nombre_tipo_servicio'
        $posicion = strpos($expresion_conf['servicio'], 'detalle');
        $acceso = 0;
        if ($posicion >= 0) {
            $acceso = 1;
        } else {
            $posicion = strpos($expresion_conf['servicio'], 'resumen');
            if ($$posicion >= 0) {
                $acceso = 2;
            }
        }

        if ($acceso == 1 || $acceso = 2) {
            $param['umae'] = true;
            switch ($expresion_conf['conf_busqueda']) {
                case '*':
//                    $param['clave_unidad']
//                    $param['clave_delegacional']
                    $umaes = $this->class_consultas->get_unidades_and_umaes($param);
                    $r_datos = array();
                    foreach ($umaes as $value) {
                        $r_datos[$value['clave_unidad']] = array('label' => $value['nombre'], 'acceso' => $acceso, 'region' => $value['clave_regional']);
                    }
                    return $r_datos;
                    ;
                case '+':
                    $param = array('u.umae', 'r.clave_regional', 'd.clave_delegacional', 'u.clave_unidad');
                    $umaes = $this->class_consultas->get_unidades_and_umaes($param);
                    $r_datos = array();
                    foreach ($umaes as $value) {
                        $r_datos[$value['clave_unidad']] = array('label' => $value['nombre'], 'acceso' => $acceso, 'region' => $value['clave_regional']);
                    }
                    break;
                case 't':
                    break;
            }
        }
    }

    function get_delegaciones($expresion_conf) {
//            'id_usuario', 'id_grupo', 'nombre', 'conf_busqueda', 'nombre', 'servicio', 'cong_prop_servicio', 
//            'descripcion', 'tipo_servicio', 'nombre_tipo_servicio'
        $posicion = strpos($expresion_conf['servicio'], 'detalle');
        $acceso = 0;
        if ($posicion >= 0) {
            $acceso = 1;
        } else {
            $posicion = strpos($expresion_conf['servicio'], 'resumen');
            if ($$posicion >= 0) {
                $acceso = 2;
            }
        }

        if ($acceso == 1 || $acceso = 2) {
            switch ($expresion_conf['conf_busqueda']) {
                case '*':
//                  $param['clave_delegacional'];
//                  $param['clave_regional'];
                    $delegaciones = $this->class_consultas->get_delegaciones();
//                    pr($delegaciones);
                    $r_datos = array();
                    foreach ($delegaciones as $value) {
                        $r_datos[$value['clave_delegacional']] = array('label' => $value['nombre'], 'acceso' => $acceso, 'region' => $value['clave_regional']);
                    }
                    return $r_datos;
                case '+':
                    break;
                case 't':
                    break;
            }
        }
    }

}
