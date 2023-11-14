<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends CI_Model {

    public function get_all_history()
    {
        // return $this->db->get('history');
        return $this->db->select('*')->get('history');
    }

    public function get_all_material()
    {
        return $this->db->get('materials');
    }


    public function get_all_products()
    {
        return $this->db->get('products');
    }

    public function get_all_customer()
    {
        //return $this->db->get('customers');
        // return $this->db->select('id, bakery_name, bakery_gst, bakery_area, bakery_city, last_amount')->get('customers');
        return $this->db->select('id, name')->get('customers');
    }

    public function update_customer($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('customers', $data);
        return $this->db->affected_rows();
    }

	/* challan SQLs */

    // get  challan list
    public function get_challan_list($customerName=null, $from_date=null, $to_date=null)
    {
        if($customerName && !$from_date && !$to_date)
        {
            $this->db->select('sr_no,customer_id, challan_no as bill_no, total, paid, balance, created_on as date_on');
            $this->db->where('customer_id', $customerName);
            $this->db->from('challan_bills');
            return $this->db->get();
        }
        elseif($customerName && $from_date && $to_date)
        {
            //$this->db->where("order_datetime BETWEEN '2018-10-01' AND '2018-10-3'","", FALSE);

            //$this->db->where('sell_date BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
            $this->db->select('sr_no,customer_id, challan_no as bill_no, total, paid, balance, created_on as date_on');
            $this->db->where('customer_id', $customerName);
            $this->db->where('created_on >=', $from_date);
            $this->db->where('created_on <=', $to_date);
            $this->db->from('challan_bills');
            return $this->db->get();
        }

        else
        {
            return $this->db->select('sr_no,customer_id, challan_no, material, total, challan_bills.created_on, bakery_name,bakery_address, bakery_city')->order_by('sr_no','desc')
            ->from('challan_bills')->join('customers', 'customers.id = challan_bills.customer_id')->get();
        }
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

    //delete a challan
    public function delete_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('challan_bills');
        return $this->db->affected_rows();
    }
    //update bulk rows
    public function update_challan($update_data)
    {
        return $this->db->update_batch('challan_bills', $update_data, 'sr_no');
    }

    //insert bulk rows in Balance_model
    public function insert_balance($insert_data)
    {
        return $this->db->insert_batch('balance', $insert_data);
    }
    //get data by bill_no from balance
    public function get_balance($billno)
    {
        return $this->db->where('bill_no', $billno)->order_by('id','ASC')->get('balance');
    }

    function get_billcust($id)
    {
      $this->db->select('*');
      $this->db->from("balance");
      $this->db->where("customer_id", $id);
      $query = $this->db->get();
      return $query->result();
    }

    function get_billinvoice($id)
    {
      $this->db->select('*');
      $this->db->from("balance");
      $this->db->where("bill_no", $id);
      $query = $this->db->get();
      return $query->result();
    }

    public function update_balance($data, $cid, $bid)
    {
        $this->db->where("customer_id", $cid);
        $this->db->where("bill_no", $bid);
        $this->db->update("balance", $data);
        return $this->db->affected_rows();
    }
    //end challan queries

    /* Insider invoice */
    // get  challan list
    public function get_invoice_list($customerName=null, $from_date=null, $to_date=null)
    {
        if($customerName && !$from_date && !$to_date)
        {
            $this->db->select('sr_no,customer_id, invoice_no as bill_no, round_off_total as total,paid, balance, invoice_date as date_on');
            $this->db->where('customer_id', $customerName)->from('insider_bill');
            return $this->db->get();
        }
        elseif($customerName && $from_date && $to_date)
        {
            $this->db->select('sr_no,customer_id, invoice_no as bill_no, round_off_total as total, paid, balance,invoice_date as date_on');
            $this->db->where('customer_id', $customerName);
            $this->db->where('invoice_date >=', $from_date);
            $this->db->where('invoice_date <=', $to_date);
            $this->db->from('insider_bill');
            return $this->db->get();
        }

        else
        {
            return $this->db->select('sr_no, invoice_no, round_off_total, invoice_date, bakery_name,bakery_address, bakery_city')->order_by('sr_no','desc')
            ->from('insider_bill')->join('customers', 'customers.id = insider_bill.customer_id')->get();
        }
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

    //update bulk rows
    public function update_invoice($update_data)
    {
        return $this->db->update_batch('insider_bill', $update_data, 'sr_no');
    }
    //delete invoice
    public function delete_invoice_by_id($id)
    {
        $this->db->where('sr_no', $id);
        $this->db->delete('insider_bill');
        return $this->db->affected_rows();
    }
    //get customer ledger blance data
    public function get_ledger_balance($customer_id)
    {
        $this->db->where('customer_id', $customer_id)->order_by('id', 'ASC');
        return $this->db->get('ledger_balance');
    }

    //insert bulk rows in Balance_model
    public function ledger_balance($insert_data)
    {
        return $this->db->insert_batch('ledger_balance', $insert_data);
    }

    /*customer ledger balance table related queries */

    //get all data from table
    public function get_customer_ledger($id = null)
    {
        if($id)
        {
            return $this->db->select('customer_ledger_balance.*,customers.name,customer_ledger_balance.last_amount')
         ->from('customer_ledger_balance')->where('customer_ledger_balance.id', $id)->order_by('customer_ledger_balance.id ASC')
         ->join('customers', 'customer_ledger_balance.customer = customers.id')->get()->row();
        }else {
            
            return $this->db->select('customer_ledger_balance.*,customers.name, customers.id as customer_id')
             ->from('customer_ledger_balance')->order_by('customer_ledger_balance.id ASC')
             ->join('customers', 'customer_ledger_balance.customer = customers.id')->get();
        }

    }
    //add new entry
    public function add_customer_ledger($data)
    {
        return $this->db->insert('customer_ledger_balance', $data);
    }

    //customer ledger in date range
    public function customer_ledger_byDate($cust_id, $frm_mnth, $frm_yr,$to_mnth=null,$to_yr=null)
    {
        $frm_date = $frm_yr.'-'.$frm_mnth.'-1 00:00:00';
        $to_date =  $to_yr.'-'.$to_mnth.'-31 23:59:59';
        $to_date1 = $frm_yr.'-'.$frm_mnth.'-31 23:59:59';

        if($to_mnth && $to_yr)
        {
            // $this->db->select('customer_ledger_balance.*,customers.name,customers.address,customers.bakery_area,customers.bakery_city,customer_ledger_balance.last_amount')
            $this->db->select('customer_ledger_balance.*,customers.name,customers.address,customer_ledger_balance.last_amount')
                ->from('customer_ledger_balance')
                ->where('customer_ledger_balance.customer', $cust_id)
                ->where('customer_ledger_balance.dated >=', $frm_date)
                ->where('customer_ledger_balance.dated <=', $to_date)
                ->order_by('customer_ledger_balance.id ASC')
                ->join('customers', 'customer_ledger_balance.customer = customers.id');
                return $this->db->get();
        }
        else
        {
            // $this->db->select('customer_ledger_balance.*,customers.name,customers.address,customers.bakery_area,customers.bakery_city,customer_ledger_balance.last_amount')
            $this->db->select('customer_ledger_balance.*,customers.name,customers.address,customer_ledger_balance.last_amount')
            ->from('customer_ledger_balance')
            ->where('customer_ledger_balance.customer', $cust_id)
            ->where('customer_ledger_balance.dated >=', $frm_date)
            ->where('customer_ledger_balance.dated <=', $to_date1)
            ->order_by('customer_ledger_balance.id ASC')
            ->join('customers', 'customer_ledger_balance.customer = customers.id');
            return $this->db->get();
        }
    }

    /*ends here */

	public function insertStockEntry($entry_from, $user_id, $invoice_id, $material_id, $in_out_qnty, $stock, $json_data) {
        $data = array(
            'entry_from' => $entry_from,
            'user_id' => $user_id,
            'invoice_no' => $invoice_id,
            'material_id' => $material_id,
            'in_out_qnty' => $in_out_qnty,
            'stock_quantity' => $stock,
            'json_data' => $json_data
        );

        // Assuming 'stock' is the name of your stock table
        $this->db->insert('history', $data);
    }
	public function updateHistoryRecordByInvoiceId($entry_from, $user_id, $invoice_id, $material_id, $in_out_qnty, $stock, $json_data) {
        $data = array(
            'entry_from' => $entry_from,
            'user_id' => $user_id,
            'invoice_no' => $invoice_id,
            'material_id' => $material_id,
            'in_out_qnty' => $in_out_qnty,
            'stock_quantity' => $stock,
            'json_data' => $json_data
        );
        $this->db->where('user_id', $user_id);
        $this->db->where('invoice_no', $invoice_id);
        $this->db->where('material_id', $material_id);
        $this->db->update('history', $data);
        
        return $this->db->affected_rows();

        $result = $this->db->insert('history', $data);

        return $result;
    }

    public function getHistoryByMaterialId($material_id, $from_date, $to_date) {
        $from_datetime = $from_date. ' '.'00:00:00';
        $to_datetime = $to_date. ' '.'23:59:59';

        $this->db->select('h.*, s.quantity');
        $this->db->from('history h');
        $this->db->join('purchaser_stock s', 'h.material_id = s.materials_id');
        $this->db->where('h.material_id', $material_id);
        $this->db->where_in('h.entry_from', array(1, 2));
        $this->db->where('h.created_at >=', $from_datetime);
        $this->db->where('h.created_at <=', $to_datetime);
        $query = $this->db->get();
        $results = $query->result();
        if (!empty($results)) {
            return $results;
        } else {
            return array(); // Return an empty array if no data is found
        }

    }
    public function getHistoryByDesignId($design_id, $from_date, $to_date) {
        $from_datetime = $from_date. ' '.'00:00:00';
        $to_datetime = $to_date. ' '.'23:59:59';

        $this->db->select('h.*');
        $this->db->from('history h');
        $this->db->where('h.material_id', $design_id);
        $this->db->where_in('h.entry_from', array(3, 4,5));
        $this->db->where('h.created_at >=', $from_datetime);
        $this->db->where('h.created_at <=', $to_datetime);
        $query = $this->db->get();
        $results = $query->result();
        // print_r($results.'-');die();
        if (!empty($results)) {
            return $results;
        } else {
            return array(); // Return an empty array if no data is found
        }

    }
    function get_all_pices_history()  {
        $this->db->select('*');
        $this->db->from('history h');
        $this->db->where_in('h.entry_from', array(3, 4, 5));
        $query = $this->db->get();
        $results = $query->result();
        if (!empty($results)) {
            return $results;
        } else {
            return array(); // Return an empty array if no data is found
        }
    }
    function get_all_material_history()  {
        $this->db->select('*');
        $this->db->from('history h');
        $this->db->where_in('h.entry_from', array(1, 2));
        $query = $this->db->get();
        $results = $query->result();
        if (!empty($results)) {
            return $results;
        } else {
            return array(); // Return an empty array if no data is found
        }
    }
    function getHistoryByUserId($user_id, $from_date, $to_date)  {
        $from_datetime = $from_date. ' '.'00:00:00';
        $to_datetime = $to_date. ' '.'23:59:59';

        $this->db->select('*');
        $this->db->from('history h');
        $this->db->where('h.user_id', $user_id);
        $this->db->where('h.created_at >=', $from_datetime);
        $this->db->where('h.created_at <=', $to_datetime);
        $query = $this->db->get();
        $results = $query->result();
        if (!empty($results)) {
            return $results;
        } else {
            return array(); // Return an empty array if no data is found
        }
    }
    public function get_sell_by_id($sell_id)
    {
        // Assuming you have a database table named 'making' with appropriate columns
        $query = $this->db->get_where('insider_bill', array('sr_no' => $sell_id));

        if ($query->num_rows() > 0) {
            return $query->row_array(); // Return the result as an associative array
        } else {
            return null; // No data found
        }
    }

    public function deletHistoryByMakerInvoiceId($maker_no){
        $this->db->where('invoice_no', $maker_no);
        $result = $this->db->delete('history');
        
        return $result;
        if (!empty($results)) {
            return $results;
        } else {
            return false; // Return an empty array if no data is found
        } 
    }
}
