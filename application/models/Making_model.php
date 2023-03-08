<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Making_model extends CI_Model {

    public function add_material($data)
	{
		$this->db->insert('making', $data);
		return $this->db->insert_id();
	}

	public function get_all_material()
    {
        return $this->db->get("making");
    }

    public function get_material_byID($id)
    {   $this->db->select('id, master_name,material_name,stock');
    	$this->db->from('making');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }

     public function update_making($data, $id)
    {
        $this->db->where('id', $id);
		$this->db->update('making', $data);
		return $this->db->affected_rows();

    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('making');
        return $this->db->affected_rows();
    }

    public function getProductDetailbyId($detail)
    {
        return $this->db->select('product_name,stock,price')->where('product_name', $detail)->get('products')->result_array();
    }

    public function get_all_making()
    {
        return $this->db->select('id, master_name,material_name,stock')->get('making');

    }
}
