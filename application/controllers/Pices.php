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
		$this->load->model('Product_model');
		$this->load->library('form_validation');
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
			$data['data_list'] = $this->Pices_model->get_products_in_pcs_list();

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('product_in_pc.php', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function add_new()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = ucwords('Add new Material Page');
			$data['username'] = $this->session->userdata('logged_in');
			$data['purList'] = $this->Product_model->get_all_purchaser();
			$data['custList'] = $this->Challan_model->get_all_customer();
        	$data['productList'] = $this->Challan_model->get_all_products();
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
		
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');	
		$this->form_validation->set_rules('amount[]', 'Total Material', 'required');	
		//$this->form_validation->set_rules('amount_with', 'Invoice Type', 'required');	
		$validation = array(
		    array(
		        'field' => 'items[]',
		        'label' => 'Product', 
		        'rules' => 'required', 
		        "errors" => array('required' => " Please select %s. ")
		    ),
		);
	
		$this->form_validation->set_rules('total_word[]', 'Total Amount in words', 'required');	

		if ($this->form_validation->run() == false)
		{			
			$this->add_new();		
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
				'master_id' => $bakers_id,
				'material_name'	=> 	$material,			
				//'stk'		=> $stk,
				'design_number'		=> $hsn,
				'pices'		=> $qnty,
				'average'		=> $rate,
				'material_used'	=> $amount,
				'total'		=> $total_amount,
				'round_off_total'  => $total_round,
				'total_in_words' => $total_word,
				//'invoice_date' => date('Y-m-d H:i:s')
			);			

			/* $data_pdf = array(
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
			); */				

			
			$insert = $this->Pices_model->create_record($data);
			
			if($insert == true)
			{	
				/* $QuantitySold = $qnty;
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
				//file_put_contents($save_path, $pdf); */	
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
}
?>