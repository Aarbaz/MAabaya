<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challan extends CI_Controller {
	/*
	 1. index() shows available challans in DB 
	 2. create() shows a form to create a challan for any customer
	 3. addChallan() insert new challan record into DB
	*/

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

	public function create()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Create new challan';
        	$data['username'] = $this->session->userdata('logged_in');			
			$data['custList'] = $this->Challan_model->get_all_customer();
        	$data['productList'] = $this->Challan_model->get_all_products();
        	$data['challan_no'] = $this->Challan_model->get_last_challan();
	        $this->load->view('layout/header', $data);	        	       
	        $this->load->view('layout/menubar');
			$this->load->view('challan', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}
		
	public function index()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Challan list';
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['challan_list'] = $this->Challan_model->get_challan_list();
	        $this->load->view('layout/header', $data);	       
	        $this->load->view('layout/menubar');
			$this->load->view('challanList', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}
    
	// Add new challan, validate and insert
	public function addChallan()
	{
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');	
		$validation = array(
		    array(
		        'field' => 'items[]',
		        'label' => 'Product', 
		        'rules' => 'required', 
		        "errors" => array('required' => " Please select %s. ")
		    ),
		);
		$this->form_validation->set_rules($validation);	
		$this->form_validation->set_rules('qnty[]', 'Quantity', 'required');
		$this->form_validation->set_rules('rate[]', 'Rate', 'required');
		$this->form_validation->set_rules('total_amt', 'Total Amount', 'required');	
		$this->form_validation->set_rules('total_word', 'Total Amount in words', 'required');	

		if ($this->form_validation->run() == false)
		{		
			$response['result'] = $this->form_validation->error_array();        	
        	$response['status']   = 'failed';
		}
		else
		{	
			$material = implode(',', $this->input->post('items[]'));
			$material = trim($material,',');
			$qnty = implode(',', $this->input->post('qnty[]'));
			$qnty = trim($qnty, ',');
			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');
			$amount = implode(',', $this->input->post('amount[]'));
			$amount = trim($amount,',');
			$bakers_id = $this->input->post('customerName');

			$data = array(
				'customer_id' => $bakers_id,
				'challan_no' => $this->input->post('chalan_no'),
				'material'	=> 	$material,			
				'qnty'		=> $qnty,
				'rate'		=> $rate,
				'amount'	=> $amount,
				'total'		=> $this->input->post('total_amt'),
				'paid'      => '0.00',
				'balance'   => $this->input->post('total_amt'),
				'total_in_words' => $this->input->post('total_word'),
			);			

			$data_pdf = array(
				'customer' => $this->input->post('cust_name'),
				'customer_address' => $this->input->post('cust_adds'),
				'challan_no' => $this->input->post('chalan_no'),
				'material'	=> 	$material,			
				'qnty'		=> $qnty,
				'rate'		=> $rate,
				'amount'	=> $amount,
				'total'		=> $this->input->post('total_amt'),				
				'total_in_words' => $this->input->post('total_word'),
				'created_on' => date('Y-m-d H:i:s')
			);	
					
			$insert = $this->Challan_model->create_challan($data);
			if($insert)
			{				
				//require_once('tcpdf/tcpdf.php');  
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);				
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				$pdf->SetFont('helvetica', '', 10);
				$pdf_data = $this->load->view('challan_pdf', $data_pdf, true);			
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');
			
				$filename = $this->input->post('chalan_no').'.pdf';
				$dir = APPPATH.'/challan/'.$data_pdf['customer'].'/';
				if(!is_dir($dir))
				{
					mkdir($dir, 0777, true);
				}
				$save_path = $dir.$filename;		
				ob_end_clean();
				$pdf->Output($save_path, 'I');	

				$response['result'] = 'Challan created successfully';          
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

	//Download pdf challan
	public function download_pdf($cust_name, $challan_id )
	{
		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		elseif( $cust_name && $challan_id )
		{			
			$pdf_file = APPPATH.'challan/'.rawurldecode($cust_name).'/'.$challan_id.'.pdf';
			//die;
			$file = $challan_id.'.pdf';

			if (file_exists($pdf_file))
			{
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"" );
				readfile($pdf_file);
			}
			else
			{				
				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
				redirect('Challan');
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

	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

	public function deleteChallan()
	{
		$bakery_name = $this->input->post('bakery_name');
		$challan_no = $this->input->post('challan_no');

		$pdf_file = APPPATH.'challan/'.$bakery_name.'/'.$challan_no.'.pdf';			

		if($this->input->post('row_id'))
		{			
			$id = $this->input->post('row_id');
			$upd = $this->Challan_model->delete_by_id($id);
			if($upd )
			{
				if (file_exists($pdf_file))
				{
					unlink($pdf_file);
				}
				$response['result'] = 'Challan Deleted successfully.';
          		$response['status']   = 'passed';
			}
			else
			{
				$response['result'] = 'Sorry, there is some problems';
          		$response['status']   = 'failed';
			}
			echo json_encode($response);
		}
	}

}
