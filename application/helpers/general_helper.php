<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// HELPER General
/**
 * Método que preformatea una cadena
 * @autor 		: Jesús Díaz P.
 * @param 		: mixed $mix Cadena, objeto, arreglo a mostrar
 * bool $return Boleano que determina si se imprema o se retorna el valor
 * @return  	: Cadena preformateada
 */
if (!function_exists('pr')) {

    function pr($mix, $return = false) {
        if ($return) {
            return print_r($mix, TRUE);
        } else {
            echo "<pre>";
            print_r($mix);
            echo "</pre>";
        }
    }

}

/**
 * Método que genera un arreglo asociativo de la forma llave => valor, derivado de un arreglo multidimensional
 * @autor 		: Jesús Díaz P.
 * @modified 	:
 * @param 		: mixed[] $array_data
 * @param 		: string $field_key
 * @param 		: string $field_value
 * @return 		: mixed[]. TRUE en caso de que exista, no sea vacía o nula de lo contrario devolverá FALSE
 * Ejemplo: $array_multi = array(
 * 		array('llave1'=>'valor1.0', 'llave2'=>'valor2.0', 'llave3'=>'valor3.0'),
 * 		array('llave1'=>'valor1.1', 'llave2'=>'valor2.1', 'llave3'=>'valor3.1'),
 * 		array('llave1'=>'valor1.2', 'llave2'=>'valor2.2', 'llave3'=>'valor3.2'),
 * )
 * dropdown_options($array_multi, 'llave2', 'llave3');
 * Resultado: array(
 * 		array('valor2.0'=>'valor3.0'),
 * 		array('valor2.1'=>'valor3.1'),
 * 		array('valor2.2'=>'valor3.2'),
 * )
 */
if (!function_exists('dropdown_options')) {

    function dropdown_options($array_data, $field_key, $field_value) {
        $options = array();
        foreach ($array_data as $key => $value) {
            $options[$value[$field_key]] = $value[$field_value];
        }
        return $options;
    }

}

/**
 * Método que define una plantilla para los mensajes que mostrará un formulario
 * @autor 		: Jesús Díaz P.
 * @modified 	:
 * @param 		: string $elemento Nombre del elemento form
 * @param 		: string $tipo Posibles valores('success','info','warning','danger')
 * @return 		: string Mensaje con formato predefinido
 */
if (!function_exists('form_error_format')) {

    function form_error_format($elemento, $tipo = null) {
        if (is_null($tipo)) {
            $tipo = 'danger';
        }
        return form_error($elemento, '<div class="alert alert-' . $tipo . '" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
    }

}

/**
 * Método que define una plantilla para los mensajes que se mostrarán con bootstrap
 * @autor 		: Jesús Díaz P.
 * @modified 	:
 * @param 		: string $message Texto a mostrar
 * @param 		: string $tipo Posibles valores('success','info','warning','danger')
 * @return 		: string Mensaje de alerta con formato predefinido
 */
if (!function_exists('html_message')) {

    function html_message($message, $tipo = null) {
        if (is_null($tipo)) {
            $tipo = 'danger';
        }
        return '<div class="alert alert-' . $tipo . '" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $message . '</div>';
    }

}

/**
 * Método que define una plantilla para los mensajes que se mostrarán con bootstrap
 * @autor     : Jesús Díaz P.
 * @modified  :
 * @param     : string $message Texto a mostrar
 * @param     : string $tipo Posibles valores('success','info','warning','danger')
 * @return    : string Mensaje de alerta con formato predefinido
 */
if (!function_exists('imprimir_resultado')) {

    function imprimir_resultado($resultado) {
        $tipo_mensaje = ($resultado['result'] === true) ? 'success' : 'danger';

        return '<div id="js_msg" class="row">
                <div class="col-lg-12 alert alert-' . $tipo_mensaje . '">
                    ' . $resultado['msg'] . '
                </div>
            </div>';
    }

}

/**
 * Método que obtiene un listado de archivos de la ruta otorgada
 * @autor 		: Jesús Díaz P.
 * @modified 	:
 * @param 		: string $path Ruta de donde se obtendrá el listado de archivos
 * @return 		: mixed[] $result Listado de archivos
 */
if (!function_exists('get_files')) {

    function get_files($path) {
        return scandir($path);
    }

}

/**
 * Método que hace una intersección entre dos array
 * @autor 		: Luis E. A.S.
 * @modified            :
 * @param 		: array $array_1 a comparar con $array_2
 * @return 		: $array_result  de intersección entré dor arrays
 */
if (!function_exists('filtra_array_por_key')) {

    function filtra_array_por_key($array_bidimencional, $array_unidimencional) {
        $is_array = exist_and_not_null($array_bidimencional);
        $is_array = $is_array && exist_and_not_null($array_unidimencional);
        $is_array = $is_array && is_array($array_bidimencional);
        $is_array = $is_array && is_array($array_unidimencional);
        $array_result = array();
        if ($is_array) {
            foreach ($array_unidimencional as $value) {
                foreach ($array_bidimencional as $key => $value_2) {
                    if ($value == $key) {
                        $array_result[$key] = $value_2;
                    }
                }
            }
            return $array_result;
        } else {
            return null;
        }
    }

}

/**
 * Método que crea un elemento number
 * @autor 		: Jesús Díaz P.
 * @modified 	:
 * @param 		: string $label_text Contenido de la etiqueta number
 * @param 		: mixed[] $attributes Atributos de la etiqueta number
 * @return 		: string Elemento p
 */
if (!function_exists('form_number')) {

    function form_number($data = '', $value = '', $extra = '') {
        $defaults = array(
            'type' => 'number',
            'name' => is_array($data) ? '' : $data,
            'value' => $value
        );

        return '<input ' . _parse_form_attributes($data, $defaults) . $extra . " />\n";
    }

}

if (!function_exists('crear_formato_array')) {

    /**
     * @author : LEAS <cenitluis.pumas@gmail.com>
     * @Fecha creación : 25-05-2016
     * @Fecha modificación :
     * @param type $array_value : Array ha analizar
     * @param type $key_ref : Llave de referencia del arreglo que será el index
     * del arreglo formateado
     * @param type $not_index_auto_incrementables : En false le agregará
     * un index autoincrementable, y en true se lo quita y sólo le agrega el $keyref
     * @return array[]
     * <p>
     * Array
      (
      [3] => Array
      (
      [0] => Array
      (
      [nombre_rol] => Ayudante
      [cve_modulo] => 3
      [nombre_modulo] => Comisiones académicas
      )

      )

      [4] => Array
      (
      [0] => Array
      (
      [nombre_rol] => Instructor de prácti
      [cve_modulo] => 2
      [nombre_modulo] => Formación del docente
      )

      [1] => Array
      (
      [nombre_rol] => Instructor de prácti
      [cve_modulo] => 3
      [nombre_modulo] => Comisiones académicas
      )

      )
      )

     * </p>
     */
    function crear_formato_array($array_value, $key_ref, $not_index_auto_incrementables) {
        $array_modulo = array();
        $index = -1;
        if ($not_index_auto_incrementables) {
            /* Le asigna la llave de referencia $key_ref al formato y no le agrega
             * index autoincrementables
             */
            for ($i = 0; $i < count($array_value); $i++) {
                $index = $array_value[$i][$key_ref];
                if (array_key_exists($index, $array_modulo)) {
                    $index_num_siguiente = count($array_modulo[$index]);
                    $array_modulo[$index] = array();
                } else {
                    $array_modulo[$index] = array();
                }
                foreach ($array_value[$i] as $key => $value) {
                    if ($key != $key_ref) {
                        $array_modulo[$index][$key] = $value;
                    }
                }
            }
        } else {
            /* Le  asigna un index auto incrementable que va desde "0" ,...., "n"
              al formato del array */
            for ($i = 0; $i < count($array_value); $i++) {
                $index = $array_value[$i][$key_ref];
                if (array_key_exists($index, $array_modulo)) {
                    $index_num_siguiente = count($array_modulo[$index]);
                    $array_modulo[$index][$index_num_siguiente] = array();
                } else {
                    $index_num_siguiente = 0;
                    $array_modulo[$index][$index_num_siguiente] = array();
                }
                foreach ($array_value[$i] as $key => $value) {
                    if ($key != $key_ref) {
                        $array_modulo[$index][$index_num_siguiente][$key] = $value;
                    }
                }
            }
        }
        return $array_modulo;
    }

}

if (!function_exists('crear_lista_asociativa_valores')) {

    /**
     *
     * @param type : $array_value array de busqueda
     * @param type : $key_ref llave busqueda
     * @param type : $val_ref valor asociación
     * @return type : array
     */
    function crear_lista_asociativa_valores($array_value, $key_ref, $val_ref) {
        $array_lista_roles = array();
        $key = -1;
        $value = '';
        for ($i = 0; $i < count($array_value); $i++) {
            $key = $array_value[$i][$key_ref];
            $value = $array_value[$i][$val_ref];
            $array_lista_roles[$key] = $value;
        }

        return $array_lista_roles;
    }

}

if (!function_exists('get_busca_array_nivel_profundidad_dos')) {

    /**
     *
     * @param type $array_busqueda
     * @param type $controlador
     * @param type $metodo_controlador
     * @param type $llave
     * @return int|array
     */
    function get_busca_array_nivel_profundidad_dos($array_busqueda = null, $controlador = null, $metodo_controlador = 'index', $llave = null) {
        //Si el arreglo es null y vacio, retorna false
//        pr($array_busqueda);
        $array_result = array();
        if (is_null($array_busqueda) AND empty($array_busqueda)) {
            return $array_result;
        }
        //Si el controlador a buscar es vacio, retorna FALSE
        if (is_null($controlador) AND empty($controlador)) {
            return $array_result;
        }
        foreach ($array_busqueda as $value_array_n1) {
            foreach ($value_array_n1 as $key_n2 => $value_array_n2) {
                if (is_array($value_array_n2) AND array_key_exists($llave, $value_array_n2)) {//Verifica que sea un array y que se encuentrá la llave
                    $valor_analizar = $value_array_n2[$llave];
                    if ($valor_analizar === $controlador) {//Si la llave es diferente de null y no es vacia
                        $array_result = $value_array_n2; //Retorna el array encontrado
                        break 2;
                    }
                }
            }
        }

        //Si no existe el controlador, retorna false
        return $array_result;
    }

}

if (!function_exists('get_busca_hijos')) {

    /**
     * @author LEAS
     * @param  type $array_busqueda
     * @param  type $controlador
     * @return array
     */
    function get_busca_hijos($array_busqueda = null, $controlador = null) {
        //Si el arreglo es null y vacio, retorna false
        $array_result = array();
        pr($controlador);
        if (is_null($array_busqueda) AND empty($array_busqueda)) {
            return $array_result;
        }
        pr($array_busqueda);
        foreach ($array_busqueda as $keys => $valores) {
            $cad1 = strtolower($controlador);
//            $cad2 = strtolower($valores['nombre_padre']);
            $cad2 = strtolower($valores['ruta_padre']);
            if (!empty($valores['padre']) AND ( $cad1 === $cad2)) {
                $array_result[$keys] = $valores;
            }
        }

        //Si no existe el controlador, retorna false
        return $array_result;
    }

}

if (!function_exists('get_ip_cliente')) {

    /**
     * @author LEAS
     * @return ip del cliente: obtiene la ip del cliente, por tres tipos de casos,
     * hasta obtener una ip:
     * 1 por IP compartido = HTTP_CLIENT_IP;
     * 2 por IP Proxy = HTTP_X_FORWARDED_FOR;
     * 3 por IP Acceso = REMOTE_ADDR;
     *
     */
    function get_ip_cliente() {
        $ip_cliente = '';
        $conexiones_ip = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        foreach ($conexiones_ip as $value) {
            if (isset($_SERVER[$value]) AND ! empty($_SERVER[$value])) {
                $ip_cliente = $_SERVER[$value];
                break;
            }
        }
        return $ip_cliente;
    }

}

///////Función general para calcular la eficiencia terminal modificada
if (!function_exists('calcular_eficiencia_terminal')) {
    function calcular_eficiencia_terminal($inscritos, $aprobados, $no_acceso){
        $eficiencia_terminal = 0;
        if($inscritos-$no_acceso > 0){
            $eficiencia_terminal = ($aprobados/($inscritos-$no_acceso))*100;
        }
        return intval($eficiencia_terminal).' %';
    }
}

if (!function_exists('imprimir_elemento_html')) {
    function imprimir_elemento_html($elemento){
        echo '<span class="pull-right fa fa-print" aria-hidden="true" onclick="javascript:imprimir_contenido(\''.$elemento.'\');" style="cursor:pointer;"></span>';
    }
}
/* End of file general_helper.php */

if (!function_exists('startsWith'))
    {
    function startsWith($haystack, $needle)
    {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }
}

if (!function_exists('is_nivel_central'))
{
    function is_nivel_central($grupos = array()){
        $salida = false;
        foreach ($grupos as $grupo){
            $id = $grupo['id_grupo'];
            if($id == 3 || $id == 2 || $id == 1){
                $salida = true;
            }
        }
        return $salida;
    }
}

if (!function_exists('is_nivel_operacional'))
{
    function is_nivel_operacional($grupos = array()){
        $salida = false;
        foreach ($grupos as $grupo){
            $id = $grupo['id_grupo'];
            if($id == 6){
                $salida = true;
            }
        }
        return $salida;
    }
}

if (!function_exists('is_nivel_tactico'))
{
    function is_nivel_tactico($grupos = array()){
        $salida = false;
        foreach ($grupos as $grupo){
            $id = $grupo['id_grupo'];
            if($id == 5 || $id == 8){
                $salida = true;
            }
        }
        return $salida;
    }
}

if (!function_exists('is_nivel_estrategico'))
{
    function is_nivel_estrategico($grupos = array()){
        $salida = false;
        foreach ($grupos as $grupo){
            $id = $grupo['id_grupo'];
            if($id == 4 || $id == 7){
                $salida = true;
            }
        }
        return $salida;
    }
}

if(!function_exists('dropdown')){
  function dropdown($config,$section=array(),$options = null){
    //title
    //id
    //class.
    //attribs
    //seccion
    //subseccion
    if(!isset($config["title"]) || !isset($config["id"])){
      return false;
    }
    $drop = "<div class='dropdown'>
              <button class='btn btn-primary btn-round btn-block dropdown-toggle %s'
                      type='button'
                      id='%s'
                      data-toggle='dropdown'
                      aria-haspopup='true' aria-expanded='true'
                      %s>
                %s
                <span class='caret'></span>
              </button>
              <ul class='dropdown-menu' aria-labelledby='%s'>
                %s
              </ul>
            </div>";
      $css = isset($config["class"])? $config["class"] : null;
      $attribs = isset($config["attribs"])? $config["attribs"] : null;
      $opt = "";
      foreach($section as $id_sec=>$seccion){
        if(is_null($options)){
          $opt .= "<li><a href='#' class='drop-option {$config["id"]}' data-id='$id_sec'>{$seccion}</a></li>";
        }else{
          $opt .= "<li class='dropdown-header' class='{$config["id"]}' data-id='$id_sec'>".$seccion."</li>";
          foreach($options as $option){
            if($option["id_subcategoria"] == $id_sec){
              $opt .= "<li><a href='#' class='drop-option {$config["id"]}' data-id='{$option[$config["id_sub"]]}'>".$option[$config["subseccion"]]."</a></li>";
            }
          }
          $opt .= "<li class='divider'></li>";
        }
      }
      $drop = sprintf($drop,$css,$config["id"],$attribs,$config["title"],$config["id"],$opt );

      return $drop;
  }
}


if(!function_exists('format_label_icon')){
    function format_label_icon($label = '', $icon = 'stars'){
        return '<span style="text-decoration:underline;">'.$label.'</span>';
    }
}

if(!function_exists('render_subtitle')){
    function render_subtitle($subtitle = '', $help = 'help'){
        return ' <i class="material-icons cores-helper" data-help="'.$help.'">help</i>'.$subtitle;
    }
}