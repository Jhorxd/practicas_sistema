<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requerimientos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Requerimientos_model');
        $this->load->model('Proyecto_model');
    }

    // Listar requerimientos
    public function index() {
        $data['requerimientos'] = $this->Requerimientos_model->listar();
        $data['proyectos'] = $this->Proyecto_model->listar();
        $this->load->view('requerimientos', $data);
    }

    // Guardar nuevo requerimiento
    public function guardar() {
        $datos = array(
            'id_proyecto' => $this->input->post('id_proyecto'),
            'titulo' => $this->input->post('titulo'),
            'descripcion' => $this->input->post('descripcion'),
            'prioridad' => $this->input->post('prioridad')
        );

        $this->Requerimientos_model->insertar($datos);
        redirect('requerimientos');
    }

    // Actualizar estado (aprobar/rechazar)
    public function actualizar_estado() {
        $id = $this->input->post('id');
        $estado = $this->input->post('estado');
        $this->Requerimientos_model->actualizar_estado($id, $estado);
        echo json_encode(['status' => 'ok']);
    }

    // Eliminar requerimiento
    public function eliminar($id) {
        $this->Requerimientos_model->eliminar($id);
        redirect('requerimientos');
    }
}
