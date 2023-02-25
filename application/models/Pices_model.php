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
        $ids = "9,11";
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
        print_r($query->result());

        // Access the values using the ID as the key
        //echo $values[1]['name'];

        $this->db->select('sr_no,  master_id,material_id,total, round_off_total,design_number,bakery_name, material_name,design_number,pices,bakery_address');
        $this->db->order_by('sr_no','desc');
        $this->db->from('product_pices');
        $this->db->join('customers', 'customers.id = product_pices.master_id');
       // $this->db->join('designs','product_pices.design_number = designs.id');
       return $this->db->get();
       /*  return $this->db->select('sr_no, invoice_no, round_off_total, invoice_date, customer_id,customer_address, product_name, invoice_date')->order_by('sr_no','desc')
        ->from('insider_bill')->get(); */
    }
    public function create_record($data)
    {
        return $this->db->insert('product_pices', $data);
    }
}