<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Proyecto_model');
        $this->load->model('Requerimientos_model');
        $this->load->model('Cotizacion_model');
    }

    public function index() {
        $data['cotizaciones'] = $this->Cotizacion_model->listar();
        $data['proyectos'] = $this->Proyecto_model->listar_con_cotizacion();
        $this->load->view('cotizaciones', $data);
    }

    // Obtener requerimientos del proyecto (para AJAX)
public function obtener_requerimientos($id_proyecto)
{
    log_message('debug', '🟢 Obtener requerimientos y precios del proyecto: ' . $id_proyecto);

    // Obtener requerimientos del proyecto
    $requerimientos = $this->Requerimientos_model->listar_por_proyecto($id_proyecto);

    // Obtener detalles de cotización si existen
    $detalles = $this->Cotizacion_model->obtener_detalles_por_proyecto($id_proyecto);

    // Indexar precios por id_requerimiento
    $precios = [];
    foreach ($detalles as $d) {
        $precios[$d->id_requerimiento] = $d->precio_unitario ?? 0;
    }

    // Combinar información: asignar precio_unitario a cada requerimiento
    foreach ($requerimientos as &$r) {
        $r->precio_unitario = $precios[$r->id_requerimiento] ?? 0;
    }

    echo json_encode($requerimientos);
}



    // Guardar cotización
public function guardar() {
    log_message('debug', '🚀 Iniciando método guardar() en Cotizaciones');

    $id_proyecto = $this->input->post('id_proyecto');
    $moneda = $this->input->post('moneda');
    $observaciones = $this->input->post('observaciones');
    $detalles = $this->input->post('detalles'); // array con descripcion, precio_unitario, id_requerimiento

    log_message('debug', '📥 Datos recibidos del POST: ' . json_encode($_POST));

    if (!is_array($detalles)) {
        log_message('error', '❌ Los detalles no son un array válido');
        return;
    }

    // Calcular total solo con precio_unitario
    $total = 0;
    foreach ($detalles as $d) {
        $total += $d['precio_unitario'];
    }
    log_message('debug', '💰 Total calculado: ' . $total);

    // Verificar si ya existe una cotización para este proyecto
    $cotizacion_existente = $this->Cotizacion_model->obtener_por_proyecto($id_proyecto);

    if ($cotizacion_existente) {
        // Actualizar cotización existente
        $this->Cotizacion_model->actualizar($cotizacion_existente->id_cotizacion, [
            'monto_total' => $total,
            'moneda' => $moneda,
            'observaciones' => $observaciones
        ]);

        // Eliminar detalles anteriores
        $this->Cotizacion_model->eliminar_detalles($cotizacion_existente->id_cotizacion);

        // Insertar nuevos detalles
        foreach ($detalles as $d) {
            $this->Cotizacion_model->insertar_detalle([
                'id_cotizacion'    => $cotizacion_existente->id_cotizacion,
                'id_requerimiento' => $d['id_requerimiento'],
                'descripcion'      => $d['descripcion'],
                'precio_unitario'  => $d['precio_unitario']
            ]);
        }

        log_message('debug', '🛠️ Cotización actualizada con ID: ' . $cotizacion_existente->id_cotizacion);
    } else {
        // Insertar nueva cotización
        $id_cotizacion = $this->Cotizacion_model->insertar([
            'id_proyecto' => $id_proyecto,
            'monto_total' => $total,
            'moneda' => $moneda,
            'observaciones' => $observaciones
        ]);

        // Insertar detalles
        foreach ($detalles as $d) {
            $this->Cotizacion_model->insertar_detalle([
                'id_cotizacion'    => $id_cotizacion,
                'id_requerimiento' => $d['id_requerimiento'],
                'descripcion'      => $d['descripcion'],
                'precio_unitario'  => $d['precio_unitario']
            ]);
        }

        log_message('debug', '✅ Nueva cotización creada con ID: ' . $id_cotizacion);
    }

    log_message('debug', '🏁 Proceso de guardar cotización finalizado');
    redirect('cotizaciones');
}




    
}
