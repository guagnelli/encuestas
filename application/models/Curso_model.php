<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Curso_model extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->config->load('general');
        // $this->load->database();
    }

    public function listado_cursos($params = null) {
        $resultado = array();

        //$guarda_busqueda = false;
        /*
          $this->db->select("GROUP_CONCAT(pd_base_datos.bd_id)"); //////Ejemplo de compilación de consulta
          $this->db->join('pub_bd', 'pub_bd.bd_id = base_datos.bd_id');
          $this->db->where('pub_bd.pub_id=publicacion.pub_id');
          $base_datos = $this->db->get_compiled_select('base_datos');
         */
        /*
          -- ANIO
          -- TIPO_CURSO
          -- TEXTO_PLANO
          -- tutorizado
          -- alcance
         */
        //pr($params);exit();
        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        $this->db->start_cache();
        $this->db->select('mdl_course.id');

        if (isset($params['cur_clave']) && !empty($params['cur_clave'])) { ////// Ejemplo - Like 
            $this->db->like('mdl_course.shortname', $params['cur_clave']);
            //$guarda_busqueda = true;
        }/*
          if(isset($params['tipo_curso']) && !empty($params['tipo_curso']))
          {
          //$guarda_busqueda = true;
          $this->db->where('mdl_course_config.tipocur',$params['tipo_curso']);
          } */
        if (isset($params['cur_id']) && !empty($params['cur_id'])) {
            //$guarda_busqueda = true;
            $this->db->where('mdl_course.id', $params['cur_id']);
        }
        if (isset($params['anio']) && !empty($params['anio'])) {
            //$guarda_busqueda = true;
            $this->db->where("TO_CHAR(TO_TIMESTAMP(mdl_course.startdate),'YYYY')='" . $params['anio'] . "'");
        }/**/

        //pr($params);
        $this->db->join('public.mdl_course_config', 'mdl_course_config.course=mdl_course.id');
        $this->db->join('public.mdl_course_categories', 'mdl_course_categories.id=mdl_course.category');
        //$this->db->group_by("mdl_user.id");

        $this->db->stop_cache();
        /////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
        ///////////////////////////// Obtener número de registros ///////////////////////////////
        $nr = $this->db->get_compiled_select('mdl_course'); //Obtener el total de registros
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (" . $nr . ") AS temp")->result();
        //pr($this->db1->last_query());
        /////////////////////////////// FIN número de registros /////////////////////////////////
        $busqueda = array(
            'mdl_course.id AS cur_id',
            'mdl_course.shortname AS cur_clave',
            'mdl_course.fullname AS cur_nom_completo',
            'mdl_course.category AS cat_cve',
            'mdl_course_categories."name" AS cat_nom',
            'TO_TIMESTAMP(mdl_course.startdate) AS fecha_inicio',
            "TO_CHAR(TO_TIMESTAMP(mdl_course.startdate),'YYYY') AS anio",
            'mdl_course_config.horascur',
            'mdl_course_config.modalidad',
            'mdl_course_config.tipocur',
            'mdl_course_config.startdatepre',
            'mdl_course_config.tutorizado',
                //'mdl_course_config.curso_alcance'
        );

        /*
          cur_id
          cur_clave
          cur_nom_completo
          cat_cve
          cat_nom
          fecha_inicio
          anio
          horascur
          tipocur
          modalidad
          startdatepre
          tutorizado
          curso_alcance

         */
        $this->db->select($busqueda);
        if (isset($params['order']) && !empty($params['order'])) {
            $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
            $this->db->order_by($params['order'], $tipo_orden);
        }
        if (isset($params['per_page']) && isset($params['current_row'])) { //Establecer límite definido para paginación 
            $this->db->limit($params['per_page'], $params['current_row']);
        }

        //$this->db->order_by("title", "desc");
        $this->db->order_by('mdl_course.fullname', 'ASC');
        $query = $this->db->get('mdl_course'); //Obtener conjunto de registros
//        pr($this->db->last_query());                                  
        $resultado['total'] = $num_rows[0]->total;
        $resultado['columns'] = $query->list_fields();
        $resultado['data'] = $query->result_array();
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria                                
        /*
          if(isset($guarda_busqueda) && $guarda_busqueda == true){
          $this->guarda_busqueda($params);
          } */

        return $resultado;
    }

    public function listar_roles_curso($params = null) {
        $resultado = array();
        //pr($params); exit();
        $this->db->start_cache(); // inicia historial de busqueda
        $this->db->select('mdl_role.id');

        $this->db->where('mdl_course.id', $params['cur_id']);

        $this->db->join('public.mdl_context', 'mdl_context.instanceid=mdl_course.id');
        $this->db->join('public.mdl_role_assignments', 'mdl_context.id=mdl_role_assignments.contextid');
        $this->db->join('public.mdl_role', 'mdl_role.id=mdl_role_assignments.roleid');
        $this->db->join('public.mdl_user', 'mdl_user.id=mdl_role_assignments.userid');
        $this->db->group_by('mdl_role.id');

        $this->db->stop_cache(); // hasta aqui el historial de busqueda
        ///////////////////////////// Obtener número de registros ///////////////////////////////
        $nr = $this->db->get_compiled_select('mdl_course'); //Obtener el total de registros

        $num_rows = $this->db->query("SELECT count(*) AS total FROM (" . $nr . ") AS temp")->result();

        //pr($num_rows);

        $busqueda = array(
            'mdl_role.id AS rol_id',
            'mdl_role.name AS nom_rol',
            'COUNT(mdl_role.id) AS usuarios_por_rol'
        );

        $this->db->select($busqueda);
        $this->db->order_by("nom_rol", "asc");
        $query = $this->db->get('mdl_course');

        //pr($this->db->last_query()); 

        $resultado['total'] = $num_rows[0]->total; // obtenemos el total de filas de la consulta
        $resultado['columns'] = $query->list_fields(); // obtenemos las columnas de la consulta
        $resultado['data'] = $query->result_array(); //guardamos en data el resultado de la consulta
        //pr($resultado['data']);

        $this->db->flush_cache(); // limpiamos la cache
        $query->free_result(); //Libera la memoria 

        return $resultado;
    }

    public function listar_grupos_curso($params = null) {
        $resultado = array();
        //pr($params); exit();

        $this->db->start_cache(); // inicia historial de busqueda
        $this->db->select('mdl_groups.id');
        $this->db->where('mdl_groups.courseid', $params['cur_id']);
        $this->db->stop_cache(); // hasta aqui el historial de busqueda


        $nr = $this->db->get_compiled_select('public.mdl_groups'); //Obtener el total de registros
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (" . $nr . ") AS temp")->result();

        $busqueda = array('mdl_groups.id AS grup_id', 'mdl_groups.name AS grup_nom');

        $this->db->select($busqueda);
        $this->db->order_by("grup_nom", "asc");
        $query = $this->db->get('public.mdl_groups');

        $resultado['total'] = $num_rows[0]->total; // obtenemos el total de filas de la consulta
        $resultado['columns'] = $query->list_fields(); // obtenemos las columnas de la consulta
        $resultado['data'] = $query->result_array(); //guardamos en data el resultado de la consulta
        //pr($resultado['data']);

        $this->db->flush_cache(); // limpiamos la cache
        $query->free_result(); //Libera la memoria 

        return $resultado;
    }

    public function listar_usuarios_grupo($params = null) {
        $resultado = array();
        //pr($params); exit();

        $this->db->start_cache(); // inicia historial de busqueda
        $this->db->select('mdl_groups.id');
        $this->db->where('mdl_groups.courseid', $params['cur_id']);
        $this->db->stop_cache(); // hasta aqui el historial de busqueda


        $nr = $this->db->get_compiled_select('public.mdl_groups'); //Obtener el total de registros
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (" . $nr . ") AS temp")->result();

        $busqueda = array('mdl_groups.id AS grup_id', 'mdl_groups.name AS grup_nom');

        $this->db->select($busqueda);
        $query = $this->db->get('public.mdl_groups');

        $resultado['total'] = $num_rows[0]->total; // obtenemos el total de filas de la consulta
        $resultado['columns'] = $query->list_fields(); // obtenemos las columnas de la consulta
        $resultado['data'] = $query->result_array(); //guardamos en data el resultado de la consulta
        //pr($resultado['data']);

        $this->db->flush_cache(); // limpiamos la cache
        $query->free_result(); //Libera la memoria 

        return $resultado;
    }

}
