<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function __construct() 
    {
        parent::__construct();     
        $this->load->library('form_validation');
		$this->load->library('tcpdf');
        $this->load->model('Challan_model'); 
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->helper('url'); 
		//$this->load->library('pdf');
    }

	public function index()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Invoice Listing';
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['invoice_list'] = $this->Challan_model->get_invoice_list();
	        $this->load->view('layout/header', $data);	        
	        $this->load->view('layout/menubar');
			$this->load->view('invoiceList', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}

	public function create()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Create new invoice';
        	$data['username'] = $this->session->userdata('logged_in');			
			$data['custList'] = $this->Challan_model->get_all_customer();
        	$data['productList'] = $this->Challan_model->get_all_products();
        	$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
	        $this->load->view('layout/header', $data);	        	       
	        $this->load->view('layout/menubar');
			$this->load->view('invoice_inside', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}	
    
	public function test()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Create new invoice';
        	$data['username'] = $this->session->userdata('logged_in');			
			$data['custList'] = $this->Challan_model->get_all_customer();
        	$data['productList'] = $this->Challan_model->get_all_products();
        	$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
	        $this->load->view('layout/header', $data);	        	       
	        $this->load->view('layout/menubar');
			$this->load->view('invoice_inside - Copy', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}	
	//add insider invoice
	public function add_insider_invoice()
	{
		
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');	
		//$this->form_validation->set_rules('region', 'Region', 'required');	
		//$this->form_validation->set_rules('amount_with', 'Invoice Type', 'required');	
		$validation = array(
		    array(
		        'field' => 'items[]',
		        'label' => 'Product', 
		        'rules' => 'required', 
		        "errors" => array('required' => " Please select %s. ")
		    ),
		);

		//$this->form_validation->set_rules($validation);	
		//$this->form_validation->set_rules('qnty[]', 'Quantity', 'required');
		//$this->form_validation->set_rules('rate[]', 'Rate', 'required');
		//$this->form_validation->set_rules('amount[]', 'Amount', 'required');				
		//$this->form_validation->set_rules('total_amt', 'Total Amount', 'required');	
		$this->form_validation->set_rules('total_word[]', 'Total Amount in words', 'required');	

		if ($this->form_validation->run() == false)
		{			
			$this->create();		
		}
		else
		{						
			$material = implode(',', $this->input->post('items[]'));
			$material = trim($material,',');

			// $stk = implode(',', $this->input->post('stk[]'));
			// $stk = trim($stk, ',');
			$hsn = implode(',', $this->input->post('hsn[]'));
			$hsn = trim($hsn, ',');
			$qnty = implode(',', $this->input->post('qnty[]'));
			$qnty = trim($qnty, ',');

			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');

			$amount = implode(',', $this->input->post('amount[]'));
			$amount = trim($amount,',');

			$bakers_id = $this->input->post('customerName');
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
				'customer_id' => $bakers_id,
				'invoice_no' => $invoice_no,
				'product_name'	=> 	$material,			
				//'stk'		=> $stk,
				'hsn'		=> $hsn,
				'qnty'		=> $qnty,
				'rate'		=> $rate,
				'amount'	=> $amount,
				'transport_charges'  => $transport_charges,
				'other_charge'  => $other_charge,
				'total_taxable_amount'  => $total_taxable_amount,
				'igst_5_cent'  => $igst_5_cent,
				'cgst_2_5_cent'  => $cgst_charge,
				'sgst_2_5_cent'  => $sgst_charge,
				'total'		=> $total_amount,
				'round_off_total'  => $total_round,
				'total_in_words' => $total_word,
				'date_of_supply'  => $sup_date,
				//'place_of_supply'  => $sup_place,
				'other_notes'  => $sup_other,
				'paid'  => '0.00',
				'balance'  => $total_round,
				'invoice_date' => date('Y-m-d H:i:s')
			);			

			$data_pdf = array(
				'customer' => $this->input->post('cust_name'),
				'customer_address' => $this->input->post('cust_adds_txt'),
				'gst' => $this->input->post('cust_gst'),
				'invoice_no' => $invoice_no,
				'product_name'	=> 	$material,			
				// /'stk'		=> $stk,
				'hsn'		=> $hsn,
				'qnty'		=> $qnty,
				'rate'		=> $rate,
				'amount'	=> $amount,
				'transport_charges'  => $transport_charges,
				'other_charge'  => $other_charge,
				'total_taxable_amount'  => $total_taxable_amount,
				'igst_5_cent'  => $igst_5_cent,
				'cgst_2_5_cent'  => $cgst_charge,
				'sgst_2_5_cent'  => $sgst_charge,

				'cgst_per'  => $cgst_per,				
				'sgst_per'  => $sgst_per,				
				'igst_per'  => $igst_per,				

				'total'		=> $total_amount,
				'round_off_total'  => $total_round,
				'total_in_words' => $total_word,
				'date_of_supply'  => $sup_date,
				//'place_of_supply'  => $sup_place,
				'other_notes'  => $sup_other
			);				

			
			$insert = $this->Challan_model->create_invoice_insider($data);
			
			if($insert == true)
			{	
				$QuantitySold = $qnty;
				$ProductID = $material; 
				$stock = 'stock';
				$latestStock = $stock - $QuantitySold;
				$data3 =array(
					
				);


				$this->load->library('tcpdf/tcpdf.php');
				
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);				
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);				
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pdf', $data_pdf, true);			
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
				$this->session->set_flashdata('success', 'Invoice created successfully....');
				redirect('Invoice/');
			}
			else
			{
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Invoice/create'));
			}					
		}		
	}


	//Download pdf invoice
	public function download_pdf($cust_name, $invoice_id )
	{
		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		elseif( $cust_name && $invoice_id )
		{			
			$pdf_file = APPPATH.'invoice/'.rawurldecode($cust_name).'/'.$invoice_id.'.pdf';
			$file = $invoice_id.'.pdf';

			if (file_exists($pdf_file))
			{
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"" );
				readfile($pdf_file);
			}
			else
			{				
				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
				redirect('Invoice');
			}
		}	
		else
		{
			$data['title'] = ucwords('Page not found');
        	$data['username'] = $this->session->userdata('logged_in');  
			$this->load->view('layout/header', $data);	       
	        $this->load->view('layout/menubar');
			$this->load->view('errors/html/error_404');
			$this->load->view('layout/footer');
		}		
	}
	// Logout from admin page//
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

	public function deleteInvoice()
	{
		$bakery_name = $this->input->post('bakery_name');
		$invoice_number = $this->input->post('invoice_number');
		
		$pdf_file = APPPATH.'invoice/'.$bakery_name.'/'.$invoice_number.'.pdf';				
		//print_r($pdf_file);
		/* if($this->input->post(' '))
		{ */			
			$id = $this->input->post('row_id');
			//$upd = 1; //$this->Challan_model->delete_invoice_by_id($id);
			//print_r($id);
			$upd = $this->Challan_model->delete_invoice_by_id($id);
			if($upd)
			{
				if (file_exists($pdf_file))
				{
					unlink($pdf_file);
				}
				$response['result'] = 'Invoice deleted successfully.';
				$response['status'] = 'passed';
			}
			else
			{
				$response['result'] = 'Sorry there was some error';
				$response['status'] = 'failed';
			}
			echo json_encode($response);
		//}
	}

	
}
