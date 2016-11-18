<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase que gestiona el login
 * @version 	: 1.0.0
 * @autor 		: Jesús Díaz P. & Pablo José
 */
class Login extends CI_Controller {

    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access 		: public
     * * @modified 	: 
     */
    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('form', 'captcha'));
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        //$this->load->library('Bitacora');
        $this->load->model('Encuestas_model', 'enc_mod');
        $this->load->model('Login_model', 'lm');
        $this->config->load('general');
    }

    public function logeo($id=NULL) {

         $url_sied = $this->config->item('url_sied');
        if(isset($id))
        {
       
            $usuario = $this->enc_mod->usuario_existe($id);
            if (isset($usuario)) 
            {
                $token=md5(uniqid(rand(),TRUE));
                $usuario_data = array(
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre.' '.$usuario->apellidos,
                    'logueado' => TRUE,
                    'token' => $token
               
                );
                //pr($usuario_data);
                $this->session->set_userdata($usuario_data);
                redirect('encuestas/index');
            }else{
                redirect($url_sied);
                //redirect('http://11.32.41.13/kio/sied');
         
            }

        }else{
            //echo 'entra';
            redirect($url_sied);
            //redirect('http://11.32.41.13/kio/sied');
        }

    }

    private function checkbrute($matricula) {
        $ahora = time(); ///Tiempo actual

        $lapso_intentos = $this->config->item('tiempo_fuerza_bruta');
        $intentos_default_fuerza_bruta = $this->config->item('intentos_fuerza_bruta');
        $numero_intentos_usuario = $this->lm->set_checkbrute_usuario($matricula, $lapso_intentos);
//        $numero_intentos_usuario = 1;
        if ($numero_intentos_usuario > $intentos_default_fuerza_bruta) {
            return true;
        } else {
            return false;
        }
    }

    private function matricula_formato($matricula) {
        return hash('sha512', $matricula);
    }

    private function contrasenia_formato($matricula, $contrasenia) {
        return hash('sha512', $contrasenia . $matricula);
    }

    private function formulario($error = "") {
        $data['error'] = $error;
        $data['captcha'] = create_captcha($this->captcha_config());
        $this->session->set_userdata('captchaWord', $data['captcha']['word']);
        //echo $data['token'] = $this->session->userdata('token'); //Se envia token al formulario
        $form_login = $this->load->view('login/formulario', $data, TRUE);
        return $form_login;
    }

    private function token() {
        $token = md5(uniqid(rand(), TRUE));
        $this->session->set_userdata('token', $token);
        return;
    }

    public function cerrar_session() {
        $url_sied_logout = $this->config->item('url_sied_logout');
        //session_destroy();
        $this->session->sess_destroy();
        //redirect('login');
        redirect($url_sied_logout);
        exit();
    }

    public function regresar_sied() {
        $url_sied = $this->config->item('url_sied');
        redirect($url_sied);
        //exit();
    }

}
