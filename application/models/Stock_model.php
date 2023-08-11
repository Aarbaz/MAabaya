<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

    public function add_record($data)
	{
		$this->db->insert_batch('stock', $data);
		return $this->db->insert_id();
	}



public function update_record($data, $id)
{
   $this->db->where('id', $id);
$this->db->update('stock', $data);
return $this->db->affected_rows();

}

public function delete_by_id($id)
{
    $this->db->where('product_id', $id);
    $this->db->delete('stock');
    return $this->db->affected_rows();
}

	// public function get_all_stocks()
    // {
    //     return $this->db->get("stock");
    // }

	public function get_stock()
	{
		/* $query = 'SELECT * FROM `stock` join `designs` where stock.p_design_number = designs.id';
		$sql = $this->db->query($query);
		return $sql->result_array(); */
		$this->db->select('*');
        //$this->db->order_by('id','desc');
        $this->db->from('stock');
        $this->db->join('designs', 'designs.id = stock.p_design_number');
        return $this->db->get();
		/* foreach ($sql->result() as $row)
{
        // echo $row->stock_qty;
        // echo $row->product_name;	
		echo $data['stocks'] = $row->stock_qty;
} */
	}
	public function get_stock1()
	{
		$query = 'SELECT stock.stock_qty, products.product_name FROM `products`,`stock` where stock.product_id = products.id';
		$sql = $this->db->query($query);
		return $sql->result_array();

		foreach ($sql->result() as $row)
{
        // echo $row->stock_qty;
        // echo $row->product_name;
		echo $data['stocks'] = $row->stock_qty;
}
	}
	function get_allstock($id)
	{
		$this->db->select("*");
		$this->db->from("stock");
		$this->db->where('p_design_number', $id);
		$query = $this->db->get();
		return $query->row();
	}

}
