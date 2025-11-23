
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
    public function index() {
        $this->load->model('Requerimientos_model');
        $this->load->model('Historial_model');

        // Datos generales
        $data['total_requerimientos'] = $this->Requerimientos_model->count_all();
        $data['pendientes'] = $this->Requerimientos_model->count_by_estado('pendiente');
        $data['aprobados'] = $this->Requerimientos_model->count_by_estado('aprobado');
        $data['rechazados'] = $this->Requerimientos_model->count_by_estado('rechazado');
        $data['avance_promedio'] = $this->Requerimientos_model->promedio_avance();
        $data['total_acciones'] = $this->Historial_model->count_all();

        $this->load->view('reportes', $data);
    }
}
