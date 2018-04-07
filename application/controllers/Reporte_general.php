<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que genera reporte dashboard de nivel central y de delegacionales
 * @version : 1.0.0
 * @autor : Miguel Guagnelli
 */
class Reporte_general extends CI_Controller
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
    }
    
    function index(){
        //echo "hola mundo";
        //$main_content = $this->load->view('tc_template/index.php.tpl', null, true);
        $this->template->setTitle("Reporte general");
        
        $this->template->setSubTitle("Mi reporte");
        $this->template->setDescripcion("Bienvenida a delegacional");
        
        $this->template->setMainContent("tc_template/index.tpl.php");
        $this->template->setBlank("tc_template/index.tpl.php");
        
        $this->template->getTemplate(null,"tc_template/index.tpl.php");
        //pendientes, correo de ingrid (archivo de cursos, archivo de unidades)
        //pendiente observaciones de las doctoras christina y palacios
        //intregrar nuevo menu
        //integrar nuevos permisos
        //generar drill down
        
        //pen dientes de sipimss, retro alimentacion y lista de cursos
        //explicar relacion circular para garantizar que hilda y jesus esten en la misma línea
    }
}