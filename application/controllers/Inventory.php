<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

	public function index()
	{
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = ucfirst('Dashboard');
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['customer_count'] = $this->Dashboard_model->get_all_customer();
        	$data['purchaser_count'] = $this->Dashboard_model->get_all_purchaser();
        	$data['product_count'] = $this->Dashboard_model->get_all_products();
        	$data['material_count'] = $this->Dashboard_model->get_all_material();
        	$data['vendor_count'] = $this->Dashboard_model->get_all_vendors();
        	$data['order_sum'] = $this->Dashboard_model->get_sum_count()->result();
	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('inventory', $data);
			// $this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}
	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$data['message_display'] = 'Successfully Logout';
		redirect('Welcome');
	}
}
