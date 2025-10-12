<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyecto_model extends CI_Model {

    // Listar proyectos, opcionalmente filtrados por usuario
    public function listar($id_usuario = null) {
        $this->db->select('p.*, u.nombre as nombre_usuario');
        $this->db->from('proyectos p');
        $this->db->join('usuarios u', 'u.id_usuario = p.id_usuario');
        if($id_usuario) {
            $this->db->where('p.id_usuario', $id_usuario);
        }
        $this->db->order_by('p.fecha_creacion', 'DESC');
        return $this->db->get()->result();
    }

    // Obtener un proyecto por ID
    public function obtener_por_id($id) {
        $this->db->select('p.*, u.nombre as nombre_usuario');
        $this->db->from('proyectos p');
        $this->db->join('usuarios u', 'u.id_usuario = p.id_usuario');
        $this->db->where('p.id_proyecto', $id);
        return $this->db->get()->row();
    }

    // Insertar nuevo proyecto
    public function insertar($data) {
        $this->db->insert('proyectos', $data);
        return $this->db->insert_id();
    }

    // Actualizar proyecto
    public function actualizar($id, $data) {
        $this->db->where('id_proyecto', $id);
        $this->db->update('proyectos', $data);
    }

    // Eliminar proyecto
    public function eliminar($id) {
        $this->db->where('id_proyecto', $id);
        $this->db->delete('proyectos');
    }

    public function actualizar_estado($id, $estado) {
    $this->db->where('id_proyecto', $id);
    $this->db->update('proyectos', ['estado' => $estado]);
}
}
