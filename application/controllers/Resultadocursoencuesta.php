<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona el login
 * @version     : 1.0.0
 * @autor       : Pablo José
 */
class Resultadocursoencuesta extends CI_Controller {

    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access        : public
     * * @modified  : 
     */
    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        //$this->load->model('Reporte_model', 'rep_mod'); // modelo de reporte
        $this->load->model('Curso_model', 'cur_mod'); // modelo de cursos
        $this->load->model('Encuestas_model', 'encur_mod'); // modelo de cursos
        $this->load->helper(array('form'));
    }

    public function index() {
        redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
    }

    public function info($curso = null) {

        $data['curso'] = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
        $data['roles'] = $this->cur_mod->listar_roles_curso(array('cur_id' => $curso));
        $data['grupos'] = $this->cur_mod->listar_grupos_curso(array('cur_id' => $curso));
        //pr($data); exit();

        $main_contet = $this->load->view('curso/info', $data, true);
        $this->template->setMainContent($main_contet);
        $this->template->getTemplate();
    }

    public function get_data_ajax($curso = null, $current_row = null) {
        if ($this->input->is_ajax_request()) { //Sólo se accede al método a través de una petición ajax
            if (isset($curso) && !empty($curso)) {

                if ($this->input->post()) { //Se verifica que se haya recibido información por método post
                    //aqui va la nueva conexion a la base de datos del buscador
                    //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda
                    $filtros = $this->input->post();

                    $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;
                    $filtros['curso'] = (isset($curso) && !empty($curso)) ? $curso : '';
                    $data = $filtros;
                    $data['current_row'] = $filtros['current_row'];
                    $data['per_page'] = $this->input->post('per_page');
                    $data['curso'] = $filtros['curso'];
                    //$data['encuestacve']='';
                    $error = "";
                    $data['error'] = $error;

                    //Checar el tipo de curso
                    $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));

                    $resultado = $this->encur_mod->listado_evaluados($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                    //pr($resultado);

                    $data['total_empleados'] = $resultado['total'];
                    $data['empleados'] = $resultado['data'];



                    $this->listado_resultado($data, array('form_recurso' => '#form_curso', 'elemento_resultado' => '#listado_resultado')); //Generar listado en caso de obtener datos
                }
            } else {
                redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
            }
        } else {

            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function listado_resultado($data, $form) {
        echo $data['error'] . '<br>';
        $data['encuestacve'] = 0;

        $pagination = $this->template->pagination_data_curso_encuesta($data); //Crear mensaje y links de paginación
        $links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>" . $pagination['total'] . "</div>
                <div class='col-sm-7 text-right'>" . $pagination['links'] . "</div>";
        echo $links . $this->load->view('curso/listado_evaluados', $data, TRUE) . $links . '
            <script>
            $("ul.pagination li a").click(function(event){
                data_ajax($(this).attr("href"), "' . $form['form_recurso'] . '", "' . $form['elemento_resultado'] . '");
                event.preventDefault();
            });
            </script>';
    }

    public function lista_anios($anio_inicio, $anio_fin) {
        $anios = array();
        for ($anio = $anio_inicio; $anio <= $anio_fin; $anio++) {
            $anios[] = array('anio_id' => $anio, 'anio_desc' => $anio);
        }
        return $anios;
    }

    public function info_curso($curso = null) {

        $data['curso'] = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
        $data['roles'] = $this->cur_mod->listar_roles_curso(array('cur_id' => $curso));
        $data['grupos'] = $this->cur_mod->listar_grupos_curso(array('cur_id' => $curso));
        //pr($data); exit();

        $main_contet = $this->load->view('curso/info_curso', $data, true);
        $this->template->setMainContent($main_contet);
        $this->template->getTemplate();
    }

    public function curso_encuesta_resultado($curso = null) {


        $anios = $this->lista_anios(2009, date('Y'));
        $rol = $this->config->item('rol_docente');
        $rol_evalua = $this->config->item('ENCUESTAS_ROL_EVALUA');
        $rol_evaluador = $this->config->item('ENCUESTAS_ROL_EVALUADOR');

        $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
        $data['datos_curso'] = $datos_curso;


        //$data['anios']=dropdown_options($anios, 'anio_id','anio_desc');
        
        $this->load->model('Reporte_model', 'rep_mod'); // modelo de cursos
        $data += $this->rep_mod->get_filtros_generales_reportes();
//        pr($datos);
//        $datos['order_columns'] += array('nombre' => 'Nombre', 'nrolevaluador' => 'Rol evaluador', 'nrolevaluado' => 'Rol evaluado', 'ngrupo' => 'Grupo');
        $data['curso'] = $curso;



        $main_contet = $this->load->view('curso/cur_enc_resultado', $data, true);
        $this->template->setMainContent($main_contet);
        $this->template->getTemplate();
    }

    public function export_data($curso = null) {
        //echo "entra";
        //echo "entra1"; //Sólo se accede al método a través de una petición ajax
        if (isset($curso) && !empty($curso)) {
            //echo "entra2";            
            if ($this->input->post()) {
                //echo "entra3";  //Se verifica que se haya recibido información por método post
                $filtros = $this->input->post();

                $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;
                $filtros['curso'] = (isset($curso) && !empty($curso)) ? $curso : '';
                $data = $filtros;
                $data['current_row'] = $filtros['current_row'];
                $data['per_page'] = $this->input->post('per_page');
                $data['curso'] = $filtros['curso'];
                //$data['encuestacve']='';
                $error = "";
                $data['error'] = $error;

                //Checar el tipo de curso
                $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
                //pr($datos_curso);
                //die();

                $resultado = $this->encur_mod->listado_evaluados($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                //pr($resultado);

                $data['total_empleados'] = $resultado['total'];
                $data['empleados'] = $resultado['data'];
                //pr($data['total_empleados']);

                if ($data['total_empleados'] > 0) {
                    //echo "emtra4";
                    //die();
                    //$this->listado_resultado($data_sesiones, array('form_recurso'=>'#form_buscador', 'elemento_resultado'=>'#listado_resultado')); //Generar listado en caso de obtener datos
                    $filename = "Export_" . date("d-m-Y_H-i-s") . "_" . $datos_curso['data'][0]['cur_id'] . ".xls";
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo $this->load->view('curso/listado_evaluados', $data, TRUE);
                } else {
                    echo data_not_exist('No han sido encontrados datos con los criterios seleccionados. <script> $("#btn_export").hide(); </script>'); //Mostrar mensaje de datos no existentes
                }
            }
        }
    }

    public function curso_encuesta_resultado_detalle($curso = null) {


        $anios = $this->lista_anios(2009, date('Y'));
        $rol = $this->config->item('rol_docente');
        $rol_evalua = $this->config->item('ENCUESTAS_ROL_EVALUA');
        $rol_evaluador = $this->config->item('ENCUESTAS_ROL_EVALUADOR');

        $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
        $data['datos_curso'] = $datos_curso;


        //$datos['order_columns'] = array('nombre'=>'Nombre','nrolevaluador'=>'Rol evaluador','nrolevaluado' => 'Rol evaluado', 'ngrupo' => 'Grupo');
        $data['curso'] = $curso;



        $main_contet = $this->load->view('curso/cur_enc_resultado_detalle', $data, true);
        $this->template->setMainContent($main_contet);
        $this->template->getTemplate();
    }

    public function get_datos($curso = null, $current_row = null) {
        if ($this->input->is_ajax_request()) { //Sólo se accede al método a través de una petición ajax
            if (isset($curso) && !empty($curso)) {

                if ($this->input->post()) { //Se verifica que se haya recibido información por método post
                    //aqui va la nueva conexion a la base de datos del buscador
                    //Se guarda lo que se busco asi como la matricula de quien realizo la busqueda
                    $filtros = $this->input->post();

                    $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;
                    $filtros['curso'] = (isset($curso) && !empty($curso)) ? $curso : '';
                    $data = $filtros;
                    $data['current_row'] = $filtros['current_row'];
                    $data['per_page'] = $this->input->post('per_page');
                    $data['curso'] = $filtros['curso'];
                    //$data['encuestacve']='';
                    $error = "";
                    $data['error'] = $error;

                    //Checar el tipo de curso
                    $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));

                    $resultado = $this->encur_mod->listado_evaluados_detalle($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                    //pr($resultado);

                    $data['total_empleados'] = $resultado['total'];
                    $data['empleados'] = $resultado['data'];



                    $this->listado_resultado_detalle($data, array('form_recurso' => '#form_curso', 'elemento_resultado' => '#listado_resultado')); //Generar listado en caso de obtener datos
                }
            } else {
                redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
            }
        } else {

            redirect(site_url()); //Redirigir al inicio del sistema si se desea acceder al método mediante una petición normal, no ajax
        }
    }

    private function listado_resultado_detalle($data, $form) {
        //echo $data['error'].'<br>';
        $data['encuestacve'] = 0;

        $pagination = $this->template->pagination_data_curso_encuesta_detalle($data); //Crear mensaje y links de paginación
        $links = "<div class='col-sm-5 dataTables_info' style='line-height: 50px;'>" . $pagination['total'] . "</div>
                <div class='col-sm-7 text-right'>" . $pagination['links'] . "</div>";
        echo $links . $this->load->view('curso/listado_evaluados_detalle', $data, TRUE) . $links . '
            <script>
            $("ul.pagination li a").click(function(event){
                data_ajax($(this).attr("href"), "' . $form['form_recurso'] . '", "' . $form['elemento_resultado'] . '");
                event.preventDefault();
            });
            </script>';
    }

    public function export_data_detalle($curso = null) {
        //echo "entra";
        //echo "entra1"; //Sólo se accede al método a través de una petición ajax
        if (isset($curso) && !empty($curso)) {
            //echo "entra2";            
            if ($this->input->post()) {
                //echo "entra3";  //Se verifica que se haya recibido información por método post
                $filtros = $this->input->post();

                $filtros['current_row'] = (isset($current_row) && !empty($current_row)) ? $current_row : 0;
                $filtros['curso'] = (isset($curso) && !empty($curso)) ? $curso : '';
                $data = $filtros;
                $data['current_row'] = $filtros['current_row'];
                $data['per_page'] = $this->input->post('per_page');
                $data['curso'] = $filtros['curso'];
                //$data['encuestacve']='';
                $error = "";
                $data['error'] = $error;

                //Checar el tipo de curso
                $datos_curso = $this->cur_mod->listado_cursos(array('cur_id' => $curso));
                //pr($datos_curso);
                //die();

                $resultado = $this->encur_mod->listado_evaluados_detalle($filtros); //Datos del formulario se envían para generar la consulta segun los filtros
                //pr($resultado);

                $data['total_empleados'] = $resultado['total'];
                $data['empleados'] = $resultado['data'];
                //pr($data['total_empleados']);

                if ($data['total_empleados'] > 0) {
                    //echo "emtra4";
                    //die();
                    //$this->listado_resultado($data_sesiones, array('form_recurso'=>'#form_buscador', 'elemento_resultado'=>'#listado_resultado')); //Generar listado en caso de obtener datos
                    $filename = "Export_" . date("d-m-Y_H-i-s") . "_" . $datos_curso['data'][0]['cur_id'] . ".xls";
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=$filename");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo $this->load->view('curso/listado_evaluados_detalle', $data, TRUE);
                } else {
                    echo data_not_exist('No han sido encontrados datos con los criterios seleccionados. <script> $("#btn_export").hide(); </script>'); //Mostrar mensaje de datos no existentes
                }
            }
        }
    }

}
