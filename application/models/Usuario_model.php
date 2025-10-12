<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function verificar_usuario($correo, $contraseña) {
        $this->db->where('correo', $correo);
        $this->db->where('contraseña', $contraseña); // 🔐 Mejora esto con password_hash en producción
        $query = $this->db->get('usuarios');

        return $query->row(); // Devuelve objeto si encuentra
    }

    public function insertar($data) {
        return $this->db->insert('usuarios', $data);
    }
    public function obtener_por_id($id_usuario) {
    $this->db->where('id_usuario', $id_usuario);
    $query = $this->db->get('usuarios');
    if ($query->num_rows() > 0) {
        return $query->row();
    }
    return false;
}

public function obtener_clientes() {
    $this->db->select('id_usuario, nombre');
    $this->db->from('usuarios');
    $this->db->where('rol', 'cliente');
    $this->db->order_by('nombre', 'ASC');
    return $this->db->get()->result();
}

    // Listar todos los usuarios
    public function listar() {
        $this->db->select('*');
        $this->db->from('usuarios');
        $this->db->order_by('nombre', 'ASC');
        return $this->db->get()->result();
    }






}
