<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Proyecto_model');
        $this->load->model('Usuario_model'); // Si quieres mostrar usuarios/clientes
    }

    // Listar proyectos
    public function index() {
        $data['proyectos'] = $this->Proyecto_model->listar();
        $data['usuarios'] = $this->Usuario_model->listar(); // Para asignar proyectos a usuarios
        $this->load->view('proyectos', $data);
    }

    // Guardar nuevo proyecto
    public function guardar() {
        $datos = array(
            'id_usuario' => $this->input->post('id_usuario'),
            'nombre_proyecto' => $this->input->post('nombre_proyecto'),
            'descripcion' => $this->input->post('descripcion'),
            'estado' => $this->input->post('estado')
        );

        $this->Proyecto_model->insertar($datos);
        redirect('proyectos');
    }

    // Actualizar estado del proyecto
    public function actualizar_estado() {
        $id = $this->input->post('id');
        $estado = $this->input->post('estado');
        $this->Proyecto_model->actualizar_estado($id, $estado);
        echo json_encode(['status'=>'ok']);
    }

    // Eliminar proyecto
    public function eliminar($id) {
        $this->Proyecto_model->eliminar($id);
        redirect('proyectos');
    }
    
}
