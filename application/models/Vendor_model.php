<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function add_vendor($data)
	{
		$this->db->insert('vendors', $data);
		return $this->db->insert_id();
	}
	
	public function get_all_vendors()
    {
        return $this->db->get("vendors");
    }

    public function get_vendor_byID($id)
    {
    	$this->db->from('vendors');
        $this->db->where('vendor_id',$id);
        return $this->db->get()->row();     
    }

     public function update_vendor($data, $id)
    { 
        $this->db->where('vendor_id', $id);
		$this->db->update('vendors', $data);
		return $this->db->affected_rows();

    }
 
    public function delete_by_id($id)
    {
        $this->db->where('vendor_id', $id);
        $this->db->delete('vendors');
        return $this->db->affected_rows();
    }

}
