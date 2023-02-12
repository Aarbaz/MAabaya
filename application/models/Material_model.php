<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model {

    public function add_material($data)
	{
		$this->db->insert('materials', $data);
		return $this->db->insert_id();
	}
	
	public function get_all_material()
    {
         return $this->db->select('materials.*,vendors.vendor_name,vendors.debit_balance')
         ->from('materials')->order_by('id ASC, vendors.vendor_name ASC')
         ->join('vendors', 'materials.vendor = vendors.vendor_id')->get();
    }
    
    public function get_all_vendors()
    {
        return $this->db->select('vendor_id,vendor_name, debit_balance')->get('vendors');
    }
    public function get_material_byID($id)
    {
        $this->db->select('materials.*,vendors.vendor_name,vendors.debit_balance')->from('materials')
        ->join('vendors', 'materials.vendor = vendors.vendor_id')
        ->where('materials.id', $id);
        return $this->db->get()->row();     
    }

     public function update_material($data, $id)
    { 
        $this->db->where('id', $id);
		$this->db->update('materials', $data);
		return $this->db->affected_rows();

    }
 
    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('materials');
        return $this->db->affected_rows();
    }
    //vendors  ledger in date range
    public function vendor_ledger_byDate($cust_id, $frm_mnth, $frm_yr,$to_mnth=null,$to_yr=null)
    {
        $frm_date = $frm_yr.'-'.$frm_mnth.'-1 00:00:00';
        $to_date =  $to_yr.'-'.$to_mnth.'-31 23:59:59';
        $to_date1 = $frm_yr.'-'.$frm_mnth.'-31 23:59:59';

        if($to_mnth && $to_yr)
        {
            $this->db->select('materials.*,vendors.vendor_name,vendors.address,vendors.area,vendors.city,materials.last_amount')
                ->from('materials')
                ->where('materials.vendor', $cust_id)
                ->where('materials.buy_date >=', $frm_date)
                ->where('materials.buy_date <=', $to_date)
                ->order_by('materials.id ASC')
                ->join('vendors', 'materials.vendor = vendors.vendor_id');
                return $this->db->get();
        }
        else
        {
            $this->db->select('materials.*,vendors.vendor_name,vendors.address,vendors.area,vendors.city,materials.last_amount')
            ->from('materials')
            ->where('materials.vendor', $cust_id)
            ->where('materials.buy_date >=', $frm_date)
            ->where('materials.buy_date <=', $to_date1)
            ->order_by('materials.id ASC')
            ->join('vendors', 'materials.vendor = vendors.vendor_id');
            return $this->db->get();   
        }
    }


}
