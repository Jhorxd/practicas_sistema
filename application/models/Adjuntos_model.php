<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjuntos_model extends CI_Model {

    public function listar_por_requerimiento($id) {
        $this->db->where('id_requerimiento', $id);
        return $this->db->get('adjuntos')->result();
    }

    public function insertar($data) {
        $this->db->insert('adjuntos', $data);
    }
}
