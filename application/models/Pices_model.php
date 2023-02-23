<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Pices_model extends CI_Model {

    public function get_products_in_pcs()
    {
        return $this->db->get("product_pices");
    }
    public function get_all_customer()
    {
        return $this->db->select('id, bakery_name, bakery_gst, bakery_area, bakery_city')->get('customers');
    }
    public function get_products_in_pcs_list()
    {
        /* return $this->db->select('sr_no, master_id,total, round_off_total, material_name,design_number,pices','bakery_address')
        ->from('customers')->join('product_pices', 'customers.id = product_pices.master_id')->get(); */
        return $this->db->select('sr_no,  master_id,total, round_off_total,bakery_name, material_name,design_number,pices,bakery_address')->order_by('sr_no','desc')
        ->from('product_pices')->join('customers', 'customers.id = product_pices.master_id')->get();
       /*  return $this->db->select('sr_no, invoice_no, round_off_total, invoice_date, customer_id,customer_address, product_name, invoice_date')->order_by('sr_no','desc')
        ->from('insider_bill')->get(); */
    }
    public function create_record($data)
    {
        return $this->db->insert('product_pices', $data);
    }
}