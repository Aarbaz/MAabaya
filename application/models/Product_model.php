<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function add_product($data)
	{
		$this->db->insert('products', $data);
		return $this->db->insert_id();
	}

	public function get_all_products()
    {
        return $this->db->get("products");
    }

    public function get_product_byID($id)
    {   $this->db->select('id,total_amount,product_name,stock,price,owner_name');
    	$this->db->from('products');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }

     public function update_product($data, $id)
    {
        $this->db->where('id', $id);
		$this->db->update('products', $data);
		return $this->db->affected_rows();

    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('products');
        return $this->db->affected_rows();
    }

    public function getProductDetailbyId($detail)
    {
        return $this->db->select('product_name,stock,price')->where('product_name', $detail)->get('products')->result_array();
    }

    public function get_all_purchaser()
    {
        return $this->db->select('id, bakery_name')->get('purchasers');

    }

}
