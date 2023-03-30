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
    public function get_products_in_pcs_list()
    {
        
        $this->db->select('design_number');
    	$this->db->from('product_pices');
        $query = $this->db->get();
        $data= $query->result();
        $data = json_encode($data);

        // Decode the JSON string into an associative array
        $jsonArray = json_decode($data, true);

        //$this->db->select('master_id,customers.name,invoice_no,sr_no');
        $this->db->select('*');
        $this->db->order_by('sr_no','desc');
       //$this->db->group_by('master_id'); 

       //$this->db->join('customers', 'customers.id = product_pices.master_id');
        $this->db->from('product_pices');
        $this->db->join('material', 'material.id = product_pices.master_id');
        $this->db->join('customers', 'customers.id = product_pices.master_id');
        return $this->db->get();
    }
    public function get_pices_byID($pices_id)
    {
        $this->db->select('*');
    	$this->db->from('product_pices');
        $this->db->where('sr_no',$pices_id);
        $query = $this->db->get();
        return $query->row();
    }
    public function create_record($data)
    {
        return $this->db->insert('product_pices', $data);
    }

    public function update_records($data, $pices_id)
    {
        $this->db->where('sr_no', $pices_id);
		$this->db->update('product_pices', $data);
		return $this->db->affected_rows();

    }

    public function get_last_invoice_pices()
    {
        return $this->db->select('invoice_no')->order_by('sr_no','desc')->limit(1)->get('product_pices')->row();    
    }
   /*  public function update_makerStock($customer_id,$material_values, $data)
    {
        foreach($material_values as $row)
        {
            $this->db->where('material_id', $row);
            $this->db->where('making_owner_id', $customer_id);
            $this->db->update('maker_stock', $data);
        }
        return $this->db->affected_rows();
        
    } */
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

    public function create_history($json_data_array)
    {
        return $this->db->insert('history', $json_data_array);
    }
    public function delete_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('product_pices');
        return $this->db->affected_rows();
    }
}