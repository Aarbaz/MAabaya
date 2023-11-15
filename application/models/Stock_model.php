<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

    public function add_record($data)
	{
		$this->db->insert_batch('stock', $data);
		return $this->db->insert_id();
	}


 public function add_records($data)
	{
		$this->db->insert('stock', $data);
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
	function get_material_stock($id)
	{
		$this->db->select("*");
		$this->db->from("stock");
		$this->db->where('product_id', $id);
		$query = $this->db->get();
		$result = $query->row();
		if ($result) {                   // Check if a row was fetched (data found)
			return $result->stock_qty;   // Return the stock_qty column value
		} else {
			return 0;                    // Return 0 if no data was found
		}
	}

    public function update_stock($product_id, $quantity_change) {
        // Update the stock table with the new quantity
        $current_quantity = $this->get_allstock($product_id)->stock_qty;

        // Calculate the new quantity
        $new_quantity = $current_quantity - $quantity_change;
        $new_quantity_add = $current_quantity + $quantity_change;

        // Check if the new quantity is negative, and only update if it is
        if ($current_quantity < 0) {
            // Update the stock table with the new quantity
            $this->db->where('p_design_number', $product_id);
            $this->db->update('stock', ['stock_qty' => $new_quantity]);
        }else {
            $this->db->where('p_design_number', $product_id);
            $this->db->update('stock', ['stock_qty' => $new_quantity_add]);
        }
    }

    private function addStock($productName, $quantity) {
        $this->db
            ->where('p_design_number', $productName)
            ->set('stock_qty', 'stock_qty + ' . $quantity, false)
            ->update('stock');
    }

    private function removeStock($productName, $quantity) {
        $this->db
            ->where('p_design_number', $productName)
            ->set('stock_qty', 'GREATEST(stock_qty - ' . $quantity . ', 0)', false)
            ->update('stock');
    }
}
