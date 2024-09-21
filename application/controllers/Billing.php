<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

	public function __construct()
    {
        parent::__construct();     
        $this->load->library('form_validation');
        $this->load->model('Billing_model');    
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
		// $validation = array(
		//     array(
		//         'field' => 'quantity[]',
		//         'label' => 'quantity[]', 
		//         'rules' => 'required', 
		//         "errors" => array('required' => " Please select %s. ")
		//     ),
		// );
		// $this->form_validation->set_rules($validation);	
		// $this->form_validation->set_rules('date', 'date', 'required');	

		// if ($this->form_validation->run() == false)
		// {		
		// 	$response['result'] = $this->form_validation->error_array();        	
        // 	$response['status']   = 'failed';
		// }
		// else
		// {	
			$invoiceNumber = $this->input->post('invoiceNumber');
			$date = $this->input->post('date');
			$myShop_name = $this->input->post('myShop_name');
			$myShop_add = $this->input->post('myShop_add');
			$myShop_gst = $this->input->post('myShop_gst');
			$buyersOrderNo = $this->input->post('buyersOrderNo');
			$dispatchedThrough = $this->input->post('dispatchedThrough');
			$destination = $this->input->post('destination');
			$consignee_date = $this->input->post('consignee_date');
			$consignee_name = $this->input->post('consignee_name');
			$consignee_add = $this->input->post('consignee_add');
			$consignee_gst = $this->input->post('consignee_gst');
			$description = implode(",",$this->input->post("desc[]"));
			$quantity = implode(",",$this->input->post("quantity[]"));
			$rate = implode(",",$this->input->post("rate[]"));
			$discount = implode(",",$this->input->post("discount[]"));
			$amount = implode(",",$this->input->post("amount[]"));
			$total = $this->input->post('total');
			$cgstchnage = $this->input->post('cgstchnage');
			$sgstchnage = $this->input->post('sgstchnage');
			$igstchnage = $this->input->post('igstchnage');
			$cgst = $this->input->post('cgst');
			$igst = $this->input->post('igst');
			$sgst = $this->input->post('sgst');
			$transportation = $this->input->post('transportation');
			$grandTotal = $this->input->post('grandTotal');
			$transport_id = $this->input->post('transportId');
			$LR_number = $this->input->post('lrNo');
			$to_person = $this->input->post('to');
			$Transport = $this->input->post('transport');

			// $data = array(
			// 	'invoiceNumber' => $invoiceNumber,
			// 	'date'	=> 	$date,			
			// 	'buyersOrderNo'	=> 	$buyersOrderNo,			
			// );			

			$data_pdf = array(
				'invoiceNumber' => $invoiceNumber,
				'date'	=> 	$date,
				'myShop_name'	=> 	$myShop_name,
				'myShop_add'	=> 	$myShop_add,
				'myShop_gst'	=> 	$myShop_gst,
				'buyersOrderNo'	=> 	$buyersOrderNo,			
				'dispatchedThrough'	=> 	$dispatchedThrough,			
				'destination'	=> 	$destination,			
				'consignee_date'	=> 	$consignee_date,			
				'consignee_name'	=> 	$consignee_name,			
				'consignee_add'	=> 	$consignee_add,			
				'consignee_gst'	=> 	$consignee_gst,			
				'description'	=> 	$description,			
				'quantity'	=> 	$quantity,			
				'rate'	=> 	$rate,			
				'discount'	=> 	$discount,			
				'amount'	=> 	$amount,			
				'total'	=> 	$total,			
				'cgstchnage'	=> 	$cgstchnage,			
				'sgstchnage'	=> 	$sgstchnage,			
				'igstchnage'	=> 	$igstchnage,			
				'cgst'	=> 	$cgst,			
				'sgst'	=> 	$sgst,			
				'igst'	=> 	$igst,			
				'transportation'	=> 	$transportation,			
				'grandTotal'	=> 	$grandTotal,			
				'transport_id'	=> 	$transport_id,			
				'LR_number'	=> 	$LR_number,			
				'to_person'	=> 	$to_person,			
				'Transport'	=> 	$Transport,			
			);				

		
					// print_r($data_pdf);die();
			$insert = $this->Billing_model->create_bill($data_pdf);
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
		// }
		echo json_encode($response);
    }
}
