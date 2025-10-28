<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requerimientos_model extends CI_Model {

    // Listar todos los requerimientos con info del proyecto
    public function listar() {
        $this->db->select('r.*, p.nombre_proyecto');
        $this->db->from('requerimientos r');
        $this->db->join('proyectos p', 'p.id_proyecto = r.id_proyecto', 'left');
        $this->db->order_by('r.fecha_registro', 'DESC');
        return $this->db->get()->result();
    }

    public function listar_por_proyecto($id_proyecto) {
    $this->db->select('r.*, p.nombre_proyecto');
    $this->db->from('requerimientos r');
    $this->db->join('proyectos p', 'p.id_proyecto = r.id_proyecto', 'left');
    $this->db->where('r.id_proyecto', $id_proyecto);
    $this->db->order_by('r.fecha_registro', 'DESC');
    return $this->db->get()->result();
}
    

    // Insertar un requerimiento
    public function insertar($data) {
        $insert = array(
            'id_proyecto' => $data['id_proyecto'],
            'titulo'      => $data['titulo'],
            'descripcion' => isset($data['descripcion']) ? $data['descripcion'] : null,
            'prioridad'   => isset($data['prioridad']) ? $data['prioridad'] : 'media',
            'estado'      => 'pendiente'
        );
        $this->db->insert('requerimientos', $insert);
        return $this->db->insert_id();
    }

    // Actualizar estado de requerimiento
    public function actualizar_estado($id, $estado) {
        $this->db->where('id_requerimiento', $id);
        $this->db->update('requerimientos', ['estado' => $estado]);
    }


    // Eliminar requerimiento
    public function eliminar($id) {
        $this->db->where('id_requerimiento', $id);
        $this->db->delete('requerimientos');
    }

    // Obtener requerimiento por ID
    public function obtener_por_id($id) {
        $this->db->where('id_requerimiento', $id);
        return $this->db->get('requerimientos')->row();
    }
}
