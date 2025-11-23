<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_model extends CI_Model {

    public function listar_por_requerimiento($id) {
        $this->db->select('h.*, u.nombre as usuario_nombre');
        $this->db->from('historial_cambios h');
        $this->db->join('usuarios u', 'h.usuario = u.id_usuario', 'left');
        $this->db->where('h.id_requerimiento', $id);
        $this->db->order_by('h.fecha', 'DESC');
        return $this->db->get()->result();
    }

    public function insertar($id_requerimiento, $accion, $usuario) {
        $this->db->insert('historial_cambios', [
            'id_requerimiento' => $id_requerimiento,
            'accion' => $accion,
            'usuario' => $usuario
        ]);
    }

    public function eliminar($id_historial) {
    $this->db->where('id_historial', $id_historial);
    $this->db->delete('historial_cambios'); // Asegúrate que tu tabla se llame 'historial'
}

    public function count_all() {
        return $this->db->count_all('historial_cambios');
    }
}
