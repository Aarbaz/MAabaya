<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function get_all_customer()
    {
        return $this->db->count_all('customers');
    }
    public function get_pur_customer()
    {
    $role = '0';
    $this->db->where("role",$role);
    $query = $this->db->get('customers');
    return $query->num_rows();
    }
    public function get_mak_customer()
    {
    $role = '1';
    $this->db->where("role",$role);
    $query = $this->db->get('customers');
    return $query->num_rows();
    }

    public function get_sel_customer()
    {
    $role = '2';
    $this->db->where("role",$role);
    $query = $this->db->get('customers');
    return $query->num_rows();
  }
    public function get_all_newmaterial()
    {
        return $this->db->count_all('material');
    }


    // public function get_sum_count()
    // {
    //     $this->db->select('customer_id, bakery_name, last_amount');
    //     $this->db->select_sum('total');
    //     $this->db->select_sum('paid');
    //     $this->db->select_sum('balance');
    //     $this->db->group_by('customer_id');
    //     return $this->db->from('ledger_balance')->join('customers', 'customers.id = ledger_balance.customer_id')->get();
    // }


}
