<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_detallado_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        //$this->load->database();
    }
    
    public function reporte_usuarios($params = null) {
        $resultado = array();

        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        $this->db->start_cache();
        $this->db->select('mdl_user.id');

        if (isset($params['rol']) && !empty($params['rol'])) {
            //$guarda_busqueda = true;
            $this->db->where('mdl_role.id', $params['rol']);
        }

        if (isset($params['anio']) && !empty($params['anio'])) {
            //$guarda_busqueda = true;
            $this->db->where("TO_CHAR(TO_TIMESTAMP(mdl_course.startdate),'YYYY')='" . $params['anio'] . "'");
        }/**/

        //pr($params);
        $this->db->join('public.mdl_role_assignments', 'mdl_role_assignments.userid=mdl_user.id');
        $this->db->join('public.mdl_role', 'mdl_role.id=mdl_role_assignments.roleid AND mdl_role.id IN (14,18,32,33,30)');
        $this->db->join('public.mdl_context', 'mdl_context.id=mdl_role_assignments.contextid');
        $this->db->join('public.mdl_course', 'mdl_context.instanceid=mdl_course.id');
        $this->db->join('public.mdl_course_config', 'mdl_course_config.course=mdl_course.id');
        //$this->db->group_by("mdl_user.id");

        $this->db->stop_cache();
        /////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
        ///////////////////////////// Obtener número de registros ///////////////////////////////
        $nr = $this->db->get_compiled_select('mdl_user'); //Obtener el total de registros
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (" . $nr . ") AS temp")->result();
        //pr($this->db1->last_query());
        /////////////////////////////// FIN número de registros /////////////////////////////////
        $busqueda = array(
            "mdl_user.id",
            "mdl_user.username AS emp_matricula",
            "mdl_course.id AS cur_id",
            "mdl_user.nom AS emp_nombre",
            "mdl_user.pat AS emp_paterno",
            "mdl_user.mat AS emp_materno",
            "mdl_user.curp AS emp_curp",
            "mdl_course.shortname AS cur_clave",
            "mdl_course.fullname AS cur_nom_completo",
            "TO_TIMESTAMP(mdl_course.startdate) AS fecha_inicio",
            "mdl_course_config.horascur",
            "mdl_course_config.lastdate AS fecha_fin",
            "mdl_role.id AS rol_id",
            "mdl_course_config.tutorizado",
            //"mdl_course_config.curso_alcance", 
            "mdl_role.name AS rol_nom",
            'CASE mdl_course_config.tipocur 
                            WHEN 0 THEN 
                                CASE SUBSTRING(mdl_course.shortname from \'%#\"GPC#\"%\' FOR \'#\')  
                                    WHEN \'GPC\' THEN \'CURSO BASADO EN GPC\' 
                                    ELSE \'CURSO\' 
                                END
                            WHEN 1 THEN \'DIPLOMADO\' 
                            ELSE \'ERROR\'  
                        END tipo_curso',
            "mdl_course_config.tipocur"
        );


        $this->db->select($busqueda);
        if (isset($params['order']) && !empty($params['order'])) {
            $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
            $this->db->order_by($params['order'], $tipo_orden);
        }
        if (isset($params['per_page']) && isset($params['current_row'])) { //Establecer límite definido para paginación 
            $this->db->limit($params['per_page'], $params['current_row']);
        }
        $query = $this->db->get('mdl_user'); //Obtener conjunto de registros
        //pr($this->db->last_query());                                  
        $resultado['total'] = $num_rows[0]->total;
        $resultado['columns'] = $query->list_fields();
        $resultado['data'] = $query->result_array();
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria         

        return $resultado;
    }



    public function listado_evaluados($params = null) {

        $resultado = array();
        $this->db->where('tutorias.mdl_userexp.cursoid', $params['cur_id']);


        if (isset($params['gpo_evaluador']) && !empty($params['gpo_evaluador'])) {


            $this->db->where('tutorias.mdl_userexp.cursoid', $params['cur_id']);
            //$this->db->where('tutorias.mdl_userexp.role',$params['role_evaluado']);
            $this->db->where('tutorias.mdl_userexp.grupoid', $params['gpo_evaluador']);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);

            $this->db->select('public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role, public.mdl_groups.name as ngpo, 
                (select public.mdl_role.name from public.mdl_role where id=' . $params['role_evaluador'] . ') as evaluador,' .
                    $params['encuesta_cve'] . ' as regla, public.mdl_groups.id as gpoid, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve=' . $params['encuesta_cve'] . ' and course_cve=' . $params['cur_id'] . ' and group_id=' . $params['gpo_evaluador'] . ' 
                    and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve=' . $params['evaluador_user_cve'] . ')  as realizado');

            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role', 'public.mdl_role.id= tutorias.mdl_userexp.role');
            $this->db->join('public.mdl_groups', 'public.mdl_groups.id=tutorias.mdl_userexp.grupoid');
        } else {


            $params['gpo_evaluador'] = 0;
            $consulta = 'public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role,' . $params['gpo_evaluador'] . ' as ngpo,
              (select public.mdl_role.name from public.mdl_role where id=' . $params['role_evaluador'] . ') as evaluador,' .
                    $params['encuesta_cve'] . ' as regla, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                    (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve=' . $params['encuesta_cve'] . ' and course_cve=' . $params['cur_id'] . ' 
                        and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve=' . $params['evaluador_user_cve'] . ')  as realizado';




            $this->db->distinct($consulta);
            $this->db->select($consulta);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);


            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role', 'public.mdl_role.id= tutorias.mdl_userexp.role');
        }




        //$this->db->select($busqueda);
        if (isset($params['filtros']['order']) && !empty($params['filtros']['order'])) {
            $tipo_orden = (isset($params['filtros']['order_type']) && !empty($params['filtros']['order_type'])) ? $params['filtros']['order_type'] : "ASC";
            $this->db->order_by($params['filtros']['order'], $tipo_orden);
        }

//        pr($params['filtros']);
        if (isset($params['filtros']['per_page']) && isset($params['filtros']['current_row'])) { //Establecer límite definido para paginación 
            $this->db->limit($params['filtros']['per_page'], $params['filtros']['current_row']);
        }

        $query = $this->db->get('tutorias.mdl_userexp');

        //$resultado['total']=$query->num_rows();
        //$resultado['columns']=$query->list_fields();


        $resultado = $query->result_array();
        return $resultado;
    }
}
