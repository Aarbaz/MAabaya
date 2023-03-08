<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function add_customer($data)
	{
		$this->db->insert('customers', $data);
		return $this->db->insert_id();
	}

	public function get_customers()
    {
        return $this->db->get('customers');
    }

    public function get_customer_byID($id)
    {
    	$this->db->from('customers');
        $this->db->where('id',$id);
        return $this->db->get()->row();
        //return $query->row();
    }

    public function get_powner()
    {
        $role = '0';
        $this->db->select('id,,name');
        $this->db->where('role',$role);
        return $query = $this->db->get('customers');
    }

    public function get_mowner()
    {
        $role = '1';
        $this->db->select('id,,name');
        $this->db->where('role',$role);
        return $query = $this->db->get('customers');
    }


     public function update_customer($data, $id)
    {
        $this->db->where('id', $id);
		$this->db->update('customers', $data);
		return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('customers');
        return $this->db->affected_rows();
    }

}
