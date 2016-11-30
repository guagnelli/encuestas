<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_general_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        // $this->load->database();
    }

    public function get_reporte_bonos_implementacion($parametros) {
        $arra_buscar_por = array(
            'matricula' => 'em.EMP_MATRICULA',
            'clavecategoria' => 'em.CATEGORIA_CVE',
            'nombre' => array('em.EMP_NOMBRE', 'em.EMP_APE_PATERNO', 'em.EMP_APE_MATERNO')
        );

        $select = array(
            'reec.encuesta_cve', 'reec.course_cve', 'vdu.namec', 'enc.cve_corta_encuesta', 
            'group_id',
            'mrlr."name"', 'mrlo."name"', 'reec.evaluado_user_cve', 'vdu.clave', 
            'vdu.tex_tutorizado', 'vdu.tutorizado',
            'vdu.del_cve', 'vdu.del_name',
            'sum(reec.total_puntua_si) puntua, SUM(reec.total_no_puntua_napv) no_puntua',
            'sum(reec.base) base',
            '(round( (sum(reec.total_puntua_si)::numeric * 100) / sum(reec.base) , 3 )) as porcentaje'
        );
        
        $where = array(
            'claveadscripcion' => array('campo' => 'vdu.cve_departamento', 'valor' =>$parametros['text_buscar_instrumento']),
        );

        $this->db->start_cache();/**         * *************Inicio cache  *************** */
//        $this->db->from('cdepartamento as dp');
        $this->db->join('encuestas.sse_encuestas enc', 'enc.encuesta_cve = reec.encuesta_cve');
        $this->db->join('encuestas.sse_reglas_evaluacion regev', 'regev.reglas_evaluacion_cve=enc.reglas_evaluacion_cve');
        $this->db->join('mdl_role mrlo', 'mrlo.id = regev.rol_evaluado_cve');
        $this->db->join('mdl_role mrlr', 'mrlr.id = regev.rol_evaluador_cve');
        $this->db->join('join encuestas.view_datos_usuario vdu', 'vdu.idc = reec.course_cve and vdu.iduser = reec.evaluado_user_cve');
        
        //Group by elementales de instrumento
        $this->db->group_by('reec.course_cve');
        $this->db->group_by('vdu.clave');
        $this->db->group_by('vdu.namec');
        //Group by de encuestas
        $this->db->group_by('reec.encuesta_cve');
        $this->db->group_by('enc.cve_corta_encuesta');
        //Group by de grupo
        $this->db->group_by('group_id');
        //Tipo de curso tutorizado 
        $this->db->group_by('vdu.tex_tutorizado');//vdu.tutorizado id
        
        
        //where que son obligatorios
        $this->db->where('em.EDO_LABORAL_CVE', 1);
//        $this->db->where('hv.IS_ACTUAL', 1);

        switch ($params['rol_seleccionado']) {
            case Enum_rols::Profesionalizacion:
                if ($params['DELEGACION_CVE'] > 0) {
                    $this->db->where('em.DELEGACION_CVE', $params['DELEGACION_CVE']);
                }
                if ($params['VAL_CON_CVE'] > 0) {
                    $this->db->where('vg.VAL_CONV_CVE', $params['VAL_CON_CVE']);
                }
//                pr($params['departamento_cve']);
                if (!empty($params['departamento_cve'])) {
                    $this->db->where('em.ADSCRIPCION_CVE', $params['departamento_cve']);
                }
                break;
            case Enum_rols::Validador_N2:
                if ($params['departamento_cve'] > 0) {
                    $this->db->where('em.ADSCRIPCION_CVE', $params['departamento_cve']);
                }
                if (isset($params['DELEGACION_CVE'])) {
                    $this->db->where('em.DELEGACION_CVE', $params['DELEGACION_CVE']);
                } else {//Condision de seguridad, si el validador no existe en la entidad validación
                    $this->db->where('em.DELEGACION_CVE', 0);
                }
                if (isset($params['VAL_CON_CVE'])) {
                    $this->db->where('vg.VAL_CONV_CVE', $params['VAL_CON_CVE']);
                } else {//Condision de seguridad, si el validador no existe en la entidad validación
                    $this->db->where('vg.VAL_CONV_CVE', 0);
                }
                break;
            case Enum_rols::Validador_N1:
                if (isset($params['DELEGACION_CVE'])) {
                    $this->db->where('em.DELEGACION_CVE', $params['DELEGACION_CVE']);
                } else {//Condision de seguridad, si el validador no existe en la entidad validación
                    $this->db->where('em.DELEGACION_CVE', 0);
                }
                $this->db->where('em.ADSCRIPCION_CVE', $params['DEPARTAMENTO_CVE']);
                if (isset($params['VAL_CON_CVE'])) {
                    $this->db->where('vg.VAL_CONV_CVE', $params['VAL_CON_CVE']);
                } else {//Condision de seguridad, si el validador no existe en la entidad validación
                    $this->db->where('vg.VAL_CONV_CVE', 0);
                }
                break;
        }


        if (!empty($params['cvalidacion_estado'])) {//where estado de la validación, no es obligatorio
            $this->db->where('hv.VAL_ESTADO_CVE', $params['cvalidacion_estado']);
        }

        if (is_array($busqueda_text)) {//si es un array lo recorre, ejemplo es la concatenación de nombre, ap y am
            foreach ($busqueda_text as $value) {
                $this->db->or_like($value, $params['buscador_docente']);
            }
        } else {//pone un like para buscar por matricula, o categoria
            $this->db->like($busqueda_text, $params['buscador_docente']);
        }
//        group by em.EMP_MATRICULA
        $this->db->group_by('em.EMP_MATRICULA');
        $this->db->stop_cache(); //************************************Fin cache
        //Cuenta la cantidad de registros
        $num_rows = $this->db->query($this->db->select('count(*) as total')->get_compiled_select('encuestas.sse_result_evaluacion_encuesta_curso reec'))->result();
        $this->db->reset_query(); //Reset de query 
        $this->db->select($select); //Crea query de consulta
        if (isset($params['per_page']) && isset($params['current_row'])) { //Establecer límite definido para paginación 
            $this->db->limit($params['per_page'], $params['current_row']);
        }

        $order_type = (isset($params['order_type'])) ? $params['order_type'] : 'asc';

        if (isset($params['order'])) { //Establecer límite definido para paginación 
            $orden = $params['order'];
//            pr($orden);
            if ($orden === 'fullname') {
                $orden = 'em.EMP_NOMBRE, em.EMP_APE_PATERNO, em.EMP_APE_MATERNO';
            }
            $this->db->order_by($orden, $order_type);
        }

        $ejecuta = $this->db->get('encuestas.sse_result_evaluacion_encuesta_curso reec'); //Prepara la consulta ( aún no la ejecuta)
        $query = $ejecuta->result_array();
//        pr($this->db->last_query());
//        $query->free_result();
        $this->db->flush_cache(); //Limpia la cache
        $result['result'] = $query;
        $result['total'] = $num_rows[0]->total;
        return $result;
    }

}
