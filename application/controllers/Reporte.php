<?php   defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona el login
 * @version 	: 1.0.0
 * @autor 		: Pablo José
 */
class Reporte extends CI_Controller
{
    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access 		: public
     * * @modified 	: 
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->model('Reporte_model', 'rep_mod'); // modelo de reporte
        $this->load->model('Curso_model', 'cur_mod'); // modelo de cursos
        $this->load->model('Encuestas_model', 'enc_mod');
    }

    public function index()
    {
        $anios = $this->lista_anios(2009,date('Y'));
        $rol = $this->config->item('rol_docente');
        $adscripcion;
        $categoria;
        
        //$data['categoria']=dropdown_options($categoria, 'cve_categoria','nom_nombre');
        //$data['adscripcion']=dropdown_options($adscripcion, '','');
        $data['anios']=dropdown_options($anios, 'anio_id','anio_desc');
        $data['rol']=dropdown_options($rol, 'rol_id','rol_nom');
        $datos['order_columns'] = array('emp_matricula'=>'Matrícula', 'cve_depto_adscripcion'=>'Adscripción', 'cat_nombre'=>'Categoría', 'grup_nom'=>'BD');

            /*
            [2] => emp_matricula
            [3] => emp_nombre
            [11] => cat_nombre
            [15] => fch_pre_registro
            [17] => cur_clave
            [18] => cur_nom_completo
            [19] => fecha_inicio
            [20] => horascur
            [21] => fecha_fin
            [24] => grup_nom
            [25] => tutorizado
            [26] => curso_alcance
            [27] => rol_nom
            [28] => tipocur
            */

        /*
        $data['profesores'] = $this->rep_mod->reporte_usuarios(array('per_page'=>10, 'current_row'=>1));
        pr($data['profesores']);
        
        */
        $main_contet = $this->load->view('reporte/docentes', $data, true);
        $this->template->setMainContent($main_contet);
        $this->template->getTemplate();
        
    }  
    
//    public function index()
//    {
//        
//        //$anios = $this->lista_anios(2009,date('Y'));
//        $rol = $this->config->item('rol_docente');
//        $cursoimp= $this->cur_mod->listado_cursos();
//        //pr($cursoimp['data']);
//        $curso=array();
//        foreach ($cursoimp['data']as $key => $value) {
//            $curso[$value['cur_id']]=$value['cur_nom_completo'].'('.$value['cur_clave'].')';
//            
//            # code...
//        }
//        $categoria;
//        //$data['categoria']=dropdown_options($categoria, 'cve_categoria','nom_nombre');
//        //$data['adscripcion']=dropdown_options($adscripcion, '','');
//        
//        //$data['anios']=dropdown_options($anios, 'anio_id','anio_desc');
//        //$data['rol']=dropdown_options($rol, 'rol_id','rol_nom');
//        $data['curso']=$curso;
//        $datos['order_columns'] = array('emp_matricula'=>'Matrícula', 'cve_depto_adscripcion'=>'Adscripción', 'cat_nombre'=>'Categoría', 'grup_nom'=>'BD');
//        $main_contet = $this->load->view('reporte/encuestasrealizadas', $data, true);
//        $this->template->setMainContent($main_contet);
//        $this->template->getTemplate();
//        
//    }
    

    public function get_data_ajax($current_row = null) {
        if ($this->input->is_ajax_request()) { //Sólo se accede al método a través de una petición ajax
            if (!is_null($this->input->post())) { //Se verifica que se haya recibido información por método post
                //aqui va la nueva conexion a la base de datos del buscador
                //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda
                $filtros = $this->input->post();
                $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;

                //pr($filtros);
                //$resultado = $this->rep_mod->reporte_usuarios($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                $idcurso=$this->input->post('curso');
                $datos_curso=$this->cur_mod->listado_cursos(array('cur_id'=> $idcurso));
                //pr($datos_curso);
                /**/
                //usuarios enrolados en el curso
                $usuarioscurso=$this->rep_mod->listado_usuariosenc($filtros);
                //pr($usuarioscurso);

               foreach ($usuarioscurso as $keyuc => $valueuc) 
               {

                 $rolescusercurso=$this->enc_mod->get_roles_usercurso(array('user_id' => $valueuc['cve_usuario'],'cur_id'=>$idcurso));
                 //pr($rolescusercurso);
            

                foreach ($rolescusercurso as $key => $value) 
                {

                  //checar reglas validas con encuestas asignadas al curso
                  //pr($value);

                  $reglas_validas=$this->enc_mod->get_reglas_validas_cur(array('role_evaluador' => $value,
                      'tutorizado'=> $datos_curso['data'][0]['tutorizado'],'cur_id'=> $idcurso,'ord_prioridad' => '1'));
                  //pr($reglas_validas);
                  if(isset($reglas_validas) && !empty($reglas_validas))
                  {
                      echo "entra";
                      //pr($reglas_validas);
                      foreach ($reglas_validas as $keyr => $valuer) 
                      {
                         //pr($value['is_excepcion']);
                         if($valuer['is_excepcion'] == 0)
                         {
                          //no hay excepciones armar el arreglo para buscar usuarios
                          $reglasgral[]=array('reglas_evaluacion_cve' => $valuer['reglas_evaluacion_cve'],
                                              'rol_evaluado_cve' => $valuer['rol_evaluado_cve'],
                                              'encuesta_cve' => $valuer['encuesta_cve'],
                                              'eva_tipo' => $valuer['eva_tipo']
                                              );
                         
                         }
                         else
                         {
                          //
                           $reglas_validas=$this->enc_mod->get_reglas_validas_cur(array('role_evaluador' => $value,
                          'tutorizado'=> $datos_curso['data'][0]['tutorizado'],'cur_id'=> $idcurso,'ord_prioridad' => '2'));

                            $reglasgral[]=array('reglas_evaluacion_cve' => $reglas_validas['reglas_evaluacion_cve'],
                                              'rol_evaluado_cve' => $reglas_validas['rol_evaluado_cve'],
                                              'encuesta_cve' => $reglas_validas['encuesta_cve'],
                                              'eva_tipo' => $reglas_validas['eva_tipo']
                                              );
                          }

               
                      }

                      foreach ($reglasgral as $keyrg => $valuerg) 
                      {
                        //pr($valuerg['eva_tipo']);

                                if($valuerg['eva_tipo'] == 1)
                                {
                                  //por persona
                                  //echo "por persona";
                                  $datos_user_aeva[]=$this->rep_mod->listado_eval_reporte(array('role_evaluado' => $valuerg['rol_evaluado_cve'],
                                    'cur_id' => $idcurso,'encuesta_cve' => $valuerg['encuesta_cve'],
                                    'evaluador_user_cve' => $idusuario,'role_evaluador' => $value)
                                  );
                                          
                                    
                                }
                                else
                                {
                                  //por grupo
                                   //echo "por grupo";
                                   $datos_usuario=$this->enc_mod->get_datos_usuarios(array('user_id' => $idusuario,'cur_id'=>$idcurso,'rol_evaluado_cve' => $valuerg['rol_evaluado_cve']));
                                   //pr($datos_usuario);
                                   if(isset($datos_usuario) || isset($datos_curso) || !empty($datos_usuario) || !empty($datos_curso))
                                   {
                                     foreach ($datos_usuario as $key => $value) 
                                     {

                                        //role evaluador
                                        $role_evaluador=$value['cve_rol'];
                                        //pr($role_evaluador);
                                        //grupo del evaluador
                                        $gpo_evaluador=$value['cve_grupo'];# code...
                                        



                                       $datos_user_aeva[]=$this->rep_mod->listado_eval_reporte(array('gpo_evaluador' => $gpo_evaluador,'role_evaluado' => $valuerg['rol_evaluado_cve'],
                                        'cur_id' => $idcurso,'encuesta_cve' => $valuerg['encuesta_cve'],
                                        'evaluador_user_cve' => $idusuario,
                                        'role_evaluador' => $role_evaluador)
                                        );
                                     }
                                  //pr($datos_user_aeva);
                                    }

                                }
                        }

                    }
                    else
                    {
                        echo "entra2";
                    }
   
             //fin de reglas validas 

              }



            } 






                /*$resultado = $datos_user_aeva; //Datos del formulario se envían para generar la consulta segun los filtros
                $data=$filtros;
                $data['total_empleados'] = $resultado['total'];
                $data['empleados'] = $resultado['data'];
                $data['current_row'] = $filtros['current_row'];
                $data['per_page'] = $this->input->post('per_page');
                //pr($data);
                $this->listado_resultado($data, array('form_recurso' => '#form_empleado', 'elemento_resultado' => '#listado_resultado_empleado')); //Generar listado en caso de obtener datos
            */
            }

        } else {
        
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        
        }
    }

    public function get_data_ajax2($current_row = null) {
        if ($this->input->is_ajax_request()) { //Sólo se accede al método a través de una petición ajax
            if (!is_null($this->input->post())) { //Se verifica que se haya recibido información por método post
                //aqui va la nueva conexion a la base de datos del buscador
                //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda
                $filtros = $this->input->post();
                $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;

                //pr($filtros);
                $resultado = $this->rep_mod->reporte_usuarios($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                $data=$filtros;
                $data['total_empleados'] = $resultado['total'];
                $data['empleados'] = $resultado['data'];
                $data['current_row'] = $filtros['current_row'];
                $data['per_page'] = $this->input->post('per_page');
                //pr($data);
                $this->listado_resultado($data, array('form_recurso' => '#form_empleado', 'elemento_resultado' => '#listado_resultado_empleado')); //Generar listado en caso de obtener datos
            
            }

        } else {
        
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        
        }
    }
    
    private function listado_resultado($data, $form){
        $pagination = $this->template->pagination_data_empleado($data, 'get_data_ajax2'); //Crear mensaje y links de paginación
        $links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>".$pagination['total']."</div>
                <div class='col-sm-7 text-right'>".$pagination['links']."</div>";
        echo $links.$this->load->view('reporte/listado_usuariosenc', $data, TRUE).$links.'
            <script>
            $("ul.pagination li a").click(function(event){
                data_ajax(this, "'.$form['form_recurso'].'", "'.$form['elemento_resultado'].'");
                event.preventDefault();
            });
            </script>';
    }


    public function lista_anios($anio_inicio, $anio_fin)
    {
        $anios = array();
        for ($anio = $anio_inicio; $anio<= $anio_fin; $anio++) 
        {
            $anios[] = array('anio_id'=>$anio, 'anio_desc'=>$anio);
        }
        return $anios;
    }



}
