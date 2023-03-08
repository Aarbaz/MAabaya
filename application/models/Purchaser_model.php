<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaser_model extends CI_Model
{
    public function add_purchaser_qty($dataStk)
    {
        $this->db->insert("purchaser_stock", $dataStk);
        return $this->db->insert_id();
    }

    public function update_purchaser_qty($data, $id, $mid)
    {
        $this->db->where("purchaser_id", $id);
        $this->db->where("material_id", $mid);
        $this->db->update("purchaser_stock", $data);
        return $this->db->affected_rows();
    }

    public function update_pstock_qty($data, $id,$mid)
    {
        $this->db->where("owner_id", $id);
        $this->db->where("material_id", $mid);
        $this->db->update("purchaser_stock", $data);
        return $this->db->affected_rows();
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
        $this->db->select("id,total_amount,material_id,stock,price,owner_id");
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

    public function getMaterialDetailbyId($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("material");
        return $query->result();
    }
    public function get_all_purchaser()
    {
        return $this->db->select("id")->get("purchaser");
    }

    function get_pstock($id)
  {
    $this->db->select("id, quantity");
    $this->db->from("purchaser_stock");
    $this->db->where("material_id", $id);
    $query = $this->db->get();
    return $query->row();
  }

}
