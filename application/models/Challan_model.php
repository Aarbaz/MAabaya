<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Challan_model extends CI_Model {

    public function get_all_material()
    {
        return $this->db->get("materials");
    }

    public function get_all_products()
    {
        return $this->db->get("material");
    }

    public function get_all_customer()
    {
        return $this->db->select('id, name,address')
        ->where('role', 2)
        ->get('customers');
    }

	/* challan SQLs */

    // get  challan list
    public function get_challan_list()
    {
        return $this->db->select('sr_no,customer_id, challan_no, material, total, challan_bills.created_on, name,address, ')->order_by('sr_no','desc')
        ->from('challan_bills')->join('customers', 'customers.id = challan_bills.customer_id')->get();
    }

    //get latest challan no.
    public function get_last_challan()
    {
    	return $this->db->select('challan_no')->order_by('sr_no',"desc")->limit(1)->get('challan_bills')->row();
    }

   //add new challan
    public function create_challan($data)
    {
        return $this->db->insert('challan_bills', $data);
    }
    //add balance sheet
    public function create_balance($data)
    {
        return $this->db->insert('balance', $data);
    }

    //delete a challan
    public function delete_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('challan_bills');
        return $this->db->affected_rows();
    }

    //end challan queries

    /* Insider invoice */
    // get  Invoice list
    public function get_invoice_list()
    {
        return $this->db->select('sr_no, invoice_no, round_off_total, invoice_date, update_at, name,address,customer_id, customer_address,id, ')->order_by('sr_no','desc')
        ->from('insider_bill')->join('customers', 'customers.id = insider_bill.customer_id')->get();
       /*  return $this->db->select('sr_no, invoice_no, round_off_total, invoice_date, customer_id,customer_address, product_name, invoice_date')->order_by('sr_no','desc')
        ->from('insider_bill')->get(); */
    }

    //get latest INVOICE no. insider
    public function get_last_invoice_insider()
    {
        return $this->db->select('invoice_no')->order_by('sr_no','desc')->limit(1)->get('insider_bill')->row();
    }

    //add new INVOICE no. insider
    public function create_invoice_insider($data)
    {
        return $this->db->insert('insider_bill', $data);
    }
    //delete invoice
    public function delete_invoice_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('insider_bill');
        return $this->db->affected_rows();
    }

    public function get_sell_stock($sell_id)
    {
        $this->db->select('*');
        $this->db->where('sr_no', $sell_id);
        $this->db->from('insider_bill');
        return $this->db->get();
    }

    



}
