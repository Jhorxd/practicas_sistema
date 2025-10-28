<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizacion_model extends CI_Model {

    public function listar() {
        $this->db->select('c.*, p.nombre_proyecto');
        $this->db->from('cotizaciones c');
        $this->db->join('proyectos p', 'p.id_proyecto = c.id_proyecto');
        $this->db->order_by('c.fecha_cotizacion', 'DESC');
        return $this->db->get()->result();
    }

    public function insertar($data) {
        $this->db->insert('cotizaciones', $data);
        return $this->db->insert_id();
    }

    public function insertar_detalle($data) {
        $this->db->insert('detalle_cotizacion', $data);
    }

    public function obtener_detalles($id_cotizacion) {
        $this->db->where('id_cotizacion', $id_cotizacion);
        return $this->db->get('detalle_cotizacion')->result();
    }

public function obtener_detalles_por_proyecto($id_proyecto) {
    $this->db->select('
        r.id_requerimiento,
        dc.precio_unitario
    ');
    $this->db->from('requerimientos r');
    $this->db->join('cotizaciones c', 'c.id_proyecto = r.id_proyecto', 'left');
    $this->db->join('detalle_cotizacion dc', 'dc.id_cotizacion = c.id_cotizacion AND dc.id_requerimiento = r.id_requerimiento', 'left');
    $this->db->where('r.id_proyecto', $id_proyecto);
    $this->db->order_by('r.id_requerimiento', 'ASC');
    return $this->db->get()->result();
}




    public function obtener_requerimientos($id_proyecto) {
    return $this->db->where('id_proyecto', $id_proyecto)
                    ->get('requerimientos')
                    ->result();
}

public function obtener_por_proyecto($id_proyecto) {
    return $this->db->where('id_proyecto', $id_proyecto)
                    ->get('cotizaciones')
                    ->row();
}

public function actualizar($id_cotizacion, $data) {
    return $this->db->where('id_cotizacion', $id_cotizacion)
                    ->update('cotizaciones', $data);
}

public function eliminar_detalles($id_cotizacion) {
    return $this->db->where('id_cotizacion', $id_cotizacion)
                    ->delete('detalle_cotizacion');
}


}
