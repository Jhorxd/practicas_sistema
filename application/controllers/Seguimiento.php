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

    $this->session->set_flashdata('swal', [
    'icon' => $upload_success ? 'success' : 'error',
    'title' => $upload_success ? 'Archivo subido' : 'Error al subir archivo',
    'text' => $upload_success ? 'Archivo subido correctamente' : $this->upload->display_errors()
]);

    $id = $this->input->post('id_requerimiento');

    log_message('debug', "Iniciando subida de archivo para requerimiento ID: $id");

    // Revisar que el input tenga archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] == 4) {
        log_message('error', 'No se ha seleccionado ningún archivo.');
        $this->session->set_flashdata('error', 'No se ha seleccionado ningún archivo.');
        redirect('seguimiento/detalle/'.$id);
    }

    log_message('debug', 'Archivo detectado en $_FILES: ' . print_r($_FILES['archivo'], true));

    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'pdf|doc|docx|gif|jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['encrypt_name']  = TRUE; // Renombra el archivo para evitar conflictos

    // Crear carpeta si no existe
    if (!is_dir($config['upload_path'])) {
        if (mkdir($config['upload_path'], 0777, true)) {
            log_message('debug', 'Carpeta uploads creada correctamente.');
        } else {
            log_message('error', 'No se pudo crear la carpeta uploads.');
            $this->session->set_flashdata('error', 'No se pudo crear la carpeta uploads.');
            redirect('seguimiento/detalle/'.$id);
        }
    } else {
        log_message('debug', 'Carpeta uploads ya existe.');
    }

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('archivo')) {
        $fileData = $this->upload->data();
        log_message('debug', 'Archivo subido correctamente: ' . $fileData['file_name']);

        // Guardar en la base de datos
        $this->Adjuntos_model->insertar([
            'id_requerimiento' => $id,
            'nombre_archivo'   => $fileData['file_name'],
            'ruta'             => 'uploads/'.$fileData['file_name']
        ]);

        $this->session->set_flashdata('success', 'Archivo subido correctamente.');
    } else {
        $error = $this->upload->display_errors();
        log_message('error', 'Error al subir archivo: ' . $error);
        $this->session->set_flashdata('error', $error);
    }

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
