<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaser_model extends CI_Model {

    public function add_purchaser($data)
	{
		$this->db->insert('purchasers', $data);
		return $this->db->insert_id();
	}

	public function get_purchasers()
    {
        return $this->db->get('purchasers');
    }

    public function get_purchaser_byID($id)
    {
    	$this->db->from('purchasers');
        $this->db->where('id',$id);
        return $this->db->get()->row();
        //return $query->row();
    }

     public function update_purchaser($data, $id)
    {
        $this->db->where('id', $id);
		$this->db->update('purchasers', $data);
		return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('purchasers');
        return $this->db->affected_rows();
    }

}
