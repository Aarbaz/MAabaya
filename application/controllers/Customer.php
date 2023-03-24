<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
	/*
	 1. index() displays a list of all customers
	 2. add_new() insert new customer in DB
	 3. call edit() to modify a customer
	 */

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Customer_model');
    }

    public function index2()
    {
    	$this->load->view('add-brand');
    }
	public function index()
	{
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = 'Customers List';
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['customer'] = $this->Customer_model->get_customers();

	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('customerList', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

	public function addBrand()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('sname', 'sr Name', 'trim|required');
		$this->form_validation->set_rules('bname', 'Brand Name', 'trim|required|regex_match[/^[a-z]+$/]', array('regex_match' => 'Enter a legel brand name.'));

		if ($this->form_validation->run() == FALSE)
        {
        	$data['result'] = $this->form_validation->error_array();
        	$data['status']   = 'failed';
        }
        else
        {
            $brand_name = $this->input->post('bname');
			$config['upload_path'] = './assets/images';
			$config['allowed_types'] = 'jpg|png|gif';
			$config['max_size']     = '500';
			$config['max_width'] = '1024';
			$config['max_height'] = '768';

			$this->load->library('upload', $config);
			$this->upload->display_errors('', '');
			if ( ! $this->upload->do_upload('userfile'))
	        {
	            $data['result'] = array('error' => $this->upload->display_errors());
	            $data['status']   = 'failed';
	        }
	        else
	        {
	            $data['result'] = $this->upload->data('is_image');
	            $data['file_name'] = $this->upload->data('file_name');
	            $data['status']   = 'passed';
	        }
	    }
        echo json_encode($data);
    }
	// Add new customer form
	public function add_new()
	{
		$this->form_validation->set_rules('name', 'Bakery Name', 'required|alpha_numeric_spaces');
		/* $this->form_validation->set_rules('owner_name', 'Owner Name', 'required|alpha_numeric_spaces'); */
		/* $this->form_validation->set_rules('area', 'Area', "required|regex_match[/^[a-zA-Z0-9\.\-\,\'\s]+$/]",
			array('regex_match' =>'Please enter valid area')); */
		//$this->form_validation->set_rules('city', 'City', 'required|alpha_numeric_spaces');
		//$this->form_validation->set_rules('phone', 'Phone number', 'numeric|min_length[10]|max_length[12]');
		//$this->form_validation->set_rules('email', 'Email ID', 'valid_email');
		/* $this->form_validation->set_rules('gst', 'GST', 'trim|regex_match[/^[a-zA-Z0-9]{15}+$/]'); */

		if( $this->input->post('add_customer') != NULL )
		{
     		$postData = $this->input->post();
			if ($this->form_validation->run() == false)
			{
				$data['title'] = ucwords('Add new Customer Page');
	        	$data['username'] = $this->session->userdata('logged_in');

		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('customer_add', $data);
				$this->load->view('layout/footer');
			}
			else
			{
				$data = array(
					'name' => strtoupper($postData['name']),
					// 'owner_name' => strtoupper($postData['owner_name']),
					// 'owner_phone' => $postData['phone'],
					// 'owner_email' => $postData['email'],
					// 'bakery_gst' => $postData['gst'],
					'address' => ucwords($postData['address']),
					// 'bakery_area' => ucwords($postData['area']),
					// 'bakery_city' => ucwords($postData['city']),
					// 'last_amount' => $postData['last_amount']
				);

				$insert = $this->Customer_model->add_customer($data);
				if($insert > 0)
				{
					// alert("Customer added successfully.");
					$this->session->set_flashdata('success', 'Customer added successfully.');
					redirect('Invoice/create');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$this->load->view('layout/header', $data);
			        $this->load->view('layout/menubar');
					$this->load->view('customer_add', $data);
					$this->load->view('layout/footer');
				}
			}
     	}

		elseif($this->session->userdata('logged_in'))
        {
        	$data['title'] = 'Add new customer';
        	$data['username'] = $this->session->userdata('logged_in');

	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('customer_add', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

  	//form to UPDATE customer
  	public function edit($cust_id )
  	{
  		$cust_data = $this->Customer_model->get_customer_byID($cust_id);
  		if(!$this->session->userdata('logged_in'))
    	{
    		redirect('Welcome');
    	}

    	elseif( $cust_id && $this->input->post('edit_customer') == NULL )
    	{
    		if($cust_data)
      		{
      			$data['title'] = 'Edit Customer Details';
	            $data['username'] = $this->session->userdata('logged_in');
	            $data['cust'] = $cust_data;
	            $this->load->view('layout/header', $data);
    	        $this->load->view('layout/menubar');
        		$this->load->view('Customer_edit');
        		$this->load->view('layout/footer');
      		}
		    else
		    {
		        $data['title'] = 'Page not found';
		        $data['username'] = $this->session->userdata('logged_in');
		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
		        $this->load->view('errors/html/error_404');
		        $this->load->view('layout/footer');
		    }
		}
	    elseif( $this->input->post('edit_customer') != NULL )
	    {
	    	$postData = $this->input->post();

			$this->form_validation->set_rules('bakery_name', 'Name', 'required|alpha_numeric_spaces');
			// $this->form_validation->set_rules('owner_name', 'Owner Name', 'required|alpha_numeric_spaces');
			// $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');
			// $this->form_validation->set_rules('phone', 'Phone number', 'numeric|min_length[10]|max_length[12]');
			// $this->form_validation->set_rules('email', 'Email ID', 'valid_email');

	      	if ($this->form_validation->run() == false)
	        {
	        	$data['title'] = 'Edit Customer Details';
				$data['username'] = $this->session->userdata('logged_in');
				$data['cust'] = $cust_data;

				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('Customer_edit');
				$this->load->view('layout/footer');
      		}
			else
			{
				$data = array(
					'name' => strtoupper($postData['bakery_name']),
					// 'owner_name' => strtoupper($postData['owner_name']),
					// 'owner_phone' => $postData['phone'],
					// 'owner_email' => $postData['email'],
					// 'bakery_gst' => $postData['gst'],
					'address' => ucwords($postData['address']),
					// 'bakery_area' => ucwords($postData['area']),
					// 'bakery_city' => ucwords($postData['city']),
					// 'last_amount' => $postData['last_amount']
				);

				$cust_id = $postData['cust_id'];
				$update = $this->Customer_model->update_customer($data, $cust_id);

				if($update != -1)
				{
					$this->session->set_flashdata('success', 'Customer details updated successfully.');
					redirect('Customer');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$data['title'] = ucwords('Edit Customer Details');
					$data['username'] = $this->session->userdata('logged_in');
					$data['cust'] = $cust_data;
					$this->load->view('layout/header', $data);
					$this->load->view('layout/menubar');
					$this->load->view('Customer_edit');
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

	public function deleteCustomer()
	{
		if( $this->input->post('row_id'))
		{
			$id = $this->input->post('row_id');
			$upd = $this->Customer_model->delete_by_id($id);
			if($upd > 0)
			{
				$resp['status'] = 'passed';
				$resp['result'] = 'Customer deleted successfully.';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}

	public function add_powner(){

			$this->form_validation->set_rules('owner_name', 'Owner name', 'required');
			if ($this->form_validation->run() == false)
	{
		$this->index();
	}
	else
	{
					$owner_name = $this->input->post('owner_name');
					$id = $this->input->post('id');
					$data = array(
							'name' => $owner_name,
							'role' => '0',
					);
				 /*  print_r($id);
					die(); */
					if ($id) {
							$data = array(
									'id' => $id,
									'name' => $owner_name,
							);
							$insert = $this->Customer_model->update_customer($data,$id);
					}else{
							$insert = $this->Customer_model->add_customer($data);
					}

					if($insert == true)
		{
							$this->session->set_flashdata('success', 'Added successfully....');
			redirect('Purchaser/add_new');
		}
		else
		{
			$this->session->set_flashdata('fail', "Sorry! there was some error.");
			redirect(base_url('/index.php/Purchaser'));
					}
			}
	}

	public function add_mowner(){

			$this->form_validation->set_rules('master_name', 'Master name', 'required');
			if ($this->form_validation->run() == false)
	{
		$this->index();
	}
	else
	{
					$master_name = $this->input->post('master_name');
					$id = $this->input->post('id');
					$data = array(
							'name' => $master_name,
							'role' => '1',
					);
				 /*  print_r($id);
					die(); */
					if ($id) {
							$data = array(
									'id' => $id,
									'name' => $master_name,
							);
							$insert = $this->Customer_model->update_customer($data,$id);
					}else{
							$insert = $this->Customer_model->add_customer($data);
					}

					if($insert == true)
		{
							$this->session->set_flashdata('success', 'Added successfully....');
			redirect('Making/add_new');
		}
		else
		{
			$this->session->set_flashdata('fail', "Sorry! there was some error.");
			redirect(base_url('/index.php/Making'));
					}
			}
	}


}
