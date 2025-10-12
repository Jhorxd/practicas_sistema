<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->library('session');
        $this->load->helper('url'); // Asegura que 'redirect' funcione correctamente
    }

    // Mostrar formulario de login
    public function index() {
        // Si ya está logueado, que entre directo al dashboard
        if ($this->session->userdata('logueado')) {
            redirect('dashboard');
        }

        $this->load->view('login');
    }

    // Procesar login
    public function login() {
        $correo = $this->input->post('correo');
        $contraseña = $this->input->post('contraseña');

        // Verificar si el usuario existe en la BD
        $usuario = $this->Usuario_model->verificar_usuario($correo, $contraseña);

        if ($usuario && $usuario->estado == 1) {
            // Guardar datos en sesión
            $this->session->set_userdata([
                'id_usuario' => $usuario->id_usuario,
                'nombre'     => $usuario->nombre,
                'rol'        => $usuario->rol,
                'logueado'   => TRUE
            ]);

            // En este proyecto solo existirá el rol "soporte"
            if ($usuario->rol === 'soporte') {
                redirect('dashboard');
            } else {
                // Si hay otro rol, lo desconectamos
                $this->session->set_flashdata('error', 'Acceso restringido.');
                redirect('auth/logout');
            }

        } else {
            // Si no existe el usuario o está inactivo
            $this->session->set_flashdata('error', 'Correo o contraseña incorrectos.');
            redirect('auth');
        }
    }

    // Cerrar sesión
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
