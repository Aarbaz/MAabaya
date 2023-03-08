<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
        	
        	// $data['order_sum'] = $this->Dashboard_model->get_sum_count()->result();

					$data['pur_customer'] = $this->Dashboard_model->get_pur_customer();
					$data['mak_customer'] = $this->Dashboard_model->get_mak_customer();
					$data['sel_customer'] = $this->Dashboard_model->get_sel_customer();
					$data['all_material'] = $this->Dashboard_model->get_all_newmaterial();

	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('dashboard', $data);
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
