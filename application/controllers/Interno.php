<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

/**
 * Description of Interno
 *
 * @author chrigarc
 */
class Interno extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('empleados_siap');
        $this->delegaciones = array(
           'SIN DELEGACION' =>'00',
           'AGUASCALIENTES' =>'01',
           'BAJA CALIFORNIA' =>'02',
           'BAJA CALIFORNIA SUR' =>'03',
           'CAMPECHE' =>'04',
           'COAHUILA' =>'05',
           'COLIMA' =>'06',
           'CHIAPAS' =>'07',
           'CHIHUAHUA' =>'08',
           'OFICINAS CENTRALES' =>'09',
           'DURANGO' =>'10',
           'GUANAJUATO' =>'11',
           'GUERRERO' =>'12',
           'HIDALGO' =>'13',
           'JALISCO' =>'14',
           'EDO MEX OTE' =>'15',
           'EDO MEX PTE' =>'16',
           'MICHOACAN' =>'17',
           'MORELOS' =>'18',
           'NAYARIT' =>'19',
           'NUEVO LEON' =>'20',
           'OAXACA' =>'21',
           'PUEBLA' =>'22',
           'QUERETARO' =>'23',
           'QUINTANA ROO' =>'24',
           'SAN LUIS POTOSI' =>'25',
           'SINALOA' =>'26',
           'SONORA' =>'27',
           'TABASCO' =>'28',
           'TAMAULIPAS' =>'29',
           'TLAXCALA' =>'30',
           'VERACRUZ NORTE' =>'31',
           'VERACRUZ SUR' =>'32',
           'YUCATAN' =>'33',
           'ZACATECAS' =>'34',
           'D F 1 NORTE' =>'35',
           'D F 2 NORTE' =>'36',
           'D F 3 SUR' =>'37',
           'MANDO' =>'39',
           'D F 4 SUR' =>'38'
        );
    }

    public function index()
    {
        $usuarios = array(
           '9181512' =>'AGUASCALIENTES',
           '7019521' =>'BAJA CALIFORNIA',
           '6290124' =>'COAHUILA',
           '7556934' =>'COAHUILA',
           '7875665' =>'COAHUILA',
           '6853633' =>'CHIAPAS',
           '5226554' =>'CHIHUAHUA',
           '8025061' =>'DURANGO',
           '6877877' =>'GUANAJUATO',
           '8398356' =>'GUERRERO',
           '7072155' =>'JALISCO',
           '8353786' =>'JALISCO',
           '8787034' =>'JALISCO',
           '8864411' =>'JALISCO',
           '9297413' =>'JALISCO',
           '9181628' =>'EDO MEX OTE',
           '8438226' =>'EDO MEX PTE',
           '9585982' =>'EDO MEX PTE',
           '10036881' =>'MICHOACAN',
           '7122055' =>'NUEVO LEON',
           '9311157' =>'NUEVO LEON',
           '99211719' =>'OAXACA',
           '7869002' =>'PUEBLA',
           '8243743' =>'PUEBLA',
           '8768331' =>'QUINTANA ROO',
           '5113458' =>'TAMAULIPAS',
           '99322588' =>'VERACRUZ SUR',
           '10196935' =>'ZACATECAS',
           '3476766' =>'D F 2 NORTE',
           '8961026' =>'D F 2 NORTE',
           '8758506' =>'D F 3 SUR',
           '9063048' =>'D F 4 SUR',
        );
        $adscripciones = array();
        foreach ($usuarios as $key => $value)
        {
            $data = array(
                "reg_delegacion" => $this->delegaciones[$value],
                "asp_matricula" => $key
            );
            $empleado = $this->empleados_siap->buscar_usuario_siap($data)['empleado'];
            $empleado = json_encode($empleado);
            $empleado = json_decode($empleado, true);
//            pr($empleado);
            $ads = $empleado['adscripcion'][0];
//            pr($ads);
//            pr($adscripciones[$ads]);            
            if(!isset($adscripciones[$ads])){
                $adscripciones[$ads] = array('nombre' => $empleado['descripcion'][0]);                
            }
            if(!isset($adscripciones[$ads]['empleados'][$key])){
                $adscripciones[$ads]['empleados'][$key] = $empleado['nombre'][0].' '.$empleado['paterno'][0].' '.$empleado['materno'][0].' ';
            }
//            break;
        }
        pr($adscripciones);
        pr(count($adscripciones));
    }

}
