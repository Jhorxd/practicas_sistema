<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        $this->load->view('usuario_form');
    }

    public function guardar() {
        $data = [
            'nombre'     => $this->input->post('nombre'),
            'correo'     => $this->input->post('correo'),
            'contraseña' => $this->input->post('contrasena'),
            'rol'        => $this->input->post('rol'),
            'estado'     => $this->input->post('estado'),
            'creado_en'  => date('Y-m-d H:i:s'),
            'telefono' => $this->input->post('telefono')
        ];

        $this->Usuario_model->insertar($data);
        $this->session->set_flashdata('msg', 'Usuario registrado correctamente.');
        redirect('usuario');
    }

    

}
