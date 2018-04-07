<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
define('TABLE_BITACORA', 'sistema.bitacora_cores');
define('BD_BITACORA_CONFIG', 'default');

/**
 * Description of Bitacora
 *
 * @author chrigarc
 */
class Bitacora
{

    const PAGE_SIZE = 5;

    //put your code here
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->db = $this->CI->load->database(BD_BITACORA_CONFIG, true);
        $this->url_a_registrar = array(
            'informacion_general/inicio',
            'welcome/documento/2',
            'reportes_estaticos/descarga/2/1',
            'informacion_general',
            'informacion_general/calcular_totales_generales',
            'informacion_general/por_perfil',
            'informacion_general/por_tipo_curso',
            'informacion_general/por_unidad',
            'comparativa/region',
            'comparativa/delegacion_v2',
            'comparativa/umae',
            'comparativa/unidades',
        );
    }

    public function registra_actividad()
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin();

        $ip = $this->CI->input->ip_address();
        $valor = [];
        if ($this->CI->input->post())
        {
            $valor = $this->CI->input->post();
        }
        $valor = json_encode($valor);
        $uri = $this->CI->uri->uri_string();
        $usuario = null;
        if ($this->CI->session->userdata('usuario') != null)
        {
            $usuario = $this->CI->session->userdata('usuario')['id_usuario'];
        }
        $insert = array(
            'id_usuario' => $usuario,
            'valor' => $valor,
            'ip' => $ip,
            'url' => $uri
        );

        if (in_array($uri, $this->url_a_registrar))
        {
            $this->db->insert(TABLE_BITACORA, $insert);
        }
//        pr($this->db->last_query());

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        } else
        {
            $this->db->trans_commit();
        }
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    function get_registros($params = [])
    {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_bitacora', 'A.id_usuario', 'to_char("A".fecha, $$dd/MM/YYYY HH24:MI$$) fecha', 'A.ip', 'A.url',
            'B.nombre', 'B.matricula', 'DEL.nombre delegacion', 'UNI.nombre unidad', 'CAT.nombre categoria'
        );
        $this->db->start_cache();
        $this->db->from(TABLE_BITACORA . ' A');
        if (isset($params['where']))
        {
            if (is_array($params['where']))
            {
                foreach ($params['where'] as $key => $value)
                {
                    $this->db->where($key, $value);
                }
            } else
            {
                $this->db->where($params['where']);
            }
        }
        if (isset($params['like']))
        {
            if (is_array($params['like']))
            {
                foreach ($params['like'] as $key => $value)
                {
                    $this->db->like($key, $value);
                }
            } else
            {
                $this->db->like($params['like']);
            }
        }
//        $this->db->where('date(fecha) = current_date', null, false);                
        $this->db->join('sistema.usuarios B', 'B.id_usuario = A.id_usuario', 'inner');
        $this->db->join('catalogos.unidades_instituto UNI', 'UNI.id_unidad_instituto = B.id_unidad_instituto', 'left');
        $this->db->join('catalogos.delegaciones DEL', 'DEL.id_delegacion = UNI.id_delegacion', 'left');
        $this->db->join('catalogos.categorias CAT', 'CAT.clave_categoria = B.clave_categoria', 'left');
        $this->db->stop_cache();
        $this->db->select('count(*) cantidad');
        $salida['itemsCount'] = $this->db->get()->result_array()[0]['cantidad'];
        $this->db->reset_query();
        $this->db->select($select);
        if (isset($params['limit']))
        {
            $offset = isset($params['offset']) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }
        $salida['data'] = $this->db->get()->result_array();
        //  $salida['query'] = $this->db->last_query();
//        pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

}
