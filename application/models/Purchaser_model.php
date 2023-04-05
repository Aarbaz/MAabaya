<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaser_model extends CI_Model
{
    public function add_purchaser_qty($dataStk)
    {
        $this->db->insert("purchaser_stock", $dataStk);
        return $this->db->insert_id();
    }

    public function add_purchaser_stk($dataStk)
    {
        $this->db->insert("stock", $dataStk);
        return $this->db->insert_id();
    }
    public function update_purchaser_qty($data, $id, $mid)
    {
        $this->db->where("purchaser_id", $id);
        $this->db->where("materials_id", $mid);
        $this->db->update("purchaser_stock", $data);
        return $this->db->affected_rows();
    }

    public function update_pstock_qty($data,$mid)
    {
        // $this->db->where("purchaser_owner_id", $id);
        $this->db->where("materials_id", $mid);
        $this->db->update("purchaser_stock", $data);
        return $this->db->affected_rows();
    }

    public function update_Dstock($data,$mid)
    {
        // $this->db->where("purchaser_owner_id", $id);
        $this->db->where("product_id", $mid);
        $this->db->update("stock", $data);
        return $this->db->affected_rows();
    }


    public function add_purchaser($data)
    {
        $this->db->insert("purchaser", $data);
        return $this->db->insert_id();
    }

    public function get_all_purchasers()
    {
        return $this->db->get("purchaser");
    }

    public function get_purchaser_byID($id)
    {
        $this->db->select("id,total_amount,material_id,stock,price,purchaser_owner_id");
        $this->db->from("purchaser");
        $this->db->where("id", $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_purchaser($data, $id)
    {
        $this->db->where("id", $id);
        $this->db->update("purchaser", $data);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where("id", $id);
        $this->db->delete("purchaser");
        return $this->db->affected_rows();
    }

    public function delete_pstock($id)
    {
        $this->db->where("purchaser_id", $id);
        $this->db->delete("purchaser_stock");
        return $this->db->affected_rows();
    }

    public function getPurchaserDetailbyId($detail)
    {
        return $this->db
            ->select("material_name,stock,price")
            ->where("material_name", $detail)
            ->get("purchaser")
            ->result_array();
    }



    public function get_all_purchaser()
    {
        return $this->db->select("id,purchaser_no")->get("purchaser");
    }

    function get_pstock($id)
  {
    $this->db->select("id, quantity");
    $this->db->from("purchaser_stock");
    $this->db->where("materials_id", $id);
    $query = $this->db->get();
    return $query->row();
  }


  //get latest Purchaser no. insider
  public function get_last_purchaser_insider()
  {
      return $this->db->select('purchaser_no')->order_by('id','desc')->limit(1)->get('purchaser')->row();
  }

  public function get_purchaser_stock()
	{
		$this->db->select('*');
        $this->db->from('purchaser_stock');
        $this->db->join('purchaser', 'purchaser.id = purchaser_stock.purchaser_id','left');
        return $this->db->get();

	}


  public function create_history($json_data_array)
  {
      return $this->db->insert('history', $json_data_array);
  }


  public function create_material($data)
  {
      return $this->db->insert("material", $data);
  }

  public function update_material($id, $data)
  {
      $this->db->where("id", $id);
      $this->db->update("material", $data);
      return $this->db->affected_rows();
  }

  public function get_all_material()
  {
      return $this->db->select("id, material_name")->get("material");
  }

  public function getMaterialDetailbyId($id)
  {
      $this->db->where("id", $id);
      $query = $this->db->get("material");
      return $query->result();
  }

  public function get_material_byID_old($id)
  {
      $this->db->select('id,material_name');
    $this->db->from('material');
      $this->db->where('id',$id);
      $query = $this->db->get();
      $data= $query->row();
      $data = json_encode($data);
      print_r($data);
  }

  public function get_material_byID($id)
  {
    $this->db->from('material');
      $this->db->where('id',$id);
      return $this->db->get()->row();
      //return $query->row();
  }
    public function delete_by_mid($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('material');
        return $this->db->affected_rows();
    }

    public function update_record($id,$data)
    {
        $this->db->where('id', $id);
    $this->db->update('material', $data);
    return $this->db->affected_rows();
    }

}
