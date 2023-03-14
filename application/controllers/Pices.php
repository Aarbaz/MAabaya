<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pices extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Pices_model');
		$this->load->model('Stock_model');
		$this->load->model('Design_model');
		//$this->load->model('Product_model');
		$this->load->model('Material_model');
		$this->load->model('Making_model');
		$this->load->model('Customer_model');
		$this->load->model('Purchaser_model');
		$this->load->library('tcpdf');
        $this->load->model('Challan_model'); 
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->helper('url'); 
	}

    public function index(){
		if ($this->session->userdata('logged_in')) {
			$data['title'] = ucfirst('PCs Recive');
			$data['username'] = $this->session->userdata('logged_in');
			$data['products'] = $this->Pices_model->get_products_in_pcs();
			$data["matList"] = $this->Purchaser_model->get_all_material();
			$data['data_list'] = $this->Pices_model->get_products_in_pcs_list();
			$data['invoice_list'] = $this->Challan_model->get_invoice_list();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('picesList.php', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function add_new()
	{
		if($this->session->userdata('logged_in'))
        {		

			$data['title'] = 'Add Purchaser Details';
			$data['username'] = $this->session->userdata('logged_in');
			$data["custList"] = $this->Customer_model->get_mowner();
			$data['materialList'] = $this->Purchaser_model->get_all_material();
			$data['designs'] = $this->Design_model->get_all_design();

        	$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
	        $this->load->view('layout/header', $data);	        	       
	        $this->load->view('layout/menubar');
			$this->load->view('pices_add', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}	
	public function create()
	{
		$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');	
		// /$this->form_validation->set_rules('amount[]', 'Total Material', 'required');	
		//$this->form_validation->set_rules('amount_with', 'Invoice Type', 'required');	
		$validation = array(
		    array(
		        'field' => 'items[]',
		        'label' => 'Product', 
		        'rules' => 'required', 
		        "errors" => array('required' => " Please select %s. ")
		    ),
		);

		if ($this->form_validation->run() == false)
		{			
			$this->add_new();		
		}
		else
		{						
			$material = implode(',', $this->input->post('items[]'));
			$material = trim($material,',');

			$selected_ids = implode(',',$this->input->post('selected_ids'));
			$selected_ids = trim($selected_ids,',');

			$material_ids = implode(',',$this->input->post('material_ids'));
			$material_ids = trim($material_ids,',');
			
			$hsn = implode(',', $this->input->post('hsn[]'));
			$hsn = trim($hsn, ',');

			$qnty = implode(',', $this->input->post('qnty[]'));
			$qnty = trim($qnty, ',');

			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');

			$amount = implode(',', $this->input->post('amount[]'));
			$amount = trim($amount,',');
			$invoice_no = $this->input->post('invoice_no');
			$customer_id = $this->input->post('customerName');
			$transport_charges = $this->input->post('trans_charge');
			$other_charge = $this->input->post('other_charge');
			$total_taxable_amount = $this->input->post('total_tax_value');
			$igst_5_cent = $this->input->post('igst_charge');
			$cgst_charge = $this->input->post('cgst_charge');
			$sgst_charge = $this->input->post('sgst_charge');

			$cgst_per = $this->input->post('cgst_per');
			$sgst_per = $this->input->post('sgst_per');
			$igst_per = $this->input->post('igst_per');

			$total_amount = $this->input->post('total_amount');
			$total_round = $this->input->post('total_round');
			$total_word = $this->input->post('total_word');
			$sup_date = $this->input->post('sup_date');
			//$sup_place = $this->input->post('sup_place');
			$sup_other = $this->input->post('sup_other');

			$data = array(
				'master_id' => $customer_id,
				'mat_name'	=> 	$material,			
				'material_id'		=> $material_ids,
				'design_number'		=> $hsn,
				'pices'		=> $qnty,
				'average'		=> $rate,
				'material_used'	=> $amount,
			);						
			
			$insert = $this->Pices_model->create_record($data);
			$json_data = json_encode($data);
			$json_data_array = array(
					'entry_from' => 'MakingAdd',
					'json_data' => $json_data,
			);
			$insert_json_data = $this->Pices_model->create_history($json_data_array);
			// Get the product and quantity values from your input
			$selected_ids_values = explode(",", $selected_ids);
			$product_values = $selected_ids_values; // Dynamic product values
			$qnty_values = explode(",", $qnty);
			$quantity_values = ($qnty_values); // Dynamic quantity values
			
			// Prepare the data to be inserted
			$data2 = array(); 
			for ($i = 0; $i < count($product_values); $i++) {
				$data2[] = array(
					'p_design_number' => $product_values[$i],
					'stock_qty' => $quantity_values[$i]
				);
			}
			$stk_data = array();
			$material_ids_values = explode(",", $material_ids);
			$material_values = $material_ids_values;
			
			$stk_values = explode(",", $amount);
			$stock_value = ($stk_values);
			for ($i = 0; $i < count($material_values); $i++) {
				$stk_data[] = array(
					'material_id' => $material_values[$i],
					'quantity' => $stock_value[$i]
				);
			}
			// Update Stock
			
			$this->db->trans_start(); // Start a transaction to ensure data consistency
			foreach ($data2 as $row) {
				$product_id = $row['p_design_number'];
				$quantity = $row['stock_qty'];
				
				$this->db->where('p_design_number', $product_id);
				$query = $this->db->get('stock');
				$row = $query->row();
				if ($query->num_rows()) {
					// If the product exists, update the quantity value in the database
					$data2 = array(
						'stock_qty' => $row->stock_qty + $quantity
					);
					//print_r($data2);
					$this->db->where('p_design_number', $product_id);
					$this->db->update('stock', $data2);
				} else {
					// If the product does not exist, insert a new row into the database
					$this->db->insert('stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
				}
			}

			foreach ($stk_data as $row) {
				$materialId = $row['material_id'];
				$quantiti = $row['quantity'];
				$this->db->where('material_id', $materialId);
				$this->db->where('making_owner_id', $customer_id);
				$query = $this->db->get('maker_stock');
				$row = $query->row();
				if ($query->num_rows()) {
					// If the product exists, update the quantity value in the database
					$data23 = array(
						'quantity' => $row->quantity - $quantiti
					);
					$this->db->where('material_id', $materialId);
					$this->db->where('making_owner_id', $customer_id);
					$this->db->update('maker_stock', $data23);
				} else {
					// If the product does not exist, insert a new row into the database
					/* $this->db->insert('maker_stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity)); */
					$this->session->set_flashdata('error', 'error occured....');
				}
			}
			//$stock = $this->Pices_model->update_makerStock($customer_id,$material_values,$stk_data);
			$this->db->trans_complete(); // End the transaction

			if ($this->db->trans_status() === false) {
				// Handle transaction failure
				$this->session->set_flashdata('success', 'Stock Updated successfully....');
			}
			if($insert == true)
			{	
				$QuantitySold = $qnty;
				$ProductID = $material; 
				$stock = 'stock';
				$latestStock = $stock - $QuantitySold;

				$this->db->select('*');
				$this->db->from('customers');
				$this->db->where('id',$customer_id);
				$query = $this->db->get();
				$maker_name = $query->row();
				//$maker_name = json_decode($maker_name);

				$this->db->select('*');
				$this->db->from('material');
				$this->db->where_in('id', $material_values);

				$query = $this->db->get();
				$results = $query->result();
				$material_names = '';
				foreach ($results as $result) {
					$material_names .= $result->material_name . ', ';
				}

				// Remove the trailing comma and space
				$material_names = rtrim($material_names, ', ');
				$data_pdf =array(
					'customer' => $maker_name->name,
					'product_name' => $material_names,
					'hsn' => $hsn,
					'qnty' => $qnty,
					'invoice_no' => $invoice_no,
					'customer_address' => $maker_name->address,
				);


				$this->load->library('tcpdf/tcpdf.php');
				
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);				
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);				
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pieces', $data_pdf, true);			
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');
				
				$filename = $this->input->post('invoice_no').'.pdf';
				$dir = APPPATH.'/invoice/'.$data_pdf['customer'].'/';
				if(!is_dir($dir))
				{
					mkdir($dir, 0777, true);
				}
				$save_path = $dir.$filename;	
				ob_end_clean();
				$pdf->Output($save_path, 'I');			
				$pdf->Output($save_path, 'F');			
				//file_put_contents($save_path, $pdf);	
				$this->session->set_flashdata('success', 'Data Added successfully....');
				redirect('Pices/');
			}
			else
			{
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Pices/add_new'));
			}					
		}		
	}

	public function editPices($pices_id)
	{
		
  		if(!$this->session->userdata('logged_in'))
    	{
			redirect('Welcome');
		}
		elseif($this->input->post('edit_purchaser') == NULL) {
				$pice_data = $this->Pices_model->get_pices_byID($pices_id);
      			$data['title'] = 'Edit Purchaser Details';
	            $data['username'] = $this->session->userdata('logged_in');
				//$data['custList'] = $this->Pices_model->get_all_master();
				$data["custList"] = $this->Customer_model->get_mowner();
				$data['materialList'] = $this->Purchaser_model->get_all_material();
				$data['designs'] = $this->Design_model->get_all_design();
				$data["pices"] = $pice_data;
				
				/* print_r($data);
				die(); */
	            $this->load->view('layout/header', $data);
    	        $this->load->view('layout/menubar');
        		$this->load->view('pices_edit');
        		$this->load->view('layout/footer');

		}elseif($this->input->post('edit_purchaser') != NULL) {
	    	$postData = $this->input->post();
		/* var_dump($postData);
		die(); */
			$this->form_validation->set_rules('customerName', 'customer Name', 'required');	
			//$this->form_validation->set_rules('amount[]', 'Total Material', 'required');	

	      	if ($this->form_validation->run() == false)
	        {
	        	$data['title'] = 'Edit Purchaser Details';
				$data['username'] = $this->session->userdata('logged_in');
				$data['cust'] = $cust_data;

				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('Purchaser_edit');
				$this->load->view('layout/footer');
      		}
			else
			{
				$material = implode(',', $this->input->post('items[]'));
				$material = trim($material,',');
				
				$selected_ids = implode(',',$this->input->post('selected_ids'));
				$selected_ids = trim($selected_ids,',');

				$material_ids = implode(',',$this->input->post('material_ids'));
				$material_ids = trim($material_ids,',');

				/* $material_id = implode(',', $this->input->post('material_id[]'));
				$material_id = trim($material_id, ','); */
				$hsn = implode(',', $this->input->post('hsn[]'));
				$hsn = trim($hsn, ',');
				$qnty = implode(',', $this->input->post('qnty[]'));
				$qnty = trim($qnty, ',');

				$rate = implode(',', $this->input->post('rate[]'));
				$rate = trim($rate, ',');

				$amount = implode(',', $this->input->post('amount[]'));
				$amount = trim($amount,',');

				$customer_id = $this->input->post('customerName');
				$invoice_no = $this->input->post('invoice_no');
				$transport_charges = $this->input->post('trans_charge');
				$other_charge = $this->input->post('other_charge');
				$total_taxable_amount = $this->input->post('total_tax_value');
				$igst_5_cent = $this->input->post('igst_charge');
				$cgst_charge = $this->input->post('cgst_charge');
				$sgst_charge = $this->input->post('sgst_charge');

				$cgst_per = $this->input->post('cgst_per');
				$sgst_per = $this->input->post('sgst_per');
				$igst_per = $this->input->post('igst_per');

				$total_amount = $this->input->post('total_amount');
				$total_round = $this->input->post('total_round');
				$total_word = $this->input->post('total_word');
				$sup_date = $this->input->post('sup_date');
				//$sup_place = $this->input->post('sup_place');
				$sup_other = $this->input->post('sup_other');

				$data = array(
					'master_id' => $customer_id,
					'mat_name'	=> 	$material,			
					'material_id'		=> $material_ids,
					'design_number'		=> $hsn,
					'pices'		=> $qnty,
					'average'		=> $rate,
					'material_used'	=> $amount,
					/* 'total'		=> $total_amount,
					'round_off_total'  => $total_round,
					'total_in_words' => $total_word, */
					//'invoice_date' => date('Y-m-d H:i:s')
				);	
				/* print_r($data);
				die(); */
				$update = $this->Pices_model->update_records($data,$pices_id);
			// Get the product and quantity values from your input
				$selected_ids_values = explode(",", $selected_ids);
				$product_values = $selected_ids_values; // Dynamic product values
				$qnty_values = explode(",", $qnty);
				$quantity_values = ($qnty_values); // Dynamic quantity values
				//print_r($values);
				
				// Prepare the data to be inserted
				$data2 = array();
				for ($i = 0; $i < count($product_values); $i++) {
					$data2[] = array(
						'p_design_number' => $product_values[$i],
						'stock_qty' => $quantity_values[$i]
					);
				}
				$this->db->trans_start(); // Start a transaction to ensure data consistency
			foreach ($data2 as $row) {
				$product_id = $row['p_design_number'];
				$quantity = $row['stock_qty'];

				$this->db->where('p_design_number', $product_id);
				$query = $this->db->get('stock');
				$row = $query->row();
				if ($query->num_rows()) {
					// If the product exists, update the quantity value in the database
					$data2 = array(
						'stock_qty' => $row->stock_qty + $quantity
					);
					print_r($data2);
					$this->db->where('p_design_number', $product_id);
					$this->db->update('stock', $data2);
				} else {
					// If the product does not exist, insert a new row into the database
					$this->db->update('stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
				}
			}
			$this->db->trans_complete(); // End the transaction

			if ($this->db->trans_status() === false) {
				// Handle transaction failure
				$this->session->set_flashdata('success', 'Stock Updated successfully....');
			}
				if($update != -1)
				{
					$this->session->set_flashdata('success', 'Pices details updated successfully.');
					redirect('Pices');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$data['title'] = ucwords('Edit Pices Details');
					$data['username'] = $this->session->userdata('logged_in');
					$data['cust'] = $cust_data;
					$this->load->view('layout/header', $data);
					$this->load->view('layout/menubar');
					$this->load->view('Pices');
					$this->load->view('layout/footer');
				}
     		}
		}
	}

	public function deletePices()
	{
		if ($this->input->post('row_id')) {
			$id = $this->input->post('row_id');
			$upd = $this->Pices_model->delete_by_id($id);
			if ($upd > 0) {
				$resp['status'] = 'passed';
				$resp['result'] = 'Pices deleted successfully.';
			} else {
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}
}
?>