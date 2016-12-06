<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Muestra listado de usuarios y su prefil a encuestar 
 * @version   : 1.0.0
 * @autor     : Hilda Trejo
 */
class Encuestausuario extends CI_Controller {

    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access    : public
     * * @modified  : 
     */
    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->config->load('general'); // instanciamos el archivo de constantes generales
        $this->load->model('Encuestas_model', 'enc_mod');
        $this->load->model('Curso_model', 'cur_mod'); // modelo de cursos
        $this->load->library('form_validation'); //implemantación de la libreria form validation
        $this->load->library('form_complete'); // form complete
        $this->config->load('form_validation'); // abrir el archivo general de validaciones
        $this->config->load('general'); // instanciamos el archivo de constantes generales
    }

    public function lista_encuesta_usuario_bck() {


        $variables = $_SERVER['QUERY_STRING']; //Verificando si tiene variables por GET
        $amigable = '';

        if (!empty($variables) & $_SERVER['REQUEST_METHOD'] == 'GET') {

//Si tiene variables pasadas por GET se procede a hacer el cambio.
//1. Agrupando $Variables por “variable=valor” en el array $Variable.
            //var_dump($variables);
            //echo '<br>';
            $varr = array();
            $variable = explode('&', $variables);
            //var_dump($variable);


            for ($i = 0; $i < count($variable); $i++) {

                $variable1 = explode('=', $variable[$i]);
                $varr[] = $variable1[1];
                //echo '<br>';
            }
        }
        $idusuario = $varr[0];
        $idcurso = $varr[1];
        //var_dump($idusuario);
        //var_dump($idcurso); 
        $datos = array();

        if (isset($idcurso) && !empty($idcurso) && isset($idusuario) && !empty($idusuario)) {



            $datos = array();

            $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $idcurso));
            //pr($datos_curso);
            //var_dump($datos_curso['data'][0]['tutorizado']);
            //$datos_roles_curso=$this->cur_mod->listar_roles_curso(array('cur_id'=>$idcurso));
            //pr($datos_roles_curso['data']);
            //pr(array_values($datos_roles_curso['data
            //roles por curso por usuario
            $rolescusercurso = $this->enc_mod->get_roles_usercurso(array('user_id' => $idusuario, 'cur_id' => $idcurso));
            pr($rolescusercurso);

            $datos_usuario = $this->enc_mod->get_datos_usuarios(array('user_id' => $idusuario, 'cur_id' => $idcurso));
            //pr($datos_usuario);
            //$rol=$this->enc_mod->get_roles_curso($idcurso);
            //pr($rol);
            if (isset($datos_usuario) || isset($datos_curso) || !empty($datos_usuario) || !empty($datos_curso)) {

                foreach ($datos_usuario as $key => $value) {

                    //role evaluador
                    $role_evaluador = $value['cve_rol'];
                    //pr($role_evaluador);
                    //grupo del evaluador
                    $gpo_evaluador = $value['cve_grupo']; # code...
                    //pr($gpo_evaluador);

                    $rol = $this->enc_mod->get_roles_curso_gpo($idcurso, $gpo_evaluador, $role_evaluador);
                    //pr($rol);

                    $role_evaluado = array();
                    if (($role_evaluador == 5) && ($datos_curso['data'][0]['tutorizado'] == 1)) {
                        if (in_array(32, $rol)) {
                            //echo "entra1";
                            //NO buscar excepciones y es el prioritario
                            array_push($role_evaluado, 32);
                            if (in_array(33, $rol)) {
                                //NO buscar excepciones y es el prioritario
                                //echo "entra2";
                                array_push($role_evaluado, 33);
                            }
                        } else {
                            //checar excepciones
                            if (in_array(33, $rol)) {
                                //NO buscar excepciones y es el prioritario
                                //echo "entra2";
                                array_push($role_evaluado, 33);
                            } else {
                                array_push($role_evaluado, 14);
                            }
                            //array_push($role_evaluado,14);
                        }
                    } elseif (($role_evaluador == 32) && ($datos_curso['data'][0]['tutorizado'] == 1)) {
                        if (in_array(18, $rol)) {
                            //NO buscar excepciones y es el prioritario
                            array_push($role_evaluado, 18);
                        } else {
                            //checar excepciones
                            array_push($role_evaluado, 14);
                        }
                    } else if (($role_evaluador == 33) && ($datos_curso['data'][0]['tutorizado'] == 1)) {
                        if (in_array(18, $rol)) {
                            //NO buscar excepciones y es el prioritario
                            array_push($role_evaluado, 18);
                        } else {
                            //checar excepciones
                            array_push($role_evaluado, 14);
                        }
                    } else if (($role_evaluador == 14) && ($datos_curso['data'][0]['tutorizado'] == 1)) {
                        if (in_array(18, $rol)) {
                            //NO buscar excepciones y es el prioritario
                            array_push($role_evaluado, 18);
                        } else {
                            //checar excepciones
                            array_push($role_evaluado, 32);
                            array_push($role_evaluado, 33);
                        }
                    } else {
                        # code...
                        //echo "entra";
                        array_push($role_evaluado, 0);
                    }


                    //pr($role_evaluado);   
                    //Buscar reglas a cumplir de acuerdo al role de evaluador y el curso
                    /*  $reglas_validas=$this->enc_mod->get_reglas_validas(array('role_evaluador' => $role_evaluador,'tutorizado'=> $datos_curso['data'][0]['tutorizado'],
                      'bono' => 1,'cur_id'=> $idcurso,'role_evaluado' => $role_evaluado)); */
                    $reglas_validas = $this->enc_mod->get_reglas_validas_curso(array('role_evaluador' => $role_evaluador,
                        'tutorizado' => $datos_curso['data'][0]['tutorizado'], 'cur_id' => $idcurso, 'role_evaluado' => $role_evaluado));

                    //pr($reglas_validas);

                    if ($reglas_validas) {
                        foreach ($reglas_validas as $index => $value) {
                            # code...
                            //pr($value['rol_evaluado_cve']);
                            //pr($value['reglas_evaluacion_cve']);
                            /* $datos_user_aeva[]=$this->enc_mod->listado_eval(array('gpo_evaluador' => $gpo_evaluador,'role_evaluado' => $value['rol_evaluado_cve'],
                              'cur_id' => $idcurso,'cve_regla' => $value['reglas_evaluacion_cve'])); */
                            $datos_user_aeva[] = $this->enc_mod->listado_eval(array('gpo_evaluador' => $gpo_evaluador, 'role_evaluado' => $value['rol_evaluado_cve'],
                                'cur_id' => $idcurso, 'encuesta_cve' => $value['encuesta_cve'], 'evaluador_user_cve' => $idusuario, 'role_evaluador' => $role_evaluador));
                            //$datos_user_aeva['eva']=$value['reglas_evaluacion_cve'];
                            //pr($datos_user_aeva);
                            $datos['datos_user_aeva'] = $datos_user_aeva;

                            //var_dump($datos_user_aeva);
                            //pr( $datos['datos_user_aeva']);
                        }
                    }
                }
                //encuestas realizadas
                //$encuestas_realizadas=$this->enc_mod->encuestas_realizadas();
                //pr($datos['datos_user_aeva']);
                //foreach ($datos['datos_user_aeva'] as $val) {

                /* pr($idusuario);
                  pr($val[0]['cursoid']);
                  pr($val[0]['userid']);
                  pr($val[0]['gpoid']);
                  pr($val[0]['regla']); */
                /*  $encuestas_realizadas=$this->enc_mod->encuestas_realizadas($idusuario,$val[0]['cursoid'],$val[0]['userid'],$val[0]['gpoid'],$val[0]['regla']);
                  //pr($encuestas_realizadas);

                  if(!isset($encuestas_realizadas) || empty($encuestas_realizadas))
                  {
                  $datos['datos_user_aeva'][0]=array('realizado' => 0);
                  }
                  else
                  {
                  $datos['datos_user_aeva'][0]=array('realizado' => 1);
                  }


                  } */


                //pr($datos['datos_user_aeva']);
                //die();
                //unset($role_evaluado);
                $datos['datos_curso'] = $datos_curso;
                //$datos['datos_usuario']=$datos_usuario;
                //$datos['datos_user_aeva'];
                //pr( $datos);
                $datos['iduevaluador'] = $idusuario;
            }
            /* $lista_roles = $this->session->userdata('lista_roles');
              $lista_roles_modulos = $this->session->userdata('lista_roles_modulos');

              $rol_seleccionado = get_array_valor($lista_roles_modulos, 3);
              $this->session->set_userdata('rol_seleccionado', $rol_seleccionado);

              $data['lista_roles'] = $lista_roles; */
            //pr($datos);
            //exit();

            $main_contet = $this->load->view('encuesta/lista_usuarios', $datos, true);
            $this->template->setMainContent($main_contet);
            $this->template->getTemplate();


            # code...
        }
    }

    public function instrumento_asignado() {
        if ($this->input->post()) {
            $id_instrumento = $this->input->post('idencuesta');
            //if (isset($id_instrumento) && !empty($id_instrumento)) {
            # code...
            $datos['instrumento'] = $this->enc_mod->get_instrumento_detalle($id_instrumento);
            $datos['preguntas'] = $this->enc_mod->preguntas_instrumento($id_instrumento);
            $datos['boton'] = TRUE;
            $datos['encuesta_cve'] = $this->input->post('idencuesta');
            $datos['evaluado_user_cve'] = $this->input->post('iduevaluado');
            $datos['evaluador_user_cve'] = $this->input->post('iduevaluador');
            $datos['curso_cve'] = $this->input->post('idcurso');
            $datos['grupo_cve'] = $this->input->post('idgrupo');

//            $parametrosp = array(
//                'curso_cve' => 838,
//                'grupo_cve' => 11843,
//                'evaluado_user_cve' => 10147,
//                'evaluado_rol_id' => 32,
//                'evaluador_rol_id' => 5,
//                'evaluador_user_cve' => 36138,
//                'encuesta_cve' => 514,
//                'is_bono' => 1)
//            ;
//
//            $promedio = $this->enc_mod->get_promedio_encuesta_encuesta($parametrosp);
////            pr($promedio);

            $main_contet = $this->load->view('encuesta/prev_encur', $datos, true);
            $this->template->setMainContent($main_contet);
            $this->template->getTemplate();
        } else {
            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function guardar_encuesta_usuario() {
        $id_instrumento = $this->input->post('idencuesta');
        //pr($this->input->post());
        $campos_evaluacion['encuesta_cve'] = $this->input->post('idencuesta');
        $campos_evaluacion['curso_cve'] = $this->input->post('idcurso');
        $campos_evaluacion['grupo_cve'] = $this->input->post('idgrupo');
        $campos_evaluacion['evaluado_user_cve'] = $this->input->post('iduevaluado');
        $campos_evaluacion['evaluador_user_cve'] = $this->input->post('iduevaluador');
        $campos_evaluacion['is_bono'] = $this->input->post('is_bono');
        //Buscar los roles con las reglas de evaluacion
        $reglas = $this->enc_mod->get_reglas_encuesta($this->input->post('idencuesta'));
        //pr($reglas);
        $fecha = date('Y-m-d: H:s');
        $campos_evaluacion['evaluado_rol_id'] = $reglas[0]['rol_evaluado_cve'];
        $campos_evaluacion['evaluador_rol_id'] = $reglas[0]['rol_evaluador_cve'];
        $campos_evaluacion['respuesta_abierta'] = '0';
        $campos_evaluacion['fecha'] = $fecha;


        $reactivos = $this->input->post('p_r');
        $encuesta_cve = $this->input->post('idencuesta');
        $busqueda = array('encuesta_cve' => $encuesta_cve);
        //pr($busqueda);

        $reactivos_base = $this->enc_mod->get_preguntas_encuesta($busqueda);
        foreach ($reactivos_base['data'] as $key => $value) {
            # code...
            //$arrpreguntas[]=$value['preguntas_cve'];
            $this->form_validation->set_rules('p_r[' . $value['preguntas_cve'] . ']', 'Pregunta', 'required', array('required' => 'Esta pregunta es requerida'));
        }


        if ($this->input->post('p_r')) { //Validar que la información se haya enviado por método POST para almacenado
            //Buscar los roles con las reglas de evaluacion
            $reglas = $this->enc_mod->get_reglas_encuesta($encuesta_cve);
            //var_dump($reglas[0]['rol_evaluado_cve']);

            $campos_evaluacion['reactivos'] = $reactivos;

            if ($this->form_validation->run()) { //Se ejecuta la validación de datos 
                $guardar_evaluacion = $this->enc_mod->guarda_reactivos_evaluacion($campos_evaluacion);
                if ($guardar_evaluacion) {
                    $datos['mensaje'] = 'El registro de la evaluación ha sido guardado correctamente';
                    $this->session->set_flashdata('success', 'El registro de la evaluación ha sido guardado correctamente'); // devuelve mensaje flash
                    $main_contet = $this->load->view('encuesta/final', $datos, true);
                    $this->template->setMainContent($main_contet);
                    $this->template->getTemplate();
                }
            } else {
                //echo "entra2";
                $campos_evaluacion['boton'] = TRUE;
                $campos_evaluacion['instrumento'] = $this->enc_mod->get_instrumento_detalle($id_instrumento);
                $campos_evaluacion['preguntas'] = $this->enc_mod->preguntas_instrumento($id_instrumento);
                $campos_evaluacion['id_instrumento'] = $id_instrumento;
                //$campos_evaluacion['mensaje']='Todos los campos son requeridos';
                $main_contet = $this->load->view('encuesta/prev_encur', $campos_evaluacion, true);
                $this->template->setMainContent($main_contet);
                $this->template->getTemplate();
            }
        } else {
            //pr($id_instrumento);
            $campos_evaluacion['boton'] = TRUE;
            $campos_evaluacion['instrumento'] = $this->enc_mod->get_instrumento_detalle($id_instrumento);
            $campos_evaluacion['preguntas'] = $this->enc_mod->preguntas_instrumento($id_instrumento);
            $campos_evaluacion['id_instrumento'] = $id_instrumento;
            $campos_evaluacion['mensaje'] = 'Todos los campos son requeridos';
            $main_contet = $this->load->view('encuesta/prev_encur', $campos_evaluacion, true);
            $this->template->setMainContent($main_contet);
            $this->template->getTemplate();
            //redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    public function lista_encuesta_usuario() {
        if ($this->input->get()) {
            $data_get = $this->input->get(null, true);
//                pr($data_get);
            $idusuario = $data_get['iduser'];
            $idcurso = $data_get['idcurso'];
            $sesion_valida = valida_sesion_activa($idusuario);
            $sesion_valida = 1;
            if ($sesion_valida) {

                $datos = array();

                $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $idcurso));
//                pr($datos_curso);
                $tutorizado = null;
                if (!empty($datos_curso['data'])) {
                    $tutorizado = $datos_curso['data'][0]['tutorizado'];
                }
                //pr($datos_curso);
                //var_dump($datos_curso['data'][0]['tutorizado']);
                //$datos_roles_curso=$this->cur_mod->listar_roles_curso(array('cur_id'=>$idcurso));
                //pr($datos_roles_curso['data']);
                //pr(array_values($datos_roles_curso['data
                //roles por curso por usuario
                $rolescusercurso = $this->enc_mod->get_roles_usercurso(array('user_id' => $idusuario, 'cur_id' => $idcurso));
                
//                $rolescusercurso = array(14, 18, 32, 5);
                $parametros = array('role_evaluador' => $rolescusercurso, 'tutorizado' => $tutorizado, 'cur_id' => $idcurso);
                $reglas_validas = $this->enc_mod->getReglasEvaluacionCurso($parametros);
                pr($reglas_validas);

                exit();

                foreach ($rolescusercurso as $key => $value) {

                    //checar reglas validas con encuestas asignadas al curso
                    //pr($value);

                    $reglas_validas = $this->enc_mod->get_reglas_validas_cur(array('role_evaluador' => $value,
                        'tutorizado' => $datos_curso['data'][0]['tutorizado'], 'cur_id' => $idcurso, 'ord_prioridad' => '1'));

                    pr($reglas_validas);
                    foreach ($reglas_validas as $keyr => $valuer) {
                        //pr($value['is_excepcion']);
                        if ($valuer['is_excepcion'] == 0) {
                            //no hay excepciones armar el arreglo para buscar usuarios
                            $reglasgral[] = array('reglas_evaluacion_cve' => $valuer['reglas_evaluacion_cve'],
                                'rol_evaluado_cve' => $valuer['rol_evaluado_cve'],
                                'encuesta_cve' => $valuer['encuesta_cve'],
                                'eva_tipo' => $valuer['eva_tipo'],
                                'is_bono' => $valuer['is_bono'],
                            );
                            //pr($reglasgral);
                        } else {
                            //
                            $reglas_validas = $this->enc_mod->get_reglas_validas_cur(array('role_evaluador' => $value,
                                'tutorizado' => $datos_curso['data'][0]['tutorizado'], 'cur_id' => $idcurso, 'ord_prioridad' => '2'));
                            //pr($reglas_validas);

                            $reglasgral[] = array('reglas_evaluacion_cve' => $reglas_validas['reglas_evaluacion_cve'],
                                'rol_evaluado_cve' => $reglas_validas['rol_evaluado_cve'],
                                'encuesta_cve' => $reglas_validas['encuesta_cve'],
                                'eva_tipo' => $reglas_validas['eva_tipo'],
                                'is_bono' => $reglas_validas['is_bono'],
                            );
                        }
                    }
                    if (isset($reglasgral)) {
                        foreach ($reglasgral as $keyrg => $valuerg) {

                            //pr($valuerg['eva_tipo']);

                            if ($valuerg['eva_tipo'] != 1) {
                                //por persona
                                //echo "por persona";
                                $datos_user_aeva[] = $this->enc_mod->listado_eval(array('role_evaluado' => $valuerg['rol_evaluado_cve'],
                                    'cur_id' => $idcurso, 'encuesta_cve' => $valuerg['encuesta_cve'],
                                    'evaluador_user_cve' => $idusuario, 'role_evaluador' => $value, 'is_bono' => $valuerg['is_bono']
                                        )
                                );
                            } else {
                                //por grupo
                                //echo "por grupo";
                                $datos_usuario = $this->enc_mod->get_datos_usuarios(array('user_id' => $idusuario, 'cur_id' => $idcurso, 'rol_evaluado_cve' => $valuerg['rol_evaluado_cve']));
                                //pr($datos_usuario);
                                if (isset($datos_usuario) || isset($datos_curso) || !empty($datos_usuario) || !empty($datos_curso)) {
                                    foreach ($datos_usuario as $key => $value) {

                                        //role evaluador
                                        $role_evaluador = $value['cve_rol'];
                                        //pr($role_evaluador);
                                        //grupo del evaluador
                                        $gpo_evaluador = $value['cve_grupo']; # code...




                                        $datos_user_aeva[] = $this->enc_mod->listado_eval(array('gpo_evaluador' => $gpo_evaluador, 'role_evaluado' => $valuerg['rol_evaluado_cve'],
                                            'cur_id' => $idcurso, 'encuesta_cve' => $valuerg['encuesta_cve'],
                                            'evaluador_user_cve' => $idusuario,
                                            'role_evaluador' => $role_evaluador)
                                        );

                                        //$datos_user_aeva[]['is_bono']=$valuerg['is_bono'];
                                    }
                                    //pr($datos_user_aeva);
                                }




                                /* $datos_user_aeva[]=$this->enc_mod->listado_eval(array('gpo_evaluador' => $gpo_evaluador,'role_evaluado' => $reglasgral['rol_evaluado_cve'],
                                  'cur_id' => $idcurso,'encuesta_cve' => $reglasgral['encuesta_cve'],
                                  'evaluador_user_cve' => $idusuario,
                                  'role_evaluador' => $value)
                                  ); */
                            }


                            # code...
                        }
                        $datos['datos_user_aeva'] = $datos_user_aeva;
                    }


//                    pr($datos_user_aeva);
                    # code...
                }

                //pr($datos_user_aeva);
                $datos['datos_curso'] = $datos_curso;
                //$datos['datos_usuario']=$datos_usuario;
                //$datos['datos_user_aeva'];
                //pr( $datos);
                $datos['iduevaluador'] = $idusuario;






                # code...
            } else {//Muestra mensaje que no hay permisos
                $datos['coment_general'] = 'El usuario actual no cuenta con permisos para ver el curso actual. '
                        . '<br><br>Por favor verifique la ruta o inicie sesión nuevamente ';
            }
            $main_contet = $this->load->view('encuesta/lista_usuarios', $datos, true);
            $this->template->setMainContent($main_contet);
            $this->template->getTemplate();
        }
    }

}
