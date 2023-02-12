<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function get_all_material()
    {
        return $this->db->count_all('materials');
    }

    public function get_all_products()
    {
        return $this->db->count_all('products');
    }

    public function get_all_customer()
    {
        return $this->db->count_all('customers');
    }

		public function get_all_purchaser()
		{
				return $this->db->count_all('purchasers');
		}

    public function get_all_vendors()
    {
        return $this->db->count_all('vendors');
    }

    public function get_sum_count()
    {
        $this->db->select('customer_id, bakery_name, last_amount');
        $this->db->select_sum('total');
        $this->db->select_sum('paid');
        $this->db->select_sum('balance');
        $this->db->group_by('customer_id');
        return $this->db->from('ledger_balance')->join('customers', 'customers.id = ledger_balance.customer_id')->get();
    }


}
