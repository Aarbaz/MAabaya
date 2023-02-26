<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Pices_model extends CI_Model {

    public function get_products_in_pcs()
    {
        return $this->db->get("product_pices");
    }
    public function get_all_material()
    {
        return $this->db->get("material");
    }
    public function get_all_master()
    {
        return $this->db->select('id, master_name')->get('material');
    }
    public function get_products_in_pcs_list()
    {
        
        $this->db->select('design_number');
    	$this->db->from('product_pices');
        $query = $this->db->get();
        $data= $query->result();
        $data = json_encode($data);

        // Decode the JSON string into an associative array
        $jsonArray = json_decode($data, true);

        $this->db->select('*');
        $this->db->order_by('sr_no','desc');
        $this->db->from('product_pices');
        $this->db->join('material', 'material.id = product_pices.master_id');
        return $this->db->get();
    }
    public function create_record($data)
    {
        return $this->db->insert('product_pices', $data);
    }

    /* public function getProduct()
    {
        $ids = "9,11,12";
        $id_array = explode(",", $ids);

        $this->db->select('id, product_name');
        $this->db->from('products');
        $this->db->where_in('id', $id_array);
        $query = $this->db->get();

        // Build an array of the retrieved values
        $values = array();
        foreach ($query->result() as $row) {
            $values[$row->id] = array(
                'product_name' => $row->product_name,
                // /'value' => $row->value
            );
        }
        print_r ($query->result());
    } */
    public function delete_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('product_pices');
        return $this->db->affected_rows();
    }
}