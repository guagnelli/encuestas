<?php   defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte_model extends CI_Model {
  	public function __construct() {
          // Call the CI_Model constructor
          parent::__construct();
          $this->config->load('general');
          //$this->load->database();
    }
    
    
    /**
    * @access   public
    * @param    array[optional] (params=>)
    * @return   array (total=>total de registros, columns=>nombres de los campos, data=>campos)
    *
    */
    public function usuarios_curso_grupo($id_curso, $id_grupo)
    {
        $resultado = array();
                
        $guarda_busqueda = false;

        //pr($id_grupo);exit();

        $cols_reporte = array(
                            'mdl_user.id AS userid', 
                            'mdl_user.username', 
                            'mdl_user.nom', 
                            'mdl_user.pat', 
                            'mdl_user.mat', 
                            'mdl_role.id AS roleid',
                            'mdl_role.name as role', 
                            'mdl_course_config.tutorizado',
                            //'mdl_course_config.curso_alcance',
                            'mdl_course.fullname',
                            'mdl_course.shortname as clave_curso',
                            'mdl_course.tipocur', 
                            'to_timestamp(mdl_course.startdate) as fecha_inicio', 
                            'mdl_course.horascur',
                            'mdl_groups.name',
                            'mdl_groups.id'
                        );
        $this->db->where(array('mdl_course.id'=>$id_curso, 'mdl_groups_members.groupid'=>$id_grupo));
        $this->db->where_in('mdl_role.id', array(14,18,32,33));
        $this->db->join('public.mdl_context', 'mdl_context.instanceid = mdl_course.id');
        $this->db->join('public.mdl_role_assignments', 'mdl_context.id = mdl_role_assignments.contextid');
        $this->db->join('public.mdl_role', 'mdl_role.id = mdl_role_assignments.roleid');
        $this->db->join('public.mdl_user', 'mdl_user.id = mdl_role_assignments.userid');
        $this->db->join('public.mdl_course_config', 'mdl_course_config.course = mdl_course.id');
        $this->db->join('public.mdl_groups_members', 'mdl_groups_members.userid=mdl_user.id', 'left');
        $this->db->join('public.mdl_groups', 'mdl_groups_members.groupid=mdl_groups.id', 'left');
        $this->db->order_by('roleid', 'ASC');
        $this->db->select($cols_reporte);        
        $query = $this->db->get('public.mdl_course');                                  
        //$resultado['total']=$num_rows[0]->total;
        //pr($this->db->last_query());
        $resultado['columns']=$query->list_fields();
        $resultado['data']=$query->result_array();
        
        return $resultado;

        /*
            SELECT 
                us.id AS userid, 
                us.username, 
                us.nom, 
                us.pat, 
                us.mat, 
                ro.id AS roleid,
                ro.name as role, 
                curcnf.tutorizado,
                curcnf.curso_alcance,
                cur.fullname,
                cur.shortname as clave_curso,
                cur.tipocur, 
                to_timestamp(cur.startdate) as fecha_inicio, 
                cur.horascur
            FROM mdl_course cur
            INNER JOIN mdl_context ctx ON ctx.instanceid = cur.id
            INNER JOIN mdl_role_assignments ra ON ctx.id = ra.contextid
            INNER JOIN mdl_role ro ON ro.id = ra.roleid
            INNER JOIN mdl_user us ON us.id = ra.userid
            INNER JOIN mdl_course_config curcnf ON curcnf.course = cur.id
            LEFT JOIN public.mdl_groups_members gpmb ON gpmb.userid=us.id
            WHERE  cur.id=823 AND ro.id IN (14,18,32,33) AND gpmb.groupid=12367 --
            ORDER BY roleid ASC;
        */

    }
        
    
    public function reporte_usuarios($params=null) {
        $resultado = array();

        ///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        $this->db->start_cache();
        $this->db->select('mdl_user.id');

        if(isset($params['rol']) && !empty($params['rol']))
        {
            //$guarda_busqueda = true;
            $this->db->where('mdl_role.id',$params['rol']);             
        } 

        if(isset($params['anio']) && !empty($params['anio']))
        {
            //$guarda_busqueda = true;
            $this->db->where("TO_CHAR(TO_TIMESTAMP(mdl_course.startdate),'YYYY')='".$params['anio']."'");             
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
        $num_rows = $this->db->query("SELECT count(*) AS total FROM (".$nr.") AS temp")->result();
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
        if(isset($params['order']) && !empty($params['order'])){
            $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
            $this->db->order_by($params['order'], $tipo_orden);
        }
        if(isset($params['per_page']) && isset($params['current_row'])){ //Establecer límite definido para paginación 
            $this->db->limit($params['per_page'], $params['current_row']);
        }
        $query = $this->db->get('mdl_user'); //Obtener conjunto de registros
        //pr($this->db->last_query());                                  
        $resultado['total']=$num_rows[0]->total;
        $resultado['columns']=$query->list_fields();
        $resultado['data']=$query->result_array();
        //pr($resultado['data']);
        $this->db->flush_cache();
        $query->free_result(); //Libera la memoria         
            
        return $resultado;
    }

    public function listado_usuariosenc($idcurso)
    {
        $resultado = array();
        /*SELECT c.id AS cve_curso,  u.id AS cve_usuario, u.username AS username, u.firstname AS nom, u.lastname AS pat,
        u.cve_departamental
   FROM mdl_user u
   JOIN mdl_role_assignments ra ON ra.userid = u.id
   JOIN mdl_context ct ON ct.id = ra.contextid
   JOIN mdl_course c ON c.id = ct.instanceid
   JOIN mdl_role r ON r.id = ra.roleid
   JOIN mdl_enrol en ON en.courseid = c.id
   JOIN mdl_user_enrolments ue ON ue.enrolid = en.id AND ue.userid = u.id
   where c.id=761 and r.id not in(11,29,19,34)*/

        $this->db->where('c.id',$idcurso);
        $this->db->where_not_in('r.id',11);
        $this->db->where_not_in('r.id',19);
        $this->db->where_not_in('r.id',29);
        $this->db->where_not_in('r.id',34);

        $this->db->join('public.mdl_role_assignments ra', 'ra.userid = u.id');
        $this->db->join('public.mdl_context ct','ct.id = ra.contextid');
        $this->db->join('public.mdl_course c', 'c.id = ct.instanceid');
        $this->db->join('public.mdl_role r','r.id = ra.roleid');
        $this->db->join('public.mdl_enrol en', 'en.courseid = c.id');
        $this->db->join('public.mdl_user_enrolments ue','ue.enrolid = en.id AND ue.userid = u.id');
        
        $busqueda = array(
                    'c.id AS cve_curso',
                    'u.id AS cve_usuario', 
                    'u.username AS username',  
                    'u.firstname AS nombres', 
                    'u.lastname AS apellidos',
                    'u.cve_departamental AS cve_ads');

        $this->db->select($busqueda);


        $query = $this->db->get('public.mdl_user u');
        $resultado = $query->result_array();
        
        $query->free_result(); //Libera la memoria

        return $resultado;

    }


    public function listado_eval_reporte($params=null)
   {
        $this->db->where('tutorias.mdl_userexp.cursoid',$params['cur_id']);


        if(isset($params['gpo_evaluador']) && !empty($params['gpo_evaluador']))
        {


            $this->db->where('tutorias.mdl_userexp.cursoid',$params['cur_id']);
            //$this->db->where('tutorias.mdl_userexp.role',$params['role_evaluado']);
            $this->db->where('tutorias.mdl_userexp.grupoid',$params['gpo_evaluador']);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);

            $this->db->select('public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role, public.mdl_groups.name as ngpo, 
                (select public.mdl_role.name from public.mdl_role where id='.$params['role_evaluador'].') as evaluador,'. 
                $params['encuesta_cve']. ' as regla, public.mdl_groups.id as gpoid, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve='. $params['encuesta_cve'].' and course_cve='.$params['cur_id'].' and group_id='.$params['gpo_evaluador'].' 
                    and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve='.$params['evaluador_user_cve'].')  as realizado');

            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role','public.mdl_role.id= tutorias.mdl_userexp.role');
            $this->db->join('public.mdl_groups', 'public.mdl_groups.id=tutorias.mdl_userexp.grupoid');
        }
        else
        {


            $params['gpo_evaluador']=0;
            $consulta='public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role,'. $params['gpo_evaluador'].' as ngpo,
              (select public.mdl_role.name from public.mdl_role where id='.$params['role_evaluador'].') as evaluador,'. 
                    $params['encuesta_cve']. ' as regla, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                    (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve='. $params['encuesta_cve'].' and course_cve='.$params['cur_id'].' 
                        and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve='.$params['evaluador_user_cve'].')  as realizado';
         

            

            $this->db->distinct($consulta);
            $this->db->select($consulta);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);

                
            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role','public.mdl_role.id= tutorias.mdl_userexp.role');

        }

        $query = $this->db->get('tutorias.mdl_userexp'); 

        $resultado = $query->result_array();
        return $resultado;
   }


   public function listado_evaluados($params=null)
    {

        $resultado = array();
        $this->db->where('tutorias.mdl_userexp.cursoid',$params['cur_id']);


        if(isset($params['gpo_evaluador']) && !empty($params['gpo_evaluador']))
        {


            $this->db->where('tutorias.mdl_userexp.cursoid',$params['cur_id']);
            //$this->db->where('tutorias.mdl_userexp.role',$params['role_evaluado']);
            $this->db->where('tutorias.mdl_userexp.grupoid',$params['gpo_evaluador']);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);

            $this->db->select('public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role, public.mdl_groups.name as ngpo, 
                (select public.mdl_role.name from public.mdl_role where id='.$params['role_evaluador'].') as evaluador,'. 
                $params['encuesta_cve']. ' as regla, public.mdl_groups.id as gpoid, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve='. $params['encuesta_cve'].' and course_cve='.$params['cur_id'].' and group_id='.$params['gpo_evaluador'].' 
                    and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve='.$params['evaluador_user_cve'].')  as realizado');

            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role','public.mdl_role.id= tutorias.mdl_userexp.role');
            $this->db->join('public.mdl_groups', 'public.mdl_groups.id=tutorias.mdl_userexp.grupoid');
        }
        else
        {


            $params['gpo_evaluador']=0;
            $consulta='public.mdl_user.firstname,public.mdl_user.lastname,public.mdl_role.name as role,'. $params['gpo_evaluador'].' as ngpo,
              (select public.mdl_role.name from public.mdl_role where id='.$params['role_evaluador'].') as evaluador,'. 
                    $params['encuesta_cve']. ' as regla, tutorias.mdl_userexp.cursoid as cursoid, public.mdl_user.id as userid,
                    (select evaluacion_resul_cve from encuestas.sse_result_evaluacion where encuesta_cve='. $params['encuesta_cve'].' and course_cve='.$params['cur_id'].' 
                        and evaluado_user_cve=public.mdl_user.id and evaluador_user_cve='.$params['evaluador_user_cve'].')  as realizado';
         

            

            $this->db->distinct($consulta);
            $this->db->select($consulta);

            $this->db->where('tutorias.mdl_userexp.role', $params['role_evaluado']);

                
            $this->db->join('public.mdl_user', 'public.mdl_user.id= tutorias.mdl_userexp.userid');
            $this->db->join('public.mdl_role','public.mdl_role.id= tutorias.mdl_userexp.role');

        }

       
        
        
        //$this->db->select($busqueda);
        if(isset($params['filtros']['order']) && !empty($params['filtros']['order'])){
            $tipo_orden = (isset($params['filtros']['order_type']) && !empty($params['filtros']['order_type'])) ? $params['filtros']['order_type'] : "ASC";
            $this->db->order_by($params['filtros']['order'], $tipo_orden);
        }

//        pr($params['filtros']);
        if(isset($params['filtros']['per_page']) && isset($params['filtros']['current_row'])){ //Establecer límite definido para paginación 
            $this->db->limit($params['filtros']['per_page'], $params['filtros']['current_row']);
        }
        
         $query = $this->db->get('tutorias.mdl_userexp'); 

        //$resultado['total']=$query->num_rows();
        //$resultado['columns']=$query->list_fields();


        $resultado= $query->result_array();
        return $resultado;
    }
    
    public function get_anios(){
        $this->db->select('distinct(fecha)');

        $this->db->order_by('fecha', 'desc');

        $query = $this->db->get('encuestas.sse_evaluacion');

        //pr($this->db->last_query());
        return $query->result_array();
    }
    
}