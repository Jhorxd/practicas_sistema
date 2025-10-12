<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Si no está logueado, redirige al login
        if (!$this->session->userdata('logueado')) {
            redirect('auth');
        }

        // Solo rol soporte puede entrar
        if ($this->session->userdata('rol') !== 'soporte') {
            redirect('auth/logout');
        }
    }

    public function index() {
        // Carga la vista del panel principal
        $this->load->view('dashboard');
    }
}
