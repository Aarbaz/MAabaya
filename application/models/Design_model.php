<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Design_model extends CI_Model {

    public function get_all_design()
    {
        return $this->db->get('designs');
    }
    public function create_record($data)
    {
        return $this->db->insert('designs', $data);
    }

    public function get_design_byID($id)
    {
        $this->db->select('id,design_num');
    	$this->db->from('designs');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $data= $query->row();
        $data = json_encode($data);
        print_r($data);
    }


    public function get_design($id)
    {
      $this->db->from('designs');
        $this->db->where('id',$id);
        return $this->db->get()->row();
        //return $query->row();
    }

    public function update_record($id,$data)
    {
        $this->db->where('id', $id);
		$this->db->update('designs', $data);
		return $this->db->affected_rows();
    }
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('designs');
        return $this->db->affected_rows();
    }
}
