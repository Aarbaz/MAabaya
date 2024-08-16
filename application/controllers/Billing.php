<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

	public function __construct()
    {
        parent::__construct();     
        $this->load->library('form_validation');
        $this->load->model('Challan_model');    
		$this->load->library('tcpdf');
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->helper('url');
    }

	public function index()
	{
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = ucfirst('Billing');
        	$data['username'] = $this->session->userdata('logged_in');
        	
            $this->load->view('layout/header', $data);
            // print_r($data);die();
	        $this->load->view('layout/menubar');
			$this->load->view('billing', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

    public function generate_bill(){

        $this->form_validation->set_rules('invoiceNumber', 'invoiceNumber', 'required');	
		$validation = array(
		    array(
		        'field' => 'invoiceNumber',
		        'label' => 'Product', 
		        'rules' => 'required', 
		        "errors" => array('required' => " Please select %s. ")
		    ),
		);
		$this->form_validation->set_rules($validation);	
		$this->form_validation->set_rules('date', 'date', 'required');	

		if ($this->form_validation->run() == false)
		{		
			$response['result'] = $this->form_validation->error_array();        	
        	$response['status']   = 'failed';
		}
		else
		{	
			$invoiceNumber = $this->input->post('invoiceNumber');
			$date = $this->input->post('date');

			// $data = array(
			// 	'invoiceNumber' => $invoiceNumber,
			// 	'date'	=> 	$date,			
			// 	'buyersOrderNo'	=> 	$buyersOrderNo,			
			// );			

			$data_pdf = array(
				'invoiceNumber' => $invoiceNumber,
				'date'	=> 	$date,
				'buyersOrderNo'	=> 	$buyersOrderNo,			
				'dispatchedThrough'	=> 	$dispatchedThrough,			
				'destination'	=> 	$destination,			
				'description'	=> 	$description,			
				'quantity'	=> 	$quantity,			
				'discount'	=> 	$discount,			
				'amount'	=> 	$amount,			
				'total'	=> 	$total,			
				'cgst'	=> 	$cgst,			
				'sgst'	=> 	$sgst,			
				'igst'	=> 	$igst,			
				'transportation'	=> 	$transportation,			
				'final_total'	=> 	$final_total,			
				'transport_id'	=> 	$transport_id,			
				'LR_number'	=> 	$LR_number,			
				'to_person'	=> 	$to_person,			
				'Transport'	=> 	$Transport,			
			);				
					
			// $insert = $this->Challan_model->create_challan($data);
			$insert = 1;
			if($insert)
			{				
				//require_once('tcpdf/tcpdf.php');  
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);				
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT, true);
				$pdf->SetAutoPageBreak(false, 10);  // Enable auto page break with a margin
				$pdf->SetFont('helvetica', '', 10);
				$pdf_data = $this->load->view('bill_pdf', $data_pdf, true);			
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');
			
				$filename = $this->input->post('invoiceNumber').'.pdf';
				$dir = APPPATH.'/bills/'.$data_pdf['invoiceNumber'].'/';
				if(!is_dir($dir))
				{
					mkdir($dir, 0777, true);
				}
				$save_path = $filename;		
				ob_end_clean();
				$pdf->Output($save_path, 'D');	

				$response['result'] = 'Bill created successfully';          
          		$response['redirect']   = base_url('/index.php/Challan');			
          		$response['status']   = 'passed';			
			}
			else
			{				
				$response['result'] = 'Sorry! there was some problems.';                    		
          		$response['status'] = 'failed';
			}					
		}
		echo json_encode($response);
    }
}
