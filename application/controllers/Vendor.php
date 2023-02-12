<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {
	/*
	 1. by default index() will display all vendors 
	 2. add_new() is called to display form and validate and insert vendor into DB
	 3. pass vendor ID in edit() to update vendor details
	*/

	public function __construct() 
    {
        parent::__construct();     
        $this->load->library('form_validation');
        $this->load->model('Vendor_model');  
    }

	public function regexValidate($str)
	{
	    if (!preg_match('/^[a-z0-9\s\.]+$/i', $str) )
	    {
	        $this->form_validation->set_message('regexValidate', 'The %s field must only contain letters and/or number');
	        return FALSE;
	    }
	    else
	    {
	        return TRUE;
	    }
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = ucfirst('Vendor List');
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['vendors'] = $this->Vendor_model->get_all_vendors();
	        $this->load->view('layout/header', $data);	        
	        $this->load->view('layout/menubar');
			$this->load->view('vendorList', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	}
    
	// Add new MATERIAL form
	public function add_new()
	{
		//if new vendor is going to add
		if( $this->input->post('add_vendor') != NULL )
		{
			$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim|required|callback_regexValidate');
			$this->form_validation->set_rules('contact_name', 'Contact Person Name', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('phone1', 'Phone 1', 'trim|required|numeric');
			$this->form_validation->set_rules('phone2', 'Phone 2', 'trim|numeric');
			$this->form_validation->set_rules('email1', 'Email 1', 'trim|valid_email');
			$this->form_validation->set_rules('email2', 'Email 2', 'trim|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('gst', 'GST', 'trim|alpha_numeric|exact_length[15]');
			$this->form_validation->set_rules('pan', 'PAN', 'trim|alpha_numeric|exact_length[10]');
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('branch', 'Bank Branch', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('account', 'Account number', 'trim|integer');
			$this->form_validation->set_rules('dbt_bal', 'Debit Balance', 'trim|decimal|max_length[9]');

     		$postData = $this->input->post();
			if ($this->form_validation->run() == false)
			{			
				$data['title'] = ucwords('Add Vendor');
	        	$data['username'] = $this->session->userdata('logged_in');        	

		        $this->load->view('layout/header', $data);	       
		        $this->load->view('layout/menubar');
				$this->load->view('vendor_add', $data);
				$this->load->view('layout/footer');
			}
			else
			{
				$data = array(
					'vendor_name' => strtoupper(trim($postData['vendor_name'])),
					'contact_name' => $postData['contact_name'],
					'address' => $postData['address'],					
					'area' => $postData['area'],					
					'city' => $postData['city'],					
					'phone1' => $postData['phone1'],					
					'phone2' => $postData['phone2'],					
					'email1' => $postData['email1'],					
					'email2' => $postData['email2'],					
					'gst' => $postData['gst'],					
					'pan' => $postData['pan'],					
					'bank_name' => strtoupper($postData['bank_name']),					
					'bank_branch' => $postData['branch'],					
					'account_number' => $postData['account'],					
					'rtgs_ifsc' => $postData['rtgs'],					
					'debit_balance' => $postData['dbt_bal'] ? $postData['dbt_bal'] : 0.00,					
				);

				$insert = $this->Vendor_model->add_vendor($data);											
				if($insert > 0)
				{
					$this->session->set_flashdata('success', 'Vendor added successfully.');
					redirect('Vendor');	
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$this->load->view('layout/header', $data);	       
			        $this->load->view('layout/menubar');
					$this->load->view('vendor_add', $data);
					$this->load->view('layout/footer');
				}
			}
     	}

		elseif($this->session->userdata('logged_in'))
        {		
        	$data['title'] = ucwords('Add Vendor');
        	$data['username'] = $this->session->userdata('logged_in');
	        $this->load->view('layout/header', $data);	       
	        $this->load->view('layout/menubar');
			$this->load->view('vendor_add', $data);
			$this->load->view('layout/footer');		
		}
		else
		{
			redirect('Welcome');
		}
	} 	

	//form to UPDATE Vendor
	public function edit($vender_id )
	{
		$cust_data = $this->Vendor_model->get_vendor_byID($vender_id);	
		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		elseif( $vender_id && $this->input->post('edit_vendor') == NULL )
		{											
			$data['title'] = ucwords('Edit Vendor Details');
        	$data['username'] = $this->session->userdata('logged_in');   
        	$data['vendor'] = $cust_data;    	
	        $this->load->view('layout/header', $data);	       
	        $this->load->view('layout/menubar');
			$this->load->view('vendor_edit');
			$this->load->view('layout/footer');			
		}	

		elseif( $this->input->post('edit_vendor') != NULL )
		{  		

			$this->form_validation->set_rules('vendor_name', 'Vendor Name', 'trim|required|callback_regexValidate');
			$this->form_validation->set_rules('contact_name', 'Contact Person Name', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('phone1', 'Phone 1', 'trim|required|numeric');
			$this->form_validation->set_rules('phone2', 'Phone 2', 'trim|numeric');
			$this->form_validation->set_rules('email1', 'Email 1', 'trim|valid_email');
			$this->form_validation->set_rules('email2', 'Email 2', 'trim|valid_email');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('gst', 'GST', 'trim|alpha_numeric|exact_length[15]');
			$this->form_validation->set_rules('pan', 'PAN', 'trim|alpha_numeric|exact_length[10]');
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('branch', 'Bank Branch', 'trim|alpha_numeric_spaces');
			$this->form_validation->set_rules('account', 'Account number', 'trim|integer');
			$this->form_validation->set_rules('dbt_bal', 'Debit Balance', 'trim|decimal|max_length[9]');			
     		
			if ($this->form_validation->run() == false)
			{			
				$data['title'] = ucwords('Edit Vendor Details');
	        	$data['username'] = $this->session->userdata('logged_in');  
	        	$data['vendor'] = $cust_data; 
	        
		        $this->load->view('layout/header', $data);	       
		        $this->load->view('layout/menubar');
				$this->load->view('vendor_edit', $data);
				$this->load->view('layout/footer');
			}

			else
			{
     			$postData = $this->input->post();   
				$data = array(
					'vendor_name' => strtoupper(trim($postData['vendor_name'])),
					'contact_name' => $postData['contact_name'],
					'address' => $postData['address'],					
					'area' => $postData['area'],					
					'city' => $postData['city'],					
					'phone1' => $postData['phone1'],					
					'phone2' => $postData['phone2'],					
					'email1' => $postData['email1'],					
					'email2' => $postData['email2'],					
					'gst' => $postData['gst'],					
					'pan' => $postData['pan'],					
					'bank_name' => strtoupper($postData['bank_name']),					
					'bank_branch' => $postData['branch'],					
					'account_number' => $postData['account'],					
					'rtgs_ifsc' => $postData['rtgs'],	
					'debit_balance' => $postData['dbt_bal'] ? $postData['dbt_bal'] : 0.00,	
				);

				$vender_id = $postData['vender_id'];
				$update = $this->Vendor_model->update_vendor($data, $vender_id);
				
				if($update != -1)
				{
					$this->session->set_flashdata('success', 'Vendor updated successfully.');
					redirect('Vendor');	
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$data['title'] = ucwords('Edit Vendor Details');
		        	$data['username'] = $this->session->userdata('logged_in');  
		        	$data['vendor'] = $cust_data;
					$this->load->view('layout/header', $data);	       
			        $this->load->view('layout/menubar');
					$this->load->view('vendor_edit', $data);
					$this->load->view('layout/footer');
				}
			}
     	}

	}

	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

	public function delete_vendor()
	{
		if($this->input->post('row_id'))
		{			
			$id = $this->input->post('row_id');
			$upd = $this->Vendor_model->delete_by_id($id);
			if($upd > 0)
			{
				$resp['status'] = 'passed';
				$resp['result'] = 'Vendor deleted successfully.';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}

}
