<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_detallado_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        //$this->load->database();
    }
    
    public function reporte_detalle_encuesta($params = null) {
        $resultado = array();

        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        /*$this->db->start_cache();
        $this->db->select('mdl_user.id');

        if (isset($params['rol']) && !empty($params['rol'])) {
            //$guarda_busqueda = true;
            $this->db->where('mdl_role.id', $params['rol']);
        }

        if (isset($params['anio']) && !empty($params['anio'])) {
            //$guarda_busqueda = true;
            $this->db->where("TO_CHAR(TO_TIMESTAMP(mdl_course.startdate),'YYYY')='" . $params['anio'] . "'");
        }

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
        */
        $query = $this->db->query('select enc.encuesta_cve, enc.descripcion_encuestas, enc.is_bono, enc.tipo_encuesta, enc.eva_tipo, tex_tutorizado,
                eva.course_cve, curso.namec, curso.clave as curso_clave, curso.tipo_curso, curso.tipo_curso_id, curso.horascur, curso.anio, curso.fecha_inicio, curso.fecha_fin, eva.group_id, grupo.name as grupo_nombre, 
                eva.evaluado_user_cve, eva.evaluado_rol_id, rol_evaluado.name as evaluado_rol_nombre, 
                    evaluado.cve_departamental as depto_user_id, depto_evaluado.nom_depto_adscripcion as depto_user_nombre, depto_evaluado.cve_delegacion as depto_user_id_del, depto_evaluado.nom_delegacion as depto_user_nom_del, 
                    (select * from departments.get_rama_completa(evaluado.cve_departamental, 7)) as rama_uder_evaluado, 
                    tut_evaluado.cve_departamento as depto_tut_id, depto_tut_evaluado.nom_depto_adscripcion as depto_tut_nombre, depto_tut_evaluado.cve_delegacion as depto_tut_id_del, depto_tut_evaluado.nom_delegacion as depto_tut_nom_del, 
                    (select * from departments.get_rama_completa(tut_evaluado.cve_departamento, 7)) as rama_tut_evaluado, 
                evaluado.cat as evaluado_cat_user_id, cat_evaluado.nom_nombre as evaluado_cat_user_nom, tut_evaluado.cve_categoria as evaluado_cat_tut_id, cat_tut_evaluado.nom_nombre as evaluado_cat_tut_nom, 
                eva.evaluador_user_cve, eva.evaluador_rol_id, rol_evaluador.name as evaluador_rol_nombre, 
                    evaluador.cve_departamental as depto_e_user_id, depto_evaluador.nom_depto_adscripcion as depto_e_user_nombre, depto_evaluador.cve_delegacion as depto_e_user_id_del, depto_evaluador.nom_delegacion as depto_e_user_nom_del,
                    (select * from departments.get_rama_completa(evaluador.cve_departamental, 7)) as rama_uder_evaluador,
                    tut_evaluador.cve_departamento as depto_e_tut_id, depto_tut_evaluador.nom_depto_adscripcion as depto_e_tut_nombre, depto_tut_evaluador.cve_delegacion as depto_e_tut_id_del, depto_tut_evaluador.nom_delegacion as depto_e_tut_nom_del, 
                    (select * from departments.get_rama_completa(tut_evaluador.cve_departamento, 7)) as rama_tut_evaluador,
                    prereg_evaluador.cve_departamental as depto_e_pre_id, depto_pre_evaluador.nom_depto_adscripcion as depto_e_pre_nombre, depto_pre_evaluador.cve_delegacion as depto_e_pre_id_del, depto_pre_evaluador.nom_delegacion as depto_e_pre_nom_del, 
                    (select * from departments.get_rama_completa(prereg_evaluador.cve_departamental, 7)) as rama_pre_evaluador,
                evaluador.cat as evaluador_cat_user_id, cat_evaluador.nom_nombre as evaluador_cat_user_nom, tut_evaluador.cve_categoria as evaluador_cat_tut_id, cat_tut_evaluador.nom_nombre as evaluador_cat_tut_nom, prereg_evaluador.cve_cat as evaluador_cat_pre_id, cat_pre_evaluador.nom_nombre as evaluador_cat_pre_nom, 
                evaluado.username as evaluado_matricula, evaluado.firstname as evaluado_nombre, evaluado.lastname as evaluado_apellido, evaluador.username as evaluador_matricula, evaluador.firstname as evaluador_nombre, evaluador.lastname as evaluador_apellido,
                enc.reglas_evaluacion_cve/*, eva.preguntas_cve, eva.reactivos_cve*/
            from encuestas.sse_encuestas enc
            inner join encuestas.sse_evaluacion eva on eva.encuesta_cve=enc.encuesta_cve
            inner join encuestas.view_datos_curso curso on curso.idc=eva.course_cve
            left join encuestas.sse_reglas_evaluacion eva_reg on eva_reg.reglas_evaluacion_cve=enc.reglas_evaluacion_cve
            left join public.mdl_groups grupo ON grupo.id=eva.group_id
            left join public.mdl_user evaluado on evaluado.id=eva.evaluado_user_cve
            left join public.mdl_role rol_evaluado on rol_evaluado.id=eva.evaluado_rol_id
            left join tutorias.mdl_usertutor tut_evaluado on tut_evaluado.nom_usuario=evaluado.username and tut_evaluado.id_curso=eva.course_cve 
                and eva.evaluado_rol_id <> 5
            left join nomina.ssn_categoria cat_evaluado ON cat_evaluado.cve_categoria = evaluado.cat
            left join nomina.ssn_categoria cat_tut_evaluado ON cat_tut_evaluado.cve_categoria = tut_evaluado.cve_categoria
            left join departments.ssv_departamentos depto_evaluado ON depto_evaluado.cve_depto_adscripcion=evaluado.cve_departamental
            left join departments.ssv_departamentos depto_tut_evaluado ON depto_tut_evaluado.cve_depto_adscripcion=tut_evaluado.cve_departamento 
            --left join encuestas.view_datos_usuario tut_evaluador on tut_evaluador.nom_usuario=evaluado.username and tut_evaluador.idc=eva.course_cve  
            left join public.mdl_user evaluador on evaluador.id=eva.evaluador_user_cve
            left join public.mdl_role rol_evaluador on rol_evaluador.id=eva.evaluador_rol_id
            left join gestion.sgp_tab_preregistro_al as prereg_evaluador on prereg_evaluador.nom_usuario=evaluador.username and prereg_evaluador.cve_curso=eva.course_cve 
                and eva.evaluador_rol_id = 5
            left join tutorias.mdl_usertutor as tut_evaluador on tut_evaluador.nom_usuario=evaluador.username and tut_evaluador.id_curso=eva.course_cve 
                and eva.evaluador_rol_id <> 5
            left join nomina.ssn_categoria cat_evaluador ON cat_evaluador.cve_categoria = evaluador.cat
            left join nomina.ssn_categoria cat_pre_evaluador ON cat_pre_evaluador.cve_categoria = prereg_evaluador.cve_cat
            left join nomina.ssn_categoria cat_tut_evaluador ON cat_tut_evaluador.cve_categoria = tut_evaluador.cve_categoria
            left join departments.ssv_departamentos depto_evaluador ON depto_evaluador.cve_depto_adscripcion=evaluador.cve_departamental
            left join departments.ssv_departamentos depto_pre_evaluador ON depto_pre_evaluador.cve_depto_adscripcion=prereg_evaluador.cve_departamental
            left join departments.ssv_departamentos depto_tut_evaluador ON depto_tut_evaluador.cve_depto_adscripcion=tut_evaluador.cve_departamento
            where eva.course_cve=838 and evaluador_rol_id=18 and evaluado_rol_id=32 AND eva.encuesta_cve=526
            group by enc.encuesta_cve, enc.descripcion_encuestas, enc.is_bono, enc.reglas_evaluacion_cve, enc.tipo_encuesta, enc.eva_tipo, tex_tutorizado,
                eva.course_cve, curso.namec, curso.clave, curso.tipo_curso, curso.tipo_curso_id, curso.horascur, curso.anio, curso.fecha_inicio, curso.fecha_fin, eva.group_id, grupo.name, 
                eva.evaluado_user_cve, eva.evaluado_rol_id, rol_evaluado.name, tut_evaluado.cve_departamento, evaluado.cve_departamental, depto_evaluado.nom_depto_adscripcion, depto_evaluado.cve_delegacion, depto_evaluado.nom_delegacion,
                tut_evaluado.cve_categoria, cat_tut_evaluado.nom_nombre, evaluado.cat, cat_evaluado.nom_nombre, depto_tut_evaluado.nom_depto_adscripcion, depto_tut_evaluado.cve_delegacion, depto_tut_evaluado.nom_delegacion,
                eva.evaluador_user_cve, eva.evaluador_rol_id, rol_evaluador.name, evaluador.cve_departamental, depto_evaluador.nom_depto_adscripcion, depto_evaluador.cve_delegacion, depto_evaluador.nom_delegacion, 
                evaluador.cat, cat_evaluador.nom_nombre, tut_evaluador.cve_categoria, cat_tut_evaluador.nom_nombre, prereg_evaluador.cve_cat, cat_pre_evaluador.nom_nombre,
                evaluado.username, evaluado.firstname, evaluado.lastname, evaluador.username, evaluador.firstname, evaluador.lastname, enc.reglas_evaluacion_cve,
                prereg_evaluador.cve_departamental, tut_evaluador.cve_departamento, depto_tut_evaluador.nom_depto_adscripcion, depto_tut_evaluador.cve_delegacion, depto_tut_evaluador.nom_delegacion,
                depto_pre_evaluador.nom_depto_adscripcion, depto_pre_evaluador.cve_delegacion, depto_pre_evaluador.nom_delegacion');
        //pr($this->db->last_query());                                  
        //$resultado['total'] = $num_rows[0]->total;
        //$resultado['columns'] = $query->list_fields();
        $resultado['data'] = $query->result_array();
        $resultado['preguntas'] = $this->reporte_preguntas();
        $resultado['respuestas'] = $this->reporte_detalle_resultados();
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria         

        return $resultado;
    }


    public function reporte_detalle_resultados($params = null) {
        $resultado = array();
        
        $query = $this->db->query('select * from encuestas.sse_evaluacion eva 
            inner join encuestas.sse_respuestas res on eva.reactivos_cve=res.reactivos_cve
            WHERE  eva.course_cve=838 and evaluador_rol_id=18 and evaluado_rol_id=32 AND eva.encuesta_cve=526');

        $temp = $query->result_array();
        foreach ($temp as $key_d => $dato) {
            $resultado['data'][$dato['course_cve']][$dato['group_id']][$dato['encuesta_cve']][$dato['evaluado_user_cve']][$dato['evaluador_user_cve']][$dato['preguntas_cve']] = $dato;
        }
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria         

        return $resultado;
    }

    public function reporte_preguntas($params = null) {
        $resultado = array();
        
        $query = $this->db->query('select * from encuestas.sse_preguntas preg where encuesta_cve=526');

        $resultado['data'] = $query->result_array();
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria         

        return $resultado;
    }

    /*
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
    }*/
}
