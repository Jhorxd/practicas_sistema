<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seguimiento extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Requerimientos_model');
        $this->load->model('Proyecto_model');
        $this->load->model('Historial_model');
        $this->load->model('Adjuntos_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // Lista todos los requerimientos
    public function index() {
        $data['requerimientos'] = $this->Requerimientos_model->listar();
        $this->load->view('seguimiento', $data);
    }

    // Detalle de un requerimiento con historial y adjuntos
    public function detalle($id) {
        $data['requerimiento'] = $this->Requerimientos_model->obtener_por_id($id);
        $data['historial'] = $this->Historial_model->listar_por_requerimiento($id);
        $data['adjuntos'] = $this->Adjuntos_model->listar_por_requerimiento($id);
        $this->load->view('detalle', $data);
    }

    // Actualizar estado y registrar historial
    public function actualizar_estado() {
        $this->session->set_flashdata('swal', [
    'icon' => 'success',
    'title' => 'Requerimiento actualizado',
    'text' => 'El estado y avance se actualizaron correctamente.'
]);

        $id = $this->input->post('id_requerimiento');
        $estado = $this->input->post('estado');
        $avance = $this->input->post('porcentaje_avance');
        $accion = $this->input->post('accion');

        // Actualizar estado y avance
        $this->Requerimientos_model->actualizar_avance($id, $estado, $avance);

        // Registrar historial
        $this->Historial_model->insertar($id, $accion, $this->session->userdata('id_usuario'));

        redirect('seguimiento/detalle/'.$id);
    }


    // Subir adjunto
public function subir_adjunto() {
    $id = $this->input->post('id_requerimiento');

    log_message('debug', "Iniciando subida de archivo para requerimiento ID: $id");

    // Revisar que el input tenga archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] == 4) {
        log_message('error', 'No se ha seleccionado ningún archivo.');
        $this->session->set_flashdata('swal', [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'No se ha seleccionado ningún archivo.'
        ]);
        redirect('seguimiento/detalle/'.$id);
    }

    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'pdf|doc|docx|gif|jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['encrypt_name']  = TRUE; 

    // Crear carpeta si no existe
    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0777, true);
    }

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('archivo')) {
        $fileData = $this->upload->data();
        log_message('debug', 'Archivo subido correctamente: ' . $fileData['file_name']);

        $this->Adjuntos_model->insertar([
            'id_requerimiento' => $id,
            'nombre_archivo'   => $fileData['file_name'],
            'ruta'             => 'uploads/'.$fileData['file_name']
        ]);

        $upload_success = true;
        $swal = [
            'icon' => 'success',
            'title' => 'Archivo subido',
            'text' => 'Archivo subido correctamente'
        ];
    } else {
        $upload_success = false;
        $swal = [
            'icon' => 'error',
            'title' => 'Error al subir archivo',
            'text' => $this->upload->display_errors()
        ];
        log_message('error', 'Error al subir archivo: ' . $swal['text']);
    }

    // Mostrar SweetAlert
    $this->session->set_flashdata('swal', $swal);

    redirect('seguimiento/detalle/'.$id);
}


public function eliminar_historial() {

    $this->session->set_flashdata('swal', [
    'icon' => 'success',
    'title' => 'Historial eliminado',
    'text' => 'El registro del historial fue eliminado correctamente.'
]);
    $id_historial = $this->input->post('id_historial');
    $id_requerimiento = $this->input->post('id_requerimiento');

    if($id_historial) {
        $this->Historial_model->eliminar($id_historial); // Asegúrate de tener este método en tu modelo
        $this->session->set_flashdata('success', 'Historial eliminado correctamente.');
    }

    redirect('seguimiento/detalle/'.$id_requerimiento);
}


}
