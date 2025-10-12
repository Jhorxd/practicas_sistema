<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function guardar_ticket($data) {
        $this->db->insert('tickets', $data);
        return $this->db->insert_id();
    }

    public function guardar_evidencia($data) {
    return $this->db->insert('evidencias', $data);
}

        // Obtener todos los tickets con nombre del usuario
    public function obtener_todos_los_tickets() {
        $this->db->select('tickets.*, usuarios.nombre AS nombre_usuario');
        $this->db->from('tickets');
        $this->db->join('usuarios', 'usuarios.id_usuario = tickets.id_usuario');
        $this->db->order_by('tickets.creado_en', 'DESC');
        return $this->db->get()->result();
    }

   public function obtener_tickets_filtrados($prioridad = null, $clienteId = null) {
    $this->db->select('tickets.*, usuarios.nombre AS nombre_usuario');
    $this->db->from('tickets');
    $this->db->join('usuarios', 'usuarios.id_usuario = tickets.id_usuario');


    if ($prioridad && in_array($prioridad, ['alta', 'media', 'baja'])) {
        $this->db->where('tickets.prioridad', $prioridad);
    }

    if ($clienteId && is_numeric($clienteId)) {
        $this->db->where('tickets.id_usuario', $clienteId);
    }

    $this->db->order_by('tickets.creado_en', 'DESC');
    return $this->db->get()->result();
}


    public function obtener_tickets_por_usuario($id_usuario) {
    $this->db->where('id_usuario', $id_usuario);
    return $this->db->get('tickets')->result();
}
public function eliminar_ticket($id_ticket) {
    // Elimina primero las evidencias asociadas
    $this->db->where('id_ticket', $id_ticket);
    $this->db->delete('evidencias');

    // Luego elimina el ticket
    $this->db->where('id_ticket', $id_ticket);
    return $this->db->delete('tickets');
}

public function get_evidencias_por_ticket($id_ticket)
{
    return $this->db->where('id_ticket', $id_ticket)->get('evidencias')->result();
}

public function get_ticket($id_ticket)
{
    return $this->db
        ->where('id_ticket', $id_ticket)
        ->get('tickets')
        ->row();  // Devuelve un solo objeto con los datos del ticket
}

public function actualizar_prioridad($id_ticket, $prioridad) {
    $this->db->where('id_ticket', $id_ticket);
    $this->db->update('tickets', ['prioridad' => $prioridad]);
}

}
